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
    $telefon = $_POST['telefonszam'];    // Telefonszám
    $jelszo = $_POST['jelszo'];          // Jelszó

    // Jelszó titkosítása
    $hashedPassword = password_hash($jelszo, PASSWORD_DEFAULT);

    // SQL lekérdezés az adatok beszúrására
    $sql = "INSERT INTO login (vezeteknev, keresztnev, email, telefonszam, jelszo)
            VALUES ('$vezeteknev', '$keresztnev', '$email', '$telefon', '$hashedPassword')";

    // Lekérdezés futtatása és eredmény kezelése
    if ($conn->query($sql) === TRUE) {
        // Sikeres regisztráció után átirányítás a login.html oldalra
        header("Location: login.php");
        exit();
    } else {
        echo "Hiba történt: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="hu">

<head>
  <title>GypoWinery Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
  <div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">
    <div class="signup">
      <form action="register.php" method="POST">
        <label for="chk" aria-hidden="true">Sign up</label>
        <input type="text" name="vezeteknev" placeholder="Last Name" required="">
        <input type="text" name="keresztnev" placeholder="First Name" required="">
        <input type="email" name="email" placeholder="Email" required="">
        <input type="tel" name="telefonszam" placeholder="Phone Number" required="">
        <input type="password" name="jelszo" placeholder="Password" required="">
        <div class="button-container">
            <button type="submit">Sign up</button>
        </div>
    </form>
    
    </div>
  </div>
</body>

</html>

