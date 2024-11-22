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

$so_id = $_SESSION["so_id"];


$n_con_st_up = sta_update($so_id);
log_inprogress($user,$so_id);

if ($n_con_st_up) {
    echo "<script type='text/javascript'>alert('submitted successfully!')</script>";
    echo "<script type='text/javascript'>document.location = \"CO_SO_Details1.php?id=$so_id\";</script>";
}
else
{
    echo "<script type='text/javascript'>document.location = \"CO_SO_Con_Status.php?id=$so_id\";</script>";
}
?>