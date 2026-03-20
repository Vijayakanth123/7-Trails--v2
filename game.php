<?php
session_start();

include "db.php";

// If the 'user' session variable isn't set, kick them back to login
if (!isset($_SESSION['user'])) {
    header("Location: loginpage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['user'];
    $result = $_POST['result']; // We will send 'win' or 'loss' from JS

    if ($result == 'win') {
        // Increment wins and current streak
        $sql = "UPDATE users SET wins = wins + 1, streak = streak + 1 WHERE username = ?";
    } else {
        // Increment losses and reset current streak to 0
        $sql = "UPDATE users SET losses = losses + 1, streak = 0 WHERE username = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        // After updating, let's check if the current streak is the NEW top streak
        $conn->query("UPDATE users SET mstreak = streak WHERE streak > mstreak AND username = '$username'");
        exit();
    }
}

$username = $_SESSION['user'];
$query = $conn->prepare("SELECT wins, losses, streak FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$userData = $query->get_result()->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7 Trails</title>
    <link rel="stylesheet" href="game.css">
</head>
<body>

<nav>
    <div class="nav-inside">
        <div>
            <button class="nav-elements">about</button>
            <a href="logout.php" ><button class="nav-elements">logout</button></a>
        </div>
    </div>
</nav>

<div class="Content-holder">
    <div class="game-container" id="gameContainer">
        <div class="stats-bar" id="stats">
            Wins: <span id="wins"><?php echo $userData['wins']; ?></span>  
            Losses: <span id="losses"><?php echo $userData['losses']; ?></span> | 
            Streak: <span id="streak"><?php echo $userData['streak']; ?></span>
        </div>
        <div class="inputs-container">
            <div class="input-row">
                <div class="input-group">
                    <input type="number" min="1" max="9">
                    <input type="number" min="1" max="9">
                    <input type="number" min="1" max="9">
                </div>
                <div class="color-indicators">
                    <div class="color-indicator"></div>
                    <div class="color-indicator"></div>
                    <div class="color-indicator"></div>
                </div>
            </div>
        </div>
        <button class="submit-btn" onclick="handleSubmit()">Enter</button>
        <button class="reset-btn"  onclick="handleresetgame()">Reset Game</button>
    </div>
</div>

<script src="game.js"></script>
</body>
</html>