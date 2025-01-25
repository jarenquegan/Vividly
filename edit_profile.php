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

// Assign the profile data to variables
$username = $artist['username'];
$firstname = $artist['firstname'];
$middlename = $artist['middlename'];
$lastname = $artist['lastname'];
$suffix = $artist['suffix'];
$email = $artist['emailaddress'];
$bio = $artist['bio'];
$artist_pic = $artist['artist_pic'];
$password = $artist['password'];
$birthdate = $artist['birthdate'];
$address = $artist['address'];
$phone_number =  $artist['phone_number'];

$input = "";

// Check if the update profile form is submitted
if (isset($_POST['updateProfile'])) {
    // Get the submitted form data
    $newUsername = $_POST['username'];
    $newFirstname = $_POST['firstname'];
    $newMiddlename = $_POST['middlename'];
    $newLastname = $_POST['lastname'];
    $newSuffix = $_POST['suffix'];
    $newEmail = $_POST['email'];
    $newBirthdate = $_POST['birthdate'];
    $newAddress = $_POST['address'];
    $newPhoneNumber = $_POST['phone_number'];
    $newPassword = $_POST['password'];
    $newAvatar = $_POST['defaultImage'];
    $newBio = $_POST['bio'];

    // Convert empty string to null for consistent handling
    $newBirthdate = ($newBirthdate === '') ? null : $newBirthdate;

    // Check if a new avatar is uploaded
    if ($_FILES['uPic']['name']) {
        // Handle the uploaded avatar
        $newUserPic = $_FILES['uPic']['name'];
        $uploadPath = "images/" . basename($newUserPic);
        move_uploaded_file($_FILES['uPic']['tmp_name'], $uploadPath);
    } else {
        // No new avatar uploaded, check if the remove button is clicked
        if (isset($_POST['defaultImage']) && $_POST['defaultImage'] !== '') {
            // User wants to remove the avatar, use the default image
            $newUserPic = 'user_default.png';
        } else {
            // Keep the existing avatar
            $newUserPic = $artist_pic;
        }
    }

    // Check if username or email is already taken
    $query = "SELECT * FROM artists WHERE (username = :newUsername OR emailaddress = :newEmail) AND artist_id != :artist_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':newUsername' => $newUsername,
        ':newEmail' => $newEmail,
        ':artist_id' => $artist_id,
    ]);
    $result = $stmt->fetchAll();

    $isUsernameTaken = false;
    $isEmailTaken = false;

    foreach ($result as $row) {
        if ($row['username'] == $newUsername) {
            $isUsernameTaken = true;
        }
        if ($row['emailaddress'] == $newEmail) {
            $isEmailTaken = true;
        }
    }

    if ($isUsernameTaken && $isEmailTaken) {
        // Both username and email are taken
        $error = "Username and email are already taken. Please choose different ones.";
    } elseif ($isUsernameTaken) {
        // Username is taken
        $error = "Username is already taken. Please choose a different one.";
    } elseif ($isEmailTaken) {
        // Email is taken
        $error = "Email is already taken. Please choose a different one.";
    } else {
        // Update the user's profile in the database
        $sql = "UPDATE artists SET
                username = :username,
                firstname = :firstname,
                middlename = :middlename,
                lastname = :lastname,
                suffix = :suffix,
                emailaddress = :email,
                birthdate = :birthdate,
                address = :address,
                phone_number = :phone_number,
                password = :password,
                bio = :bio,
                artist_pic = :userPic
                WHERE artist_id = :artist_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':username' => $newUsername,
            ':firstname' => $newFirstname,
            ':middlename' => $newMiddlename,
            ':lastname' => $newLastname,
            ':suffix' => $newSuffix,
            ':email' => $newEmail,
            ':birthdate' => $newBirthdate,
            ':address' => $newAddress,
            ':phone_number' => $newPhoneNumber,
            ':password' => $newPassword,
            ':bio' => $newBio,
            ':userPic' => $newUserPic,
            ':artist_id' => $artist_id,
        ]);

        // Redirect to the edit page after updating
        header("Location: success_page.php?success=true&updateUser=true");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
    <!-- Profile Section -->
    <section class="creator container" id="creator">
        <div class="creator-content">
            <div class="creator-img">
                <img id="previewImage" src="images/<?php echo htmlspecialchars($artist_pic); ?>" loading="lazy" alt="Preview Image">
            </div>
            <div class="profile-text">
                <?php
                if (!empty($artist['firstname']) || !empty($artist['middlename']) || !empty($artist['lastname']) || !empty($artist['suffix'])) {
                    $middleInitial = !empty($artist['middlename']) ? strtoupper($artist['middlename'][0]) . "." : "";
                    echo "<h2 id=\"artistName\">" . $artist['firstname'] . " " . $middleInitial . " <span class='color'>" . $artist['lastname'] . "</span>";

                    if (!empty($artist['suffix'])) {
                        echo ", " . $artist['suffix'] . "</h2>";
                    }
                } else {
                    echo "<h2>" . $artist['username'] . "</h2>";
                }
                ?>

            </div>
            <div>
                <br>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" class="profile-form feedback-form" id="feedback-form">
                <input type="hidden" id="defaultImage" name="defaultImage" value="">

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" placeholder="Username" required>
                <label for="username" class="note">Recommendation: Choose a unique username.</label>

                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>" placeholder="First Name" required oninput="updateArtistFName()">

                <label for="middlename">Middle Name</label>
                <input type="text" id="middlename" name="middlename" value="<?php echo isset($middlename) ? htmlspecialchars($middlename) : ''; ?>" placeholder="Middle Name" oninput="updateArtistMName()">

                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>" placeholder="Last Name" oninput="updateArtistLName()">

                <label for="suffix">Suffix</label>
                <input type="text" id="suffix" name="suffix" value="<?php echo isset($suffix) ? htmlspecialchars($suffix) : ''; ?>" placeholder="Suffix" oninput="updateArtistSName()">

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" placeholder="Email Address" required>

                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($birthdate) ? htmlspecialchars($birthdate) : ''; ?>">

                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>" placeholder="Address">

                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo isset($phone_number) ? htmlspecialchars($phone_number) : ''; ?>" placeholder="Phone Number">

                <label for="editPswd">Password</label>
                <input type="password" id="editPswd" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>" placeholder="Password" required>
                <label for="editPswd" class="note">Note: Passwords should be strong and may include a combination of letters, numbers, and symbols.</label>

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" placeholder="Bio" rows="10" style="resize: none;"><?php echo isset($bio) ? htmlspecialchars($bio) : ''; ?></textarea>
                <label for="bio" class="note">Recommendation: Tell something about yourself.</label>

                <label for="uPic">Profile Picture</label>
                <input type="file" id="uPic" name="uPic" class="avatar">
                <label for="uPic" class="note">Tip: Choose an image that represents you well.</label>

                <input type="button" id="removePicBtn" name="removeAvatar" onclick="removeProfilePicture()" class="send-btn" value="Remove Picture">
                <label for="removeAvatar" class="note">Note: Click this button to remove your current profile picture.
                </label>

                <input type="submit" value="Update Profile" name="updateProfile" class="send-btn">
            </form>


            <div>
                <?php if (isset($error)) : ?>
                    <br>
                    <p style="color: #1D9BF0; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>
            </div>
            <div class="logout" style="margin-bottom: 5rem !important;">
                <a href="delete_confirmation.php"><i class='bx bx-trash'></i>
                    <span class="logout-text">DELETE ACCOUNT</span>
                </a>
            </div>
        </div>
    </section>
    <script>
        function updateArtistFName() {
            var artistNameInput = document.getElementById("firstname");
            var artistName = document.getElementById("artistName");

            var firstName = artistNameInput.value.trim();
            var middleName = document.getElementById("middlename").value.trim();
            var lastName = document.getElementById("lastname").value.trim();
            var suffix = document.getElementById("suffix").value.trim();

            var middleInitial = middleName ? middleName.charAt(0).toUpperCase() + "." : "";

            artistName.innerHTML = (firstName || "<?php echo $artist['firstname']; ?>") + " " + middleInitial + " <span class='color'>" + (lastName || "<?php echo $artist['lastname']; ?>") + "</span>";

            if (suffix) {
                artistName.innerHTML += ", " + suffix;
            }
        }

        function updateArtistMName() {
            var artistNameInput = document.getElementById("middlename");
            var artistName = document.getElementById("artistName");

            var firstName = document.getElementById("firstname").value.trim();
            var middleName = artistNameInput.value.trim();
            var lastName = document.getElementById("lastname").value.trim();
            var suffix = document.getElementById("suffix").value.trim();

            var middleInitial = middleName ? middleName.charAt(0).toUpperCase() + "." : "";

            artistName.innerHTML = (firstName || "<?php echo $artist['firstname']; ?>") + " " + middleInitial + " <span class='color'>" + (lastName || "<?php echo $artist['lastname']; ?>") + "</span>";

            if (suffix) {
                artistName.innerHTML += ", " + suffix;
            }
        }

        function updateArtistLName() {
            var artistNameInput = document.getElementById("lastname");
            var artistName = document.getElementById("artistName");

            var firstName = document.getElementById("firstname").value.trim();
            var middleName = document.getElementById("middlename").value.trim();
            var lastName = artistNameInput.value.trim();
            var suffix = document.getElementById("suffix").value.trim();

            var middleInitial = middleName ? middleName.charAt(0).toUpperCase() + "." : "";

            artistName.innerHTML = (firstName || "<?php echo $artist['firstname']; ?>") + " " + middleInitial + " <span class='color'>" + (lastName || "<?php echo $artist['lastname']; ?>") + "</span>";

            if (suffix) {
                artistName.innerHTML += ", " + suffix;
            }
        }

        function updateArtistSName() {
            var artistNameInput = document.getElementById("suffix");
            var artistName = document.getElementById("artistName");

            var firstName = document.getElementById("firstname").value.trim();
            var middleName = document.getElementById("middlename").value.trim();
            var lastName = document.getElementById("lastname").value.trim();
            var suffix = artistNameInput.value.trim();

            var middleInitial = middleName ? middleName.charAt(0).toUpperCase() + "." : "";

            artistName.innerHTML = (firstName || "<?php echo $artist['firstname']; ?>") + " " + middleInitial + " <span class='color'>" + (lastName || "<?php echo $artist['lastname']; ?>") + "</span>";

            if (suffix) {
                artistName.innerHTML += ", " + suffix;
            }
        }

        // Set the initial preview image
        var previewImage = document.getElementById('previewImage');
        var lastDisplayedPhoto = previewImage.src; // Store the last displayed photo

        // Update the image source when a new file is selected
        var editPicInput = document.getElementById('uPic');
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

        function removeProfilePicture() {
            var previewImage = document.getElementById('previewImage');
            var editPicInput = document.getElementById('uPic');
            var defaultImage = 'user_default.png';
            var defaultImageInput = document.getElementById('defaultImage');

            lastDisplayedPhoto = previewImage.src; // Update the last displayed photo
            previewImage.src = "images/" + defaultImage;
            editPicInput.value = ''; // Clear the file input
            defaultImageInput.value = defaultImage; // Update the hidden input field value
        }
    </script>

</body>

</html>