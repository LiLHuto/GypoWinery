<?php
include 'config.php';
// Lekérdezzük a kosár tartalmát
$user_id = $_SESSION['user_id'];
$query = "SELECT cart.*, borok.nev, borok.ar FROM cart JOIN borok ON cart.bor_id = borok.ID WHERE cart.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kosár összesítő
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['ar'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendelés - Gypo Winery</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="text-center py-3">
        <h1><a href="index.php" class="text-decoration-none">Gypo Winery</a></h1>
    </header>

    <main class="container my-5">
        <h2 class="section-title">Kosarad</h2>

        <?php if (empty($cart_items)): ?>
            <p>A kosarad üres.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Bor</th>
                        <th>Mennyiség</th>
                        <th>Ár</th>
                        <th>Összesen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <img src="kepek/bor<?php echo $bor['ID'] . '.jfif'; ?>" alt="<?php echo $item['nev']; ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php echo $item['nev']; ?>
                            </td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['ar'], 0, '.', ' '); ?> Ft</td>
                            <td><?php echo number_format($item['ar'] * $item['quantity'], 0, '.', ' '); ?> Ft</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-right">Összesen:</td>
                        <td><?php echo number_format($total_price, 0, '.', ' '); ?> Ft</td>
                    </tr>
                </tbody>
            </table>

            <form action="checkout.php" method="POST">
                <button type="submit" class="btn btn-primary">Tovább a rendeléshez</button>
            </form>
        <?php endif; ?>
    </main>

    <footer class="text-center py-3">
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>