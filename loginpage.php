<?php
session_start();
include "db.php";

if(isset($_POST['login'])){
    $username=$_POST['username'];
    $password=$_POST['password'];

    $sql="SELECT * FROM users WHERE username='$username'";
    $result=$conn->query($sql);

    if($result->num_rows>0){
        $row=$result->fetch_assoc();

        if(password_verify($password,$row['password'])){
            $_SESSION['user']=$username;
            header("Location: game.php");
        } else {
            echo "Wrong password";
        }
    } else {
        echo "User not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginpage.css">
    <title>Login Page</title>
</head>
<body>
    <div id="background">7Trails</div>
    <form method="POST" action = ""  class="box-box">
        <div class="login-box">
            <input type="text" name="username"     placeholder="Username">
            <br>
            <input type="Password" name="password" placeholder="Password">
            <div>
                <button name="login"  class="btn">Login</button>
                <a href="signup.php" class="btn">Sign Up</a>
            </div>
        </div>
    </form>
</body>
</html>