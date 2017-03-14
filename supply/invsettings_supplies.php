
<?php include "setting_supply_engine.php"; ?>

	<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
		<div class="panel-heading">
			<div class="row" align="center">
				<div class="col-md-12">
					<div class="col-md-12">
						<form role="form" id="addsupplyname" method="post" name="contentForm" enctype="multipart/form-data" onsubmit="submitform(event);">
							<br />
							<div class="col-lg-12">
								<div class="row">
									<!--<div class="col-lg-2" align="left">
										<label>Stock No.</label>
										<input class="form-control" name="inputstock" id="inputstock" placeholder="Stock No:" required />
									</div>-->
									<div class="col-lg-3" align="left">
										<label>Supply Name:</label>
										<input class="form-control" name="inputsupply" id="inputsupply" placeholder="Name of the item" required />
									</div>
									<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
										<label>Item Unit:</label>
										<select class="selectpicker form-control" multiple name="inputsupplyunit[]" id="inputsupplyunit" data-hide-disabled="true" data-live-search="true" required>
											<?php 
												$query = mysql_query("SELECT * FROM `item_unit`");
												echo "<option selected disabled>-Select Unit-</option>";
												while($row = mysql_fetch_array($query)){
													echo "<option value='".$row['item_unit_id']."'>".ucfirst($row['item_unit_name'])."</option>";
												}
											?>
										</select>
									</div>
									<!--<div class="col-lg-2" style="margin-bottom: 10px;" align="left">
										<label>Unit Cost:</label>
										<input class="form-control" name="itemprice" id="itemprice" placeholder="Item Price" pattern="([0-9])+" min="1" type="number" step="any" required />
									</div>-->
									<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
										<label>Item Category:</label>
										<select class="selectpicker form-control" name="supplycateg" id="supplycateg" data-hide-disabled="true" data-live-search="true" required >
											<?php 
												$query = mysql_query("SELECT * FROM `category` WHERE category_type IN ('Category for Supplies', 'Category for All Items')");
												echo "<option selected disabled>-Select Category-</option>";
												while($row = mysql_fetch_array($query)){
													echo "<option value='".$row['category_id']."'>".ucfirst($row['category_name'])."</option>";
												}
											?>
										</select>
									</div>
									<!--<div class="col-lg-2" style="margin-bottom: 10px;" align="left">
										<label>Critical Limit:</label>
										<input class="form-control" type="number" name="inputlimit" id="inputlimit" placeholder="Set Limit" pattern="([0-9])+" min="1" required />
									</div>-->
									<div class="col-lg-1" align="left" style="margin: 25px 0 0 0;">
										<button name="submitsupply" id="submitsupply" class="btn btn-success" ><span class="fa fa-plus-circle fa-fw"></span>&nbsp;&nbsp;Add</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12">
					
					<br />
					<div class="row">
						<div class="col-lg-12" align="center">
							<?php
								$getsupplies = mysql_query("SELECT * FROM items WHERE item_type = 'Supply' ORDER BY item_name ASC");
								
								if(mysql_num_rows($getsupplies) == 0){
									print "<br /><p align=center><i>There are no available Supply Name/s on the system. Please add one.</i></p><br />";
								}else{
									
									print "
									<div class='table-responsive'>
										<table class = 'table table-striped table-bordered table-hover display' id='showTable'>
									
										<thead>
											<tr>
												<th>Supply Names</th>
												<th>Item Unit/s</th>
												<th>Category</th>
												<th>Option</th>
											</tr>
										</thead>
										<tbody>
									";
									while($getdata = mysql_fetch_array($getsupplies)){
										$getcategory = mysql_fetch_array(mysql_query("SELECT * FROM category WHERE category_id = $getdata[category_id]"));
										
										print "<tr>";
										print "<td style='text-align:center;vertical-align:middle;'>".$getdata['item_name']."</td>";
										print "<td style='text-align:center;vertical-align:middle;'>";
										$munit = mysql_query("SELECT iu.item_unit_name, mu.item_unit_id, mu.price FROM `more_units` AS mu LEFT JOIN item_unit AS iu ON iu.item_unit_id = mu.item_unit_id WHERE mu.item_id = '$getdata[item_id]'");
										if(mysql_num_rows($munit) > 0){
											while($unitnames = mysql_fetch_array($munit)){
												print $unitnames['item_unit_name'];
												print "<br/>";
												/* print $unitnames['item_unit_name']." - ";
												if($unitnames['price'] == 0){
													print "Price is not set.";
													print "<br/>";
												}else{
													print "Php ".number_format($unitnames['price'], 2,'.',',');
													print "<br/><br/>";
												} */
											}
										}
										print "</td>";
										print "<td style='text-align:center;vertical-align:middle;'>".$getcategory['category_name']."</td>";
										print "<td style='text-align:center;vertical-align:middle;'><center>";
										?>
											<!--<button name="edit" data-id="<?php echo $getdata['item_id']."|{$getdata['item_name']}|{$getdata['category_id']}|{$getdata['price']}"?>" class='btn btn-info' data-toggle="modal" data-target="#editSupply" style="margin: 5px 5px 5px 5px;" ><i class="zmdi zmdi-edit zmdi-hc-1x"></i>&nbsp;&nbsp;Edit Info</button>-->
											<a href="edit_item.php?id=<?php print $getdata['item_id']; ?>" class="btn btn-info" title="Edit information about the item." style="margin:5px 0 5px 0;"><i class="zmdi zmdi-edit zmdi-hc-1x"></i>&nbsp;&nbsp;Edit Info</a>
											<!--<a href="edit_price.php?id=<?php print $getdata['item_id']; ?>" class="btn btn-info" title="Create or update prices for the item." style="margin:5px 0 5px 0;"><i class="zmdi zmdi-money-box zmdi-hc-1x"></i>&nbsp;&nbsp;Item Prices</a>-->
											<!--<button name="del" data-id="<?php echo $getdata['item_id']."|{$getdata['item_name']}"?>" class='btn btn-warning' data-toggle="modal" data-target="#delSupply" style="margin: 5px 5px 5px 5px;" ><i class="zmdi zmdi-delete zmdi-hc-1x"></i>&nbsp;&nbsp;Delete</button>-->
										<?php
										print "</center></td>";
										print "</tr>";
									}
									print "</tbody></table></div>";
								}
							?>
								<div class="modal fade" id="delSupply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myModalLabel">Delete Entry</h4>
												<input class="form-control" id="delSupplyNo" type="hidden" />
											</div>
											<div class="modal-body">
												<center>Are you sure you want to delete the supply entry <label id="delSupplyName"></label>?</center>
											</div>
											<div class="modal-footer">
												<button type="button" id="yesSupplyBtn" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;Yes</button>
												<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;No</button>
											</div>
											<div id="delSupplyResult"></div>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>