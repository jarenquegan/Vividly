<?php
// Database connection
include("config.php");
include("branding.php");

include("social_accounts.php");

session_start();
// Check if the user is not logged in, redirect to the login page
if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user information from the database
$artist_id = $_SESSION['artist_id'];
$sql = "SELECT * FROM artists WHERE artist_id = :artist_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_STR);
$stmt->execute();
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review'])) {
    $review = $_POST['review'];
    $artist_id = $artist['artist_id'];
    $username = $artist['username'];
    $firstname = $artist['firstname'];
    $middlename = $artist['middlename'];
    $lastname = $artist['lastname'];
    $suffix = $artist['suffix'];
    $email = $artist['emailaddress'];
    $artist_pic = $artist['artist_pic'];

    $sql = "INSERT INTO reviews (artist_id, username, firstname, middlename, lastname, suffix, emailaddress, review_content, artist_pic) 
        VALUES (:artist_id, :username, :firstname, :middlename, :lastname, :suffix, :emailaddress, :review_content, :artist_pic)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':middlename', $middlename, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':suffix', $suffix, PDO::PARAM_STR);
    $stmt->bindParam(':emailaddress', $email, PDO::PARAM_STR);
    $stmt->bindParam(':review_content', $review, PDO::PARAM_STR);
    $stmt->bindParam(':artist_pic', $artist_pic, PDO::PARAM_STR);
    $stmt->execute();


    $feedback_result = 'Your feedback has been added successfully. Thank you for taking the time to provide your review!';

    // Redirect to login page after 2 seconds
    echo "<script>setTimeout(function() {
                window.location.href = 'more.php';
            }, 2000);</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Entry</title>
    <!-- Link To CSS -->
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/search_results.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/artist_search_results.css">
    <link rel="stylesheet" href="css/span.css">
    <link rel="stylesheet" href="css/result-container.css">
    <link rel="stylesheet" href="css/filetype.css">
    <link rel="stylesheet" href="css/post.css">
    <!-- Link Swiper CSS -->
    <link rel="stylesheet" href="css/swiper-bundle.min.css">
    <!-- Fav Icon -->
    <link rel="shortcut icon" href="img/<?= $brand_logo; ?>" type="image/x-icon">
    <!-- Box Icons -->
    <link href='assets/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .profile-img {
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .profile-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
    <script>
        // Loader
        window.addEventListener("load", () => {
            const loader = document.querySelector(".loader");

            loader.classList.add("loader--hidden");

            loader.addEventListener("transitionend", () => {
                document.body.removeChild(loader);
            });
        });
    </script>
    <script>
        // Redirect to previous page after 5 seconds
        setTimeout(function() {
            history.back();
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>

</head>

<body>
    <!-- Loader -->
    <div class="loader" style="z-index: 99999999999999;"></div>
    <!-- profile -->
    <section class="profile container" id="profile">
        <div class="profile-content">
            <div class="profile-img">
                <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="logo">
            </div>
            <div class="profile-text">
                <h2><?= $brand_name; ?></h2>
                <p>
                    <?php echo $feedback_result; ?>
                </p>
                <p>
                    Redirecting to the previous page. Please wait...
                </p>
            </div>
        </div>
    </section>
</body>

</html>