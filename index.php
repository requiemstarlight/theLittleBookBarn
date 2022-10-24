<?php

include('includes/db_connect.php');

$username = $password =  '';

$errors = array('username'=>'', 'password' => '');

if(isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // check username/ email contains no spaces
    
    if(preg_match("/\s/", $username)) {
        $errors['username'] = 'Username/ email cannot contain spaces';
    } 
    if(preg_match("/\s/", $password)) {
        $errors['password'] = 'Password cannot contain spaces';
    } 

    if(!array_filter($errors)) {

        // write a query for all the users using a prepared statement

        $sql = "SELECT * FROM users
            WHERE username = ? OR email = ?";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: index.php?error=stmt");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $username, $username);
            mysqli_stmt_execute($stmt);
        

            $result = mysqli_stmt_get_result($stmt);

            if(!$row = mysqli_fetch_assoc($result)) {
                $errors['username'] = 'This username or email is not recognised';
            } else {

                // check if the encrypted password from the database matches, user's login attempt

                $encryptedPassword = $row['password']; 

                if (!password_verify($password, $encryptedPassword)) {
                        $errors['password'] = 'That is not the correct password';
                    } else if ($row['emailVerified'] === 0) {
                        $errors['username'] = 'You have not verified your email yet';
                    } else if ($row['teacherApproved'] === 0) {
                        $errors['username'] = 'Your teacher/ administrator has not yet approved your account';
                    } else {
                    
                    session_start();

                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['schoolClass'] = $row['schoolClass'];
                    $_SESSION['status'] = $row['status'];
                    $_SESSION['userID'] = $row['id'];
                    $_SESSION['yearGroup'] = $row['yearGroup'];


                    mysqli_free_result($result);

                    // close connection

                    mysqli_close($conn);

                    if($_SESSION['status'] === 'pupil') {
                        header('Location: books.php');
                    } else if($_SESSION['status'] === 'teacher') {
                        header('Location: myPupils.php');
                    } else if($_SESSION['status'] === 'admin') {
                        header('Location: myTeachers.php');
                    }
                }
            }
        } 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
<link type="text/css" rel="stylesheet" href="stylesheets/styles3.css" /> 
</head>
<body class="index-body">
<div class="container">
        <!-- login form -->
        <form class="form" action="index.php" method="POST">
            <h1 class="form__title">Login</h1>
            <div class="form__message form__message--error">
            </div>
            <div class="form__input-group">
                <input type="text" name="username" 
                value="<?php echo $username; ?>" 
                autocomplete="off" class="form__input" autofocus 
                placeholder="Username or email" required>
                <div class="form__input-error-message">
                    <?php echo $errors['username']; ?>
                </div>
            </div>
            <div class="form__input-group">
                <input type="password" name="password"
                value="<?php echo $password; ?>" 
                autocomplete="off" class="form__input" 
                autofocus placeholder="Password" required>
                <div class="form__input-error-message">
                    <?php echo $errors['password']; ?>
                </div>
            </div>

            <button class="form__button" type="submit" name="submit" value="submit">
                Continue</button>

            <p class="form__text">
            <a href="reset-password.php" class="form__link">Forgot your password?</a>
        </p>
        <p class="form__text">
            <a class="form__link" href="createAccount.php" id="linkCreateAccount">
                Don't have an account? Create account
            </a>
        </p>
        </form>
    
</body> 
</html>