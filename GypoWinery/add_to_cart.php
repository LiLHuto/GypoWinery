<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Nincs bejelentkezve."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$bor_id = $_POST['bor_id'];  // Termék ID
$quantity = $_POST['quantity'];  // Mennyiség

// Ellenőrizze, hogy a termék már a kosárban van-e
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND bor_id = :bor_id");
$stmt->execute(['user_id' => $user_id, 'bor_id' => $bor_id]);
$item = $stmt->fetch();

if ($item) {
    // Ha már van a kosárban, frissítse a mennyiséget
    $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND bor_id = :bor_id");
    $stmt->execute(['quantity' => $quantity, 'user_id' => $user_id, 'bor_id' => $bor_id]);
    echo json_encode(["success" => "A termék mennyisége frissítve lett."]);
} else {
    // Ha nincs a kosárban, hozzáadja
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, bor_id, quantity) VALUES (:user_id, :bor_id, :quantity)");
    $stmt->execute(['user_id' => $user_id, 'bor_id' => $bor_id, 'quantity' => $quantity]);
    echo json_encode(["success" => "A termék hozzáadva a kosárhoz."]);
}
?>