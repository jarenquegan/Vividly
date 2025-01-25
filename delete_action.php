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

if (isset($_GET['artist_id']) || isset($_GET['artwork_id']) || isset($_GET['review_id'])) {
    if (isset($_GET['artist_id']) && $_GET['delete'] === 'true') {
        $artist_idToDelete = $_GET['artist_id'];
        // Get artist ID from the request

        // Retrieve artwork IDs associated with the artist
        $getArtworkIdsQuery = "SELECT artwork_id FROM artists_artworks WHERE artist_id = :artist_id";
        $getArtworkIdsStmt = $conn->prepare($getArtworkIdsQuery);
        $getArtworkIdsStmt->bindParam(':artist_id', $artist_idToDelete, PDO::PARAM_INT);
        $getArtworkIdsStmt->execute();
        $artworkIds = $getArtworkIdsStmt->fetchAll(PDO::FETCH_COLUMN);

        // Delete associated artworks
        if (!empty($artworkIds)) {
            $deleteArtworksQuery = "DELETE FROM artworks WHERE artwork_id IN (" . implode(',', $artworkIds) . ")";
            $deleteArtworksStmt = $conn->prepare($deleteArtworksQuery);
            $deleteArtworksStmt->execute();
        }

        // Delete the artist
        $deleteArtistQuery = "DELETE FROM artists WHERE artist_id = :artist_id";
        $deleteArtistStmt = $conn->prepare($deleteArtistQuery);
        $deleteArtistStmt->bindParam(':artist_id', $artist_idToDelete, PDO::PARAM_INT);
        $deleteArtistStmt->execute();
        header("Location: success_page.php?success=true&deleteArtist=true");
        exit;
    } elseif (isset($_GET['artist_id']) && $_GET['deleteInside'] === 'true') {
        $artist_idToDelete = $_GET['artist_id'];
        $deleteUserQuery = "DELETE FROM artists WHERE artist_id = :artist_id";
        $deleteUserStmt = $conn->prepare($deleteUserQuery);
        $deleteUserStmt->bindParam(':artist_id', $artist_idToDelete, PDO::PARAM_INT);
        $deleteUserStmt->execute();

        header("Location: success_page.php?success=true&deleteInside=true");
        exit;
    } elseif (isset($_GET['artwork_id']) && $_GET['delete'] === 'true') {
        $artworkToDelete = $_GET['artwork_id'];
        $deleteUserQuery = "DELETE FROM artworks WHERE artwork_id = :artwork_id";
        $deleteUserStmt = $conn->prepare($deleteUserQuery);
        $deleteUserStmt->bindParam(':artwork_id', $artworkToDelete, PDO::PARAM_INT);
        $deleteUserStmt->execute();

        header("Location: success_page.php?success=true&deleteArtwork=true");
        exit;
    } elseif (isset($_GET['review_id']) && $_GET['delete'] === 'true') {
        $review_idToDelete = $_GET['review_id'];
        $deleteUserQuery = "DELETE FROM reviews WHERE review_id = :review_id";
        $deleteUserStmt = $conn->prepare($deleteUserQuery);
        $deleteUserStmt->bindParam(':review_id', $review_idToDelete, PDO::PARAM_INT);
        $deleteUserStmt->execute();

        header("Location: success_page.php?success=true&deleteArtwork=true");
        exit;
    }
}