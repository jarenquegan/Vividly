<?php
include("config.php");
include("branding.php");

session_start();

// Check if the user is not logged in, redirect to the login page
if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit;
}

$artist_id = $_SESSION['artist_id'];

// include("mark_as_read.php"); 

$sqlFetchNotifications = "
    SELECT n.*, a.artist_id as sender_id, a.username as sender_username, a.emailaddress as sender_emailaddress, a.firstname as sender_firstname, a.middlename as sender_middlename, a.lastname as sender_lastname, a.suffix as sender_suffix, a.artist_pic as sender_artist_pic, n.notification_type as notiftype, n.artwork_id as artworkId
    FROM notifications n
    JOIN artists a ON n.sender_id = a.artist_id
    WHERE n.receiver_id = :artist_id
    ORDER BY n.created_at DESC";
$stmtFetchNotifications = $conn->prepare($sqlFetchNotifications);
$stmtFetchNotifications->bindParam(':artist_id', $artist_id);
$stmtFetchNotifications->execute();
$notifications = $stmtFetchNotifications->fetchAll(PDO::FETCH_ASSOC);

// Check if there are unread notifications
$unreadNotificationsExist = array_reduce(
    $notifications,
    function ($carry, $notification) {
        return $carry || !$notification['is_read'];
    },
    false
);

// Set the session variable
$_SESSION['unread_notifications'] = $unreadNotificationsExist;
// echo 'Unread Notifications: ' . $unreadNotificationsExist;
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: application/json');
echo json_encode($notifications);
?>
