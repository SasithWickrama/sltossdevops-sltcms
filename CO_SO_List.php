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
  
<script >
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
        </script>  
  
<script>
function search() {
    var val = document.getElementById("rt").value;
	self.location='CO_SO_List.php?rt=' + val ;
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
          <div class="col-sm-6">
            <h4 class="m-0 text-dark">Pending Service Orders</h4>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />

         			<table cellpadding="10">
<tr>
<td style="font-size: 15px">RTOM AREA</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php
$area = con_area();

    echo "<td ><select style=\"border-radius:5px; width:150px;height:35px;text-align: left\" name=\"rt\" class=\"form-control\" id=\"rt\">";
    echo "<option value=\"\"></option>";
    while($row=oci_fetch_array($area))

    {
        echo "<option value=\"$row[0]\">$row[0]</option>";
    }
    
    echo "</select><td >";

?>
<td style="width: 10px;"></td>
<td><input type="submit" class="btn btn-primary"  style="height: 35px; " name="submit" onclick="search()" value="Select"></td>
</tr>
</table>
<br />

<?php
if(isset($_GET['rt']))
{
$rt=$_GET['rt']; 

 $refno=$rt.'_'.$user.'_'.date("ymdHis");

 

$so_list = SO_List($contractor_name,$rt);

echo"	<div class=\"row\">
                <!-- /.col-lg-12 -->
                <div class=\"col-sm-11\"></div>
                <div class=\"col-sm-1\">
                <div class=\"form-group\">
                 <a href=\"dashreports/{$refno}_pendingso.csv\" download><img src=\"dist/img/Excel.ico\" width=\"30\" height=\"30\"/>
                  <label >EXCEL</label></a>

                  
                </div>
                
                </div>
            </div>
            
            
          	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table id=\"simple-table\" style=\"font-size:11.6px;\" class=\"table table-striped table-bordered table-hover\">
                                    <thead>
                                        <tr style=\"font-weight:bold\">
                                            <th>SOD NO</th>
											<th>Circuit NO</th>
                                            <th>Received Date</th>
                                            <th>Service Type</th>
											<th>Order Type</th>
                                            <th>Area</th>
                                            <th>Task Name</th>
                                            <th>Package</th>
											<th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>		
			
			
			
			
			
			";
        $i=1;
        
     $HEADER = "Service Order NO,Received Date,Circuit NO,RTOM, LEA,SERVICE TYPE, ORDER TYPE,TASK NAME, Customer Name,Customer Contact NO, Customer Address, ONT Serial NO,FTTH Package,MEGALINE Package, DP , loop ,MASN, INDEX, CARD, PORT, PHONE CLASS,DESCRIPTION, PHONE PURCHASE FROM SLT, ADSL ROUTER CLASS  \n";    
    while($row=oci_fetch_array($so_list))
    {
		
            $getexcel =getexcel($row[0]);
            
            $HEADER = $HEADER . "{$getexcel[0]},{$getexcel[1]},{$getexcel[2]},{$getexcel[3]},{$getexcel[4]},{$getexcel[5]},{$getexcel[6]},{$getexcel[7]},{$getexcel[8]},{$getexcel[9]},{$getexcel[10]},{$getexcel[11]},{$getexcel[12]},{$getexcel[13]},{$getexcel[14]},{$getexcel[15]},{$getexcel[16]},{$getexcel[17]},{$getexcel[18]},{$getexcel[19]},{$getexcel[20]},{$getexcel[21]},{$getexcel[22]}\n";	

    if($row[8]== 'VOICE_INT_IPTV'){
        $a= 'Triple Play';
    }
    if($row[8]== 'VOICE_INT'){
        $a= 'Double Play - BB';
    }
    if($row[8]== 'VOICE_IPTV'){
        $a= 'Double Play - PeoTV';
    }
    if($row[8]== 'Triple Play'){
        $a= 'Triple Play';
    }
    if($row[8]== 'Double Play - PeoTV'){
        $a= 'Double Play - PeoTV';
    }
    if($row[8]== 'Double Play-BB with modem' || $row[8]== 'Double Play-BB without modem'){
        $a= 'Double Play - BB';
    }
    if($row[8]== 'Single Play'){
        $a= 'Single Play';
    }
    
    if($row[8]== 'VOICE_IPTV'){
        $a= 'Double Play - PeoTV';
    }
    if($row[8]== ''){
        $a= '';
    }

        
        
        if($row[8] == '1')
			{
				$get_appoint = get_appoint($row[0]);
				$apint=oci_fetch_array($get_appoint);
			}
		else
			{
				$apint[0] = "";
				$apint[1] = "";
				$apint[2] = "";
			}
			
	if($row[3] == 'INPROGRESS')
	{
    echo "<tr>";
			if($row[8] == '1')
			{
            echo"<td><a href=\"CO_SO_Details2.php?id=$row[0]\" target=\"_blank\"><u>$row[0]</u></a></td>";
			}
			else
			{
			echo"<td><a href=\"CO_SO_Details1.php?id=$row[0]\" target=\"_blank\">$row[0]</a></td>";
			}
         echo"<td>$row[1]</td>
			<td>$row[2]</td>
            <td>$row[4]</td>
			<td>$row[5]</td>
            <td>$row[6]</td>
			<td>$row[7]</td>
            <td>$a</td>
			<td style=\"background: #FFBF00;\">$row[3]</td>
            </tr>
          ";
	}
	
	if($row[3] == 'DELAYED')
	{
    echo "<tr>";
			if($row[8] == '1')
			{
            echo"<td><a href=\"CO_SO_Details2.php?id=$row[0]\" target=\"_blank\"><u>$row[0]</u></a></td>";
			}
			else
			{
			echo"<td><a href=\"CO_SO_Details1.php?id=$row[0]\" target=\"_blank\"><u>$row[0]</u></a></td>";
			}
         echo"   <td>$row[1]</td>
            <td>$row[2]</td>
			<td>$row[4]</td>
			<td>$row[5]</td>
            <td>$row[6]</td>
			<td>$row[7]</td>
            <td>$a</td>
			<td style=\"background: #FF0000;\">$row[3]</td>
            </tr>
          ";
	}
	
		if($row[3] == 'ASSIGNED')
	{
    echo "<tr>
            <td><a href=\"CO_SO_Con_Status.php?id=$row[0]& rdate=$row[3]\" target=\"_blank\"><u>$row[0]</u></a></td>
			<td>$row[1]</td>
            <td>$row[2]</td>
			<td>$row[4]</td>
            <td>$row[5]</td>
            <td>$row[6]</td>
			<td>$row[7]</td>
            <td>$a</td>
			<td style=\"background: #04B431;\">$row[3]</td>
            </tr>
          ";
	}
	
	if($row[3] == 'REASSIGNED')
	{
    echo "<tr>";
			if($row[8] == '1')
			{
            echo"<td><a href=\"CO_SO_Details2.php?id=$row[0]\" target=\"_blank\">$row[0]</a></td>";
			}
			else
			{
			echo"<td><a href=\"CO_SO_Details1.php?id=$row[0]\" target=\"_blank\">$row[0]</a></td>";
			}
         echo"   <td>$row[1]</td>
            <td>$row[2]</td>
			<td>$row[5]</td>
			<td>$row[6]</td>
            <td>$row[9]</td>
			<td>$row[7]</td>
            <td>$a</td>
			<td style=\"background: #FC8EAC;\">$row[3]</td>
            </tr>
          ";
	}
    echo "</tr>";
	
    }
    echo "</table> </div>";
    
    $File = "dashreports/{$refno}_pendingso.csv";
		$FILE_WRITE = fopen($File, 'w') or die("can't open file");
		fwrite($FILE_WRITE, $HEADER);
		fclose($FILE_WRITE);
}
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
