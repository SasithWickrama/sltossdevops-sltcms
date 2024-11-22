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
$contractor_name = $_SESSION["contractor"];
$fromdate = $_POST["from"];
$todate = $_POST["to"];
$areanew=$_POST["area"];
$ortyp=$_POST["ortyp"];
$sertyp=$_POST["sertyp"];



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

<script type="text/javascript">
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
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
            <h4 class="m-0 text-dark">Completed Service Orders</h4>

        </div>
        <div class="col-sm-1">
            <h6><a href="javascript:history.go(-1)"><img src="img/back.jpg" width="35" alt="back" height="35"></a></h6>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />


            <?php
	echo"	 <div id=\"page-wrapper\">
	
	<div class=\"row\">
                <div class=\"col-lg-12\">
					<h6><b>RTOM :</b> $areanew &nbsp;&nbsp;&nbsp;<b>SERVICE:</b> $sertyp &nbsp;&nbsp;&nbsp;<b>ORDER TYPE:</b> $ortyp</h6>
					</div>
                <!-- /.col-lg-12 -->
                
                               <div class=\"col-sm-11\"></div>
                <div class=\"col-sm-1\">
                <div class=\"form-group\">
                 <a href=\"soreports/{$areanew}_{$contractor_name}_closedso.csv\" download><img src=\"dist/img/Excel.ico\" width=\"30\" height=\"30\"/>
                  <label >EXCEL</label></a>

                  
                </div>
                
                </div> 
                
            </div></br>";
			

   $empty_result_set = true;
   $i=1;

    if ($areanew == 'ALL')
    {
	$HEADER = "NO,Circuit NO,Service Order NO,Received Date,Completed Date,Task Name,Delayed Days,Penalty,Approved,Order Type\n";
      $so_con_comp_list = so_con_comp_all($fromdate,$todate,$sertyp,$ortyp,$contractor_name,$areanew);			
	echo" <div class=\"panel-body\">

		
                        <div class=\"table-responsive\">
                        <table id=\"simple-table\"  style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                            <thead>
                            <tr style=\"font-weight:bold\">
								<th>#</th>
								<th>Circuit NO</th>
								<th>Service Order NO</th>
                                <th>Order Type</th>
								<th>Received Date</th>
								<th>Completed Date</th>
								<th>Task Name</th>
                                <th>Delay Days</th>
								<th>Penalty</th>
                            </tr>
                            </thead>
                            <tbody>		
			";
        while ($row = oci_fetch_array($so_con_comp_list))
		{
		if ($row[9] == "")
            {
                $row[9] = 'NO';
            }
			$empty_result_set = false;	 
		echo "<tr>
				<th>$i</th>";
			if($sertyp == 'AB-CAB' || $sertyp == 'AB-FTTH')
			{			
				echo"<td><a href=\"CO_SO_CON_Detail.php?id=$row[1]\" target=\"_blank\">$row[0]</a></td>";
			}
			if($sertyp == 'E-IPTV COPPER' || $sertyp == 'E-IPTV FTTH')
			{			
				echo"<td><a href=\"CO_SO_IPTV_Detail.php?id=$row[1]\" target=\"_blank\">$row[0]</a></td>";
			}
		echo"		<td>$row[1]</td>
                <td>$row[8]</td>
				<td>$row[2]</td>
				<td>$row[3]</td>
				<td>$row[4]</td>
				<td>$row[5]</td>
				<td>$row[6]</td>
				</tr>";
		$HEADER = $HEADER . "{$i},{$row[0]},{$row[1]},{$row[2]},{$row[3]},{$row[4]},{$row[5]},{$row[6]},{$row[7]},{$row[8]}\n";
		$i++;
		}
    
		echo "</table> ";
		
		if ($empty_result_set) {
    // No rows in the result set.
		echo "<script type='text/javascript'>alert('No Data Found')</script>";
        echo "<script type='text/javascript'>document.location = \"CO_SO_CON_Comp.php\";</script>";
		}	
		
		echo "</div>";
    }
     else if ($areanew != 'ALL' && $ortyp == 'ALL')
    {
	$HEADER = "NO,Circuit NO,Service Order NO,Received Date,Completed Date,Task Name,Delayed Days,Penalty,Approved,Order Type\n";
      $so_con_comp_list = so_con_comp_all($fromdate,$todate,$sertyp,$ortyp,$contractor_name,$areanew);			
	echo" <div class=\"panel-body\">

		
                        <div class=\"table-responsive\">
                        <table id=\"simple-table\"  style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                            <thead>
                            <tr style=\"font-weight:bold\">
								<th>#</th>
								<th>Circuit NO</th>
								<th>Service Order NO</th>
                                <th>Order Type</th>
								<th>Received Date</th>
								<th>Completed Date</th>
								<th>Task Name</th>
                                <th>Delay Days</th>
								<th>Penalty</th>
	
                            </tr>
                            </thead>
                            <tbody>		
			";
        while ($row = oci_fetch_array($so_con_comp_list))
		{
		if ($row[9] == "")
            {
                $row[9] = 'NO';
            }
			$empty_result_set = false;	 
		echo "<tr>
				<th>$i</th>";
			if($sertyp == 'AB-CAB' || $sertyp == 'AB-FTTH')
			{			
				echo"<td><a href=\"CO_SO_CON_Detail.php?id=$row[1]\" target=\"_blank\">$row[0]</a></td>";
			}
			if($sertyp == 'E-IPTV COPPER' || $sertyp == 'E-IPTV FTTH')
			{			
				echo"<td><a href=\"CO_SO_IPTV_Detail.php?id=$row[1]\" target=\"_blank\">$row[0]</a></td>";
			}
		echo"		<td>$row[1]</td>
                <td>$row[8]</td>
				<td>$row[2]</td>
				<td>$row[3]</td>
				<td>$row[4]</td>
				<td>$row[5]</td>
				<td>$row[6]</td>

				</tr>";
		$HEADER = $HEADER . "{$i},{$row[0]},{$row[1]},{$row[2]},{$row[3]},{$row[4]},{$row[5]},{$row[6]},{$row[7]},{$row[8]}\n";
		$i++;
		}
    
		echo "</table> ";
		
		if ($empty_result_set) {
    // No rows in the result set.
		echo "<script type='text/javascript'>alert('No Data Found')</script>";
        echo "<script type='text/javascript'>document.location = \"CO_SO_CON_Comp.php\";</script>";
		}	
		
		echo "</div>";
    }
	else
	{
	$HEADER = "NO,Circuit NO,Service Order NO,Received Date,Completed Date,Task Name,Delayed Days,Penalty,Approved,Order Type\n";
      $so_con_comp_list = so_con_comp_li($fromdate,$todate,$areanew,$sertyp,$ortyp,$contractor_name);			
	echo" <div class=\"panel-body\">

		
                        <div class=\"table-responsive\">
                        <table id=\"simple-table\" style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                            <thead>
                            <tr style=\"font-weight:bold\">
								<th>#</th>
								<th>Circuit NO</th>
								<th>Service Order NO</th>
                                <th>Order Type</th>
								<th>Received Date</th>
								<th>Completed Date</th>
								<th>Task Name</th>
                                <th>Delay Days</th>
								<th>Penalty</th>
                            </tr>
                            </thead>
                            <tbody>		
			";
        while ($row = oci_fetch_array($so_con_comp_list))
		{
		if ($row[7] == "")
            {
                $row[7] = 'NO';
            }
			$empty_result_set = false;	 
		echo "<tr>
				<th>$i</th>";
			if($sertyp == 'AB-CAB'  || $sertyp == 'AB-FTTH')
			{			
				echo"<td><a href=\"CO_SO_CON_Detail.php?id=$row[1]\" target=\"_blank\">$row[0]</a></td>";
			}
			if($sertyp == 'E-IPTV COPPER' || $sertyp == 'E-IPTV FTTH')
			{			
				echo"<td><a href=\"CO_SO_IPTV_Detail.php?id=$row[1]\" target=\"_blank\">$row[0]</a></td>";
			}
		echo"	
				<td>$row[1]</td>
                <td>$row[8]</td>
				<td>$row[2]</td>
				<td>$row[3]</td>
				<td>$row[4]</td>
				<td>$row[5]</td>
				<td>$row[6]</td>
				</tr>";
		$HEADER = $HEADER . "{$i},{$row[0]},{$row[1]},{$row[2]},{$row[3]},{$row[4]},{$row[5]},{$row[6]},{$row[7]},{$row[8]}\n";
		$i++;
		}
    
		echo "</table> ";
		
		if ($empty_result_set) {
    // No rows in the result set.
		echo "<script type='text/javascript'>alert('No Data Found')</script>";
        echo "<script type='text/javascript'>document.location = \"CO_SO_CON_Comp.php\";</script>";
		}	
		
		echo "</div>";
	}
	
		$File = "soreports/{$areanew}_{$contractor_name}_closedso.csv";
		$FILE_WRITE = fopen($File, 'w') or die("can't open file");
		fwrite($FILE_WRITE, $HEADER);
		fclose($FILE_WRITE);
    ?>  

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
