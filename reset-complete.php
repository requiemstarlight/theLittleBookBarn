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
        <!-- check for a GET request in the url -->
        <?php 
        if(!isset($_GET["newPwd"])) {
            header("location: index.php");
        } else if ($_GET['newPwd'] === "updated") {
            echo "<h1 class='form__title form__title--success'>
            Reset Complete</h1>";
            echo "<p class='reset-paragraph'>
                You have successfully updated your password. 
                You can now login.</p>";
            echo "<p class='form__text'>
                <a class='form__link' href='index.php' id='linkLogin'>
                    Login
                </a>
            </p>";
        }
        ?>

        </form>
    </div>
    
</body> 
</html>