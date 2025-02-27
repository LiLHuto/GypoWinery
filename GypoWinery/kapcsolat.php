<?php
include('config.php');
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery - Kapcsolat</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="user-menu.css"> <!-- Felhasználói menü stílus -->
    <link rel="stylesheet" href="darkmode.css">
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
form {
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ccc;
}

.btn-primary {
    background-color: #5a2a4e;
    border: none;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background-color: #45223e;
}
/* Dark mode - Label-ek feketévé tétele */
body.dark-mode label {
    color: black !important; /* Fekete szöveg */
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
    <section class="contact.section">
        <h2>Kapcsolat</h2>
        <p>Ha szeretne kapcsolatba lépni velünk, kérjük, küldjön üzenetet az alábbi elérhetőségek egyikén:</p>
        <ul>
            <li>Email: gypowinery@gmail.com</li>
            <li>Telefon: +36 30 123 4567</li>
            <li>Cím: 2211 Csévharaszt, Kossuth Lajos utca 12.</li>
        </ul>
    </section>

    <!-- Contact Form -->
    <section class="contact-section py-5">
    <div class="container">
        <h3 class="text-center mb-4 text-primary">Küldjön nekünk üzenetet!</h3>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="https://api.web3forms.com/submit" method="POST" class="p-4 shadow rounded bg-white">
                    <input type="hidden" name="access_key" value="a058a000-92b7-445f-9d13-e75f1cee5a04">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Keresztnév:</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Vezetéknév:</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail cím:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Üzenet:</label>
                        <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Küldés</button>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="house-image-section text-center">
        <h3>A borászatunk</h3>
        <img src="kepek/hazkep.png" alt="Gypo Winery Ház" class="img-fluid rounded shadow-lg" style="max-width: 50%; height: auto; margin: 20px 0;">
    </section>


    <!-- Embedded Map -->
    <section>
        <h3>Találjon meg minket itt:</h3>
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11094.41825447772!2d19.432318016154997!3d47.377823500000006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741cb7e4a6b2749%3A0xf7e4df83adf6f1e5!2sCs%C3%A9vharaszt%2C%20Kossuth%20Lajos%20u.%2012%2C%202212!5e0!3m2!1shu!2shu!4v1709056000000" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </section>

    <footer>
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="user-menu.js"></script> <!-- Felhasználói menü funkciók -->
    <script src="translate.js"></script>
    <script src="darkmode.js"></script>
</html>
