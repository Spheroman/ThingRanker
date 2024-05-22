<?php
// Database connection parameters
$dsn = "mysql:host=localhost;dbname=mydb";
$username = "cyrus";
$password = "1234";

// PDO connection
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

