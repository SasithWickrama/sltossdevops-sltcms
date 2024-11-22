<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor = $_SESSION["contractor"];
   	$uarea = $_GET["id"];
    $temp1 = explode('_',$uarea);
    $n = sizeof($temp);

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
            <h4 class="m-0 text-dark"></h4>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 16px;">
       <div class="container-fluid">
   

<?php

if($temp1[3] == 'ACCESS'){
    
    $con = 'ACCESS_ENG';
}else {
    
    $con = $temp1[3];
}

$invno =str_replace('/', '', $temp1[0]);
	echo"	 <div id=\"page-wrapper\">
	
	<div class=\"row\">
                <div class=\"col-lg-12\">
                    <h4 class=\"page-header\">Invoice</h4>
                    <hr/>
                </div>
                <!-- /.col-lg-12 --></div>";
       
                
        echo"        <p><a href = \"Invoice/$temp1[1]/$con/{$invno}A.pdf\" download>$temp1[0]-A</a></p>
                <p><a href = \"Invoice/$temp1[1]/$con/{$invno}B.pdf\" download>$temp1[0]-B</a></p>

                
            
  <br/>
  <div class=\"row\">
                <div class=\"col-lg-12\">
                    <h4 class=\"page-header\">Meterial Number Invoice</h4>
                    <hr/>
                </div>
                <!-- /.col-lg-12 -->
                
                
                <p><a href = \"Invoice/$temp1[1]/$con/{$invno}_met.xls\" download>$temp1[0]-METERIAL</a></p>
                
            </div>          
            ";


    
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