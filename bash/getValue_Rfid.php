<?php
session_start();
//$_SESSION["gettingRfid"] = 0;






// Turn RFID on in the HMI, Zero Out the RFID value
if ($_SESSION["gettingRfid"] == 0) {
    // Zero out Rfid on HMI
    $out = shell_exec("cp RfidVal_0.csv RfidVal.csv");
    $out = shell_exec("bash moveZeroRfidToHmi.sh");

    // Let HMI know to write Rfid
    $out = shell_exec("cp lsact_on_r.csv lsact.csv");
    $out = shell_exec("bash turnOn_Rfid.sh");

/*    
    //exec('rm /home/xl-loadingstation/bash/csv/lsact.csv');    
    exec('cp -rf /home/xl-loadingstation/bash/csv/Rfid_turnOn.csv /home/xl-loadingstation/bash/csv/lsact.csv');    
    exec('bash /home/xl-loadingstation/bash/turnOn_Rfid.sh');
    //exec('rm /home/xl-loadingstation/bash/csv/RfidVal.csv');    
    exec('cp /home/xl-loadingstation/bash/csv/zeroRfid.csv /home/xl-loadingstation/bash/RfidVal.csv');
    exec('bash /home/xl-loadingstation/bash/putCsv_Rfid.sh');
    shell_exec("rm 'RfidVal.csv'");
*/        
    $_SESSION["gettingRfid"] = 1;
}

/*
// Turn RFID on in the HMI, Zero Out the RFID value
if ($_SESSION["gettingRfid"] == 0) {
    exec('rm ./csv/lsact.csv');    
    exec('cp -rf ./csv/Rfid_turnOn.csv ./csv/lsact.csv');    
	exec('bash ./turnOn_Rfid.sh');
    exec('rm ./csv/RfidVal.csv');    
	exec('cp ./csv/zeroRfid.csv ./csv/RfidVal.csv');
    exec('bash ./putCsv_Rfid.sh');
	//$_SESSION["gettingRfid"] = 1;
}
*/

//echo $_SESSION["gettingRfid"];


// Now We can wait for the RFID from the HMI

//shell_exec('cd /home/xl-loadingstation/bash/');
//$out = shell_exec("'wget ftp://q:0@10.10.34.100/data/ls/RfidVal.csv' -O '/loadingStation/RfidVal.csv'");
//$out = shell_exec("wget 'ftp://q:0@10.10.34.100/data/ls/RfidVal.csv' -O '/loadingStation/RfidVal.csv'");
//$out = shell_exec("wget 'ftp://q:0@10.10.34.100/data/ls/RfidVal.csv' -O './csv/RfidVal.csv'");
//$out = shell_exec("rm 'RfidVal.csv'");

//$out = shell_exec("cp -rf '/home/xl-loadingstation/bash/csv/Rfid_turnOn.csv' '/home/xl-loadingstation/bash/csv/lsact.csv'");    
//$out = shell_exec("cp lsact_on_r.csv lsact.csv");
//$out = shell_exec("bash turnOn_Rfid.sh");

//$out = shell_exec("wput './lsact.csv' 'ftp://q:0@10.10.34.100/data/ls/lsact.csv'");
//$out = shell_exec("wput './lsact.csv' 'ftp://q:0@10.10.34.100/data/ls/lsact.csv'");

$out = shell_exec("wget 'ftp://q:0@10.10.34.100/data/ls/RfidVal.csv'");

// WPUT lsact_turnOffRfid.csv ftp://q:0@10.10.34.100/data/ls/Lsact.csv
// wget ftp://q:0@10.10.34.100/data/ls/RfidVal.csv /home/xl-loadingstation/bash/RfidVal.csv 


//echo "hi ".$out;


//sleep(2);

$rfidValue = "";

if (($handle = fopen("./RfidVal.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $rfidValue = $data[$c];
        }
    }
    fclose($handle);
}

// Delete Rfid value file to make room for the next read
if($rfidValue == "" || $rfidValue == 0){
    $out = shell_exec("rm RfidVal.csv");
    $out = shell_exec("rm RfidVal.csv.*");
}


if($rfidValue != 0){
    // Let HMI know to turn off Rfid
    $out = shell_exec("cp lsact_off_r.csv lsact.csv");
    $out = shell_exec("bash turnOff_Rfid.sh");

    //exec('rm ./csv/lsact.csv');    
    //exec('cp -rf ./csv/Rfid_turnOff.csv ./csv/lsact.csv');    
    //exec('bash /var/www/html/LoadingStation/bash/turnOn_Rfid.sh');
}


//echo 0;
echo $rfidValue;
//echo $_SESSION["gettingRfid"];


?>