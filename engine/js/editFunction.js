	var alpha = /^([A-za-z., ])+$/;
	var numeric = /^([0-9+-])+$/;
	var alphaNumeric = /^([A-za-z0-9@_.\/])+$/;
	
	//javascript for editing infos (viewinfo.php)
	function enable_edit( id ){
		document.getElementById(id).disabled = false;
		
		document.getElementById("edit_"+id).hidden = true;
		document.getElementById("save_"+id).hidden = false;
		document.getElementById("cancel_"+id).hidden = false;
	}
	function cancel_edit( id ){
		document.getElementById(id).disabled = true;
		
		document.getElementById("edit_"+id).hidden = false;
		document.getElementById("save_"+id).hidden = true;
		document.getElementById("cancel_"+id).hidden = true;
	}
	function enable_edit( id ){
		document.getElementById("enable_"+id+"_edit").hidden = true;
		document.getElementById("disable_"+id+"_edit").hidden = false;
		
		switch( id ){
			case "viewinfo":
				document.getElementById("lname").disabled = false;
				document.getElementById("fname").disabled = false;
				document.getElementById("mname").disabled = false;
				document.getElementById("sex").disabled = false;
				document.getElementById("bdate").disabled = false;
				document.getElementById("bplace").disabled = false;
				document.getElementById("cvilstat").disabled = false;
				document.getElementById("address").disabled = false;
				document.getElementById("emailadd").disabled = false;
				document.getElementById("cpnum").disabled = false;
				document.getElementById("prieduc").disabled = false;
				document.getElementById("pe_year").disabled = false;
				document.getElementById("seceduc").disabled = false;
				document.getElementById("se_year").disabled = false;
				document.getElementById("tereduc").disabled = false;
				document.getElementById("bacdeg").disabled = false;
				document.getElementById("te_year").disabled = false;
				document.getElementById("gradsch").disabled = false;
				document.getElementById("masdeg").disabled = false;
				document.getElementById("gs_year").disabled = false;
				document.getElementById("empid").disabled = false;
				document.getElementById("empost").disabled = false;
				
				$("#empost").removeClass("hidden");
				$("#dummy_empost").addClass("hidden");
				var empostCont = $("#empost_cont");
				empostCont.find(".bootstrap-select").removeClass("disabled");
				empostCont.find(".bootstrap-select").find(".btn").removeClass("disabled");
				empostCont.find(".form-group").addClass("hidden");
				empostCont.find(".bootstrap-select").removeClass("hidden");
				empostCont.find(".bootstrap-select").find(".btn").removeClass("hidden");
				empostCont.find(".bootstrap-select").find(".bs-searchbox").find(".form-group").removeClass("hidden");
				
				document.getElementById("postat").disabled = false;
				document.getElementById("postype").disabled = false;
				document.getElementById("emdept").disabled = false;
				
				$("#emdept").removeClass("hidden");
				$("#dummy_emdept").addClass("hidden");
				var emdeptCont = $("#emdept_cont");
				emdeptCont.find(".bootstrap-select").removeClass("disabled");
				emdeptCont.find(".bootstrap-select").find(".btn").removeClass("disabled");
				emdeptCont.find(".form-group").addClass("hidden");
				emdeptCont.find(".bootstrap-select").removeClass("hidden");
				emdeptCont.find(".bootstrap-select").find(".btn").removeClass("hidden");
				emdeptCont.find(".bootstrap-select").find(".bs-searchbox").find(".form-group").removeClass("hidden");
				
				break;
				
			case "stockview":
				document.getElementById("stockdesc").disabled = false;
				document.getElementById("orderpoint").disabled = false;
				
				break;
				
			case "equipment":
				document.getElementById("eqpbrand").disabled = false;
				document.getElementById("eqpsn").disabled = false;
				document.getElementById("eqpdesc").disabled = false;	
				document.getElementById("eqpic").disabled = false;
			
				break;
		}
	}
	function disable_edit( id ){
		document.getElementById("enable_"+id+"_edit").hidden = false;
		document.getElementById("disable_"+id+"_edit").hidden = true;
		
		switch( id ){
			case "viewinfo":
				document.getElementById("lname").disabled = true;
				document.getElementById("fname").disabled = true;
				document.getElementById("mname").disabled = true;
				document.getElementById("sex").disabled = true;
				document.getElementById("bdate").disabled = true;
				document.getElementById("bplace").disabled = true;
				document.getElementById("cvilstat").disabled = true;
				document.getElementById("address").disabled = true;
				document.getElementById("emailadd").disabled = true;
				document.getElementById("cpnum").disabled = true;
				document.getElementById("prieduc").disabled = true;
				document.getElementById("pe_year").disabled = true;
				document.getElementById("seceduc").disabled = true;
				document.getElementById("se_year").disabled = true;
				document.getElementById("tereduc").disabled = true;
				document.getElementById("bacdeg").disabled = true;
				document.getElementById("te_year").disabled = true;
				document.getElementById("gradsch").disabled = true;
				document.getElementById("masdeg").disabled = true;
				document.getElementById("gs_year").disabled = true;
				document.getElementById("empid").disabled = true;
				document.getElementById("empost").disabled = true;
				
				$("#dummy_empost").removeClass("hidden");
				$("#empost").addClass("hidden");
				$("#empost_cont").find(".form-group").removeClass("hidden");
				
				document.getElementById("postat").disabled = true;
				document.getElementById("postype").disabled = true;
				
				$("#dummy_emdept").removeClass("hidden");
				$("#emdept").addClass("hidden");
				$("#emdept_cont").find(".form-group").removeClass("hidden");
				
				break;
				
			case "stockview":
				document.getElementById("stockdesc").disabled = true;
				document.getElementById("orderpoint").disabled = true;
				
				break;
			
			case "equipment":
				document.getElementById("eqpbrand").disabled = true;
				document.getElementById("eqpsn").disabled = true;
				document.getElementById("eqpdesc").disabled = true;	
				document.getElementById("eqpic").disabled = true;
			
				break;
		}
	}