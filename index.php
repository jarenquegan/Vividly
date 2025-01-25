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

// Retrieve random artworks from the database
$sql = "SELECT artworks.artwork_id, artworks.*,
            artists.artist_id,
            artists.username,
            artists.firstname,
            artists.middlename,
            artists.lastname,
            artists.suffix,
            COUNT(DISTINCT artist_liked_artworks.id) AS total_likes,
            COUNT(DISTINCT comments.comment_id) AS total_comments
        FROM artworks
        INNER JOIN artists_artworks ON artworks.artwork_id = artists_artworks.artwork_id
        INNER JOIN artists ON artists_artworks.artist_id = artists.artist_id
        LEFT JOIN artist_liked_artworks ON artworks.artwork_id = artist_liked_artworks.artwork_id
        LEFT JOIN comments ON artworks.artwork_id = comments.artwork_id
    GROUP BY artworks.artwork_id
    ORDER BY total_likes DESC
    LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute();
$artworksWithArtists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve random artists from the database
$sql = "SELECT * FROM artists WHERE artist_id != $artist_id ORDER BY RAND() LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$actrand = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT artworks.artwork_id, artworks.*,
            artists.artist_id,
            artists.username,
            artists.firstname,
            artists.middlename,
            artists.lastname,
            artists.suffix,
            COUNT(DISTINCT artist_liked_artworks.id) AS total_likes,
            COUNT(DISTINCT comments.comment_id) AS total_comments
        FROM artworks
        INNER JOIN artists_artworks ON artworks.artwork_id = artists_artworks.artwork_id
        INNER JOIN artists ON artists_artworks.artist_id = artists.artist_id
        LEFT JOIN artist_liked_artworks ON artworks.artwork_id = artist_liked_artworks.artwork_id
        LEFT JOIN comments ON artworks.artwork_id = comments.artwork_id
        INNER JOIN artist_followers ON artists.artist_id = artist_followers.artist_id
    WHERE artist_followers.follower_id = :artist_id
    GROUP BY artworks.artwork_id
    ORDER BY artworks.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
$stmt->execute();
$artworksOfFollowedArtists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize variables
$input = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $brand_name; ?> | Home</title>
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
                <a href="index.php" class="nav-link nav-active">
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
        </div>
    </header>
    <!-- Home -->
    <section class="home container" id="home">
        <!-- Home Image -->
        <img src="" loading="lazy" alt="" class="home-img">
        <!-- Home Text -->
        <div class="home-text">
            <p>10 Most Lked Artworks</p>
            <h1 class="home-title" style="width: 50%;"></h1>
            <p class="artist-name"></p>
            <a href="" class="open-btn" id="open-btn">
                <i class='bx bx-show'></i>
                <span>See this artwork</span>
            </a>
        </div>
    </section>
    <!-- Home End -->
    <script src="js/banner.js"></script>
    <!-- Review Form -->
    <!-- <section class="popular container" id="popular"> -->
    <!-- Heading -->
    <!-- <div class="heading"> -->
    <!-- <h2 class="heading-title">Submit a Challenge</h2> -->
    <!-- </div> -->
    <!-- feedback Form -->
    <!-- <form action="add_review.php" class="feedback-form review" id="feedback-form review" method="POST" style="max-width: 1060px; margin: auto; width: 100%; display: block;"> -->
    <!-- <textarea id="review" name="review" cols="30" rows="4" placeholder="Write a challenge here and maybe one day it will come up..." class="message" style="resize: none; margin-bottom: 1.5rem; max-width: 1060px" required></textarea> -->
    <!-- <input type="submit" value="Submit" class="send-btn" style="max-width: 1060px; margin: auto; width: 100%;"> -->
    <!-- </form> -->
    <!-- </section> -->
    <!-- Artworks Library Section Start -->
    <section class="popular container" id="popular">
        <!-- Heading -->
        <div class="heading">
            <h2 class="heading-title">Top 10 Popular Arts</h2>
            <!-- Swiper Buttons -->
            <div class="swiper-btn">
                <div class="swiper-button-prev swiper-button-prev1"></div>
                <div class="swiper-button-next swiper-button-next1"></div>
            </div>
        </div>
        <!-- Content -->
        <div class="popular-content popular-content1 swiper">
            <div class="swiper-wrapper">
                <!-- Artworks Box -->
                <?php foreach ($artworksWithArtists as $artworksWithArtists) { ?>
                    <div class="swiper-slide">
                        <div class="artwork-box">
                            <img src="<?php echo "./artworks/" . $artworksWithArtists['image_url']; ?>" loading="lazy" alt="<?php echo $artworksWithArtists['title']; ?>" class="artwork-box-img">
                            <div class="box-text">
                                <h2 class="artwork-title" style="width: 80%;"><?php echo $artworksWithArtists['title']; ?></h2>
                                <span class="artwork-type" style="width: 80%;">@<?php echo $artworksWithArtists['username']; ?></span>
                                <span class="artwork-type" style="width: 80%;"><?php echo str_replace(',', ' • ', $artworksWithArtists['style']); ?></span>
                                <span class="artwork-type" style="width: 80%;"><?php echo date('Y', strtotime($artworksWithArtists['created_at'])); ?></span>
                                <a href="open-now.php?id=<?php echo $artworksWithArtists['artwork_id']; ?>" class="open-btn play-btn">
                                    <i class='bx bx-show'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- Artworks Library Section End -->
    <section class="result-container" style="margin-top: -2rem">
        <div class="container">
            <?php if (count($actrand) > 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span class="spanspan">
                        <?php echo strtoupper("Artists you may know"); ?>
                    </span>
                    <!-- <a href="add-user.php?users=true" class="dashspan">ADD <i class='bx bx-plus-medical'></i></a> -->
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
                                                        echo $artist['firstname'] . " " . $middleInitial . " <span class='color'>" . $artist['lastname'] . "</span>";
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
                                            <!-- <span>
                                                Password: <span class="read" title="<?php echo htmlspecialchars($artist['password']); ?>">###</span>
                                            </span> -->
                                            <span>
                                                <?php echo $artist['phone_number']; ?>
                                            </span>
                                        </p>
                                        <!-- <p>Account Type: <?php echo $artist['acct_type']; ?></p> -->
                                        <!-- <div class="artwork-details plot-container">
                                            <p>
                                                Bio: <?php echo preg_replace('/((\S+\s+){10}).*/', '$1', $artist['bio']); ?>
                                                <span class="read" title="<?php echo htmlspecialchars($artist['bio']); ?>">...</span>
                                            </p>
                                        </div> -->
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
        </div>
        <div class="container">
            <?php if (count($artworksOfFollowedArtists) > 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;"><?php echo strtoupper("Followed Artists' Art"); ?></h3>
                <ul class="artwork-list">
                    <?php foreach ($artworksOfFollowedArtists as $result) { ?>
                        <li>
                            <a href="open-now.php?id=<?php echo $result['artwork_id']; ?>" class="artwork-link">
                                <div class="artwork-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                                    <div class="poster">
                                        <img src="artworks/<?php echo $result['image_url']; ?>" loading="lazy" alt="<?php echo $result['title']; ?>">
                                    </div>
                                    <div class="artwork-details">
                                        <div style="display: flex; align-items: center;">
                                            <h3 class="spanspan"><?php echo $result['title']; ?></h3>
                                            <span>
                                                <p>
                                                    <?php
                                                    $formatted_time_difference = format_time_difference($result['created_at']);
                                                    echo $formatted_time_difference;
                                                    ?>
                                                    <i class='bx bx-time'></i>
                                                </p>
                                            </span>
                                        </div>
                                        <p style="display: flex; align-items: center;">
                                            <span class="spanspan">
                                                <i class='bx bx-user'></i>
                                                <?php
                                                if (!empty($result['firstname']) || !empty($result['middlename']) || !empty($result['lastname']) || !empty($result['suffix'])) {
                                                    $middleInitial = !empty($result['middlename']) ? substr($result['middlename'], 0, 1) . "." : "";
                                                    echo $result['firstname'] . " " . $middleInitial . " " . $result['lastname'];
                                                    if (!empty($result['suffix'])) {
                                                        echo ", " . $result['suffix'];
                                                    }
                                                } else {
                                                    echo $result['username'];
                                                }
                                                ?>
                                            </span>
                                        </p>
                                        <p><i class='bx bx-like'></i> <?php echo $result['total_likes']; ?></p>
                                        <p><i class='bx bx-comment'></i> <?php echo $result['total_comments']; ?></p>
                                        <?php if (!empty($result['style'])) : ?>
                                            <p style="display: flex; align-items: center;"><span class="spanspan"><i class='bx bx-tag'></i> <?php echo str_replace(',', ' • ', $result['style']); ?></span></p>
                                        <?php endif; ?>
                                        <?php if (!empty($result['description'])) : ?>
                                            <div class="artwork-details plot-container">
                                                <p><i class='bx bx-notepad'></i>
                                                    <?php
                                                    $description = $result['description'];
                                                    $words = explode(' ', $description);

                                                    if (count($words) > 30) {
                                                        echo implode(' ', array_slice($words, 0, 30)) . ' ';
                                                        echo '<i class="read" title="' . htmlspecialchars($description) . '">...see more...</i>';
                                                    } else {
                                                        echo $description;
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                        <?php $formatted_time = format_date_time($result['created_at']); ?>
                                        <p><i class='bx bx-calendar-alt'></i> <?php echo $formatted_time; ?></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($artworksOfFollowedArtists) == 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;"><?php echo strtoupper("Follow Artist for Art"); ?></h3>
            <?php } ?>
        </div>
    </section>
    <div class="spaces"></div>
    <!-- Link Swiper JS -->
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/swipe.js"></script>
</body>

</html>