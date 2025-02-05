<?php
session_start();
session_unset();  // Session változók törlése
session_destroy();  // Session megsemmisítése
header("Location: index.php");  // Vissza a főoldalra
exit();
?>