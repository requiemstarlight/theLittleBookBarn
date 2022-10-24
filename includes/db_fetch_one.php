<?php

// write query for one book reviews

$sql = "SELECT * FROM review WHERE id = '$id'";

// make query and get result

$result = mysqli_query($conn, $sql);


// fetch the resulting rows as an array

$books = mysqli_fetch_all($result, MYSQLI_ASSOC);

// free the result from memory and close the connection

mysqli_free_result($result);

mysqli_close($conn);
