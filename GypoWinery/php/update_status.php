<?php
session_start();
include ('config2.php'); // Adatbázis kapcsolat

// Csak akkor hajtódjon végre, ha admin és van rendelési ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin') {
    $order_id = $_POST['order_id'];

    // Frissítjük a rendelés állapotát "completed"-re
    $query = "UPDATE rendelesek SET statusz = 'completed' WHERE ID = ?";
    $stmt = $pdo->prepare($query);
    
    if ($stmt->execute([$order_id])) {
        echo json_encode(["status" => "success", "message" => "Rendelés teljesítve!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Hiba történt!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Nincs jogosultságod vagy hibás kérés!"]);
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".approve-btn").forEach(button => {
        button.addEventListener("click", function() {
            let orderId = this.getAttribute("data-id");

            fetch("update_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "order_id=" + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    let statusCell = document.getElementById("status_" + orderId);
                    statusCell.innerText = "Completed"; // UI frissítése
                    this.replaceWith(document.createTextNode("Teljesítve")); // Gomb eltávolítása
                } else {
                    alert("Hiba: " + data.message);
                }
            })
            .catch(error => console.error("Hálózati hiba:", error));
        });
    });
});
</script>

</body>
</html>