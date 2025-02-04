<?php
include('config.php');

// Lekérdezzük a borokat az adatbázisból
$query = "SELECT * FROM borok";
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
        <h2 class="section-title text-center mb-4">Boraink</h2>

        <div class="row">
            <?php foreach ($borok as $bor): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <?php 
                            // A bor ID-ja alapján kép megjelenítése
                            $image_path = 'kepek/bor' . $bor['ID'] . '.jfif';
                        ?>
                        <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo $bor['nev']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $bor['nev']; ?></h5>
                            <p class="card-text"><?php echo $bor['leiras']; ?></p>
                            <p class="card-text"><strong>Ár:</strong> <?php echo number_format($bor['ar'], 0, '.', ' '); ?> Ft</p>
                            <p class="card-text"><strong>Raktár:</strong> 
                                <?php 
                                    echo ($bor['keszlet'] > 0) ? $bor['keszlet'] . " palack" : "Nincs raktáron"; 
                                ?>
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
    <script src="user-menu.js"></script> <!-- Felhasználói menü funkciók -->
</body>
</html>
