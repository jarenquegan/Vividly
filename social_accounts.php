<?php
include("config.php");
include("branding.php");

// Prepare the query with a WHERE clause
$query = "SELECT facebook, instagram, twitter, linkedin FROM social_accounts WHERE social_id = :social_id";
$stmt = $conn->prepare($query);
$social_id = 1;
$stmt->bindParam(':social_id', $social_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Retrieve the values from the fetched row
$facebook = $row['facebook'];
$instagram = $row['instagram'];
$twitter = $row['twitter'];
$linkedin = $row['linkedin'];
?>