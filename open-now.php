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


if (isset($_GET['id'])) {
    $artwork_id = $_GET['id'];

    $sql = "SELECT * FROM artworks WHERE artwork_id = :artwork_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
    $stmt->execute();
    $artwork = $stmt->fetch(PDO::FETCH_ASSOC);

    // Retrieve the associated artists for the artwork
    $sql = "SELECT artists.*
        FROM artists
        INNER JOIN artists_artworks ON artists.artist_id = artists_artworks.artist_id
        WHERE artists_artworks.artwork_id = :artwork_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artwork_id', $artwork_id);
    $stmt->execute();
    $associatedArtists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $isFavorite = false;
    // Check if the artwork is in the user's favorites
    $sql = "SELECT * FROM artist_liked_artworks WHERE artist_id = :artist_id AND artwork_id = :artwork_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
    $stmt->execute();
    $favorite = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($favorite !== false) {
        $isFavorite = true;
    }

    $sql = "SELECT COUNT(*) AS total_likes
            FROM artist_liked_artworks
            WHERE artwork_id = :artwork_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artwork_id', $artwork_id);
    $stmt->execute();
    $totLike = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT comments.comment_text, comments.created_at, artists.artist_id, artists.username, artists.emailaddress, artists.firstname, artists.middlename, artists.lastname, artists.suffix, artists.artist_pic
        FROM comments
        INNER JOIN artists ON comments.artist_id = artists.artist_id
        WHERE comments.artwork_id = :artwork_id
        ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Initialize variables
$input = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artwork['title']); ?></title>
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
    <script src="js/height.js" defer></script>
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
    <!-- Play Artwork Container -->
    <div class="play-container container">
        <!-- PLay Image -->
        <img src="<?php echo "./artworks/" . htmlspecialchars($artwork['image_url']); ?>" loading="lazy" alt="<?php echo htmlspecialchars($artwork['title']); ?>" class="play-img">
        <!-- Play Text -->
        <div class="play-text">
            <h2 style="width: 30%;"><?php echo htmlspecialchars($artwork['title']); ?></h2>
            <!-- Ratings -->
            <div class="rating" id="likes-count">
                Likes: <?php echo $totLike['total_likes']; ?>
            </div>
            <div class="rating" id="comments-count">
                Comments: <?= count($comments); ?>
            </div>
            <!-- Tags -->
            <div class="tags">
                <?php
                $genres = explode(',', $artwork['style']);
                foreach ($genres as $genre) {
                    echo '<span>' . htmlspecialchars(trim($genre)) . '</span>';
                }
                ?>
            </div>
            <form id="favoritesForm" action="toggle_favorites.php" method="POST" class="open-btn download-btn add-to-favorites-form">
                <button type="submit" class="open-btn download-btn add-to-favorites" style="cursor:pointer; border: none; outline: none; background: transparent;">
                    <?php if ($isFavorite) : ?>
                        <i class="bx bxs-like"></i>
                        <span>Dislike</span>
                    <?php else : ?>
                        <i class="bx bx-like"></i>
                        <span>Like</span>
                    <?php endif; ?>
                </button>
                <input type="hidden" name="artworkId" value="<?php echo $artwork['artwork_id']; ?>">
                <input type="hidden" name="isFavorite" value="<?php echo $isFavorite; ?>">
            </form>
        </div>
        <!-- PLay Btn -->
        <a href="<?php echo "./artworks/" . htmlspecialchars($artwork['image_url']); ?>" download style="text-decoration: none; color: inherit;">
            <i class="bx bxs-download play-artwork"></i>
        </a>
        <!-- Video Container -->
        <div class="video-container">
            <!-- Video-Box -->
            <div class="video-box">
                <img id="myimage" src="<?php echo "./artworks/" . htmlspecialchars($artwork['image_url']); ?>" class="image-container">
                <!-- Close Video Icon -->
                <i class="bx bx-x close-video"></i>
            </div>
        </div>
    </div>
    <!-- About -->
    <div class="about-artwork container">
        <h2><?php echo htmlspecialchars($artwork['title']); ?></h2>

        <?php
        $description = $artwork['description'];
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

        <?php foreach ($associatedArtists as $artists) { ?>
            <?php if ($artists['artist_id'] === $artist['artist_id']) : ?>
                <p>
                    <a href="edit_post.php?id=<?= $artwork['artwork_id']; ?>" class="dashspan">
                        <i class='bx bxs-edit-alt'></i> Edit Post
                    </a>
                </p>
                <p>
                    <a href="delete_post_confirmation_inside.php?id=<?= $artwork['artwork_id']; ?>" class="dashspan">
                        <i class='bx bx-trash'></i> Delete
                    </a>
                </p>
            <?php endif; ?>
        <?php } ?>
        <!-- Artwork Cast -->
        <h2 class="cast-heading">Artwork By</h2>
        <ul class="artist-list">
            <?php foreach ($associatedArtists as $artists) {
                $artistPhoto = $artists["artist_pic"]; ?>
                <li>
                    <a href="artist.php?artist=<?php echo $artists['artist_id']; ?>" class="artist-link">
                        <div class="artist-item">
                            <div class="artist_pic">
                                <img src="images/<?php echo $artists['artist_pic']; ?>" loading="lazy" alt="<?php echo $artists['username']; ?>">
                            </div>
                            <div class="artist-details">
                                <h3>
                                    <?php
                                    if (!empty($artists['firstname']) || !empty($artists['middlename']) || !empty($artists['lastname']) || !empty($artists['suffix'])) {
                                        $middleInitial = !empty($artists['middlename']) ? substr($artists['middlename'], 0, 1) . "." : "";
                                        echo $artists['firstname'] . " " . $middleInitial . " " . $artists['lastname'];
                                        if (!empty($artists['suffix'])) {
                                            echo ", " . $artists['suffix'];
                                        }
                                    } else {
                                        echo $artists['username'];
                                    }
                                    ?>
                                </h3>
                                <p><i><?php echo $artists['emailaddress']; ?></i></p>
                            </div>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="about-artwork container">
        <h2 class="cast-heading">Write a comment</h2>
        <form action="add_comment.php" class="feedback-form review" id="commentForm" method="POST" style="max-width: 1060px; margin: auto; width: 100%; display: block;">
            <textarea id="review" name="review" cols="30" rows="3" placeholder="Write a comment here..." class="message" style="resize: none; margin-bottom: 1.5rem; max-width: 1060px" required></textarea>
            <input type="hidden" name="artworkId" value="<?php echo $artwork['artwork_id']; ?>">
            <input type="submit" value="Comment" class="send-btn" style="max-width: 1060px; margin: auto; width: 100%;">
        </form>
    </div>
    <div class="about-artwork container">
        <h2 class="cast-heading" id="comments-heading" style="margin-bottom: 10px; display: flex; align-items: center;">
            <?php if (count($comments) == 1) {
                echo strtoupper("Comment");
            } elseif (count($comments) == 0) {
                echo strtoupper("No comments yet");
            } else {
                echo strtoupper("Comments");
            } ?>
        </h2>
        <ul class="artist-list" id="comments-container">
        </ul>
        <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
    </div>
    <!-- Download -->
    <section class="download container" id="download">
        <div class="download">
            <h2 class="download-title">Want this piece of art? Download Now</h2>
            <div class="download-links">
                <a href="<?php echo "./artworks/" . htmlspecialchars($artwork['image_url']); ?>" download>SAVE</a>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <section class="footer container" style="margin-top: 2rem;" id="footer">
        <div class="social">
            <a href="<?php echo $facebook; ?>"><i class="bx bxl-facebook"></i></a>
            <a href="<?php echo $twitter; ?>"><i class="bx bxl-twitter"></i></a>
            <a href="<?php echo $instagram; ?>"><i class="bx bxl-instagram"></i></a>
            <a href="<?php echo $linkedin; ?>"><i class="bx bxl-linkedin"></i></a>
        </div>
        <!-- Footer Links -->
        <div class="footer-links">
            <a href="support.php#disclaimer">Disclaimer</a>
            <a href="support.php#terms">Terms Of Use</a>
            <a href="support.php#privacy">Privacy Policy</a>
        </div>
        <!-- Copyright -->
        <p><a href="more.php" style="text-decoration: none; color: inherit;"><?= $brand_name; ?> &#169; 2023</a></p>
    </section>

    <script src="js/like_toggle.js"></script>
    <script>
        // Include the artist_id and artwork_id in a JavaScript variable
        const currentArtistId = <?php echo json_encode($_SESSION['artist_id']); ?>;
        const artworkId = <?php echo json_encode($_GET['id']); ?>;
        <?php
        $artwork_id = $_GET['id'];

        $sql = "SELECT * FROM artworks WHERE artwork_id = :artwork_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':artwork_id', $artwork_id, PDO::PARAM_INT);
        $stmt->execute();
        $artwork = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retrieve the associated artists for the artwork
        $sql = "SELECT artists.*
        FROM artists
        INNER JOIN artists_artworks ON artists.artist_id = artists_artworks.artist_id
        WHERE artists_artworks.artwork_id = :artwork_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':artwork_id', $artwork_id);
        $stmt->execute();
        $associatedArtists = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if the current artist is associated with the artwork
        $isAssociated = false;
        foreach ($associatedArtists as $associatedArtist) {
            if ($associatedArtist['artist_id'] == $_SESSION['artist_id']) {
                $isAssociated = true;
                break;
            }
        }
        ?>
        const isAssociated = <?php echo json_encode($isAssociated); ?>;
    </script>
    <script src="js/comment.js"></script>
    <!-- Link Swiper Files -->
    <script src="js/swiper-bundle.min.js"></script>
    <!-- Link To JS -->
</body>

</html>