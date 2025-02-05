<?php
include('index_config.php');


if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email=$_POST["email"];
    $password=$_POST["password"];
 
    $sql="select * from login where email= '".$email."' AND password='".$password."' ";
    $result=mysqli_query();
}


?>



<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
<body>
    <center>
        <h1>login form</h1>
        <br><br><br><br>
        <div style="background-color: grey; width: 500px">
        <br><br>


        <form action="#" method="POST">
        <div>
            <label>emailadress</label>
            <input type="text" name="email" required>
        </div>
        <br><br>

        <div>
            <label>password</label>
            <input type="password" name="password" required>
        </div>
        <br><br>

        <div>
            <input type="submit" value="login">
        </div>
        </form>
        <br><br>
        </div>
    </center>
</body>