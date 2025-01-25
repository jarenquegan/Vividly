<?php
// Database connection
include("config.php");
include("branding.php");

include("social_accounts.php");

// Check if the deletion was successful
if (isset($_GET['success']) && $_GET['success'] === 'true') {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Success</title>
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
        <!-- Link Swiper CSS -->
        <link rel="stylesheet" href="css/swiper-bundle.min.css">
        <!-- Fav Icon -->
        <link rel="shortcut icon" href="img/<?= $brand_logo; ?>" type="image/x-icon">
        <!-- Box Icons -->
        <link href='assets/boxicons/css/boxicons.min.css' rel='stylesheet'>
        <style>
            .profile-img {
                overflow: hidden;
                margin-bottom: 2rem;
            }

            .profile-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
        </style>
        <script>
            // Loader
            window.addEventListener("load", () => {
                const loader = document.querySelector(".loader");

                loader.classList.add("loader--hidden");

                loader.addEventListener("transitionend", () => {
                    document.body.removeChild(loader);
                });
            });
        </script>
        <?php
        if (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['post']) && $_GET['post'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-2);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['editpost']) && $_GET['editpost'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-2);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['previewed']) && $_GET['previewed'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['deleted']) && $_GET['deleted'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-2);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['notif']) && $_GET['notif'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-1);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['comment']) && $_GET['comment'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-1);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['registered']) && $_GET['registered'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['ban']) && $_GET['ban'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-1);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['deleteArtist']) && $_GET['deleteArtist'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-1);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['deleteArtwork']) && $_GET['deleteArtwork'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-1);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['deleteInside']) && $_GET['deleteInside'] === 'true') {
            echo "<script>
        setTimeout(function() {
            window.history.go(-2);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        } else {
            echo "<script>
        setTimeout(function() {
            window.history.go(-2);
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>";
        }
        ?>

    </head>

    <body>
        <!-- Loader -->
        <div class="loader" style="z-index: 99999999999999;"></div>
        </div>
        <!-- profile -->
        <section class="profile container" id="profile">
            <div class="profile-content">
                <div class="profile-img">
                    <img src="img/<?= $brand_logo; ?>" loading="lazy" alt="logo">
                </div>
                <div class="profile-text">
                    <h2><?= $brand_name; ?></h2>
                    <?php if (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['deleted']) && $_GET['deleted'] === 'true') { ?>
                        <p>
                            Account deleted successfully.
                        </p>
                        <p>
                            I am sorry to see you leave. Thank you for using <?= $brand_name; ?>.<br>
                            Redirecting to the login page. Please wait...
                        </p>
                    <?php } elseif (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['registered']) && $_GET['registered'] === 'true') { ?>
                        <p>
                            Thank you for joining us on this epic journey through the world of arts and extraordinary stories.
                        </p>
                        <p>
                            Redirecting to login page. Please wait...
                        </p>
                    <?php } else { ?>
                        <p>
                            Bravo! Your task has been carried out flawlessly, turning your effort into a beautiful success!
                        </p>
                        <p>
                            Redirecting to previous. Please wait...
                        </p>
                    <?php } ?>
                </div>
            </div>
        </section>
    </body>

    </html>
<?php
} else
    header("Location: index.php");
exit;
?>