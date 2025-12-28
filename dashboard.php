<?php
session_start();
require 'db.php'; // Ensure db.php is included

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
            
            // Redirect to self to prevent resubmission on refresh
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            $error = "Error posting comment: " . $e->getMessage();
        }
    }
}

// --- FETCH ALL COMMENTS ---
try {
    // Get all comments, newest first
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

    <div class="container p-6 mx-auto mt-10 space-y-8">
        
        <?php if ($role === 'admin'): ?>
            <div class="p-8 bg-gray-700 rounded-lg shadow-md border border-gray-600">
                <h1 class="text-3xl font-bold text-white">System Overview</h1>
                <p class="mt-4 text-gray-300">Welcome, Admin. You have full control here.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="p-6 bg-gray-800 rounded shadow border border-gray-600">
                        <h3 class="font-bold text-lg text-red-400">Total Comments</h3>
                        <p class="text-2xl mt-2"><?php echo count($comments); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($role === 'user'): ?>
            <div class="p-8 bg-white rounded-lg shadow-md">
                <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                <p class="mt-4 text-gray-700">Welcome back! Check the discussion board below.</p>
            </div>
        <?php endif; ?>

        <div class="p-8 rounded-lg shadow-md <?php echo ($role === 'admin') ? 'bg-gray-700 border border-gray-600' : 'bg-white'; ?>">
            <h2 class="text-2xl font-bold <?php echo ($role === 'admin') ? 'text-white' : 'text-gray-900'; ?>">Discussion Board</h2>
            
            <form action="" method="POST" class="mt-6 space-y-4">
                <div>
                    <label for="comment_text" class="block text-sm font-medium <?php echo ($role === 'admin') ? 'text-gray-300' : 'text-gray-700'; ?>">
                        Leave a comment as <strong><?php echo $username; ?></strong>
                    </label>
                    <textarea name="comment_text" id="comment_text" rows="3" required
                        class="w-full px-3 py-2 mt-1 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                        <?php echo ($role === 'admin') ? 'bg-gray-800 border-gray-600 text-white placeholder-gray-400' : 'border-gray-300 text-gray-900'; ?>"
                        placeholder="Write something..."></textarea>
                </div>
                <button type="submit" name="submit_comment"
                        class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Post Comment
                </button>
            </form>

            <div class="mt-8 space-y-4">
                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="p-4 rounded border <?php echo ($role === 'admin') ? 'bg-gray-800 border-gray-600' : 'bg-gray-50 border-gray-200'; ?>">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <span class="font-bold <?php echo ($c['role'] === 'admin') ? 'text-red-500' : 'text-blue-600'; ?>">
                                        <?php echo htmlspecialchars($c['username']); ?>
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded ml-2 <?php echo ($c['role'] === 'admin') ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                        <?php echo ucfirst($c['role']); ?>
                                    </span>
                                </div>
                                <span class="text-sm <?php echo ($role === 'admin') ? 'text-gray-400' : 'text-gray-500'; ?>">
                                    <?php echo date('M d, Y h:i A', strtotime($c['time'])); ?>
                                </span>
                            </div>
                            <p class="<?php echo ($role === 'admin') ? 'text-gray-200' : 'text-gray-800'; ?>">
                                <?php echo nl2br(htmlspecialchars($c['comment'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="<?php echo ($role === 'admin') ? 'text-gray-400' : 'text-gray-500'; ?>">No comments yet. Be the first to say hello!</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>
</html>