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
      <form action="login.php" method="POST">
        <label for="chk" aria-hidden="true">Login</label>
        <input type="email" name="email" placeholder="Email" required="">
        <input type="password" name="jelszo" placeholder="Password" required="">
        <div class="button-container">
            <button type="submit">Login</button> 
           
        </div>
        <p>Nincs még fiókód? <a href="register.php">Regisztrálj!</a></p>
    </form>
    </div>
  </div>
</body>

</html>

