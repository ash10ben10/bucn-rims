<!DOCTYPE html>
<?php
include "../connect.php";

	session_start();
	
	if(!isset($_SESSION['logged_in'])){
	print "<script>alert('You need to log in to access this page.'); window.location='../login.php';</script>";
	}
	/* else if($_SESSION['account_type'] == "System Administrator" || $_SESSION['account_type'] == "Administrator"){
		$uInfo = $_SESSION['user_info'];
		$aInfo = $_SESSION['account_info'];
	} */
	/* else if($_SESSION['account_type'] == "End User" ){
		print "<script>window.location='../personnel/end_usr/eu_profile.php';</script>";
	} */
	else{
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' "));
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
	}
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include "../engine/jscalls.php"; ?>
	
	<?php include "supspec_engine.php"; ?>
	
	<script>
	
	$(document).ready(function() {
		
		var $iUnit = $("#itemunit");
		
		$("#itemname").on("change", function(){
			
			$.ajax({
					url: "supspec_engine.php",
					data: {
						item_id: $(this).val(),
						getunit: "getItemUnit",
						},
					type: "POST",
					dataType: "json"
				}).done(function(response){
					$iUnit.html("");
					$.each(response.data, function(index, element){
						console.log(element.unitname);
						$iUnit.append("<option value='"+element.unitid+"'>"+element.unitname+"</option>");
					});
					$iUnit.selectpicker("refresh");
					$iUnit.trigger("change");
				}).fail(function(){
					alert("error sending request.")
				});
			
		});
		
		$("#editDesc").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#mdNo").val(request_data[0]);
            $("#mdDesc").val(request_data[1]);
            $("#mdPrice").val(request_data[2]);
            $("#mdName").text(request_data[3]);
            $("#mdUnit").text(request_data[4]);
            //alert(data);
        });
		
		$("#saveDescBtn").click(function(event){
				var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $mdDesc = $("#mdDesc").val();
				if($mdDesc == 0 || $mdDesc == "" || $mdDesc == null){
					isOk = false;
					msg;
				}
				
				var $mdPrice = $("#mdPrice").val();
				if($mdPrice == 0 || $mdPrice == "" || $mdPrice == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var mdNo = $("#mdNo").val();
					var mdDesc = $("#mdDesc").val();
					var mdPrice = $("#mdPrice").val();

					var hr = new XMLHttpRequest();
						var url = "saveiDesc.php";
						var vars = "mdNo="+mdNo+"&mdDesc="+mdDesc+"&mdPrice="+mdPrice;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("saveDescResult").innerHTML = return_data;
							window.location = "supply_specs.php";
							
						  }
						}
						hr.send(vars);
						document.getElementById("saveDescResult").innerHTML = "Updating...";
				}
			});
		
	});
	
	function submitform(e){
		var isOk = true;
		var msg = "Please fill in the required forms.";
		
		var $itemname = $("#itemname").val();
		if($itemname == 0 || $itemname == "" || $itemname == null){ 
			isOk = false;
			msg;
		}
		
		var $itemunit = $("#itemunit").val();
		if($itemunit == 0 || $itemunit == "" || $itemunit == null){ 
			isOk = false;
			msg;
		}
			
		if(!isOk){
			e.preventDefault();
			alert(msg);
			return false;
		}
	}
		
	</script>
</head>

<!-- Header and Sidebar Page -->

<!-- Wrapper -->
<div id="wrapper" style="font-family: Segoe UI;">
	
	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<!-- Inside Sidebar -->
		
		<div class="panel panel-success" style="margin: 30px 15px 0 15px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<div class="panel-heading" style="font-family: Segoe UI Light;">
				<h3 class="panel-title" align="center"><strong>You are logged in as <?php echo $account['account_type']; ?></strong></h3>
			</div>
			<div class="panel-body" style="height:90px; overflow-y: auto;">
				<a href="../personnel/viewinfo.php?id=<?php echo $personnel['personnel_id']; ?>" style="margin: 0px  0 -5px;"><img src="<?php echo "../personnel/".$personnel['personnel_photo']; ?>" class="img-circle" style="height:60px; width:60px; padding: 0px 0px 0px 0px;"/></a> <div align="middle" style="margin: -59px 0 -5px 80px;"><strong><?php echo $personnel['personnel_fname']." ".$personnel['personnel_lname']; ?></strong><br /><i><?php echo $position['position_name']; ?></i></div>
			</div>
		</div>
		<!-- <div style="margin:15px 0 0 65px;"><a href="../index.php" data-toggle="tooltip" title="Go back to Dashboard Home."><button type="submit" class="btn btn-default" style="margin: 0 -15px 0 -5px;"><i class="fa fa-dashboard fa-fw"></i>&nbsp;&nbsp;Dashboard</button></a></div> -->
		<ul class="sidebar-nav nav-pills nav-stacked" id="menu" style="margin-top:200px;">
			
			<?php 
			if($position['position_name'] == "Supply Officer"){
				?>
				<li>
					<a href="sup_list.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-store zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Stocks</a>
				</li>
				<?php
			}else{
				print "";
			}
			?>
			<li>
				<a href="stock_req.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-shopping-cart zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Stock Requests</a>
			</li>
			<?php 
			if($position['position_name'] == "Supply Officer"){
				?>
				<li>
					<a href="supply_label.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-label zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Labels</a>
				</li>
				<li class="active">
					<a href="supply_specs.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-widgets zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Descs</a>
				</li>
				<li>
					<a href="supply_report.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-file-text zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Reports</a>
				</li>
				<?php
			}else{
				print "";
			}
			?>
			
		</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->

	<!-- Navigation -->
	<?php include "../supply/sup_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
	
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-widgets zmdi-hc-lg"></i>&nbsp;&nbsp;Supply Descriptions</h1>
					
					<br />
					
					<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
						<div class="panel-heading">
							<form method="POST" id="addesc" enctype="multipart/form-data" onsubmit="submitform(event);">
							<div class="row">
								<br/>
								<div class="col-lg-12">
									<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
										<label>Item Name:</label>
										<select name="itemname" id="itemname" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required />
											<?php 
												$query = mysql_query("SELECT * FROM `items` WHERE item_type='Supply'");
												echo "<option selected disabled>-Select Items-</option>";
												while($row = mysql_fetch_array($query)){
													echo "<option value='".$row['item_id']."' >".ucfirst($row['item_name'])."</option>";
												}
											?>
										</select>
									</div>
									<div class="col-lg-2">
										<label>Item Unit:</label>
										<select class="selectpicker form-control" name="itemunit" id="itemunit" value="" data-hide-disabled="true" data-live-search="true" onChange="" required >
										</select>
									</div>
									<div class="col-lg-3">
										<label>Description:</label>
										<input class="form-control" name="itemdesc" id="itemdesc" placeholder="Item Description" required />
									</div>
									<div class="col-lg-2">
										<label>Price:</label>
										<input class="form-control" name="itemprice" id="itemprice" placeholder="Item Price:" pattern="([0-9])+" min="0.01" type="number" step="0.01" required />
									</div>
									<div class="col-lg-1" align="left" style="margin: 25px 0 0 0;">
										<button name="submitdesc" id="submitdesc" class="btn btn-info" ><span class="fa fa-arrow-right fa-fw"></span>&nbsp;Submit</button>
									</div>
								</div>
							</div>
							</form>
							</div>
					
						
						<div class="panel-body">
						
						<?php
						
						$getdesc = mysql_query("SELECT md.md_id, i.item_name, iu.item_unit_name, md.description, md.price FROM `more_desc`AS md LEFT JOIN more_units AS mu ON mu.munit_id = md.munit_id LEFT JOIN items AS i ON i.item_id = mu.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = mu.item_unit_id WHERE i.item_type = 'Supply'");
						
						if(mysql_num_rows($getdesc) == 0){
							print "<br /><p align=center><i>There are no available Descriptions on the Supply. Please add one.</i></p><br />";
						}else{
							
							print "
								<div class='table-responsive'>
									<table class = 'table table-striped table-bordered table-hover display' id='showTable'>
								
									<thead>
										<tr>
											<th>Item Name</th>
											<th>Item Unit</th>
											<th>Description</th>
											<th>Price</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
								";
								
							while($getdata = mysql_fetch_array($getdesc)){
								
								print "<tr>";
								print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['item_name']."</center></td>";
								print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['item_unit_name']."</center></td>";
								print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['description']."</center></td>";
								print "<td style='text-align:center;vertical-align:middle;'><center>Php ".number_format($getdata['price'], 2,'.',',')."</center></td>";
								print "<td style='text-align:center;vertical-align:middle;'><center>";
								?>
								<button name="edit" data-id="<?php echo $getdata['md_id']."|{$getdata['description']}|{$getdata['price']}|{$getdata['item_name']}|{$getdata['item_unit_name']}"?>" class="btn btn-warning" data-toggle="modal" data-target="#editDesc" style="margin: 5px 5px 5px 5px;" ><i class="zmdi zmdi-edit zmdi-hc-1x"></i>&nbsp;&nbsp;Edit Info</button>
								
								<div class="modal fade" id="editDesc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<form>
												<div class="modal-header" align="left">
													<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-border-color zmdi-hc-2x"></i>&nbsp;&nbsp;Edit Description of <label id="mdName"></label> by <label id="mdUnit"></label></h4>
													<input class="form-control" id="mdNo" type="hidden" />
												</div>
												<div class="modal-body">
													
													<div class="row" align="center">
														<div class="col-lg-1">
														</div>
														<div class="col-lg-10">
															<div class="row" style="margin:20px 0 20px 0;">
																<div class="col-lg-4" style="margin-top:10px;" align="left">
																	<label>Description:</label>
																</div>
																<div class="col-lg-6" style="margin-top:5px;" align="left">
																	<input class="form-control" id="mdDesc" placeholder="Item Description" required />
																</div>
															</div>
															<div class="row" style="margin:20px 0 20px 0;">
																<div class="col-lg-4" style="margin-top:5px;" align="left">
																	<label>Price:</label>
																</div>
																<div class="col-lg-6" align="left">
																	<input class="form-control" id="mdPrice" placeholder="Item Price:" pattern="([0-9])+" min="0.01" type="number" step="0.01" required />
																</div>
															</div>
														</div>
													</div>
													
												</div>
												<div class="modal-footer">
													<button type="button" id="saveDescBtn" class="btn btn-success"><span class="fa fa-arrow-right fa-fw"></span>&nbsp;Submit</button>
													<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
												</div>
												<div id="saveDescResult" style="margin: 10px 0 10px 0;"></div>
											</form>
										</div>
									</div>
								</div>
								
								
								
								<?php
								print "</center></td>";
								print "</tr>";
							}
							
							print "</tbody></table></div>";
						}
						
						?>
						
						</div>
					</div>
					
				</div>
			</div>
			
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>