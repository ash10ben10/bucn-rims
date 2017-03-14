	
	<!-- JavaScript Calls -->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../engine/js/jquery-3.1.0.min.js"></script>
	<!-- Sidebar Function -->
	<!--<script src="../engine/js/sidebar_menu.js"></script>-->
	<!-- Bootstrap Core JavaScript (Include all compiled plugins (below), or include individual files as needed) -->
	<script src="../engine/js/bootstrap.js"></script>
	<!-- Material Effects -->
	<script src="../engine/js/ripples.min.js"></script>
	<script src="../engine/js/material.min.js"></script>
	<script src="../engine/js/bootstrap-select.js"></script>
	<script>
		  $(function () {
			$.material.init();
		  });
	</script>
	
	<!-- DataTables JavaScript -->
    <script src="../engine/dataTables/jquery.dataTables.js"></script>
    <script src="../engine/dataTables/dataTables.bootstrap.js"></script>
	<script>
    $(document).ready(function() {
        $('#showTable').DataTable({
                responsive: true,
				order:[[0, "DESC"]]
        });
		$('table.display').DataTable();
    });
    </script>
	<script src="../engine/js/jquery.num2words.js"></script>
	<script src="../engine/js/editFunction.js"></script>