<?php

session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}
include "db.php";
$so_id = $_POST["so_id"];
$reason = $_POST["re_reasons"];
$reason1= $_POST["otherreason"];

if($reason == "Other" || $reason == "OVER DISTANCE")
{
$rea = $reason.' - '.$reason1;

$return = so_return($so_id,$rea,$user);
log_returned($user,$so_id,$reason1);
}
else 
{
     $return = so_return($so_id,$reason,$user);
 log_returned($user,$so_id,$reason);
}

if ($return) {
    echo "<script type='text/javascript'>alert('submitted successfully!')</script>";
    echo "<script type='text/javascript'>document.location = \"CO_SO_List.php\";</script>";
}
else
{
    echo "<script type='text/javascript'>document.location = \"CO_SO_Return.php?id=$so_id\";</script>";
}
?>