<?php
$host = "localhost"; // Change if your DB is on a different host
$dbname = "gypowinery"; // Database name from your SQL file
$username = "root"; // Change if using a different MySQL user
$password = ""; // Update if a password is set

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
