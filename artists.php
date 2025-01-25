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

$sql = "SELECT * FROM artists ORDER BY firstname ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$actrand = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize variables
$input = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $brand_name; ?> | Artists</title>
    <!-- Link To CSS -->
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/search_results.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/artist_search_results.css">
    <link rel="stylesheet" href="css/span.css">
    <link rel="stylesheet" href="css/result-container.css">
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
                <a href="post.php" class="nav-link">
                    <i class='bx bx-plus-circle'></i>
                    <span class="nav-link-title">Post</span>
                </a>
                <a href="artists.php" class="nav-link nav-active">
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
    <section class="result-container">
        <div class="container">
            <?php if (count($actrand) > 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span class="spanspan">
                        <?php echo strtoupper("<?= $brand_name; ?> Artists"); ?>
                    </span>
                </h3>
                <ul class="artwork-list">
                    <?php foreach ($actrand as $artist) {
                        $userPhoto = $artist["artist_pic"]; ?>
                        <li>
                            <a href="artist.php?artist=<?php echo $artist['artist_id']; ?>" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="images/<?php echo $artist['artist_pic']; ?>" loading="lazy" alt="<?php echo $artist['username']; ?>">
                                    </div>
                                    <div class="artist-details">
                                        <span style="display: flex; align-items: center;">
                                            <span class="spanspan">
                                                <h3>
                                                    <?php
                                                    if (!empty($artist['firstname']) || !empty($artist['middlename']) || !empty($artist['lastname']) || !empty($artist['suffix'])) {
                                                        $middleInitial = !empty($artist['middlename']) ? substr($artist['middlename'], 0, 1) . "." : "";
                                                        echo $artist['firstname'] . " " . $middleInitial . " " . $artist['lastname'];
                                                        if (!empty($artist['suffix'])) {
                                                            echo ", " . $artist['suffix'];
                                                        }
                                                    } else {
                                                        echo $artist['username'];
                                                    }
                                                    ?>
                                                </h3>
                                            </span>
                                            <span>
                                                <p><?php echo $artist['emailaddress']; ?></p>
                                            </span>
                                        </span>
                                        <p style="display: flex; align-items: center;">
                                            <span class="spanspan">@<?php echo $artist['username']; ?></span>
                                            <span>
                                                <?php echo $artist['phone_number']; ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($actrand) == 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span class="spanspan">
                        <?php echo strtoupper("No Artists Yet"); ?>
                    </span>
                </h3>
            <?php } ?>
        </div>
    </section>
    <!-- Link Swiper JS -->
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/swipe.js"></script>
    <!-- Link To JS -->
    
</body>

</html>