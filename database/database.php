<?php
$host = "localhost";    // Your PostgreSQL server
$port = "5432";         // Default Postgres port
$dbname = "mydb";  // The database you created in pgAdmin
$user = "postgres";     // Your Postgres username
$password = "406408";  // Replace with your password

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>
