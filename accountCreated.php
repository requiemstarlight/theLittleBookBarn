<?php

require_once 'includes/startSession.php';

?>

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
    <link type="text/css" rel="stylesheet" href="stylesheets/styles2.css" /> 
  </head>
  <body class="body">
    <section class="menu-success">
        <!-- title -->
        <div class="title">
          <h2>
            <i>Account Pending</i>
          </h2>
            <div class="underline"></div>
        </div>
        <!-- add an informative paragraph for the new user -->

        <div class="paragraph-container">
            <p>
                We have sent you an email. Click on the link to verify your new account.
                It will expire in 30 minutes.
            </p>
        </div>

    </section>

      <div class>
          <p class="form-text">
              <a class="form-link" href="index.php" id="linkReviews">
              Teacher approved? Login
              </a>
          </p>
      </div>
</body>
</html>

    

