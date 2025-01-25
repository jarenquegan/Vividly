<?php
// Include connection
include("config.php");
include("branding.php");

include("social_accounts.php");

session_start();

if ((isset($_SESSION['username']) || isset($_SESSION['emailaddress'])) && isset($_SESSION['password'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['username']) && $_GET['password']) {
    $username = $_GET['username'];
    $password = $_GET['password'];

    // Query the database to check if the user exists
    $sql = "SELECT * FROM artists WHERE (username = :username) AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($artist) {
        // Set session variables
        session_regenerate_id(true);
        $_SESSION['artist_id'] = $artist['artist_id'];
        $_SESSION['username'] = $artist['username'];
        $_SESSION['password'] = $password;
        $_SESSION['artist_pic'] = $artist['artist_pic'];
        $_SESSION['emailaddress'] = $artist['emailaddress'];
        $_SESSION['firstname'] = $artist['firstname'];
        $_SESSION['lastname'] = $artist['lastname'];
        $_SESSION['bio'] = $artist['bio'];

        header("Location: index.php");
        exit;
    }
}

// Initialize variables
$username = "";
$emailaddress = "";
$usernameOrEmail = "";
$password = "";
$error = "";

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the entered username or email address and password
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Query the database to check if the user exists and is not banned
    $sql = "SELECT * FROM artists WHERE (username = :username OR emailaddress = :emailaddress) AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $usernameOrEmail);
    $stmt->bindParam(':emailaddress', $usernameOrEmail);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($artist) {
        if ($artist['is_banned'] == 0) {
            // Set session variables
            session_regenerate_id(true);
            $_SESSION['artist_id'] = $artist['artist_id'];
            $_SESSION['username'] = $artist['username'];
            $_SESSION['password'] = $password;
            $_SESSION['artist_pic'] = $artist['artist_pic'];
            $_SESSION['emailaddress'] = $artist['emailaddress'];
            $_SESSION['firstname'] = $artist['firstname'];
            $_SESSION['lastname'] = $artist['lastname'];
            $_SESSION['bio'] = $artist['bio'];

            if ($artist['acct_type'] === 'Admin') {
                header("Location: whereTo.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            // User is banned, display error message
            $error = "Account banned. Contact the admin at queganjaren@gmail.com.";
        }
    } else {
        // User not found, display error message
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Link To CSS -->
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/search_results.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/artist_search_results.css">
    <link rel="stylesheet" href="css/span.css">
    <!-- Fav Icon -->
    <link rel="shortcut icon" href="img/<?= $brand_logo; ?>" type="image/x-icon">
    <!-- Box Icons -->
    <link href='assets/boxicons/css/boxicons.min.css' rel='stylesheet'>
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
    <section class="login1 container" id="login1">
        <!-- Login Form -->
        <section class="login container" id="login">
            <!-- Heading -->
            <div class="heading-2">
                <h2 class="heading-title-2">LOGIN</h2>
            </div>
            <!-- Login Form -->
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login-form" id="login-form">
                <input type="text" id="username_or_email" name="username_or_email" value="<?php echo $usernameOrEmail; ?>" placeholder="Username or Email address" class="email" required>
                <input type="password" id="password" name="password" placeholder="Password" class="password" required>
                <input type="submit" value="CONTINUE" class="send-btn">
            </form>

        </section>
        <div style="color: #1D9BF0;">
            <?php echo $error; ?>
        </div>
        <!-- Login -->
        <section class="loginalt container" id="loginalt">
            <div>
                <p>
                    Account Problem?
                </p>
            </div>

            <!-- Loginalt Links -->
            <div class="loginalt-links">
                <a href="sign_up.php">Sign Up</a>
                <a href="forgot_password.php">Forgot Password</a>
                <a href="support.php">Support</a>
            </div>
            <!-- Copyright -->
            <p>
                <a href="more.php" style="text-decoration: none; color: inherit;"><?= $brand_name; ?> &#169; 2023</a>
            </p>
        </section>
    </section>
    <!-- Email Js Link -->
</body>

</html>