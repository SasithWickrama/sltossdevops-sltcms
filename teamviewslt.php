<?php
ini_set('max_execution_time', 300);
session_start();
include "db.php";
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

<style>
#img1:hover {
    cursor: pointer;
}
</style>

   <script>
  	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"datefield",
			dateFormat:"%m/%d/%Y",
			limitToToday:true
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
	var val2 = document.getElementById("datefield").value;
	
	val = val1+'_'+val2;
	self.location='teamviewslt.php?rt=' + val ;
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
            <h4 class="m-0 text-dark">Team View</h4>

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

<?php
$area = con_area();

    echo "<td ><select style=\"border-radius:5px; width:150px;height:30px;text-align: left\" name=\"rt\" class=\"form-control\" id=\"rt\">";
    echo "<option value=\"\"></option>";
if($temp[0] == 'SLTQC')   {
   while($row=oci_fetch_array($area))

    {
        echo "<option value=\"$row[0]\">$row[0]</option>";
    }
}else{
        $i=0;
	    while($n > $i)
		{
			echo "<option value=\"$temp[$i]\">$temp[$i]</option>";
			$i++;
		}
}    
    
    
    echo "</select></td >";

?>
</tr>
<tr style="height:5px;">  </tr >
<tr>
    <td >DATE * </td><td> <input type="text"  name="dateraise" id="datefield" style="width:150px; height: 30px;" class="form-control" required=""/></td>
<td style="width: 20px;"></td>

	<td style="width: 20px;"></td>

<td><input type="submit" class="btn btn-primary"  style="height: 30px; " name="submit" onclick="search()" value="Add"></td>

    
 
    
    
     </tr > 
</table>
<br />
<br />
<br />

<?php

echo"	<div class=\"row\">
                <!-- /.col-lg-12 -->
                                <div class=\"col-sm-11\"></div>
                <div class=\"col-sm-1\">
                <div class=\"form-group\">
                 <a href=\"dashreports/{$refno}_team.csv\" download><img src=\"dist/img/Excel.ico\" width=\"30\" height=\"30\"/>
                  <label >EXCEL</label></a>

                  
                </div>
                
                </div>
            </div>
            
            
          	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table id=\"simple-table\" style=\"font-size:14px;  class=\"table table-striped table-bordered table-hover\">
                                    <thead>
                                        <tr style=\"font-weight:bold\">
                                            <th>RTOM</th>
											<th>CONTRACTOR</th>
                                            <th>SERVICE TYPE</th>
                                            <th>DATE</th>
											<th>TEAM COUNT</th>
											<th>USER</th>
											<th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>";  


if(isset($_GET['rt']))
{
$HEADER = "RTOM,CONTRACTOR,SERVICE TYPE ,DATE,TEAM COUNT  \n";    
    
$rt=$_GET['rt']; 

$temp2 = explode('_',$rt);

$getteam = getteamrtscslt($temp2[0],$temp2[1]);
while($row=oci_fetch_array($getteam))
			{		 
				$HEADER = $HEADER . "{$row[0]},{$row[1]},{$row[3]},{$row[2]},{$row[4]}\n";	
				
				echo "
				<tr>
				<td>$row[0]</td>
				<td>$row[1]</td>
				<td>$row[3]</td>
				<td>$row[2]</td>
				<td>$row[4]</td>
				<td>$row[5]</td>
				<td>CONFIRMED</td>";
				
				echo"</tr>";
			
			}



}else{						
	$HEADER = "RTOM,CONTRACTOR,SERVICE TYPE ,DATE,TEAM COUNT  \n";								
		if($temp[0] == 'SLTQC')   {

			$getteam = getteamrtslt('SLTQC');
			while($row=oci_fetch_array($getteam))
			{		 
				
				$HEADER = $HEADER . "{$row[0]},{$row[1]},{$row[3]},{$row[2]},{$row[4]}\n";	
				echo "
				<tr>
				<td>$row[0]</td>
				<td>$row[1]</td>
				<td>$row[3]</td>
				<td>$row[2]</td>
				<td>$row[4]</td>
				<td>$row[5]</td>";
				if($row[6] == '0'){
						echo "<td>PENDING</td>";
					}else{
						echo "<td>CONFIRMED</td>";
					}

				echo"</tr>";
			
			}
		
	}else{
			$i=0;
			while($n > $i)
			{
				$getteam = getteamrtslt($temp[$i]);
				while($row=oci_fetch_array($getteam))
				{		 
				
				$HEADER = $HEADER . "{$row[0]},{$row[1]},{$row[3]},{$row[2]},{$row[4]}\n";	
				echo "
					<tr>
					<td>$row[0]</td>
					<td>$row[1]</td>
					<td>$row[3]</td>
					<td>$row[2]</td>
					<td>$row[4]</td>
					<td>$row[5]</td>";
					if($row[6] == '0'){
						echo "<td>PENDING</td>";
					}else{
						echo "<td>CONFIRMED</td>";
					}
				
				echo"</tr>";
				
				}
			}
	} 

}								
    
    echo "</table> </div>";

	$File = "dashreports/{$refno}_team.csv";
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
