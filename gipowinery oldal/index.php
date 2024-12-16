<?php
session_start();

// Ha nincs bejelentkezve a felhasználó, átirányítjuk a login oldalra
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>