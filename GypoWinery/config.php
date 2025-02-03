<?php
session_start();

// Bejelentkezés ellenőrzése
if (!isset($_SESSION['user_id'])) {
    // Ha a felhasználó nincs bejelentkezve, átirányítjuk a bejelentkező oldalra
    header('Location: login.html');
    exit(); // Ne folytassa az oldal betöltését
}

// Ha a kosár még nincs inicializálva, hozzuk létre
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>