<div class="panel panel-default" div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
	<div class="panel-heading">
		<form role="form" id="selreportstat" method="POST" name="contentForm" enctype="multipart/form-data">
		<div class="row" style="margin:10px 0 10px 0;">
			<div class="col-lg-12">
				<div class="col-lg-3" align="left">
					<label>Filter Status:</label>
					<select name="filstock" id="filstock" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
						<option value="" selected disabled>- Select -</option>
						<option value="all">All Stocks</option>
						<option value="sa">Stocks Available</option>
						<option value="cls">Critical Level Stocks</option>
					</select>
				</div>
				<div class="col-lg-1" align="left" style="margin: 25px 0 0 0;">
					<button name="submitfilter" id="submitfilter" class="btn btn-success" ><span class="fa fa-arrow-right"></span>&nbsp;&nbsp;View</button>
				</div>
			</div>
		</div>
		</form>
	</div>
	<div class="panel-body">
	
		<?php
		
		if(isset($_POST['submitfilter'])){
			if($_POST['filstock'] == "all"){
				$statsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply'");
			}else if($_POST['filstock'] == "sa"){
				$statsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply' AND su.quantity != 0");
			}else if($_POST['filstock'] == "cls"){
				$statsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply' AND su.quantity <= si.order_point");
			}
			
			if(mysql_num_rows($statsupply) == 0){
				print "<br /><p align=center><i>There are no issued items.</i></p><br />";
			}else{
				print "
				
				<div class='table-responsive'>
				<table class='table table-striped table-bordered table-hover'>
				
					<tbody>
						<tr>
							<td style='font-size:16px;'><center><strong>";
							
							if($_POST['filstock'] == "all"){
								print "REPORT OF ALL INVENTORY SUPPLIES";
							}else if($_POST['filstock'] == "sa"){
								print "REPORT OF AVAILABLE INVENTORY SUPPLIES";
							}else if($_POST['filstock'] == "cls"){
								print "REPORT ON CRITICAL LEVEL OF INVENTORY SUPPLIES";
							}
							
							print "
							</strong></center></td>
						</tr>
						<tr>
							<td>
								<br/>
								<div class='col-lg-12'>
						<table class = 'table table-striped table-bordered table-hover display'>
						<thead>
							<tr>
								
								<th>Stock No.</th>
								<th>Description</th>";
								
								if($_POST['filstock'] == "cls"){
									print "<th>Order Point</th>";
									print "<th>Remaining Quantity</th>";
								}else{
									print "<th>Quantity</th>";
								}
								
								print "
								<th>Category</th>
							</tr>
						</thead>
						<tbody>
				";
				
				while($retdata = mysql_fetch_array($statsupply)){
					$stockitems = mysql_fetch_array(mysql_query("SELECT si.*, su.* FROM stock_units AS su LEFT JOIN stock_items AS si ON si.stock_id = su.stock_id WHERE su.su_id = '$retdata[su_id]'"));
					$items = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$stockitems[item_id]'"));
					$itemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$stockitems[item_unit_id]'"));
					$cat = mysql_fetch_array(mysql_query("SELECT i.item_id, cat.category_name FROM items AS i LEFT JOIN category AS cat ON cat.category_id = i.category_id WHERE i.item_id = '$stockitems[item_id]'"));
					
					print "<tr>";
					print "<td style='text-align:left;vertical-align:middle;'><center>".$stockitems['stock_no']."</center></td>";
					print "<td style='text-align:left;vertical-align:middle;'><center>";?><a href="stockview.php?id=<?php echo $stockitems['su_id']; ?>"><?php print $items['item_name'].", ".$stockitems['description'];?></a><?php print "</center></td>";
					if($_POST['filstock'] == "cls"){
						print "<td style='text-align:left;vertical-align:middle;color:blue;'><center>".$stockitems['order_point']."</center></td>";
						print "<td style='text-align:left;vertical-align:middle;color:red;'><center>".$stockitems['quantity']."</center></td>";
					}else{
						print "";
						print "<td style='text-align:left;vertical-align:middle;'><center>".$stockitems['quantity']."</center></td>";
					}
					print "<td style='text-align:left;vertical-align:middle;'><center>".$cat['category_name']."</center></td>";
					print "</tr>";
				}
				print "</tbody></table></div>";
				?>
				<br/>
				<div class="row">
					<div class="col-lg-12" align="left" style="margin:0px 0 10px 15px;">
					<?php 
					if($_POST['filstock'] == "all"){
						?>
						<a target="_blank" href="print_stockreportall.php"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
						<?php
					}else if($_POST['filstock'] == "sa"){
						?>
						<a target="_blank" href="print_stockreportsa.php"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
						<?php
					}else if($_POST['filstock'] == "cls"){
						?>
						<a target="_blank" href="print_stockreportcls.php"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
						<?php
					}
					?>
					</div>
				</div>
				<?php
			}
			print "
				</td>
				</tr>
				</tbody>
				</table>
			</div>";
		}
			
		?>
	
	</div>
</div>