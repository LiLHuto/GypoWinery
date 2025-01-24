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
if (!isset($data->user_id) || !isset($data->quiz_completed)) {
    echo json_encode(["status" => "error", "message" => "Hiányzó adat"]);
    exit;
}

$user_id = $data->user_id;
$quiz_completed = $data->quiz_completed;

// Ellenőrizzük, hogy a felhasználó létezik-e
$sql = "SELECT * FROM login WHERE ID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL hiba: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Ha a felhasználó nem található
    echo json_encode(["status" => "error", "message" => "Felhasználó nem található"]);
    exit;
}

// Ha a felhasználó létezik, frissítjük a kvíz státuszát
$sql = "UPDATE login SET quiz_completed = ? WHERE ID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL hiba: " . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $quiz_completed, $user_id);

// Ha sikeres a mentés
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Kvíz mentése sikeres"]);
} else {
    // Ha hiba történt a mentés során
    echo json_encode(["status" => "error", "message" => "Hiba történt a kvíz mentésekor"]);
}

// Kapcsolat lezárása
$stmt->close();
$conn->close();
?>
