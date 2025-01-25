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

// Retrieve user information from the database
$artist_id = $_SESSION['artist_id'];
$sql = "SELECT * FROM artists WHERE artist_id = :artist_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_STR);
$stmt->execute();
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize variables
$input = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <!-- Link To CSS -->
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/search_results.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/artist_search_results.css">
    <link rel="stylesheet" href="css/span.css">
    <link rel="stylesheet" href="css/result-container.css">
    <link rel="stylesheet" href="css/profile.css">
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
            <a href="artwork_dashboard.php" class="logo">
                <?= $brandname; ?>
            </a>
            <!-- Search Form -->
            <form action="search_results.php" method="GET" class="search-box">
                <input type="search" name="search" id="search-input" placeholder="Search <?= $brand_name; ?>" value="<?php echo htmlspecialchars($input); ?>">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
            <!-- User -->
            <a href="admin_profile.php" class="user">
                <img src="images/<?php echo $artist['artist_pic']; ?>" loading="lazy" alt="<?= $artist['username']; ?>" class="user-img">
            </a>
            <!-- NavBar -->
            <div class="navbar">
                <a href="artwork_dashboard.php" class="nav-link">
                    <i class='bx bx-image-alt'></i>
                    <span class="nav-link-title">Posts</span>
                </a>
                <a href="top_picks_dashboard.php" class="nav-link">
                    <i class='bx bxs-hot'></i>
                    <span class="nav-link-title">Top Liked</span>
                </a>
                <a href="artists_dashboard.php" class="nav-link">
                    <i class='bx bx-user'></i>
                    <span class="nav-link-title">Artists</span>
                </a>
                <a href="ban_account.php" class="nav-link">
                    <i class='bx bx-block'></i>
                    <span class="nav-link-title">Banned</span>
                </a>
                <a href="reviews_dashboard.php" class="nav-link">
                    <i class='bx bxs-paper-plane'></i>
                    <span class="nav-link-title">Reviews</span>
                </a>
                <a href="more_dashboard.php" class="nav-link">
                    <i class='bx bx-menu'></i>
                    <span class="nav-link-title">More</span>
                </a>
            </div>
        </div>
    </header>
    <!-- profile -->
    <section class="profile container" id="profile">
        <div class="profile-content">
            <div class="profile-img">
                <img src="images/<?php echo $artist['artist_pic']; ?>" loading="lazy" alt="<?= $artist['username']; ?>">
            </div>
            <div class="profile-text">
                <?php
                if (!empty($artist['firstname']) || !empty($artist['middlename']) || !empty($artist['lastname']) || !empty($artist['suffix'])) {
                    $middleInitial = !empty($artist['middlename']) ? substr($artist['middlename'], 0, 1) . "." : "";
                    echo "<h2>" . $artist['firstname'] . " " . $middleInitial . " <span class='color'>" . $artist['lastname'] . "</span>";
                    if (!empty($artist['suffix'])) {
                        echo ", " . $artist['suffix'];
                    }
                    echo "</h2>";
                } else {
                    echo "<h2>" . $artist['username'] . "</h2>";
                }

                if (!empty($artist['bio'])) {
                    echo "<p>" . $artist['bio'] . "</p>";
                } else {
                    echo "<p><i>Edit your profile to add information about yourself.</i></p>";
                }
                ?>
                <p>
                    <a href="edit_artist.php?artist_id=<?= $artist_id; ?>">
                        <span class="color">Edit your profile </span><i class='bx bx-edit-alt' style="color: var(--text-color);"></i>
                    </a>
                    <?php if ($artist['acct_type'] === 'Admin') { ?>
                        <a href="login.php?username=<?= $artist['username']; ?>&password=<?= $artist['password']; ?>">
                            <br><span class="color">See <?= $brand_name; ?> | Home </span><i class='bx bx-command' style="color: var(--text-color);"></i>
                        </a>
                    <?php } ?>
                </p>
                <div class="logout">
                    <a href="logout.php?admin=true"><i class='bx bxs-log-out'></i>
                        <span class="logout-text">LOGOUT</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</body>

</html>