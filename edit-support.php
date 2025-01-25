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

// Retrieve user information from the database
$artist_id = $_SESSION['artist_id'];
$sql = "SELECT * FROM artists WHERE artist_id = :artist_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_STR);
$stmt->execute();
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "ID not provided";
    exit;
}

$id = $_GET['id'];

// Fetch owner
$sql = "SELECT * FROM branding WHERE brand_id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$brand = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch data from the about table
$sql = "SELECT * FROM about WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$about = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch data from the social_accounts table
$sql = "SELECT * FROM social_accounts WHERE social_id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$social_links = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch data from the qr_code table
$sql = "SELECT * FROM qr_code WHERE qr_code_id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$qr_code = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission to update the tables
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['about']) && $_GET['about'] === 'true') {
        // Update about table
        $about = $_POST['about'];
        $disclaimer = $_POST['disclaimer'];
        $terms_of_use = $_POST['terms_of_use'];
        $privacy_policy = $_POST['privacy_policy'];

        $sql = "UPDATE about SET about = :about, disclaimer = :disclaimer, terms_of_use = :terms_of_use, privacy_policy = :privacy_policy WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':about', $about);
        $stmt->bindParam(':disclaimer', $disclaimer);
        $stmt->bindParam(':terms_of_use', $terms_of_use);
        $stmt->bindParam(':privacy_policy', $privacy_policy);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } elseif (isset($_GET['social_links']) && $_GET['social_links'] === 'true') {
        // Update social_accounts table
        $facebook = $_POST['facebook'];
        $twitter = $_POST['twitter'];
        $instagram = $_POST['instagram'];
        $linkedin = $_POST['linkedin'];

        $sql = "UPDATE social_accounts SET facebook = :facebook, twitter = :twitter, instagram = :instagram, linkedin = :linkedin WHERE social_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':facebook', $facebook);
        $stmt->bindParam(':twitter', $twitter);
        $stmt->bindParam(':instagram', $instagram);
        $stmt->bindParam(':linkedin', $linkedin);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } elseif (isset($_GET['qr_code']) && $_GET['qr_code'] === 'true') {
        // Update qr_code table
        $donation_text = $_POST['donation_text'];
        $account_name = $_POST['account_name'];
        $account_no = $_POST['account_no'];
        $qr_code_img = $_FILES['qr_code_img']['name'];

        $sql = "UPDATE qr_code SET donation_text = :donation_text, account_name = :account_name, account_no = :account_no WHERE qr_code_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':donation_text', $donation_text);
        $stmt->bindParam(':account_name', $account_name);
        $stmt->bindParam(':account_no', $account_no);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Handle qr_code picture upload
        if (isset($_FILES['qr_code_img']['name']) && !empty($_FILES['qr_code_img']['name'])) {
            $qr_name = $_FILES['qr_code_img']['name'];
            $qr_nameTemp = $_FILES['qr_code_img']['tmp_name'];
            $poster_destination = "img/" . $qr_name;
            move_uploaded_file($qr_nameTemp, $poster_destination);
        } else {
            // No new image uploaded, check if the remove button is clicked
            if (isset($_POST['defaultImage']) && $_POST['defaultImage'] !== '') {
                // Use a default photo if no image is uploaded
                $qr_code_img = 'qr_default.png';
            } else {
                // Keep the existing image
                $qr_code_img = $qr_code['qr_code_img'];
            }
        }

        // Update owner picture in the branding table
        $sql = "UPDATE qr_code SET qr_code_img = :qr_code_img WHERE qr_code_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':qr_code_img', $qr_code_img);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } elseif (isset($_GET['owners']) && $_GET['owners'] === 'true') {

        // Update owner name and message
        $brandName = $_POST['brand_name'];
        $brandMessage = $_POST['ownerMessage'];
        $logoPic = $_FILES['brand_logo']['name'];

        $sql = "UPDATE branding SET brand_name = :ownerName, brand_info = :ownerMessage WHERE brand_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ownerName', $brandName);
        $stmt->bindParam(':ownerMessage', $brandMessage);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Handle owner picture upload
        if (isset($_FILES['brand_logo']['name']) && !empty($_FILES['brand_logo']['name'])) {
            $logoPicName = $_FILES['brand_logo']['name'];
            $logoPicTemp = $_FILES['brand_logo']['tmp_name'];
            $poster_destination = "img/" .  $logoPicName;
            move_uploaded_file($logoPicTemp, $poster_destination);
        } else {
            // No new avatar uploaded, check if the remove button is clicked
            if (isset($_POST['defaultImage']) && $_POST['defaultImage'] !== '') {
                // Use a default photo if no image is uploaded
                $logoPic = 'user_default.png';
            } else {
                // Keep the existing avatar
                $logoPic = $brand[0]['brand_logo'];
            }
        }
        // Update owner picture in the branding table
        $sql = "UPDATE branding SET brand_logo = :logoPic WHERE brand_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':logoPic', $logoPic);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    // Redirect back to the page after updating the tables
    header("Location: edited-entry.php?edited=true&support=true");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Support</title>
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
    <link rel="stylesheet" href="css/options.css">
    <link rel="stylesheet" href="css/edit_support.css">
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
            <a href='artwork_dashboard.php' class="logo">
                <?= $brandname; ?>
            </a>
            <!-- Search Form -->
            <form action="search_results.php" method="GET" class="search-box">
                <input type="search" name="search" id="search-input" placeholder="Search <?= $brand_name; ?>" value="<?php echo htmlspecialchars($input); ?>">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
            <!-- User -->
            <a href="admin_profile.php" class="user">
                <img src="images/<?php echo htmlspecialchars($artist['artist_pic']); ?>" loading="lazy" alt="<?= $artist['username']; ?>" class="user-img">
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
    <!-- Profile Section -->
    <section class="creator container" id="creator">
        <div class="creator-content">
            <?php if (isset($_GET['owners']) && $_GET['owners'] === 'true') { ?>
                <?php foreach ($brand as $brand) { ?>
                    <div class="creator-img">
                        <img id="previewImage" src="img/<?php echo $brand['brand_logo']; ?>" loading="lazy" alt="Preview Image">
                    </div>
                    <div class="profile-text">
                        <h2 id="artistName">
                            <?php
                            $title = $brand['brand_name'];
                            $words = explode(' ', $title);
                            $totalWords = count($words);
                            $halfWords = ceil($totalWords / 2);
                            $spannedWords = array_map(function ($index, $word) use ($halfWords) {
                                if ($index >= $halfWords) {
                                    return '<span class="color">' . $word . '</span>';
                                }
                                return $word;
                            }, array_keys($words), $words);

                            $spannedTitle = implode(' ', $spannedWords);
                            $spannedTitle = preg_replace('/(.*:)(.*)/i', '$1<span class="color">$2</span>', $spannedTitle);

                            echo $spannedTitle;
                            ?>
                        </h2>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if (isset($_GET['about']) && $_GET['about'] === 'true') { ?>
                <div class="creator-img">
                    <img id="previewImage" src="img/<?= $brand_logo; ?>" loading="lazy" alt="Preview Image">
                </div>
                <div class="profile-text">
                    <h2 id="BrandTitle"><?= $brand_name; ?></h2>
                </div>
            <?php } ?>
            <?php if (isset($_GET['qr_code']) && $_GET['qr_code'] === 'true') { ?>
                <div class="qr-img">
                    <img id="previewImage" src="img/<?php echo $qr_code['qr_code_img']; ?>" loading="lazy" alt="Preview Image">
                </div>
                <div class="profile-text">
                    <h2 id="artistName">
                        <?php
                        $actName = $qr_code['account_name'];
                        $last_word = substr($actName, strrpos($actName, ' ') + 1);
                        $spanned_actName = str_replace($last_word, '<span class="color">' . $last_word . '</span>', $actName);

                        echo $spanned_actName;
                        ?>
                    </h2>
                </div>
            <?php } ?>
            <?php if (isset($_GET['social_links']) && $_GET['social_links'] === 'true') { ?>
                <?php foreach ($brand as $brand) { ?>
                    <div class="creator-img">
                        <img id="previewImage" src="img/<?php echo $brand['brand_logo']; ?>" loading="lazy" alt="Preview Image">
                    </div>
                    <div class="profile-text">
                        <h2 id="BrandTitle">
                            <?php
                            $title = $brand['brand_name'];
                            $words = explode(' ', $title);
                            $totalWords = count($words);
                            $halfWords = ceil($totalWords / 2);
                            $spannedWords = array_map(function ($index, $word) use ($halfWords) {
                                if ($index >= $halfWords) {
                                    return '<span class="color">' . $word . '</span>';
                                }
                                return $word;
                            }, array_keys($words), $words);

                            $spannedTitle = implode(' ', $spannedWords);
                            $spannedTitle = preg_replace('/(.*:)(.*)/i', '$1<span class="color">$2</span>', $spannedTitle);

                            echo $spannedTitle;
                            ?>
                        </h2>
                    </div>
                <?php } ?>
            <?php } ?>
            <div>
                <br>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="profile-form feedback-form" id="feedback-form">
                <?php if (isset($_GET['owners']) && $_GET['owners'] === 'true') { ?>
                    <input type="hidden" id="defaultImage" name="defaultImage" value="">

                    <label>Brand Name</label>
                    <input type="text" id="name" name="brand_name" value="<?php echo $brand['brand_name']; ?>" placeholder="Owner name" required oninput="updateBrandName()">
                    <!-- <label class="note">Provide the name of the account associated with the <?= $brand_name; ?> owner.</label> -->

                    <label>Short Description</label>
                    <textarea name="ownerMessage" rows="10" style="resize: none;" placeholder="Owner message" required><?php echo $brand['brand_info']; ?></textarea>
                    <label class="note">Enter a brief description.</label>

                    <label for="logoPic">Logo</label>
                    <input type="file" id="poster" name="brand_logo" class="avatar">
                    <label class="note">Tip: Choose an image that represents the brand well.</label>

                    <input type="button" id="removePicBtn" name="removeAvatar" onclick="removeProfilePicture()" class="send-btn" value="Remove Owner Picture">
                    <label for="removeAvatar" class="note">Note: Click this button to remove the current logo.</label>
                <?php } ?>
                <?php if (isset($_GET['about']) && $_GET['about'] === 'true') { ?>
                    <label>About</label>
                    <textarea name="about" rows="10" style="resize: none;" placeholder="About" required><?php echo $about['about']; ?></textarea>
                    <label class="note">Write a brief description about <?= $brand_name; ?>.</label>

                    <label>Disclaimer</label>
                    <textarea name="disclaimer" rows="10" style="resize: none;" placeholder="Disclaimer" required><?php echo $about['disclaimer']; ?></textarea>
                    <label class="note">Provide the disclaimer information for <?= $brand_name; ?>.</label>

                    <label>Terms of Use</label>
                    <textarea name="terms_of_use" rows="10" style="resize: none;" placeholder="Terms of use" required><?php echo $about['terms_of_use']; ?></textarea>
                    <label class="note">Specify the terms of use for <?= $brand_name; ?>.</label>

                    <label>Privacy Policy</label>
                    <textarea name="privacy_policy" rows="10" style="resize: none;" placeholder="Privacy policy" required><?php echo $about['privacy_policy']; ?></textarea>
                    <label class="note">Enter the privacy policy details for <?= $brand_name; ?>.</label>
                <?php } ?>
                <?php if (isset($_GET['social_links']) && $_GET['social_links'] === 'true') { ?>
                    <label>Facebook</label>
                    <input type="text" name="facebook" value="<?php echo $social_links['facebook']; ?>" placeholder="Facebook link" required>
                    <label class="note">Provide the link to the official Facebook account of the owner.</label>

                    <label>Twitter</label>
                    <input type="text" name="twitter" value="<?php echo $social_links['twitter']; ?>" placeholder="Twitter link" required>
                    <label class="note">Enter the link to the official Twitter account of the owner.</label>

                    <label>Instagram</label>
                    <input type="text" name="instagram" value="<?php echo $social_links['instagram']; ?>" placeholder="Instagram link" required>
                    <label class="note">Specify the link to the official Instagram profile of the owner.</label>

                    <label>LinkedIn</label>
                    <input type="text" name="linkedin" value="<?php echo $social_links['linkedin']; ?>" placeholder="LinkedIn link" required>
                    <label class="note">Provide the link to the official LinkedIn account of the owner.</label>
                <?php } ?>
                <?php if (isset($_GET['qr_code']) && $_GET['qr_code'] === 'true') { ?>
                    <input type="hidden" id="defaultImage" name="defaultImage" value="">

                    <label>Donation Text</label>
                    <textarea name="donation_text" rows="10" style="resize: none;" placeholder="Donation text" required><?php echo $qr_code['donation_text']; ?></textarea>
                    <label class="note">Enter the text donation message.</label>

                    <label>Account Name</label>
                    <input type="text" id="name" name="account_name" value="<?php echo $qr_code['account_name']; ?>" placeholder="Account name" required oninput="updateActorName()">
                    <label class="note">Provide the name of the account associated with the QR code.</label>

                    <label>Account No.</label>
                    <input type="text" name="account_no" value="<?php echo $qr_code['account_no']; ?>" placeholder="Account number" required>
                    <label class="note">Specify the account number associated with the QR code.</label>

                    <label for="qr_code_img">QR Code Image</label>
                    <input type="file" id="poster" name="qr_code_img" class="avatar">
                    <label class="note">Specify the image of the QR code.</label>

                    <input type="button" id="removePicBtn" name="removeAvatar" onclick="removeProfilePicture1()" class="send-btn" value="Remove QR Code">
                    <label for="removeAvatar" class="note">Note: Click this button to remove the current QR code.</label>
                <?php } ?>

                <input type="submit" id="submitButton" value="UPDATE ENTRY" name="insertEntry" class="send-btn">

            </form>

            <div>
                <?php if (isset($error)) : ?>
                    <br>
                    <p style="color: #1D9BF0; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>
            </div>
            <div class="logout" style="margin-bottom: 5rem !important;">
            </div>
        </div>
    </section>
    <script>
        function updateActorName() {
            var artistNameInput = document.getElementById("name");
            var artistName = document.getElementById("artistName");

            var inputText = artistNameInput.value.trim();
            var words = inputText.split(' ');

            // Check if there are more than one word
            if (words.length > 1) {
                var lastWord = words.pop();

                var spannedLastWord = '<span class="color">' + lastWord + '</span>';
                var spannedText = inputText.replace(/\b(?![.,])(?![^\s]+\.[^\s]+)\b(?:[a-zA-Z0-9+]*)(?<![.,])/g, function(match) {
                    return match === lastWord ? spannedLastWord : match;
                });

                artistName.innerHTML = inputText ? spannedText : "Actor Name";
            } else {
                artistName.textContent = inputText ? inputText : "Actor Name";
            }
        }
    </script>
     <script>
        function updateBrandName() {
            var artistNameInput = document.getElementById("name");
            var artistName = document.getElementById("artistName");

            var inputText = artistNameInput.value.trim();
            var words = inputText.split(' ');

            // Check if there are more than one word
            if (words.length > 1) {
                var lastWord = words.pop();

                var spannedLastWord = '<span class="color">' + lastWord + '</span>';
                var spannedText = inputText.replace(/\b(?![.,])(?![^\s]+\.[^\s]+)\b(?:[a-zA-Z0-9+]*)(?<![.,])/g, function(match) {
                    return match === lastWord ? spannedLastWord : match;
                });

                artistName.innerHTML = inputText ? spannedText : "Enter Title";
            } else {
                artistName.textContent = inputText ? inputText : "Enter Title";
            }
        }
    </script>
    <script>
        // Set the initial preview image
        var previewImage = document.getElementById('previewImage');
        var lastDisplayedPhoto = previewImage.src; // Store the last displayed photo

        // Update the image source when a new file is selected
        var editPicInput = document.getElementById('poster');
        editPicInput.addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                lastDisplayedPhoto = previewImage.src; // Update the last displayed photo
            };
            reader.readAsDataURL(file);
        });
        // Handle the case when the user removes the current selection
        editPicInput.addEventListener('input', function() {
            if (editPicInput.files.length === 0) {
                previewImage.src = lastDisplayedPhoto; // Reset to the last displayed photo
            }
        });
    </script>
    <script>
        function updateBrandTitle(checkbox) {
            var BrandTitle = document.getElementById("BrandTitle");
            BrandTitle.textContent = checkbox.nextElementSibling.textContent || "Enter Title";
        }
    </script>
    <script>
        function removeProfilePicture() {
            var previewImage = document.getElementById('previewImage');
            var editPicInput = document.getElementById('poster');
            var defaultImage = 'user_default.png';
            var defaultImageInput = document.getElementById('defaultImage');

            lastDisplayedPhoto = previewImage.src; // Update the last displayed photo
            previewImage.src = "img/" + defaultImage;
            editPicInput.value = ''; // Clear the file input
            defaultImageInput.value = defaultImage; // Update the hidden input field value
        }
    </script>
    <script>
        function removeProfilePicture1() {
            var previewImage = document.getElementById('previewImage');
            var editPicInput = document.getElementById('poster');
            var defaultImage = 'qr_default.png';
            var defaultImageInput = document.getElementById('defaultImage');

            lastDisplayedPhoto = previewImage.src; // Update the last displayed photo
            previewImage.src = "img/" + defaultImage;
            editPicInput.value = ''; // Clear the file input
            defaultImageInput.value = defaultImage; // Update the hidden input field value
        }
    </script>
    <script>
        function toggleExpand() {
            var expandButton = document.getElementById("expandButton");
            var hiddenCheckboxes = document.querySelectorAll(".hidden-checkbox");
            var hiddenLabels = document.querySelectorAll(".hidden-label");

            // Toggle the visibility of hidden checkboxes and labels
            hiddenCheckboxes.forEach(function(checkbox) {
                checkbox.classList.toggle("hidden");
            });

            hiddenLabels.forEach(function(label) {
                label.classList.toggle("hidden");
            });

            // Toggle the text of the expand button
            expandButton.value = expandButton.value === "Expand" ? "Collapse" : "Expand";
        }

        // Collapse the artists initially
        toggleExpand();
    </script>
</body>

</html>