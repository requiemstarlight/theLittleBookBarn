<?php

    if(isset($_POST['reset-password-submit'])) {

        $selector = $_POST['selector'];
        $validator = $_POST['validator'];
        $newPassword = $_POST['password'];
        $passwordRepeat = $_POST['repeatPassword'];


        if(empty($newPassword) || empty($passwordRepeat)) {
            header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=empty");
            exit();
        } else if ($newPassword !== $passwordRepeat) {
            header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=mismatch");
            exit();
        }

        // we are now ready to check if the token is valid

        // start by finding the current date

        $currentDate = date("U");

        require 'db_connect.php';

        // we will now try to find data relating to the selector in the database

        $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector = ?
        AND pwdResetExpires >= $currentDate";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=stmterror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $selector);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if(!$row = mysqli_fetch_assoc($result)) {
                header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=fetcherror");
                exit();
            } else {

                // convert our validator to binary

                $tokenBin = hex2bin($validator);

                // check is if our tokens match
                $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);
                
                if($tokenCheck === false) {
                    header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=tokenerror");
                    exit();
                } else if ($tokenCheck === true)  {

                    $tokenEmail = $row['pwdResetEmail'];

                    $sql = "SELECT * FROM users WHERE email = ?";

                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                        header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=stmterror");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);

                        if(!$row = mysqli_fetch_assoc($result)) {
                            header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=fetcherror");
                            exit();
                        } else {
                            // we now want to insert the new password in the users database
                            $sql = "UPDATE users SET password = ? WHERE email = ?";
                                
                            $stmt = mysqli_stmt_init($conn);

                            if(!mysqli_stmt_prepare($stmt, $sql)) {
                                header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=stmterror");
                                exit();
                            } else {

                                // first hashed the new password
                                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                                mysqli_stmt_bind_param($stmt, "ss", $hashedNewPassword, $tokenEmail);
                                mysqli_stmt_execute($stmt);

                                // finally, we must delete the token from the database

                                $sql = "DELETE FROM pwdreset WHERE pwdResetEmail = ?";
                                    
                                $stmt = mysqli_stmt_init($conn);

                                if(!mysqli_stmt_prepare($stmt, $sql)) {
                                    header("location: ../createNewPassword.php?selector=$selector&validator=$validator&newPwd=stmterror");
                                    exit();
                                } else {
                                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                    mysqli_stmt_execute($stmt);

                                    header("location: ../reset-complete.php?newPwd=updated");
                                    exit();
                                }
                            }                            
                        }
                    }
                }
            }
        }
    } else {
        header("location: ../index.php");
    }




