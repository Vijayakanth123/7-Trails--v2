<?php
session_start();      // Join the current session
session_unset();      // Clear all variables (username, etc.)
session_destroy();    // Kill the session completely
header("Location: loginpage.php"); // Send user back to login
exit();
?>