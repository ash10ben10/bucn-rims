<script>

	$(document).ready(function(){
	
		$("#extend").on("show.bs.modal", function(event){
			var button = $(event.relatedTarget);
			data = button.data('id');
			request_data = data.split('|');
			
			$("#getpoId").val(request_data[0]);
			$("#getpoNum").text(request_data[1]);
			//alert(data);
		});
		
		$("#extendBtn").click(function(event){
			var isOk = true;
			var msg = "Please fill in the required forms.";
			
			var $extdel = $("#extdel").val();
			if($extdel == 0 || $extdel == "" || $extdel == null){
				isOk = false;
				msg;
			}
			var $reason = $("#reason").val();
			if($reason == 0 || $reason == "" || $reason == null){
				isOk = false;
				msg;
			}
			
			if(!isOk){
					alert(msg);
					return false;
			}else{
				var POid = $("#getpoId").val();
				var extdel = $("#extdel").val();
				var reason = $("#reason").val();

				var hr = new XMLHttpRequest();
					var url = "extcount.php";
					var vars = "POid="+POid+"&extdel="+extdel+"&reason="+reason;

					hr.open("POST", url, true);
					hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

					hr.onreadystatechange = function() {
					if(hr.readyState == 4 && hr.status == 200) {
					  var return_data = hr.responseText;
						document.getElementById("ExtendingResult").innerHTML = return_data;
						window.location = "status.php";
						
					  }
					}
					hr.send(vars);
					document.getElementById("ExtendingResult").innerHTML = "Extending order...";
			}
			
		});
	
	});
	
</script>

<?php

	#count the remaining days left from current date to delivery date
	$getime = mysql_fetch_array(mysql_query("SELECT `delivery_date`, `delivery_term` FROM `purchase_order` WHERE po_id = '$getdata[po_id]'"))or die(mysql_error());
	$deldate = date("Y-m-d", strtotime($getime['delivery_date']));
	$future = date_create($deldate);
	$today = date_create($date);
	$diff = date_diff($today, $future);
	$dayss = $diff->days;
	
	$period = new DatePeriod($today, new DateInterval('P1D'), $future);
	
	foreach($period as $dt) {
		$curr = $dt->format('D');
		// substract if Saturday or Sunday
		if ($curr == 'Sat' || $curr == 'Sun') {
			$dayss--;
		}
	}
	
	if($dayss == 0 || $today > $future){
		print "<div style='color:red;'>";
		print "Delivery has reached the deadline.";
		print "</div>";
		
		if($position['position_name'] == "Supply Officer"){
			?>
			<button name="exdel" data-id="<?php echo $getpo['po_id']."|{$getpo['ponumber']}" ?>" data-toggle="modal" data-target="#extend" class="btn btn-danger"><i class="zmdi zmdi-time"></i>&nbsp;&nbsp;Extend Delivery</button>
			<br/>
			
			<div class="modal fade" id="extend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<form>
							<div class="modal-header" align="left">
								<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-truck zmdi-hc-2x"></i>&nbsp;&nbsp;Extend Delivery for Purchase Order No. <label id="getpoNum"></label></h4>
								<input class="form-control" id="getpoId" type="hidden" />
							</div>
							<div class="modal-body">
								
								<div class="row" align="center">
									<div class="col-lg-12">
										<div class="row">
											<div class="col-lg-3" align="left" style="margin:4px 0 0 0;">
												<label>Extension days:</label>
											</div>
											<div class="col-lg-9" align="left">
												<input name="extdel" id="extdel" class="form-control" pattern="([0-9])+" maxlength="3" placeholder="days" />
											</div>
										</div>
										<div class="row">
											<div class="col-lg-9" align="left" style="margin:4px 0 0 0;">
												<label>Reason for Extension:</label>
											</div>
											<div class="col-lg-12">
												<textarea class="textarea" id="reason" style="height:100px;resize:none;width:100%;" required ></textarea>
											</div>
										</div>
									</div>
								</div>
								
							</div>
							<div class="modal-footer">
								<button type="button" id="extendBtn" class="btn btn-info"><span class="fa fa-arrow-right"></span>&nbsp;Extend</button>
								<button type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
							</div>
							<div id="ExtendingResult" style="margin: 10px 0 10px 0;"></div>
						</form>
					</div>
				</div>
			</div>
			
			<?php
		}else if($position['position_name'] == "Inspection Officer"){
			?>
			<a onClick='cancel_items("<?php print $getpo['po_id'];?>")' class="btn btn-danger" title="This will terminate the process of ordering and delivering the purchases from the supplier."><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel Delivery</a>
			<?php
		}
		
	}else{
		print "<div style='color:orange;'>";
		print "Order is now under delivery.";
		print "<br/>";
		print $dayss." days left until deadline.";
		print "</div>";
		if($getpo['ext_reason'] == ""){
			print "";
		}else{
			$penalty = number_format($getpo['ext_penalty'], 2,'.',',');
			
			?>
			
			<script>
			
			$(document).ready(function(){
				$("#viewReason").on("show.bs.modal", function(event){            
					var button = $(event.relatedTarget);
					data = button.data('id');
					request_data = data.split('|');
					
					$("#poId").val(request_data[0]);
					$("#poNumber").text(request_data[1]);
					$("#reAson").text(request_data[2]);
					$("#penalty").text(request_data[3]);
					//alert(data);
				});
			});
			
			</script>
			
			<a href="#" data-id="<?php echo $getpo['po_id']."|{$getpo['ponumber']}|{$getpo['ext_reason']}|{$penalty}"?>" data-toggle="modal" data-target="#viewReason" class="btn btn-warning" title="View Remarks" style="margin:3px 3px 3px 3px;"><span class="zmdi zmdi-comment-text-alt"></span>&nbsp;&nbsp;Remarks</a></div></div></td>
			
				<div class="modal fade" id="viewReason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-time-interval zmdi-hc-2x"></i>&nbsp;&nbsp;Remarks for Extending Delivery</h4>
								<input class="form-control" id="poId" type="hidden" />
							</div>
							<div class="modal-body">
								<div class="row" align="center" style="font-size:16px;">
									<div class="col-lg-12">
										<label>Reason for Extension:</label>
										<p id="reAson"></p>
									</div>
									<div class="col-lg-12">
										<label>Penalty for Extension:</label><br/>
										Php <text id="penalty"></text>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Close</button>
							</div>
						</div>
					</div>
				</div>
			
			<?php
		}
	}

?>