<?php
session_start();
include_once '../global_includes.php';

$fixtureSort = new FixtureSort($link);


$lmsVal = $_REQUEST['lmsVal'];
$_SESSION["lmsVal"] = $lmsVal;	


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
		$(function() {
		$( "#DoneCommand" ).on("keyup", function() {
			if($( "#DoneCommand" ).val()  == "VP-F0064"){
				$("#PageNavForm").submit();	
			}
		    });
		});
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
		<div id="content-box-in" >

			<a name="skip-menu"></a>
			<!-- Content left -->
			<div class="content-box-left" >

			<!-- Labels for Loaded Wheels -->
			<div>
				<div class='wheelIdLabel' style="width:800px; color:#FFFFFF" >
					(<?php echo $lmsVal; ?>) Provide Lens Data for Wheel Locations:
				</div>

				<form id="PageNavForm" action="addWheelToDatabase.php" method="get" >
					<div id="LmsInputBox" >
<?php 
	if($_SESSION["wheelPairs"] == "1" 
		|| $_SESSION["wheelPairs"] == "2"
		|| $_SESSION["wheelPairs"] == "3") 
{ ?>	
						<!-- Position 1 -->
						<div class="LmsLabel" style="width:500px; font-size:20px; padding-top: 5px" >Position 1:</div>

						<div class="LmsLabel" >Test Lens Category:</div>
						<div class="LmsInput" >
						<select id="SagDepth1" type="text" name="SagDepth1">
							<?= $fixtureSort->showCalculatedSagOptions() ?>
						</select>
						</div>


						<!-- Position 2 -->
						<div class="LmsLabel" style="width:500px; font-size:20px; padding-top: 5px" >Position 2:</div>

						<div class="LmsLabel" >Test Lens Category:</div>
						<div class="LmsInput" >
						<select id="SagDepth2" type="text" name="SagDepth2">
							<?= $fixtureSort->showCalculatedSagOptions() ?>
						</select>
						</div>

<?php } ?>	
<?php 
	if( $_SESSION["wheelPairs"] == "2"
		|| $_SESSION["wheelPairs"] == "3") 
{ ?>	

						<!-- Position 3 -->
						<div class="LmsLabel" style="width:500px; font-size:20px; padding-top: 5px" >Position 3:</div>

						<div class="LmsLabel" >Test Lens Category:</div>
						<div class="LmsInput" >
						<select id="SagDepth3" type="text" name="SagDepth3">
							<?= $fixtureSort->showCalculatedSagOptions() ?>
						</select>
						</div>


						<!-- Position 4 -->
						<div class="LmsLabel" style="width:500px; font-size:20px; padding-top: 5px" >Position 4:</div>

						<div class="LmsLabel" >Test Lens Category:</div>
						<div class="LmsInput" >
						<select id="SagDepth4" type="text" name="SagDepth4">
							<?= $fixtureSort->showCalculatedSagOptions() ?>
						</select>
						</div>

<?php } ?>	
<?php 
	if($_SESSION["wheelPairs"] == "3") 
{ ?>	

						<!-- Position 5 -->
						<div class="LmsLabel" style="width:500px; font-size:20px; padding-top: 5px" >Position 5:</div>

						<div class="LmsLabel" >Test Lens Category:</div>
						<div class="LmsInput" >
						<select id="SagDepth5" type="text" name="SagDepth5">
							<?= $fixtureSort->showCalculatedSagOptions() ?>
						</select>
						</div>


						<!-- Position 6 -->
						<div class="LmsLabel" style="width:500px; font-size:20px; padding-top: 5px" >Position 6:</div>

						<div class="LmsLabel" >Test Lens Category:</div>
						<div class="LmsInput" >
						<select id="SagDepth6" type="text" name="SagDepth6">
							<?= $fixtureSort->showCalculatedSagOptions() ?>
						</select>
						</div>

<?php } ?>	

						<div class="LmsDone" style="padding-top:10px"> <input type="submit" value="NEXT"> </div>
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

$("#DoneCommand").val('');
$("#DoneCommand").focus();	
  
</script>

</body>


</html>



<?php
include_once '../close_db.php';
?>





