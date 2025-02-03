<?php
session_start();

$servername = "localhost";  // Vagy a megfelelő host, pl. 127.0.0.1
$username = "root";         // A megfelelő felhasználónév
$password = "";             // A megfelelő jelszó
$dbname = "gypowinery";     // Az adatbázis neve

// Kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Új admin hozzáadása biztonságos jelszó hash-eléssel
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);
    if ($stmt->execute()) {
        echo "<p>Új admin sikeresen hozzáadva!</p>";
    } else {
        echo "<p>Hiba történt az admin hozzáadásakor!</p>";
    }
}

// Új bor hozzáadása
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_wine'])) {
    $name = $_POST['name'];
    $year = $_POST['year'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO borok (name, year, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $year, $price);
    if ($stmt->execute()) {
        echo "<p>Bor sikeresen hozzáadva!</p>";
    } else {
        echo "<p>Hiba történt!</p>";
    }
}

// Bor törlése
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM borok WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p>Bor törölve!</p>";
    }
}

// Borok listázása
$result = $conn->query("SELECT * FROM borok");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h2>Admin Panel - Borok kezelése</h2>
    <a href="logout.php">Kijelentkezés</a>

    <h3>Új admin hozzáadása</h3>
    <form method="post">
        <input type="email" name="email" placeholder="Admin email" required>
        <input type="password" name="password" placeholder="Jelszó" required>
        <button type="submit" name="add_admin">Admin hozzáadása</button>
    </form>

    <h3>Új bor hozzáadása</h3>
    <form method="post">
        <input type="text" name="name" placeholder="Bor neve" required>
        <input type="number" name="year" placeholder="Évjárat" required>
        <input type="number" name="price" placeholder="Ár" required>
        <button type="submit" name="add_wine">Hozzáadás</button>
    </form>

    <h3>Elérhető borok</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Név</th>
            <th>Évjárat</th>
            <th>Ár</th>
            <th>Művelet</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['year']) ?></td>
            <td><?= htmlspecialchars($row['price']) ?></td>
            <td><a href="admin.php?delete=<?= htmlspecialchars($row['id']) ?>">Törlés</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
