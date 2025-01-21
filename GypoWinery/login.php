<?php
// Kapcsolódás az adatbázishoz
$servername = "localhost";
$username = "root"; // vagy a megfelelő felhasználó
$password = ""; // vagy a megfelelő jelszó
$dbname = "gypowinery";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Bejelentkezési adatkezelés
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $jelszo = $_POST['jelszo'];

    // SQL lekérdezés a felhasználó lekérdezésére
    $sql = "SELECT * FROM login WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Jelszó ellenőrzése
        if (password_verify($jelszo, $row['jelszo'])) {
            // Bejelentkezés sikeres
            echo "Bejelentkezve mint " . $row['keresztnev'];
            // Példa: átirányítás másik oldalra
            header("Location: index.html");
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "Nincs ilyen felhasználó!";
    }

    $conn->close();
}
?>
