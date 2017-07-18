<?php
include_once '../global_includes.php';

if(isset($_REQUEST["WheelVal"])){
	$rfidForWheel = $_REQUEST['WheelVal'];
	$job1 = $_REQUEST['Job1Val'];
	$job2 = $_REQUEST['Job2Val'];
	$job3 = $_REQUEST['Job3Val'];

	
	$wheel->customer_id = 9;
	$wheel->wheel_id = $rfidForWheel;
	$wheel->job1 = $job1;
	$wheel->job2 = $job2;
	$wheel->job3 = $job3;
	$wheel->writeLoadedWheelsCsv();
	$wheel->insertLoadedWheel();
}



$wheel = new Wheel($dbLink);

$wheel->readLoadedWheelsCsv();
$wheel->writeWheelsToMachine();




?>


<?= $job1 ?>
<?= $job2 ?>
<?= $job3 ?>



