<?php
session_start();

// Bejelentkezés ellenőrzése
if (!isset($_SESSION['user_id'])) {
    // Ha a felhasználó nincs bejelentkezve, átirányítjuk a bejelentkező oldalra
    header('Location: login.html');
    exit(); // Ne folytassa az oldal betöltését
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery</title>
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
            color: #5a2a4e;
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
        <h1><a href="index.php" class="text-decoration-none">Gypo Winery</a></h1>
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
                        <a href="rendeles.php">Rendeles</a>                       
                        <a href="logout.php">Kijelentkezés</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Login/Register links - only visible if not logged in -->
                <div class="login-links mt-3">
                    <ul class="nav justify-content-center">
                        <li class="nav-item"><a href="login.html">Bejelentkezés</a></li>
                        <li class="nav-item"><a href="regisztracio.html">Regisztráció</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container my-5">
        <!-- Első szekció -->
        <section class="row align-items-center mb-5">
            <div class="col-md-6">
                <h1>Gypo Winery – A Borok Művészete</h1>
                <h2>Üdvözöljük a Gypo Winery-nél!</h2>
                <p>Fedezze fel a kiváló minőségű boraink világát, ahol a hagyomány és a modern technológia találkozik! Borkészítésünk során a természet adta legjobb alapanyagokat használjuk, hogy minden palackunkban a terroir és a szenvedély esszenciáját örökítsük meg.</p>
                <a href="tortenet.php" class="btn btn-primary">Rólunk</a>
            </div>
            <div class="col-md-6">
                <img src="kepek/borozo.jfif" alt="Borkóstoló" class="img-fluid rounded">
            </div>
        </section>

        <!-- Második szekció -->
        <section class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2">
                <h1>Látogatások és Kóstolók</h1>
                <p>Fedezze fel borászatunkat személyesen! Csoportos és egyéni kóstolóink során lehetősége van megismerkedni a borkészítés folyamatával, valamint megkóstolni a legújabb borainkat. Foglaljon időpontot most!</p>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="kepek/borkostolo-borkostolas-pince.webp" alt="Borkóstoló esemény" class="img-fluid rounded">
            </div>
        </section>

        <!-- Harmadik szekció -->
        <section class="row align-items-center mb-5">
            <div class="col-md-6">
                <h1>Hírek és Események</h1>
                <p>Tartsa velünk a lépést! Friss hírek, események és különleges ajánlatok várják Önt. Ne hagyja ki a jövőbeli borfesztiválokat és workshopokat!</p>
            </div>
            <div class="col-md-6">
                <img src="kepek/istock-1126184071-1140x760-1.jpg" alt="Kapcsolat" class="img-fluid rounded">
            </div>
        </section>
    </main>

    <footer class="text-center py-3">
        <p>Johann Wolfgang von Goethe: „Az élet túl rövid ahhoz, hogy rossz bort igyunk.”</p>
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
</body>
</html>
