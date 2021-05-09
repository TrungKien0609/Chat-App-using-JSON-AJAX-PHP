<?php
session_start();
include_once("config.php");
$output = "";
$outgoing_id = $_SESSION['unique_id'];
$searchTerm = mysqli_real_escape_string($conn, json_decode($_POST['searchTerm']));
$sql = mysqli_query($conn, "SELECT * FROM users  WHERE NOT unique_id= {$outgoing_id} AND ( fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%')");
if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);

    $mes = "";
    $sql1 = "SELECT * FROM messages WHERE (incoming_mes_id = '{$row['unique_id']}' 
        OR outgoing_mes_id = '{$row['unique_id']}')
        AND (incoming_mes_id = '{$outgoing_id}' 
        OR outgoing_mes_id = '{$outgoing_id}') ORDER BY mes_id DESC LIMIT 1 ";
    $query2 = mysqli_query($conn, $sql1);
    $row2 = mysqli_fetch_assoc($query2);
    if (mysqli_num_rows($query2) > 0) {
        $result =  $row2['mes'];
        // cắt tin nhắn cuối cùng tối đa 28 kí tự
        (strlen($result) > 28) ? $mes = substr($result, 0, 28) . '....' : $mes = $result;
    }
    $offline = "";
    // check user is online or not ?
    ($row['status'] === "Offline now") ? $offline = "offline" : $offline = "";

    $outgoing_id == $row2['outgoing_mes_id'] ? $you = 'you: ' : $you = "";

    $output = array();
    array_push($output, (object)[
        "unique_id" => $row['unique_id'],
        "img" => $row['img'],
        "fname" => $row['fname'],
        "lname" => $row['lname'],
        "you" => $you,
        "mes" => $mes,
        "offline" => $offline
    ]);
    echo json_encode($output,JSON_PRETTY_PRINT);
} else {
    echo json_encode("No user found related to your search term");
}
