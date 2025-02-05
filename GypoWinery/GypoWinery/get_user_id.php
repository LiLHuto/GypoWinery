<?php
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Felhasználó ID lekérése a session-ból

    // Adatbázis kapcsolat
    $servername = "localhost";
    $username = "root"; // XAMPP alapértelmezett felhasználó
    $password = ""; // XAMPP alapértelmezett jelszó
    $dbname = "gypowinery"; // Az adatbázis neve

    // Kapcsolódás a MySQL adatbázishoz
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kapcsolódási hiba kezelése
    if ($conn->connect_error) {
        echo json_encode(['user_id' => null, 'quiz_completed' => null, 'message' => 'Kapcsolódási hiba: ' . $conn->connect_error]);
        exit;
    }

    // Kérdezzük le, hogy a felhasználó kitöltötte-e a kvízt
    $sql = "SELECT quiz_completed FROM login WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['user_id' => $user_id, 'quiz_completed' => $row['quiz_completed']]);
    } else {
        echo json_encode(['user_id' => null, 'quiz_completed' => null]);
    }

    // Kapcsolat lezárása
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['user_id' => null, 'quiz_completed' => null]);
}
?>
