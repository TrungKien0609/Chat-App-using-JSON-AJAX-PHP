<?php 
session_start();
if(isset($_SESSION['unique_id'])){
   include_once("config.php");
   $outgoing_id = $_SESSION['unique_id'];
   $data = mysqli_real_escape_string($conn,$_POST['data']);
   $incoming_id = "";
   $message = "";
   for($i= 0 ; $i < strlen($data);$i++){
       if($data[$i] == "a"){
        $incoming_id= substr($data,0,$i);
        break;
       }
   }
   for($i= 0 ; $i < strlen($data);$i++){
    if($data[$i] == "d"){
        $message= substr($data,$i+1);
        break;
       }
   }
   if(!empty($message)) {
       $sql = mysqli_query($conn,"INSERT INTO messages (incoming_mes_id,outgoing_mes_id,mes) 
       VALUES ('{$incoming_id}','{$outgoing_id}','{$message}')") or die() ; // die để show lổi khi có lổi xảy ra
}
}
else
{
    header("../login.php");
}
?>