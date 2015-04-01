function validate(firstN,lastN,mobile){
	var Exp1 = /^[A-Za-z]+$/;
	var Exp2 = /^[56789][0-9]{9}$/;
	if (firstN.value.match(Exp1)){
		if (lastN.value.match(Exp1)){
			if (mobile.value.match(Exp2)){
				return true;
			}
			else{
				alert("Error in input...Invalid mobile number");
				return false;
			}
			return true;
		}
		else{
			alert("Error in last name...Only Alphabets Allowed");
			return false;
		}
		return true;
	}
	else{
		alert("Error in first name...Only Alphabets Allowed");
		return false;
	}
}

$(document).ready(function (e) {
	
	$("#userDetails	").on('submit',(function(e) {
		e.preventDefault();
		//This segment displays the validation rule for name text field and send ajax request
		if (validate(document.getElementById('FirstN'),document.getElementById('LastN'),document.getElementById('mobile'))){
			$.ajax({
			url: "process.php", 	  // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data){
						$("#message").html(data);
					}
			});
		}
		
	}));
});