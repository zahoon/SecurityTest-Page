<?php
session_start();
require 'db.php';

// FETCH FROM LOGIN.PHP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Email and password are required");
        exit;
    }

    try {
        // 1- CHECK IS IT AN ADMIN ?
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';

            header("Location: dashboard.php");
            exit;
        }

        // 2- CHECK IS IT A NORMAL USER ? (only runs if the admin check failed)
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'user';

            header("Location: dashboard.php");
            exit;
        }

        // 3- FAILED BOTH CHECKS
        header("Location: login.php?error=Invalid email or password");
        exit;
        
        // 4- DATABASE ERROR HANDLING
    } catch (PDOException $e) {
        header("Location: login.php?error=Database error: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>