<?php
include("config.php");
include("branding.php");

if (isset($_GET['admin']) && $_GET['admin'] === 'true') {
    session_name("AdminSession");
    session_start();
} else {
    session_start();
}

// Check if user ID is available
if (isset($_SESSION['artist_id'])) {
    $userId = $_SESSION['artist_id'];

    // Unset session variables for the specific user
    unset($_SESSION['artist_id']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['artist_pic']);
    unset($_SESSION['emailaddress']);
    unset($_SESSION['firstname']);
    unset($_SESSION['lastname']);
    unset($_SESSION['bio']);

    if (isset($_GET['admin']) && $_GET['admin'] === 'true') {
        header("Location: login-dashboard.php");
        exit;
    } else {
        header("Location: login.php");
        exit;
    }
}
