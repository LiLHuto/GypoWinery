<?php
include('index_config.php');
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
    <link rel="stylesheet" href="darkmode.css">
    


    <style>
         /* Popup alapstílus */
         .popup-container {
            display: none; /* Kezdetben el van rejtve */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #5a2a4e;
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
            background: #ff66b2;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            margin-top: 10px;
        }

        .popup-close:hover {
            background: #ff2222;
        }
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
    <a href="index.php">
    <img src="kepek/gypo2-removebg-preview.png" alt="Gypo Winery Logo" class="logo"></a>
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
                    <img src="kepek/user-icon.png" alt="Felhasználó ikon" class="icon" width="30">
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
        <!-- Első szekció -->
        <section class="row align-items-center mb-5">
            <div class="col-md-6">
                <h1>Gypo Winery – A Borok Művészete</h1>
                <h2>Üdvözöljük a Gypo Winery-nél!</h2>
                <p>Fedezze fel a kiváló minőségű boraink világát, ahol a hagyomány és a modern technológia találkozik! Borkészítésünk során a természet adta legjobb alapanyagokat használjuk, hogy minden palackunkban a terroir és a szenvedély esszenciáját örökítsük meg.</p>
                <a href="tortenet.php" class="btn btn-primary">Rólunk</a>
            </div>
            <div class="col-md-6">
                <img src="kepek/borpince.jpg" alt="Borkóstoló" class="img-fluid rounded">
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
    <script src="translate.js"></script>
    <script src="darkmode.js"></script>
    
    <?php if (isset($_SESSION['login_message'])): ?>
    <div id="loginPopup" class="popup-container">
        <p><?php echo $_SESSION['login_message']; ?></p>
        <button class="popup-close" onclick="closePopup()">OK</button>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var popup = document.getElementById("loginPopup");
            if (popup) {
                popup.style.display = "block";

                // 🔥 Popup automatikus eltűnése 3 másodperc után
                setTimeout(closePopup, 8000);
            }
        });

        function closePopup() {
            var popup = document.getElementById("loginPopup");
            if (popup) {
                popup.style.display = "none";
            }
        }
    </script>
    <?php unset($_SESSION['login_message']); // ✅ Üzenet törlése a session-ből ?>
<?php endif; ?>

</body>

</html>