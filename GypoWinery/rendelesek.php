<?php
include ('config2.php'); // Csatlakozás az adatbázishoz

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'admin') {
    echo "<script>alert('Nincs jogosultságod az oldal megtekintéséhez!'); window.location.href='index.php';</script>";
    exit();
}

// Rendelések lekérése az adatbázisból
$query = "SELECT rendelesek.ID, login.vezeteknev, login.keresztnev, rendelesek.rendeles_datuma, rendelesek.statusz 
          FROM rendelesek 
          JOIN login ON rendelesek.user_id = login.ID 
          ORDER BY rendelesek.rendeles_datuma DESC";
$stmt = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rendelések</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="darkmode.css">
    <link rel="stylesheet" href="user-menu.css">
    <link rel="stylesheet" href="darkmodecard.css">
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
                var flagsContainer = document.querySelector("#flags-container");
                var darkmodeContainer = document.querySelector("#darkmode-container");
                if (flagsContainer && darkmodeContainer) {
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
                <li class="mav-item"><a href="rendelesek.php">Rendelések</a></li>
            </ul>
        </nav>  
    </header>

    <div class="container mt-5">
        <h2 class="text-center">Rendelések kezelése</h2>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Vevő neve</th>
                    <th>Rendelés dátuma</th>
                    <th>Állapot</th>
                    <th>Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td><?php echo htmlspecialchars($row['vezeteknev'] . ' ' . $row['keresztnev']); ?></td>
                        <td><?php echo $row['rendeles_datuma']; ?></td>
                        <td class="status" id="status_<?php echo $row['ID']; ?>"> <?php echo ucfirst($row['statusz']); ?></td>
                        <td>
                            <?php if ($row['statusz'] === 'pending'): ?>
                                <button class="btn btn-success" onclick="approveOrder(<?php echo $row['ID']; ?>)">Jóváhagyás</button>
                            <?php else: ?>
                                <span class="text-success">Teljesítve</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="darkmode.js"></script>
    <script src="translate.js"></script>
    <script src="user-menu.js"></script>
</body>
</html>
