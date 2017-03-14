<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Add Equipment
	if(isset($_POST['submitequipment'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$inputexist = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_name = '$_POST[inputequipment]'"));
		if($inputexist[0] > 0){
			print "<script>alert('".$_POST['inputequipment']." is already in the list of items.')</script>";
		}else{
			mysql_query("LOCK TABLE items WRITE;");
			try{
				mysql_query("INSERT INTO `items`(`item_name`, `item_type`, `category_id`) VALUES 
				(
				'".escapeString($_POST['inputequipment'])."',
				'Equipment',
				'".escapeString($_POST['equipcateg'])."'
				)");
				mysql_query("COMMIT");
			}catch(Exception $e){
				print "<script>alert('Something went wrong when adding the Equipment item. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			#get `items` data
			$getitemid = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` IN (SELECT MAX(item_id) FROM items) ")); //this makes the select last id recorded.
			
			#copy the item_unit information and insert into more_units table
			$itemunitid = "(". implode(", ", $_POST['inputequipmentunit']) .")";
			$select = "SELECT * FROM `item_unit` WHERE `item_unit_id` IN ".$itemunitid."";
			$getius = mysql_query($select);
			
			while($insert = mysql_fetch_array($getius)){
				
				mysql_query("LOCK TABLE more_units WRITE;");
				try{
					mysql_query("INSERT INTO `more_units`(`item_id`, `item_unit_id`) VALUES 
					(
					'$getitemid[item_id]',
					'$insert[item_unit_id]'
					)");
				}catch(Exception $e){
				print "<script>alert('Something went wrong when saving item units for the item. Please check your connection.')</script>";
				}
				mysql_query("UNLOCK TABLE;");
			}
			print "<script>alert('Item has successfully added.');window.location='equipment_label.php';</script>";
		}
	}else if(isset($_POST['editeqpitem'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE items WRITE;");
		try{
			mysql_query("UPDATE `items` SET 
			`item_name` = '".escapeString($_POST['inputequipment'])."',
			`category_id` = '".escapeString($_POST['equipcateg'])."'
			WHERE `item_id` = '$readitem_id'
			");
			mysql_query("COMMIT");
		}catch(Exception $e){
			print "<script>alert('Something went wrong when adding the Equipment item. Please check your connection.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		$getmunitid = mysql_query("SELECT * FROM `more_units` WHERE item_id = '$readitem_id'");
			while($getmunit = mysql_fetch_array($getmunitid)){
				$delmdesc = mysql_query("DELETE FROM `more_desc` WHERE `munit_id` = $getmunit[munit_id]");
			}
		$deleteoldiu = mysql_query("DELETE FROM `more_units` WHERE `item_id` = '$readitem_id'");
		
		#copy the item_unit information and insert into more_units table
		$itemunitid = "(". implode(", ", $_POST['inputequipmentunit']) .")";
		$select = "SELECT * FROM `item_unit` WHERE `item_unit_id` IN ".$itemunitid."";
		$getius = mysql_query($select);
		
		while($insert = mysql_fetch_array($getius)){
			
			mysql_query("LOCK TABLE more_units WRITE;");
			try{
				mysql_query("INSERT INTO `more_units`(`item_id`, `item_unit_id`) VALUES 
				(
				'$readitem_id',
				'$insert[item_unit_id]'
				)");
			}catch(Exception $e){
			print "<script>alert('Something went wrong when saving item units for the item. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
		}
		print "<script>alert('Item has successfully updated.');window.location='equipment_label.php';</script>";
		
	}else if(isset($_POST['editeqprice'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE more_units WRITE;");
		try{
			$getmu = mysql_query("SELECT * FROM `more_units` WHERE `item_id` = '$readitem_id'");
			$row = 1;
			while($mudata = mysql_fetch_array($getmu)){
				$price = escapeString($_POST["itemprice".$row]);
				mysql_query("UPDATE `more_units` SET `price` = '$price' WHERE `item_unit_id` = '$mudata[item_unit_id]'");
				$row++;
			}
			mysql_query("COMMIT");
		}catch(Exception $e){
			print "<script>alert('Something went wrong when setting prices for the item. Please check your connection.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		print "<script>alert('Prices has successfully updated.');window.location='equipment_label.php';</script>";
		
	}

?>

<script>

	$(document).ready(function(){
		
		$("#delEquipment").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#delEquipmentNo").val(request_data[0]);
            $("#delEquipmentName").text(request_data[1]);
            //alert(data);
        });
		
		$("#yesEquipmentBtn").click(function(event){
            var delEquipment = $("#delEquipmentNo").val(); 

            var hr = new XMLHttpRequest();
                var url = "delEquipmentEntry.php";
                var vars = "delEquipment="+delEquipment;

                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                hr.onreadystatechange = function() {
                if(hr.readyState == 4 && hr.status == 200) {
                  var return_data = hr.responseText;
                    document.getElementById("delEquipmentResult").innerHTML = return_data;
                    window.location = "equipment_label.php";
                    
                  }
                }
                hr.send(vars);
                document.getElementById("delEquipmentResult").innerHTML = "Deleting Entry...";
            
        });
		
	});
	
	function submitform(e){
		var isOk = true;
		var msg = "Please fill in the required forms.";
		
		var $equipname = $("#inputequipment").val();
		if($equipname == 0 || $equipname == "" || $equipname == null){ 
					isOk = false;
					msg;
		}
		
		var $equipunit = $("#inputequipmentunit").val();
		if($equipunit == 0 || $equipunit == "" || $equipunit == null){ 
					isOk = false;
					msg;
		}
		
		var $equipcateg = $("#equipcateg").val();
		if($equipcateg == 0 || $equipcateg == "" || $equipcateg == null){ 
					isOk = false;
					msg;
		}
		
		/* var $itemprice = $("#itemprice").val();
		if($itemprice == 0 || $itemprice == "" || $itemprice == null){ 
					isOk = false;
					msg;
		} */
			
		if(!isOk){
			e.preventDefault();
				alert(msg);
				return false;
		}
	}

</script>