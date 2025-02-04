<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery - Boraink</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="user-menu.css"> <!-- Felhasználói menü stílus -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }

        header {
            background-color: #5a2a4e;
            color: white;
            padding: 20px 0;
            text-align: center;
            top: 0;
            z-index: 1000;
        }

        header .logo {
            width: 300px;
            margin-bottom: 10px;
        }

        header h1 a {
            text-decoration: none;
            color: white;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: #f8f9fa;
            font-weight: bold;
        }

        nav ul li a:hover {
            color: #ffc107;
        }

        section {
            padding: 40px 15px;
            text-align: center;
        }

        section h1, section h2 {
            color: #6c757d;
            margin-bottom: 20px;
        }

        section p {
            max-width: 800px;
            margin: auto;
            font-size: 1.1em;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #6c757d;
            border: none;
        }

        .btn-primary:hover {
            background-color: #495057;
        }

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 2.5rem;
            color: #6c757d;
        }

        .call-to-action {
            background-color: #e9ecef;
            padding: 30px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header class="text-center py-3">
        <img src="kepek/gypo2-removebg-preview.png" alt="Gypo Winery Logo" class="logo">
        <h1><a href="php/index.php" class="text-decoration-none">Gypo Winery</a></h1>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item"><a href="index.php">Főoldal</a></li>
                <li class="nav-item"><a href="tortenet.php">Történet</a></li>
                <li class="nav-item"><a href="boraink.php">Boraink</a></li>
                <li class="nav-item"><a href="kapcsolat.php">Kapcsolat</a></li>
                <li class="nav-item"><a href="Kviz.php">Kviz</a></li>
            </ul>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User menu -->
                <div class="user-menu mt-3">
                    <button id="userIcon" class="user-icon" onclick="toggleMenu()">
                        <img src="kepek/user-icon.png" alt="Felhasználó ikon" class="icon">
                    </button>
                    <div id="userDropdown" class="dropdown-menu">
                        <a href="#" id="cartButton">Kosár</a>
                        <a href="php/logout.php">Kijelentkezés</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Login/Register links - only visible if not logged in -->
                <div class="login-links mt-3">
                    <ul class="nav justify-content-center">
                        <li class="nav-item"><a href="login.html">Bejelentkezés</a></li>
                        <li class = "nav-item"><a href="rendeles.php"></a></li>
                        <li class="nav-item"><a href="regisztracio.html">Regisztráció</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <section class="container mb-5">
        <h2 class="text-center mb-4">Boraink</h2>
        <p class="text-center">Üdvözöljük a GypoWinery Borainak az oldalán! Fedezze fel a Csévharaszti borok gazdag választékát, melyek a helyi terroir és tradíciók egyedülálló ötvözetét kínálják.</p>

        <!-- Borok listája -->
        <div id="wine-list" class="row">
            <!-- Itt fognak dinamikusan megjelenni a borok -->
        </div>

        <!-- Kosár -->
        <div id="cartPanel" class="cart-panel" style="display: none;">
            <div class="cart-header">
                <h2>Kosár tartalma</h2>
                <button id="closeCartBtn" class="close-btn">X</button>
            </div>
            <div id="cartContent" class="cart-content">
                <!-- Kosár tartalma dinamikusan itt jelenik meg -->
            </div>
        </div>
    </section>

    <footer class="bg-light py-3 text-center">
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>
    <!-- Kosár panel -->
    <div id="cartPanel" class="cart-panel" style="display:none;">
        <div class="cart-header">
            <h2>Kosár</h2>
            <button id="closeCartBtn" class="close-btn">X</button>
        </div>
        <div id="cartContent" class="cart-content">
            <!-- Kosár tartalom dinamikusan kerül ide -->
        </div>
    </div>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="user-menu.js"></script> <!-- Felhasználói menü funkciók -->

    <!-- Kosár funkció és megjelenítés -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cartButton = document.getElementById("cartButton");
            const cartPanel = document.getElementById("cartPanel");
            const closeCartBtn = document.getElementById("closeCartBtn");

            // Kosár panel megjelenítése
            cartButton.addEventListener("click", function () {
                cartPanel.style.display = "block";
            });

            // Kosár panel bezárása
            closeCartBtn.addEventListener("click", function () {
                cartPanel.style.display = "none";
            });

            // Kosár tartalom (itt statikus példát adok, valós adatbázisból kell lekérdezni)
            const cartContent = document.getElementById("cartContent");
            cartContent.innerHTML = "<p>A kosár üres.</p>";
        });
    </script>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
