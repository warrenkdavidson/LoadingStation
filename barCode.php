<?php
$barCode = "";

if(isset($_REQUEST['barCode'])){
	echo "Bar Code = ".$_REQUEST['barCode'];
	$barCode = $_REQUEST['barCode']; 	
}


/*
$barCodeNums = explode("-", $barCode);

print_r($barCodeNums);

echo hex2str($barCodeNums[0]);
echo hex2str($barCodeNums[1]);
echo hex2str($barCodeNums[2]);
echo hex2str($barCodeNums[3]);


function hex2str($hex) {
    $str = '';
    for($i=0;$i<strlen($hex);$i+=2) $str .= chr(hexdec(substr($hex,$i,2)));
    return $str;
}
*/


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>





<input type="text" name="barCode" />
