<?php
include "db.php";

if(isset($_POST['signup'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Hash the password (Never store plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. Check if username already exists
    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        echo "Username already taken!";
    } else {
        // 3. Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if($stmt->execute()){
            header("Location: game.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - 7Trails</title>
    <link rel="stylesheet" href="loginpage.css">
</head>
<body>
    <div id="background">7Trails</div>
    <form class="box-box" method="POST" action="">
        <div class="login-box">
            <input type="text" name="username" placeholder="Create Username" required>
            <br>
            <input type="password" name="password" placeholder="Create Password" required>
            <div>
                <button type="submit" name="signup" class="btn">Create Account</button>
                <a href="loginpage.php" class="btn" style="text-decoration:none;">Back to Login</a>
            </div>
        </div>
    </form>
</body>
</html>