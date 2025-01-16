<?php
$servername = "localhost";
$username = "root"; // vagy a megfelelő felhasználó
$password = ""; // vagy a megfelelő jelszó
$dbname = "gypowinery"; // adatbázis neve

// Csatlakozás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizzük a kapcsolatot
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ha a kosárba tett termékekről van szó
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bor_id = $_POST['bor_id'];
    $action = $_POST['action']; // action: "add" vagy "remove"
    
    if ($action == 'add') {
        // Kosárba helyezés: készlet csökkentése
        $sql = "UPDATE borok SET keszlet = keszlet - 1 WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bor_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'remove') {
        // Kosárból való eltávolítás: készlet növelése
        $sql = "UPDATE borok SET keszlet = keszlet + 1 WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bor_id);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode(["status" => "success"]);
    exit;
}

// Kosár adatainak lekérése (GET)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM borok WHERE keszlet > 0";
    $result = $conn->query($sql);
    
    $borok = [];
    while($row = $result->fetch_assoc()) {
        $borok[] = $row;
    }

    echo json_encode($borok);
    exit;
}

$conn->close();
?>
