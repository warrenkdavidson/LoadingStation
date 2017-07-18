
<?php
include_once '../global_includes.php';

$wheel = new Wheel($link);
//$wheel->setLink($dbLink);

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
	if($( "#DoneCommand" ).val()  == "MS-00002"){
		$("#PageNavForm").submit();	
	}
    });
});

function GetInProcessUpdate()
{
    $(function() {
		$.get("../bash/getValue_InProcess.php", function( inProcess ) {
			if(inProcess != 'no value'){
				$(location).attr('href', 'startPage.php');		
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

	<div id="content-box">
		<div id="content-box-in">

			<a name="skip-menu"></a>
			<!-- Content left -->
			<div class="content-box-left">

			<!-- Labels for Loaded Wheels -->
			<div>
			<!-- Content Here -->
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


</body>


</html>



<?php
include_once '../close_db.php';
?>





