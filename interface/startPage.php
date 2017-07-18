<?php
session_start();
include_once '../global_includes.php';

$wheel = new Wheel($link);

if(isset($_REQUEST['graphic']))
	$_SESSION['graphic'] = $_REQUEST['graphic'];
else if(!isset($_SESSION['graphic']))
	$_SESSION['graphic'] = "off";

?>


<html>
<head>
	<title>QTMI XL Loading Station</title>

	<!-- CSS -->
	<link rel="stylesheet" href="../css/style.css" type="text/css" media="screen, projection, tv" />
	<link rel="stylesheet" href="../css/style-print.css" type="text/css" media="print" />
	<link rel='stylesheet' type='text/css' href='../css/loadingStation.css'>
	<link rel='stylesheet' type='text/css' href='../css/fixtureSelector.css'>

<script src="../css/jquery.min.js"></script>

<script language="javascript" >


$(function() {
	$( "#DoneCommand" ).on("keyup", function() {
		if($( "#DoneCommand" ).val()=="CH-00197"){
			$("#Load1Pair").submit();	
		}else if($( "#DoneCommand" ).val()=="P-00005"){
			$("#Load2Pair").submit();	
		}else if($( "#DoneCommand" ).val()=="CH-00091"){
			$("#Load3Pair").submit();	
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
			<li><a href="#">PREVIOUS WEEK</a></li>
			<li><a href="#">PREVIOUS 30 DAYS</a></li>
<?php if(($_SESSION['graphic']) == "off"){ ?>			
			<li><a href="startPage.php?graphic=on" style="color:red" >GRAPHIC: OFF</a></li>
<?php } else { ?>			
			<li><a href="startPage.php?graphic=off" style="color:green">GRAPHIC: ON</a></li>
<?php } ?>			
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
				<table>

					<!-- Loaded Wheels Section  -->
					<tr>
						<td colspan=2 class='sectionWheelLoaded' >Loaded Wheels: <?= $wheel->NumLoadedWheels() ?></td>
					</tr>

					<tr>
						<td colspan=2 >
							<?= $wheel->showLoadedWheels() ?>
						</td>
					</tr>

					<!-- In Process Wheels Section -->  
					<tr>
						<td colspan=2 class='sectionWheelInProcess' >In Process Wheels: <?= $wheel->NumInProcessWheels() ?></td>
					</tr>

					<tr>
						<td colspan=2 >
							<?= $wheel->showInProcessWheels() ?>
						</td>
					</tr>

				</div>				
			</td>
		</tr>

	</table>
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


<div class='pageMessageFooter' >Please use bar code scan to add or update a wheel.</div>
<form id="Load1Pair" action="getWheel.php" method="get">
		<input id="wheelPairs1" name="wheelPairs" type="hidden" value="1" />
		<input id="DoneCommand" type="text" size="1" />
</form>

<form id="Load2Pair" action="getWheel.php" method="get">
		<input id="wheelPairs2" name="wheelPairs" type="hidden" value="2" />
</form>

<form id="Load3Pair" action="getWheel.php" method="get">
		<input id="wheelPairs3" name="wheelPairs" type="hidden" value="3" />
</form>

</body>


</html>


<script language="javascript" >

$("#DoneCommand").val('');
$("#DoneCommand").focus();	

$('html, body').animate({ scrollTop: 0 }, 'fast');


setInterval( GetInProcessUpdate, 20000 );

  
            var modal = (function(){
                // Generate the HTML and add it to the document
                $modal = $('<div id="modal"></div>');
                $content = $('<div id="content"></div>');
               $close = $('<a id="close" href="#">close</a>');

                $modal.hide();
                $modal.append($content, $close);

                $(document).ready(function(){
                    $('body').append($modal);                       
                });

                $close.click(function(e){
                    e.preventDefault();
                    $modal.hide();
                    $content.empty();
                });
                // Open the modal
                return function (content) {
                    $content.html(content);
                    // Center the modal in the viewport
                    $modal.css({
                        top: ($(window).height() - $modal.outerHeight()) / 2, 
                        left: ($(window).width() - $modal.outerWidth()) / 2
                    });
                    $modal.show();
                };
            }());

            // Wait until the DOM has loaded before querying the document
            $(document).ready(function(){



<?php if(($_SESSION['graphic']) == "on"){ ?>			
                $(document).ready(function(e){

			    modal("<p><div id='widgetContainer'><?= $wheel->fixtureSortDivs() ?><img width='500' height='545' src='../img/wheelInfographic.jpg' /><img id='fixtureImage' width='500' height='545' src='../img/fix1.jpg' /><br/></div></p>");

			$('#fixtureImage').attr("src", '../img/fix_noFix.jpg');
			
			<?= $wheel->fixtureSortJquery() ?>

			$('#position1')
			.mouseover(function() { 
			    $('#fixtureImage').attr("src", position1Fix);
			})
			.mouseout(function() {
			    $('#fixtureImage').attr("src", '../img/fix_noFix.jpg');
			});		    

			$('#position2')
			.mouseover(function() { 
			    $('#fixtureImage').attr("src", position2Fix);
			})
			.mouseout(function() {
			    $('#fixtureImage').attr("src", '../img/fix_noFix.jpg');
			});		    

			$('#position3')
			.mouseover(function() { 
			    $('#fixtureImage').attr("src", position3Fix);
			})
			.mouseout(function() {
			    $('#fixtureImage').attr("src", '../img/fix_noFix.jpg');
			});		    

			$('#position4')
			.mouseover(function() { 
			    $('#fixtureImage').attr("src", position4Fix);
			})
			.mouseout(function() {
			    $('#fixtureImage').attr("src", '../img/fix_noFix.jpg');
			});		    

			$('#position5')
			.mouseover(function() { 
			    $('#fixtureImage').attr("src", position5Fix);
			})
			.mouseout(function() {
			    $('#fixtureImage').attr("src", '../img/fix_noFix.jpg');
			});		    

			$('#position6')
			.mouseover(function() { 
			    $('#fixtureImage').attr("src", position6Fix);
			})
			.mouseout(function() {
			    $('#fixtureImage').attr("src", '../img/fix_noFix.jpg');
			});		    


			e.preventDefault();
		});
<?php } ?>			

        	
            });
</script>

<?php
include_once '../close_db.php';
?>





