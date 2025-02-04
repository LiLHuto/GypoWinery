<?php
include('config.php');

// Ha a rendelés véglegesítése gombra kattintottak
if (isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];

    // Az aktuális időpont
    $date = date('Y-m-d H:i:s');

    // Rendelés rögzítése a rendelesek táblába
    $insert_order_query = "INSERT INTO rendelesek (user_id, rendeles_datuma) VALUES (:user_id, :rendeles_datuma)";
    $insert_order_stmt = $pdo->prepare($insert_order_query);
    $insert_order_stmt->execute(['user_id' => $user_id, 'rendeles_datuma' => $date]);

    // Az utolsó beszúrt rendelés ID-jának lekérése
    $order_id = $pdo->lastInsertId();

    // Kosárban lévő tételek lekérdezése
    $get_cart_query = "SELECT * FROM cart WHERE user_id = :user_id";
    $get_cart_stmt = $pdo->prepare($get_cart_query);
    $get_cart_stmt->execute(['user_id' => $user_id]);
    $cart_items = $get_cart_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Rendeléshez tartozó tételek rögzítése a rendeles_tetelek táblában
    foreach ($cart_items as $item) {
        $bor_id = $item['bor_id'];
        $quantity = $item['quantity'];

        $insert_order_items_query = "INSERT INTO rendeles_tetelek (rendeles_id, bor_id, quantity) VALUES (:rendeles_id, :bor_id, :quantity)";
        $insert_order_items_stmt = $pdo->prepare($insert_order_items_query);
        $insert_order_items_stmt->execute(['rendeles_id' => $order_id, 'bor_id' => $bor_id, 'quantity' => $quantity]);
    }

    // Kosár törlése a cart táblából
    $delete_cart_query = "DELETE FROM cart WHERE user_id = :user_id";
    $delete_cart_stmt = $pdo->prepare($delete_cart_query);
    $delete_cart_stmt->execute(['user_id' => $user_id]);

    // Törlés után visszairányítás a rendelés oldalra
    header('Location: rendelesvege.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gypo Winery - Checkout</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <header class="text-center py-3">
        <h1>Véglegesítés</h1>
    </header>

    <div class="container">
        <h3>Rendelés véglegesítése</h3>
        <p>Ha biztos vagy a rendelésedben, kattints a "Rendelés leadása" gombra.</p>

        <form action="checkout.php" method="POST">
            <button type="submit" name="place_order" class="btn btn-success">Rendelés leadása</button>
        </form>
    </div>

    <footer class="text-center py-3">
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
