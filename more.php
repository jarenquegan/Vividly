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
$sender = "";
$sender_email = "";

// Fetch owner
$sql = "SELECT * FROM branding WHERE brand_id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$brand = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

                $sender = $artist['firstname'] . " " . $artist['lastname'];
                $sender_email = $artist['emailaddress'];
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
                    <a href="more.php" class="nav-link nav-active">
                        <i class='bx bx-menu'></i>
                        <span class="nav-link-title">More</span>
                    </a>
                </div>
            <?php else : ?>
                <span class="user" style="visibility: hidden; opacity:0"><img src="img/<?= $brand_logo; ?>" loading="lazy" alt="<?= $brand_name; ?>" class="user-img"></span>
            <?php endif; ?>
        </div>
    </header>
    <!-- Home -->
    <section class="creator container" id="creator">
        <div class="creator-content">
            <?php foreach ($brand as $brand) { ?>
                <div class="creator-img">
                    <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="<?= $brand['brand_logo']; ?>">
                </div>
                <div class="creator-text">
                    <h3> EST. 2024 </h3>
                    <h2>
                        <?= $brandname; ?>
                    </h2>
                    <?php
                    $description = $brand['brand_info'];
                    $paragraphs = explode("\n\n", $description); // Split description into paragraphs using double line breaks
                    foreach ($paragraphs as $paragraph) {
                        $lines = explode("\n", $paragraph); // Split each paragraph into separate lines
                        echo "<p>";
                        foreach ($lines as $line) {
                            echo "{$line}<br>"; // Print each line with a line break (<br>) to keep them separate
                        }
                        echo "</p>"; // Add a double line break between paragraphs
                    }
                    ?>
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
    <!-- About -->
    <section class="about container" id="about">
        <!-- Heading -->
        <div class="heading-2">
            <h2 class="heading-title-2">About</h2>
        </div>
        <!-- About Content -->
        <div class="about-content">
            <div class="about-text">
                <p>
                    <?php
                    foreach ($data as $row) {
                        $about = $row['about'];
                    }
                    echo $about;
                    ?>
                </p>
            </div>
        </div>
    </section>
    <!-- Reviews -->
    <?php if (count($reviews) > 0) { ?>
        <section class="container reviews" id="reviews">
            <!-- Heading -->
            <div class="heading-2">
                <h2 class="heading-title-2">Reviews</h2>
            </div>
            <!-- Review Content -->
            <div class="reviews-content swiper">
                <div class="swiper-wrapper">
                    <?php
                    foreach ($reviews as $review) {
                        $reviewText = $review['review_content'];
                        $firstName = $review['firstname'];
                        $lastName = $review['lastname'];
                        $created_at = $review['created_at'];
                        $userPic = $review['artist_pic'];
                        $userID = $review['artist_id'];
                    ?>
                        <div class="swiper-slide">
                            <!-- Review Box -->
                            <div class="review-box">
                                <i class='bx bxs-quote-right'></i>
                                <?php
                                $words = explode(' ', $reviewText);
                                $firstFiftyWords = implode(' ', array_slice($words, 0, 50));
                                ?>
                                <p class="review-text">
                                    <?php echo $firstFiftyWords; ?>
                                </p>
                                <div class="review-profile">
                                    <?php if (empty($firstName) && empty($lastName)) : ?>
                                        <h2><?php echo $userID; ?></h2>
                                    <?php else : ?>
                                        <h2><?php echo $firstName . ' ' . $lastName; ?></h2>
                                    <?php endif; ?>
                                    <span><?php echo date("F j, Y", strtotime($created_at)); ?></span>
                                    <img src="<?php echo "./images/" . $userPic; ?>" loading="lazy" alt="<?php echo $userName; ?>">
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    <?php } ?>
    <?php if (isset($_SESSION['username']) || isset($_SESSION['emailaddress']) && isset($_SESSION['password'])) : ?>
        <!-- Review Form -->
        <section class="feedback review container" id="review">
            <!-- Heading -->
            <div class="heading-2">
                <h2 class="heading-title-2">Leave a Review</h2>
            </div>
            <!-- feedback Form -->
            <form action="add_review.php" class="feedback-form review" id="feedback-form review" method="POST">
                <textarea id="review" name="review" cols="30" rows="5" placeholder="You can write your review here..." class="message" style="resize: none;" required></textarea>
                <input type="submit" value="Add Review" class="send-btn">
            </form>
        </section>
    <?php endif; ?>
    <!-- support -->
    <section class="support container" id="support">
        <!-- Heading -->
        <div class="heading-2">
            <h2 class="heading-title-2">support</h2>
        </div>
        <!-- support Content -->
        <?php
        // Loop through the data and display it
        foreach ($data as $row) {
            $disclaimer = $row['disclaimer'];
            $termsOfUse = $row['terms_of_use'];
            $privacyPolicy = $row['privacy_policy'];
        ?>
            <div class="support-content">
                <div class="support-box">
                    <i class='bx bxs-edit'></i>
                    <h2>Terms of Use</h2>
                    <?php
                    $termsWords = explode(' ', $termsOfUse);
                    $maxWords = 35;
                    $excerpt = implode(' ', array_slice($termsWords, 0, $maxWords));
                    $showReadMore = count($termsWords) > $maxWords;
                    ?>
                    <p>
                        <?php echo $excerpt; ?>
                        <?php if ($showReadMore) : ?>
                            <br><a href="support.php#terms"><i class="read" title="<?php echo htmlspecialchars($termsOfUse); ?>">...read more...</i></a>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="support-box">
                    <i class='bx bx-error'></i>
                    <h2>Disclaimer</h2>
                    <?php
                    $disclaimerWords = explode(' ', $disclaimer);
                    $maxWords = 35;
                    $excerpt = implode(' ', array_slice($disclaimerWords, 0, $maxWords));
                    $showReadMore = count($disclaimerWords) > $maxWords;
                    ?>
                    <p>
                        <?php echo $excerpt; ?>
                        <?php if ($showReadMore) : ?>
                            <br><a href="support.php#disclaimer"><i class="read" title="<?php echo htmlspecialchars($disclaimer); ?>">...read more...</i></a>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="support-box">
                    <i class='bx bx-lock'></i>
                    <h2>Privacy Policy</h2>
                    <?php
                    $policyWords = explode(' ', $privacyPolicy);
                    $maxWords = 35;
                    $excerpt = implode(' ', array_slice($policyWords, 0, $maxWords));
                    $showReadMore = count($policyWords) > $maxWords;
                    ?>
                    <p>
                        <?php echo $excerpt; ?>
                        <?php if ($showReadMore) : ?>
                            <br><a href="support.php#privacy"><i class="read" title="<?php echo htmlspecialchars($privacyPolicy); ?>">...read more...</i></a>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php
        }
        ?>
    </section>
    <!-- feedback Form -->
    <section class="feedback container" id="feedback">
        <!-- Heading -->
        <div class="heading-2">
            <h2 class="heading-title-2">feedback</h2>
        </div>
        <!-- feedback Form -->
        <form action="send_feedback.php" class="feedback-form" id="feedback-form" method="POST">
            <input type="text" id="name" name="name" placeholder="Your Name" class="name" value="<?php echo $sender; ?>" required>
            <input type="email" id="email" name="email" placeholder="Email address" class="email" value="<?php echo $sender_email; ?>" required>
            <textarea id="message" name="message" cols="30" rows="10" placeholder="Write your message here..." class="message" style="resize: none;" required></textarea>
            <input type="submit" value="Send" class="send-btn">
        </form>
    </section>
    <section class="donate container" id="donate">
        <!-- Heading -->
        <div class="heading-2">
            <h2 class="heading-title-2">Donate</h2>
        </div>
        <!-- Donation Content -->
        <div class="donate creator-text">
            <?php
            foreach ($qr_code as $qr_code) {
            ?>
                <p><?= $qr_code['donation_text']; ?></p>
                <div class="qr-code">
                    <img src="img/<?= $qr_code['qr_code_img']; ?>" loading="lazy" alt="QR Code">
                </div>
                <p>Account Name: <?= $qr_code['account_name']; ?>
                    <br>Account Number: <?= $qr_code['account_no']; ?>
                </p>
            <?php
            }
            ?>
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
    <script>
        // Reviews Swiper
        var swiper = new Swiper(".reviews-content", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    </script>
</body>

</html>