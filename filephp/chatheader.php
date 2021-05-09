<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    echo json_encode("fail");
} else {
    include_once("config.php");
    $user_id = mysqli_real_escape_string($conn, json_decode($_POST['user_id']));
    $sql = mysqli_query($conn, " SELECT * FROM users WHERE unique_id = '{$user_id}'");
    if (mysqli_num_rows($sql)) {
        $row = mysqli_fetch_assoc($sql);
        $chathearder = new stdClass();
        $chathearder->img = $row['img'];
        $chathearder->fname = $row['fname'];
        $chathearder->lname = $row['lname'];
        $chathearder->status = $row['status'];
        echo json_encode($chathearder, JSON_PRETTY_PRINT);
    } else
        echo json_encode("fail");
}
