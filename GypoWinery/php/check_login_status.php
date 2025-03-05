<?php
session_start();

$response = ['isLoggedIn' => false];

if (isset($_SESSION['user_id'])) {
    $response['isLoggedIn'] = true;
}

echo json_encode($response);
?>
