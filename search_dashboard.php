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
$searchActor  = array();
$searchResults = array();
$input = "";

// Search Database
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $input = $_GET['search'];
    $search = '%' . preg_replace('/[^a-zA-Z0-9\s]/', '', str_replace(' ', '%', $_GET['search'])) . '%';

    $sql = "SELECT * FROM artists
                WHERE username LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(username, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(firstname, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(middlename, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(lastname, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(suffix, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(emailaddress, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(phone_number, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();
    $searchActor = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                WHERE REGEXP_REPLACE(artworks.title, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(artworks.style, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                OR REGEXP_REPLACE(artworks.description, '[^a-zA-Z0-9\s]', '') LIKE :search COLLATE utf8mb4_unicode_ci
                GROUP BY artworks.artwork_id
                ORDER BY artworks.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch all artworks if no search query is provided
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
    ORDER BY artworks.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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
    <title>Search Results</title>
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
    <script src="js/searchclick.js"></script>
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
                <a href="more_dashboard.php" class="nav-link">
                    <i class='bx bx-menu'></i>
                    <span class="nav-link-title">More</span>
                </a>
            </div>
        </div>
    </header>
    <!-- Home -->
    <section class="result-container">
        <div class="container">
            <?php if (count($searchActor) > 0) { ?>
                <div style="margin-bottom: 10px; display: flex; align-items: center;">
                    <h3 class="spanspan">
                        <?php if (count($searchActor) == 1) {
                            echo strtoupper("Found Artist");
                        } else {
                            echo strtoupper("Found Artists");
                        } ?>
                    </h3>
                    <!-- <span><?= "ARTISTS: " . count($searchActor) . " | "; ?><a href="add_artist.php?addArtist=true" class="dashspan">ADD +</a></span> -->
                </div>
                <ul class="artwork-list">
                    <?php foreach ($searchActor as $artist) {
                        $userPhoto = $artist["artist_pic"]; ?>
                        <li>
                            <a href="edit_artist.php?artist_id=<?php echo $artist['artist_id']; ?>" class="artist-link">
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
                                        <p style="display: flex; align-items: center;">
                                            <span class="spanspan">Account Type: <?php echo $artist['acct_type']; ?></span>
                                            <span>
                                                Password: <span class="read" title="<?php echo htmlspecialchars($artist['password']); ?>">###</span>
                                            </span>
                                        </p>
                                        <?php if (!empty($artist['bio'])) { ?>
                                            <div class="artwork-details plot-container">
                                                <p>
                                                    Bio:
                                                    <?php
                                                    $bio = $artist['bio'];
                                                    $words = explode(' ', $bio);

                                                    if (count($words) > 10) {
                                                        echo implode(' ', array_slice($words, 0, 10)) . ' ';
                                                        echo '<i class="read" title="' . htmlspecialchars($bio) . '">...hover to read...</i>';
                                                    } else {
                                                        echo $bio;
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        <?php } ?>
                                        <p style="text-align: right;">
                                            <?php if ($artist['is_banned'] === 0) { ?>
                                                <a href="ban_action.php?artist_id=<?php echo $artist['artist_id']; ?>&ban=true" class="dashspan">
                                                    BAN ACC <i class='bx bx-block'></i>
                                                </a>
                                            <?php } else { ?>
                                                <a href="ban_action.php?artist_id=<?php echo $artist['artist_id']; ?>&unban=true" class="dashspan">
                                                    UNBAN ACC <i class='bx bx-block'></i>
                                                </a>
                                            <?php } ?>
                                            <a href="delete_action.php?artist_id=<?php echo $artist['artist_id']; ?>&delete=true" class="dashspan">
                                                DELETE <i class='bx bx-trash'></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($searchResults) > 0) { ?>
                <div style="margin-bottom: 10px; display: flex; align-items: center;">
                    <h3 class="spanspan">
                        <?php if (count($searchResults) == 1) {
                            echo strtoupper("Found Artwork");
                        } else {
                            echo strtoupper("Found Artworks");
                        } ?>
                    </h3>
                    <!-- <span><?= "UPLOADS: " . count($searchResults); ?></span> -->
                </div>
                <ul class="artwork-list">
                    <?php foreach ($searchResults as $result) { ?>
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
                                                    // $result['created_at'] is the date string from the database
                                                    $createdDateTime = new DateTime($result['created_at']);
                                                    $currentDateTime = new DateTime();

                                                    // Calculate the difference between the two dates
                                                    $interval = $currentDateTime->diff($createdDateTime);

                                                    // Format the output based on the difference
                                                    if ($interval->y > 0) {
                                                        echo ($interval->y == 1) ? '1 year ago' : $interval->format('%y years ago');
                                                    } elseif ($interval->m > 0) {
                                                        echo ($interval->m == 1) ? '1 month ago' : $interval->format('%m months ago');
                                                    } elseif ($interval->d > 0) {
                                                        echo ($interval->d == 1) ? '1 day ago' : $interval->format('%d days ago');
                                                    } elseif ($interval->h > 0) {
                                                        echo ($interval->h == 1) ? '1 hour ago' : $interval->format('%h hours ago');
                                                    } elseif ($interval->i > 0) {
                                                        echo ($interval->i == 1) ? '1 minute ago' : $interval->format('%i minutes ago');
                                                    } else {
                                                        echo 'Less than a minute ago';
                                                    }
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
                                        <p><i class='bx bx-calendar-alt'></i> <?php echo date('F j, Y \a\t g:i A', strtotime($result['created_at'])); ?></p>
                                        <p style="text-align: right;">
                                            <a href="delete_action.php?artwork_id=<?php echo $result['artwork_id']; ?>&delete=true" class="dashspan">
                                                DELETE <i class='bx bx-trash'></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
            <?php } ?>
            <?php if (count($searchResults) == 0 && count($searchActor) == 0) { ?>
                <div class="no-results">
                    <i class='bx bx-search'></i>
                    <p><br>No results found.<br>Please try another keyword.</p>
                </div>
            <?php } ?>
        </div>
    </section>
    <div class="spaces"></div>
    <!-- Link Swiper JS -->
    <script src="js/swiper-bundle.min.js"></script>
    <!-- Link To JS -->

</body>

</html>