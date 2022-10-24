<?php

// connect to the database

$host = 'localhost';
$user = 'dave';
$password = 'pablohoney';
$database = 'book_reviews';




// $host = 'localhost';
// $user = 'u203742733_dave_finn2004';
// $password = '9Ti3dbeb';
// $database = 'u203742733_book_reviews';

$conn = mysqli_connect($host, $user, $password, $database);

// check connection
if(!$conn) {
    echo 'Connection error ' . mysqli_connect_error();
}

