<?php
header('Content-Type: application/json');

// Adatbázis kapcsolat
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gypowinery";

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolódási hiba ellenőrzése
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kapcsolódási hiba: " . $conn->connect_error]);
    exit;
}

// Kérjük a POST adatokat
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id) || !isset($data->quiz_completed) || !isset($data->score)) {
    echo json_encode(["status" => "error", "message" => "Hiányzó adat"]);
    exit;
}

$user_id = $data->user_id;
$quiz_completed = $data->quiz_completed;
$score = $data->score;

// Ellenőrizzük, hogy a felhasználó létezik-e
$sql = "SELECT coupon_code FROM login WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "Felhasználó nem található"]);
    exit;
}

// Ha még nem volt kitöltve, frissítjük az adatbázist
if (!$user['coupon_code'] && $score >= 2) { 
    // Keresünk egy szabad kupont
    $sql = "SELECT kupon_kod FROM kuponok WHERE kiosztott = 0 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $coupon = $result->fetch_assoc()['kupon_kod'];

        // Frissítsük a kupont a felhasználóhoz és állítsuk kiosztottra
        $sql = "UPDATE login SET quiz_completed = ?, coupon_code = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $quiz_completed, $coupon, $user_id);
        $stmt->execute();

        $sql = "UPDATE kuponok SET kiosztott = 1 WHERE kupon_kod = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $coupon);
        $stmt->execute();
    }
}

// Kérjük le a frissített kupont
$sql = "SELECT coupon_code FROM login WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$coupon_code = $user['coupon_code'] ?? null;

echo json_encode([
    "status" => "success",
    "message" => "Kvíz mentése sikeres",
    "coupon" => $coupon_code
]);

$stmt->close();
$conn->close();
?>
