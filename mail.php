<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/database/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to validate email address
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $domain = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($domain, "MX")) {
        return false;
    }
    return true;
}

// Function to generate verification token
function generateVerificationToken() {
    return bin2hex(random_bytes(32));
}

// Function to send welcome email
function sendWelcomeEmail($recipientEmail, $recipientName, $verificationToken) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jamal.kiki@strathmore.edu';   // Your Gmail
        $mail->Password   = 'ulkq vvwy ylgd asjs';         // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Send to the actual registering user
        $mail->setFrom('jamal.kiki@strathmore.edu', 'ICS2.2 Team');
        $mail->addAddress($recipientEmail, $recipientName);

        // Verification link
        $verificationLink = "http://localhost/myemail/verify.php?token=" . urlencode($verificationToken) . "&email=" . urlencode($recipientEmail);

        // HTML content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ICS2.2 - Complete Your Registration';
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .button { 
                    background-color: #4CAF50; 
                    color: white; 
                    padding: 12px 24px; 
                    text-decoration: none; 
                    border-radius: 4px; 
                    display: inline-block;
                    margin: 10px 0;
                }
                .footer { margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Welcome to ICS2.2!</h2>
                </div>
                <div class='content'>
                    <p>Hello <strong>" . htmlspecialchars($recipientName) . "</strong>,</p>
                    <p>You requested an account on ICS2.2. Click the button below to complete registration:</p>
                    <p><a href='" . $verificationLink . "' class='button'>Complete Registration</a></p>
                    <p>Or copy and paste this link in your browser:<br>
                    <small>" . $verificationLink . "</small></p>
                    <p>If you didn't request this account, please ignore this email.</p>
                    <p>Best regards,<br>The ICS2.2 Team</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message. Please do not reply.</p>
                </div>
            </div>
        </body>
        </html>";

        // Plain text version
        $mail->AltBody = "Hello " . $recipientName . ",\n\n" .
                         "You requested an account on ICS2.2. Complete your registration using this link:\n" .
                         $verificationLink . "\n\n" .
                         "If you didn't request this account, please ignore this email.\n\n" .
                         "Best regards,\nThe ICS2.2 Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

// Main execution - Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');

    if (empty($email) || empty($name)) {
        echo "❌ Error: Email and name are required.";
        exit;
    }

    if (!validateEmail($email)) {
        echo "❌ Error: Invalid email address or domain.";
        exit;
    }

    // Check for duplicates
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            echo "❌ Error: An account with this email already exists.";
            exit;
        }
    } catch (PDOException $e) {
        echo "❌ Database error: " . $e->getMessage();
        exit;
    }

    // Generate token & store user
    $verificationToken = generateVerificationToken();
    try {
        $stmt = $conn->prepare("
            INSERT INTO users (username, email, password, verification_token, is_verified, created_at) 
            VALUES (?, ?, ?, ?, false, NOW())
        ");
        $placeholderPassword = password_hash('temporary', PASSWORD_DEFAULT);
        $stmt->execute([$name, $email, $placeholderPassword, $verificationToken]);
        echo "✅ User registered successfully! ";
    } catch (PDOException $e) {
        echo "❌ Registration failed: " . $e->getMessage();
        exit;
    }

    // Send email to the actual user
    if (sendWelcomeEmail($email, $name, $verificationToken)) {
        echo "📧 Welcome email sent successfully to " . htmlspecialchars($email);
    } else {
        echo "📧 User registered but email sending failed. Please try again later.";
    }

} else {
    // Simple test form
    echo "
    <h3>Test Welcome Email System</h3>
    <form method='POST'>
        <p>
            <label>Name:</label><br>
            <input type='text' name='name' required placeholder='Enter full name'>
        </p>
        <p>
            <label>Email:</label><br>
            <input type='email' name='email' required placeholder='Enter email address'>
        </p>
        <p>
            <button type='submit'>Send Welcome Email</button>
        </p>
    </form>
    ";
}
?>
