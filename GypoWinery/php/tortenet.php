<?php
include('config.php');
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery - Történet</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/user-menu.css"> <!-- Felhasználói menü stílus -->
    <link rel="stylesheet" href="../css/darkmode.css">
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

        
        .navbar {
        background-color: #5a2a4e; /* A headerrel megegyező háttérszín */
        }
        .navbar-nav .nav-link {
            font-weight: bold;
            color: white;
        }
        .navbar-nav .nav-link:hover {
            color: gold;
        }
        .dropdown-menu {
            background-color: #5a2a4e;
        }
        .dropdown-menu .dropdown-item {
            font-weight: bold;
            color: white;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: gold;
            color: black;
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
<a href="index.php">
    <img src="../kepek/gypo2-removebg-preview.png" alt="Gypo Winery Logo" class="logo"></a>
        <h1><a href="index.php" class="text-decoration-none">Gypo Winery</a></h1>
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
<nav class="navbar navbar-expand-lg navbar-light text-center">
    <div class="container-fluid d-flex flex-column align-items-center">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav text-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Főoldal</a></li>
                <li class="nav-item"><a class="nav-link" href="tortenet.php">Történet</a></li>
                <li class="nav-item"><a class="nav-link" href="boraink.php">Boraink</a></li>
                <li class="nav-item"><a class="nav-link" href="kapcsolat.php">Kapcsolat</a></li>
                <li class="nav-item"><a class="nav-link" href="Kviz.php">Kviz</a></li>
            </ul>
        </div>
        <?php if (isset($_SESSION['usertype'])&& ($_SESSION['usertype'] === 'admin')): ?>
               
               <ul class="navbar-nav text-center">
                   <li class="nav-item"><a class="nav-link" href="admin_borok.php">Admin</a></li>
                   <li class="nav-item"><a class="nav-link" href="rendelesek.php">Admin rendelések</a></li>
               </ul>
          
<?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        
            <div class="dropdown mt-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../kepek/user-icon.png" alt="Felhasználó ikon" class="icon" width="30">
                </button>
                <ul class="dropdown-menu text-center" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="rendeles.php">Rendelés</a></li>
                    <li><a class="dropdown-item" href="logout.php">Kijelentkezés</a></li>
                </ul>
            </div>

        <?php else: ?>
            <ul class="navbar-nav mt-3 text-center">
                <li class="nav-item"><a class="nav-link" href="login.php">Bejelentkezés</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Regisztráció</a></li>
            </ul>
        <?php endif; ?>
    </div>
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
                    <img src="../kepek/borkezdete 1.jpg" alt="A borászat kezdetei" class="img-fluid rounded shadow">
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
                    <img src="../kepek/borokfejlodes 1.jpeg" alt="A borászat fejlődése" class="img-fluid rounded shadow">
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">Küldetésünk</h1>
            <div class="text-center">
                <p>Célunk, hogy bemutassuk a Csévharaszti terroir egyedülálló ízvilágát. Minden palack borunkban a szőlő, a föld és a helyi környezet szelleme tükröződik. Hiszünk abban, hogy a borkészítés művészet, ahol minden egyes üveg egy történetet mesél el.</p>
                <img src="../kepek/borkuldetes 1.jpg" alt="Küldetésünk" class="img-fluid rounded shadow">
            </div>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">Közösség és Fenntarthatóság</h1>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>A GypoWinery elkötelezett a fenntarthatóság mellett. Szőlőinket természetbarát módszerekkel gondozzuk, és figyelmet fordítunk a helyi közösség támogatására is. Rendszeresen részt veszünk helyi rendezvényeken, és együttműködünk más borászokkal, hogy népszerűsítsük a Csévharaszti borászatot.</p>
                </div>
                <div class="col-md-6">
                    <img src="../kepek/borkozosseg 1.jpg" alt="Közösség és Fenntarthatóság" class="img-fluid rounded shadow">
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h1 class="h3 fw-bold">Jövőnk</h1>
            <div class="text-center">
                <p>A jövőnk fényes, és szeretnénk továbbra is a borászat iránti szenvedélyünket megosztani Önökkel. Tervezünk új borfajták bevezetését és borászatunk bővítését, hogy a lehető legjobb élményt nyújthassuk a látogatóknak.</p>
                <img src="../kepek/borfejlodes.jpeg" alt="Jövőnk" class="img-fluid rounded shadow">
            </div>
        </section>

        <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@farkasleventeandras/video/7299865332431260961" data-video-id="7299865332431260961" style="max-width: 605px;min-width: 325px;" > <section> <a target="_blank" title="@farkasleventeandras" href="https://www.tiktok.com/@farkasleventeandras?refer=embed">@farkasleventeandras</a> A legendás HAMVAS Béla; Borról és Szerelemről!🍷<a title="drfarkasleventeandrás" target="_blank" href="https://www.tiktok.com/tag/drfarkasleventeandr%C3%A1s?refer=embed">#drfarkasleventeandrás</a> <a title="fyp" target="_blank" href="https://www.tiktok.com/tag/fyp?refer=embed">#fyp</a> <a target="_blank" title="♬ eredeti hang - Dr. Farkas Levente András" href="https://www.tiktok.com/music/eredeti-hang-7299865360231090976?refer=embed">♬ eredeti hang - Dr. Farkas Levente András</a> </section> </blockquote> <script async src="https://www.tiktok.com/embed.js"></script>

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

    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/user-menu.js"></script> <!-- Felhasználói menü funkciók -->
    <script src="../js/translate.js"></script>
    <script src="../js/darkmode.js"></script>


</body>
</html>