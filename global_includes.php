<?php
include_once "model/QTMIBaseClass.php"; 
include_once "model/Wheel.php"; 
include_once "model/PlaneCalc.php"; 
include_once "model/FixtureSort.php"; 


/**
initilaze general DB connection
 */

//$link = mysqli_connect("127.0.0.1", "warren", "usnusn", "loading_station");
$link = mysqli_connect("127.0.0.1", "warren", "usnusn", "mysql");


//$sql = "SELECT * FROM `loading_station`.`loaded_wheel` WHERE `loaded_wheel`.`status` = 1 ";
//$result = $link->query($sql);


if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$dbLink = $link;

?>
