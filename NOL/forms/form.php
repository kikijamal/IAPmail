<?php

class form
{
    // Signup form
    public function signup()
    {
        ?>
        <form action="/myemail/NOL/forms/signup_process.php" method="post">

            <h2>Sign Up</h2>

            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Sign Up">
            <a href="./">Already have an account? Log in</a>
        </form>
        <?php
    }

    // Login form
    public function login()
    {
        ?>
        <form method="post" action="">
            <h2>Login</h2>

            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Log In">
            <a href="./">Don't have an account? Sign up</a>
        </form>
        <?php
    }
}
?>
