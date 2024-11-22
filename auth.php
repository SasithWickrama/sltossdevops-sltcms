<?php
session_start();
include "db.php";
$ip=$_SERVER['REMOTE_ADDR'];

if ($_POST['token'] == $_SESSION['token'])
{
    
// Grab User submitted information
$user_name = $_POST["txtUsername"];
$con_slt = $_POST["contractor"];
//$user_key = $_POST["txtkey"];
//$split = explode("-",$user_name);
//user = $split[1];
//$user = $_POST["txtUsername"];
$pass = $_POST["txtPassword"];
$hash = md5($pass);
// Select the database to use
//SLT Domain Login
if($con_slt == 'SLT')
{
    
    $auth = validate_user($user_name,$con_slt);
    $row=oci_fetch_array($auth);
    
    if($row[0]==$user_name)
    {    $uname = $user_name."@intranet.slt.com.lk";

        $link = ldap_connect( 'intranet.slt.com.lk' );
        if( ! $link )
        {
                echo"Cant Connect to Server";
        }
        ldap_set_option( $link, LDAP_OPT_PROTOCOL_VERSION, 3 ); 
        if (  ldap_bind( $link, $uname, $pass ) )
        {
                $_SESSION['loggedin'] = true; 
                $_SESSION["user"] = $user_name;
		$_SESSION["level"] = $row[3];
		$_SESSION["area"] = $row[4];
        $_SESSION["contractor"] = $con_slt;
		$_SESSION["opmc"] = $row[5];
		

			//echo '<script type="text/javascript"> document.location = "slt_dash";</script>';
			echo '<script type="text/javascript"> document.location = "sltdash.php";</script>';
                 log_logging($user_name, $ip);
			
        }
        else{
                echo "<script type='text/javascript'>alert('Sorry, your credentials are not valid, Please try again or you have already logged using this password once')</script>";
                log_fail($user_name, $ip);
                echo '<script type="text/javascript"> document.location = "login.php";</script>';
        }
    }
    else
    {
        echo "<script type='text/javascript'>alert('Sorry, your not authorized for this site')</script>";
                log_fail($user_name, $ip);
                echo '<script type="text/javascript"> document.location = "login.php";</script>';
    }
}
else  ////SLT Contractor Login
{
    
	if($user_name == 'WEBADMIN' && $hash == 'a2cee7e92f5bf48541dc3151d1542285')
	{
		$_SESSION['loggedin'] = true;
		$_SESSION["user"] = $user_name;
		$_SESSION["area"] = 'ALL';
		$_SESSION["contractor"] = $con_slt;
		$_SESSION["level"] = 5;
	 echo '<script type="text/javascript"> document.location = "CO_SO_List.php";</script>';
	}
	else
	{
	$auth = validate_user($user_name,$con_slt);
    $row=oci_fetch_array($auth);
    //if($row[0]==$user ){
	echo $row[0] ,$row[2];
        if($row[0]==$user_name && $row[2]==$hash){
        //if($row[0]==$user && $row[2]==$hash && $user_key == $row[4] ){    

            $_SESSION['loggedin'] = true;
            $_SESSION["user"] = $user_name;
            $_SESSION["contractor"] = $con_slt;
            $_SESSION["level"] = $row[3];
			$_SESSION["area"] = $row[4];
        change_hash($user_name , $con_slt);   
        log_logging($user_name, $ip);
            
            if($row[4] == 'HQQC' && $con_slt == 'SLTS')
            {
                echo '<script type="text/javascript"> document.location = "SLT_QUANTITY_CON.php";</script>';
            }
            
            if($row[3] == 2 || $row[3] == 5)
            {
                echo '<script type="text/javascript"> document.location = "CO_SO_List.php";</script>';
            }
        }
        else
        {
            echo "<script type='text/javascript'>alert('Sorry, your credentials are not valid, Please try again.')</script>";
            log_fail($user, $ip);
            echo '<script type="text/javascript"> document.location = "login.php";</script>';
        }
	}
}  
}
?>
