<?php
$servername = "localhost";
$username = "root"; // XAMPP alapértelmezett felhasználó
$password = ""; // XAMPP alapértelmezett jelszó
$dbname = "gypowinery"; // Az adatbázis neve

// Adatbázis kapcsolat
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolódási hiba kezelése
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kapcsolódási hiba: " . $conn->connect_error]));
}

// Ha POST kérés érkezik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bor_id = intval($_POST['bor_id']);
    $action = $_POST['action']; // "add" vagy "remove"

    // Ellenőrizzük, hogy létezik-e a bor ID
    $sql = "SELECT keszlet FROM borok WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bor_id);
    $stmt->execute();
    $stmt->bind_result($keszlet);
    $stmt->fetch();
    $stmt->close();

    if (!$keszlet) {
        echo json_encode(["status" => "error", "message" => "A megadott termék nem található."]);
        exit;
    }

    if ($action === 'add') {
        if ($keszlet > 0) {
            // Készlet csökkentése
            $sql = "UPDATE borok SET keszlet = keszlet - 1 WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $bor_id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Termék hozzáadva a kosárhoz."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Hiba történt a készlet frissítésekor."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Nincs elég készlet."]);
        }
    } elseif ($action === 'remove') {
        // Készlet növelése
        $sql = "UPDATE borok SET keszlet = keszlet + 1 WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bor_id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Termék eltávolítva a kosárból."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hiba történt a készlet frissítésekor."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Ismeretlen művelet."]);
    }
    exit;
}

// Ha GET kérés érkezik (borok listázása)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM borok";
    $result = $conn->query($sql);

    $borok = [];
    while ($row = $result->fetch_assoc()) {
        $borok[] = $row;
    }

    echo json_encode($borok);
    exit;
}

// Kapcsolat lezárása
$conn->close();
?>
