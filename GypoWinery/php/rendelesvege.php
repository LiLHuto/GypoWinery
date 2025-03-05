<?php
include('config.php');

// A felhasználó neve lekérése az adatbázisból
$user_id = $_SESSION['user_id'];

// Lekérdezzük a felhasználó nevét a 'login' táblából
$query = "SELECT keresztnev FROM login WHERE ID = :user_id";  // Módosítottuk a tábla nevét 'login'-re
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ha nem találjuk a felhasználót, akkor hibát jelezhetünk
if (!$user) {
    die("Felhasználó nem található.");
}

$user_name = $user['keresztnev'];
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery - Rendelés Vége</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="darkmode.css">
</head>
<body class="rendelesvege-page">
                      <!-- Zászlók helye (ez JavaScript tölti be) -->
                      <div id="flags-container"></div>

<!-- Sötét mód kapcsoló -->
<div id="darkmode-container">
    <label class="theme-switch">
        <input type="checkbox" id="darkModeToggle">
        <div class="slider">
            <div class="clouds">
                <span class="cloud"></span>
                <span class="cloud"></span>
                <span class="cloud"></span>
                <span class="cloud"></span>
            </div>
            <div class="circle"></div>
            <div class="stars">
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
            </div>
        </div>
    </label>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Megvárjuk, amíg a JavaScript betölti a zászlókat
    var flagsContainer = document.querySelector("#flags-container");
    var darkmodeContainer = document.querySelector("#darkmode-container");

    if (flagsContainer && darkmodeContainer) {
        // A sötét mód kapcsolót a zászlók után helyezzük el
        flagsContainer.insertAdjacentElement("afterend", darkmodeContainer);
    }
});
</script>
    <header class="text-center py-3">
        <h1>Rendelés Vége</h1>
    </header>

    <div class="container">
        <h3 class="mt-5">Rendelésedet sikeresen rögzítettük!</h3>
        <p>Köszönjük a vásárlást, <strong><?php echo htmlspecialchars($user_name); ?></strong>!</p>
        <p>Hamarosan értesítünk a rendelés állapotáról.</p>
        <a href="index.php" class="btn btn-primary mt-3">Vissza a főoldalra</a>
    </div>

    <footer class="text-center py-3">
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="translate.js"></script>
    <script src="darkmode.js"></script>
</body>
</html>