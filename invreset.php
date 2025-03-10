<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
     $level = $_SESSION["level"];
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}

$pkg = $_SESSION["pkg"];
include "db.php";
include "GetDays.php";




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

 <style>
 img:hover{
 cursor: pointer;
}
 </style>
 
      <script>


function search() {
    var val = document.getElementById("cir").value;
	self.location='invreset.php?cir=' + val ;
}


function deleteval(val){
	
	  var r = confirm("Are you sure you want to update ");
    if (r == true) {  
      var q = 'deleteinv';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"&inv="+val+"&q="+q,
             success:function(data){
                    if(data == "success"){
                        
                        
                        alert("Invoice Reset Successful");
                        self.location='invreset.php' ;
                    }else{
                       // alert("Please try again or contact support team");
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
          <div class="col-sm-11">
            <h3>Invoice Reset</h3>

        </div>
        <div class="col-sm-1">
            <h6><a href="CO_SO_List"><img src="img/back.jpg" width="35" alt="back" height="35"></a></h6>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />
        <div class="row">
            <div class="col-sm-2"><span style="font-size:20px;">INVOICE NO</span></div>
            <div class="col-sm-3"><input type="text" id="cir" style="border-radius: 5px; width: 250px; height: 35px;"  class="form-control" value="<?php echo $_GET['cir']; ?>" /></div>
            <div class="col-sm-2 "><input type="submit" class="btn btn-primary"  style="height: 35px; "  onclick="search()" value="Search" />  </div><br/>
          
         </div>
<br /><br />
<?php
if(isset($_GET['cir']))
{

$cir=$_GET['cir'];

$inv_list = oci_fetch_array(co_inv_listsltchk($cir));

if($inv_list != ""){
	
	echo"	 <div id=\"page-wrapper\">
	

			
			
	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table id=\"simple-table\" style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                                    <thead>
                                        <tr style=\"font-weight:bold\">
                                            <th>Invoice No</th>
											<th>Service Type</th>
                                            <th>Invoice Date</th>
											<th>RTOM Area</th>
											<th>Contractor</th>
											<th></th>
                                        </tr>
                                    </thead>
                                    <tbody>		
			
			
			<tr>
            <td>$inv_list[4]</td>
            <td>$inv_list[6]</td>
    		<td>$inv_list[5]</td>
            <td>$inv_list[7]</td>
			<td>$inv_list[0]</td>
			<td><img src=\"img/del.png\"  width=\"25\" height=\"25\" onclick=\"deleteval('$inv_list[4]')\"/> </td>
           
        
        </tr>";
			
			
			
}else{
	
	echo "<h4 style=\"color:red\">Incorrect Invocie No or not exist. Pls check again.</h4>";
}
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
