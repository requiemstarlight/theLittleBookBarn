<?php

require_once 'startSession.php';

include('db_connect.php');

if(!isset($_SESSION['id']) || !isset($_GET['id'])) {
    header("location: ../index.php");
}

if($_SESSION['status'] !== 'teacher') {
    header("location: ../index.php");
}

$id = $_GET['id'];

$schoolClass = $_SESSION['schoolClass'];

// first set all to 0, in case a review had been selected

// write query to update

$sql = "UPDATE review SET review_of_month = 0 WHERE schoolClass = '$schoolClass'
    AND date_created BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00')
AND DATE_FORMAT(LAST_DAY(NOW()), '%Y-%m-%d 23:59:59');";

// make query and get result

mysqli_query($conn, $sql);

// write query to update

$newSql = "UPDATE review SET review_of_month = 1 
    WHERE id = '$id';";

// make query and get result

mysqli_query($conn, $newSql);


header("location: ../reviewOfMonthUpdated.php?id=${id}");

exit();
