	
	<?php
		
		$sum = 0;
		
		$issuecheck = mysql_query("SELECT * FROM `purchase_request` WHERE `iar_stat` = 'Completed' AND personnel_id = '".$_SESSION['logged_personnel_id']."'");
		$geteqpfocustat = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
		$eqpdispnotif = mysql_query("SELECT * FROM `equipments` WHERE `remarks` = 'Pending for Disposal' and received_by = '".$_SESSION['logged_personnel_id']."'");
	
		if($position['position_name'] == "Supply Officer"){
			$insk = mysql_query("SELECT po.po_id, po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'Inspection Complete'");
			$iar = mysql_query("SELECT * FROM `purchase_request` WHERE iar_stat = 'Completed';");
			$cart = mysql_fetch_array(mysql_query("SELECT count(*) FROM `cart_status` WHERE `cart_status_name` = 'Requested'"));
			$qtylimit = mysql_query("SELECT si.order_point, si.stock_id, su.stock_no, si.item_id, su.item_unit_id, si.description, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply' AND su.quantity <= si.order_point");
			
			if(mysql_num_rows($eqpdispnotif) == 0){
				$dispnotif[0] = 0;
			}else{
				$dispnotif[0] = mysql_num_rows($eqpdispnotif);
			}
			if(mysql_num_rows($geteqpfocustat) == 0){
				$counteqp[0] = 0;
			}else{
				$counteqp[0] = mysql_num_rows($geteqpfocustat);
			}
			if(mysql_num_rows($issuecheck) == 0){
				$issue[0] = 0;
			}else{
				$issue[0] = mysql_num_rows($issuecheck);
			}
			
			if(mysql_num_rows($insk) == 0){
				$accept[0] = 0;
			}else{
				$accept[0] = mysql_num_rows($insk);
			}
			
			if(mysql_num_rows($iar) == 0){
				$iarcount[0] = 0;
			}else{
				$iarcount[0] = mysql_num_rows($iar);
			}
			
			if(mysql_num_rows($qtylimit) == 0){
				$limit[0] = 0;
			}else{
				$limit[0] = mysql_num_rows($qtylimit);
			}
			
			$sum += $accept[0] + $cart[0] + $limit[0] + $iarcount[0] + $issue[0] + $counteqp[0] + $dispnotif[0];
		}else if ($position['position_name'] == "Dean"){
			$pending = mysql_query("SELECT count(*) FROM `request_items` WHERE pr_status = 'pending'");
			
			if(mysql_num_rows($eqpdispnotif) == 0){
				$dispnotif[0] = 0;
			}else{
				$dispnotif[0] = mysql_num_rows($eqpdispnotif);
			}
			if(mysql_num_rows($geteqpfocustat) == 0){
				$counteqp[0] = 0;
			}else{
				$counteqp[0] = mysql_num_rows($geteqpfocustat);
			}
			if(mysql_num_rows($issuecheck) == 0){
				$issue[0] = 0;
			}else{
				$issue[0] = mysql_num_rows($issuecheck);
			}
			
			if(mysql_num_rows($pending) == 0){
				$dean[0] = 0;
			}else{
				$dean[0] = mysql_num_rows($pending);
			}
			
			$sum += $dean[0] + $issue[0] + $counteqp[0] + $dispnotif[0];
		}else if ($position['position_name'] == "BAC Officer"){
			$fundcheck = mysql_query("SELECT po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'funded'");
			
			if(mysql_num_rows($eqpdispnotif) == 0){
				$dispnotif[0] = 0;
			}else{
				$dispnotif[0] = mysql_num_rows($eqpdispnotif);
			}
			if(mysql_num_rows($geteqpfocustat) == 0){
				$counteqp[0] = 0;
			}else{
				$counteqp[0] = mysql_num_rows($geteqpfocustat);
			}
			if(mysql_num_rows($issuecheck) == 0){
				$issue[0] = 0;
			}else{
				$issue[0] = mysql_num_rows($issuecheck);
			}
			
			if(mysql_num_rows($fundcheck) == 0){
				$fund[0] = 0;
			}else{
				$fund[0] = mysql_num_rows($fundcheck);
			}
			
			$sum += $fund[0] + $issue[0] + $counteqp[0] + $dispnotif[0];
		}else if ($position['position_name'] == "Budget Officer"){
			$pf = mysql_query("SELECT po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'pending'");
			
			if(mysql_num_rows($eqpdispnotif) == 0){
				$dispnotif[0] = 0;
			}else{
				$dispnotif[0] = mysql_num_rows($eqpdispnotif);
			}
			if(mysql_num_rows($geteqpfocustat) == 0){
				$counteqp[0] = 0;
			}else{
				$counteqp[0] = mysql_num_rows($geteqpfocustat);
			}
			if(mysql_num_rows($issuecheck) == 0){
				$issue[0] = 0;
			}else{
				$issue[0] = mysql_num_rows($issuecheck);
			}
			
			if(mysql_num_rows($pf) == 0){
				$pfund[0] = 0;
			}else{
				$pfund[0] = mysql_num_rows($pf);
			}
			
			$sum += $pfund[0] + $issue[0] + $counteqp[0] + $dispnotif[0];
		}else if ($position['position_name'] == "Inspection Officer"){
			$inscheck = mysql_query("SELECT po.po_id, po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'delivered'");
			
			if(mysql_num_rows($eqpdispnotif) == 0){
				$dispnotif[0] = 0;
			}else{
				$dispnotif[0] = mysql_num_rows($eqpdispnotif);
			}
			if(mysql_num_rows($geteqpfocustat) == 0){
				$counteqp[0] = 0;
			}else{
				$counteqp[0] = mysql_num_rows($geteqpfocustat);
			}
			if(mysql_num_rows($issuecheck) == 0){
				$issue[0] = 0;
			}else{
				$issue[0] = mysql_num_rows($issuecheck);
			}
			
			if(mysql_num_rows($inscheck) == 0){
				$ins[0] = 0;
			}else{
				$ins[0] = mysql_num_rows($inscheck);
			}
			
			$sum += $ins[0] + $issue[0] + $counteqp[0] + $dispnotif[0];
		}else{
			
			if(mysql_num_rows($eqpdispnotif) == 0){
				$dispnotif[0] = 0;
			}else{
				$dispnotif[0] = mysql_num_rows($eqpdispnotif);
			}
			
			if(mysql_num_rows($geteqpfocustat) == 0){
				$counteqp[0] = 0;
			}else{
				$counteqp[0] = mysql_num_rows($geteqpfocustat);
			}
			
			if(mysql_num_rows($issuecheck) == 0){
				$issue[0] = 0;
			}else{
				$issue[0] = mysql_num_rows($issuecheck);
			}
			
			
			$sum += $issue[0] + $counteqp[0] + $dispnotif[0];
		}
	?>
	
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" style="margin:1px 0 0 0;">
		
		<?php 
		
		if($sum == 0){
			?>
			<i class="zmdi zmdi-notifications-none zmdi-hc-lg"></i>&nbsp;&nbsp;<span class="badge style1"><?php print $sum; ?></span>&nbsp;<b class="caret"></b>
			<?php
		}else{
			?>
			<i class="zmdi zmdi-notifications-active zmdi-hc-lg" style="color:red;"></i>&nbsp;&nbsp;<span class="badge"><?php print $sum; ?></span>&nbsp;<b class="caret" style="color:red;"></b>
			<?php
		}
		?>
		
		<ul class="dropdown-menu dropdown-alerts">
		
			<?php
			
			if($position['position_name'] == "Dean"){
				if($sum == 0){
					?>
					<li align="left" style="margin:10px 0 10px 0;"><a><center><i class="zmdi zmdi-notifications-none zmdi-hc-5x"></i></center><br/>All set! No notifs for today.</a></li>
					<?php
				}else if($sum > 0){
					
					if($issue[0] == 0){
						print "";
					}else if($issue[0] > 0){
						
						$issuecheck = mysql_query("SELECT * FROM `purchase_request` WHERE `iar_stat` = 'Completed' AND personnel_id = '".$_SESSION['logged_personnel_id']."'");
			
						while($issuedata = mysql_fetch_array($issuecheck)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/issuance.php"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your PR No. ".$issuedata['prnum']." has<br/>delivered and accepted.<br/>Please claim for Issuance.";
							?>
							</a></li>
							<?php
							}
					}
					if($dean[0] == 0){
						print "";
					}else{
						$prpending = mysql_fetch_array(mysql_query("SELECT count(*) FROM `request_items` WHERE pr_status = 'pending'"));
			
						?>
						<li align="left" style="margin:10px 0 10px 0;"><a href="requests/req.php"><i class="zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
						<?php
						print "There are ".$prpending[0]." purchase request items that needs your approval.";
						?>
						</a></li>
						<?php
						
					}
					if($counteqp[0] == 0){
						print "";
					}else if($counteqp[0] > 0){
						
						$geteqp = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($eqpstat = mysql_fetch_array($geteqp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$itemname['item_name']." is ".$eqpstat['remarks'].".";
							?>
							</a></li>
							<?php
							}
					}
					if($counteqp[0] == 0){
						print "";
					}else if($counteqp[0] > 0){
						
						$geteqp = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($eqpstat = mysql_fetch_array($geteqp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$itemname['item_name']." is ".$eqpstat['remarks'].".";
							?>
							</a></li>
							<?php
							}
					}
					if($dispnotif[0] == 0){
						print "";
					}else if($dispnotif[0] > 0){
						
						$notifdisp = mysql_query("SELECT * FROM `equipments` WHERE `remarks` = 'Pending for Disposal' and received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($dntf = mysql_fetch_array($notifdisp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$dntf['item_name']." is disposed.";
							?>
							</a></li>
							<?php
							}
					}
				}
				
			}else if($position['position_name'] == "Supply Officer"){
				if($sum == 0){
					?>
					<li align="left" style="margin:10px 0 10px 0;"><a><center><i class="zmdi zmdi-notifications-none zmdi-hc-5x"></i></center><br/>All set! No notifs for today.</a></li>
					<?php
				}else if($sum > 0){
					if($accept[0] == 0){
						print "";
					}else if($accept[0] > 0){
						
						$completeins = mysql_query("SELECT po.po_id, po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'Inspection Complete'");
			
						while($inscdata = mysql_fetch_array($completeins)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/add_iar.php?id=<?php print $inscdata['po_id']; ?>"><i class="zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "PO No. ".$inscdata['ponumber']." has completed inspection.<br/>Create acceptance now.";
							?>
							</a></li>
						<?php
						}
					}
					if($iarcount[0] == 0){
						print "";
					}else if($iarcount[0] > 0){
						
						$getiar = mysql_query("SELECT * FROM `purchase_request` WHERE iar_stat = 'Completed';");
						while($iardata = mysql_fetch_array($getiar)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/add_ris.php?id=<?php print $iardata['pr_id']; ?>"><i class="zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "PR No. ".$iardata['prnum']." is ready for issuance.";
							?>
							</a></li>
						<?php
						}
					}
					if($cart[0] == 0){
						print "";
					}else if($cart[0] > 0){
						?>
						<li align="left" style="margin:10px 0 10px 0;"><a href="supply/stock_req.php"><i class="zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
						<?php
						if($cart[0] == 1){
							print "There is ".$cart[0]." request for stock.";
						}else if($cart[0] > 1){
							print "There are ".$cart[0]." requests for stock.";
						}
						?>
						</a></li>
						<?php
					}
					?>
					<?php
					if($limit[0] == 0){
						print "";
					}else if($limit > 0){
						$getdata = mysql_query("SELECT su.su_id, si.order_point, si.stock_id, su.stock_no, si.item_id, su.item_unit_id, si.description, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id LEFT JOIN items AS i ON i.item_id = si.item_id WHERE si.stock_type = 'Supply' AND su.quantity <= si.order_point");
						
						while($compare = mysql_fetch_array($getdata)){
							if($compare['quantity'] <= $compare['order_point']){
								$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$compare[item_id]'"));
								?>
								<li align="left" style="margin:10px 0 10px 0;"><a href="supply/stockview.php?id=<?php print $compare['su_id']; ?>"><div style="color:red;"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
								
								<?php
								print $itemname['item_name'].", ".$compare['description']." has ".$compare['quantity']." quantity left.";
								?>
								</div></a></li>
								<?php
							}else{
								print "";
							}
						}
					}
					
					if($issue[0] == 0){
						print "";
					}else if($issue[0] > 0){
						
						$issuecheck = mysql_query("SELECT * FROM `purchase_request` WHERE `iar_stat` = 'Completed' AND personnel_id = '".$_SESSION['logged_personnel_id']."'");
			
						while($issuedata = mysql_fetch_array($issuecheck)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/issuance.php"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your PR No. ".$issuedata['prnum']." has<br/>delivered and accepted.<br/>Please claim for Issuance.";
							?>
							</a></li>
							<?php
							}
					}
					if($counteqp[0] == 0){
						print "";
					}else if($counteqp[0] > 0){
						
						$geteqp = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($eqpstat = mysql_fetch_array($geteqp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$itemname['item_name']." is ".$eqpstat['remarks'].".";
							?>
							</a></li>
							<?php
							}
					}
					if($dispnotif[0] == 0){
						print "";
					}else if($dispnotif[0] > 0){
						
						$notifdisp = mysql_query("SELECT * FROM `equipments` WHERE `remarks` = 'Pending for Disposal' and received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($dntf = mysql_fetch_array($notifdisp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$dntf['item_name']." is disposed.";
							?>
							</a></li>
							<?php
							}
					}
					
				}
			}else if($position['position_name'] == "BAC Officer"){
				if($sum == 0){
					?>
					<li align="left" style="margin:10px 0 10px 0;"><a><center><i class="zmdi zmdi-notifications-none zmdi-hc-5x"></i></center><br/>All set! No notifs for today.</a></li>
					<?php
				}else if($sum > 0){
					
					if($issue[0] == 0){
						print "";
					}else if($issue[0] > 0){
						
						$issuecheck = mysql_query("SELECT * FROM `purchase_request` WHERE `iar_stat` = 'Completed' AND personnel_id = '".$_SESSION['logged_personnel_id']."'");
			
						while($issuedata = mysql_fetch_array($issuecheck)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/issuance.php"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your PR No. ".$issuedata['prnum']." has<br/>delivered and accepted.<br/>Please claim for Issuance.";
							?>
							</a></li>
							<?php
							}
					}
					if($fund[0] == 0){
						print "";
					}else if($fund[0] > 0){
						
						$fundok = mysql_query("SELECT po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'funded'");
			
						while($datafundok = mysql_fetch_array($fundok)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/status.php"><i class="zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "PO No. ".$datafundok['ponumber']." is ready for order.";
							?>
							</a></li>
						<?php
						}
					}
					if($counteqp[0] == 0){
						print "";
					}else if($counteqp[0] > 0){
						
						$geteqp = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($eqpstat = mysql_fetch_array($geteqp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$itemname['item_name']." is ".$eqpstat['remarks'].".";
							?>
							</a></li>
							<?php
							}
					}
					if($dispnotif[0] == 0){
						print "";
					}else if($dispnotif[0] > 0){
						
						$notifdisp = mysql_query("SELECT * FROM `equipments` WHERE `remarks` = 'Pending for Disposal' and received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($dntf = mysql_fetch_array($notifdisp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$dntf['item_name']." is disposed.";
							?>
							</a></li>
							<?php
							}
					}
				}
			}else if($position['position_name'] == "Budget Officer"){
				if($sum == 0){
					?>
					<li align="left" style="margin:10px 0 10px 0;"><a><center><i class="zmdi zmdi-notifications-none zmdi-hc-5x"></i></center><br/>All set! No notifs for today.</a></li>
					<?php
				}else if($sum > 0){
					
					if($issue[0] == 0){
						print "";
					}else if($issue[0] > 0){
						
						$issuecheck = mysql_query("SELECT * FROM `purchase_request` WHERE `iar_stat` = 'Completed' AND personnel_id = '".$_SESSION['logged_personnel_id']."'");
			
						while($issuedata = mysql_fetch_array($issuecheck)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/issuance.php"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your PR No. ".$issuedata['prnum']." has<br/>delivered and accepted.<br/>Please claim for Issuance.";
							?>
							</a></li>
							<?php
							}
					}
					if($pfund[0] == 0){
						print "";
					}else if($pfund[0] > 0){
						
						$funding = mysql_query("SELECT po.po_id, po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'pending'");
			
						while($fundata = mysql_fetch_array($funding)){
							?>
						<li align="left" style="margin:10px 0 10px 0;"><a href="requests/view_po2.php?id=<?php print $fundata['po_id']; ?>.php"><i class="zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
						<?php
						print "PO No. ".$fundata['ponumber']." needs funding information.";
						?>
						</a></li>
						<?php
						}
					}
					if($counteqp[0] == 0){
						print "";
					}else if($counteqp[0] > 0){
						
						$geteqp = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($eqpstat = mysql_fetch_array($geteqp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$itemname['item_name']." is ".$eqpstat['remarks'].".";
							?>
							</a></li>
							<?php
							}
					}
					if($dispnotif[0] == 0){
						print "";
					}else if($dispnotif[0] > 0){
						
						$notifdisp = mysql_query("SELECT * FROM `equipments` WHERE `remarks` = 'Pending for Disposal' and received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($dntf = mysql_fetch_array($notifdisp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$dntf['item_name']." is disposed.";
							?>
							</a></li>
							<?php
							}
					}
				}
			}else if($position['position_name'] == "Inspection Officer"){
				if($sum == 0){
					?>
					<li align="left" style="margin:10px 0 10px 0;"><a><center><i class="zmdi zmdi-notifications-none zmdi-hc-5x"></i></center><br/>All set! No notifs for today.</a></li>
					<?php
				}else if($sum > 0){
					
					if($issue[0] == 0){
						print "";
					}else if($issue[0] > 0){
						
						$issuecheck = mysql_query("SELECT * FROM `purchase_request` WHERE `iar_stat` = 'Completed' AND personnel_id = '".$_SESSION['logged_personnel_id']."'");
			
						while($issuedata = mysql_fetch_array($issuecheck)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/issuance.php"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your PR No. ".$issuedata['prnum']." has<br/>delivered and accepted.<br/>Please claim for Issuance.";
							?>
							</a></li>
							<?php
							}
					}
					if($ins[0] == 0){
						print "";
					}else if($ins[0] > 0){
						
						$inscheck = mysql_query("SELECT po.po_id, po.ponumber, rs.status FROM `requisition_status` AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE rs.status = 'delivered';");
						while($insdata = mysql_fetch_array($inscheck)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/add_ins.php?id=<?php print $insdata['po_id']; ?>"><i class="zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "PO No. ".$insdata['ponumber']." is ready for inspection.";
							?>
							</a></li>
						<?php
						}
					}
					if($counteqp[0] == 0){
						print "";
					}else if($counteqp[0] > 0){
						
						$geteqp = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($eqpstat = mysql_fetch_array($geteqp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$itemname['item_name']." is ".$eqpstat['remarks'].".";
							?>
							</a></li>
							<?php
							}
					}
					if($dispnotif[0] == 0){
						print "";
					}else if($dispnotif[0] > 0){
						
						$notifdisp = mysql_query("SELECT * FROM `equipments` WHERE `remarks` = 'Pending for Disposal' and received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($dntf = mysql_fetch_array($notifdisp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$dntf['item_name']." is disposed.";
							?>
							</a></li>
							<?php
							}
					}
				}
			}else{
				if($sum == 0){
					?>
					<li align="left" style="margin:10px 0 10px 0;"><a><center><i class="zmdi zmdi-notifications-none zmdi-hc-5x"></i></center><br/>All set! No notifs for today.</a></li>
					<?php
				}else if($sum > 0){
					
					if($issue[0] == 0){
						print "";
					}else if($issue[0] > 0){
						
						$issuecheck = mysql_query("SELECT * FROM `purchase_request` WHERE `iar_stat` = 'Completed' AND personnel_id = '".$_SESSION['logged_personnel_id']."'");
			
						while($issuedata = mysql_fetch_array($issuecheck)){
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="requests/issuance.php"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your PR No. ".$issuedata['prnum']." has<br/>delivered and accepted.<br/>Please claim for Issuance.";
							?>
							</a></li>
							<?php
							}
					}
					if($counteqp[0] == 0){
						print "";
					}else if($counteqp[0] > 0){
						
						$geteqp = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Under Maintenance|Subect for Disposal|Subject for New Repair' AND received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($eqpstat = mysql_fetch_array($geteqp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$itemname['item_name']." is ".$eqpstat['remarks'].".";
							?>
							</a></li>
							<?php
							}
					}
					if($dispnotif[0] == 0){
						print "";
					}else if($dispnotif[0] > 0){
						
						$notifdisp = mysql_query("SELECT * FROM `equipments` WHERE `remarks` = 'Pending for Disposal' and received_by = '".$_SESSION['logged_personnel_id']."'");
			
						while($dntf = mysql_fetch_array($notifdisp)){
							$itemname = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$eqpstat[item_id]'"));
							?>
							<li align="left" style="margin:10px 0 10px 0;"><a href="equipment/view_eq.php?id=<?php print $eqpstat['eqp_id']; ?>"><i class="zmdi zmdi zmdi-info zmdi-hc-lg"></i>&nbsp;&nbsp;
							<?php
							print "Your ".$dntf['item_name']." is disposed.";
							?>
							</a></li>
							<?php
							}
					}
				}
			}
			
			?>
		
		</ul>
	</a>