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
$so_id = $_GET["id"];

$so_com = so_com_ospupdate($so_id);


if ($so_com) {
    echo "<script type='text/javascript'>alert('submitted successfully!')</script>";
    echo "<script type='text/javascript'>document.location = \"CO_SO_List.php\";</script>";
}
?>