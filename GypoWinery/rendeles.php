<?php
include('config2.php');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
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

// Ha a kosár üres
/*if (!$cart_items) {
    echo "<div class='mt-5 empty-cart'>
    <div class='empty-cart-icon'>🛒</div>
    <h3>A kosár üres.</h3>
    <p>Nincs termék a kosaradban.</p>
    <a href='boraink.php' class='btn btn-primary'>Vissza a borainkhoz</a>
</div>
";
    exit();
}*/

// Kosár frissítése
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity'];

    // Mennyiség frissítése
    $update_query = "UPDATE cart SET quantity = :quantity WHERE ID = :cart_id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->execute(['quantity' => $new_quantity, 'cart_id' => $cart_id]);

    // Frissítjük az oldalt
    header('Location: rendeles.php'); // Oldal frissítése
    exit();
}

// Kosár törlés
if (isset($_POST['remove_item'])) {
    $cart_id = $_POST['cart_id'];
    $bor_id = $_POST['bor_id'];  // A bor ID-ja
    $quantity = $_POST['quantity'];  // A törölt mennyiség

    // Először frissítjük a bor készletét
    $update_stock_query = "UPDATE borok SET keszlet = keszlet + :quantity WHERE ID = :bor_id";
    $update_stock_stmt = $pdo->prepare($update_stock_query);
    $update_stock_stmt->execute(['quantity' => $quantity, 'bor_id' => $bor_id]);

    // Majd töröljük a terméket a kosárból
    $delete_query = "DELETE FROM cart WHERE ID = :cart_id";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->execute(['cart_id' => $cart_id]);

    header('Location: rendeles.php'); // Törlés után frissítjük az oldalt
    exit();
}
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
    <link rel="stylesheet" href="darkmode2.css">
</head>
<body class="rendeles-page">
    <header class="text-center py-3">
        <h1>Rendelés</h1>
    </header>
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
                        <?php $total = 0; // Összes ár változókezdése ?>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo $item['nev']; ?></td>
                                <td><?php echo number_format($item['ar'], 0, '.', ' '); ?> Ft</td>
                                <td>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="50"<?php echo $item['keszlet']; ?> class="form-control" required>
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <input type="hidden" name="bor_id" value="<?php echo $item['bor_id']; ?>"> <!-- Bor ID -->
                                </td>
                                <td>
                                    <?php
                                        $subtotal = $item['ar'] * $item['quantity'];
                                        $total += $subtotal;
                                        echo number_format($subtotal, 0, '.', ' ');
                                    ?> Ft
                                </td>
                                <td>
                                    <button type="submit" name="update_cart" class="btn btn-warning">Frissítés</button>
                                    <form method="POST" action="rendeles.php" style="display:inline;">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="hidden" name="bor_id" value="<?php echo $item['bor_id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>"> <!-- Készlet frissítéshez -->
                                        <button type="submit" name="remove_item" class="btn btn-danger">Törlés</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Összesítés és díj -->
                <div class="d-flex justify-content-between mt-4">
                    <div><strong>Összesen:</strong></div>
                    <div><?php echo number_format($total, 0, '.', ' '); ?> Ft</div>
                </div>

                <!-- Szállítási díj hozzáadása -->
                <div class="d-flex justify-content-between mt-2">
                    <div><strong>Szállítási díj:</strong></div>
                    <div><?php echo number_format(1500, 0, '.', ' '); ?> Ft</div>
                </div>

                <!-- Végösszeg -->
                <div class="d-flex justify-content-between mt-3">
                    <div><strong>Végösszeg:</strong></div>
                    <div><?php echo number_format($total + 1500, 0, '.', ' '); ?> Ft</div>
                </div>

                <a href="boraink.php" class="btn btn-secondary">Vissza a borainkhoz</a>
                <a href="checkout.php" class="btn btn-success mt-3">Tovább a fizetéshez</a>
            </form>
    <?php else: ?>
        <div class="mt-5 empty-cart">
    <div class="empty-cart-icon">🛒</div>
    <h3>A kosár üres.</h3>
    <p>Nincs termék a kosaradban.</p>
    <a href="boraink.php" class="btn btn-primary">Vissza a borainkhoz</a>
</div>

    <?php endif; ?>
    </div>

    <footer class="text-center py-3">
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="translate.js"></script>
    <script src="darkmode.js"></script>
</body>
</html>
