<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	$temp = explode('/',$area);
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
  
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> 
	<link rel="stylesheet" href="assets/css/jquery.dataTables.min.css" />
	<script src="assets/js/jquery-2.1.4.min.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<script src="dist/js/adminlte.js"></script>
	<script src="dist/js/demo.js"></script>
	<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
	<script src="plugins/raphael/raphael.min.js"></script>
	<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
	<script src="plugins/jquery-mapael/maps/world_countries.min.js"></script>
	<script src="plugins/chart.js/Chart.min.js"></script>
	<link rel="stylesheet" href="js/jsDatePick_ltr.min.css" />
	<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

<div class="wrapper">

  <nav class="main-header navbar navbar-expand border-bottom navbar-dark navbar-primary">

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">

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

  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index" class="brand-link">
      <img src="dist/img/slt.jpg" alt="" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-size: 25px; font-weight: bolder;">SLTCMS</span>
    </a>
	
    <div class="sidebar">

        <?php include("navbar.php"); ?>

    </div>

  </aside>


  <div class="content-wrapper">
	
	<div class="col-lg-12">
		
		<h2></h2>
		
		<ol class="breadcrumb">
		
			<li class="breadcrumb-item">
				Home
			</li>
			
			<li class="breadcrumb-item">
				 OPMC Staff Inbox
			</li>
			
			<li class="breadcrumb-item">
				<strong>Add Group</strong>
			</li>
			
		</ol>
	</div>

    <!-- Main content -->

      <div class="container-fluid">

		<div class="card">	
           
		    <div class="card-body">
		            
				<div class="ibox-title">
					<h5><b>Add Group</b></h5>
					<hr/>
				</div>
		   
				<div class="ibox-content">
					
					<div class="form-group  row">
					
						<label class="col-sm-2 col-form-label" style="font-size:14px;" >Group Name</label>
						<div class="col-sm-6"><input type="text" style="font-size:13px;" id="grp_name" onkeypress="return IsAlphaNumeric(event);" class="form-control"></div>
						<span id="error" style="color: Red; display: none;font-size:13px;">* Special Characters not allowed.</span>
						
					</div>

					<div class="form-group row">
					
						<div class="col-md-2">&nbsp;</div>
						
						<div class="col-md-6">
						
							<button class="btn btn-primary btn-md col-md-4" style="font-size:14px;" onclick="saveData();"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
						
						</div>
						
					</div>
					
				</div>

			</div>	

        </div>

  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>

  <!-- Main Footer -->
  <footer class="main-footer" style="text-align: center;">
    <strong>Copyright &copy; 2019  <span style="color: red;">IT Solution & Devops.</span> </strong>
    All rights reserved.
  </footer>
  
</div>


<script type="text/javascript">
	
	//-------------- validate input box --------------//
     var specialKeys = new Array();
     specialKeys.push(8);  //Backspace
     specialKeys.push(9);  //Tab
     specialKeys.push(46); //Delete
     specialKeys.push(36); //Home
     specialKeys.push(35); //End
     specialKeys.push(37); //Left
     specialKeys.push(39); //Right
 
     function IsAlphaNumeric(e) {
         var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
         var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || keyCode == 32 || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
         document.getElementById("error").style.display = ret ? "none" : "inline";
         return ret;
     }
	 
	 //--------- end validate input box ---------------//
	 
	 
	 function saveData(){
		
		var group_name = document.getElementById('grp_name').value;
		
		if(group_name == ''){
			alert('Group Name Required');
			return false;
		}else{
			
			 $.ajax({
			 type: "POST",
			 data: {group_name:group_name},
			 url: "./function.php?r=22",
			 success: function(res){
				
				if(res == 'true'){
					
				alert("Successfully Created Group");

				setInterval(location.reload(), 5000);
				
				}

			  }
		    });
	   
		}
	   
	 }
	 
</script>



</body>
</html>
