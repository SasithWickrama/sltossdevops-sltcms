<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	$temp = explode('/',$area);
	$n = sizeof($temp);
    
    $refno=date("ymdHis");
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}
include "db.php";


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

<script type="text/javascript">
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
        </script> 



   <script>
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField",
			dateFormat:"%m/%d/%Y"
		});
                new JsDatePick({
			useMode:2,
			target:"inputField2",
			dateFormat:"%m/%d/%Y"
		});
	};
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

        <li class="nav-item dropdown">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="color:white">
							<img class="nav-user-photo" src="dist/img/user.png" style="border-radius: 50%;" alt="" />
								<span class="user-info" style="color:white">
									<small>Welcome,
									<?php echo $_SESSION['user']; ?>
									</small>
								</span>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li class="divider"></li>
								<li>
									<a href="logout.php">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
		</li>
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
        <?php include("navbar.php"); ?>
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
            <h4 class="m-0 text-dark">Approved Service Orders</h4>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />


         <form name="form2" method="post" action="CO_SO_CON_Compap2.php" id="form2"> 
   <?php
   $i = 0;
   $area = con_area();
	echo"	 <div id=\"page-wrapper\">
	

					
	<div class=\"panel-body\">

                                    <table border = \"0\"> ";
    echo "<tr>";
    echo "<td>RTOM Area </td><td>&nbsp;&nbsp;&nbsp;</td>";
    echo "<td ><select style=\"border-radius:5px; width:250px;height:35px;text-align: left\" class=\"form-control\"  name=\"area\"  id=\"area\">";
    echo "<option value=\"\"></option>";
    echo "<option value=\"ALL\">ALL</option>";
    while($row=oci_fetch_array($area))

    {
        echo "<option value=\"$row[0]\">$row[0]</option>";
    }
    
    echo "</select>";
    echo "</tr><tr style=\"height:10px;\">  </tr >";
    echo "<tr>";
	 echo "<td>Order Type </td><td>&nbsp;&nbsp;&nbsp;</td>";
	 echo "<td ><select style=\"border-radius:5px;width:100;height:35px;text-align: left\" class=\"form-control\"  name=\"ortyp\"  id=\"ortyp\">";
    echo "<option value=\"\"></option>";
    echo "<option value=\"ALL\">ALL</option>";
	echo "<option value=\"CREATE\">CREATE</option>";
	echo "<option value=\"CREATE-OR\">CREATE-OR</option>";
	echo "<option value=\"CREATE-RECON\">CREATE-RECON</option>";
    echo "<option value=\"CREATE-UPGRD SAME NO\">CREATE-UPGRD SAME NO</option>";
	echo "<option value=\"MODIFY-LOCATION\">MODIFY-LOCATION</option>";
	echo "<option value=\"MODIFY-SERVICE\">MODIFY-SERVICE</option>";
        echo "<option value=\"MODIFY-LOC SAMEDP\">MODIFY-LOC SAMEDP</option>";
	  echo "</tr><tr style=\"height:10px;\">  </tr >";
    echo "<tr>";
	 echo "<td>Service Type *</td><td>&nbsp;&nbsp;&nbsp;</td>";
	 echo "<td ><select style=\"border-radius:5px;width:100;height:35px;text-align: left\" class=\"form-control\"  name=\"sertyp\" id=\"sertyp\">";
    echo "<option value=\"\"></option>";
	echo "<option value=\"AB-CAB\">AB-CAB</option>";
    echo "<option value=\"AB-FTTH\">AB-FTTH</option>";
    echo "<option value=\"E-IPTV COPPER\">E-IPTV COPPER</option>";
    echo "<option value=\"E-IPTV FTTH\">E-IPTV FTTH</option>";
	  echo "</tr><tr style=\"height:10px;\">  </tr >";  
    echo "<tr>";
    echo "<td >From Date * </td><td>&nbsp;&nbsp;&nbsp;</td><td><input type=\"text\"  class=\"form-control\"  name=\"from\" style=\"border-radius:5px; width:250px;height:35px;text-align: left\" id=\"inputField\" /></td>
    </tr><tr style=\"height:10px;\">  </tr >";
    echo "<tr>
    <td >To Date * </td><td>&nbsp;&nbsp;</td><td><input type=\"text\" name=\"to\" class=\"form-control\" style=\"border-radius:5px; width:250px;height:35px;text-align: left\" id=\"inputField2\" /></td>
    </tr><tr style=\"height:10px;\">  </tr >"; 
    echo "<tr style=\"height:15px;\"></tr>";
    echo "<tr><td></td><td>&nbsp;&nbsp;</td><td align =\"right\"><input type=\"submit\" class=\"btn btn-primary\" name=\"submit\" value=\"search\"></td>";
    echo "</tr>";
    echo "</table>";
   
    ?>  
	</form>

      </div><!--/. container-fluid -->
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
