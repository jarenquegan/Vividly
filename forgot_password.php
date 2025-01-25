<?php
// Include connection
include("config.php");
include("branding.php");

include("social_accounts.php");
session_start();

// Initialize variables
$error = "";
$success = "";

// Check if the password reset form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the entered email address and new password
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the new password and confirm password match
    if ($newPassword === $confirmPassword) {
        // Check if the user exists in the database
        $sql = "SELECT * FROM artists WHERE emailaddress = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);
        $artist = $stmt->fetch();

        if ($artist) {
            // Update the user's password in the database
            $updateSql = "UPDATE artists SET password = :password WHERE emailaddress = :email";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->execute([
                ':password' => $newPassword,
                ':email' => $email
            ]);

            // Set the success message
            $success = "Password reset successfully.<br>Redirecting to the login page. Please wait...";

            // Redirect to login page after 2 seconds
            echo "<script>setTimeout(function() {
                window.location.href = 'login.php';
            }, 2000);</script>";
        } else {
            // User doesn't exist, display an error message
            $error = "User does not exist.";
        }
    } else {
        // Passwords don't match, display an error message
        $error = "Passwords do not match.";
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
    <div class="loader" style="z-index: 99999999999999;"></div></div>
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
                <h2 class="heading-title-2">Password</h2>
            </div>
            <!-- Signup Form -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="signup-form" id="signup-form">
                <input type="email" name="email" placeholder="Email address" class="email" required>
                <input type="password" name="new_password" placeholder="New password" class="password" required>
                <input type="password" name="confirm_password" placeholder="Confirm password" class="password" required>

                <input type="submit" value="Reset" class="send-btn">
            </form>
        </section>
        <div>
            <?php if (isset($error)) {
            ?>
                <p style="color: #1D9BF0; text-align: center;">
                    <?php echo $error; ?>
                </p>
            <?php
            }
            if (isset($success)) {
            ?>
                <p style="text-align: center;">
                    <?php echo $success; ?>
                </p>
            <?php
            } ?>
        </div>
        <!-- Signup -->
        <section class="signupalt container" id="signupalt">
            <div>
                <p>
                    Account Problem?
                </p>
            </div>

            <!-- signupalt Links -->
            <div class="signupalt-links">
                <a href="login.php">Log In</a>
                <a href="sign_up.php">Sign Up</a>
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