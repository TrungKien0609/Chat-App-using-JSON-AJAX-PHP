<?php
include_once "config.php";
session_start();
if (!isset($_SESSION['unique_id'])) {
    echo "fail login";
} else {
    $sql = mysqli_query($conn,"SELECT * FROM users WHERE unique_id =  '{$_SESSION['unique_id']}'");
    if(mysqli_num_rows($sql) > 0){
        $row  = mysqli_fetch_assoc($sql);
        $userinfor = new stdClass();
        $userinfor->fname = $row['fname'];
        $userinfor->lname = $row['lname'];
        $userinfor->status = $row['status'];
        $userinfor->img = $row['img'];
        $userinfor->userId = $row['unique_id'];
        $userinforJSON =  json_encode($userinfor);
        echo $userinforJSON;
    }
}
?>