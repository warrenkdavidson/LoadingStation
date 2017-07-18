<!DOCTYPE html>
<html>
<head>


<?php
$barCode = "";

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script language="javascript" >
$(function() {
  $("#BarCodeCommand").focus();
});


$(function() {
$( "#BarCodeCommand" ).on("keyup", function() {
	if($( "#BarCodeCommand" ).val()  == "MS-00002"){
		$("#statusWheelMessageBox").html("Loading Wheel A");
		$("#statusWheelMessageBox").css("background-color", "green")
		//alert( "Loading a wheel");
		$("#Job1").focus();		
	}
    });
});


$(function() {
$( "#Job1" ).on("keyup", function() {
	if($( "#Job1" ).val().length > 3){
//		alert( "Handler for .change() called." + $( "#Box1" ).val().length);
		$("#statusJob1MessageBox").html("Job 1: "+$( "#Job1" ).val());
		$("#statusJob1MessageBox").css("background-color", "green")
		$("#Job2").focus();		
	}
    });
});


$(function() {
$( "#Job2" ).on("keyup", function() {
	if($( "#Job2" ).val().length > 3){
//		alert( "Handler for .change() called." + $( "#Box1" ).val().length);
		$("#statusJob2MessageBox").html("Job 2:  "+$( "#Job2" ).val());
		$("#statusJob2MessageBox").css("background-color", "green")
		$("#Job3").focus();		
	}
    });
});

$(function() {
$( "#Job3" ).on("keyup", function() {
	if($( "#Job3" ).val().length > 3){
//		alert( "Handler for .change() called." + $( "#Box1" ).val().length);
		$("#statusJob3MessageBox").html("Job 3:  "+$( "#Job3" ).val());
		$("#statusJob3MessageBox").css("background-color", "green")		
		$("#BarCodeEndCommand").focus();				
	}
    });
});

$(function() {
$( "#BarCodeEndCommand" ).on("keyup", function() {
	if($( "#BarCodeEndCommand" ).val()  == "VP-F0064"){
		$("#statusWheelEndMessageBox").html("End Loading Wheel A");
		$("#statusWheelEndMessageBox").css("background-color", "green");
		$("#ScannerInputForm").submit();
		
		//alert( "Loading a wheel");
		//$("#Job1").focus();		
	}
    });
});

/*
Form submission
*/  




/*
$(someTextInputField).on("keyup", function() {
  alert($(this).val());
});
*/  
  
</script>
<style >
#mask1 { 
	position: absolute;
	left: 5px;
	top: 5px;
	width: 500px;
	height: 10px;
	background-color: white;	
}


#PageLayout { 
	position: absolute;
	left: 5px;
	top: 15px;
	border-width: 15px;
	border-color: blue; 
	border-style:solid;
	border-radius: 25px;
}


#VisualProgressIndicators { 
	float: left;

	border-width: 15px;
	border-color: #efefef; 
	border-style:solid;
	border-radius: 25px;
}


#ScannerBox { 
	float: left;

}

#ScannerInputLabelsBox { 
	float: left;

}

.BarCodeInputLabel { 
	float: left;
	width: 100px;
}



#FooterBar { 
	float: left;
	width: 400px;
	border-width: 0px;
}

#ScannerInputBox { 
	float: left;
	height: 5px;	
	width: 400px;
	background-color: black;	
}

#BarCodeCommand { 
	float: left;
	height: 1px;
	width: 1px;
}


#Job1 { 
	float: left;
	height: 1px;
	width: 1px;
}

#Job2 { 
	float: left;
	height: 1px;
	width: 1px;
}

#Job3 { 
	float: left;
	height: 1px;
	width: 1px;
}

#BarCodeEndCommand { 
	float: left;
	height: 1px;
	width: 1px;
}

.jobLoadOptionsText {
	font-family: "Arial";
	font-size: 20px;
	padding: 5px;
}

/*
Status Messages As a Wheel Is Loaded
*/
#statusWheelMessageBox { 
	background-color: powderblue;
}

#statusJob1MessageBox { 
	background-color: powderblue;
}

#statusJob2MessageBox { 
	background-color: powderblue;
}

#statusJob3MessageBox { 
	background-color: powderblue;
}

#statusWheelEndMessageBox { 
	background-color: powderblue;
}

</style>

</head>

<body>

<div id="mask1" >
</div>

	<div id="ScannerInputBox" >
		<form id="ScannerInputForm" action="SubmitWheel.php" method="get">
		<input id="BarCodeCommand" type="text" name="barCodeCommand"  />
		<input id="Job1" type="text" name="barCode" />
		<input id="Job2" type="text" name="barCode2" />
		<input id="Job3" type="text" name="barCode3" />
		<input id="BarCodeEndCommand" type="text" name="BarCodeEndCommand" />
		<input id="ScannerInputSubmit" type="submit" value="go" />
		</form>
	</div>

<div id="PageLayout" >
	<div id="VisualProgressIndicators" >
		<div id="ScannerBox" >
			<div id="ScannerInputLabelsBox" >
				<div id="statusWheelMessageBox" class="jobLoadOptionsText" name="statusMessageBox">No Wheel</div>
				<div id="statusJob1MessageBox" class="jobLoadOptionsText" name="statusMessageBox">Job 1</div>
				<div id="statusJob2MessageBox" class="jobLoadOptionsText" name="statusMessageBox">Job 2</div>
				<div id="statusJob3MessageBox" class="jobLoadOptionsText" name="statusMessageBox">Job 3</div>
				<div id="statusWheelEndMessageBox" class="jobLoadOptionsText" name="statusWheelEndMessageBox">No Wheel</div>
			</div>
		</div>
	</div>
</div>





</body>
</html>
