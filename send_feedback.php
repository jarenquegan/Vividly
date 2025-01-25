<?php

include("config.php");
include("branding.php");
include("fetch_notif.php");
include("social_accounts.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$request_result = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Configure PHPMailer
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'queganjaren@gmail.com';
        $mail->Password = 'otdrcsuflqccusgb ';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Set sender and recipient
        $mail->isHTML(true);
        $mail->setFrom($email, $name);
        $mail->addAddress("queganjaren@gmail.com");

        $mail->addReplyTo($email, 'Feedback Recieved. Thank you for taking the time to provide your feedback!');


        // Set email subject and body
        $mail->Subject = $brand_name . ' | Feedback from ' . $name;
        $mail->Body = $message;

        // Send the email
        $mail->send();

        $request_result = 'Your email has been sent successfully. Rest assured that Jaren Quegan will respond to you promptly. Thank you for taking the time to provide your feedback!';
    } catch (Exception $e) {
        $request_result = 'Sorry, an error occurred. Please try again later.';
    }
} else {
    $request_result = 'Invalid request.';
}
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
    <script src="assets/jquery-3.6.4.js"></script>
    <script src="js/loader.js"></script>
    <script>
        // Redirect to previous page after 2 seconds
        setTimeout(function() {
            history.back();
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }, 2000);
    </script>
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
                <p>
                    <?php echo $request_result ?>
                </p>
                <p>
                    Redirecting to the previous page. Please wait...
                </p>
            </div>
        </div>
    </section>
</body>

</html>