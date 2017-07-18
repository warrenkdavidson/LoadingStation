<?php
include_once 'global_includes.php';

$wheel = new Wheel($dbLink);

// http://127.0.0.1/LoadingStation/SubmitWheel.php?barCodeCommand=MS-00002&barCode=1327&barCode2=1327&barCode3=1327&BarCodeEndCommand=VP-F0064

if(isset($_REQUEST["barCodeCommand"])){
	$wheel->customer_id = 9;
	$wheel->wheel_id = 'A';
	$wheel->job1 = $_REQUEST["barCode"];
	$wheel->job2 = $_REQUEST["barCode2"];
	$wheel->job3 = $_REQUEST["barCode3"];
	$wheel->insertLoadedWheel();
}

?>

<!DOCTYPE html>
<html>
<head>

<link rel='stylesheet' type='text/css' href='css/global_styles.css'>

<?php
//http://127.0.0.1/LoadingStation/SubmitWheel.php?barCodeCommand=MS-00002&barCode=1327&barCode2=1327&barCode3=1327&BarCodeEndCommand=VP-F0064



if(isset($_REQUEST['barCodeCommand'])){
	echo $_REQUEST['barCodeCommand'];
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script language="javascript" >
  
</script>
<style >

</style>

</head>

<body>
<div class="DashboardContainer" >
	<div class="DashboardContent" >
		<div class="MessageBox" >
			<div class="MessageBoxContent" >
				Thank you for adding this wheel
			</div>
		</div>
	</div>
</div>


Wheel Submitted
</body>
</html>
