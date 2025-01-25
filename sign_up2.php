<?php
// Include connection
include("config.php");
include("branding.php");

include("social_accounts.php");
session_start();

// Initialize variables
$firstname = "";
$lastname = "";
$error = "";

// Check if the signup form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addUser'])) {
    // Retrieve the entered first name and last name
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    // Handle uploaded avatar
    if ($_FILES["uPic"]["error"] == 0) {
        $filename = $_FILES["uPic"]["name"];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = $_SESSION['username'] . "." . $extension;
        $tempname = $_FILES["uPic"]["tmp_name"];
        $folder = "./images/" . $newFilename;

        // Move uploaded avatar to folder
        move_uploaded_file($tempname, $folder);
    } else {
        // Use a default photo if no image is uploaded
        $newFilename = "user_default.png";
    }

    // Update user's first name, last name, and avatar in the database
    $sql = "UPDATE artists SET firstname = :firstname, lastname = :lastname, artist_pic = :pic WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':pic' => $newFilename,
        ':username' => $_SESSION['username']
    ]);

    // Redirect to the login page
    header("Location: success_page.php?success=true&registered=true");
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['addUser'])) {
    header("Location: sign_up.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page 2</title>
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
            <span class="user" style="visibility: hidden; opacity:0"><img src="img/<?= $brand_logo; ?>" loading="lazy" alt="<?= $brand_name; ?>" class="user-img">
            </span>
        </div>
    </header>
    <section class="signup1 container" id="signup1">
        <!-- Signup Form -->
        <section class="signup container" id="signup">
            <!-- Heading -->
            <div class="heading-2">
                <h2 class="heading-title-2">Sign Up</h2>
            </div>
            <!-- Signup Form -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="signup-form" id="signup-form">
                <input type="text" id="firstname" name="firstname" placeholder="Enter First Name" class="name" required>
                <input type="text" id="lastname" name="lastname" placeholder="Enter Last Name" class="name">
                <input type="file" id="uPic" name="uPic" class="avatar">
                <input type="submit" value="Complete Registration" name="addUser" class="send-btn">
            </form>
        </section>
        <!-- Signup -->
        <section class="signupalt container" id="signupalt">
            <div>
                <p>
                    Already have an account?
                </p>
            </div>
            <!-- signupalt Links -->
            <div class="signupalt-links">
                <a href="login.php">Log In</a>
                <a href="forgot_password.php">Forgot Password</a>
                <a href="support.php">Support</a>
            </div>
            <!-- Copyright -->
            <p>
                <a href="more.php" style="text-decoration: none; color: inherit;"><?= $brand_name; ?> &#169; 2023</a>
            </p>
        </section>
    </section>
</body>

</html>