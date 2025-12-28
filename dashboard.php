<?php
session_start();
require 'db.php';

// 1. Security: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Get User Info
$username = htmlspecialchars($_SESSION['username']);
$role = $_SESSION['role'];

// --- HANDLE COMMENT SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
    $comment_text = trim($_POST['comment_text']);
    
    if (!empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comment (username, role, comment) VALUES (?, ?, ?)");
            $stmt->execute([$username, $role, $comment_text]);
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// --- FETCH ALL COMMENTS ---
try {
    $stmt = $pdo->query("SELECT * FROM comment ORDER BY time DESC");
    $comments = $stmt->fetchAll();
} catch (PDOException $e) {
    $comments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* USER ANIMATION */
        @keyframes user-gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animated-user {
            background: linear-gradient(-45deg, #dabd14ff, #db7f29ff, #f05850ff, #c8cacaff);
            background-size: 400% 400%;
            animation: user-gradient 15s ease infinite;
        }

        /* ADMIN ANIMATION */
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
    </style>
</head>

<body class="<?php echo ($role === 'admin') ? 'animated-admin text-gray-100' : 'animated-user text-gray-900'; ?> min-h-screen">

    <nav class="<?php echo ($role === 'admin') ? 'bg-black/80' : 'bg-white/90'; ?> backdrop-blur-sm shadow-md transition-colors">
        <div class="container px-6 py-4 mx-auto">
            <div class="flex items-center justify-between">
                <div class="text-xl font-bold <?php echo ($role === 'admin') ? 'text-red-500' : 'text-orange-600'; ?>">
                    <?php echo ($role === 'admin') ? 'ADMIN PANEL' : 'Customer Dashboard !'; ?>
                </div>

                <div class="flex items-center">
                    <span class="mr-4 <?php echo ($role === 'admin') ? 'text-gray-300' : 'text-gray-700'; ?>">
                        <?php echo ucfirst($role); ?>: <strong><?php echo $username; ?></strong>
                    </span>
                    <a href="logout.php" 
                       class="px-4 py-2 font-medium text-white rounded-md transition duration-300 
                       <?php echo ($role === 'admin') ? 'bg-red-700 hover:bg-red-900' : 'bg-red-600 hover:bg-orange-600'; ?>">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container p-6 mx-auto mt-10 space-y-8">
        
        <?php if ($role === 'admin'): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="p-8 bg-gray-900/80 backdrop-blur rounded-lg shadow-md border border-red-900">
                    <h1 class="text-3xl font-bold text-white">System Overview</h1>
                    <p class="mt-4 text-gray-300">Welcome, Admin. All system information is displayed here.</p>
                    <div class="mt-6 p-6 bg-gray-900 rounded shadow border border-red-900">
                        <h3 class="font-bold text-lg text-red-500">Total Comments</h3>
                        <p class="text-2xl mt-2 text-white"><?php echo count($comments); ?></p>
                    </div>
                </div>

                <div class="p-8 bg-gray-900/80 backdrop-blur rounded-lg shadow-md border border-red-900 flex flex-col justify-center items-center text-center">
                    <h1 class="text-2xl font-bold text-white mb-4">Admin Management</h1>
                    <p class="text-gray-400 mb-6">Create new administrators to help manage the system.</p>
                    
                    <a href="registeradmin.php" 
                       class="px-6 py-3 font-bold text-white bg-red-700 rounded-lg hover:bg-red-600 transition shadow-lg shadow-red-900/50">
                        + Register New Admin !
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($role === 'user'): ?>
            <div class="p-8 bg-white/95 backdrop-blur rounded-lg shadow-md">
                <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                <p class="mt-4 text-gray-700">Welcome back to the System! Check the discussion room below.</p>
            </div>
        <?php endif; ?>

        <div class="p-8 rounded-lg shadow-md <?php echo ($role === 'admin') ? 'bg-gray-900/80 backdrop-blur border border-red-900' : 'bg-white/95 backdrop-blur'; ?>">
            <h2 class="text-3xl font-bold <?php echo ($role === 'admin') ? 'text-white' : 'text-gray-900'; ?>">Discussion Room</h2>
            <h3 class="text-sm mt-3 mb-7 <?php echo ($role === 'admin') ? 'text-gray-300' : 'text-gray-900'; ?>"> This is the place and platform that you can communicate with others in the world ! (its not)</h3>
            
            <form action="" method="POST" class="mt-6 space-y-4">
                <div>
                    <label for="comment_text" class="block text-sm font-medium <?php echo ($role === 'admin') ? 'text-gray-300' : 'text-gray-700'; ?>">
                        Leave a comment as <strong><?php echo $username; ?></strong>
                    </label>
                    <textarea name="comment_text" id="comment_text" rows="3" required
                        class="w-full px-3 py-2 mt-1 border rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-white 
                        <?php echo ($role === 'admin') ? 'bg-gray-900 border-red-900 text-white placeholder-gray-500' : 'border-gray-300 text-gray-900'; ?>"
                        placeholder="anything in ur mind...?"></textarea>
                </div>
                <button type="submit" name="submit_comment"
                        class="px-4 py-2 font-medium text-white rounded-md hover:opacity-90 transition
                        <?php echo ($role === 'admin') ? 'bg-orange-600 hover:bg-red-900' : 'bg-orange-500 hover:bg-yellow-400'; ?>">
                    Post Comment !
                </button>
            </form>

            <div class="mt-8 space-y-4">
                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="p-4 rounded border <?php echo ($role === 'admin') ? 'bg-gray-900 border-red-900' : 'bg-gray-50 border-gray-200 shadow-md'; ?>">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <span class="font-bold <?php echo ($c['role'] === 'admin') ? 'text-red-500' : 'text-yellow-500'; ?>">
                                        <?php echo htmlspecialchars($c['username']); ?>
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded ml-2 <?php echo ($c['role'] === 'admin') ? 'bg-red-900 text-white' : 'bg-gray-200 text-orange-700'; ?>">
                                        <?php echo ucfirst($c['role']); ?>
                                    </span>
                                </div>
                                <span class="text-sm <?php echo ($role === 'admin') ? 'text-gray-400' : 'text-gray-500'; ?>">
                                    <?php echo date('M d, Y h:i A', strtotime($c['time'])); ?>
                                </span>
                            </div>
                            <p class="<?php echo ($role === 'admin') ? 'text-gray-300' : 'text-gray-800'; ?>">
                                <?php echo nl2br(htmlspecialchars($c['comment'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="<?php echo ($role === 'admin') ? 'text-gray-400' : 'text-gray-500'; ?>">There is no comments yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>