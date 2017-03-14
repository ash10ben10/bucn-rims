<?php

	#Add Unit of Measure
	if(isset($_POST['addcategory'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$entryexist = mysql_fetch_array(mysql_query("SELECT count(*) FROM category WHERE category_name = '$_POST[inputcategory]'"));
		if($entryexist[0] > 0){
			print "<script>alert('".$_POST['inputcategory']." is already in the Categories.')</script>";
		}else{

			mysql_query("LOCK TABLE category WRITE;");
			try{
				mysql_query("INSERT INTO `category`(`category_name`, `category_type`) VALUES (
				'".mysql_real_escape_string($_POST['inputcategory'])."',
				'".mysql_real_escape_string($_POST['selectcatype'])."'
				)")or die(mysql_error());
				mysql_query("COMMIT");
				mysql_query("UNLOCK TABLE;");
			}catch(Exception $e){
				print "<script>alert('Something went wrong when adding the category. Please check your connection.')</script>";
			}
			mysql_close();
			print "<script>window.location='categories.php'</script>";
		}
	}

?>

<script>

	$(document).ready(function(){
		
        $("#editCategory").on("show.bs.modal", function(event){
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#editCategNo").val(request_data[0]);
            $("#editCategName").val(request_data[1]);
			$("#editCategType").val(request_data[2]);
            //alert(data);
        });
		
        $("#saveCategoryBtn").click(function(event){
				var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $editCategName = $("#editCategName").val();
				if($editCategName == 0 || $editCategName == "" || $editCategName == null){
					isOk = false;
					msg;
				}
				
				var $editCategType = $("#editCategType").val();
				if($editCategType == 0 || $editCategType == "" || $editCategType == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var saveCategNo = $("#editCategNo").val();
					var saveCategName = $("#editCategName").val();
					var saveCategType = $("#editCategType").val();

					var hr = new XMLHttpRequest();
						var url = "saveCategory.php";
						var vars = "saveCategNo="+saveCategNo+"&saveCategName="+saveCategName+"&saveCategType="+saveCategType;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("saveCategoryResult").innerHTML = return_data;
							window.location = "categories.php";
							
						  }
						}
						hr.send(vars);
						document.getElementById("saveCategoryResult").innerHTML = "Saving Changes...";
				}
        });
		
        $("#delCategory").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#delCategNo").val(request_data[0]);
            $("#delCategName").text(request_data[1]);
            //alert(data);
        });
		
        $("#yesCategoryBtn").click(function(event){
            var delCateg = $("#delCategNo").val(); 

            var hr = new XMLHttpRequest();
                var url = "delCategory.php";
                var vars = "delCateg="+delCateg;

                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                hr.onreadystatechange = function() {
                if(hr.readyState == 4 && hr.status == 200) {
                  var return_data = hr.responseText;
                    document.getElementById("delCategoryResult").innerHTML = return_data;
                    window.location = "categories.php";
                    
                  }
                }
                hr.send(vars);
                document.getElementById("delCategoryResult").innerHTML = "Deleting Category...";
            
        });
    });
	
	function submitform(e){
		var isOk = true;
		var msg = "Please fill in the required forms.";
		
		var $inputcategory = $("#inputcategory").val();
		if($inputcategory == 0 || $inputcategory == "" || $inputcategory == null){ 
					isOk = false;
					msg;
		}
		
		var isOk = true;
		var msg = "Please fill in the required forms.";
		
		var $selectcatype = $("#selectcatype").val();
		if($selectcatype == 0 || $selectcatype == "" || $selectcatype == null){ 
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