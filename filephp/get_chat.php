<?php 
session_start();
if(isset($_SESSION['unique_id'])){
   include_once("config.php");
   $outgoing_id = $_SESSION['unique_id'];
   $incoming_id = mysqli_real_escape_string($conn,json_decode($_POST['user_id']));
   $output = "";
   $sql = " SELECT * FROM messages 
   LEFT JOIN users ON users.unique_id = messages.outgoing_mes_id
   WHERE ( outgoing_mes_id = '{$outgoing_id}' AND 
   incoming_mes_id = '{$incoming_id}') OR ( outgoing_mes_id = '{$incoming_id}' AND  incoming_mes_id = '{$outgoing_id}') ORDER BY mes_id ASC"; 
   $query = mysqli_query($conn,$sql); 
   if(mysqli_num_rows($query) > 0){
       $output = array();
       
       while($row = mysqli_fetch_assoc($query)){
           if($row['outgoing_mes_id'] === $outgoing_id){
               // chọn ra người gửi trong một dòng row . để hiển thị tin nhắn của người đó
               array_push($output,(object)[
                   "mes" => $row['mes'],
                    "id" => $outgoing_id,
                    "unique_id" => $_SESSION['unique_id']
               ]);
            
            }
            else
            {
                array_push($output,(object)[
                    "mes" => $row['mes'],
                     "id" => $incoming_id,
                     "img" => $row['img'],
                     "unique_id" => $_SESSION['unique_id']
                ]);
           
            }
       }
      echo json_encode($output);
   }
}
else
{
    header("../login.html");
}
