<?php
session_start();  // Kezdjük el a session-t

// Kapcsolódás az adatbázishoz
$servername = "localhost";  // Vagy a megfelelő host, pl. 127.0.0.1
$username = "root";         // A megfelelő felhasználónév
$password = "";             // A megfelelő jelszó
$dbname = "gypowinery";     // Az adatbázis neve

// Kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ha POST kérés érkezik
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $jelszo = $_POST['jelszo'];

    // SQL lekérdezés a felhasználó ellenőrzésére
    $sql = "SELECT * FROM login WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Ha találunk felhasználót, ellenőrizzük a jelszót
        $row = $result->fetch_assoc();
        if (password_verify($jelszo, $row['jelszo'])) {
            // Bejelentkezés sikeres, mentsük a session változókat
            $_SESSION['user_id'] = $row['ID']; // user_id beállítása
            $_SESSION['email'] = $row['email'];
            
            // Átirányítás a főoldalra vagy bármilyen oldalra, amit szeretnél
            header("Location: index.php");
            exit();
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "Nincs ilyen felhasználó!";
    }
}

$conn->close();
