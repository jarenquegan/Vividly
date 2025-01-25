<?php
// Database connection
include("config.php");
include("branding.php");
include("fetch_notif.php");
include("social_accounts.php");

session_start();

// Check if the user is logged in and 'artist_id' exists in the session
if (isset($_SESSION['artist_id'])) {
    $artist_id = $_SESSION['artist_id'];

    // Retrieve user information from the database
    $sql = "SELECT * FROM artists WHERE artist_id = :artist_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_STR);
    $stmt->execute();
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch notifications
    $notifications = fetchNotifications($conn, $artist_id);
    $unreadNotificationsExist = $_SESSION['unread_notifications'];
} else {
    // Handle case when user is not logged in
    $artist = null; // or set default values
    $unreadNotificationsExist = false; // no unread notifications
}

// Initialize variables
$input = "";

// Fetch brand
$sql = "SELECT * FROM branding WHERE brand_id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$brand = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($brand as $brand1) {
    $brand_name = $brand1['brand_name'];
    $brand_logo = $brand1['brand_logo'];

    if (strlen($brand_name) >= 5) {
        $first_part = substr($brand_name, 0, 5);
        $last_part = substr($brand_name, 5);
        $brandname = $first_part . '<span>' . $last_part . '</span>';
    } else {
        $brandname = $brand_name;
    }
    // echo $brandname;
}


// Fetch reviews from the database
$sql = "SELECT * FROM reviews";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM about WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch qr_code
$sql = "SELECT * FROM qr_code WHERE qr_code_id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$qr_code = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>More</title>
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
    <link rel="stylesheet" href="css/newabout.css">
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
            <?php if (isset($_SESSION['username']) || isset($_SESSION['emailaddress']) && isset($_SESSION['password'])) : ?>
                <?php
                // Retrieve user information from the database
                $artist_id = $_SESSION['artist_id'];
                $sql = "SELECT * FROM artists WHERE artist_id = :artist_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_STR);
                $stmt->execute();
                $artist = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
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
                    <a href="post.php" class="nav-link">
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
            <?php else : ?>
                <span class="user" style="visibility: hidden; opacity:0"><img src="img/<?= $brand_logo; ?>" loading="lazy" alt="<?= $brand_name; ?>" class="user-img"></span>
            <?php endif; ?>
        </div>
    </header>
    <!-- creator -->
    <section class="creator container" id="creator">
        <div class="creator-content">
            <?php foreach ($brand as $brand) { ?>
                <div class="creator-img">
                    <img src="img/<?= $brand['brand_logo']; ?>" loading="lazy" alt="<?= $brand['brand_logo']; ?>">
                </div>
                <div class="creator-text">
                    <h3> EST. 2024 </h3>
                    <h2>
                        <?= $brandname; ?>
                    </h2>
                    <p>
                        <?= $brand['brand_info']; ?>
                    <p>
                        Stay <span class="color">connected</span>.
                    </p>

                    </p>

                    <!-- Social -->
                    <div class="social">
                        <a href="<?php echo $facebook; ?>"><i class='bx bxl-facebook'></i></a>
                        <a href="<?php echo $twitter; ?>"><i class='bx bxl-twitter'></i></a>
                        <a href="<?php echo $instagram; ?>"><i class='bx bxl-instagram'></i></a>
                        <a href="<?php echo $linkedin; ?>"><i class='bx bxl-linkedin'></i></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <!-- Terms of Use -->
    <section class="terms_of_use container" id="terms">
        <!-- Heading -->
        <div class="heading-2">
            <h2 class="heading-title-2">Terms of Use</h2>
        </div>
        <!-- Terms Content -->
        <div class="about-content">
            <div class="about-text">
                <?php
                foreach ($data as $row) {
                    $termsOfUse = $row['terms_of_use'];
                    $paragraphs = explode("\n\n", $termsOfUse); // Split the terms of use into paragraphs using double line breaks
                    foreach ($paragraphs as $paragraph) {
                        $lines = explode("\n", $paragraph); // Split each paragraph into separate lines
                        echo "<p>";
                        foreach ($lines as $line) {
                            echo "{$line}<br>"; // Print each line with a line break (<br>) to keep them separate
                        }
                        echo "</p><br>"; // Add a double line break between paragraphs
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Disclaimer -->
    <section class="disclaimer container" id="disclaimer">
        <!-- Heading -->
        <div class="heading-2">
            <h2 class="heading-title-2">Disclaimer</h2>
        </div>
        <!-- About Content -->
        <div class="about-content">
            <div class="about-text">
                <?php
                foreach ($data as $row) {
                    $disclaimer = $row['disclaimer'];
                    $paragraphs = explode("\n\n", $disclaimer); // Split the terms of use into paragraphs using double line breaks
                    foreach ($paragraphs as $paragraph) {
                        $lines = explode("\n", $paragraph); // Split each paragraph into separate lines
                        echo "<p>";
                        foreach ($lines as $line) {
                            echo "{$line}<br>"; // Print each line with a line break (<br>) to keep them separate
                        }
                        echo "</p><br>"; // Add a double line break between paragraphs
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Privacy Policy -->
    <section class="privacy_policy container" id="privacy">
        <!-- Heading -->
        <div class="heading-2">
            <h2 class="heading-title-2">Privacy Policy</h2>
        </div>
        <!-- About Content -->
        <div class="about-content">
            <div class="about-text">
                <?php
                foreach ($data as $row) {
                    $policy = $row['privacy_policy'];
                    $paragraphs = explode("\n\n", $policy); // Split the terms of use into paragraphs using double line breaks
                    foreach ($paragraphs as $paragraph) {
                        $lines = explode("\n", $paragraph); // Split each paragraph into separate lines
                        echo "<p>";
                        foreach ($lines as $line) {
                            echo "{$line}<br>"; // Print each line with a line break (<br>) to keep them separate
                        }
                        echo "</p><br>"; // Add a double line break between paragraphs
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <section class="footer container" style="margin-top: 2rem;" id="footer">
        <div class="social">
            <a href="<?php echo $facebook; ?>"><i class='bx bxl-facebook'></i></a>
            <a href="<?php echo $twitter; ?>"><i class='bx bxl-twitter'></i></a>
            <a href="<?php echo $instagram; ?>"><i class='bx bxl-instagram'></i></a>
            <a href="<?php echo $linkedin; ?>"><i class='bx bxl-linkedin'></i></a>
        </div>
        <!-- Footer Links -->
        <div class="footer-links">
            <a href="support.php#disclaimer">Disclaimer</a>
            <a href="support.php#terms">Terms Of Use</a>
            <a href="support.php#privacy">Privacy Policy</a>
        </div>
        <!-- Copyright -->
        <p>
            <a href="more.php" style="text-decoration: none; color: inherit;"><?= $brand_name; ?> &#169; 2023</a>
        </p>
    </section>
    <!-- Link Swiper JS -->
    <script src="js/swiper-bundle.min.js"></script>
    <!-- Link To JS -->
    <script src="js/main-about.js"></script>
</body>

</html>