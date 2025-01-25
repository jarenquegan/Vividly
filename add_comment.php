<?php
include("config.php");
include("branding.php");

session_start();

// Check if the user is not logged in, redirect to the login page
if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $artist_id = $_SESSION['artist_id'];
    $artwork_id = $_POST['artworkId'];
    $review = $_POST['review'];

    // Assuming you have a comments table with columns: id, artist_id, artwork_id, comment_text, created_at
    $sqlAddComment = "INSERT INTO comments (artist_id, artwork_id, comment_text, created_at) VALUES (:artist_id, :artwork_id, :comment_text, NOW())";

    try {
        $stmtAddComment = $conn->prepare($sqlAddComment);
        $stmtAddComment->bindParam(':artist_id', $artist_id);
        $stmtAddComment->bindParam(':artwork_id', $artwork_id); // Assuming you have a form field with the name 'artwork_id'
        $stmtAddComment->bindParam(':comment_text', $review); // Assuming your textarea has the name 'review'
        $stmtAddComment->execute();

        $newCommentId = $conn->lastInsertId(); // Get the ID of the newly inserted comment

        // Fetch the details of the new comment
        $sqlFetchComment = "SELECT c.*, a.username, a.emailaddress, a.firstname, a.middlename, a.lastname, a.suffix, a.artist_pic, c.created_at
                            FROM comments c
                            JOIN artists a ON c.artist_id = a.artist_id
                            WHERE c.comment_id = :comment_id
                            ORDER BY c.created_at DESC";
        $stmtFetchComment = $conn->prepare($sqlFetchComment);
        $stmtFetchComment->bindParam(':comment_id', $newCommentId);
        $stmtFetchComment->execute();

        $newComment = $stmtFetchComment->fetch(PDO::FETCH_ASSOC);

        // Return the new comment details as JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'comment' => $newComment]);

        // Fetch the artwork title
        $sqlArtworkTitle = "SELECT title FROM artworks WHERE artwork_id = :artwork_id";
        $stmtArtworkTitle = $conn->prepare($sqlArtworkTitle);
        $stmtArtworkTitle->bindParam(':artwork_id', $artwork_id);
        $stmtArtworkTitle->execute();
        $artworkTitle = $stmtArtworkTitle->fetchColumn();

        // Retrieve the associated artists for the artwork
        $sql = "SELECT artists.*
            FROM artists
            INNER JOIN artists_artworks ON artists.artist_id = artists_artworks.artist_id
            WHERE artists_artworks.artwork_id = :artwork_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':artwork_id', $artwork_id);
        $stmt->execute();
        $associatedArtists = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch the artist name of the follower
        $sqlFollowerName = "SELECT * FROM artists WHERE artist_id = :follower_id";
        $stmtFollowerName = $conn->prepare($sqlFollowerName);
        $stmtFollowerName->bindParam(':follower_id', $artist_id);
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

        foreach ($associatedArtists as $artists) {
            $receiver_id = $artists['artist_id'];
        }
        
        $follower_id =  $_SESSION['artist_id'];
        $notificationMessage = "{$followerName} commented on your artwork '{$artworkTitle}'.";

        if ($follower_id !== $receiver_id) {
            $sqlInsertNotification = "INSERT INTO notifications (sender_id, receiver_id, artwork_id, notification_type, notification_data)
                              VALUES (:follower_id, :artist_id, :artwork_id, 'Like', :notification_data)";

            $stmtInsertNotification = $conn->prepare($sqlInsertNotification);
            $stmtInsertNotification->bindParam(':follower_id', $follower_id);
            $stmtInsertNotification->bindParam(':artist_id', $receiver_id);
            $stmtInsertNotification->bindParam(':artwork_id', $artwork_id);
            $stmtInsertNotification->bindParam(':notification_data', $notificationMessage);
            $stmtInsertNotification->execute();
        }
    } catch (PDOException $e) {
        // Handle database errors
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        // You might want to log the error for further investigation
        error_log("Database error in add_review.php: " . $e->getMessage(), 0);
    }
}
