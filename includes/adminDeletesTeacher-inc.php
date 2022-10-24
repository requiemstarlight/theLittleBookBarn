<?php

require_once 'startSession.php';
include('db_connect.php');

if($_SESSION['status'] !== 'admin' && !isset($_GET['id'])) {
    header("location: ../index.php");
}

if(isset($_GET["id"])) {
    $id = $_GET["id"];

    // first delete all their reviews

    $sql = "DELETE FROM review WHERE user_id = '$id'";

    $result = mysqli_query($conn, $sql);

    // now delete the user themselves

    $newSql = "DELETE FROM users WHERE id = '$id'";

    $newResult = mysqli_query($conn, $newSql);

    header("location: ../myTeachers.php");

}

exit();