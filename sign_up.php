<?php
session_start();
// Include connection
include("config.php");
include("branding.php");

include("social_accounts.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);


$error = "";
$username = "";
$password = "";
$email = "";

// Add User
if (isset($_POST['addUser'])) {
    // Set up user data
    $username = $_POST['uname'];
    $password = $_POST['pswd'];
    $email = $_POST['email'];
    $newFilename = "user_default.png";

    // Check if username or email is already taken
    $query = "SELECT * FROM artists WHERE username = :uname OR emailaddress = :email";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':uname' => $username,
        ':email' => $email,
    ]);
    $result = $stmt->fetchAll();

    $isUsernameTaken = false;
    $isEmailTaken = false;

    foreach ($result as $row) {
        if ($row['username'] == $username) {
            $isUsernameTaken = true;
        }
        if ($row['emailaddress'] == $email) {
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
        // Username and email are not taken, proceed with adding the user
        $sql = "INSERT INTO artists (username, password, emailaddress, artist_pic) VALUES (:uname, :pwd, :email, :artist_pic)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':uname' => $username,
            ':pwd' => $password,
            ':email' => $email,
            ':artist_pic' =>  $newFilename,
        ]);

        $sql = "SELECT * FROM artists WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $artist = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['username'] = $artist['username'];

        header("Location: sign_up2.php");
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
    <title>Sign Up Page</title>
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
                <h2 class="heading-title-2">signup</h2>
            </div>
            <!-- Signup Form -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="signup-form" id="signup-form">
                <input type="text" id="uname" name="uname" placeholder="Enter Username" class="name" required>
                <input type="email" id="email" name="email" placeholder="Enter Email Address" class="email" required>
                <input type="password" id="pswd" name="pswd" placeholder="Enter Password" class="password" required>
                <input type="submit" value="Create Account" name="addUser" class="send-btn">
            </form>
        </section>
        <!-- Signup -->
        <div>
            <?php if (isset($error)) : ?>
                <p style="color: #1D9BF0; text-align: center">
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>
        </div>

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