<?php

	if($getdata['status'] == 'pending' || $getdata['status'] == 'funded'){
		?>
				<a href="add_ins.php?id=<?php echo $getpo['po_id']; ?>" class="btn btn-default" title="Inspection cannot process yet." disabled><i class="fa fa-search fa-fw"></i>&nbsp;&nbsp;Inspect</a>
			<?php 
	}
	else if($getdata['status'] == 'ordered'){
			if($dayss == 0 || $today > $future){
				?>
					<a href="#" class="btn btn-default" title="Inspection cannot process until delivery extension." disabled><i class="fa fa-search fa-fw"></i>&nbsp;&nbsp;Inspect</a>
				<?php 
			}else{
				?>
					<a href="add_ins.php?id=<?php echo $getpo['po_id']; ?>" class="btn btn-default" title="Inspect delivery"><i class="fa fa-search fa-fw"></i>&nbsp;&nbsp;Inspect</a>
				<?php 
			}
			
	}else if($getdata['status'] == 'Delivery Complete' || $getdata['status'] == 'Acceptance Complete'){
		
		$getins = mysql_fetch_array(mysql_query("SELECT i.inspection_date, (CONCAT(p.personnel_fname,' ',p.personnel_lname)) AS full_name FROM inspection AS i LEFT JOIN personnel AS p ON p.personnel_id = i.personnel_id WHERE po_id = '$getdata[po_id]'"));

		print "Completed on ".date("M j, Y", strtotime($getins['inspection_date']))." by ".$getins['full_name'].".";
	}
	
	//if($dayss == 0 || $today > $future)

?>