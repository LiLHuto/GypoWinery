<?php
include('config2.php');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Kosár tartalma lekérdezése
$query = "SELECT cart.ID as cart_id, borok.nev, borok.ar, cart.quantity, borok.keszlet, borok.ID as bor_id
          FROM cart 
          JOIN borok ON cart.bor_id = borok.ID
          WHERE cart.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kosár frissítése
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity'];

    $update_query = "UPDATE cart SET quantity = :quantity WHERE ID = :cart_id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->execute(['quantity' => $new_quantity, 'cart_id' => $cart_id]);

    header('Location: rendeles.php');
    exit();
}

// Kosár törlés
if (isset($_POST['remove_item'])) {
    $cart_id = $_POST['cart_id'];
    $bor_id = $_POST['bor_id'];
    $quantity = $_POST['quantity'];

    $update_stock_query = "UPDATE borok SET keszlet = keszlet + :quantity WHERE ID = :bor_id";
    $update_stock_stmt = $pdo->prepare($update_stock_query);
    $update_stock_stmt->execute(['quantity' => $quantity, 'bor_id' => $bor_id]);

    $delete_query = "DELETE FROM cart WHERE ID = :cart_id";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->execute(['cart_id' => $cart_id]);

    header('Location: rendeles.php');
    exit();
}

// Kuponkód alkalmazása
$discount = 0;
$total = 0;

// Kosár összegének kiszámítása
foreach ($cart_items as $item) {
    $subtotal = $item['ar'] * $item['quantity'];
    $total += $subtotal;
}

if (isset($_POST['apply_coupon'])) {
    $coupon_code = $_POST['coupon_code'];

    // Kupon ellenőrzése
    $coupon_query = "SELECT * FROM kuponok WHERE kupon_kod = :coupon_code AND felhasznalt = 0";
    $coupon_stmt = $pdo->prepare($coupon_query);
    $coupon_stmt->execute(['coupon_code' => $coupon_code]);
    $coupon = $coupon_stmt->fetch(PDO::FETCH_ASSOC);

    if ($coupon) {
        $discount = 0.1 * $total; // 10% kedvezmény
        $_SESSION['discount'] = $discount;
        $_SESSION['applied_coupon'] = $coupon_code;

        // Kupon felhasználásának frissítése
        $update_coupon_query = "UPDATE kuponok SET felhasznalt = 1 WHERE id = :coupon_id";
        $update_coupon_stmt = $pdo->prepare($update_coupon_query);
        $update_coupon_stmt->execute(['coupon_id' => $coupon['id']]);
    } else {
        $error_message = "A kupon érvénytelen vagy már felhasználásra került!";
    }
}

$shipping_cost = 1500;
$final_total = max(0, ($total + $shipping_cost - $discount));
$_SESSION['final_total'] = $final_total;
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery - Kosár</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="darkmode.css">
    <link rel="stylesheet" href="rend.css">
</head>
<body class="rendeles-page">
    <header class="text-center py-3">
        <h1>Rendelés</h1>
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
    </header>

    <div class="container">
        <?php if ($cart_items): ?>
            <h3 class="mt-5">A kosár tartalma</h3>
            <form method="POST" action="rendeles.php">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bor neve</th>
                            <th>Ár</th>
                            <th>Mennyiség</th>
                            <th>Összesen</th>
                            <th>Művelet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo $item['nev']; ?></td>
                                <td><?php echo number_format($item['ar'], 0, '.', ' '); ?> Ft</td>
                                <td>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="50" class="form-control" required>
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <input type="hidden" name="bor_id" value="<?php echo $item['bor_id']; ?>"> 
                                </td>
                                <td><?php echo number_format($item['ar'] * $item['quantity'], 0, '.', ' '); ?> Ft</td>
                                <td>
                                    <button type="submit" name="update_cart" class="btn btn-warning">Frissítés</button>
                                    <button type="submit" name="remove_item" class="btn btn-danger">Törlés</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h4>Összesen: <?php echo number_format($total, 0, '.', ' '); ?> Ft</h4>
                    <h4>Kedvezmény: -<?php echo number_format($discount, 0, '.', ' '); ?> Ft</h4>
                    <h4>Szállítási díj: <?php echo number_format($shipping_cost, 0, '.', ' '); ?> Ft</h4>
                    <h3>Fizetendő összeg: <?php echo number_format($final_total, 0, '.', ' '); ?> Ft</h3>
                </div>

                <div class="mt-4">
                    <input type="text" name="coupon_code" class="form-control" placeholder="Írj be egy kuponkódot">
                    <button type="submit" name="apply_coupon" class="btn btn-primary mt-2">Kupon alkalmazása</button>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger mt-2"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                </div>

                <div class="button-container2">
                    <a href="boraink.php" class="btn btn-secondary">Vissza a borainkhoz</a>
                    <a href="checkout.php" class="btn btn-success mt-3">Tovább a fizetéshez</a>
                </div>
            </form>
        <?php else: ?>
            <h3>A kosár üres.</h3>
            <a href="boraink.php" class="btn btn-primary">Vissza a borainkhoz</a>
        <?php endif; ?>
    </div>
    <script src="translate.js"></script>
    <script src="darkmode.js"></script>
</body>
</html>
