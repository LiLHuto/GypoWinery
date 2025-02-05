<?php
session_start();

// Adatbázis kapcsolat beállítások
$host = 'localhost';
$dbname = 'gypowinery';
$username = 'root';
$password = '';

try {
    // PDO kapcsolat létrehozása
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Hibakezelés beállítása
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Kapcsolódási hiba: " . $e->getMessage());
}

// Bejelentkezés ellenőrzése
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

// Admin jogosultság ellenőrzése
function check_admin() {
    if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
        header("Location: admin_borok.php");
        exit;
    }
}
?>