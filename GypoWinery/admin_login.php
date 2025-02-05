<?php
session_start();
include('config.php');

$connection = mysqli_connect("localhost", "root", "", "gypowinery");
if (!$connection) {
    die("Database connection error: " . mysqli_connect_error());
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';

    if (empty($email) || empty($password)) {
        echo "Email and password fields cannot be empty.";
        exit;
    }

    $sql = "SELECT id, jelszo, usertype FROM login WHERE email = ?";
    $stmt = mysqli_prepare($connection, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_array($result)) {
            if (password_verify($password, $row["jelszo"])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['usertype'] = $row['usertype'];
                
                if ($row["usertype"] == "admin") {
                    header("Location: admin_borok.php");
                    exit;
                } else {
                    header("Location: index.php");
                    exit;
                }
            } else {
                echo "Email or password incorrect";
            }
        } else {
            echo "Email or password incorrect";
        }
    } else {
        echo "Query preparation failed.";
    }
}
?>
