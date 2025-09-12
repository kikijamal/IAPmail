<?php
// TEMPORARY TEST INDEX – no classes

// Simple signup form (directly in index.php)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup Test</title>
</head>
<body>
    <h1>Signup Test Form</h1>
    <form action="forms/signup_process.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
