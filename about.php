<!DOCTYPE html>

<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "engine/dashboardcss.php"; ?>
	
	
</head>

<body>

<div id="page-wrapper">
	<div id="page-content-wrapper">
				<div class="container-fluid">
					<div class="row" style="margin-top:0px;">
						<div class="col-lg-10"></div>
						<div class="col-lg-2">
								<div class="container-fluid" align="right">
									<div class="dropdown" style="margin-top:8px; margin-left:-500px;">
										<font color="white" size="4" style="font-family:Segoe UI;">User Name Here</font>&nbsp;
										<button class="btn btn-default btn-lg dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="background:transparent;">
											<i class="fa fa-user fa-fw" style="color:white;"></i>&nbsp;
											<span class="caret" style="color:white;"></span>
										</button>
										<ul class="dropdown-menu dropdown-menu-right">
											<li role="separator" class="divider"></li>
											<!--<li align="center" style="margin-left:8px; margin-right:8px; margin-top:5px; margin-bottom:5px;"><strong>Personnel/Employee Name</strong></li>-->
											<li align="center"><a href="#"><img src="engine/images/user.png" class="img-circle" alt="User Image" /></a></li> <!-- inser link for user profile -->
											<div align="center" style="margin-top:10px;"><a href="#"><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</button></a></div>
											<li role="separator" class="divider"></li>
										</ul>
									</div>
								</div><!-- /.container-fluid -->
						</div>
					</div>
				</div> <!--/.container-fluid--> 
		
		<div style="margin-bottom: 20px;"></div>
	
			<!-- Main Navigation -->
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-0"></div>
						<div class="col-lg-6">
						
							<nav class="navbar navbar-default navbar-static" style="font-family: Segoe UI; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
								
								<div class="container-fluid">
									<div class="navbar-header">
										<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" style="margin-right:10px;">
											<span class="sr-only">Toggle navigation</span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
										</button>
									</div>
									<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1" align="center" style="font-family: Segoe UI; font-size: 100%;">
												
												<div class="container-fluid">
															<ul class="nav navbar-nav">
																<li style="margin-right:20px; margin-left:6px;" class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-archive fa-fw"></i>&nbsp;&nbsp;Inventory&nbsp;<span class="caret"></span></a>
																	<ul class="dropdown-menu">
																		<li align="center" style="margin-top:10px; margin-bottom:10px;"><a href="supply/sup_list.php"><i class="fa fa-shopping-cart fa-fw"></i>&nbsp;&nbsp;Supply</a></li>
																		<li align="center" style="margin-top:10px; margin-bottom:10px;"><a href="equipment/eq_list.php"><i class="fa fa-truck fa-fw"></i>&nbsp;&nbsp;Equipment</a></li>
																	</ul>
																</li>
																<li style="margin-right:20px; margin-left:-2px;"><a href="requests/req.php"><i class="fa fa-clipboard fa-fw"></i>&nbsp;&nbsp;Requisition</a></li>
																<li style="margin-right:20px;"><a href="personnel/emp.php"><i class="fa fa-users fa-fw"></i>&nbsp;&nbsp;Personnel</a></li>
																<li style="margin-right:-10px;"><a href="index.php"><i class="fa fa-arrow-left fa-fw"></i>&nbsp;&nbsp;Go Back</a></li>
															</ul>
												</div>
									</div><!--/.nav-collapse -->
								</div><!--/.container-fluid -->
							</nav>
						</div>
					</div>
				</div>
			<!-- /.Main Navigation -->
	
		<!-- Dashboard Contents -->
			<div class="panel panel-success" style="margin-top:-40px;">
				<div class="panel-body" style="margin-top:20px;">
					<div class="row">
					
						<div style="margin-left:50px;">
							<img src="engine/images/logo.png" class="logo" align="left" />
							<h2 style="font-family:Segoe UI Light; color:black;" align="left"><small>Bicol University College of Nursing</small></h2>
							<h1 style="font-family:Segoe UI Light; color:black;" align="left">Requisition, Inventory and Monitoring System</h1>
						</div>
						
						<br><br />
						
						<div class="col-lg-12" style="font-family:Segoe UI;">
							<div class="row" align="center">
								<i class="mdi mdi-account-balance mdi-4x"></i>
								<br />
								<h2 align="center">About Bicol University College of Nursing</h2><br />
								<div class="col-lg-12">
										<p style="margin-left:125px; margin-right:125px; font-size:16px;">The College of Nursing believes in equipping the graduates with the fundamental, essential and universal knowledge, skills, attitudes and ideals to enable them to fulfill their role, functions and responsibilities as professionals within and to society.</p>
								</div>
								<br /><br /><br />
								<div class="col-lg-1"></div>
								<div class="col-lg-10" align="center">
									<div class="col-lg-4">
										<div class="panel panel-success">
											<div class="panel-heading">
												<i>Vision</i>
											</div>
											<div class="panel-body" align="justify">
												<p style="text-indent:25px; font-size:110%;">A Center of Exellence in nursing education and health related courses that contributes to the improvement of the lives of the Filipinos, both locally and abroad.</p>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="panel panel-success">
											<div class="panel-heading">
												<i>Mission</i>
											</div>
											<div class="panel-body" align="justify">
												<p style="text-indent:25px; font-size:110%;">Bicol University College of Nursing is primarily designed to produce health professionals capable of providing the highest quality health care service to individuals, families and communities.</p>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="panel panel-success">
											<div class="panel-heading">
												<i>Goal</i>
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
								<h2 align="center">Developers</h2>
								<p style="font-size:16px;">The programmers behind the development of the system.</p>
								<br /> <br />
								<div class="row">
									<div class="col-lg-12">
										<div class="col-lg-4">
											<i class="fa fa-sliders fa-3x"></i>
											<h4>Jessica Cimanes</h4>
											<p>System Analyst</p>
										</div>
										<div class="col-lg-4">
											<i class="mdi mdi-settings mdi-3x"></i>
											<h4>Kristine Marie Razo</h4>
											<p>Database Manager</p>
										</div>
										<div class="col-lg-4">
											<i class="mdi mdi-build mdi-3x"></i>
											<h4>Lowe Antony Balean</h4>
											<p>Interface Designer</p>
										</div>
									</div>
								</div>
								<br />
								<p><i>***************** Notes *****************</i></p>
								<p><i>The system's interface was built using bootstrap v3.3.6 with material design integration.</i></p>
							
							</div>
						</div>
					
					</div>
				</div>
			</div>
		<!-- /.Dashboard Contents -->
		
		
			<p align="center" style="color:white;">This program is under Development. Interface is based on bootstrap v3.3.6 with material design integration. Capstone Project 2016</p>
		
		
	</div> <!--/.page-content-wrapper">-->
</div>
	
	
	
	<!-- Calling Default Javascript files -->
	<?php include  "engine/jsdashboard.php"; ?>
</body>

</html>