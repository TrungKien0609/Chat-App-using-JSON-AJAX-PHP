<?php
session_start();
$outgoing_id = $_SESSION['unique_id'];
include_once "config.php";
$sql = mysqli_query($conn,"SELECT * FROM users WHERE NOT unique_id= {$outgoing_id}");
$output = "";
if(mysqli_num_rows($sql) == 0){ 
    echo json_encode("No users are available to chat");
}
else if(mysqli_num_rows($sql)>0)
{
    $array = array();
    while($row = mysqli_fetch_assoc($sql))
    {
        $mes = "";
        $sql1 = "SELECT * FROM messages WHERE (incoming_mes_id = '{$row['unique_id']}' 
        OR outgoing_mes_id = '{$row['unique_id']}')
        AND (incoming_mes_id = '{$outgoing_id}' 
        OR outgoing_mes_id = '{$outgoing_id}') ORDER BY mes_id DESC LIMIT 1 " ;
        $query2 = mysqli_query($conn,$sql1);
        $row2 = mysqli_fetch_assoc($query2);
        if(mysqli_num_rows($query2) > 0){
            $result =  $row2['mes'];
            // cắt tin nhắn cuối cùng tối đa 28 kí tự
            (strlen($result) > 28) ? $mes = substr($result,0,28).'....' : $mes = $result;
        }
        else{
            $result = "No message available";
        }
        $offline = "";
        
        // check user is online or not ?
        ($row['status'] === "Offline now") ? $offline = "offline" :$offline = "";

        $outgoing_id == $row2['outgoing_mes_id'] ? $you = 'you: ' : $you = "";
        
        // $userchats->unique_id = $row['unique_id'];
        // $userchats->img =$row['img'];
        // $userchats->fname = $row['fname'];
        // $userchats->lname = $row['lname'];
        // $userchats->you = $you;
        // $userchats->mes = $mes;
        // $userchats->offline = $offline;
        array_push($array,(object)[
            'unique_id' => $row['unique_id'],
            'img'=> $row['img'],
            'fname' => $row['fname'],
            'lname' => $row['lname'],
            'you' => $you,
            'mes' => $mes,
            'offline' => $offline
        ]);
    }
    echo json_encode($array);
}

?>