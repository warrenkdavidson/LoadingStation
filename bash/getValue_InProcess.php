<?php
session_start();
include_once '../global_includes.php';

$wheel = new Wheel($link);

//$_SESSION["gettingRfid"] = 0;

$out = shell_exec("wget 'ftp://q:0@10.10.34.100/data/ls/InProc.csv'");

$inProcValue = "";

if (($handle = fopen("./InProc.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $inProcValue = $data[$c];
        }
    }
    fclose($handle);
}

if($inProcValue != "-1"){
    $wheel->wheelInProcess($inProcValue);

    // Zero out HMI In Process variable
    $out = shell_exec("cp InProc_off.csv InProc.csv");
    $out = shell_exec("bash turnOff_InProc.sh");    
    $out = shell_exec("rm InProc.csv.*");    
    $out = shell_exec("rm InProc.csv"); 
    echo $inProcValue;       
}else{
    $out = shell_exec("rm InProc.csv.*");        
    $out = shell_exec("rm InProc.csv");        
    echo 'no value';       
}



?>