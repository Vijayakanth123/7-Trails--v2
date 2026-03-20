<?php
$servername = "localhost";
$username = "root";
$password = "123@l"; // <--- CHANGE THIS to your actual MySQL password!
$dbname = "7trails_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection worked
if ($conn->connect_error) {
    // This stops the page and tells you exactly what went wrong
    die("Database Connection failed: " . $conn->connect_error);
}

// Optional: Tell PHP to use UTF-8 for special characters
$conn->set_charset("utf8mb4");
?>