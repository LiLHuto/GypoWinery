<?php
session_start();
header('Content-Type: application/json'); // Kényszerítjük a JSON választ

// Kapcsolódás az adatbázishoz (beépítve a fájlba)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gypowinery";

// Kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Adatbázis kapcsolat sikertelen: " . $conn->connect_error]);
    exit;
}

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Be kell jelentkezned!"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// **Lekérjük a még fel nem használt kupont**
$query = "SELECT id, kupon_kod FROM kuponok WHERE kiosztott = 0 ORDER BY RAND() LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $coupon_id = $row['id'];
    $coupon_code = $row['kupon_kod'];

    // **Frissítjük a kupont, hogy már felhasznált legyen**
    $update_query = "UPDATE kuponok SET kiosztott = 1 WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $coupon_id);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "coupon" => $coupon_code]);
    } else {
        echo json_encode(["status" => "error", "message" => "Nem sikerült frissíteni a kupont."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Nincs elérhető kupon."]);
}

$conn->close(); // Kapcsolat lezárása
exit;
?>
