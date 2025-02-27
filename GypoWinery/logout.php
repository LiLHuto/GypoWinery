<?php
session_start();
session_unset();
session_destroy();

// Üzenet beállítása a kijelentkezés sikerességéről
session_start();
$_SESSION['logout_message'] = "Sikeresen kijelentkeztél!";

// Visszairányítás a bejelentkező oldalra
header("Location: index.php"); // Vagy "index.php" attól függően, hova akarod irányítani
exit();
?>
