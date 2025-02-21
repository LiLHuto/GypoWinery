<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gypowinery";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kapcsolódási hiba: " . $conn->connect_error]);
    exit;
}

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Felhasználó nincs bejelentkezve."]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT quiz_completed, coupon_code FROM login WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Felhasználó nem található"]);
    exit;
}

$row = $result->fetch_assoc();

$response = [
    "status" => "success",
    "quiz_completed" => (bool) $row['quiz_completed'],
    "coupon" => $row['coupon_code']
];

echo json_encode($response);

$conn->close();
?>
