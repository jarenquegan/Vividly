<?php
// Database connection
include("config.php");
include("branding.php");

include("social_accounts.php");

session_name("AdminSession");
session_start();

// Check if the admin is logged in if not redirect to the login page
if ((!isset($_SESSION['username']) || !isset($_SESSION['emailaddress'])) && !isset($_SESSION['password'])) {
    header("Location: login-dashboard.php");
    exit;
}

// Initialize variables
$reviews = array();
$input = "";

// Fetch all reviews
$sql = "SELECT * FROM reviews ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve user information from the database
$artist_id = $_SESSION['artist_id'];
$sql = "SELECT * FROM artists WHERE artist_id = :artist_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_STR);
$stmt->execute();
$artist = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | Dashboard</title>
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
            <a href="artwork_dashboard.php" class="logo">
                <?= $brandname; ?>
            </a>
            <!-- Search Form -->
            <form action="search_dashboard.php" method="GET" class="search-box">
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
                <a href="reviews_dashboard.php" class="nav-link nav-active">
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
    <section class="result-container">
        <div class="container">
            <?php if (count($reviews) > 0) { ?>
                <div style="margin-bottom: 10px; display: flex; align-items: center;">
                    <h3 class="spanspan">
                        <?php echo strtoupper("SENT REVIEWS"); ?>
                    </h3>
                    <span><?= "TOTAL REVIEWS: " . count($reviews); ?></span>
                </div>
                <ul class="artwork-list">
                    <?php foreach ($reviews as $reviews) { ?>
                        <li>
                            <span href="edit-user.php?id=<?php echo $reviews['artist_id']; ?>&users=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="images/<?php echo $reviews['artist_pic']; ?>" loading="lazy" alt="<?php echo $reviews['username']; ?>">
                                    </div>
                                    <div class="artist-details">
                                        <span style="display: flex; align-items: center;">
                                            <span class="spanspan">
                                                <h3>
                                                    <?php
                                                    if (!empty($reviews['firstname']) || !empty($reviews['middlename']) || !empty($reviews['lastname']) || !empty($reviews['suffix'])) {
                                                        $middleInitial = !empty($reviews['middlename']) ? substr($reviews['middlename'], 0, 1) . "." : "";
                                                        echo $reviews['firstname'] . " " . $middleInitial . " <span class='color'>" . $reviews['lastname'] . "</span>";
                                                        if (!empty($reviews['suffix'])) {
                                                            echo ", " . $reviews['suffix'];
                                                        }
                                                    } else {
                                                        echo $reviews['username'];
                                                    }
                                                    ?>
                                                </h3>
                                            </span>
                                            <span>
                                                <p><?php echo date('F j, Y', strtotime($reviews['created_at'])); ?></p>
                                            </span>
                                        </span>
                                        <div class="artwork-details plot-container">
                                            <p>
                                                <?php
                                                $reviewContent = $reviews['review_content'];
                                                $words = explode(' ', $reviewContent);

                                                if (count($words) > 30) {
                                                    echo implode(' ', array_slice($words, 0, 30)) . ' ';
                                                    echo '<i class="read" title="' . htmlspecialchars($reviewContent) . '">...hover to read...</i>';
                                                } else {
                                                    echo $reviewContent;
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <p style="text-align: right;">
                                            <a href="delete_action.php?review_id=<?php echo $reviews['review_id']; ?>&delete=true" class="dashspan">
                                                DELETE <i class='bx bx-trash'></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </span>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($reviews) == 0) { ?>
                <div style="margin-bottom: 10px; display: flex; align-items: center;">
                    <h3 class="spanspan">
                        <?php echo strtoupper("No Reviews Yet"); ?>
                    </h3>
                    <span><?= "TOTAL REVIEWS: " . count($reviews); ?></span>
                </div>
            <?php } ?>
        </div>
    </section>
    <!-- Link Swiper JS -->
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/swipe.js"></script>
    <!-- Link To JS -->
    
</body>

</html>