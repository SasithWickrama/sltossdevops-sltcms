<?php
include "db.php";
 $con_que = con_contractor_name();


$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;

//$_SESSION['token_time'] = time(); 
?> 

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
		
		<script src="assets/js/jquery-2.1.4.min.js"></script>
	</head>

	<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
						<div class="space-30"></div>
						<div class="space-30"></div>
							<div class="center">
								<h1>
									<i class="ace-icon fa fa-file green"></i>
									<span class="blue" style=" font-size :30px">SLT Contractor Management System</span>
									
								</h1>
								
							</div>

							<div class="space-10"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
										
												Please Enter Login Information
											</h4>

											<div class="space-6"></div>

											<form method="post" action="auth.php">
                                            <input type="hidden" name="token" value="<?php echo $token; ?>" /> 
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="txtUsername" id="txtUsername" class="form-control" placeholder="Service ID" required="true"/>
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="txtPassword" id="txtPassword" class="form-control" placeholder="Password" required="true"/>
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>
                                                    
                                                    <label class="block clearfix">
														<?php
                                                        echo "<select id= \"contractor\"  style=\"border-radius:5px; width:300px;height:32px;text-align: left\" name= \"contractor\" class=\"form-control\">";
                                                	    echo "<option value=\"\" selected></option>";
                                                            while($row=oci_fetch_array($con_que))
                                                            {
                                                                echo"<option value=\"$row[0]\">$row[0]</option>";
                                                            }
                                                            echo "</select></br>";
                                                        
                                                        
                                                        ?>
													</label>

													<div class="space"></div>

													<div class="clearfix">


														<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>
														</button>
                                                        
                                                        <div><a href="captchacode.php"><span style="font-size: 15px;">Get Password</span></a></div>
                                                        
													</div>

													<div class="space-4"></div>
                                                    
                                                    
												</fieldset>
											</form>

											<div class="space-6"></div>

											
										</div><!-- /.widget-main -->

									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

							</div><!-- /.position-relative -->

				
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->


		



		
	</body>
</html>
