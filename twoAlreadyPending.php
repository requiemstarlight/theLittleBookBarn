<?php

require_once 'includes/startSession.php';

if(!isset($_SESSION['id'])) {
  header("location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- auto logout after 10 minutes of inactivity --> 
    <meta http-equiv="refresh" content="600;url=includes/logout-inc.php" />
    <meta name="description" content="This website allows school children to share their book reviews.
     It is a reading resource for primary/ elementary schools.">
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
            <i>Oops...</i>
          </h2>
            <div class="underline"></div>
        </div>
        <!-- add an informative paragraph for the pupils -->

        <div class="paragraph-container">
            <p>
                Unfortunately, you already have two reviews pending approval.
                Please wait for your teacher to approve one of your reviews.
            </p>
        </div>

    </section>

      <div class>
            <p class="form-text">
                <a class="form-link" href="books.php" id="linkReviews">
              Return to Reviews
                </a>
            </p>
            <p class='form-text'>
                <a class='form-link' href='editDeletePupil.php' id='linkReviews'>
                Edit or Delete Pending Review
                </a>
            </p>

          <p class="form-text">
              <a class="form-link" href="includes/logout-inc.php" id="linkLogout">
              Logout
              </a>
          </p>
      </div>
</body>
</html>

    

