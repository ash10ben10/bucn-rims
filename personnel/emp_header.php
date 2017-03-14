
	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="font-family: Segoe UI;">
		
			<div>
					<img src="../engine/images/header.png" class="img-responsive" style="width:100%;">
				</div>
		
				<div class="navbar-header" style="margin-right: 20px;">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" style="margin-right:10px;">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					
					<a class="navbar-brand"><button class="btn btn-default" data-toggle="collapse" id="menu-toggle" style="margin-top:-7px;"><span class="fa fa-th-list fa-fw" aria-hidden="true" style="margin-top: 5px;"></span></button></a>
					
				</div>
					<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1" align="center" style="font-family: Segoe UI; font-size: 98%;">
								<div class="container-fluid">	
									<ul class="nav navbar-nav" style="margin: 2px 0 0 -15px;">
										<li><a href="../index.php" data-toggle="tooltip" title="Return to Dashboard Home Page."><i class="zmdi zmdi-view-dashboard zmdi-hc-lg"></i>&nbsp;&nbsp;Dashboard</a></li>
										
										<?php
										if($position['position_name'] == "Supply Officer"){
											?>
											<li><a href="../supply/sup_list.php" data-toggle="tooltip" title="Manage Supply and the status in the inventory."><i class="zmdi zmdi-mall zmdi-hc-lg"></i>&nbsp;&nbsp;Supply</a></li>
											<?php
										}else{
											?>
											<li><a href="../supply/stock_req.php" data-toggle="tooltip" title="Manage Supply and the status in the inventory."><i class="zmdi zmdi-mall zmdi-hc-lg"></i>&nbsp;&nbsp;Supply</a></li>
											<?php
										}
										?>
										<li><a href="../equipment/eq_issued.php" data-toggle="tooltip" title="Manage Equipment and the status in the inventory."><i class="zmdi zmdi-washing-machine zmdi-hc-lg"></i>&nbsp;&nbsp;Equipment</a></li>
										<li><a href="../requests/req.php" data-toggle="tooltip" title="Manage Requests and the transactions."><i class="zmdi zmdi-mail-send zmdi-hc-lg"></i>&nbsp;&nbsp;Requests</a></li>
										<?php
										if($account['account_type'] == "System Administrator"){
											?>
											<li><a href="../personnel/emp.php" data-toggle="tooltip" title="Manage Personnel and their information."><button type="button" class="btn btn-info" style="margin-bottom:-8px; margin-top:-11px;"><i class="zmdi zmdi-accounts-list-alt zmdi-hc-lg"></i>&nbsp;&nbsp;Personnel</button></a></li>
											<?php
										}else{
											?>
											<li><a href="../personnel/viewinfo.php?id=<?php print $_SESSION['logged_personnel_id']; ?>" data-toggle="tooltip" title="Manage Personnel and their information."><button type="button" class="btn btn-info" style="margin-bottom:-8px; margin-top:-11px;"><i class="zmdi zmdi-accounts-list-alt zmdi-hc-lg"></i>&nbsp;&nbsp;Personnel</button></a></li>
											<?php
										}
										?>
										<?php
										if($account['account_type'] == "System Administrator"){
											?>
											<li><a href="../settings/suppliers.php" data-toggle="tooltip" title="Manage System Settings."><i class="zmdi zmdi-settings zmdi-hc-lg"></i>&nbsp;&nbsp;Settings</a></li>
											<?php
										}else{
											print "";
										}
											
										?>
										<!--<li><a href="#"><i class="fa fa-print fa-fw"></i>&nbsp;&nbsp;Reports</a></li>-->
									</ul>
									
									<div class="container-fluid">
										<form method="POST">
											<ul class="nav navbar-nav navbar-right">
													<li style="margin-left:-8px;"><button type="submit" name="outnow" id="outnow" class="btn btn-default btn-transparent" style="margin: 10px 0 10px 0;"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</button></li>
													<!--<a href="login.php"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</a></li>-->
											</ul>
										</form>
										<?php 
											if(isset($_POST['outnow'])){
												session_destroy();
												print "<script>window.location='../login.php';</script>";
											}
										?>
									</div>
									
									<!-- (these will bring back to the button design of the navbar in user panel)
									<div class="container-fluid">
										<ul class="nav navbar-nav navbar-right">
											<div class="dropdown" style="margin-top:8px;">
												<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
													<i class="fa fa-user fa-fw"></i>&nbsp;User&nbsp;
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li role="separator" class="divider"></li>
													<li align="center" style="margin-left:8px; margin-right:8px; margin-top:5px; margin-bottom:5px;"><strong>Personnel/Employee Name</strong></li>
													<li align="center"><a href="#"><img src="../engine/images/user.png" class="img-circle" alt="User Image" /></a></li>
													<div align="center" style="margin-top:10px;"><a href="#" onClick="window.location='../login.php'"><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</button></a></div>
													<li role="separator" class="divider"></li>
												</ul>
											</div>
										</ul>
									</div>
									-->
									<!-- (these will bring back to old concept of user account in right side of the header)
									<div class="container-fluid">
										<ul class="nav navbar-nav navbar-right">
											<li class="dropdown"><a href="index.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-fw"></i>&nbsp;<strong>Employee Name</strong>&nbsp;<span class="caret"></span></a>
												<ul class="dropdown-menu">
													<li role="separator" class="divider"></li>
													<li align="center"><a href="#"><img src="../engine/images/user.png" class="img-circle" alt="User Image" /></a></li>
													<div align="center" style="margin-top:10px;"><a href="#"><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</button></a></div>
													<li role="separator" class="divider"></li>
												</ul>
											</li>
										</ul>
									</div>
									-->
								</div>
					</div>
					
	</nav> <!--/.Navigation -->
	
	<!-- Sidebar Function -->
	<script src="../engine/js/sidebar_menu.js"></script>