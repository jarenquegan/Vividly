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
$searchResults = array();
$input = "";

// Check if the artist name is present in the URL
if (isset($_GET['artist'])) {
    $artistId = $_GET['artist'];

    // Retrieve artworks where the artist is also seen and count the likes of that artwork
    $sql = "SELECT artworks.artwork_id, artworks.*, 
                COUNT(DISTINCT artist_liked_artworks.id) AS total_likes,
                COUNT(DISTINCT comments.comment_id) AS total_comments
            FROM artworks
            INNER JOIN artists_artworks ON artworks.artwork_id = artists_artworks.artwork_id
            INNER JOIN artists ON artists_artworks.artist_id = artists.artist_id
            LEFT JOIN artist_liked_artworks ON artworks.artwork_id = artist_liked_artworks.artwork_id
            LEFT JOIN comments ON artworks.artwork_id = comments.artwork_id
            WHERE artists.artist_id = :artist_id
        GROUP BY artworks.artwork_id
        ORDER BY artworks.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artistId, PDO::PARAM_INT);
    $stmt->execute();
    $artworksWithArtists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalLikes = 0;
    foreach ($artworksWithArtists as $artwork) {
        $totalLikes += $artwork['total_likes'];
    }

    $sql = "SELECT * FROM artists WHERE artist_id = :artist_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artistId);
    $stmt->execute();
    $artistInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    $artistFullname = "";

    if (!empty($artistInfo['firstname']) || !empty($artistInfo['middlename']) || !empty($artistInfo['lastname']) || !empty($artistInfo['suffix'])) {
        $middleInitial = !empty($artistInfo['middlename']) ? substr($artistInfo['middlename'], 0, 1) . "." : "";
        $artistFullname = $artistInfo['firstname'] . " " . $middleInitial . " " . $artistInfo['lastname'];
        if (!empty($artistInfo['suffix'])) {
            $artistFullname .= ", " . $artistInfo['suffix'];
        }
    } else {
        $artistFullname = $artist['username'];
    }

    $followerId = $_SESSION['artist_id'];
    $isFollowing = false;

    // Check if the artist is being followed by the user
    $sql = "SELECT * FROM artist_followers WHERE follower_id = :follower_id AND artist_id = :artist_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':follower_id', $followerId, PDO::PARAM_INT);
    $stmt->bindParam(':artist_id', $artistId, PDO::PARAM_INT);
    $stmt->execute();
    $follow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($follow !== false) {
        $isFollowing = true;
    }

    $sql = "SELECT COUNT(*) AS total_followers
            FROM artist_followers
            WHERE artist_id = :artist_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artistId, PDO::PARAM_INT);
    $stmt->execute();
    $totalFollowers = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $artistFullname . " | Portfolio" ?></title>
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
    <section class="result-container">
        <div class="container">
            <ul class="artwork-list">
                <li>
                    <span href="artist.php?artist=<?php echo $artistInfo['artist_id']; ?>" class="artist-link">
                        <div class="artist-item" style="padding-top: 10px;">
                            <div class="artist_pic">
                                <img src="images/<?php echo $artistInfo['artist_pic']; ?>" loading="lazy" alt="<?php echo $artistInfo['username']; ?>">
                            </div>
                            <div class="artist-details">
                                <h1>
                                    <?php
                                    if (!empty($artistInfo['firstname']) || !empty($artistInfo['middlename']) || !empty($artistInfo['lastname']) || !empty($artistInfo['suffix'])) {
                                        $middleInitial = !empty($artistInfo['middlename']) ? substr($artistInfo['middlename'], 0, 1) . "." : "";
                                        echo $artistInfo['firstname'] . " " . $middleInitial . " " . $artistInfo['lastname'];
                                        if (!empty($artistInfo['suffix'])) {
                                            echo ", " . $artistInfo['suffix'];
                                        }
                                    } else {
                                        echo $artistInfo['username'];
                                    }
                                    ?>
                                </h1>
                                <!-- </span> -->
                                <!-- <span> -->
                                <p>@<?php echo $artistInfo['username']; ?></p>
                                <div id="followers-count">
                                    <i class="bx bxs-heart"></i> <?php echo $totalFollowers['total_followers']; ?>
                                </div>
                                <p><i class='bx bxs-like'></i> <?= $totalLikes; ?></p>
                            </div>
                        </div>
                    </span>
                </li>
                <?php if (!empty($artistInfo['bio'])) : ?>
                    <p style="text-align: center; margin-top: 1rem;"><?= $artistInfo['bio']; ?></p>
                <?php endif; ?>
                <h3 style="margin-top: 1rem; margin-bottom: 1rem;">Personal Details</h3>
                <?php if (!empty($artistInfo['birthdate'])) : ?>
                    <p style="padding-left: 1rem;"><i class='bx bxs-balloon'></i> <?php echo date('F j, Y', strtotime($artistInfo['birthdate'])); ?></p>
                <?php endif; ?>
                <?php if (!empty($artistInfo['emailaddress'])) : ?>
                    <p style="padding-left: 1rem;"><i class='bx bxs-envelope'></i> <?= $artistInfo['emailaddress']; ?></p>
                <?php endif; ?>
                <?php if (!empty($artistInfo['address'])) : ?>
                    <p style="padding-left: 1rem;"><i class='bx bxs-home'></i> Lives in <?= $artistInfo['address']; ?></p>
                <?php endif; ?>
                <?php if (!empty($artistInfo['phone_number'])) : ?>
                    <p style="padding-left: 1rem;"><i class='bx bxs-phone'></i> <?php echo $artistInfo['phone_number']; ?></p>
                <?php endif; ?>
                <P style="margin-bottom: 1rem;"></P>
                <?php if ($artistInfo['artist_id'] === $artist['artist_id']) : ?>
                    <p>
                        <a href="edit_profile.php?artist=<?= $artist['artist_id']; ?>" class="dashspan">
                            <i class='bx bxs-edit-alt'></i> Edit profile
                        </a>
                    </p>
                    <P style="margin-bottom: 1rem;"></P>
                <?php endif; ?>
                <?php if ($artistInfo['artist_id'] !== $artist['artist_id']) : ?>
                    <form id="followForm" action="toggle_follow.php" method="POST" class="open-btn download-btn follow-artist-form">
                        <button type="submit" class="open-btn download-btn follow-artist" style="cursor:pointer; border: none; outline: none; background: transparent;">
                            <?php if ($isFollowing) : ?>
                                <i class="bx bxs-heart"></i>
                                <span>Unfollow</span>
                            <?php else : ?>
                                <i class="bx bx-heart"></i>
                                <span>Follow</span>
                            <?php endif; ?>
                        </button>
                        <input type="hidden" name="artistId" value="<?php echo $artistInfo['artist_id']; ?>">
                        <input type="hidden" name="isFollowing" value="<?php echo $isFollowing; ?>">
                    </form>
                    <p style="margin-bottom: 1rem;"></p>
                <?php endif; ?>
            </ul>
            <?php if (count($artworksWithArtists) > 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;"><?php if (count($artworksWithArtists) == 1) {
                                                                                            echo strtoupper("Creative work");
                                                                                        } else {
                                                                                            echo strtoupper("Creative works");
                                                                                        } ?></h3>
                <ul class="artwork-list">
                    <?php foreach ($artworksWithArtists as $result) { ?>
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
                                                <i class='bx bx-user'></i> <?php echo $artistFullname; ?>
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
                                        <?php if ($artistInfo['artist_id'] === $artist['artist_id']) : ?>
                                            <p style="text-align: right;">
                                                <a href="edit_post.php?id=<?= $result['artwork_id']; ?>" class="dashspan">
                                                    <i class='bx bxs-edit-alt'></i>
                                                </a>
                                            </p>
                                            <p style="text-align: right;">
                                                <a href="delete_post_confirmation.php?id=<?= $result['artwork_id']; ?>" class="dashspan">
                                                    <i class='bx bx-trash'></i>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($artworksWithArtists) == 0) { ?>
                <h3 style="margin-bottom: 10px; display: flex; align-items: center;"><?php echo strtoupper("No creative works yet"); ?></h3>
            <?php } ?>
        </div>
    </section>
    <div class="spaces"></div>
    <script src="js/follow_toggle.js"></script>
    <!-- Link Swiper JS -->
    <script src="js/swiper-bundle.min.js"></script>
    <!-- Link To JS -->
</body>

</html>