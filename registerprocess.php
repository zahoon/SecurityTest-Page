<?php
require 'db.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- Validation (Basic) ---
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=All fields are required");
        exit;
    }

    // --- Check if email already exists ---
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header("Location: register.php?error=Email is already registered");
            exit;
        }

        // --- Hash the password (CRITICAL FOR SECURITY) ---
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // --- Insert the new user into the database ---
        // We use prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        // --- Redirect to login page with success message ---
        header("Location: login.php?success=Registration successful! Please log in.");
        exit;

    } catch (PDOException $e) {
        // Handle database errors
        header("Location: register.php?error=Database error: " . $e->getMessage());
        exit;
    }
} else {
    // If not a POST request, redirect to register page
    header("Location: register.php");
    exit;
}
?>