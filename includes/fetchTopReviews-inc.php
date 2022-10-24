<?php

$sql = "SELECT * FROM review 
        WHERE review_of_month = 1 
        AND date_created BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00')
        AND DATE_FORMAT((NOW()), '%Y-%m-01 00:00:00');";

$reviewsOfMonth = [];

// make query and get result

$result = mysqli_query($conn, $sql);

if(!$result) {
    header("location: ../books.php");
    } else {
    // fetch the resulting rows as an array

    $reviewsOfMonth = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // free the result from memory and close the connection

    mysqli_free_result($result);

    }





