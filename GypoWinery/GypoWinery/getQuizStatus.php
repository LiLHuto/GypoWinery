<?php
header('Content-Type: application/json');

// Adatbázis kapcsolat
$servername = "localhost";
$username = "root"; // XAMPP alapértelmezett felhasználó
$password = ""; // XAMPP alapértelmezett jelszó
$dbname = "gypowinery"; // Az adatbázis neve

// Kapcsolódás a MySQL adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolódási hiba kezelése
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kapcsolódási hiba: " . $conn->connect_error]);
    exit;
}

// Kérjük a POST adatokat
$data = json_decode(file_get_contents("php://input"));

// Ellenőrizzük, hogy léteznek-e a szükséges adatok
if (!isset($data->user_id)) {
    echo json_encode(["status" => "error", "message" => "Hiányzó adat"]);
    exit;
}

$user_id = $data->user_id;

// Ellenőrizzük, hogy a felhasználó létezik-e
$sql = "SELECT quiz_completed FROM login WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Felhasználó nem található"]);
    exit;
}

// Ha a felhasználó létezik, visszaadjuk a kvíz állapotát
$row = $result->fetch_assoc();
if (isset($data->quiz_completed)) {
    // Frissítés a kvíz státusza
    $quiz_completed = $data->quiz_completed;
    $sql = "UPDATE login SET quiz_completed = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quiz_completed, $user_id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Kvíz mentése sikeres"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Hiba történt a kvíz mentésekor"]);
    }
} else {
    // Visszaadjuk a kvíz állapotát (már kitöltötte-e)
    echo json_encode(["status" => "success", "quiz_completed" => $row['quiz_completed']]);
}

// Kapcsolat lezárása
$stmt->close();
$conn->close();
?>
