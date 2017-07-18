<?php
session_start();
//exec('C:\wamp64\www\LoadingStation\ftp\CMD\turnOffRfid.cmd');
echo "Session = ".$_SESSION["wheelPairs"];

$rfidForWheel = $_REQUEST['wheelId'];
$_SESSION["rfidForWheel"] = $rfidForWheel;


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
function LoadRfidForJobs()
{
    $(function() {
    	$('#wheelForJobs').html('<?= $rfidForWheel ?>');
    });
}

$(function() {
$( "#Job1Val" ).on("keyup", function() {
	if($( "#Job1Val" ).val().length > 3){
		$("#Job2Val").focus();		
	}
    });
});


$(function() {
$( "#Job2Val" ).on("keyup", function() {
	if($( "#Job2Val" ).val().length > 3){
		$("#Job3Val").focus();		
	}
    });
});

$(function() {
$( "#Job3Val" ).on("keyup", function() {
	if($( "#Job3Val" ).val().length > 3){
		$("#DoneCommand").focus();			
	}
    });
});


$(function() {
$( "#DoneCommand" ).on("keyup", function() {
	if($( "#DoneCommand" ).val()  == "VP-F0064"){
		$("#ScannerInputForm").submit();	
	}
    });
});


</script>


<body>
<div id="wrapper">

		<!-- Header -->
		<div id="header">

			<h1><a href="#">Fusion XL Loading Station</a></h1>
		

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
		<div id="content-box-in" >

			<a name="skip-menu"></a>
			<!-- Content left -->
			<div class="content-box-left" >

			<!-- Labels for Loaded Wheels -->
			<div>
				<div class='wheelIdLabel' style="width:600px" >
					Load Jobs for Wheel:
				</div>

				<form id="ScannerInputForm" action="getLmsJob.php" method="get" style="width:600px">
					<div id="ScannerInputBox" >
						<div class="JobLabel">Wheel:</div>
						<div id="Job1">
						<input id="WheelVal" type="text" name="WheelVal" value="<?= $rfidForWheel ?>" />
						</div>
<?php if($_SESSION["wheelPairs"] == "1") { ?>						
						<div class="JobLabel">Job 1:</div>
						<div id="Job1">
						<input id="Job1Val" type="text" name="Job1Val" />
						</div>
<?php }else if($_SESSION["wheelPairs"] == "2") { ?>						
						<div class="JobLabel">Job 1:</div>
						<div id="Job1">
						<input id="Job1Val" type="text" name="Job1Val" />
						</div>
						<div class="JobLabel">Job 2:</div>
						<div id="Job2">
						<input id="Job2Val" type="text" name="Job2Val" />
						</div>
<?php }else if($_SESSION["wheelPairs"] == "3") { ?>						
						<div class="JobLabel">Job 1:</div>
						<div id="Job1">
						<input id="Job1Val" type="text" name="Job1Val" />
						</div>
						<div class="JobLabel">Job 2:</div>
						<div id="Job2">
						<input id="Job2Val" type="text" name="Job2Val" />
						</div>
						<div class="JobLabel">Job 3:</div>
						<div id="Job3">
						<input id="Job3Val" type="text" name="Job3Val" />
						</div>
<?php } ?>						

						<div class="JobLabel"> Done </div><div id="Job3"><input id="DoneCommand" type="text" size="1" /></div>
					</div>
				</form>
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

LoadRfidForJobs();

$("#Job1Val").focus();	
  
</script>

</body>


</html>



<?php
include_once '../close_db.php';
?>





