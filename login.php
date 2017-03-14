<!DOCTYPE html>

<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include "connect.php";
	//clearstatcache();
?>

<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "engine/logincss.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "engine/jsdashboard.php"; ?>
</head>

<body>

<div id="page-wrapper" style="font-family: Segoe UI;">
	<div id="page-content-wrapper">
	
		<div style="margin-left:50px;">
			<img src="engine/images/logo.png" class="logo" align="left" />
			<h2 style="font-family:Segoe UI Light; font-size:40px;" align="left"><small><font color="white">Bicol University College of Nursing</font></small></h2>
			<h1 style="font-family:Calibri; font-size:33px; color:white;" align="left">Requisition, Inventory and Monitoring System</h1>
		</div>
	
				<div class="container-fluid" align="center" style="margin-top:70px;">
					
					<div class="row">
						<div class="col-lg-4">
						</div>
						<div class="col-lg-4">
									<div class="panel panel-success" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
										<div class="panel-heading" align="center">
											<h3 class="panel-title"><strong>USER LOG IN</strong></h3>
										</div>
										<br>
										<!--<i>Please fill-in both fields.</i>-->
										<div class="panel-body" align="left" style="font-size:105%;">
											<div class="col-lg-12">
											<?php if(isset($_POST['gouser'])) readuser($_POST['per_id'], $_POST['password']); ?>
												<form method="post" autocomplete="off">		
													<div class="form-group has-feedback">
														<input type="text" class="form-control" name="per_id" placeholder="Personnel ID Number" required autocomplete="off"/>
														<span class="glyphicon glyphicon-user form-control-feedback"></span>
													</div>
													<div class="form-group has-feedback">
														<input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="off"/>
														<span class="glyphicon glyphicon-lock form-control-feedback"></span>
													</div>
											</div>
										</div>
											<div class="panel-footer">
												<button type="submit" class="btn btn-success" name="gouser"><i class="zmdi zmdi-sign-in zmdi-hc-lg"></i>&nbsp;&nbsp;Log In</button>
											</div>
									</div>
												</form>
						</div>
					</div>
						
						<?php
						
							function readuser($pid, $pwd){
								
								#if personnel id exists
								$check = mysql_fetch_array(mysql_query("SELECT count(*) FROM personnel WHERE personnel_empid = '$pid' ")) or die(mysql_error());
								if($check[0] > 0){
									$password = md5($pwd);
									$confirm = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_empid = '$_POST[per_id]' ")) or die(mysql_error());
									
									#check this if the account is activated
									$play = mysql_fetch_array(mysql_query("SELECT count(*) FROM account WHERE personnel_id = '$confirm[personnel_id]' AND account_status = 'activated' ")) or die(mysql_error());
									if($play[0] > 0){
										
										#if password matched
										$compare = mysql_fetch_array(mysql_query("SELECT count(*) FROM account WHERE personnel_id = '$confirm[personnel_id]' AND password = '$password' ")) or die(mysql_error());
										if($compare[0] > 0){
											$partwo = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id='$confirm[personnel_id]' AND password = '".$password."' ")) or die(mysql_error());
										
											$_SESSION['logged_in'] = true;
											$_SESSION['account_type'] = $partwo['account_type'];
											$_SESSION['logged_personnel_id'] = $confirm['personnel_id'];
											//$_SESSION['user_info'] = $confirm;
											//$_SESSION['account_info'] = $partwo;
											
											if($partwo['account_type'] == "System Administrator" AND $partwo['password'] == md5("bucnrims_2016")) print "<script>alert('Please change your password soon for your account privacy.'); window.location='index.php'</script>";
											else if($partwo['account_type'] == "Administrator" AND $partwo['password'] == md5("bucnrims_2016")) print "<script>alert('Please change your password soon for your account privacy.'); window.location='index.php'</script>";
											else if ($partwo['account_type'] == "End User" AND $partwo['password'] == md5("bucnrims_2016")) print "<script>alert('Please change your password soon for your account privacy.'); window.location='index.php'</script>";
										
											else if($partwo['account_type'] == "System Administrator") print "<script>window.location='index.php'</script>";
											else if($partwo['account_type'] == "Administrator") print "<script>window.location='index.php'</script>";
											else if($partwo['account_type'] == "End User") print "<script>window.location='index.php'</script>";
										
											else print "<script>alert('Something went wrong when you're logging in. Please tell this to the System Administrator.')</script>";
											
										}else{
											print "<script>alert('The password is incorrect. Please contact your System Administrator for assistance.')</script>";
										}
									}else{
										print "<script>alert('This is an inactive account. Please report your account to your System Administrator for assistance.')</script>";
									}
									
								}else{
									print "<script>alert('This account does not exist. Please contact the System Administrator to Register.')</script>";;
								}
							}
						?>
					
					
					
					<!-- This part is the system details... -->
					<!--<div class="panel panel-default" style="margin: 130px 0 -20px 0; ">
						<p align="center" style="margin-top: 13px;">This program is under Development. Interface is based on bootstrap v3.3.6 with material effects integration. Capstone Project 2016</p>
					</div>-->
					
				</div> <!--/.container-fluid-->
				<br /><br /><br /><br />
				<div style="margin-top: 25px; margin-left:35px;" align="left">
					<a class="page-scroll" href="#dev" data-toggle="tooltip" title="About Us"><button type="button" class="btn btn-success btn-circle btn-xl btn-5x"><font color="white" size="4" style="font-family:Segoe UI;"><i class="zmdi zmdi-info-outline zmdi-hc-3x" style="margin-left: -5px; margin-top:-3px;" ></i></font></button></a>
				</div>
				
		<section id="dev" class="contact-section">		
			<!-- About content -->
			<br />
			<div class="panel panel-success" style="margin-top:-50px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
				<div class="panel-body" style="margin-top:20px;">
					<div class="row">
						<br /><br />
						<div class="col-lg-12" style="font-family:Segoe UI;">
							<div class="row" align="center">
								<i class="zmdi zmdi-balance zmdi-hc-4x"></i>
								<br />
								<h2 align="center">Bicol University College of Nursing</h2><br />
								<div class="col-lg-12">
									<p style="margin-left:125px; margin-right:125px; font-size:16px;">The College of Nursing believes in equipping the graduates with the fundamental, essential and universal knowledge, skills, attitudes and ideals to enable them to fulfill their role, functions and responsibilities as professionals within and to society.</p>
									<br />
								</div>
								
								<div class="row">
									<div class="col-lg-3"></div>
									<div class="col-lg-6">
										<div class="col-lg-12">
											<div class="col-lg-12">
												<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
													<div class="panel-heading">
														<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" align="center">
															<ol class="carousel-indicators">
															  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
															  <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
															  <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
															  <li data-target="#carousel-example-generic" data-slide-to="3" class=""></li>
															  <li data-target="#carousel-example-generic" data-slide-to="4" class=""></li>
															  <li data-target="#carousel-example-generic" data-slide-to="5" class=""></li>
															</ol>
															<div class="carousel-inner">
															  <div class="item active">
																<img src="engine/images/slides/1.jpg" alt="First slide" width="620px" height="350px">
															  </div>
															  <div class="item">
																<img src="engine/images/slides/2.jpg" alt="Second slide" width="620px" height="350px">
															  </div>
															  <div class="item">
																<img src="engine/images/slides/3.jpg" alt="Third slide" width="620px" height="350px">
															  </div>
															  <div class="item">
																<img src="engine/images/slides/4.jpg" alt="Fourth slide" width="620px" height="350px">
															  </div>
															  <div class="item">
																<img src="engine/images/slides/5.jpg" alt="Fifth slide" width="620px" height="350px">
															  </div>
															  <div class="item">
																<img src="engine/images/slides/6.jpg" alt="Sixth slide" width="620px" height="350px">
															  </div>
															</div>
															<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
															  <span class="glyphicon glyphicon-chevron-left"></span>
															</a>
															<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
															  <span class="glyphicon glyphicon-chevron-right"></span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-lg-1"></div>
								<div class="col-lg-10" align="center">
									<br /><br />
									<div class="col-lg-4">
										<div class="panel panel-info" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
											<div class="panel-heading">
												<strong>VISION</strong>
											</div>
											<div class="panel-body" align="justify">
												<p style="text-indent:25px; font-size:110%;">A Center of Exellence in nursing education and health related courses that contributes to the improvement of the lives of the Filipinos, both locally and abroad.</p>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="panel panel-danger" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
											<div class="panel-heading">
												<strong>MISSION</strong>
											</div>
											<div class="panel-body" align="justify">
												<p style="text-indent:25px; font-size:110%;">Bicol University College of Nursing is primarily designed to produce health professionals capable of providing the highest quality health care service to individuals, families and communities.</p>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="panel panel-success" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
											<div class="panel-heading">
												<strong>GOAL</strong>
											</div>
											<div class="panel-body" align="justify">
												<p style="text-indent:25px; font-size:110%;">Attainment of the highest quality of graduates with training in the different health courses and in research and extension services responsive to the needs of society.</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row" align="center">
								<br />
								<i class="fa fa-code fa-4x"></i>
								<h2 align="center">Researchers</h2>
								<!--<p style="font-size:16px;">The programmers behind the development of the system.</p>-->
								<br /> <br />
								<div class="row">
									<div class="col-lg-12">
										<div class="col-lg-4">
											<i class="zmdi zmdi-apple zmdi-hc-3x"></i>
											<h4>Jessica Cimanes</h4>
										</div>
										<div class="col-lg-4">
											<i class="zmdi zmdi-android zmdi-hc-3x"></i>
											<h4>Lowe Antony Balean</h4>
										</div>
										<div class="col-lg-4">
											<i class="zmdi zmdi-windows zmdi-hc-3x"></i>
											<h4>Kristine Marie Razo</h4>
										</div>
									</div>
								</div>
								<br />
								<div class="row">
									<div class="col-lg-1"></div>
									<div class="col-lg-10">
										<p><i>The system's interface was built using bootstrap v3.3.6 with jQuery and a little of aJAX technology, dataTables plugin for best table viewing and fpdf for generation of reports and material design for icons and the ripple effects.</i></p>
									</div>
								</div>
								
							</div>
						</div>
					
					</div>
				</div>
			</div>
			<!-- /.About content -->
			
			<p align="center" style="color:white;">Capstone Project 2016 - 2017 Version 1.0</p>
			
		</section>		
		
		
	</div> <!--/.page-content-wrapper">-->
</div>
	
</body>

</html>