<?php
include("config.php");
include("branding.php");

include("social_accounts.php");

session_start();

if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit;
}

$artist_id = $_SESSION['artist_id'];

if (isset($_GET['id'])) {
    $artwork_id = $_GET['id'];

    $isFavorite = $_GET['isFavorite'];

    if ($isFavorite === 'true') {
        $sql = "DELETE FROM artist_liked_artworks WHERE artist_id = :artist_id AND artwork_id = :artwork_id";
    } else {
        $sql = "INSERT INTO artist_liked_artworks (artist_id, artwork_id) VALUES (:artist_id, :artwork_id)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
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
} else {
// Artist has not liked the artwork, so like it
$sqlLike = "INSERT INTO artist_liked_artworks (artist_id, artwork_id)
VALUES (:artist_id, :artwork_id)";

$stmtLike = $conn->prepare($sqlLike);
$stmtLike->bindParam(':artist_id', $artist_id);
$stmtLike->bindParam(':artwork_id', $artwork_id);
$stmtLike->execute();
}
?>