<?php
include('fasz.php');
check_admin();

// Új bor hozzáadása
if (isset($_POST['add_wine'])) {
    $nev = $_POST['nev'];
    $ar = $_POST['ar'];
    $leiras = $_POST['leiras'];
    $keszlet = $_POST['keszlet'];

    $query = "INSERT INTO borok (nev, ar, leiras, keszlet) VALUES (:nev, :ar, :leiras, :keszlet)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nev' => $nev, 'ar' => $ar, 'leiras' => $leiras, 'keszlet' => $keszlet]);
    header("Location: admin_borok.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Borok Kezelése</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="text-center py-3">
        <img src="kepek/gypo2-removebg-preview.png" alt="Gypo Winery Logo" class="logo">
        <h1><a href="index.php" class="text-decoration-none">Gypo Winery</a></h1>
        <div id="flags-container"></div>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item"><a href="index.php">Főoldal</a></li>
                <li class="nav-item"><a href="tortenet.php">Történet</a></li>
                <li class="nav-item"><a href="boraink.php">Boraink</a></li>
                <li class="nav-item"><a href="kapcsolat.php">Kapcsolat</a></li>
                <li class="nav-item"><a href="Kviz.php">Kvíz</a></li>
                <li class="nav-item"><a href="admin_borok.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main class="container my-5">
        <h2 class="text-center mb-4">Borok Kezelése</h2>

        <?php
        $query = "SELECT * FROM borok";
        $stmt = $pdo->query($query);
        $borok = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="row">
            <?php foreach ($borok as $bor): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($bor['nev']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($bor['leiras']) ?></p>
                            <p class="card-text"><strong>Ár:</strong> <?= htmlspecialchars($bor['ar']) ?> Ft</p>
                            <p class="card-text"><strong>Készlet:</strong> <?= htmlspecialchars($bor['keszlet']) ?> db</p>
                            <form method="post">
                                <input type="hidden" name="wine_id" value="<?= $bor['ID'] ?>">
                                <button type="submit" name="delete_wine" class="btn btn-danger">Törlés</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="text-center my-4">Új Bor Hozzáadása</h2>
        <form method="post" class="text-center">
            <input type="text" name="nev" placeholder="Bor neve" required>
            <input type="number" name="ar" placeholder="Ár (Ft)" required>
            <textarea name="leiras" placeholder="Leírás" required></textarea>
            <input type="number" name="keszlet" placeholder="Készlet" required>
            <button type="submit" name="add_wine" class="btn btn-primary">Hozzáadás</button>
        </form>
    </main>

    <footer class="text-center py-3">
        <p>Johann Wolfgang von Goethe: „Az élet túl rövid ahhoz, hogy rossz bort igyunk.”</p>
        <p>&copy; 2024 Gypo Winery. Minden jog fenntartva.</p>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
