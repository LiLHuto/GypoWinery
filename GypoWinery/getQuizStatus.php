<?php
header('Content-Type: application/json');

// Adatbázis kapcsolat
$servername = "localhost";
$username = "root"; // XAMPP alapértelmezett felhasználó
$password = ""; // XAMPP alapértelmezett jelszó
$dbname = "gypowinery";

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolódási hiba ellenőrzése
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kapcsolódási hiba: " . $conn->connect_error]);
    exit;
}

// Kérjük a session-t, hogy megnézzük, a felhasználó be van-e jelentkezve
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Felhasználó nincs bejelentkezve."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Ellenőrizzük, hogy a felhasználó kitöltötte-e a kvízt és van-e kuponja
$sql = "SELECT quiz_completed, coupon_code FROM login WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Felhasználó nem található"]);
    exit;
}

// Adatok visszaadása
$row = $result->fetch_assoc();
$quiz_completed = $row['quiz_completed'];
$coupon_code = $row['coupon_code'];

$response = [
    "status" => "success",
    "quiz_completed" => (bool) $quiz_completed
];

// Ha a felhasználó kitöltötte a kvízt, de még nincs kuponja, akkor adjunk neki egyet
if ($quiz_completed && empty($coupon_code)) {
    // Keressünk egy szabad kupont
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

        $coupon_code = $coupon; // A válaszban küldjük vissza
    }
}

// Ha a felhasználó kapott kupont, adjuk hozzá a válaszhoz
if (!empty($coupon_code)) {
    $response["coupon_code"] = $coupon_code;
}

echo json_encode($response);

// Lezárjuk a kapcsolatot
$stmt->close();
$conn->close();
?>
