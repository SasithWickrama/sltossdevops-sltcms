<?php
ini_set ("display_errors", "1");
error_reporting(E_ALL);

$db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty))
  )
";

   
   if($c = oci_connect("OSSRPT", "ossrpt123", $db))        
    {
      echo "Successfully connected to Oracle.\n";
            return $c;
    }
    else
    {
        $err = OCIError();
        echo "Connection failed." . $err[messege];
    }


?>