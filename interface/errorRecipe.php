<?php
session_start();
$_SESSION["gettingLms"] = 0;

include_once '../global_includes.php';

?>



<html>
<head>
<title>QTMI XL Loading Station</title><link rel='stylesheet' type='text/css' href='../css/loadingStation.css'>

<script src="../css/jquery.min.js"></script>

<script language="javascript" >

</script>

<script language="javascript" >


function RedirectTo()
{
    $(function() {
		$(location).attr('href', 'getWheel.php');		
    });
}

  
</script>

</head>


<body>

<div class='siteTitle' ><img src="../img/quantum_logo.gif"></br></div>
<div class='pageTitle' >XL LOADING STATION</div>


<div>
	<table>
		<!-- Load A Wheel Section -->
		<tr>
			<td colspan=2 class='pageMessageFooter' >LMS Recipe mis-match</td>
		</tr>
		<tr>
			<td colspan=2 class='pageMessageFooter' >Redirecting to load wheel again</td>
		</tr>
		<tr>
			<td id='omRfid' class='detectingWheel' ><?php echo $_REQUEST['ErrorMessage']; ?></td>
		</tr>	
		<tr>
			<td colspan=2  class='pageMessageFooter' ></td>
		</tr>
	</table>
</div>

</body>


<script language="javascript" >

setInterval( RedirectTo, 8000 );
//GetRfid();
				//$('#omRfid').html('None'); 
  
</script>

</html>







