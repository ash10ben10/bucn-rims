
	function change_type_edit( id ){
		document.getElementById('type'+id).disabled = false;
		document.getElementById('edit_'+id).hidden = true;
		document.getElementById('cancel_'+id).hidden = false;
	}
	function change_type_cancel( id ){
		document.getElementById('type'+id).disabled = true;
		document.getElementById('edit_'+id).hidden = false;
		document.getElementById('cancel_'+id).hidden = true;
		window.location.reload();
	}
	function approve_req( id ){
		if(confirm('Are you sure you want to approve this item?'))window.location='req_approve.php?id='+id;
	}
	function set_complete( id ){
		if(confirm('Are you sure you want to set this order item as complete?'))window.location='setDone.php?id='+id;
	}
	function set_incomplete( id ){
		if(confirm('Are you sure you want to set this order item as incomplete?'))window.location='setUNDone.php?id='+id;
	}
	function set_undelivered( id ){
		if(confirm('Are you sure you want to set this order item as undelivered?'))window.location='setUNDel.php?id='+id;
	}
	function cancel_items( id ){
		if(confirm('Are you sure you want to cancel this order? Items that have not set their status under inspection page will be cancelled permanently.'))window.location='cancelReq.php?id='+id;
	}
	
	$(document).ready(function(){
		
		$("#unlikerequest").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#ReqItemNo").val(request_data[0]);
            $("#ReqItemName").text(request_data[1]);
			$("#ReqNo").val(request_data[2]);
            //alert(data);
        });
		
		$("#DisapproveBtn").click(function(event){
			var isOk = true;
			var msg = "Please type your remarks.";
				var $remarks = $("#remarks").val();
				if($remarks == 0 || $remarks == "" || $remarks == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var ReqNo = $("#ReqItemNo").val(); 
					var ReqRemarks = $("#remarks").val(); 
					var prNum = $("#ReqNo").val();

					var hr = new XMLHttpRequest();
						var url = "disappReq.php";
						var vars = "ReqNo="+ReqNo+"&ReqRemarks="+ReqRemarks;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("DisapproveResult").innerHTML = return_data;
							window.location = "view_pr.php?id="+prNum+"";
							
						  }
						}
						hr.send(vars);
						document.getElementById("DisapproveResult").innerHTML = "Disapproving request...";
				}
        });
		
	});