<?php

$sql = "SELECT * FROM review 
        WHERE date_created BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-%d 00:00:00')
AND DATE_FORMAT(LAST_DAY(NOW()), '%Y-%m-%d 23:59:59');";

// make query and get result

$result = mysqli_query($conn, $sql);


// fetch the resulting rows as an array

$recentlyReviewed = mysqli_fetch_all($result, MYSQLI_ASSOC);

// free the result from memory and close the connection

mysqli_free_result($result);