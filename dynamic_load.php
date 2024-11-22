<?php
include "db.php";

 if(isset($_POST["user"])) {
    
    $user = con_user_name($_POST["user"]);
    
    echo"<option value=\"\">Select User</option>";
    
    while($row = oci_fetch_array($user)){
	
       echo"<option  value=\"$row[0]\">$row[0]</option>";
     } 
    
}


?>