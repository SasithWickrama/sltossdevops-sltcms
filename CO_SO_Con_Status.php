<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
	$contractor_name = $_SESSION["contractor"];
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}
include "db.php";

$so_id = $_GET["id"];
$_SESSION["so_id"] = $so_id;
$rdate = $_GET["rdate"];

$prio_approve = oci_fetch_array(priority($so_id));
$service_add = oci_fetch_array(service_add($so_id));

$getsod = getsod($so_id);
$chksod = chksod($so_id);

$chkwo = chkwo($so_id);
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
            <h4 class="m-0 text-dark">Service Order Details</h4>
        </div>
        <div class="col-sm-1">
            <h6><a href="CO_SO_List.php"><img src="img/back.jpg" width="35" alt="back" height="35"></a></h6>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />

        	<form name="form2" method="post" action="CO_SO_ST_Update.php" id="form2">
        <input type="hidden" id="so_id" name="so_id" value="<?php echo $so_id ?>">	
   <?php

        
	

	echo"	 <div id=\"page-wrapper\">
	
	<div class=\"row\">
                <div class=\"col-lg-12\">
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
			
			
	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                                    
                                    <tbody>		

			";
//OLD RECORD
if($chkwo != ''){
    echo "<tr>
			<th>Service Order NO</th>
            <td>$so_id</td>
			<th>Recieve Date</td>
            <td>$rdate</td>
            </tr>";
			
	echo "<tr>
			<th>Service Type</td>
			<td>$prio_approve[5]</td>
            <th>Order Type</td>
            <td>$prio_approve[6]</td>
            </tr>";			
		
	echo "<tr>
            <th>Customer Name</td>
            <td>$prio_approve[3]</td>
			<th>Customer Contact NO</td>
			<td>$prio_approve[4]</td>
            </tr>";		
					

	echo "<tr>
			<th>Service Address</th>
            <td colspan =\"3\">$service_add[0] $service_add[1] $service_add[2] $service_add[3] $service_add[4]</td>
            </tr>";	

		echo "<tr>
			<th>Circuit NO</th>
            <td>$prio_approve[2]</td>
            <th>Status</td>
			<td>INPROGRESS</td>
            </tr>";			
    
	echo "<tr>
			<th>Description</th>
            <td colspan =\"3\">$prio_approve[7]</td>
            </tr>";
			
    echo "</table> </div>";
    
    
    echo "<tr>
			<th colspan =\"3\"></th>
            <td><input type=\"submit\" class=\"btn btn-primary\" name=\"submit\" value=\"Assign\"></td>
            </tr>";
}



            
//NEW RECORD            
if(($getsod == 'E-IPTV COPPER' or $getsod == 'E-IPTV FTTH') && $chksod == '1' && $chkwo == ''){
    echo "<tr>
			<th>Service Order NO</th>
            <td>$so_id</td>
			<th>Recieve Date</td>
            <td>$rdate</td>
            </tr>";
			
	echo "<tr>
			<th>Service Type</td>
			<td>$prio_approve[5]</td>
            <th>Order Type</td>
            <td>$prio_approve[6]</td>
            </tr>";			
		
	echo "<tr>
            <th>Customer Name</td>
            <td>$prio_approve[3]</td>
			<th>Customer Contact NO</td>
			<td>$prio_approve[4]</td>
            </tr>";		
					

	echo "<tr>
			<th>Service Address</th>
            <td colspan =\"3\">$service_add[0] $service_add[1] $service_add[2] $service_add[3] $service_add[4]</td>
            </tr>";	

		echo "<tr>
			<th>Circuit NO</th>
            <td>$prio_approve[2]</td>
            <th>Status</td>
			<td>INPROGRESS</td>
            </tr>";			
    
	echo "<tr>
			<th>Description</th>
            <td colspan =\"3\">$prio_approve[7]</td>
            </tr>";
			
    echo "</table> </div>";
    
    
    echo "<tr>
			<th colspan =\"3\"></th>
            <td><input type=\"submit\" class=\"btn btn-primary\" name=\"submit\" value=\"Assign\"></td>
            </tr>";
}
if($chksod == '' && $chkwo == ''){

      
        $chkvoice = chkvoice($so_id);
        
        if($chkvoice == ''){
            
            $getftthcab= oci_fetch_array(getftthcab($so_id));
        }else{
            $getftthcabiptv = getftthcabiptv($chkvoice);
            $getftthcab= oci_fetch_array(getftthcab($getftthcabiptv));
        }
        

  
        
        if($getftthcab[1] == 'AB-CAB'){
            $osp_date = oci_fetch_array(osp_date($so_id));
        }
        else if($getftthcab[1] == 'AB-FTTH'){
            $ftth_data = oci_fetch_array(ftth_data($so_id));
        }
        
  if($getftthcab[1] == 'AB-CAB'){
 echo"<input type=\"hidden\" id=\"ser\" name=\"ser\" value=\"AB-CAB\">";

        $getiptv= oci_fetch_array(getiptv($getftthcab[3]));
    echo "<tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">CAB SOD Details</span> </th></tr>";
    
        echo "<tr>
			<th>CAB SOD</th>
            <td>$getftthcab[0]</td>
            <th>Service Type</th>
            <td>$getftthcab[1]</td>
			<th>Recieve Date</td>
            <td>$getftthcab[2]</td>
            </tr>
            
            <tr>
			<th>VOICE NO</th>
            <td>$getftthcab[3]</td>
            <th>RTOM</th>
            <td>$getftthcab[4]</td>
			<th>Order Type</td>
            <td>$getftthcab[5]</td>
            </tr>
            
            <tr>
            <th>Customer Name</td>
            <td>$getftthcab[6]</td>
			<th>Customer Contact NO</td>
			<td>$getftthcab[7]</td>
            <th>SOD Status</td>
            <td ><select class=\"form-control select2\"  style=\"border-radius:5px;width:270;height:35px; color:#1C96B1; \"  onchange=\"test()\" class=\"txtbg\" id=\"ch_status\">
			<option value=\"ASSIGNED\">ASSIGNED</option>
			 </select></td>
            </tr>
            
            <tr>
			<th>Service Address</th>
            <td colspan=\"5\">$getftthcab[8]</td>
            </tr>
            
            <tr>
			<th>Megaline Package</th>
            <td>$osp_date[4]</td>
            <th>Phone Class</th>
            <td>$getftthcab[11]</td>
			<th>Phone Purchase from SLT</td>
            <td>$getftthcab[13]</td>
            </tr>
            
            <tr>
			<th>DP Loop </th>
            <td>$getftthcab[9] - $getftthcab[10]</td>
            <th>Description</th>
            <td colspan=\"3\">$getftthcab[14]</td>
            </tr>";
      if($osp_date[4] == 'Triple Play' or $osp_date[4] == 'Double Play - PeoTV') {    
            echo"<tr style=\"height:30px;\"> </tr>
            <tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">IPTV SOD Details</span></th></tr>
            <tr>
			<th>IPTV SOD</th>
            <td>$getiptv[0]</td>
            <th>Service Type</th>
            <td>$getiptv[1]</td>
			<th>Recieve Date</td>
            <td>$getiptv[2]</td>
            </tr>
            
            <tr>
			<th>IPTV NO</th>
            <td>$getiptv[3]</td>
            <th>RTOM</th>
            <td>$getiptv[4]</td>
			<th>Order Type</td>
            <td>$getiptv[5]</td>
            </tr>
            
            <tr>
			<th>MSAN Location</th>
            <td>$getiptv[7] - $getiptv[6]</td>
            <th>CARD PORT</th>
            <td>$getiptv[8] - $getiptv[9]</td>
			<th>ADSL Router Class</td>
            <td>$getiptv[10]</td>
            </tr>
            
            ";}
    
}else if($getftthcab[1] == 'AB-FTTH'){
  echo"<input type=\"hidden\" id=\"ser\" name=\"ser\" value=\"AB-FTTH\">";
    
    $getiptv= oci_fetch_array(getiptv($getftthcab[3]));
    
    if($ftth_data[3]== 'VOICE_INT_IPTV'){
        $a= 'Triple Play';
    }
    if($ftth_data[3]== 'VOICE_INT'){
        $a= 'Double Play - BB';
    }
    if($ftth_data[3]== 'VOICE_IPTV'){
        $a= 'Double Play - PeoTV';
    }
    if($ftth_data[3]== ''){
        $a= '';
    }
    $_SESSION['pkg'] = $a;
    echo "<tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">FTTH SOD Details</span> </th></tr>";
        echo "<tr>
			<th>FTTH SOD</th>
            <td>$getftthcab[0]</td>
            <th>Service Type</th>
            <td>$getftthcab[1]</td>
			<th>Recieve Date</td>
            <td>$getftthcab[2]</td>
            </tr>
            
            <tr>
			<th>VOICE NO</th>
            <td>$getftthcab[3]</td>
            <th>RTOM</th>
            <td>$getftthcab[4]</td>
			<th>Oerder Type</td>
            <td>$getftthcab[5]</td>
            </tr>
            
            <tr>
            <th>Customer Name</td>
            <td>$getftthcab[6]</td>
			<th>Customer Contact NO</td>
			<td>$getftthcab[7]</td>
            <th>SOD Status</td>
            <td ><select style=\"border-radius:5px;width:170;height:25px; color:#1C96B1;\"  onchange=\"test()\" class=\"txtbg\" id=\"ch_status\">
			<option value=\"ASSIGNED\">ASSIGNED</option>
			 </select></td>
            </tr>
            
            <tr>
			<th>Service Address</th>
            <td colspan=\"5\">$getftthcab[8]</td>
            </tr>
            
            <tr>
			<th>FTTH Package</th>
            <td>$a</td>
            <th>Phone Class</th>
            <td>$getftthcab[11]</td>
			<th>Phone Purchase from SLT</td>
            <td>$getftthcab[13]</td>
            </tr>
            
            <tr>
			<th>DP Loop </th>
            <td>$getftthcab[9] - $getftthcab[10]</td>
            <th>Description</th>
            <td colspan=\"3\">$getftthcab[14]</td>
            </tr>";
            
         if($a == 'Triple Play' or $a == 'Double Play - PeoTV') {    
            echo "<tr style=\"height:30px;\"> </tr>
            <tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">IPTV SOD Details</span></th></tr>
            <tr>
			<th>IPTV SOD</th>
            <td>$getiptv[0]</td>
            <th>Service Type</th>
            <td>$getiptv[1]</td>
			<th>Recieve Date</td>
            <td>$getiptv[2]</td>
            </tr>
            
            <tr>
			<th>IPTV NO</th>
            <td>$getiptv[3]</td>
            <th>RTOM</th>
            <td>$getiptv[4]</td>
			<th>Order Type</td>
            <td>$getiptv[5]</td>
            </tr>
            
            <tr>
			<th>MSAN Location</th>
            <td>$getiptv[7] - $getiptv[6]</td>
            <th>CARD PORT</th>
            <td>$getiptv[8] - $getiptv[9]</td>
			<th>ADSL Router Class</td>
            <td>$getiptv[10]</td>
            </tr>
            ";   }
}
   
   echo "</table> </div>";
    
    if($getsod== 'AB-CAB' || $getsod== 'AB-FTTH'){
    echo "<tr>
			<th colspan =\"3\"></th>
            <td><input type=\"submit\" class=\"btn btn-primary\" name=\"submit\" value=\"Assign\"></td>
            </tr>";
     } 
     
     
      
            
     }     
     
   
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
