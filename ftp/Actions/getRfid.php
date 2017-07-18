<?php
exec('C:\wamp64\www\LoadingStation\ftp\CMD\getRfid.cmd');

$rfidValue = "";

if (($handle = fopen("../download/LsOmId.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $rfidValue = $data[$c];
        }
    }
    fclose($handle);
}
exec('C:\wamp64\www\LoadingStation\ftp\CMD\deleteRfid.cmd');

if($rfidValue != 0){
//	exec('C:\wamp64\www\LoadingStation\ftp\CMD\turnOnRfid.cmd');
}

echo $rfidValue;
?>