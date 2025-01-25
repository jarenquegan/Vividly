<?php
include("config.php");
include("branding.php");

session_start();

if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $follower_id = $_SESSION['artist_id'];
    $artist_id = $_POST['artistId'];
    $isFollowing = $_POST['isFollowing'];

    // Fetch the artist name of the follower
    $sqlFollowerName = "SELECT username, firstname, middlename, lastname, suffix FROM artists WHERE artist_id = :follower_id";
    $stmtFollowerName = $conn->prepare($sqlFollowerName);
    $stmtFollowerName->bindParam(':follower_id', $follower_id);
    $stmtFollowerName->execute();
    $followerData = $stmtFollowerName->fetch(PDO::FETCH_ASSOC);

    // Format the follower name and store it in a variable
    if (!empty($followerData['firstname']) || !empty($followerData['middlename']) || !empty($followerData['lastname']) || !empty($followerData['suffix'])) {
        $middleInitial = !empty($followerData['middlename']) ? substr($followerData['middlename'], 0, 1) . "." : "";
        $followerName = $followerData['firstname'] . " " . $middleInitial . " " . $followerData['lastname'];
        if (!empty($followerData['suffix'])) {
            $followerName .= ", " . $followerData['suffix'];
        }
    } else {
        $followerName = $followerData['username'];
    }

    // Check if the follower is already following the artist
    $sqlCheckFollowing = "SELECT COUNT(*) AS following_count
                          FROM artist_followers
                          WHERE follower_id = :follower_id AND artist_id = :artist_id";

    $stmtCheckFollowing = $conn->prepare($sqlCheckFollowing);
    $stmtCheckFollowing->bindParam(':follower_id', $follower_id);
    $stmtCheckFollowing->bindParam(':artist_id', $artist_id);
    $stmtCheckFollowing->execute();
    $followingCount = $stmtCheckFollowing->fetchColumn();

    if ($followingCount > 0) {
        // Follower is already following the artist, so unfollow
        $sqlUnfollow = "DELETE FROM artist_followers
                        WHERE follower_id = :follower_id AND artist_id = :artist_id";

        $stmtUnfollow = $conn->prepare($sqlUnfollow);
        $stmtUnfollow->bindParam(':follower_id', $follower_id);
        $stmtUnfollow->bindParam(':artist_id', $artist_id);
        $stmtUnfollow->execute();
        $isFollowing = false;

        // Send unfollow notification
        $notificationMessage = "{$followerName} unfollowed you.";
    } else {
        // Follower is not following the artist, so follow
        $sqlFollow = "INSERT INTO artist_followers (follower_id, artist_id)
                      VALUES (:follower_id, :artist_id)";

        $stmtFollow = $conn->prepare($sqlFollow);
        $stmtFollow->bindParam(':follower_id', $follower_id);
        $stmtFollow->bindParam(':artist_id', $artist_id);
        $stmtFollow->execute();
        $isFollowing = true;

        // Send follow notification
        $notificationMessage = "{$followerName} started following you.";
    }

    // Fetch the updated total_followers
    $sqlTotalFollowers = "SELECT COUNT(*) AS total_followers
                          FROM artist_followers
                          WHERE artist_id = :artist_id";

    $stmtTotalFollowers = $conn->prepare($sqlTotalFollowers);
    $stmtTotalFollowers->bindParam(':artist_id', $artist_id);
    $stmtTotalFollowers->execute();
    $result = $stmtTotalFollowers->fetch(PDO::FETCH_ASSOC);

    // Return the updated total_followers and isFollowing status as JSON
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'total_followers' => $result['total_followers'], 'isFollowing' => $isFollowing]);

    // Insert notification into the notifications table
    $sqlInsertNotification = "INSERT INTO notifications (sender_id, receiver_id, notification_type, notification_data)
                              VALUES (:follower_id, :artist_id, 'follow', :notification_data)";

    $stmtInsertNotification = $conn->prepare($sqlInsertNotification);
    $stmtInsertNotification->bindParam(':follower_id', $follower_id);
    $stmtInsertNotification->bindParam(':artist_id', $artist_id);
    $stmtInsertNotification->bindParam(':notification_data', $notificationMessage);
    $stmtInsertNotification->execute();
    exit();
} else {
    // Invalid artist_id or follower_id
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid artist_id or follower_id']);
    exit();
}
