<?php

require_once 'startSession.php';
include('db_connect.php');

if($_SESSION['status'] !== 'teacher') {
  header("location: index.php");
}

if(isset($_GET["bookId"])) {
    $id = $_GET["bookId"];

    $sql = "UPDATE review SET approved = 1 WHERE id = '$id'";

    $result = mysqli_query($conn, $sql);

    header("location: ../approve.php");
}

exit();