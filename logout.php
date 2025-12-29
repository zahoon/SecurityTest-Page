<?php
session_start(); // Resume the session

// UNSET ALL THE SESSION
$_SESSION = array();

// DESTROY SESSION COOKIE
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// DESTROY THE SESSION
session_destroy();

// ALLOCATIE TO LOGIN PAGE
header("Location: login.php");
exit;
?>