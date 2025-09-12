<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load DB connection + PHPMailer
require __DIR__ . '/../../db.php';
require __DIR__ . '/../../vendor/autoload.php';


// Get input from signup form
$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $email && $password) {
    try {
        // 1️⃣ Insert new user into database
        $stmt = $conn->prepare("
            INSERT INTO users (username, email, password) 
            VALUES (:username, :email, :password)
        ");
        $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT) // secure storage
        ]);

        // 2️⃣ Send welcome email
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jamal.kiki@strathmore.edu';   // your Gmail
        $mail->Password   = 'ulkq vvwy ylgd asjs';         // your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('jamal.kiki@strathmore.edu', 'Jamal Kiki');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = "Welcome to MyEmail App!";
        $mail->Body    = "Hello <b>$username</b>,<br><br>Welcome to our app 🎉<br>We’re excited to have you!";
        $mail->AltBody = "Hello $username, Welcome to our app 🎉 We’re excited to have you!";

        $mail->send();

        echo "✅ Signup successful! Email sent to $email";
    } catch (Exception $e) {
        echo "❌ Signup failed. Error: " . $e->getMessage();
    }
} else {
    echo "❌ Please fill all fields.";
}



