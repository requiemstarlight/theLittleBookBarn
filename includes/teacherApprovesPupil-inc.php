<?php

require_once 'startSession.php';
include('db_connect.php');

if($_SESSION['status'] !== 'teacher' && !isset($_GET['id'])) {
    header("location: ../index.php");
}

if(isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "UPDATE users SET teacherApproved = 1
        WHERE id = '$id'";

    $result = mysqli_query($conn, $sql);

    header("location: ../myPupils.php");

}

exit();