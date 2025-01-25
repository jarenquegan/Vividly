<?php
include("config.php");
include("branding.php");

// Start or resume the session
session_start();

// PHP code to fetch the top 10 most liked artworks
$sql = "SELECT
            artworks.*,
            artists.*,
            COUNT(DISTINCT artist_liked_artworks.id) AS total_likes
        FROM artworks
        LEFT JOIN artists_artworks ON artworks.artwork_id = artists_artworks.artwork_id
        LEFT JOIN artists ON artists.artist_id = artists_artworks.artist_id
        LEFT JOIN artist_liked_artworks ON artworks.artwork_id = artist_liked_artworks.artwork_id
        GROUP BY artworks.artwork_id, artists.artist_id
        ORDER BY total_likes DESC
        LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($results);
?>
