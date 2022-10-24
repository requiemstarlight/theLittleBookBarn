<?php

$schoolClass = $_SESSION['schoolClass'];

// write query for all the book reviews

$sql = "SELECT * FROM users 
    WHERE schoolClass = '$schoolClass' && status = 'pupil'
    && emailVerified = 1";

// make query and get result

$result = mysqli_query($conn, $sql);

$pupils = [];


// fetch the resulting rows as an array

if($result) {
    $pupils = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// free the result from memory and close the connection

mysqli_free_result($result);
