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
    $stmt->bindParam(':nev', $nev);
    $stmt->bindParam(':ar', $ar);
    $stmt->bindParam(':leiras', $leiras);
    $stmt->bindParam(':keszlet', $keszlet);
    $stmt->execute();
    header("Location: admin_borok.php");
    exit();
}

// Bor törlése (először a rendelés tételeket töröljük, majd a bort)
if (isset($_POST['delete_wine'])) {
    $wine_id = $_POST['wine_id'];
    
    // Kapcsolódó rendelés tételek törlése
    $delete_rendeles_query = "DELETE FROM rendeles_tetelek WHERE bor_id = :wine_id";
    $delete_stmt = $pdo->prepare($delete_rendeles_query);
    $delete_stmt->bindParam(':wine_id', $wine_id, PDO::PARAM_INT);
    $delete_stmt->execute();

    // Bor törlése
    $delete_bor_query = "DELETE FROM borok WHERE ID = :wine_id";
    $delete_bor_stmt = $pdo->prepare($delete_bor_query);
    $delete_bor_stmt->bindParam(':wine_id', $wine_id, PDO::PARAM_INT);
    $delete_bor_stmt->execute();

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
    <link rel="stylesheet" href="darkmode.css">
    <link rel="stylesheet" href="user-menu.css">

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

        </style>
</head>
<body>
    <header class="text-center py-3">
        <img src="kepek/gypo2-removebg-preview.png" alt="Gypo Winery Logo" class="logo">
        <h1><a href="index.php" class="text-decoration-none">Gypo Winery</a></h1>
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
        $query = "SELECT borok.*, bor_kepek.kep_url FROM borok LEFT JOIN bor_kepek ON borok.ID = bor_kepek.bor_id";
        $stmt = $pdo->query($query);
        $borok = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <div class="row">
            <?php foreach ($borok as $bor): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="<?= htmlspecialchars($bor['kep_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($bor['nev']) ?>">
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
    <script src="darkmode.js"></script>
    <script src="translate.js"></script>
    <script src="user-menu.js"></script>
</body>
</html>
