<?php

	if($getdata['status'] == 'pending' || $getdata['status'] == 'ordered' || $getdata['status'] == 'Delivery Complete' || $getdata['status'] == 'Acceptance Complete'){
			?>
			<button name="strtcount" id="strtcount" class="btn btn-default" title="You cannot order items until funding is completed." disabled><span class="zmdi zmdi-truck"></span>&nbsp;&nbsp;Start Delivery Count</button>
			<?php 
	}else if($getdata['status'] == 'funded'){
		?>
			<a href="strtcount.php?id=<?php print $getdata['po_id']; ?>" name="strtcount" id="strtcount" class="btn btn-default" title="Set Purchase Order as Received and Ordered to the Supplier."><span class="zmdi zmdi-truck"></span>&nbsp;&nbsp;Start Delivery Count</a>
		<?php
	}

?>