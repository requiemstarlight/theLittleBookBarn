<?php

$schoolClass = $_SESSION['schoolClass'];

// write query for all the book reviews

$sql = "SELECT * FROM users 
    WHERE status = 'teacher'
    && emailVerified = 1";

// make query and get result

$result = mysqli_query($conn, $sql);


// fetch the resulting rows as an array

$teachers = mysqli_fetch_all($result, MYSQLI_ASSOC);

// free the result from memory and close the connection

mysqli_free_result($result);
