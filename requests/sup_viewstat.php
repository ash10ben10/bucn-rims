<?php

	if($getdata['status'] == 'pending' || $getdata['status'] == 'funded' || $getdata['status'] == 'ordered'){
		?>
		<a class="btn btn-default" title="Purchase is not ready for acceptance." disabled><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create Acceptance</a>
		<?php 
	}else if($getdata['status'] == 'Delivery Complete'){
		
		$count = mysql_fetch_array(mysql_query("SELECT count(*) FROM request_items WHERE po_id ='$getpo[po_id]' AND instat = 'Complete'"));
		if($count[0] == 0){
			?>
				<a class="btn btn-default" title="Purchase is not ready for acceptance." disabled><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create Acceptance</a>
			<?php 
		}else{
			?>
				<a href="add_iar.php?id=<?php echo $getpo['po_id']; ?>" class="btn btn-default" title="Purchase is now ready for acceptance."><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create Acceptance</a>
			<?php 
		}
		
	}else if($getdata['status'] == 'Acceptance Complete'){
		
		$getiar = mysql_fetch_array(mysql_query("SELECT iar_id, iarnumber, iardate FROM inspect_accept_report WHERE po_id = '$getdata[po_id]' "))or die(mysql_error());
	
		?>
		<span class="fa fa-arrow-circle-right fa-1x"></span>&nbsp;&nbsp;&nbsp;<a href="view_iar.php?id=<?php echo $getiar['iar_id'];?>"><?php print $getiar['iarnumber']." dated <i>".date("M j, Y", strtotime($getiar['iardate']))."</i>"; ?></a>
		<?php
		
	}

?>