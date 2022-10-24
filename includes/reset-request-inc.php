<?php

if(isset($_POST['reset-request-submit'])) {

    // connect to the database

        require 'db_connect.php';

        // create a variable of the user trying to reset password

        $userEmail = $_POST["email"];

        // check if the email exists in the users table

        $sql = "SELECT * FROM users WHERE email = ?";

        $stmt = mysqli_stmt_init($conn);

        if(mysqli_stmt_prepare($stmt, $sql)) {

                mysqli_stmt_bind_param($stmt, "s", $userEmail);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);

                $row = mysqli_fetch_assoc($result);

                if($row) {
                        $selector = bin2hex(random_bytes(8));

                        // create a token to authenticate user
                        $token = random_bytes(32);

                        $url = "www.thelittlebookbarn.com/createNewPassword.php?selector=" . $selector . "&validator=" . bin2hex($token);

                        // create an expiry date/ time for the token in seconds 
                        // 1800 seconds = 30 minutes

                        $expires = date("U") + 1800;

                        $sql = "DELETE FROM pwdreset WHERE pwdResetEmail = ?";

                        $stmt = mysqli_stmt_init($conn);

                        if(mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_stmt_bind_param($stmt, "s", $userEmail);
                            mysqli_stmt_execute($stmt);

                            // password to be hashed for safe storage

                            $hashedToken = password_hash($token, PASSWORD_DEFAULT);

                            // we are now ready to insert the data in the database

                            $sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector,
                            pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";

                            $stmt = mysqli_stmt_init($conn);

                            if(mysqli_stmt_prepare($stmt, $sql)) { 
                                mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
                                mysqli_stmt_execute($stmt);

                                mysqli_stmt_close($stmt);
                                mysqli_close($conn);

                                // now that the information is in the database, we prepare the email

                                $to = $userEmail;

                                $subject = "Reset your password for The Little Book Barn";

                                $message = "We received a request to reset your password. The 
                                link to reset your password is below. If you did not make this 
                                request your can ignore this email. \r\n\r\n";

                                $message .= "Here is your password reset link: \r\n\r\n";
                                $message .= $url;

                                $headers = "From: thelittlebookbarn <replyTo: info@thelittlebookbarn.com>\r\n";
                                $headers .= "Reply-To: info@thelittlebookbarn.com\r\n";
                                $headers .= "Context-type: text/html\r\n";

                                // send email

                                mail($to, $subject, $message, $headers);

                                // EXCHANGE THE TWO BELOW AFTER TESTING

                                header("location: ../reset-password.php?reset=success");

                                // header("location: ../createNewPassword.php?selector=" . $selector . "&validator=" . bin2hex($token));


                            } else {
                                header("location: ../reset-password.php?reset=connectionError");
                                exit();
                            }

                        } else {
                            header("location: ../reset-password.php?reset=connectionError");
                            exit();
                        }
                    } else {
                        header("location: ../reset-password.php?reset=userdoesnotexist");
                        exit();
                    }

        } else {
                header("location: ../index.php");
        }
    }



