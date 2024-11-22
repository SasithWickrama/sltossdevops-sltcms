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
  
<style>
#img1:hover {
    cursor: pointer;
}


div#success {
    text-align: center;
    box-shadow: 1px 1px 5px #455644;
    background: #bae8ba;
    padding: 10px;
    border-radius: 3px;
    margin: 0 auto;
    width: 350px;
}

.inputBox {
    width: 100%;
     height: 10px;
    margin: 5px 0px 15px 0px;
    border: #dedede 1px solid;
    box-sizing: border-box;
    padding: 15px;
}

#contact-popup {
    position: absolute;
    top: 0px;
    left: 0px;
    height: 100%;
    width: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    color: #676767;
}

#contact-up {
    position: absolute;
    top: 0px;
    left: 0px;
    height: 100%;
    width: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    color: #676767;
}

.contact-form {
    width: 450px;
    margin: 0px;
    background-color: white;
    font-family: Arial;
    position: relative;
    left: 50%;
    top: 50%;
    margin-left: -210px;
    margin-top: -255px;
    box-shadow: 1px 1px 5px #444444;
    padding: 20px 40px 40px 40px;
}

#contact-icon {
    padding: 10px 5px 5px 12px;
    width: 58px;
    color: white;
    box-shadow: 1px 1px 5px grey;
    border-radius: 3px;
    cursor: pointer;
    margin: 60px auto;
}

.info {
    color: #d30a0a;
    letter-spacing: 2px;
    padding-left: 5px;
}

#send {
    background-color: #09F;
    border: 1px solid #1398f1;
    font-family: Arial;
    color: white;
    width: 100%;
    padding: 10px;
    cursor: pointer;
}

#contact-popup h1 {
    font-weight: normal;
    text-align: center;
    margin: 10px 0px 20px 0px;
}

.input-error {
    border: #e66262 1px solid;
}
</style>
<script>
function closeform(){
    $("#contact-popup").hide();
}

function closeform1(){
    $("#contact-up").hide();
}

function popup(){
        $("#contact-popup").show();
}



function updateval(val,val2,val3, val4,val5){
        
        document.getElementById("upcon").value = val;
        document.getElementById("upname").value = val2;
        document.getElementById("upmob").value = val3;
        document.getElementById("uparea").value = val4;
        document.getElementById("upsup").value = val5;
        
        $("#contact-up").show();
}

function delval(val,val2,val3){
    
    var r = confirm("Are you sure you want to Delete ");
    if (r == true) {
          var q = 'deluser';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"&con="+val+"&uname="+val2+"&mob="+val3+"&q="+q,
             success:function(data){
                
                    if(data == "success"){
                        
                        
                        alert("User Delete Successful");
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

function add(){
    
    
    var r = confirm("Are you sure you want to Add ");
    if (r == true) {
        var con = document.getElementById("con").value;
        var uname = document.getElementById("userName").value;
        var nam = document.getElementById("name").value;
        var mob = document.getElementById("mobile").value;
        var mail = document.getElementById("mail").value;
        var area = document.getElementById("area").value;
        var sup = document.getElementById("sup").value;

          var q = 'addconuser';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"con="+con+"&uname="+uname+"&nam="+nam+"&mob="+mob+"&mail="+mail+"&area="+area+"&sup="+sup+"&q="+q,
             success:function(data){
                    if(data == "success"){
                        
                        alert("User Add Successful");
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


function update(){
    
    
    var r = confirm("Are you sure you want to Update ");
    if (r == true) {
        var con = document.getElementById("upcon").value;
        var uname = document.getElementById("upname").value;
        var mob = document.getElementById("upmob").value;
        var area = document.getElementById("uparea").value;
        var sup = document.getElementById("upsup").value;

          var q = 'upconuser';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"con="+con+"&uname="+uname+"&mob="+mob+"&area="+area+"&sup="+sup+"&q="+q,
             success:function(data){
                    if(data == "success"){
                        
                        alert("User Detail Update Successful");
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

  
<script >
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
        </script>  
  
<script>
function search() {
    var val = document.getElementById("rt").value;
	self.location='con_new.php?rt=' + val ;
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
            <h4 class="m-0 text-dark">Contractor List</h4>

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
<td style="font-size: 15px">CONTRACTOR</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php
$con_que = con_contractor_name();

    echo "<td ><select style=\"border-radius:5px; width:150px;height:35px;text-align: left\" name=\"rt\" class=\"form-control\" id=\"rt\">";
    echo "<option value=\"\"></option>";
    while($row=oci_fetch_array($con_que))

    {
        echo "<option value=\"$row[0]\">$row[0]</option>";
    }
    
    echo "</select><td >";

?>
<td style="width: 10px;"></td>
<td><input type="submit" class="btn btn-primary"  style="height: 35px; " name="submit" onclick="search()" value="Select"></td>

<td><input type="submit" class="btn btn-primary"  style="height: 35px; " name="submit" onclick="popup()" value="Add"></td>

</tr>
</table>
<br />

<?php
if(isset($_GET['rt']))
{

    
$rt=$_GET['rt']; 
$contractor = con_cont($rt);;

echo"	<div class=\"row\">
                <!-- /.col-lg-12 -->
                <div class=\"col-sm-11\"></div>
                <div class=\"col-sm-1\">

                
                </div>
            </div>
            
            
          	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table id=\"simple-table\" style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                                    <thead>
                                        <tr style=\"font-weight:bold\">
                                            <th>Contractor Name</th>
                                            <th>User Name</th>
                                            <th>Name</th>
                                            <th>Email Address</th>
											<th>Mobile No</th>
											<th>Area</th>
                                            <th>Privilege</th>
                                            <th>Update</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>		
			
			
			
			
			
			";
        $i=1;
        
         
    while($row=oci_fetch_array($contractor))
    {
            
        
        echo "
        <tr>
        <td>$row[0]</td>
        <td>$row[1]</td>
        <td>$row[2]</td>
        <td>$row[3]</td>
        <td>$row[4]</td>
        <td>$row[5]</td>";
        if($row[6]== '2'){
            $aa= 'User' ;         
        }
        if($row[6]== '5'){
            $aa= 'Approver' ;         
        }
        echo"<td>$aa</td>
        <td><img src=\"img/edit.png\" id=\"img1\"  width=\"25\" height=\"25\" onclick=\"updateval('$row[0]','$row[1]','$row[4]','$row[5]','$row[6]')\"/></div>
        <td><img src=\"img/del.png\" id=\"img1\"  width=\"25\" height=\"25\" onclick=\"delval('$row[0]','$row[1]','$row[4]')\"/></div>   ";
	
    }
    echo "</table> </div>";

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


<!--Contact Form-->
    <div id="contact-popup">
        <form class="contact-form" action="" id="contact-form"
            method="post" enctype="multipart/form-data">
            <h4>Add New User</h4>
            <div>
                <table>
                <tr>
                <td>Contractor</td>
                <td><?php
                $con_que = con_contractor_name();
                
                echo "<select style=\"border-radius:5px; width:250px;height:35px;text-align: left\" name=\"con\" class=\"form-control\" id=\"con\">";
                    echo "<option value=\"\"></option>";
                    while($row=oci_fetch_array($con_que))
                
                    {
                        echo "<option value=\"$row[0]\">$row[0]</option>";
                    }
                    
                    echo "</select>";

?></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>User Name</td>
                <td><input type="text" id="userName" name="userName" style="border-radius:5px; width:250px;height:35px;text-align: left" required=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>Name</td>
                <td><input type="text" id="name" name="name" style="border-radius:5px; width:250px;height:35px;text-align: left" required=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>Mobile No</td>
                <td><input type="number" id="mobile" name="mobile" style="border-radius:5px; width:250px;height:35px;text-align: left" maxlength="10" required=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>Email</td>
                <td><input type="text" id="mail" name="mail" style="border-radius:5px; width:250px;height:35px;text-align: left"  required=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>Area</td>
                <td><input type="text" id="area" name="area" style="border-radius:5px; width:250px;height:35px;text-align: left"  required=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>User Name</td>
                <td><select id="sup" name="sup" style="border-radius:5px; width:250px;height:35px;text-align: left"  required=""/>
                <option value="2">USER</option>
                <option value="5">APPROVER</option>
                <option value="9">SLT</option>
                </select></td>
                </tr>
                <tr style="height: 25px;"></tr>
                <tr>
                <td><input type="submit" class="btn btn-primary"  style="height: 35px; " name="submit" onclick="add()" value="Add"></td></td>
                <td><input type="button" class="btn btn-primary"  style="height: 35px; " name="submit" onclick="closeform()" value="Close"></td></td>
                
                </tr>
                </table>
            
        
            </div>
        </form>
    </div>
    
    
        <div id="contact-up">
        <form class="contact-form" action="" id="contact-form"
            method="post" enctype="multipart/form-data">
            <h4>Update User</h4>
            <div>
                <table>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>Contractor</td>
                <td><input type="text" id="upcon" name="upcon" value="" style="border-radius:5px; width:250px;height:35px;text-align: left" disabled=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>User Name</td>
                <td><input type="text" id="upname" name="upname" style="border-radius:5px; width:250px;height:35px;text-align: left" disabled=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>Mobile No</td>
                <td><input type="number" id="upmob" name="upmob" style="border-radius:5px; width:250px;height:35px;text-align: left" maxlength="10" required=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>Area</td>
                <td><input type="text" id="uparea" name="uparea" style="border-radius:5px; width:250px;height:35px;text-align: left"  required=""/></td>
                </tr>
                <tr style="height: 15px;"></tr>
                <tr>
                <td>User Name</td>
                <td><select id="upsup" name="upsup" style="border-radius:5px; width:250px;height:35px;text-align: left"  required=""/>
                <option value="2">USER</option>
                <option value="5">APPROVER</option>
                <option value="9">SLT</option>
                </select></td>
                </tr>
                <tr style="height: 25px;"></tr>
                <tr>
                <td><input type="submit" class="btn btn-primary"  style="height: 35px; " name="submit" onclick="update()" value="Update"></td></td>
                <td><input type="button" class="btn btn-primary"  style="height: 35px; " name="submit" onclick="closeform1()" value="Close"></td></td>
                
                </tr>
                </table>
            
        
            </div>
        </form>
    </div>
    

</body>
</html>
