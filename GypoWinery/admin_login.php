<?php
include('fasz.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';

    if (empty($email) || empty($password)) {
        echo "Email and password fields cannot be empty.";
        exit;
    }

    try {
        $sql = "SELECT ID, jelszo, usertype FROM login WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row["jelszo"])) {
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['usertype'] = $row['usertype'];

            echo "Logged in as: " . htmlspecialchars($row["usertype"]);

            if ($row["usertype"] === "admin") {
                header("Location: admin_borok.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            echo "Email or password incorrect";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <center>
        <h1>Admin Form</h1>
        <div style="background-color: grey; width: 500px; padding: 20px; border-radius: 10px;">
            <form action="#" method="POST">
                <div>
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>
                <br>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <br>
                <div>
                    <input type="submit" value="Login">
                </div>
            </form>
    </center>
</body>
</html>