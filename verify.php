<?php
require __DIR__ . '/database.php';

$message = '';
$messageType = 'error';

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    
    try {
        // Find user with matching token and email
        $stmt = $conn->prepare("
            SELECT id, username, is_verified 
            FROM users 
            WHERE email = ? AND verification_token = ?
        ");
        $stmt->execute([$email, $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if ($user['is_verified']) {
                $message = 'Your account has already been verified. You can now log in.';
                $messageType = 'info';
            } else {
                // Verify the user
                $updateStmt = $conn->prepare("
                    UPDATE users 
                    SET is_verified = true, verification_token = NULL 
                    WHERE id = ?
                ");
                $updateStmt->execute([$user['id']]);
                
                $message = 'Congratulations! Your account has been successfully verified. You can now log in to ICS2.2.';
                $messageType = 'success';
            }
        } else {
            $message = 'Invalid verification token or email. The link may have expired or been used already.';
            $messageType = 'error';
        }
        
    } catch (PDOException $e) {
        $message = 'Database error occurred during verification. Please try again later.';
        $messageType = 'error';
        error_log("Verification error: " . $e->getMessage());
    }
} else {
    $message = 'Invalid verification link. Missing token or email parameter.';
    $messageType = 'error';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - ICS2.2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: center;
        }
        .success {
            color: #4CAF50;
            border: 2px solid #4CAF50;
            background-color: #f0fff0;
        }
        .error {
            color: #f44336;
            border: 2px solid #f44336;
            background-color: #fff0f0;
        }
        .info {
            color: #2196F3;
            border: 2px solid #2196F3;
            background-color: #f0f8ff;
        }
        .message {
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Verification</h1>
        
        <?php if ($messageType === 'success'): ?>
            <div class="icon">✅</div>
        <?php elseif ($messageType === 'info'): ?>
            <div class="icon">ℹ️</div>
        <?php else: ?>
            <div class="icon">❌</div>
        <?php endif; ?>
        
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        
        <?php if ($messageType === 'success' || $messageType === 'info'): ?>
            <a href="login.php" class="button">Go to Login</a>
        <?php else: ?>
            <a href="register.php" class="button">Try Registration Again</a>
        <?php endif; ?>
    </div>
</body>
</html>