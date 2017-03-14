<?php

	#Add Supplier
	if(isset($_POST['addsupplier'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$entryexist = mysql_fetch_array(mysql_query("SELECT count(*) FROM supplier WHERE supplier_name = '$_POST[suppliername]' OR supplier_tin_no = '$_POST[suppliertin]' "));
		if($entryexist[0] > 0){
			print "<script>alert('".$_POST['suppliername']." is already in the list of suppliers.')</script>";
		}else{

			mysql_query("LOCK TABLE supplier WRITE;");
			try{
				mysql_query("INSERT INTO `supplier`(`supplier_name`, `supplier_tin_no`, `supplier_contact_no`, `supplier_address`) VALUES ('".mysql_real_escape_string($_POST['suppliername'])."', '".mysql_real_escape_string($_POST['suppliertin'])."', '".mysql_real_escape_string($_POST['suppliercontact'])."', '".mysql_real_escape_string($_POST['supplieraddress'])."') ")or die(mysql_error());
				mysql_query("COMMIT");
				mysql_query("UNLOCK TABLE;");
			}catch(Exception $e){
				print "<script>alert('Something went wrong when adding the supplier info. Please check your connection.')</script>";
			}
			mysql_close();
			print "<script>window.location='suppliers.php'</script>";
		}
	}

?>

<script>

	$(document).ready(function(){
		
        $("#editSupplier").on("show.bs.modal", function(event){
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#editSupplierNo").val(request_data[0]);
            $("#editSupplierTin").val(request_data[1]);
			$("#editSupplierName").val(request_data[2]);
			$("#editSupplierAddress").val(request_data[3]);
			$("#editSupplierContact").val(request_data[4]);
            //alert(data);
        });
		
        $("#saveSupplierBtn").click(function(event){
				var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $editSupplierTin = $("#editSupplierTin").val();
				if($editSupplierTin == 0 || $editSupplierTin == "" || $editSupplierTin == null){
					isOk = false;
					msg;
				}
				
				var $editSupplierName = $("#editSupplierName").val();
				if($editSupplierName == 0 || $editSupplierName == "" || $editSupplierName == null){
					isOk = false;
					msg;
				}
				
				var $editSupplierAddress = $("#editSupplierAddress").val();
				if($editSupplierAddress == 0 || $editSupplierAddress == "" || $editSupplierAddress == null){
					isOk = false;
					msg;
				}
				
				var $editSupplierContact = $("#editSupplierContact").val();
				if($editSupplierContact == 0 || $editSupplierContact == "" || $editSupplierContact == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var saveSupplierNo = $("#editSupplierNo").val();
					var saveSupplierTin = $("#editSupplierTin").val();
					var saveSupplierName = $("#editSupplierName").val();
					var saveSupplierAddress = $("#editSupplierAddress").val();
					var saveSupplierContact = $("#editSupplierContact").val();

					var hr = new XMLHttpRequest();
						var url = "saveSupplier.php";
						var vars = "saveSupplierNo="+saveSupplierNo+"&saveSupplierTin="+saveSupplierTin+"&saveSupplierName="+saveSupplierName+"&saveSupplierAddress="+saveSupplierAddress+"&saveSupplierContact="+saveSupplierContact;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("saveSupplierResult").innerHTML = return_data;
							window.location = "suppliers.php";
							
						  }
						}
						hr.send(vars);
						document.getElementById("saveSupplierResult").innerHTML = "Saving Changes...";
				}
        });
		
        $("#deleteSupplier").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#delSupplierNo").val(request_data[0]);
            $("#delSupplierName").text(request_data[1]);
            //alert(data);
        });
		
        $("#yesSupplierBtn").click(function(event){
            var delSupplier = $("#delSupplierNo").val(); 

            var hr = new XMLHttpRequest();
                var url = "delSupplier.php";
                var vars = "delSupplier="+delSupplier;

                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                hr.onreadystatechange = function() {
                if(hr.readyState == 4 && hr.status == 200) {
                  var return_data = hr.responseText;
                    document.getElementById("delSupplierResult").innerHTML = return_data;
                    window.location = "suppliers.php";
                    
                  }
                }
                hr.send(vars);
                document.getElementById("delSupplierResult").innerHTML = "Deleting Entry...";
            
        });
    });
	
	function submitform(e){
		var isOk = true;
		var msg = "Please fill in the required forms.";
		
		var $suppliertin = $("#suppliertin").val();
		if($suppliertin == 0 || $suppliertin == "" || $suppliertin == null){ 
					isOk = false;
					msg;
		}
		
		var $suppliername = $("#suppliername").val();
		if($suppliername == 0 || $suppliername == "" || $suppliername == null){ 
					isOk = false;
					msg;
		}
		
		var $supplieraddress = $("#supplieraddress").val();
		if($supplieraddress == 0 || $supplieraddress == "" || $supplieraddress == null){ 
					isOk = false;
					msg;
		}
		
		if(!isOk){
			e.preventDefault();
				alert(msg);
				return false;
		}
		
	}

</script>