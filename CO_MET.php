<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor = $_SESSION["contractor"];
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
  
  <script type="text/javascript">

function getval(){
    
    var rt = document.getElementById("area").value;
    var ser = document.getElementById("sertype").value;
    var dat = document.getElementById("inputField").value;
	var dat2 = document.getElementById("inputField2").value;
    
    if(rt != '' && ser !='' && dat !='' && dat2 !=''){
       
        val = rt+'_'+ser+'_'+dat+'_'+dat2;
        
        self.location='CO_MET.php?id=' + val ;
        
    }else{
        alert('Missing Value');
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
            <h4 class="m-0 text-dark">To be Invoice Meterial</h4>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 16px;">
        <div class="container-fluid">
<hr />    
      
    			<table border = "0">
	<tr>
    <td>Service Type</td><td>&nbsp;&nbsp;</td>
    <td ><select style="border-radius:5px;width:250;height:35px;text-align: left" name= "sertype" class="form-control" id="sertype" required="">";
    <option value=""></option>
    <option value="AB-CAB">AB-CAB</option>
	<option value="AB-FTTH">AB-FTTH</option>
    <option value="IPTV">PEO TV</option>
    </select>
    </tr>
	<tr style="height:10px;">  </tr >
    <tr>
        
    <?php  
    $area = con_area();
    echo "<tr>";
    echo "<td>RTOM Area</td><td>&nbsp;&nbsp;</td>";
    echo "<td ><select style=\"border-radius:5px;width:250;height:35px;text-align: left\" name= \"area\" class=\"form-control\" id=\"area\" required=\"\">";
    echo "<option value=\"\"></option>";
    $i=0;
    while($row =oci_fetch_array($area))
		{
			echo "<option value=\"$row[0]\">$row[0]</option>";
			
		}
    
    echo "</select>";
    echo "</tr>";
    ?>
  
   <tr style="height:10px;"></tr>   
   <tr >
    <td>Invoice Date From</td><td>&nbsp;&nbsp;</td>
    <td><input type="text" class="form-control" name="fromdate" id="inputField2" style="border-radius:5px; width:250px;height:35px;text-align: left" required=""></td>
   </tr>
   <tr style="height:10px;"></tr>   
   <tr >
    <td>Invoice Date up to</td><td>&nbsp;&nbsp;</td>
    <td><input type="text" class="form-control" name="todate" id="inputField" style="border-radius:5px; width:250px;height:35px;text-align: left" required=""></td>
   </tr>
     <tr style="height:10px;"></tr>   
    <tr><td></td><td>&nbsp;</td><td align ="right"><input type="button" class="btn btn-primary" onclick="getval()" name="submit" value="GET"></td>    
   </table>

<?php
if(isset($_GET['id']))
{
    $val= $_GET['id'];
    $temp = explode('_',$val);
    $HEADER = "VOCIE NO,DW-EW,FTTH-DW,EX-IPTV,PL-C-5.6-CE,PL-C-6.7,PL-C-7.5,PT-SP-VO-ID,PT-2P-VB-ID,PT-3P-BP-ID,FT-DP-VP-ID,FT-SP-PO-ID,PL-C-5.6-L,DW-DF,TL-WM-D-25,SC-C5,PT-SP-PO-ID,FT-DP-VB-ID,FT-DP-V3P-ID,FT-3P-B2P-ID,PL-C6.7CE,PL-C-9,PT-2P-VP-ID,FT-3P-BP-ID,PSTN-DW,DW-ER,FT-SP-VO-ID,FT-DP-V2P-ID,PL-C-5.6-H,PL-C-8,DW-LH,B-16,PLC-CON,PL-GI-50\n";



$comp = comp_count($temp[0],$contractor,$temp[1],$temp[2],$temp[3]);
$rwcount = oci_fetch_all($comp,$result);
$rowcount =oci_num_rows($comp);

 if($rowcount != '0')  { 
    $comp_count = comp_count($temp[0],$contractor,$temp[1],$temp[2],$temp[3]);
    while( $row = oci_fetch_array($comp_count) ){
        $getexcel= getmetlist($row[0]);
        
            while( $rows = oci_fetch_array($getexcel) ) {
            $HEADER = $HEADER . "{$rows[0]},{$rows[1]},{$rows[2]},{$rows[3]},{$rows[4]},{$rows[5]},{$rows[6]},{$rows[7]},{$rows[8]},{$rows[9]},{$rows[10]},{$rows[11]},{$rows[12]},{$rows[13]},{$rows[14]},{$rows[15]},{$rows[16]},{$rows[17]},{$rows[18]},{$rows[19]},{$rows[20]},{$rows[21]},{$rows[22]},{$rows[23]},{$rows[24]},{$rows[25]},{$rows[26]},{$rows[27]},{$rows[28]},{$rows[29]},{$rows[30]},{$rows[31]},{$rows[32]},{$rows[33]},{$rows[34]}\n";	

    }
    
        $File = "Invoice/{$temp[0]}/{$contractor}/{$temp[1]}_met.csv";
		$FILE_WRITE = fopen($File, 'w') or die("can't open file");
		fwrite($FILE_WRITE, $HEADER);
		fclose($FILE_WRITE);
    }
    
    
      echo"<div class=\"row\">
                <div class=\"col-lg-12\">
                    <h5 class=\"page-header\">Meterial Number Invoice</h5>
                    <hr/>
                </div>
                
                <!-- /.col-lg-12 -->
                
                
                <p><a href = \"Invoice/{$temp[0]}/{$contractor}/{$temp[1]}_met.csv\" download>$temp[0]-METERIAL</a></p>
                
            </div>          
 
            ";
    }
    else{
        
        echo "<script type=\"text/javascript\"> alert('No Data Found for Given Period');</script>"; 
        
    }
}
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
