<?php
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

// Adatok regisztrálása
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vezeteknev = $_POST['vezeteknev'];  // Vezetéknév
    $keresztnev = $_POST['keresztnev'];  // Keresztnév
    $email = $_POST['email'];            // E-mail
    $telefon = $_POST['telefonszam'];        // Telefonszám
    $jelszo = $_POST['jelszo'];          // Jelszó

    // Jelszó titkosítása
    $hashedPassword = password_hash($jelszo, PASSWORD_DEFAULT);

    // SQL lekérdezés az adatok beszúrására
    $sql = "INSERT INTO login (vezeteknev, keresztnev, email, telefonszam, jelszo)
            VALUES ('$vezeteknev', '$keresztnev', '$email', '$telefon', '$hashedPassword')";

    // Lekérdezés futtatása és eredmény kezelése
    if ($conn->query($sql) === TRUE) {
        echo "Sikeres regisztráció!";
    } else {
        echo "Hiba történt: " . $conn->error;
    }
}

$conn->close();
?>
