<?php
header('Content-Type: application/json');

// Adatbázis kapcsolat
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gypowinery";

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolódási hiba kezelése
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kapcsolódási hiba: " . $conn->connect_error]);
    exit;
}

// Kérjük a POST adatokat
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id) || !isset($data->quiz_completed)) {
    echo json_encode(["status" => "error", "message" => "Hiányzó adat"]);
    exit;
}

$user_id = $data->user_id;
$quiz_completed = $data->quiz_completed;

// Ellenőrizzük, hogy a felhasználó létezik-e
$sql = "SELECT * FROM login WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Felhasználó nem található"]);
    exit;
}

// Ha a felhasználó létezik, frissítjük a kvíz státuszát és hozzárendelünk egy kupont
$sql = "UPDATE login SET quiz_completed = ? WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $quiz_completed, $user_id);

if ($stmt->execute()) {
    // Nézzük meg, hogy a felhasználónak már van-e kuponja
    $sql = "SELECT coupon_code FROM login WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (empty($row['coupon_code'])) {
        // Keressünk egy elérhető kupont
        $sql = "SELECT kupon_kod FROM kuponok WHERE kiosztott = 0 LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $coupon = $result->fetch_assoc()['kupon_kod'];

            // Frissítsük a kupont a felhasználóhoz és állítsuk kiosztottra
            $sql = "UPDATE login SET coupon_code = ? WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $coupon, $user_id);
            $stmt->execute();

            $sql = "UPDATE kuponok SET kiosztott = 1 WHERE kupon_kod = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $coupon);
            $stmt->execute();

            echo json_encode(["status" => "success", "message" => "Kvíz mentése sikeres", "coupon" => $coupon]);
            exit;
        }
    }
    echo json_encode(["status" => "success", "message" => "Kvíz mentése sikeres, de nincs elérhető kupon"]);
} else {
    echo json_encode(["status" => "error", "message" => "Hiba történt a kvíz mentésekor"]);
}

// Kapcsolat lezárása
$stmt->close();
$conn->close();
?>
