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
    <title>7Trails - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 font-sans">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="mb-10">
                    <h1 class="text-7xl font-bold text-emerald-800">7Trails</h1>
                </div>

                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Username</label>
                        <input type="text" name="username" required 
                            class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="Username">
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <label class="block text-sm font-medium text-stone-700">Password</label>
                            <a href="#" class="text-xs text-emerald-600 hover:underline">Forgot password?</a>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" name="login"
                        class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 rounded-lg transition-colors shadow-lg">
                        Sign In
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-stone-600 text-sm">
                        Don't have an account? 
                        <a href="signup.php" class="text-emerald-700 font-semibold hover:underline">Create one</a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>