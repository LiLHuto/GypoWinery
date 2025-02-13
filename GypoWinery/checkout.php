<?php
include('config2.php');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Lekérdezzük a kosár tartalmát
$query = "SELECT cart.ID as cart_id, borok.ID as bor_id, borok.ar, borok.nev, cart.quantity 
          FROM cart 
          JOIN borok ON cart.bor_id = borok.ID
          WHERE cart.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    echo "<p>A kosár üres. Nem lehet fizetni.</p>";
    exit();
}

$total = 0;
$order_details = "";
foreach ($cart_items as $item) {
    $total += $item['ar'] * $item['quantity'];
    $order_details .= "{$item['nev']} - {$item['quantity']} db - " . number_format($item['ar'] * $item['quantity'], 0, ',', ' ') . " Ft\n";
}
$shipping_cost = 1500;
$final_total = $total + $shipping_cost;

// Ha rendelést adnak le
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    // Új rendelés létrehozása
    $insert_order = "INSERT INTO rendelesek (user_id, statusz) VALUES (:user_id, 'pending')";
    $order_stmt = $pdo->prepare($insert_order);
    $order_stmt->execute(['user_id' => $user_id]);
    $order_id = $pdo->lastInsertId();

    // Rendelés tételek mentése és készlet frissítése
    foreach ($cart_items as $item) {
        $insert_item = "INSERT INTO rendeles_tetelek (rendeles_id, bor_id, quantity) VALUES (:order_id, :bor_id, :quantity)";
        $item_stmt = $pdo->prepare($insert_item);
        $item_stmt->execute(['order_id' => $order_id, 'bor_id' => $item['bor_id'], 'quantity' => $item['quantity']]);
        
        // Készlet csökkentése
        $update_stock = "UPDATE borok SET keszlet = keszlet - :quantity WHERE ID = :bor_id";
        $stock_stmt = $pdo->prepare($update_stock);
        $stock_stmt->execute(['quantity' => $item['quantity'], 'bor_id' => $item['bor_id']]);
    }

    // Kosár kiürítése
    $delete_cart = "DELETE FROM cart WHERE user_id = :user_id";
    $delete_stmt = $pdo->prepare($delete_cart);
    $delete_stmt->execute(['user_id' => $user_id]);

    // Vásárló e-mail címe
    $get_user_email_query = "SELECT email FROM login WHERE ID = :user_id";
    $get_user_email_stmt = $pdo->prepare($get_user_email_query);
    $get_user_email_stmt->execute(['user_id' => $user_id]);
    $user_email = $get_user_email_stmt->fetchColumn();

    if ($user_email) {
        $api_key = "a058a000-92b7-445f-9d13-e75f1cee5a04"; // Cseréld ki a saját Web3Forms API kulcsodra

        $post_fields = http_build_query([
            "access_key" => $api_key,
            "subject" => "Rendelés visszaigazolás - Gypo Winery",
            "from_name" => "Gypo Winery",
            "from_email" => "gypowinery@gmail.com",
            "replyto" => $user_email,
            "to" => $user_email,
            "message" => "Kedves Vásárló,\n\nKöszönjük a rendelésed!\n\nRendelési azonosító: #$order_id\n\nRendelt tételek:\n$order_details\n\nÖsszesen: " . number_format($final_total, 0, ',', ' ') . " Ft\n\nHamarosan jelentkezünk a kiszállítás részleteivel.\n\nÜdvözlettel,\nGypo Winery"
        ]);

        $ch = curl_init("https://api.web3forms.com/submit");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    // Átirányítás a rendelés végére
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
    <link rel="stylesheet" href="darkmode.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .checkout-card {
            background: linear-gradient(135deg, #f8f9fa, #e0e0e0);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .btn-success, .btn-secondary {
            width: 100%;
            font-size: 18px;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            margin-top: 10px;
        }
        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0px 6px 15px rgba(40, 167, 69, 0.3);
            transform: scale(1.05);
        }
        .btn-secondary:hover {
            background-color: #6c757d;
            box-shadow: 0px 6px 15px rgba(108, 117, 125, 0.3);
            transform: scale(1.05);
        }
        .form-select {
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
        footer {
            margin-top: 50px;
            padding: 20px;
            background: #343a40;
            color: white;
        }
        
    </style>
    <script>
        function togglePaymentDetails() {
            var paymentMethod = document.getElementById("payment_method").value;
            var cardDetails = document.getElementById("card-details");
            var paypalDetails = document.getElementById("paypal-details");
            
            if (paymentMethod === "Bankkártya") {
                cardDetails.classList.remove("hidden");
                paypalDetails.classList.add("hidden");
            } else if (paymentMethod === "PayPal") {
                paypalDetails.classList.remove("hidden");
                cardDetails.classList.add("hidden");
            } else {
                cardDetails.classList.add("hidden");
                paypalDetails.classList.add("hidden");
            }
        }
    </script>
</head>
<body>
<header class="text-center py-3">
        <h1>Rendelés</h1>
    </header>
    <div class="container text-center">
        <div id="flags-container" class="mb-3"></div>
        <div id="darkmode-container" class="mb-3"></div>
<body class="checkout-page">
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

        <div class="checkout-card">
            <h2 class="mb-3">🛒 Véglegesítés</h2>
            <p class="mb-4">Ha biztos vagy a rendelésedben, válaszd ki a fizetési módot és kattints a "Rendelés leadása" gombra.</p>
            <h4 class="mb-3">🛍 Termékek ára: <?php echo number_format($total, 0, ',', ' '); ?> Ft</h4>
            <h4 class="mb-3">🚚 Szállítási díj: <?php echo number_format($shipping_cost, 0, ',', ' '); ?> Ft</h4>
            <h4 class="mb-3">💰 Összesen: <?php echo number_format($final_total, 0, ',', ' '); ?> Ft</h4>
            <form action="checkout.php" method="POST">
                <div class="mb-3 text-start">
                    <label for="payment_method" class="form-label">💳 Válassz fizetési módot:</label>
                    <select class="form-select" name="payment_method" id="payment_method" onchange="togglePaymentDetails()" required>
                        <option value="Készpénz">💵 Készpénz</option>
                        <option value="Bankkártya">💳 Bankkártya</option>
                        <option value="PayPal">🅿️ PayPal</option>
                    </select>
                </div>
                <div id="card-details" class="hidden">
                    <div class="mb-3 text-start">
                        <label for="card_name" class="form-label">👤 Kártyatulajdonos neve:</label>
                        <input type="text" class="form-control" name="card_name" placeholder="Teljes név">
                    </div>
                    <div class="mb-3 text-start">
                        <label for="card_number" class="form-label">💳 Kártyaszám:</label>
                        <input type="text" class="form-control" name="card_number" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="mb-3 text-start">
                        <label for="expiry_date" class="form-label">📅 Lejárati dátum:</label>
                        <input type="text" class="form-control" name="expiry_date" placeholder="MM/YY">
                    </div>
                    <div class="mb-3 text-start">
                        <label for="cvv" class="form-label">🔒 CVC:</label>
                        <input type="text" class="form-control" name="cvc" placeholder="123">
                    </div>
                </div>
                <div id="paypal-details" class="hidden">
                    <div class="mb-3 text-start">
                        <label for="paypal_email" class="form-label">📧 PayPal e-mail:</label>
                        <input type="email" class="form-control" name="paypal_email" placeholder="email@example.com">
                    </div>
                </div>
                <button type="submit" name="place_order" class="btn btn-success">✔️ Rendelés leadása</button>
            </form>
            <a href="rendeles.php" class="btn btn-secondary">🔙 Vissza</a>
        </div>
    </div>
    <script src="darkmode.js"></script>
    <script src="translate.js"></script>
</body>
</html>