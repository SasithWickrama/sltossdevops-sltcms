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
			target:"inputField",
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
function delval(val,val2,val3,val4){
    
    var r = confirm("Are you sure you want to Delete ");
    if (r == true) {
          var q = 'delteam';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"&area="+val+"&con="+val2+"&tdate="+val3+"&q="+q+"&stype="+val4,
             success:function(data){
                
                    if(data == "success"){
                        
                        
                        alert("Team Delete Successful");
                        location.reload();
                    }else{
                        alert(data);
                        location.reload();
                    }
     
               
                }
          });
        }else {
    location.reload();
    return false;
    } 
}

function Add(){
    
    
    var r = confirm("Are you sure you want to Add ");
    if (r == true) {
        var rt = document.getElementById("rt").value;
        var con = <?php echo(json_encode($contractor_name)); ?>;
        var stype = document.getElementById("stype").value;
        var steam = document.getElementById("steam").value;


          var q = 'addteam';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"rt="+rt+"&con="+con+"&stype="+stype+"&steam="+steam+"&q="+q,
             success:function(data){
				 
                    if(data == "success"){
                        
                        alert("Team Add Successful");
                       location.reload();
                        
                    }else{
                        alert(data);
                        location.reload();
                    }
                
                }
          });
        
        
    }else {
    location.reload();
    return false;
    } 
}


function confirmval(val,val2,val3,val4){
    
    var r = confirm("Are you sure you want to Confirm ");
    if (r == true) {
          var q = 'confirmteam';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"&area="+val+"&con="+val2+"&tdate="+val3+"&q="+q+"&stype="+val4,
             success:function(data){
                
                    if(data == "success"){
                        
                        
                        alert("Team Confirm Successful");
                        location.reload();
                    }else{
                        alert(data);
                        location.reload();
                    }
     
               
                }
          });
        }else {
    location.reload();
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
            <h4 class="m-0 text-dark">Team Assign</h4>

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
if($temp[0] == 'ALL')   {
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
    <td >DATE * </td><td> <input type="text" value="<?php date_default_timezone_set("Asia/Colombo");  echo date('Y-m-d'); ?>" name="dateraise" id="datefield" style="width:150px; height: 30px;" class="form-control" disabled=""/></td>
<td style="width: 20px;"></td>
<td >TEAM COUNT * </td>
<td> <select style="border-radius:5px; width:150px;height:30px;text-align: left" id="steam" >
        
        <?php for($i=1;$i<21;$i++){
			echo "<option value=\"$i\">$i</option>";
		}?>
</select></td>
	<td style="width: 20px;"></td>

<td><input type="submit" class="btn btn-primary"  style="height: 30px; " name="submit" onclick="Add()" value="Add"></td>

    
 
    
    
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
                
                </div>
            </div>
            
            
          	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table id=\"simple-table\" style=\"font-size:14px;  class=\"table table-striped table-bordered table-hover\">
                                    <thead>
                                        <tr style=\"font-weight:bold\">
                                            <th>RTOM</th>
                                            <th>SERVICE TYPE</th>
                                            <th>DATE</th>
											<th>TEAM COUNT</th>
											<th>USER</th>
											<th></th>
											<th></th>
                                        </tr>
                                    </thead>
                                    <tbody>";  


		if($temp[0] == 'ALL')   {

			$getteam = getteamrt('ALL',$contractor_name);
			while($row=oci_fetch_array($getteam))
			{		 
				echo "
				<tr>
				<td>$row[0]</td>
				<td>$row[3]</td>
				<td>$row[2]</td>
				<td>$row[4]</td>
				<td>$row[5]</td>";
			if($row[6] == '0'){
				echo"<td><img src=\"img/confirm.png\" id=\"img1\" data-toggle=\"tooltip\" title=\"Confirm\"  width=\"25\" height=\"25\" onclick=\"confirmval('$row[0]','$row[1]','$row[7]','$row[3]')\"/></td>
				<td><img src=\"img/del.png\" id=\"img1\"  width=\"25\" height=\"25\" onclick=\"delval('$row[0]','$row[1]','$row[7]','$row[3]')\"/></td>";
			}else{
				echo"<td><span style=\"color:green;font-weight:bold\">Confrimed</span></td> <td></td>";
			}
				
				echo"</tr>";
			
			}
		
	}else{
			$i=0;
			while($n > $i)
			{
				$getteam = getteamrt($temp[$i],$contractor_name);
				while($row=oci_fetch_array($getteam))
				{		 
					echo "
					<tr>
					<td>$row[0]</td>
					<td>$row[3]</td>
					<td>$row[2]</td>
					<td>$row[4]</td>
					<td>$row[5]</td>";
			if($row[6] == '0'){
				echo"<td><img src=\"img/confirm.png\" id=\"img1\" data-toggle=\"tooltip\" title=\"Confirm\"  width=\"25\" height=\"25\" onclick=\"confirmval('$row[0]','$row[1]','$row[7]','$row[3]')\"/></td>
				<td><img src=\"img/del.png\" id=\"img1\"  width=\"25\" height=\"25\" onclick=\"delval('$row[0]','$row[1]','$row[7]','$row[3]')\"/></td>";
			}else{
				echo"<td colspan=\"2\"> <span style=\"color:green;font-weight:bold\">Confrimed</span></td><td></td>";
			}
				
				echo"</tr>";
				
				}
				$i++;
			}
	} 

									
    
    echo "</table> </div>";

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
