<?php
// Database connection
include("config.php");
include("branding.php");
include("fetch_notif.php");
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

$notifications = fetchNotifications($conn, $artist_id);
$unreadNotificationsExist = $_SESSION['unread_notifications'];

// Initialize variables
$input = "";

if (isset($_POST['insertEntry'])) {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $style = $_POST['style'];
    $artistId = $_POST['artistId'];

    // File upload
    $artwork = $_FILES['artwork']['name'];

    $artwork_filename = $_FILES['artwork']['name'];
    $artwork_tmp_path = $_FILES['artwork']['tmp_name'];
    $artwork_destination = "artworks/" . $artwork_filename;
    move_uploaded_file($artwork_tmp_path, $artwork_destination);

    // Insert into 'artworks' table
    $stmt = $conn->prepare("INSERT INTO artworks (title, description, style, image_url) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $style, $artwork]);

    // Get the last inserted artwork ID
    $artworkId = $conn->lastInsertId();

    // Insert into 'artists_artworks' table
    $stmt = $conn->prepare("INSERT INTO artists_artworks (artist_id, artwork_id) VALUES (?, ?)");
    $stmt->execute([$artistId, $artworkId]);

    // Redirect or display a success message
    header("Location: success_page.php?success=true&post=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
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
    <link rel="stylesheet" href="css/editprof.css">
    <!-- Link Swiper CSS -->
    <link rel="stylesheet" href="css/swiper-bundle.min.css">
    <!-- Fav Icon -->
    <link rel="shortcut icon" href="img/<?= $brand_logo; ?>" type="image/x-icon">
    <!-- Box Icons -->
    <link href='assets/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="assets/jquery-3.6.4.js"></script>
    <script src="js/loader.js"></script>
</head>

<body>
    <!-- Loader -->
    <div class="loader" style="z-index: 99999999999999;"></div>
    <!-- Header -->
    <header>

        <!-- Nav -->
        <div class="nav container">
            <!-- Logo -->
            <a href="index.php" class="logo">
                <?= $brandname; ?>
            </a>
            <!-- Search Form -->
            <form action="search_results.php" method="GET" class="search-box">
                <input type="search" name="search" id="search-input" placeholder="Search <?= $brand_name; ?>" value="<?php echo htmlspecialchars($input); ?>">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
            <!-- User -->
            <a href="profile.php" class="user">
                <img src="images/<?php echo $artist['artist_pic']; ?>" loading="lazy" alt="<?= $artist['username']; ?>" class="user-img">
            </a>
            <!-- NavBar -->
            <div class="navbar">
                <a href="index.php" class="nav-link">
                    <i class='bx bx-home'></i>
                    <span class="nav-link-title">Home</span>
                </a>
                <a href="discover.php" class="nav-link">
                    <i class='bx bx-compass'></i>
                    <span class="nav-link-title">Discover</span>
                </a>
                <a href="post.php" class="nav-link nav-active">
                    <i class='bx bx-plus-circle'></i>
                    <span class="nav-link-title">Post</span>
                </a>
                <a href="artists.php" class="nav-link">
                    <i class='bx bx-user'></i>
                    <span class="nav-link-title">Artists</span>
                </a>
                <a href="notif.php" id="notificationsLink" class="nav-link <?php echo ($unreadNotificationsExist) ? 'unread-notifications' : ''; ?>">
                    <i class='bx bx-bell'></i>
                    <span class="nav-link-title">Notifs</span>
                </a>
                <a href="more.php" class="nav-link">
                    <i class='bx bx-menu'></i>
                    <span class="nav-link-title">More</span>
                </a>
            </div>
        </div>
    </header>
    <section class="creator container" id="creator">
        <div class="creator-content">
            <div class="creator-img">
                <img id="previewImage1" src="images/<?php echo $artist['artist_pic']; ?>" loading="lazy" alt="<?= $artist['username']; ?>">
            </div>
            <div class="profile-text">
                <h2 id="artistName">@<?= $artist['username']; ?></h2>
            </div>

            <div>
                <br>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="profile-form feedback-form" id="feedback-form">
                <input type="hidden" id="defaultImage" name="defaultImage" value="">

                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Title" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Description" rows="10" style="resize: none;"></textarea>

                <label for="style">Style</label>
                <input type="text" id="style" name="style" placeholder="Style">
                <label for="style" class="note">Note: Use comma(,) as separator.</label>

                <label for="artwork">Artwork</label>
                <input type="file" id="artwork" name="artwork" class="avatar" required>
                <label for="artwork" class="note">Note: For a better viewing experience, use a 4:3 aspect ratio photo.</label>

                <div class="artwork-image">
                    <img id="previewImage" src="artworks/default_photo.png" loading="lazy" alt="Preview Image">
                </div>

                <input type="button" id="removePicBtn" name="removeAvatar" onclick="removePhoto()" class="send-btn" value="Remove Photo">
                <label for="removeAvatar" class="note"> Note: Click this button to remove the photo.</label>

                <input type="hidden" name="artistId" value="<?php echo $artist['artist_id']; ?>">
                <input type="submit" id="submitButton" value="UPLOAD" name="insertEntry" class="send-btn">
            </form>
            <div>
                <?php if (isset($error)) : ?>
                    <br>
                    <p style="color: #1D9BF0; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>
            </div>
            <div class="logout" style="margin-bottom: 5rem !important;">
            </div>
        </div>
    </section>
    <script src="js/post_image.js"></script>
</body>

</html>