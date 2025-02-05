<?php
include('config.php');
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery - Történet</title>
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
                        <li class = "nav-item"><a href="rendeles.php">Rendelés</a></li>
                        <a href="logout.php">Kijelentkezés</a>
                    </div>
                </div>
                <?php else: ?>
                <!-- Login/Register links - only visible if not logged in -->
                <div class="login-links mt-3">
                    <ul class="nav justify-content-center">
                        <li class="nav-item"><a href="login.php">Bejelentkezés</a></li>
                        
                        <li class="nav-item"><a href="register.php">Regisztráció</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container my-5">
        <section class="text-center mb-5">
            <h2 class="section-title display-4 fw-bold">A Gypo Winery története</h2>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">A Borászat Kezdetei</h1>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>A GypoWinery története a csodálatos Csévharaszti vidéken kezdődött, ahol a borászat hagyományai évszázadokra nyúlnak vissza. A családunk generációk óta foglalkozik szőlőtermesztéssel, és a szenvedélyünk a borkészítés iránt öröklődött. Az első szőlőültetvényünket 1990-ben alapítottuk, kezdetben csak kis mennyiségben készítettük borainkat, de a minőség és a helyi ízek iránti elkötelezettségünk hamarosan meghozta gyümölcsét.</p>
                </div>
                <div class="col-md-6">
                    <img src="kepek/borkezdete 1.jpg" alt="A borászat kezdetei" class="img-fluid rounded shadow">
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">A Borászat Fejlődése</h1>
            <div class="row align-items-center">
                <div class="col-md-6 order-md-2">
                    <p>Az évek során a GypoWinery folyamatosan fejlődött. 2005-ben modern borkészítő technológiákkal bővítettük üzemünket, ami lehetővé tette számunkra, hogy még finomabb és változatosabb borokat készítsünk. A hagyományos módszerek mellett a legújabb tudományos megközelítéseket is alkalmazzuk, hogy a lehető legjobb minőséget érjük el.</p>
                </div>
                <div class="col-md-6 order-md-1">
                    <img src="kepek/borokfejlodes 1.jpeg" alt="A borászat fejlődése" class="img-fluid rounded shadow">
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">Küldetésünk</h1>
            <div class="text-center">
                <p>Célunk, hogy bemutassuk a Csévharaszti terroir egyedülálló ízvilágát. Minden palack borunkban a szőlő, a föld és a helyi környezet szelleme tükröződik. Hiszünk abban, hogy a borkészítés művészet, ahol minden egyes üveg egy történetet mesél el.</p>
                <img src="kepek/borkuldetes 1.jpg" alt="Küldetésünk" class="img-fluid rounded shadow">
            </div>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">Közösség és Fenntarthatóság</h1>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>A GypoWinery elkötelezett a fenntarthatóság mellett. Szőlőinket természetbarát módszerekkel gondozzuk, és figyelmet fordítunk a helyi közösség támogatására is. Rendszeresen részt veszünk helyi rendezvényeken, és együttműködünk más borászokkal, hogy népszerűsítsük a Csévharaszti borászatot.</p>
                </div>
                <div class="col-md-6">
                    <img src="kepek/borkozosseg 1.jpg" alt="Közösség és Fenntarthatóság" class="img-fluid rounded shadow">
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">Jövőnk</h1>
            <div class="text-center">
                <p>A jövőnk fényes, és szeretnénk továbbra is a borászat iránti szenvedélyünket megosztani Önökkel. Tervezünk új borfajták bevezetését és borászatunk bővítését, hogy a lehető legjobb élményt nyújthassuk a látogatóknak.</p>
                <img src="kepek/borfejlodes.jpeg" alt="Jövőnk" class="img-fluid rounded shadow">
            </div>
        </section>

        <div class="text-center py-4 bg-light rounded shadow">
            <p class="fs-5">Fedezze fel a GypoWinery-t, és legyen részese a boraink történetének! Kóstolja meg a szenvedélyünket és hagyományainkat minden egyes palackban!</p>
            <a href="boraink.php" class="btn btn-primary btn-lg">Ismerje meg borainkat</a>
        </div>
    </main>
    <footer>
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
    <script src="translate.js"></script>


</body>
</html>