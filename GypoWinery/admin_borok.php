<?php
include('fasz.php');

// Ensure only admins can access
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_borok.php");
    exit;
}

// Fetch wines from the database
$query = "SELECT * FROM borok";
$stmt = $pdo->query($query);
$borok = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle wine deletion
if (isset($_POST['delete_wine'])) {
    $wine_id = $_POST['wine_id'];
    $delete_query = "DELETE FROM borok WHERE id = :wine_id";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->execute(['wine_id' => $wine_id]);
    header("Location: admin_borok.php");
    exit;
}

// Handle adding new wine
if (isset($_POST['add_wine'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $insert_query = "INSERT INTO borok (name, price, description, image) VALUES (:name, :price, :description, :image)";
    $insert_stmt = $pdo->prepare($insert_query);
    $insert_stmt->execute(['name' => $name, 'price' => $price, 'description' => $description, 'image' => $image]);
    header("Location: admin_borok.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Borok</title>
</head>
<body>
    <h1>Borok Kezelése</h1>
    
    <?php foreach ($borok as $bor): ?>
        <div>
            <h3><?= htmlspecialchars($bor['name']) ?></h3>
            <p><?= htmlspecialchars($bor['description']) ?></p>
            <p>Ár: <?= htmlspecialchars($bor['price']) ?> Ft</p>
            <img src="<?= htmlspecialchars($bor['image']) ?>" width="100">
            <form method="post">
                <input type="hidden" name="wine_id" value="<?= $bor['id'] ?>">
                <button type="submit" name="delete_wine">Törlés</button>
            </form>
        </div>
    <?php endforeach; ?>
    
    <h2>Új Bor Hozzáadása</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Név" required>
        <input type="number" name="price" placeholder="Ár" required>
        <textarea name="description" placeholder="Leírás" required></textarea>
        <input type="text" name="image" placeholder="Kép URL" required>
        <button type="submit" name="add_wine">Hozzáadás</button>
    </form>
</body>
</html>