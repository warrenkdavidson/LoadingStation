<?php
session_start();
include_once '../global_includes.php';
$fixtureSort = new FixtureSort($link);


echo $_SESSION["customer_id"];
echo $_SESSION["rfidForWheel"];
echo $_SESSION["job1"];	
echo $_SESSION["job2"];	
echo $_SESSION["job3"];	
echo $_SESSION["lmsVal"]; 

$wheel = new Wheel($link);

if(isset($_REQUEST["SagDepth1"])){
	$SagDepth1 = "7.24";
	$SagDepth2 = "7.24";
	$SagDepth3 = "7.24";
	$SagDepth4 = "7.24";
	$SagDepth5 = "7.24";
	$SagDepth6 = "7.24";

	if($_SESSION["wheelPairs"] == "1" 
		|| $_SESSION["wheelPairs"] == "2"
		|| $_SESSION["wheelPairs"] == "3"){ 
		$SagDepth1 = $fixtureSort->getSagDepth($_REQUEST['SagDepth1']);
		$SagDepth2 = $fixtureSort->getSagDepth($_REQUEST['SagDepth2']);
	}
	if($_SESSION["wheelPairs"] == "2"
		|| $_SESSION["wheelPairs"] == "3"){ 
		$SagDepth3 = $fixtureSort->getSagDepth($_REQUEST['SagDepth3']);
		$SagDepth4 = $fixtureSort->getSagDepth($_REQUEST['SagDepth4']);
	}
	if($_SESSION["wheelPairs"] == "3"){ 
		$SagDepth5 = $fixtureSort->getSagDepth($_REQUEST['SagDepth5']);
		$SagDepth6 = $fixtureSort->getSagDepth($_REQUEST['SagDepth6']);
	}
}


$sag_depths = array();
$planeCalc = new PlaneCalc();
$sag_depths = $planeCalc->wheelPlane(
	$SagDepth1,
	$SagDepth2,
	$SagDepth3,
	$SagDepth4,
	$SagDepth5,
	$SagDepth6
	);

echo "Sag Depths: ";
print_r($sag_depths);


$wheel->customer_id = 9;
$wheel->wheel_id = $_SESSION["rfidForWheel"];
$wheel->job1 = $_SESSION["job1"];
$wheel->job2 = $_SESSION["job2"];
$wheel->job3 = $_SESSION["job3"];
$wheel->wheel_ar = $_SESSION["lmsVal"];
//$wheel->writeLoadedWheelsCsv();
if(isset($_REQUEST["SagDepth1"])){
	$wheel->batch_id1 = 3;
	$wheel->batch_id2 = 3;
	$wheel->batch_id3 = 3;
	$wheel->batch_id4 = 3;
	$wheel->batch_id5 = 3;
	$wheel->batch_id6 = 3;


	if($_SESSION["wheelPairs"] == "1" 
		|| $_SESSION["wheelPairs"] == "2"
		|| $_SESSION["wheelPairs"] == "3"){ 
		$wheel->batch_id1 = $_REQUEST['SagDepth1'];
		$wheel->batch_id2 = $_REQUEST['SagDepth2'];
	}
	if($_SESSION["wheelPairs"] == "2"
		|| $_SESSION["wheelPairs"] == "3"){ 
		$wheel->batch_id3 = $_REQUEST['SagDepth3'];
		$wheel->batch_id4 = $_REQUEST['SagDepth4'];
	}
	if($_SESSION["wheelPairs"] == "3"){ 
		$wheel->batch_id5 = $_REQUEST['SagDepth5'];
		$wheel->batch_id6 = $_REQUEST['SagDepth6'];
	}

}

$wheel->insertLoadedWheel($sag_depths);




	

?>


<script src="../css/jquery.min.js"></script>

<script language="javascript" >
window.location.replace("startPage.php");
</script>


