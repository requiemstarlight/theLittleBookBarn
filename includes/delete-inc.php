<?php

require_once 'startSession.php';
include('db_connect.php');

if($_SESSION['status'] !== 'teacher' || $_SESSION['status'] !== 'pupil') {
}

if(isset($_GET["bookId"])) {
    $id = $_GET["bookId"];
    $location = $_GET["location"];

    $sql = "DELETE FROM review  WHERE id = '$id'";

    $result = mysqli_query($conn, $sql);

    if($location === "editDelete") {
      header("location: ../editDelete.php");
    } 
    else if($location === "approve") {
    header("location: ../approve.php");
    } 
    else if ($location === "editDeletePupil") {
      header("location: ../editDeletePupil.php");
    }
}

exit();

