<?php
// Database connection
include("config.php");
include("branding.php");

include("social_accounts.php");

session_name("AdminSession");
session_start();
// Check if the user is not logged in, redirect to the login page
if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login-dashboard.php");
    exit;
}

if (isset($_GET['artist_id'])) {
    if (isset($_GET['artist_id']) && $_GET['unban'] === 'true') {
        $artist_idToEdit = $_GET['artist_id'];
        // Update the user's profile in the database
        $sql = "UPDATE artists SET
            is_banned = :is_banned
            WHERE artist_id = :artist_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':is_banned' => 0,
            ':artist_id' => $artist_idToEdit,
        ]);
        header("Location: success_page.php?success=true&ban=true");
        exit;
    }
    if (isset($_GET['artist_id']) && $_GET['ban'] === 'true') {
        $artist_idToEdit = $_GET['artist_id'];
        // Update the user's profile in the database
        $sql = "UPDATE artists SET
            is_banned = :is_banned
            WHERE artist_id = :artist_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':is_banned' => 1,
            ':artist_id' => $artist_idToEdit,
        ]);
        header("Location: success_page.php?success=true&ban=true");
        exit;
    }
}
?>