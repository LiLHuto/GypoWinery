<?php


// Adatbázis kapcsolat beállítások
$host = 'localhost'; // Az adatbázis host
$dbname = 'gypowinery'; // Az adatbázis neve
$username = 'root'; // Az adatbázis felhasználó
$password = ''; // Az adatbázis jelszó

try {
    // PDO kapcsolat létrehozása
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Hibakezelés beállítása
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Hiba esetén kiírjuk az üzenetet és kilépünk
    die("Kapcsolódási hiba: " . $e->getMessage());
}

// Bejelentkezés ellenőrzése
if (!isset($_SESSION['user_id'])) {
    // Ha a felhasználó nincs bejelentkezve, átirányítjuk a bejelentkező oldalra
    header('Location: login.php');
    exit(); // Ne folytassa az oldal betöltését
}
?>
