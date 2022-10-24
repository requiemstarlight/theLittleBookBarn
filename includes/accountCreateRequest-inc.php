<?php

if(isset($_GET['email'])) {

    // connect to the database

    require 'db_connect.php';

    // create a variable of the user trying to reset password

    $userEmail = $_GET["email"];
 
    $selector = bin2hex(random_bytes(8));

    // create a token to authenticate user
    $token = random_bytes(32);

    $url = "www.thelittlebookbarn.com/includes/verifyAccount.php?selector=" . $selector . "&validator="
    . bin2hex($token). "&email=". $userEmail;

    // create an expiry date/ time for the token in seconds 
    // 1800 seconds = 30 minutes

    $expires = date("U") + 1800;

    // delete any existing tokens for this user

    $sql = "DELETE FROM pwdreset WHERE pwdResetEmail = '$userEmail'";

    if(!mysqli_query($conn, $sql)) {
        header("location: ../accountVerified.php?account=connectionError");
        exit();
    }

    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    // we are now ready to insert the data in the database

    $sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector,
    pwdResetToken, pwdResetExpires) VALUES ('$userEmail', '$selector', '$hashedToken', '$expires');";

    if(!mysqli_query($conn, $sql)) {
        header("location: ../accountVerified.php?account=connectionError");
        exit();
}


    mysqli_close($conn);

    // now that the information is in the database, we prepare the email

    $to = $userEmail;

    $subject = "Activate your account for The Little Book Barn";

    $message = "We received a request to set up your account on our site. The 
    link to activate your account is below. If you did not make this 
    request your can ignore this email. \r\n\r\n";

    $message .= "Here is your activation link: \r\n\r\n";
    $message .= $url;

    $headers = "From: thelittlebookbarn <replyTo: info@thelittlebookbarn.com >\r\n";
    $headers .= "Reply-To: info@thelittlebookbarn.com\r\n";
    $headers .= "Context-type: text/html\r\n";

    // send email

    mail($to, $subject, $message, $headers);

    // EXCHANGE THE TWO BELOW AFTER TESTING

    header("location: ../accountVerified.php?account=success");

    // header("location: verifyAccount.php?selector=" . $selector . "&validator=" . bin2hex($token) . "&email=" . $userEmail);

} else {
    header("location: ../index.php");
}