<?php

// write query for all the book reviews

$sql = 'SELECT * FROM review ORDER BY date_created DESC';

// make query and get result

$result = mysqli_query($conn, $sql);


// fetch the resulting rows as an array

$books = mysqli_fetch_all($result, MYSQLI_ASSOC);

// free the result from memory and close the connection

mysqli_free_result($result);


