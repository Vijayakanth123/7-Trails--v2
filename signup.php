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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7Trails - Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 font-sans">
    <div class="min-h-screen flex items-center justify-center p-4 text-stone-900">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="mb-8">
                    <h1 class="text-6xl font-bold text-emerald-800">7Trails</h1>
                </div>

                <!-- look at this once -->
                <!-- <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm border border-red-200">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?> -->

                <form action="" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Username</label>
                        <input type="text" name="username" required 
                            class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="Choose a trail name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Email</label>
                        <input type="email" name="email" required 
                            class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="hiker@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Password</label>
                        <input type="password" name="password" required 
                            class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" name="signup" 
                        class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 rounded-lg transition-colors shadow-lg mt-2">
                        Create Account
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-stone-600 text-sm">
                        Already a member? 
                        <a href="loginpage.php" class="text-emerald-700 font-semibold hover:underline">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
