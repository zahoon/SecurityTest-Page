<?php
session_start();

// 1. Security: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Get User Info
$username = htmlspecialchars($_SESSION['username']);
$role = $_SESSION['role']; // This will be 'admin' or 'user'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo ($role === 'admin') ? 'bg-gray-800 text-gray-100' : 'bg-gray-100 text-gray-900'; ?>">

    <nav class="<?php echo ($role === 'admin') ? 'bg-gray-900' : 'bg-white'; ?> shadow-md">
        <div class="container px-6 py-4 mx-auto">
            <div class="flex items-center justify-between">
                
                <div class="text-xl font-bold <?php echo ($role === 'admin') ? 'text-red-500' : 'text-blue-600'; ?>">
                    <?php echo ($role === 'admin') ? 'ADMIN PANEL' : 'My User Dashboard'; ?>
                </div>

                <div class="flex items-center">
                    <span class="mr-4 <?php echo ($role === 'admin') ? 'text-gray-300' : 'text-gray-700'; ?>">
                        <?php echo ucfirst($role); ?>: <strong><?php echo $username; ?></strong>
                    </span>
                    
                    <a href="logout.php" 
                       class="px-4 py-2 font-medium text-white rounded-md transition duration-300 
                       <?php echo ($role === 'admin') ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'; ?>">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container p-6 mx-auto mt-10">
        
        <?php if ($role === 'admin'): ?>
            <div class="p-8 bg-gray-700 rounded-lg shadow-md border border-gray-600">
                <h1 class="text-3xl font-bold text-white">System Overview</h1>
                <p class="mt-4 text-gray-300">Welcome, Admin. You have full control here.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="p-6 bg-gray-800 rounded shadow border border-gray-600">
                        <h3 class="font-bold text-lg text-red-400">Total Users</h3>
                        <p class="text-2xl mt-2">1,245</p>
                    </div>
                    <div class="p-6 bg-gray-800 rounded shadow border border-gray-600">
                        <h3 class="font-bold text-lg text-red-400">Security Logs</h3>
                        <p class="text-2xl mt-2">All Clear</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($role === 'user'): ?>
            <div class="p-8 bg-white rounded-lg shadow-md">
                <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                <p class="mt-4 text-gray-700">Welcome back! Here is your personal activity feed.</p>
                
                <div class="mt-6 p-4 bg-blue-50 text-blue-800 rounded border border-blue-200">
                    <p>You have no new notifications.</p>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>