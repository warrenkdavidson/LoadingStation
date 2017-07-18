<?php
session_start();
$_SESSION["gettingRfid"] = 0;

if(isset($_REQUEST['wheelPairs']))
	$_SESSION["wheelPairs"] = $_REQUEST['wheelPairs'];

echo "Session = ".$_SESSION["wheelPairs"];

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

var rfidResult;
var rfidResultComp;
var turnOnRfid;
rfidResult = 0;
rfidResultComp = 0;


function TurnRfidReaderOn()
{
    $(function() {
		$.get("../bash/turnOn_Rfid.php", function( turnOnRfid ) {}, 'html');
    });
}

function GetRfid()
{
    $(function() {
		$.get("../bash/getValue_Rfid.php", function( rfidResult ) {
			if(rfidResult > 0){
				$('#omRfid').html(rfidResult);	
				$(location).attr('href', 'getWheelJobs.php?wheelId=' + rfidResult);		
			}else{
				$('#omRfid').html('Detecting Wheel'); 
			}						
		}, 'html');
    });
}
  
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
		<div id="content-box-in">

			<a name="skip-menu"></a>
			<!-- Content left -->
			<div class="content-box-left">

			<!-- Labels for Loaded Wheels -->
			<div>
				<table>
					<!-- Load A Wheel Section -->
					<tr>
						<td id='omRfid' class='detectingWheel' >Detecting Wheel</td><td class='detectingWheelIcon' style="background-color:#626172;" ><img src="../img/loadingIcon.gif" ></td>
					</tr>

					<tr>
						<td colspan=2 class='pageMessageFooter' >Please pass the Wheel in front of the RFID reader</td>
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
	setInterval( GetRfid, 5000 );
</script>

</body>


</html>



<?php
include_once '../close_db.php';
?>





