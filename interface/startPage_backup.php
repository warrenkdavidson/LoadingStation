
<?php
include_once '../global_includes.php';

$wheel = new Wheel($dbLink);

?>

<html>
<head>
	<title>QTMI XL Loading Station</title><link rel='stylesheet' type='text/css' href='../css/loadingStation.css'></head>


<body>

<div class='siteTitle' ><img src="../img/quantum_logo.gif"></br></div>
<div class='pageTitle' >XL LOADING STATION</div>


<!-- Labels for Loaded Wheels -->
<div>
	<table>

		<!-- Loaded Wheels Section -->
		<tr>
			<td colspan=2 class='sectionWheelLoaded' >Loaded Wheels: 3</td>
		</tr>

		<tr>
			<td colspan=2 >
				<?= $wheel->showLoadedWheels() ?>
				<div class='loadedWheelsContainer' >
					<!-- Labels for Loaded Wheels -->
					<div class='loadedWheelsFieldShort' >
					Wheel 
					</div>
					<div class='loadedWheelsFieldShort' >
					Job #
					</div>
					<div class='loadedWheelsFieldLong' >
					Recipe Name
					</div>
					<div class='loadedWheelsFieldShort2' >
					Left Eye Fixture
					</div>					
					<div class='loadedWheelsFieldShort2' >
					Right Eye Fixture
					</div>	

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					C
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					3746
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					Sentinal UV Green
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					B
					</div>					

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					C
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					7346
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					Sentinal UV Green
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					C
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					3758
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					Sentinal UV Green
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>					

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					F
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					3096
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorDark' >
					Crizal Blue
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					F
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					9646
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorDark' >
					Crizal Blue
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>	

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					D
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					7846
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					DES
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					B
					</div>					

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					D
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					2246
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					DES
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>													

				</div>				
			</td>
		</tr>

		<!-- Wheels In Process Section -->
		<tr>
			<td colspan=2 class='sectionWheelInProcess' >Wheels In Process: 2 </td>
		</tr>

		<tr>
			<td colspan=2 >
				<div class='loadedWheelsContainer' >
					<!-- Labels for Loaded Wheels -->
					<div class='loadedWheelsFieldShort' >
					Wheel 
					</div>
					<div class='loadedWheelsFieldShort' >
					Job #
					</div>
					<div class='loadedWheelsFieldLong' >
					Recipe Name
					</div>
					<div class='loadedWheelsFieldShort2' >
					Left Eye Fixture
					</div>					
					<div class='loadedWheelsFieldShort2' >
					Right Eye Fixture
					</div>	

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					W
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					7846
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					DES
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					B
					</div>					

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					W
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					2246
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					DES
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>													

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					S
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					3096
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorDark' >
					Crizal Blue
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					S
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					9646
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorDark' >
					Crizal Blue
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>	


				</div>				
			</td>
		</tr>

		<!-- Wheels In Process Section -->
		<tr>
			<td colspan=2 class='sectionWheelDone' >Wheels Completed: 3 </td>
		</tr>

		<tr>
			<td colspan=2 >
				<div class='loadedWheelsContainer' >
					<!-- Labels for Loaded Wheels -->
					<div class='loadedWheelsFieldShort' >
					Wheel 
					</div>
					<div class='loadedWheelsFieldShort' >
					Job #
					</div>
					<div class='loadedWheelsFieldLong' >
					Recipe Name
					</div>
					<div class='loadedWheelsFieldShort2' >
					Left Eye Fixture
					</div>					
					<div class='loadedWheelsFieldShort2' >
					Right Eye Fixture
					</div>	

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					C
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					3746
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					Sentinal UV Green
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					B
					</div>					

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					C
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					7346
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					Sentinal UV Green
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					C
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					3758
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					Sentinal UV Green
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>					

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					F
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					3096
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorDark' >
					Crizal Blue
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					F
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorDark' >
					9646
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorDark' >
					Crizal Blue
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorDark' >
					B
					</div>	

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					D
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					7846
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					DES
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					M
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					B
					</div>					

					<!-- Values for A Loaded Wheel -->
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					D
					</div>
					<div class='loadedWheelsFieldValueShort lineIndicatorLight' >
					2246
					</div>
					<div class='loadedWheelsFieldValueLong lineIndicatorLight' >
					DES
					</div>
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>					
					<div class='loadedWheelsFieldValueShort2 lineIndicatorLight' >
					F
					</div>													

				</div>				
			</td>
		</tr>



				

				</div>				
			</td>
		</tr>

	</table>
</div>

<div class='pageMessageFooter' >Please use bar code scan to add or update a wheel.</div>
</body>

</html>







