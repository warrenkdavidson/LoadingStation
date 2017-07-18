
<?php
session_start();
$_SESSION["gettingLms"] = 0;

include_once '../global_includes.php';

$wheel = new Wheel($dbLink);


if(isset($_REQUEST["WheelVal"])){
	$rfidForWheel = $_REQUEST['WheelVal'];
	$job1 = $_REQUEST['Job1Val'];
	if(isset($_REQUEST['Job2Val']))
		$job2 = $_REQUEST['Job2Val'];
	else
		$job2 = "none";
	if(isset($_REQUEST['Job3Val']))
		$job3 = $_REQUEST['Job3Val'];
	else
		$job3 = "none";

	$_SESSION["job1"] = $job1;	
	$_SESSION["job2"] = $job2;	
	$_SESSION["job3"] = $job3;	

	$_SESSION["customer_id"] = 9;	

	$wheel->wheel_id = $rfidForWheel;
	$wheel->job1 = $job1;
	$wheel->job2 = $job2;
	$wheel->job3 = $job3;
	$wheel->writeLoadedWheelsCsv();	

	
	$out = shell_exec("bash ../bash/putJobNums_Wheel.sh");
	
}

?>


<html>
<head>
	<title>QTMI XL Loading Station</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../css/style.css" type="text/css" media="screen, projection, tv" />
	<link rel="stylesheet" href="../css/style-print.css" type="text/css" media="print" />
	<link rel='stylesheet' type='text/css' href='../css/loadingStation.css'>

<script src="../css/jquery.min.js"></script>

<script language="javascript" >

var lmsResult;
var lmsResultComp;
var turnOnLms;
lmsResult = "";
lmsResultComp = "";


function TurnGetLmsOn()
{
    $(function() {
		$.get("../bash/turnOn_Lms.php", function( turnOnLms ) {}, 'html');
    });
}

function GetLMS()
{
    $(function() {
		$.get("../bash/getValue_Lms.php", function( lmsResult ) {
			if(lmsResult != 'Null'){
				$('#omRfid').html(lmsResult);
				if(lmsResult.indexOf('ERROR') >= 0){
					$(location).attr('href', 'errorRecipe.php?ErrorMessage=' + lmsResult.indexOf('ERROR') + "--" + lmsResult);		
				}else{
					$('#omRfid').html(lmsResult);
					$(location).attr('href', 'provideLmsData.php?lmsVal='+ lmsResult);		
				}	
			}else{
				$('#omRfid').html('Detecting Lms'); 
			}	
		}, 'html');
    });
}

</script>


<body>
<div id="wrapper">

		<!-- Header -->
		<div id="header">

			<!-- Your website name  -->
			<h1><a href="#">Fusion XL Loading Station</a></h1>
			<!-- Your website name end -->
		

			<div id="header-logo"></div>

		</div>
		<!-- Header end -->

		<!-- Menu -->
		<a href="#skip-menu" class="hidden">Skip menu</a>
		<ul id="menu" class="cleaning-box">
			<li class="first"><a href="startPage.php" class="active">HOME</a></li>
			<li><a href="#">PREVIOUS WEEK<a></li>
			<li><a href="#">PREVIOUS 30 DAYS</a></li>
		</ul>
		<!-- Menu end -->

<hr class="noscreen" />

	<div id="content-box" style="background-color:#626172;">
		<div id="content-box-in">

			<a name="skip-menu"></a>
			<!-- Content left -->
			<div class="content-box-left">

			<!-- Labels for Loaded Wheels -->
			<div>
				<table>
					<!-- Load A Wheel Section -->
					<tr>
						<td colspan=2 class='pageMessageFooter' >Please wait while we detect LMS Recipe</td>
					</tr>
					<tr>
						<td id='omRfid' class='detectingWheel' >No Recipe</td>
					</tr>	
					<tr>
						<td colspan=2  class='pageMessageFooter' ></td>
					</tr>
				</table>
			</div>				
		</div>
	</div>
			<!-- Content left end -->

<hr class="noscreen" />


			<!-- Content right end -->
			<div class="cleaner">&nbsp;</div>
		</div>
	</div>

<hr class="noscreen" />

	<!-- Footer -->
	<div id="footer">
		<p class="left">&copy; <a class="b" href="#">Quantum Innovations</a>, 2017</p>
	</div>
	<!-- Footer end -->
</div>


<script language="javascript" >
	setInterval( GetLMS, 2000 );
</script>

</body>


</html>



<?php
include_once '../close_db.php';
?>





