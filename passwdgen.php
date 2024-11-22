
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>SLTCMS</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  
  <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css" />
  
  
  <!-- REQUIRED SCRIPTS -->
  
<!-- jQuery -->
<!-- PAGE SCRIPTS -->
<script src="assets/js/jquery-2.1.4.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="dist/js/demo.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/world_countries.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>

<link rel="stylesheet" href="js/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>

<script>
function getuser(val) {
	$.ajax({
	type: "POST",
	url: "dynamic_load.php",
	data:'user='+val,
	success: function(data){
		$("#user").html(data);
	}
	});
}

function checkForm(frm) {

if (frm.contractor.value == "" ) {
         alert("Missing Contractor");
         return false;     
     }

if (frm.user.value == "" ) {
         alert("Missing User");
         return false;     
     }
}

</script> 
  
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand border-bottom navbar-dark navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>

    </ul>



    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->


    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index" class="brand-link">
      <img src="dist/img/slt.jpg" alt="" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-size: 25px; font-weight: bolder;">SLTCMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
        <?php include("navbar2.php"); ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-11">
            <h4 class="m-0 text-dark">Password Generate</h4>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
        <div class="container-fluid">
<hr />    
      
        
<?php
//ini_set('display_errors', 0);
include "db.php";
//if ($_POST['token'] == $_SESSION['token'])
//{
$contractor = $_POST["contractor"];
$usr_name = $_POST["user"];

//}


echo "</br>";
//$userID = 
$passwd = randomPassword();
function randomPassword() {
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

$hash = md5($passwd);
$from = 'oss@slt.com.lk';
$to = Get_email($contractor,$usr_name);
$subject = 'Password For SLT Contractor WFM';
//$message = 'hello ur pass word is = '.$passwd;
//$message = 'Dear'. $con_name;
//$message ='your password is : '.$passwd;
//$message = 'use the following link to login';
//$message = 'https://172.25.17.225/UR_Contractors/Login.php';

$message = '<html>
<head>
</head>
<body>
  <p>Dear '.$usr_name.',</p>
      </br>
  <p>User Name : '.$usr_name.'</p>   
  <p>password  : '.$passwd.'</p>    
      </br>
  <p>use the following link to login</p>
  <p>http://serviceportal.slt.lk/conmgt/Login.php</p>
</body>
</html>';

/*$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();*/
$headers  = "From: $from \r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

if ($to == 'Connection failed')
{
    echo "ERR001 : System Error, Please Contact System Administrator";
}

if ($to == NUCE)
{
    echo "Invalid User, Please Enter the Valid user name";
}

else {


//mail($to, $subject, $message, $headers );
//if(mail){
  
echo "Thank you for using our mail form. your Username & password is sent to ". $to ;
echo "</br>";
echo "You will Recieve a SMS shortly. $contractor,$usr_name";

$phn=getPhn($contractor,$usr_name);
$update_pwd = update_passwd($hash, $usr_name,$contractor);
//$insertsms = insertsms($passwd, $usr_name,$phn);
log_passwd($usr_name,$to);
//}else{
 // echo "Mail sending failed."; 
//}



//Send SMS via WDSL
//SendSMS($phn,$passwd,$usr_name);

//$wsdl = 'http://172.25.2.134:8080/Smssystem/smsSending?WSDL';

$wsdl = 'http://172.25.37.196:8080/Smssystem/smsSending?WSDL';

$trace = true;
$exceptions = false;

/*$xml_array['tps'] = $phn;
$xml_array['msg'] = "your username : $usr_name and Password : ".$passwd;
$xml_array['sender'] = 'OSS';
$xml_array['owner'] = 'SLTCMS';*/

$xml_array['tps'] = $phn;
$xml_array['msg'] = "your username : $usr_name and Password : ".$passwd;
$xml_array['sender'] = 'OSS';
$xml_array['owner'] = 'SLTCMS';
$xml_array['pwd'] = '!23qweASD';

try
{
   
   $client = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
 
   //$response = $client->operation($xml_array);
   $response = $client->smsdirectx($xml_array);

}
catch (Exception $e)
{
   echo "Error!";
   echo $e -> getMessage ();
   echo 'Last response: '. $client->__getLastResponse();
}
//var_dump($response);

}
//}
?>


  </div>    
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer" style="text-align: center;">
    <strong>Copyright &copy; 2019  <span style="color: red;">IT Solution & Devops.</span> </strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->


</body>
</html>
