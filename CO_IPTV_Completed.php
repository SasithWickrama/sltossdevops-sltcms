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
$so_id = $_GET["id"];
$pkg = $_SESSION["pkg"];
$con_type = "IPTV";

include "db.php";
include "GetDays.php";


$getcir = oci_fetch_array(getcircab($so_id));
$getcon = oci_fetch_array(getcon($so_id));

$cunt= '0';
$a= '';
$pl=0;

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
function checkForm(frm) {

if (frm.drop_wire.value == "" && frm.drop_wire_ser.value == "" && frm.drop_wire_acc.value == "") {
         alert("DROP WIRE Cannot be null");
         return false;     
     }
 }
 
function upcontyp(val){
    
    var r = confirm("Are you sure you want to update ");
    if (r == true) {
        var contype= document.getElementById("contype").value;
          var q = 'updatecon';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"sod="+val+"&contype="+contype+"&q="+q,
             success:function(data){
                    if(data == "success"){
                        
                        alert("Connection type update Successful");
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

function addmet(val, val2){

    var sod = "<?php echo $so_id; ?>";
    var cir = "<?php echo $getcir[0]; ?>";
    var ser = 'IPTV';


     if(val2 == 'LH'){
        var a= document.getElementById("lh").value;
        var b= document.getElementById("lhP").value;
        var c= document.getElementById("lhook").value;
        var sn= '';
        
        if(c == ''){
            alert('EX-IPTV cannot be empty');
            return;
        }
        
     }
     
          if(val2 == 'OM'){
        var a= document.getElementById("othmet").value;
        var b= document.getElementById("othmetP").value;
        var c= '1';
        var sn= '';
 
     }
     
          var q = 'addmet';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"sod="+sod+"&cir="+cir+"&q="+q+"&met="+a+"&sn="+sn+"&type="+b+"&ser="+ser+"&val="+c,
             success:function(data){
             
                   if(data == "success"){
                        
                        alert("Meterial Insert Successful");
                        location.reload();
                        
                    }else{
                        alert(data);
                        location.reload();
                    }
               
                }
          });
          
}


 function deleteval(val,val2,val3,val4,val5) {
    var sod = "<?php echo $so_id; ?>";
    var cir = "<?php echo $getcir[0]; ?>";
    var ser = 'IPTV';
      var q = 'deletemet';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"&ser="+ser+"&cir="+val4+"&metid="+val5+"&q="+q+"&sod="+sod+"&met="+val,
             success:function(data){
                    if(data == "success"){
                        
                        
                        alert("Meterial Delete Successful");
                        location.reload();
                    }else{
                        alert(data);
                        location.reload();
                    }
     
               
                }
          });                        
}


function closeSODIPTV(val){
    
    var setsereial = document.getElementById("setsereial").value;
    var setmanufac = document.getElementById("setmanufac").value;
    var setmodel = document.getElementById("setmodel").value;
    var setwarrent = document.getElementById("setwarrent").value;
    var iptvhw = document.getElementById("iptvhw").value;
    var iptvhwser = document.getElementById("iptvhwser").value;
    
    
    
    if(setsereial == '' || setmanufac == '' || setmodel == '' || setwarrent == '' || iptvhw == '' || iptvhwser == ''){
            alert('Values cannot be empty');
            return;
        }
    
    
    var q = 'updateIPTVsod';
              $.ajax({

            type:"post",
             url:"db.php",
             data:"&sod="+val+"&q="+q+"&setsereial="+setsereial+"&setmanufac="+setmanufac+"&setmodel="+setmodel+"&setwarrent="+setwarrent+"&iptvhw="+iptvhw+"&iptvhwser="+iptvhwser,
             success:function(data){
                    if(data == "success"){
                        
                        
                        alert("SOD Completed Successful");
                        document.location = "CO_SO_List.php";
                    }else{
                        alert(data);
                        location.reload();
                    }
     
               
                }
          });
    
}

function retSODIPTV(valIPTV,retreson){
    
    
    var q = 'returnIPTVsod';
              $.ajax({

            type:"post",
             url:"db.php",
             data:"&sod="+valIPTV+"&q="+q+"&reason="+retreson,
             success:function(data){
                    if(data == "success"){
                        
                        
                        alert("SOD Completed Successful");
                        document.location = "CO_SO_List.php";
                    }else{
                        alert(data);
                        location.reload();
                    }
     
               
                }
          });
    
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
            <h4 class="m-0 text-dark">Service Order Completed - IPTV</h4>

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

    <form name="form2" method="post" action="" id="form2" > 
      
      <input type="hidden" id="so_id" name="so_id" value="<?php echo $so_id ?>"/>
      <input type="hidden" id="con_type" name="con_type" value="<?php echo $con_type ?>"/>
      <input type="hidden" id="circuit" name="circuit" value="<?php echo $getcir[0] ?>"/>
      <input type="hidden" id="next" name="next" />
      <input type="hidden" id="next2" name="next2" />
        <?php
        
       $getftthcab= oci_fetch_array(getftthcab($so_id));
       $osp_date = oci_fetch_array(osp_date($so_id));
       
       
       
      // $osp_date = oci_fetch_array(osp_date($so_id));
        //$so_completed = so_compCab($so_id);
        //$row = oci_fetch_array($so_completed);
       
        
       date_default_timezone_set('Asia/Colombo');


                
                $recieve_date = $getftthcab[2];
                
                $completed_date = date('m/d/Y h:i:s A', time());
                
                $completed_datesub = substr($completed_date, 0, 10);
                $recieve_datesub = substr($recieve_date, 0, 10);
              
                $d = substr($recieve_date, 20, 2);
                
              

                

//============


                //=================
                $working_days = TotalDays($recieve_datesub,$completed_datesub);
                if ($d == 'PM') {
                    $working_days--;
                }
                
                if ($con_type == 'IPTV') {
                    if ($working_days < '4') {
                        $days = '0';
                    } else {
                        $days = ($working_days - 3);
                    }
                }
                
                $penalty = $days * 200 ;	
                //=================
     
     
     
	 
	    echo " <input type=\"hidden\" id=\"days\" name=\"days\" value=\"$days\">";
        echo " <input type=\"hidden\" id=\"penalty\" name=\"penalty\" value=\"$penalty\">";
        echo " <input type=\"hidden\" id=\"delday\" name=\"delday\" value=\"$recieve_date\">";
        echo " <input type=\"hidden\" id=\"sertyp\" name=\"sertyp\" value=\"$getftthcab[1]\">";
        
	echo"	 <div id=\"page-wrapper\">
		
			
	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                                    
                                    <tbody>		

			";

    echo "<tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">IPTV SOD Details</span> </th></tr>";
    
        echo "<tr>
			<th>IPTV SOD</th>
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
            <td ><select class=\"form-control select2\" style=\"border-radius:5px;width:270;height:32px; color:#1C96B1; \"   class=\"txtbg\" >
              <option value=\"COMPLETED\" selected>COMPLETED</option>
			 </select></td>
            </tr>
            
            <tr>
			<th>Service Address</th>
            <td colspan=\"3\">$getftthcab[8]</td>
            <th>Connection Type</td>
            <td>$getcon[0]</td>
            </tr>
            
            <tr>
			<th>Megaline Package</th>
            <td>$osp_date[4]</td>
            <th>Phone Class</th>
            <td>$getftthcab[11]</td>
			<th>Phone Purchase from SLT</td>
            <td>$getftthcab[13]</td>
            </tr>";
	 

            echo "
            
            <tr>
            <th>Total Days</td>
            <td>$working_days</td>
             <th>Delay Days</td>
            <td>$days</td>
			<th>Penalty</td>
			<td>$penalty</td>
            </tr>";


                 
    echo "</table> 
	</div>";
    
    
    //=========================
  
    $meterial = nc_meterialpeo($so_id);
    
    $num =nc_meterialpeo2($so_id) ;
    	         echo " 
	<table style=\"font-size:12px; width:700px;\" class=\"table table-striped table-bordered table-hover\">";
       if($num != '0'){   
          echo"<tr>
                <th>UNIT DESIGNATOR</th>
                <th>P0</th>
                <th>P1</th>
                <th>SN</th>
                <th>Delete</th><tr>"; }
                
         $i=0;                           
          while ($rowa= oci_fetch_array($meterial)){

        if($rowa[2]== 'EX-IPTV'){
            $lh ='YES';
        }

        echo "<tr>
            
                <td><span>$rowa[2]</span></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"P0$i\" name=\"P0$i\" value=\"$rowa[3]\" disabled></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"P1$i\" name=\"P1$i\" value=\"$rowa[4]\" disabled></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"SN$i\" name=\"SN$i\" value=\"$rowa[5]\" disabled></td>
                <td><img src=\"img/del.png\"  width=\"25\" height=\"25\" onclick=\"deleteval('$rowa[2]','$i','$getser[0]','$row[0]','$rowa[6]')\"/> </td>
 
            </tr>";
            $i++;
            $cunt++;
            
          }
     
     echo"<input style=\"height: 30px; border-radius: 5px;\" id=\"plct\" name=\"plct\" type=\"hidden\" value=\"$pl\"  class=\"form-control\" >";     
    echo "</table>";
 //===========================
    


//====================================================
	//POlES


	//OTHER MET
echo "<br/><div class=\"row\">
    <div class=\"col-sm-3 \"><b><u>OTHER METERIALS</u></b> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    </div>";
    echo "<div class=\"row\">
    <div class=\"col-sm-3 \">Unit Designator </div>
    <div class=\"col-sm-2 \"></div>
    <div class=\"col-sm-2 \">Type</div>
    <div class=\"col-sm-3 \"> </div>
    </div><br/>";
    
if($lh == ''){   

//EX-IPTV

echo"
<br/>
<tr><div class=\"row\">
<div class=\"col-sm-3 \">
<select name=\"lh\" id=\"lh\"style=\"width: 250px; height: 30px; border-radius: 5px;\">
<option value=\"EX-IPTV\">EX-IPTV</option>
</select>
</div>
<div class=\"col-sm-2\">
<input style=\"height: 30px; border-radius: 5px;\" id=\"lhook\" name=\"lhook\" type=\"number\"  class=\"form-control\" >

</div>
<div class=\"col-sm-2 \">
<select id=\"lhP\" name=\"lhP\" style=\"width: 50px; height: 30px; border-radius: 5px;\">
<option value=\"P0\">P0</option>
<option value=\"P1\">P1</option>
</select>
</div>

<div class=\"form-group\">
  <div style=\"padding-left: 1px;\" class=\"col-sm-2\">
    <button style=\" width: 80px; height: 30px; border-radius: 5px;\" onclick=\"addmet('$getftthcab[0]','LH')\" id=\"add-more\" name=\"add-more\" class=\"btn btn-primary\">Add</button>
  </div>

</div>


</div></tr>

<br/>";


}
$getothmetcab= getothmetiptv();
    echo "<tr><div class=\"row\">
<div class=\"col-sm-3 \">
<select name=\"othmet\" id=\"othmet\" style=\"width: 250px; height: 30px; border-radius: 5px;\">";
while($row= oci_fetch_array($getothmetcab)){
    echo "<option value=\"$row[0]\">$row[0]</option>";
}
echo"</select>
</div>
<div class=\"col-sm-2\">


</div>
<div class=\"col-sm-2 \">
<select id=\"othmetP\" name=\"othmetP\" style=\"width: 50px; height: 30px; border-radius: 5px;\">
<option value=\"P0\">P0</option>
<option value=\"P1\">P1</option>
</select>
</div>

<div class=\"form-group\">
  <div style=\"padding-left: 1px;\" class=\"col-sm-2\">
    <button style=\" width: 80px; height: 30px; border-radius: 5px;\" onclick=\"addmet('$getftthcab[0]','OM')\" id=\"add-more\" name=\"add-more\" class=\"btn btn-primary\">Add</button>
  </div>

</div>
</div></tr><br/>";

   //$getcon
echo " 
	<table style=\"font-size:12px;\" class=\"table table-striped table-bordered table-hover\">
                                    
                                    <tbody>		

			";
	echo "<tr>";
			  echo "<td>SETTOPBOX SERIAL NO</td>";
			  echo "<td><input type=\"text\" class=\"txtbg\" name=\"setsereial\" id=\"setsereial\" style=\"border-radius:5px;width:300px;height:25px;\" required=\"\"></td>";
			  echo "</tr>";  
			  
			  echo "<tr>";
			  echo "<td>SETTOPBOX MANUFACTURER</td>";
			  echo "<td><input type=\"text\" class=\"txtbg\" name=\"setmanufac\" id=\"setmanufac\" style=\"border-radius:5px;width:300px;height:25px;\" required=\"\"></td>";
			  echo "</tr>"; 

				echo "<tr>";
			  echo "<td>SETTOPBOX MODEL</td>";
			  echo "<td><input type=\"text\" class=\"txtbg\" name=\"setmodel\" id=\"setmodel\" style=\"border-radius:5px;width:300px;height:25px;\" required=\"\"></td>";
			  echo "</tr>"; 
			  
			  echo "<tr>";
			  echo "<td>SETTOPBOX WARRANTEE</td>";
			  echo "<td><input type=\"text\" class=\"txtbg\" name=\"setwarrent\" id=\"setwarrent\" style=\"border-radius:5px;width:300px;height:25px;\" required=\"\"></td>";
			  echo "</tr>"; 
			  
			  echo "<tr>";
			  echo "<td>IPTV HW MODEL</td>";
			  echo "<td><input type=\"text\" class=\"txtbg\" name=\"iptvhw\" id=\"iptvhw\" style=\"border-radius:5px;width:300px;height:25px;\" required=\"\"></td>";
			  echo "</tr>"; 
			  
			  echo "<tr>";
			  echo "<td>IPTV HW SERIAL NO</td>";
			  echo "<td><input type=\"text\" class=\"txtbg\" name=\"iptvhwser\" id=\"iptvhwser\" style=\"border-radius:5px;width:300px;height:25px;\" required=\"\"></td>";
			  echo "</tr>";
				
          echo "<tr style=\"height:10px;\">";
		  
		  
          echo "</tr>";
	echo "</table>";

if($cunt != '0'){   
    echo "<tr>
			<th colspan =\"3\"></th>
            <td><input type=\"button\" class=\"btn btn-primary\" name=\"submit\" onclick=\"closeSODIPTV('$getftthcab[0]')\" value=\"Save\"></td>
            </tr>";
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
