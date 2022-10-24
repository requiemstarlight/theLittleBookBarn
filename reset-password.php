<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="This website allows school children to share their book reviews.
    It is a reading resource for primary/ elementary schools.">
<!-- auto logout after 10 minutes of inactivity --> 
<meta http-equiv="refresh" content="600;url=includes/logout-inc.php" />
<title>The Little Book Barn</title>
<link rel="shortcut icon" type="image/png" href="templates/bookicon.png">
<!-- font-awesome -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
/>
<!-- styles -->
<link type="text/css" rel="stylesheet" href="stylesheets/styles3.css" /> 
</head>
<body class="index-body">
<div class="container">
    <?php
    if(isset($_GET['reset'])) {
        if($_GET['reset'] === "success") {
            echo "<h1 class='form__title form__title--success'>Link Sent</h1>";
            echo "<p class='reset-paragraph'>
                Check your email for a reset link, which will be valid for 30 minutes.
                This link may take a couple of minutes to arrive.</p>";

            echo  "<p class='form__text'>
                <a class='form__link' href='index.php' id='linkLogin'>
                    Login
                </a>
                </p>";
                exit();
        } else if($_GET['reset'] === "connectionError") {
            echo "<h1 class='form__title form__title--error'>Error</h1>";
            echo "<p class='reset-paragraph'>
                Unfortunately, there was an error. Click below to generate a new email.</p>";

            echo  "<p class='form__text'>
                <a class='form__link' href='reset-password.php' id='linkLogin'>
                    Generate a new Link
                </a>
                </p>";
                exit();
            }
}
    ?>
    <!-- create account id -->
    <form class="form" action="includes/reset-request-inc.php" method="POST">

        <!-- check for a GET request in the url -->
        <?php 
        if(!isset($_GET["reset"])) {
            echo "<h1 class='form__title'>Reset Password</h1>";
            echo "<p class='reset-paragraph'>
                An email will be sent to you, with instructions on how to 
                reset your password.</p>";
        } else if ($_GET['reset'] === "userdoesnotexist") {
            echo "<h1 class='form__title form__title--error'>Reset Error</h1>";
            echo "<p class='reset-paragraph'>
                The email your entered is not registered. Enter a registered email
                to try again.</p>";
        } else if ($_GET['reset'] === "error") {
            echo "<h1 class='form__title form__title--error'>Reset Error</h1>";
            echo "<p class='reset-paragraph'>
                There was an error resetting your password. Enter your email
                to try again.</p>";
        } else if ($_GET['reset'] === "success") {
            echo "<h1 class='form__title form__title--success'>Success</h1>";
            echo "<p class='reset-paragraph'>
                Check your email. We have sent you a link. 
                Click on it to create a new password.</p>";
        }
        ?>

        <!-- input for username --> 
        <div class="form__input-group">
            <input type="email" name="email" class="form__input" 
            autofocus placeholder="Enter your Email" autocomplete="off" required>
        </div>
        <button class="form__button" type="submit"
            name='reset-request-submit' value='submit'>Reset Password</button>
    </form>
    <p class="form__text">
        <a class="form__link" href="index.php" id="linkLogin">
            Remember your Password? Sign in
        </a>
    </p>

    </div>
    
</body> 
</html>