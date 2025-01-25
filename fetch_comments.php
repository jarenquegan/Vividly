<?php
include("config.php");
include("branding.php");

session_start();

// Check if the user is not logged in, redirect to the login page
if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit;
}

$artwork_id = isset($_GET['artwork_id']) ? $_GET['artwork_id'] : null;

if (!$artwork_id) {
    // Handle the case where artwork_id is not provided
    echo json_encode(['error' => 'artwork_id is required']);
    exit;
}

$sqlFetchComments = "
    SELECT comments.comment_id, comments.comment_text, comments.created_at, artists.artist_id, artists.username, artists.emailaddress, artists.firstname, artists.middlename, artists.lastname, artists.suffix, artists.artist_pic
    FROM comments
    INNER JOIN artists ON comments.artist_id = artists.artist_id
    WHERE comments.artwork_id = :artwork_id
    ORDER BY comments.created_at DESC";

$stmtFetchComments = $conn->prepare($sqlFetchComments);
$stmtFetchComments->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
$stmtFetchComments->execute();
$comments = $stmtFetchComments->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($comments);
?>
