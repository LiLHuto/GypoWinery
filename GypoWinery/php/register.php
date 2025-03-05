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
  <link rel="stylesheet" type="text/css" href="../css/login.css">
  <style>
      /* A kép tartója (kitölti a piros területet) */
      #signupImageContainer {
          display: none;
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 350px; /* Állítsd be a kívánt szélességet */
          height: 500px; /* Állítsd be a kívánt magasságot */
          overflow: hidden;
          background: none;
          padding: 0;
          border: none;
          box-shadow: none;
      }

      /* Maga a kép, hogy teljesen kitöltse a piros részt */
      #signupImageContainer img {
          width: 100%;
          height: 100%;
          object-fit: cover; /* Teljesen kitölti a területet, de nem torzul */
      }
  </style>
</head>

<div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">
    <div class="signup">
        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <label for="chk" aria-hidden="true" onclick="showImage()">Sign up</label>
            <input type="text" name="vezeteknev" placeholder="Last Name" required="">
            <input type="text" name="keresztnev" placeholder="First Name" required="">

            <!-- Email mező: ellenőrzi, hogy tartalmaz-e '@' -->
            <input type="email" id="email" name="email" placeholder="Email" required="">

            <!-- Telefonszám: csak számokat enged, min. 8 és max. 15 karakter -->
            <input type="tel" name="telefonszam" placeholder="Phone Number" required="" 
            pattern="[0-9]{8,15}" title="Csak számokat használhatsz, minimum 8 és maximum 15 hosszúságban!">

            <!-- Jelszó: legalább 8 karakter, min. 2 szám -->
            <input type="password" id="jelszo" name="jelszo" placeholder="Password" required="" 
                   pattern="^(?=.*\d.*\d)[A-Za-z\d]{8,}$"
                   title="A jelszónak legalább 8 karakter hosszúnak kell lennie és minimum 2 számot kell tartalmaznia!">

            <div class="button-container">
                <button type="submit">Sign up</button>
            </div>
        </form>
    </div>
</div>

<!-- A megjelenítendő kép -->
<div id="signupImageContainer">
    <img src="../kepek/dicaprio.png" alt="Signup Image">
</div>

<script>
    function validateForm() {
        let email = document.getElementById("email").value;
        let password = document.getElementById("jelszo").value;

        // Email ellenőrzése
        if (!email.includes("@")) {
            alert("Az e-mail címnek tartalmaznia kell egy '@' jelet!");
            return false;
        }

        // Jelszó ellenőrzése
        let passwordRegex = /^(?=.*\d.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordRegex.test(password)) {
            alert("A jelszónak legalább 8 karakter hosszúnak kell lennie és minimum 2 számot kell tartalmaznia!");
            return false;
        }

        return true;
    }

    // Kép megjelenítése kattintásra
    function showImage() {
        document.getElementById("signupImageContainer").style.display = "block";
    }
</script>

</body>
</html>