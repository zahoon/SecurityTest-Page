<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //  BASIC VALIDATION
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=All fields are required");
        exit;
    }

    // CHECK IF THE EMAIL DAH ADA OR BELUM
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header("Location: register.php?error=Email is already registered");
            exit;
        }

        // HASH THE PASSWORD
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // INSERT THE NEW USER INTO DATABASE (prepared statement to prevent SQL injection)
        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        //  REDIRECT TO LOGIN PAGE WITH SUCCESS MESSAGE
        header("Location: login.php?success=Registration successful! Please log in.");
        exit;

    } catch (PDOException $e) {
        header("Location: register.php?error=Database error: " . $e->getMessage());
        exit;
    }
} else {
    //  REDIRECT BACK IF NOT A POST REQUEST
    header("Location: register.php");
    exit;
}
?>