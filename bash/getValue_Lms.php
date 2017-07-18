<?php
session_start();
//exec('bash moveZeroRfidToHmi.sh');



// Turn RFID on in the HMI, Zero Out the RFID value
if ($_SESSION["gettingLms"] == 0) {
    // Zero out Lms on HMI
    $out = shell_exec("cp LmsZero.csv LmsVal.csv");
    $out = shell_exec("bash moveZeroLmsToHmi.sh");

    // Let HMI know to write Lms
    $out = shell_exec("cp lsact_on_lms.csv lsact.csv");
    $out = shell_exec("bash turnOn_Lms.sh");

    $_SESSION["gettingLms"] = 1;

}

$out = shell_exec("wget 'ftp://q:0@10.10.34.100/data/ls/LmsVal.csv'");

$lmsValue = "";

if (($handle = fopen("./LmsVal.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $lmsValue = $data[$c];
        }
    }
    fclose($handle);
}

// Delete Rfid value file to make room for the next read
if($lmsValue == "Null"){
    $out = shell_exec("rm LmsVal.csv");
    $out = shell_exec("rm LmsVal.csv.*");
}else{
    // Let HMI know to turn off Rfid
    $out = shell_exec("cp lsact_off_lms.csv lsact.csv");
    $out = shell_exec("bash turnOff_Lms.sh");    
}


//echo 0;
echo $lmsValue;
//echo $_SESSION["gettingRfid"];
?>