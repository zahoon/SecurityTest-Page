<?php
session_start();
require 'db.php';

// --- SECURITY CHECK ---
// If user is NOT logged in OR is NOT an admin, redirect them.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$admin_msg = "";

// --- HANDLE REGISTRATION ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_user = trim($_POST['new_username']);
    $new_email = trim($_POST['new_email']);
    $new_pass = $_POST['new_password'];

    if (!empty($new_user) && !empty($new_email) && !empty($new_pass)) {
        try {
            // Check if email already exists in admins
            $check = $pdo->prepare("SELECT id FROM admin WHERE email = ?");
            $check->execute([$new_email]);
            
            if ($check->rowCount() > 0) {
                $admin_msg = "<div class='p-3 bg-red-900/50 border border-red-500 text-red-200 rounded'>Error: Admin email already exists!</div>";
            } else {
                // Hash the password
                $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
                
                // Insert into ADMINS table
                $stmt = $pdo->prepare("INSERT INTO admin (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$new_user, $new_email, $hashed_password]);
                
                $admin_msg = "<div class='p-3 bg-green-900/50 border border-green-500 text-green-200 rounded'>Success: New Admin '$new_user' created!</div>";
            }
        } catch (PDOException $e) {
            $admin_msg = "<div class='p-3 bg-red-900/50 border border-red-500 text-red-200 rounded'>Database Error: " . $e->getMessage() . "</div>";
        }
    } else {
        $admin_msg = "<div class='p-3 bg-red-900/50 border border-red-500 text-red-200 rounded'>Error: All fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes admin-gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animated-admin {
            background: linear-gradient(-45deg, #000000, #a05726ff, #800000, #555555ff);
            background-size: 400% 400%;
            animation: admin-gradient 15s ease infinite;
        }
        
        /* Floating Card Animation */
        @keyframes float {
            0% { transform: translateY(0px); box-shadow: 0 5px 15px 0px rgba(0,0,0,0.5); }
            50% { transform: translateY(-10px); box-shadow: 0 25px 15px 0px rgba(0,0,0,0.2); }
            100% { transform: translateY(0px); box-shadow: 0 5px 15px 0px rgba(0,0,0,0.5); }
        }
        .floating-card { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="animated-admin flex flex-col items-center justify-center min-h-screen text-gray-100 p-4">

    <div class="absolute top-6 left-6">
        <a href="dashboard.php" class="flex items-center text-gray-300 hover:text-white transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="floating-card w-full max-w-md p-8 space-y-6 bg-gray-900/80 backdrop-blur rounded-lg shadow-2xl">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white">New Admin Access</h1>
            <p class="text-sm text-gray-300 mt-3">Create credentials for a new administrator.</p>
        </div>

        <?php echo $admin_msg; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-400">Username</label>
                <input type="text" name="new_username" required placeholder="e.g. SystemAdmin"
                    class="w-full px-4 py-3 mt-1 bg-black border border-red-900 rounded-md text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-red-600 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400">Email Address</label>
                <input type="email" name="new_email" required placeholder="admin@gmail.com"
                    class="w-full px-4 py-3 mt-1 bg-black border border-red-900 rounded-md text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-red-600 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400">Secure Password</label>
                <input type="password" name="new_password" required placeholder="••••••••"
                    class="w-full px-4 py-3 mt-1 bg-black border border-red-900 rounded-md text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-red-600 transition">
            </div>

            <button type="submit" 
                    class="w-full px-4 py-3 font-bold text-white bg-red-700 rounded-md hover:bg-red-600 hover:shadow-lg hover:shadow-red-900/50 transition duration-200">
                Create Admin Account
            </button>
        </form>
    </div>

</body>
</html>