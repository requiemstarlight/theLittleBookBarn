<?php

include('includes/db_connect.php');
include('includes/db_fetch_books.php');

// write query for all the book reviews

$sql = "SELECT * FROM review
        WHERE status = 'pupil' ORDER BY date_created DESC";

// make query and get result

$result = mysqli_query($conn, $sql);

$books = [];

if($result) {
        // fetch the resulting rows as an array

        $books = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // free the result from memory and close the connection

        mysqli_free_result($result);
}

$date = new DateTime;

$thisMonthBooks = [];

for ($x = 0; $x <= sizeof($books) - 1; $x++) {
        $newDate = DateTime::createFromFormat('Y-m-d H:i:s', $books[$x]['date_created']);
        $newDate->format('m Y');

        if($date->format('m Y') === $newDate->format('m Y')) {
                array_push($thisMonthBooks, $books[$x]);
        }
}
