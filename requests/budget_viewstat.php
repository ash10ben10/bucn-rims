<?php

	if($getdata['status'] == 'pending'){
		?>
		
		<button name="fund" data-id="<?php echo $getpo['po_id']."|{$getpo['ponumber']}|{$getpo['allitem_nums']}" ?>" data-toggle="modal" data-target="#verifyFund" class="btn btn-warning"><i class="zmdi zmdi-balance zmdi-hc-1x"></i>&nbsp;&nbsp;Fund this Order</button>

		<div class="modal fade" id="verifyFund" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form>
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-balance zmdi-hc-2x"></i>&nbsp;&nbsp;Funding for Purchase Order No. <label id="getpoNum"></label></h4>
							<input class="form-control" id="getpoId" type="hidden" />
						</div>
						<div class="modal-body">
							
							<div class="row" align="center">
								<div class="col-lg-1">
								</div>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-lg-4" style="margin-top:10px;" align="left">
											<label>Select Fund:</label>
										</div>
										<div class="col-lg-6" style="margin-top:5px;" align="left">
											<select class="selectpicker form-control" id="fund">
												<option selected disabled>-Select Fund-</option>
												<option value="101">101</option>
												<option value="164">164</option>
												<option value="BEMONC">BEMONC</option>
												<option value="MNCHN">MNCHN</option>
											</select>
										</div>
									</div>
									&nbsp;
									<!--<div class="row">
										<div class="col-lg-4" style="margin-top:5px;" align="left">
											<label>OS Number:</label>
										</div>
										<div class="col-lg-6" align="left">
											<input class="form-control" id="POOSNum" />
										</div>
									</div>-->
									<div class="row">
										<div class="col-lg-4" style="margin-top:5px;" align="left">
											<label>Amount:</label>
										</div>
										<div class="col-lg-6" align="left">
											<input class="form-control" id="getTotalCost" name="getTotalCost" pattern="([0-9.,])+">
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="modal-footer">
							<button type="button" id="savePOFundBtn" class="btn btn-success"><span class="fa fa-thumbs-up fa-fw"></span>&nbsp;Approve Funding</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
						</div>
						<div id="saveFundingResult" style="margin: 10px 0 10px 0;"></div>
					</form>
				</div>
			</div>
		</div>
			
		<?php
	}else if($getdata['status'] == 'funded' || $getdata['status'] == 'ordered' || $getdata['status'] == 'Delivery Complete' || $getdata['status'] == 'Acceptance Complete'){
		?>
			<button name="fund" data-id="<?php echo $getpo['po_id']."|{$getpo['ponumber']}" ?>" data-toggle="modal" data-target="#verifyFund" class="btn btn-warning" disabled title="This Order is already funded."><i class="zmdi zmdi-balance zmdi-hc-1x"></i>&nbsp;&nbsp;Fund this Order</button>
		<?php
	}

?>