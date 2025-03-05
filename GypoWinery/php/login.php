<?php
session_start();  // Kezdjük el a session-t

// Kapcsolódás az adatbázishoz
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "gypowinery";     

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
        $row = $result->fetch_assoc();
        if (password_verify($jelszo, $row['jelszo'])) {
            // Bejelentkezés sikeres, session változók beállítása
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['email'] = $row['email'];
            // 🔥 Üzenet eltárolása session-ben
            $_SESSION['login_message'] = "Sikeresen bejelentkeztél!";

            // Átirányítás a főoldalra
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
  <link rel="stylesheet" type="text/css" href="../css/login.css">
  
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
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 5px 20px 50px #000;
      position: relative;
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

    /* ✅ Bejelentkezés Popup */
    .popup-container {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #5a2a4e; /* LILA háttér */
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
        color: white;
        z-index: 9999;
        width: 300px;
        animation: fadeIn 0.5s ease-in-out;
    }

    /* Fade-in animáció */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, -55%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    /* Bezárás gomb */
    .popup-close {
        background: #ff66b2; /* RÓZSASZÍN gomb */
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        color: white;
        margin-top: 10px;
        transition: background 0.3s ease-in-out;
    }

    .popup-close:hover {
        background: #ff3385;
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
        <p>Nincs még fiókod? <a href="register.php">Regisztrálj!</a></p>
      </form>
    </div>
</div>
<!-- ✅ Sikeres bejelentkezés Popup -->
<?php if (isset($_SESSION['login_message'])): ?>
    <div id="loginPopup" class="popup-container">
        <p><?php echo $_SESSION['login_message']; ?></p>
        <button class="popup-close" onclick="closePopup()">OK</button>
    </div>
    <?php unset($_SESSION['login_message']); ?>
<?php endif; ?>

<script>
    // Megnyitja a popupot automatikusan
    document.addEventListener("DOMContentLoaded", function() {
        var popup = document.getElementById("loginPopup");
        if (popup) {
            popup.style.display = "block";
        }
    });

    // Bezárja a popupot
    function closePopup() {
        var popup = document.getElementById("loginPopup");
        popup.style.display = "none";
    }
</script>

</body>
</html>
