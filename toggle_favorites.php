<?php
include("config.php");
include("branding.php");

session_start();

if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $artist_id = $_SESSION['artist_id'];
    $artwork_id = $_POST['artworkId'];
    $isFavorite = $_POST['isFavorite'];

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

    // Check if the artist has already liked the artwork
    $sqlCheckLiked = "SELECT COUNT(*) AS liked_count
                      FROM artist_liked_artworks
                      WHERE artist_id = :artist_id AND artwork_id = :artwork_id";

    $stmtCheckLiked = $conn->prepare($sqlCheckLiked);
    $stmtCheckLiked->bindParam(':artist_id', $artist_id);
    $stmtCheckLiked->bindParam(':artwork_id', $artwork_id);
    $stmtCheckLiked->execute();
    $likedCount = $stmtCheckLiked->fetchColumn();

    if ($likedCount > 0) {
        // Artist has already liked the artwork, so dislike it
        $sqlDislike = "DELETE FROM artist_liked_artworks
                       WHERE artist_id = :artist_id AND artwork_id = :artwork_id";

        $stmtDislike = $conn->prepare($sqlDislike);
        $stmtDislike->bindParam(':artist_id', $artist_id);
        $stmtDislike->bindParam(':artwork_id', $artwork_id);
        $stmtDislike->execute();
        $isFavorite = false;

        // Send unlike notification
        // $notificationMessage = "{$followerName} unliked your artwork '{$artworkTitle}'.";
    } else {
        // Artist has not liked the artwork, so like it
        $sqlLike = "INSERT INTO artist_liked_artworks (artist_id, artwork_id)
                    VALUES (:artist_id, :artwork_id)";

        $stmtLike = $conn->prepare($sqlLike);
        $stmtLike->bindParam(':artist_id', $artist_id);
        $stmtLike->bindParam(':artwork_id', $artwork_id);
        $stmtLike->execute();
        $isFavorite = true;

        // Send like notification
        $notificationMessage = "{$followerName} liked your artwork '{$artworkTitle}'.";

        foreach ($associatedArtists as $artists) {
            $receiver_id = $artists['artist_id'];
        }
    
        $follower_id =  $_SESSION['artist_id'];
    
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
    }

    // Fetch the updated total_likes
    $sqlTotalLikes = "SELECT COUNT(*) AS total_likes
                      FROM artist_liked_artworks
                      WHERE artwork_id = :artwork_id";

    $stmtTotalLikes = $conn->prepare($sqlTotalLikes);
    $stmtTotalLikes->bindParam(':artwork_id', $artwork_id);
    $stmtTotalLikes->execute();
    $result = $stmtTotalLikes->fetch(PDO::FETCH_ASSOC);

    // Return the updated total_likes and isFavorite status as JSON
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'total_likes' => $result['total_likes'], 'isFavorite' => $isFavorite]);
    exit();
} else {
    // Invalid artwork_id or artist_id
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid artwork_id or artist_id']);
    exit();
}
