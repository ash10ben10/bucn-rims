<?php

	#Add Unit of Measure
	if(isset($_POST['addmeasure'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$entryexist = mysql_fetch_array(mysql_query("SELECT count(*) FROM item_unit WHERE item_unit_name = '$_POST[inputmeasure]'"));
		if($entryexist[0] > 0){
			print "<script>alert('".$_POST['inputmeasure']." is already in the unit of measurements.')</script>";
		}else{

			mysql_query("LOCK TABLE item_unit WRITE;");
			try{
				mysql_query("INSERT INTO `item_unit`(`item_unit_name`) VALUES ('".mysql_real_escape_string($_POST['inputmeasure'])."')")or die(mysql_error());
				mysql_query("COMMIT");
				mysql_query("UNLOCK TABLE;");
			}catch(Exception $e){
				print "<script>alert('Something went wrong when adding the unit of measurement. Please check your connection.')</script>";
			}
			mysql_close();
			print "<script>window.location='unitmeasure.php'</script>";
		}
	}

?>

<script>

	$(document).ready(function(){
		
        $("#editUnitMeasure").on("show.bs.modal", function(event){
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#editUnitMeasurementNo").val(request_data[0]);
            $("#editUnitMeasurementName").val(request_data[1]);
            //alert(data);
        });
		
        $("#saveUnitMeasurementBtn").click(function(event){
			var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $editUnitMeasurementName = $("#editUnitMeasurementName").val();
				if($editUnitMeasurementName == 0 || $editUnitMeasurementName == "" || $editUnitMeasurementName == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var saveUnitMeasurementNo = $("#editUnitMeasurementNo").val();
					var saveUnitMeasurementName = $("#editUnitMeasurementName").val();

					var hr = new XMLHttpRequest();
						var url = "saveUnitMeasurement.php";
						var vars = "saveUnitMeasurementNo="+saveUnitMeasurementNo+"&saveUnitMeasurementName="+saveUnitMeasurementName;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("saveUnitMeasurementResult").innerHTML = return_data;
							window.location = "unitmeasure.php";
							
						  }
						}
						hr.send(vars);
						document.getElementById("saveUnitMeasurementResult").innerHTML = "Saving Changes...";
				}
        });
		
        $("#deleteUnitMeasure").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#delUnitMeasurementNo").val(request_data[0]);
            $("#delUnitMeasurementName").text(request_data[1]);
            //alert(data);
        });
		
        $("#yesUnitMeasurementBtn").click(function(event){
            var delUnitMeasurement = $("#delUnitMeasurementNo").val(); 

            var hr = new XMLHttpRequest();
                var url = "delUnitMeasurement.php";
                var vars = "delUnitMeasurement="+delUnitMeasurement;

                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                hr.onreadystatechange = function() {
                if(hr.readyState == 4 && hr.status == 200) {
                  var return_data = hr.responseText;
                    document.getElementById("delUnitMeasurementResult").innerHTML = return_data;
                    window.location = "unitmeasure.php";
                    
                  }
                }
                hr.send(vars);
                document.getElementById("delUnitMeasurementResult").innerHTML = "Deleting Entry...";
            
        });
    });

</script>