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

            $error = '';

            if(isset($_GET['newPwd'])) {
                if($_GET['newPwd'] === 'empty') {
                    $error = "Both password fields must be filled";
                } else if ($_GET['newPwd'] === 'mismatch') {
                    $error = "Both passwords must match";
                } else {
                    $error = "There was a connection problem";
                }
            }

            if(!isset($_GET['selector']) || !isset($_GET['validator'])) {
                echo "<h1 class='form__title form__title--error'>Reset Error</h1>";
                echo "<p class='reset-paragraph'>
                    We could not validate your password on this occasion.
                    Generate a new link by clicking below.</p>";
            } else {
                $selector = $_GET['selector'];
                $validator = $_GET['validator'];

                if(ctype_xdigit($selector) !== false && (ctype_xdigit($validator) !== false)) {
                    ?>
                    <h1 class="form__title">Choose Password</h1>
                    
                    <div class="form__input-error-bigger">
                        <?php echo $error; ?>
                    </div>

                    <!-- create form to reset password -->
                    <form class="form" action="includes/reset-password-inc.php" method="POST">
                        <!-- create hidden input to send selector and validator --> 
                        <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                        <input type="hidden" name="validator" value="<?php echo $validator; ?>">
                        <!-- input for password --> 
                        <div class="form__input-group">
                            <input type="password" name="password" class="form__input" 
                            autofocus placeholder="Choose a Password" autocomplete="off" required>
                        </div>
                        <!-- input for password repeat--> 
                        <div class="form__input-group">
                            <input type="password" name="repeatPassword" class="form__input" 
                            autofocus placeholder="Re-type Password" autocomplete="off" required>
                        </div>
                        <button class="form__button" type="submit"
                            name='reset-password-submit' value='submit'>Reset Password</button>

                    <?php
                }

            }      
        ?>

        <p class="form__text">
            <a class="form__link" href="reset-password.php" id="linkLogin">
                Generate a New Link
            </a>
        </p>
        <p class="form__text">
            <a class="form__link" href="index.php" id="linkLogin">
                Remember your Password? Sign in
            </a>
        </p>
        </form>
    </div>
    
</body> 
</html>