<?php
include 'config.php';


$stmt = $pdo->prepare("SELECT c.quantity, b.nev, b.ar FROM cart c JOIN borok b ON c.bor_id = b.ID WHERE c.user_id = :user_id");
$cart_items = $stmt->fetchAll();

if ($cart_items) {
    echo "A kosárban lévő termékek:<br>";
    foreach ($cart_items as $item) {
        echo "Termék: " . $item['nev'] . ", Ár: " . $item['ar'] . " Ft, Mennyiség: " . $item['quantity'] . "<br>";
    }
} else {
    echo "A kosár üres.";
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendelések - Gypo Winery</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
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

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: #f8f9fa;
            font-weight: bold;
        }

        nav ul li a:hover {
            color: #ffc107;
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
        <img src="kepek/gypo2-removebg-preview.png" alt="Gypo Winery Logo" class="logo">
        <h1><a href="php/index.php" class="text-decoration-none">Gypo Winery</a></h1>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item"><a href="index.php">Főoldal</a></li>
                <li class="nav-item"><a href="boraink.php">Boraink</a></li>
                <li class="nav-item"><a href="kapcsolat.php">Kapcsolat</a></li>
                <li class="nav-item"><a href="logout.php">Kijelentkezés</a></li>
            </ul>
        </nav>
    </header>

    <section class="container mt-5">
        <h2 class="text-center mb-4">Kosár tartalma</h2>
        <?php if (!empty($cart)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Termék</th>
                        <th>Mennyiség</th>
                        <th>Ár</th>
                        <th>Összesen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo intval($item['quantity']); ?></td>
                            <td><?php echo number_format($item['price'], 2); ?> Ft</td>
                            <td><?php echo number_format($item['quantity'] * $item['price'], 2); ?> Ft</td>
                        </tr>
                        <?php $total += $item['quantity'] * $item['price']; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Végösszeg:</strong></td>
                        <td><strong><?php echo number_format($total, 2); ?> Ft</strong></td>
                    </tr>
                </tfoot>
            </table>
            <div class="text-center">
                <a href="fizetes.php" class="btn btn-success">Fizetés</a>
            </div>
        <?php else: ?>
            <p class="text-center">A kosár üres.</p>
        <?php endif; ?>
    </section>

    <footer class="bg-light py-3 text-center mt-5">
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>