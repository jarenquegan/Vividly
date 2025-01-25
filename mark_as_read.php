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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_as_read') {
    try {
        // Using a transaction for atomicity
        $conn->beginTransaction();

        $notificationId = $_POST['notification_id'];

        // Update the 'is_read' status in the database
        $sqlMarkAsRead = "UPDATE notifications SET is_read = 1 WHERE notification_id = :notification_id AND receiver_id = :artist_id";
        $stmtMarkAsRead = $conn->prepare($sqlMarkAsRead);
        $stmtMarkAsRead->bindParam(':notification_id', $notificationId);
        $stmtMarkAsRead->bindParam(':artist_id', $artist_id);
        $stmtMarkAsRead->execute();

        // Commit the transaction
        $conn->commit();

        error_log("Notification marked as read: notification_id=$notificationId, artist_id=$artist_id");

        // Return a success response
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollBack();

        // Log the error for debugging
        error_log("Error marking notification as read: " . $e->getMessage());

        // Return an error response
        header('Content-Type: application/json');
        echo json_encode(['error' => 'An error occurred while marking the notification as read.']);
        exit;
    }
}
?>
