<?php
session_start();
if(isset($_POST["captcha"])&&$_POST["captcha"]!=""&&$_SESSION["code"]==$_POST["captcha"])
{

include "db.php";
}
else
{
if(isset($_POST["captcha"]))
{
echo "<script type='text/javascript'>alert('Invalid Code')</script>";
}
echo '<script type="text/javascript"> document.location = "captchacode.php";</script>'; 
}


?>

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
<?php 
session_start();
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;

$_SESSION['token_time'] = time();
@$contractor=$_GET['contractor']; 

if(isset($contractor) and strlen($contractor) > 0){
		$usr_que = con_user_name($contractor); 
	}
?>


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
      
        
			<form name="pw_gen" method="post" action="passwdgen.php" id="form2" onsubmit="return checkForm(this)">
        <input type="hidden" name="token" value="<?php echo $token; ?>" /> 
        
		
		<?php

    $con_que = con_contractor_name_1();

    echo "<div>";
    echo "<table border = \"0\"> ";
    echo "<tr>";
    echo "<td>Contractor</td><td>&nbsp;&nbsp;&nbsp;</td>";
	echo "<td ><select style=\"border-radius:5px; width:170px;height:32px;text-align: left\" name= \"contractor\" class=\"form-control\" id=\"contractor\" onchange=\"getuser(this.value)\">";
	echo "<option value=\"\" selected></option>";
    while($row=oci_fetch_array($con_que))
    {
		echo"<option value=\"$row[0]\">$row[0]</option>";
    }
    echo "</select></tr><tr style=\"height:10px;\">  </tr >";
	
	
    
    echo "<tr>";
    echo "<td>User Name</td><td>&nbsp;&nbsp;&nbsp;</td>";
    echo "<td width=\"185\"><select style=\"border-radius:5px; width:170px;height:32px;text-align: left\" name=\"user\" class=\"form-control\"  id=\"user\">";
		echo "<option value=\"\"></option>";
		echo"</select> ";
    echo "</tr>"; 
    echo "<tr style=\"height:25px;\"></tr>";

    
       echo "<tr><td style=\"></td>
	   <td width=\"185\"><input type=\"submit\" class=\"btn btn-primary\" name=\"submit\" value=\"Generate\"></td> </tr>";
  echo "</table>";
?>
		
		
		
    
    </form>


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
