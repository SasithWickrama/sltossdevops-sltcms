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
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField",
			dateFormat:"%m/%d/%Y"
		});
                new JsDatePick({
			useMode:2,
			target:"inputField1",
			dateFormat:"%m/%d/%Y"
		});
	};
 </script> 
  
<script >
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
        </script>  
  
<script>
function search() {
    var val1 = document.getElementById("rt").value;
    var val2 = document.getElementById("inputField").value;
    var val3 = document.getElementById("inputField1").value;
    var val4 = document.getElementById("stype").value;

    val = val1+'_'+val2+'_'+val3+'_'+val4;
	self.location='slt_com.php?rt=' + val ;
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
            <h4 class="m-0 text-dark">Completed Service Orders</h4>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />

         			<table cellpadding="10" >
<tr>
<td style="font-size: 14px">RTOM AREA</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php
$area = con_area();

    echo "<td ><select style=\"border-radius:5px; width:150px;height:30px;text-align: left\" name=\"rt\" class=\"form-control\" id=\"rt\">";
    echo "<option value=\"\"></option>";
   /* while($row=oci_fetch_array($area))

    {
        echo "<option value=\"$row[0]\">$row[0]</option>";
    }*/
    
        $i=0;
	    while($n > $i)
		{
			echo "<option value=\"$temp[$i]\">$temp[$i]</option>";
			$i++;
		}
    
    
    
    echo "</select><td >";

    
?>
<td style="width: 20px;"></td>
<td >SERVICE TYPE * </td>
<td> <select style="border-radius:5px; width:150px;height:30px;text-align: left" id="stype" >
        <option value=""></option>
        <option value="AB-CAB">AB-CAB</option>
        <option value="AB-FTTH">AB-FTTH</option>
        <option value="E-IPTV COPPER">E-IPTV COPPER</option>
        <option value="E-IPTV FTTH">E-IPTV FTTH</option>
</select></td>
</tr>
<tr style="height:5px;">  </tr >
<tr>
    <td >FROM DATE * </td><td>&nbsp;&nbsp;&nbsp;</td><td><input type="text" class="form-control"  style="border-radius:5px; width:150px;height:30px;text-align: left" id="inputField" /></td>
<td style="width: 20px;"></td>
    <td > </td><td>TO DATE *</td><td><input type="text"  class="form-control" style="border-radius:5px; width:150px;height:30px;text-align: left" id="inputField1" /></td>
    <td style="width: 5px;"></td>

<td><input type="submit" class="btn btn-primary"  style="height: 30px; " name="submit" onclick="search()" value="Select"></td>

    
 
    
    
     </tr > 
</table>
<br />

<?php
if(isset($_GET['rt']))
{

    
$rt=$_GET['rt']; 

	$temp2 = explode('_',$rt);
    


$so_list = comso_slt($temp2[0],$temp2[1],$temp2[2],$temp2[3]);

echo"	<div class=\"row\">
                <!-- /.col-lg-12 -->
                <div class=\"col-sm-11\"></div>
                <div class=\"col-sm-1\">
                <div class=\"form-group\">
                 <a href=\"dashreports/{$temp2[0]}_com.csv\" download><img src=\"dist/img/Excel.ico\" width=\"30\" height=\"30\"/>
                  <label >EXCEL</label></a>

                  
                </div>
                
                </div>
            </div>
            
            
          	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table id=\"simple-table\" style=\"font-size:12px; width:1500px\" class=\"table table-striped table-bordered table-hover\">
                                    <thead>
                                        <tr style=\"font-weight:bold\">
                                            <th>RTOM</th>
                                            <th>LEA</th>
                                            <th>SOD NO</th>
											<th>Circuit NO</th>
                                            <th>Service Type</th>
											<th>Order Type</th>
                                            <th>Task Name</th>
                                            <th>Package</th>
                                            <th>Contractor</th>
                                            <th>Received Date</th>
                                            <th>Completed Date</th>
                                            <th>Status</th>
                                            <th>DP LOOP</th>
											<th>ONT SN</th>
                                        </tr>
                                    </thead>
                                    <tbody>		
			
			
			
			
			
			";
        $i=1;
        
     $HEADER = "RTOM,LEA,SOD NO,Circuit,Service Type,Order Type,Task Name,Package,Contractor,Received Date,Completed Date,Status,DP LOOP, ONT SN  \n";        
    while($row=oci_fetch_array($so_list))
    {
            
            $HEADER = $HEADER . "{$row[0]},{$row[1]},{$row[2]},{$row[3]},{$row[4]},{$row[5]},{$row[6]},{$row[7]},{$row[8]},{$row[11]},{$row[9]},{$row[10]},{$row[12]},{$row[13]}\n";	

    if($row[7]== 'VOICE_INT_IPTV'){
        $a= 'Triple Play';
    }
    if($row[7]== 'VOICE_INT'){
        $a= 'Double Play - BB';
    }
    if($row[7]== 'VOICE_IPTV'){
        $a= 'Double Play - PeoTV';
    }
    if($row[7]== 'Triple Play'){
        $a= 'Triple Play';
    }
    if($row[7]== 'Double Play - PeoTV'){
        $a= 'Double Play - PeoTV';
    }
    if($row[7]== 'Double Play-BB with modem' || $row[7]== 'Double Play-BB without modem'){
        $a= 'Double Play - BB';
    }
    if($row[7]== 'Single Play'){
        $a= 'Single Play';
    }
    
    if($row[7]== 'VOICE_IPTV'){
        $a= 'Double Play - PeoTV';
    }
    if($row[7]== ''){
        $a= '';
    }

        
        echo "
        <tr>
        <td>$row[0]</td>
        <td>$row[1]</td>
        <td>$row[2]</td>
        <td><a href=\"slt_con_detail.php?id=$row[2]\" target=\"_blank\">$row[3]</a></td>
        <td>$row[4]</td>
        <td>$row[5]</td>
        <td>$row[6]</td>
        <td>$a</td>
        <td>$row[8]</td>
        <td>$row[11]</td>
        <td>$row[9]</td>
        <td>$row[10]</td>
        <td>$row[12]</td>
		 <td>$row[13]</td>
        </tr>
        ";
	
    }
    echo "</table> </div>";
    
    $File = "dashreports/{$temp2[0]}_com.csv";
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
