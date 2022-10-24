<?php

if(isset($_GET['selector']) && isset($_GET['validator']) && isset($_GET['email'])) {


        $selector = $_GET['selector'];
        $validator = $_GET['validator'];
        $email = $_GET['email'];

        // we are now ready to check if the token is valid

        // start by finding the current date

        $currentDate = date("U");

        require 'db_connect.php';

        // we will now try to find data relating to the selector in the database

        $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector = ?
        AND pwdResetExpires >= '$currentDate';";

        $stmt = mysqli_stmt_init($conn);

        if(mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $selector);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);

                $row = mysqli_fetch_assoc($result);

                if($row) {
                        // convert our validator to binary
                        $tokenBin = hex2bin($validator);

                        // check is if our tokens match
                        $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);

                        if($tokenCheck === false) {
                                header("location: ../accountVerified.php?selector=$selector&validator=$validator&account=error3");
                                exit();
                        } else if ($tokenCheck === true)  {

                            $tokenEmail = $row['pwdResetEmail'];

                            $sql = "SELECT * FROM users WHERE email = ?";

                            $stmt = mysqli_stmt_init($conn);

                            if(mysqli_stmt_prepare($stmt, $sql)) {
                                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                    mysqli_stmt_execute($stmt);

                                    $result = mysqli_stmt_get_result($stmt);

                                    
                                if($row = mysqli_fetch_assoc($result)) { 
                                        // we now want to update the users table so that the user is emailVerified
                                        $sql = "UPDATE users
                                        SET emailVerified = 1
                                        WHERE email = ?;";
                                        
                                        $stmt = mysqli_stmt_init($conn);

                                        if(mysqli_stmt_prepare($stmt, $sql)) {

                                            mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                            mysqli_stmt_execute($stmt);

                                        // finally, we must delete the token from the database

                                        $sql = "DELETE FROM pwdreset WHERE pwdResetEmail = ?";
                                            
                                        $stmt = mysqli_stmt_init($conn);

                                        if(mysqli_stmt_prepare($stmt, $sql)) {
                                            
                                            mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                            mysqli_stmt_execute($stmt);



        }
    
    
    }}}}}
        header("location: ../accountVerified.php?account=updated");
        exit();
        
    } else {
                header("location: ../accountVerified.php?account=error;");
            }} else {
                header("location: ../index.php");
            };

        
















    