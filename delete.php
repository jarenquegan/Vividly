<?php
// Database connection
include("config.php");
include("branding.php");

include("social_accounts.php");

session_start();

// Check if the user is logged in
if (isset($_SESSION['artist_id'])) {
    $artist_id = $_SESSION['artist_id'];

    // Check if the user confirmed the deletion
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['artists']) && $_GET['post'] === 'true') {
        $deleteUserQuery = "DELETE FROM artists WHERE artist_id = :artist_id";
        $deleteUserStmt = $conn->prepare($deleteUserQuery);
        $deleteUserStmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
        $deleteUserStmt->execute();

        // Destroy the session
        session_destroy();

        // Redirect to the previous page with success message
        header("Location: success_page.php?success=true&deleted=true");
        exit;
    }

    // Check if the user confirmed the deletion
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['post']) && $_GET['post'] === 'true' && isset($_GET['id'])) {
        $artwork_id = $_GET['id'];

        $deletePostQuery = "DELETE FROM artworks WHERE artwork_id = :artwork_id";
        $deletePostStmt = $conn->prepare($deletePostQuery);
        $deletePostStmt->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
        $deletePostStmt->execute();

        // Redirect to the previous page with success message
        header("Location: success_page.php?success=true&post=true");
        exit;
    }

    // Check if the user confirmed the deletion
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['previewed']) && $_GET['previewed'] === 'true' && isset($_GET['id'])) {
        $artwork_id = $_GET['id'];

        $deletePostQuery = "DELETE FROM artworks WHERE artwork_id = :artwork_id";
        $deletePostStmt = $conn->prepare($deletePostQuery);
        $deletePostStmt->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
        $deletePostStmt->execute();

        // Redirect to the previous page with success message
        header("Location: success_page.php?success=true&previewed=true");
        exit;
    }

    // Check if the user confirmed the deletion
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['notif']) && $_GET['notif'] === 'true' && isset($_GET['notif_id'])) {
        $notif_id = $_GET['notif_id'];

        $deleteNotifQuery = "DELETE FROM notifications WHERE notification_id = :notification_id";
        $deleteNotifStmt = $conn->prepare($deleteNotifQuery);
        $deleteNotifStmt->bindParam(':notification_id', $notif_id, PDO::PARAM_INT);
        $deleteNotifStmt->execute();

        // Redirect to the previous page with success message
        header("Location: success_page.php?success=true&notif=true");
        exit;
    }

    // Check if the user confirmed the deletion
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['comment']) && $_GET['comment'] === 'true' && isset($_GET['comment_id'])) {
        $comment_id = $_GET['comment_id'];

        $deleteCommentQuery = "DELETE FROM comments WHERE comment_id = :comment_id";
        $deleteCommentStmt = $conn->prepare($deleteCommentQuery);
        $deleteCommentStmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $deleteCommentStmt->execute();

        // Redirect to the previous page with success message
        header("Location: success_page.php?success=true&comment=true");
        exit;
    }

    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && isset($_GET['clear']) && $_GET['clear'] === 'true'  && isset($_GET['artist_id'])) {
        $receiver_id = $_GET['artist_id'];
        $deleteNotifQuery = "DELETE FROM notifications WHERE receiver_id = :receiver_id";
        $deleteNotifStmt = $conn->prepare($deleteNotifQuery);
        $deleteNotifStmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $deleteNotifStmt->execute();
        // Redirect to the previous page with success message
        header("Location: success_page.php?success=true&notif=true");
        exit;
    }

}

// If the user didn't confirm the deletion or is not logged in, redirect to an appropriate page
header("Location: profile.php");
exit;
