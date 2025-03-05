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
          ORDER BY rendelesek.rendeles_datuma ASC";
$stmt = $pdo->query($query);
$rendelesek = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    
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
           .wine-card img.card-img-top {
             width: 100%;
             height: auto;
             max-height: 300px;
             object-fit: contain; /* Az arányokat megőrzi, és nem vágja le */
            display: block;
        }

        </style>
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

<nav class="navbar navbar-expand-lg navbar-light text-center">
    <div class="container-fluid d-flex flex-column align-items-center">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav text-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Főoldal</a></li>
                <li class="nav-item"><a class="nav-link" href="tortenet.php">Történet</a></li>
                <li class="nav-item"><a class="nav-link" href="boraink.php">Boraink</a></li>
                <li class="nav-item"><a class="nav-link" href="kapcsolat.php">Kapcsolat</a></li>
                <li class="nav-item"><a class="nav-link" href="Kviz.php">Kviz</a></li>
            </ul>
        </div>
                <ul class="navbar-nav text-center">
                   <li class="nav-item"><a class="nav-link" href="admin_borok.php">Admin</a></li>
                   <li class="nav-item"><a class="nav-link" href="rendelesek.php">Admin rendelések</a></li>
               </ul>
          
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
                <?php if (count($rendelesek) > 0): ?>
                    <?php foreach ($rendelesek as $row): ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo htmlspecialchars($row['vezeteknev'] . ' ' . $row['keresztnev']); ?></td>
                            <td><?php echo $row['rendeles_datuma']; ?></td>
                            <td class="status" id="status_<?php echo $row['ID']; ?>"> <?php echo ucfirst($row['statusz']); ?></td>
                            <td>
    <?php if ($row['statusz'] === 'pending'): ?>
        <button class="btn btn-success approve-btn" data-id="<?php echo $row['ID']; ?>">Jóváhagyás</button>
    <?php else: ?>
        <span class="text-success">Teljesítve</span>
    <?php endif; ?>
</td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nincs megjeleníthető rendelés.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".approve-btn").forEach(button => {
        button.addEventListener("click", function() {
            let orderId = this.getAttribute("data-id");

            fetch("update_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "order_id=" + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    let statusCell = document.getElementById("status_" + orderId);
                    statusCell.innerText = "Completed"; // UI frissítése
                    this.replaceWith(document.createTextNode("Teljesítve")); // Gomb eltávolítása
                } else {
                    alert("Hiba: " + data.message);
                }
            })
            .catch(error => console.error("Hálózati hiba:", error));
        });
    });
});
</script>


    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="darkmode.js"></script>
    <script src="translate.js"></script>
    <script src="user-menu.js"></script>
</body>
</html>