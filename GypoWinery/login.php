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
  <style>
    body {
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  font-family: 'Jost', sans-serif;
  background: linear-gradient(to bottom, #0f0c29, #5a2a4e, #24243e);
}

.main {
  width: 350px;
  height: 500px;
  background: red;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 5px 20px 50px #000;
  position: relative;
}

#chk {
  display: none;
}

.signup,
.login {
  position: absolute;
  width: 100%;
  height: 100%;
  transition: transform 0.8s ease-in-out;
}

.signup {
  background: #fff;
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.login {
  background: #eee;
  z-index: 1;
  transform: translateY(-100%);
  display: flex;
  flex-direction: column;
  align-items: center;
}

#chk:checked ~ .login {
  transform: translateY(0);
  z-index: 2;
}

#chk:checked ~ .signup {
  transform: translateY(100%);
  z-index: 1;
}

label {
  color: #573b8a;
  font-size: 2.3em;
  display: flex;
  justify-content: center;
  margin: 50px 0;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.5s ease-in-out;
}

a{
  text-decoration: none;
}

input {
  width: 80%;
  background: #e0dede;
  margin: 10px auto;
  padding: 10px;
  border: none;
  outline: none;
  border-radius: 5px;
  display: block;
}

.button-container {
  display: flex;
  justify-content: center;
  width: 100%;
}

button {
  width: 80%;
  background: #573b8a;
  color: #fff;
  padding: 10px;
  border: none;
  outline: none;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
  text-align: center;
}

button:hover {
  background: #6d44b8;
}

  </style>
</head>

<body>
  <div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">
    <div class="signup">
      <form action="login.php" method="POST">
        <label for="chk" aria-hidden="true"> <a href="admin_login.php">Login</a></label>
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

