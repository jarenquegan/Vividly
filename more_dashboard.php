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
$searchActor = array();
$searchResults = array();
$input = "";

// Fetch all artworks
$sql = "SELECT * FROM artworks ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all artists
$sql = "SELECT * FROM artists ORDER BY firstname ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$searchActor = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

// Fetch owner
$sql = "SELECT * FROM branding WHERE brand_id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$brand = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all support
$sql = "SELECT * FROM about WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$about = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all social links
$sql = "SELECT * FROM social_accounts WHERE social_id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$social_links = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>More | Dashboard</title>
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
                <a href="reviews_dashboard.php" class="nav-link">
                    <i class='bx bxs-paper-plane'></i>
                    <span class="nav-link-title">Reviews</span>
                </a>
                <a href="more_dashboard.php" class="nav-link nav-active">
                    <i class='bx bx-menu'></i>
                    <span class="nav-link-title">More</span>
                </a>
            </div>
        </div>
    </header>
    <!-- Home -->
    <section class="result-container">
        <div class="container">
            <div style="margin-bottom: 10px; display: flex; align-items: center;">
                <h3 class="spanspan">
                    <?php echo strtoupper("$brand_name SYSTEM REPORT"); ?>
                </h3>
                <span>
                    <?php
                    // Get the current date
                    $currentDate = date("F j, Y");
                    ?>
                    <?= $currentDate; ?>
                </span>
            </div>
            <ul class="artwork-list">
                <li>
                    <span class="artist-link">
                        <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                            <div class="artist_pic">
                                <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="<?= $brand_name; ?>_logo">
                            </div>
                            <div class="artist-details">
                                <span style="display: flex; align-items: center;">
                                    <span class="spanspan">
                                        <h3>
                                            Data Highlights
                                        </h3>
                                    </span>
                                </span>
                                <p>
                                    Total No. of Uploaded Entries: <?= count($searchResults); ?>
                                </p>
                                <p>
                                    Total No. of Registered Users: <?= count($searchActor); ?>
                                </p>
                                <p>
                                    Total No. of Reviews Received: <?= count($reviews); ?>
                                </p>
                            </div>
                        </div>
                    </span>
                </li>
            </ul>
            <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php if (count($brand) > 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span class="spanspan">
                        <?php echo strtoupper("$brand_name CREATORS"); ?>
                    </span>
                </h3>
                <ul class="artwork-list">
                    <?php foreach ($brand as $brand) {
                        $brandPhoto = $brand["brand_logo"]; ?>
                        <li>
                            <a href="edit-support.php?id=<?php echo $brand['brand_id']; ?>&owners=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="img/<?php echo $brand['brand_logo']; ?>" loading="lazy" alt="<?php echo $brand['brand_name']; ?>">
                                    </div>
                                    <div class="artist-details">

                                        <span style="display: flex; align-items: center;">
                                            <span class="spanspan">
                                                <h3>
                                                    <?= $brand['brand_name']; ?>
                                                </h3>
                                            </span>
                                        </span>
                                        <div class="artwork-details plot-container">
                                            <p>
                                                Message: <?php echo preg_replace('/((\S+\s+){50}).*/', '$1', $brand['brand_info']); ?>
                                                <span class="read" title="<?php echo htmlspecialchars($brand['brand_info']); ?>">...</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($about) > 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span class="spanspan">
                        <?php echo strtoupper("$brand_name SUPPORT"); ?>
                    </span>
                </h3>
                <ul class="artwork-list">
                    <?php foreach ($about as $about) { ?>
                        <li>
                            <a href="edit-support.php?id=<?php echo $about['id']; ?>&about=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="about.png">
                                    </div>
                                    <div class="artist-details">
                                        <h3>About</h3>
                                        <div class="artwork-details plot-container">
                                            <p>
                                                <?php
                                                $words = preg_split('/\s+/', $about['about']);
                                                $first50 = implode(' ', array_slice($words, 0, 50));
                                                echo htmlspecialchars($first50);
                                                ?>
                                                <span class="read" title="<?php echo htmlspecialchars($about['about']); ?>">...</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="edit-support.php?id=<?php echo $about['id']; ?>&about=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="disclaimer.png">
                                    </div>
                                    <div class="artist-details">
                                        <h3>Disclaimer</h3>
                                        <div class="artwork-details plot-container">
                                            <p>
                                                <?php
                                                $words = preg_split('/\s+/', $about['disclaimer']);
                                                $first50 = implode(' ', array_slice($words, 0, 50));
                                                echo htmlspecialchars($first50);
                                                ?>
                                                <span class="read" title="<?php echo htmlspecialchars($about['disclaimer']); ?>">...</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="edit-support.php?id=<?php echo $about['id']; ?>&about=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="terms_of_use.png">
                                    </div>
                                    <div class="artist-details">
                                        <h3>Terms of Use</h3>
                                        <div class="artwork-details plot-container">
                                            <p>
                                                <?php
                                                $words = preg_split('/\s+/', $about['terms_of_use']);
                                                $first50 = implode(' ', array_slice($words, 0, 50));
                                                echo htmlspecialchars($first50);
                                                ?>
                                                <span class="read" title="<?php echo htmlspecialchars($about['terms_of_use']); ?>">...</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="edit-support.php?id=<?php echo $about['id']; ?>&about=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="privacy_policy.png">
                                    </div>
                                    <div class="artist-details">
                                        <h3>Privacy Policy</h3>
                                        <div class="artwork-details plot-container">
                                            <p>
                                                <?php
                                                $words = preg_split('/\s+/', $about['privacy_policy']);
                                                $first50 = implode(' ', array_slice($words, 0, 50));
                                                echo htmlspecialchars($first50);
                                                ?>
                                                <span class="read" title="<?php echo htmlspecialchars($about['privacy_policy']); ?>">...</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($qr_code) > 0) { ?>
                <ul class="artwork-list">
                    <?php foreach ($qr_code as $qr_code) { ?>
                        <li>
                            <a href="edit-support.php?id=<?php echo $qr_code['qr_code_id']; ?>&qr_code=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="qr_code_img.png">
                                    </div>
                                    <div class="artist-details">
                                        <h3>QR code</h3>
                                        <div class="artwork-details plot-container">
                                            <p>
                                                Donation text:
                                                <?php
                                                $words = preg_split('/\s+/', $qr_code['donation_text']);
                                                $first = implode(' ', array_slice($words, 0, 30));
                                                echo htmlspecialchars($first);
                                                ?>
                                                <span class="read" title="<?php echo htmlspecialchars($qr_code['donation_text']); ?>">...</span>
                                            </p>
                                        </div>
                                        <p>
                                            Acount Name: <?= $qr_code['account_name']; ?>
                                        </p>
                                        <p>
                                            Account No.: <?= $qr_code['account_no']; ?>
                                        </p>
                                        <p>
                                            QR Code: <?= $qr_code['qr_code_img']; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($social_links) > 0) { ?>
                <ul class="artwork-list">
                    <?php foreach ($social_links as $socialLinks) { ?>
                        <li>
                            <a href="edit-support.php?id=<?php echo $socialLinks['social_id']; ?>&social_links=true" class="artist-link">
                                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="artist_pic">
                                        <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="social_medias.png">
                                    </div>
                                    <div class="artist-details">
                                        <h3>Social Links</h3>
                                        <p>
                                            Facebook: <?= $socialLinks['facebook']; ?>
                                        </p>
                                        <p>
                                            Twitter: <?= $socialLinks['twitter']; ?>
                                        </p>
                                        <p>
                                            Instagram: <?= $socialLinks['instagram']; ?>
                                        </p>
                                        <p>
                                            LinkedIn: <?= $socialLinks['linkedin']; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
        </div>
    </section>
    <div class="spaces"></div>
    <!-- Link Swiper JS -->
    <script src="js/swiper-bundle.min.js"></script>
    <!-- Link To JS -->
    
</body>

</html>