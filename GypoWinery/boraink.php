<?php
include('config.php');

// Lekérdezzük a borokat az adatbázisból a képekkel együtt
$query = "SELECT borok.*, bor_kepek.kep_url FROM borok LEFT JOIN bor_kepek ON borok.ID = bor_kepek.bor_id";
$stmt = $pdo->query($query);
$borok = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ha a kosárba tétel történt
if (isset($_POST['add_to_cart'])) {
    $bor_id = $_POST['bor_id'];
    $quantity = $_POST['quantity'];

    // Ellenőrizzük, hogy a bor már benne van-e a kosárban
    $user_id = $_SESSION['user_id'];  // A bejelentkezett felhasználó ID-ja
    $check_query = "SELECT * FROM cart WHERE user_id = :user_id AND bor_id = :bor_id";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->execute(['user_id' => $user_id, 'bor_id' => $bor_id]);
    $existing_item = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Ha már van a kosárban, frissítjük a mennyiséget
        $new_quantity = $existing_item['quantity'] + $quantity;
        $update_query = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND bor_id = :bor_id";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute(['quantity' => $new_quantity, 'user_id' => $user_id, 'bor_id' => $bor_id]);
    } else {
        // Ha nincs még a kosárban, akkor hozzáadjuk
        $insert_query = "INSERT INTO cart (user_id, bor_id, quantity) VALUES (:user_id, :bor_id, :quantity)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->execute(['user_id' => $user_id, 'bor_id' => $bor_id, 'quantity' => $quantity]);
    }

    // Frissítjük a bor készletét az adatbázisban
    $update_stock_query = "UPDATE borok SET keszlet = keszlet - :quantity WHERE ID = :bor_id";
    $update_stock_stmt = $pdo->prepare($update_stock_query);
    $update_stock_stmt->execute(['quantity' => $quantity, 'bor_id' => $bor_id]);

    // Visszairányítás a borok listájához
    header('Location: boraink.php');
    exit();
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
        .wine-card img.card-img-top {
    width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain; /* Az arányokat megőrzi, és nem vágja le */
    display: block;
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
    <h2 class="section-title text-center mb-4">Boraink</h2>

    <div class="row">
        <?php foreach ($borok as $bor): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm wine-card"> 
                    <img src="<?php echo htmlspecialchars($bor['kep_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($bor['nev']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($bor['nev']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($bor['leiras']); ?></p>
                        <p class="card-text wine-price"><strong>Ár:</strong> <?php echo number_format($bor['ar'], 0, '.', ' '); ?> Ft</p>
                        <p class="card-text wine-stock"><strong>Raktár:</strong> 
                            <?php echo ($bor['keszlet'] > 0) ? $bor['keszlet'] . " palack" : "Nincs raktáron"; ?>
                        </p>
                        <form action="boraink.php" method="POST">
                            <input type="hidden" name="bor_id" value="<?php echo $bor['ID']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $bor['keszlet']; ?>" <?php if ($bor['keszlet'] <= 0) echo 'disabled'; ?> class="form-control mb-3">
                            <button type="submit" name="add_to_cart" class="btn btn-primary btn-block" <?php if ($bor['keszlet'] <= 0) echo 'disabled'; ?>>Kosárba</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

    <footer class="text-center py-3">
        <p>Johann Wolfgang von Goethe: „Az élet túl rövid ahhoz, hogy rossz bort igyunk.”</p>
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="user-menu.js"></script> 
    <script src="translate.js"></script>
    <script src="darkmode.js"></script>
</body>
</html>
