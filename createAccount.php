<?php

require_once 'includes/startSession.php';

include('includes/db_connect.php');

$username = $email = $name = $surname = $password = $confirmPassword = '';
$schoolClass = "Choose a Class";
$status = "Choose a Status";


$errors = array('connection'=>'', 'username'=>'', 'email'=>'', 'name'=>'',
                'surname'=>'', 'schoolClass'=>'', 'status'=>'',
                'password'=>'', 'confirmPassword'=>'');

        if(isset($_POST['submit'])) {

        // check POST items

        if(!isset($_POST['schoolClass']) || $_POST['schoolClass'] === "Choose a Class") {
            $errors['schoolClass'] = "You must select a class <br />";
        } else {
            $schoolClass = $_POST['schoolClass'];
        }

        if(!isset($_POST['status']) || $_POST['status'] === "Choose a Status") {
            $errors['status'] = "You must select a status <br />";
        } else {
            $status = $_POST['status'];
        }

          // check username
        $username = $_POST['username'];
        if(strlen($username) < 6 || strlen($username) > 12 ) {
            $errors['username'] = "Username must be between 6 and 12 characters <br />";
        } else if (preg_match("/\s/", $username)) {
            $errors['username'] = "Username must be contain no spaces <br />";
        }


        // check email
        $email = $_POST['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address. <br />";
        } else {
            if(strpos($email, "stpauls") === false) {
                $errors['email'] = "You must use a 'stpauls.br' email address <br />";
            }
        }
                
        // check name
        $name = $_POST['name'];
        if(strlen($name) > 25) {
            $errors['name'] = "Name should be a maximum of 25 characters <br />";
        } else {
            if (!preg_match('/^[A-Za-z0-9-]+$/D', $name)) {
                $errors['name'] = "Name must be letters and hyphens only <br />";
        }
    }

        // check surname
        $surname = $_POST['surname'];
        if(strlen($surname) > 25) {
            $errors['surname'] = "Surname should be a maximum of 25 characters <br />";
        } else {
            if (!preg_match('/^[A-Za-z0-9-]+$/D', $surname)) {
                $errors['surname'] = "Surname must be letters and hypens only <br />";
        }
    }

        // check password
        $password = $_POST['password'];
        if(strlen($password) < 6 || strlen($password) > 12 ) {
            $errors['password'] = "Password must be between 6 and 12 characters <br />";
        }

        // check confirm password
        $confirmPassword = $_POST['confirmPassword'];
        if($password != $confirmPassword) {
            $errors['confirmPassword'] = "Password fields do not match";
        }

        // finally, check that neither the email or username is currently in use

        if(!array_filter($errors)) {
        $sql = "SELECT username, email, emailVerified FROM users WHERE (username = ? OR email = ?)";

        // create a prepared statement for the ? placeholder above

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            $errors['connection'] = "There was a connection error <br />";
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if($user = mysqli_fetch_assoc($result)) {
    
                if($user['username'] == $username && $user['emailVerified'] === 1) {
                    $errors['username'] = "Username already in use";
                } else if ($user['email'] == $email && $user['emailVerified'] === 1) {
                    $errors['email'] = "Email already in use";
                } else if ($user['email'] == $email && $user['emailVerified'] === 0) {
                    // now delete the user if they exist unverified on the database

                    $sql = "DELETE FROM users WHERE email = ?;";

                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                        $errors['connection'] = "There was a connection error <br />";
                    } else {

                        mysqli_stmt_bind_param($stmt, "s", $email);
                        mysqli_stmt_execute($stmt);

                        mysqli_stmt_close($stmt);
                }
            }
        }
        }         
    }

        if(!array_filter($errors)) {

            $password = mysqli_real_escape_string($conn, $_POST['password']);

            $yearGroup = '';

            $schoolClass = $_POST['schoolClass'];
            $status = $_POST['status'];

            // check the class and assign the yeargroup


            if(in_array($schoolClass, ['ocean', 'rainforest', 'savannah', 'woodland'])) {
                $yearGroup = 'prep-1';
            } else if(in_array($schoolClass, ['cajueiro', 'coqueiro', 'ipe', 'manaca'])) {
                $yearGroup = 'prep-2';
            } else if(in_array($schoolClass, ['frida', 'niemayer', 'ohtake', 'tarsila'])) {
                $yearGroup = 'prep-3';
            } else if(in_array($schoolClass, ['curie', 'darwin', 'franklin', 'turing'])) {
                $yearGroup = 'prep-4';
            } else if(in_array($schoolClass, ['gandhi', 'malala', 'mandela', 'tiradentes'])) {
                $yearGroup = 'prep-5';
            } else if($schoolClass === 'teacher') {
                $yearGroup = 'teacher';
            }

            // hash password to protect the identity in the database

            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

            // create sql query

            $sql = "INSERT INTO users(username, email, name, surname,
            schoolClass, yearGroup, password, status)
            VALUES(?, ?, ?, ?, '$schoolClass', '$yearGroup', '$passwordHashed', '$status')";

            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql)) {
                $errors['connection'] = "There was a connection error <br />";
            } else {

                mysqli_stmt_bind_param($stmt, "ssss", $username ,$email, $name, $surname);
                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
                mysqli_close($conn);

                header("location: includes/accountCreateRequest-inc.php?email=$email");
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
<body>
<div class="container">
    <!-- create account id -->
    <form class="form" action="createAccount.php" method="POST">
        <h1 class="form__title">Create Account</h1>
        <div class="form__message form__message--error">
            <?php echo $errors['connection']; ?>
        </div>
        <!-- input for username --> 
        <div class="form__input-group">
            <input type="text" name="username" class="form__input" 
            value="<?php echo htmlspecialchars($username); ?>"
            autofocus placeholder="Username" autocomplete="off" required>
            <div class="form__input-error-message">
                <?php echo $errors['username']; ?>
            </div>
        </div>
        <!-- input for email--> 
        <div class="form__input-group">
            <input type="text" name="email" class="form__input" 
            value="<?php echo htmlspecialchars($email); ?>"
            autofocus placeholder="Email Address" autocomplete="off" required>
            <div class="form__input-error-message">
                <?php echo $errors['email']; ?>
            </div>
        </div>
        <!-- input for name --> 
        <div class="form__input-group">
            <input type="text" name="name" class="form__input" 
            value="<?php echo htmlspecialchars($name); ?>"
            autofocus placeholder="First Name" autocomplete="off" required>
            <div class="form__input-error-message">
                <?php echo $errors['name']; ?>
            </div>
        </div>
        <!-- input for surname --> 
        <div class="form__input-group">
            <input type="text" name="surname" class="form__input" 
            value="<?php echo htmlspecialchars($surname); ?>"
            autofocus placeholder="Surname" autocomplete="off" required>
            <div class="form__input-error-message">
                <?php echo $errors['surname']; ?>
            </div>
        </div>
        <!-- input for school class -->
        <div class="form__input-group">
            <select class="form__input" type="text" name="schoolClass">
                <option value="<?php echo htmlspecialchars($schoolClass); ?>"
                selected hidden>
                    <?php echo htmlspecialchars($schoolClass); ?>
                </option>
                <option value="ocean">Ocean</option>
                <option value="rainforest">Rainforest</option>
                <option value="savannah">Savannah</option>
                <option value="woodland">Woodland</option>
                <option value="cajueiro">Cajueiro</option>
                <option value="coqueiro">Coqueiro</option>
                <option value="ipe">Ipe</option>
                <option value="manaca">Manaca</option>
                <option value="frida">Frida</option>
                <option value="niemayer">Niemayer</option>
                <option value="ohtake">Ohtake</option>
                <option value="tarsila">Tarsila</option>
                <option value="curie">Curie</option>
                <option value="darwin">Darwin</option>
                <option value="franklin">Franklin</option>
                <option value="turing">Turing</option>
                <option value="gandhi">Gandhi</option>
                <option value="malala">Malala</option>
                <option value="mandela">Mandela</option>
                <option value="tiradentes">Tiradentes</option>
                <option value="teacher">Teacher</option>
            </select>
            <div class="form__input-error-message">
                <?php echo $errors['schoolClass']; ?>
            </div>
        </div>
        <!-- user status -->
        <div class="form__input-group">
            <select class="form__input" type="text" name="status">
                <option value="<?php echo htmlspecialchars($status); ?>"
                selected hidden>
                    <?php echo htmlspecialchars($status); ?>
                </option>
                <option value="pupil">Pupil</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
            <div class="form__input-error-message">
                <?php echo $errors['status']; ?>
            </div>
        </div>
            <!-- password --> 
        <div class="form__input-group">
            <input type="password" name="password" class="form__input" 
            value="<?php echo htmlspecialchars($password); ?>"
            autofocus placeholder="Password" autocomplete="off" required>
            <div class="form__input-error-message">
                <?php echo $errors['password']; ?>
            </div>
        </div>
        <!-- confirm password --> 
        <div class="form__input-group">
            <input type="password" name="confirmPassword" class="form__input" 
            value="<?php echo htmlspecialchars($confirmPassword); ?>"
            autofocus placeholder="Confirm Password"
                autocomplete="off" required>
            <div class="form__input-error-message">
                <?php echo $errors['confirmPassword']; ?>
            </div>
        </div>
        <div>
            <button class="form__button" type="submit"
            name='submit' value='submit'>Continue</button>
            <button type="reset" name="reset" class="form__button" id="reset-btn">
                Reset form
            </button>
        </div>

    <p class="form__text">
        <a class="form__link" href="index.php" id="linkLogin">
            Already have an account? Sign in
        </a>
    </p>
    </form>
</div>
    
</body> 
</html>