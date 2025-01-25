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

// Add a new query to count comments
$sqlCountComments = "SELECT COUNT(*) as comment_count FROM comments WHERE artwork_id = :artwork_id";
$stmtCountComments = $conn->prepare($sqlCountComments);
$stmtCountComments->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
$stmtCountComments->execute();
$commentCount = $stmtCountComments->fetchColumn();

header('Content-Type: application/json');
echo json_encode(['commentCount' => $commentCount]);
?>
