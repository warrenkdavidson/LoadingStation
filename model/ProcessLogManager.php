<?php 

class ProcessLogManager extends QtmiBaseClass {
    // declare properties
    public $customer_id = "";
    public $customer_name = "";
    public $customer_code = "";
    public $ip = -1;    
    public $user_name = "";
    public $user_password = "";
    public $home_dir = '/home/warren/QTMI_Manager_server';  
    public $machine_type = "";
 
    public $customers_id = array();
    public $last_error_file_count = array();

    // private ftp properties
    private $conn_id = "";
    private $login_result = "";
    private $error_file_names = array();
    private $data_file_names = array();
    private $process_file_names = array();
    private $voltage_file_names = array();
    private $file_name_index = 0;
    private $today_date = "";
    private $this_output = "";
    public $output_count = -1;
    public $last_output_count = -1;
    public $isConnected = false;    
    public $dataToRetrieve = "";   
    public $today = "";    
    
    // Log error files, so we don't continue to retrieve them
    private $alarm_file_logger = "";


   function __construct($link) {
       parent::__construct($link);
       $this->alarm_file_logger = new AlarmFileLogger($link);
       $this->today = date("Ymd");
      // print "In SubClass constructor\n";
   }

   function __destruct() {
      // parent::__destruct();
      // print "In SubClass deconstructor\n";
   }
	// method declaration
	public function getCustomers() {	
		$query = "SELECT * FROM `qtmi_mgr_server`.`customer`";
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$this->customers_id[] = $row['id'];
			$this->last_error_file_count[$row['id']] = $row['last_error_file_count'];
		}	
		//print_r($this->customers_id);

	}

	// method declaration
	public function setCustomerData() {	
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`customer` WHERE `customer`.`id` = %s ",  mysql_real_escape_string($this->customer_id));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
				$this->customer_id = $row['id'];
				$this->customer_name = $row['name'];
				$this->customer_code = $row['code'];
				$this->ip = $row['ip'];
				$this->user_name = $row['username'];
				$this->user_password = $row['password'];
				$this->last_output_count = $row['last_error_file_count'];				
		}
	}	


	// method declaration
	public function readAllProcessLogDirs() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/process/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." ){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/process/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					// Check if file hase already been logged
					if(!$this->hasFileBeenLogged($csvFile)){
						if($csvFile != "." && $csvFile != ".." ){
							// Log the file
							$this->insertLoggedFile($csvFile);
						}
					}else if ($this->loggedFileIs1($csvFile)){
						echo "CSV Filename = ".$this->csv_process_dir.$csvFile."\n";
						$csv_value_array = $this->csv_to_array($this->csv_process_dir.$csvFile);
						//print_r($csv_value_array);
						$this->insertFusionProcessFiles($csv_value_array);						
						$this->updateLoggedFile($csvFile);
					}						
				} 
			}
		} 		
	}
	
	// method declaration
	public function readAllVoltageLogDirs() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/voltage/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." ){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/voltage/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					// Check if file hase already been logged
					if(!$this->hasVoltageFileBeenLogged($csvFile)){
						if($csvFile != "." && $csvFile != ".."){
							// Log the file
							$this->insertVoltageLoggedFile($csvFile);
							echo "CSV Voltage Filename = ".$this->csv_process_dir.$csvFile."\n";
							$csv_value_array = $this->csv_to_array2($this->csv_process_dir.$csvFile);
							//print_r($csv_value_array);
							$this->insertFusionVoltageFiles($csv_value_array, $csvFolder);						
						}
					}						
				} 
			}
		} 		
	}	
	
	
	// method declaration
	public function readAllAset1LogDirs() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." && $csvFolder != "process" && $csvFolder != "voltage"){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					// Check if file hase already been logged
					if(!$this->hasAsetFileBeenLogged($csvFile) && $csvFile == "aset.csv"){
						if($csvFile != "." && $csvFile != ".." && $csvFile != "process" && $csvFile != "voltage"){
							// Log the file
							$this->insertAsetLoggedFile($csvFolder.'-'.$csvFile);
							echo "CSV Aset Filename = ".$this->csv_process_dir.$csvFile."\n";
							$csv_value_array = $this->csv_to_array2($this->csv_process_dir.$csvFile);
							//print_r($csv_value_array);
							$this->insertFusionAset1Files($csv_value_array, $csvFolder);						
						}
					}						
				} 
			}
		} 		
	}	


	// method declaration
	public function readAllSettingsLogDirs() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." && $csvFolder != "process" && $csvFolder != "voltage"){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					// Check if file hase already been logged
					if(!$this->hasSettingsFileBeenLogged($csvFile) && $csvFile == "settings.csv"){
						if($csvFile != "." && $csvFile != ".." && $csvFile != "process" && $csvFile != "voltage"){
							// Log the file
							$this->insertSettingsLoggedFile($csvFolder.'-'.$csvFile);
							echo "CSV Settings Filename = ".$this->csv_process_dir.$csvFile."\n";
							$csv_value_array = $this->csv_to_array2($this->csv_process_dir.$csvFile);
							//print_r($csv_value_array);
							//$this->insertFusionSettingsFiles($csv_value_array, $csvFolder);						
						}
					}						
				} 
			}
		} 		
	}	

	// method declaration
	public function readAllMaterialLogDirs() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." && $csvFolder != "process" && $csvFolder != "voltage"){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					// Check if file hase already been logged
					if(!$this->hasMaterialFileBeenLogged($csvFile) && $csvFile == "material.csv"){
						if($csvFile != "." && $csvFile != ".." && $csvFile != "process" && $csvFile != "voltage"){
							// Log the file
							$this->insertMaterialLoggedFile($csvFolder.'-'.$csvFile);
							echo "CSV Material Filename = ".$this->csv_process_dir.$csvFile."\n";
							$csv_value_array = $this->csv_to_array3($this->csv_process_dir.$csvFile);
							//print_r($csv_value_array);
							$this->insertFusionMaterialFiles($csv_value_array, $csvFolder);						
						}
					}						
				} 
			}
		} 		
	}	


	// method declaration
	public function readAllAset2LogDirs() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." && $csvFolder != "process" && $csvFolder != "voltage"){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					// Check if file hase already been logged
					if(!$this->hasAset2FileBeenLogged($csvFile) && $csvFile == "aset2.csv"){
						if($csvFile != "." && $csvFile != ".." && $csvFile != "process" && $csvFile != "voltage"){
							// Log the file
							$this->insertAset2LoggedFile($csvFolder.'-'.$csvFile);
							echo "CSV Aset2 Filename = ".$this->csv_process_dir.$csvFile."\n";
							$csv_value_array = $this->csv_to_array2($this->csv_process_dir.$csvFile);
							//print_r($csv_value_array);
							//$this->insertFusionAset2Files($csv_value_array, $csvFolder);						
						}
					}						
				} 
			}
		} 		
	}	

	// method declaration
	public function readAllHoursLogDirs() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." && $csvFolder != "process" && $csvFolder != "voltage"){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					// Check if file hase already been logged
					if(!$this->hasHoursFileBeenLogged($csvFile) && $csvFile == "hours.csv"){
						if($csvFile != "." && $csvFile != ".." && $csvFile != "process" && $csvFile != "voltage"){
							// Log the file
							$this->insertHoursLoggedFile($csvFolder.'-'.$csvFile);
							echo "CSV Hours Filename = ".$this->csv_process_dir.$csvFile."\n";
							$csv_value_array = $this->csv_to_array2($this->csv_process_dir.$csvFile);
							//print_r($csv_value_array);
							$this->insertFusionHoursFiles($csv_value_array, $csvFolder);						
						}
					}						
				} 
			}
		} 		
	}	




function csv_to_array($filename='', $delimiter=',')
{
	if(!file_exists($filename)) echo "filename - ".$filename;

	if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
	while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
	{
	    if(!$header)
		$header = $row;
	    else
		$data[] = array_combine($header, $row);
//		if (!in_array("Comment", $row) && !in_array("Data", $row)) {
		if (!in_array("Comment", $row)) {
		    //echo "Not a valid row";
		    $data[] = array_combine($header, $row);
		}
		//echo $row;
	}
	fclose($handle);
	}
	return $data;
}

function csv_to_array2($filename='', $delimiter=',')
{
	if(!file_exists($filename)) echo "filename - ".$filename;

	if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
	//$row = fgetcsv($handle, 1000, $delimiter);
	while (($row = fgetcsv($handle, 3000, $delimiter)) !== FALSE)
	{
	    if(!$header)
		$header = $row;
	    else
		//$data[] = array_combine($header, $row);
//		if (!in_array("Comment", $row) && !in_array("Data", $row)) {
		    //echo "Not a valid row";
		    $data[] = array_combine($header, $row);

		//echo $row;
	}
	fclose($handle);
	}
	return $data;
}


function csv_to_array3($filename='', $delimiter=',')
{
	if(!file_exists($filename)) echo "filename - ".$filename;

	if(!file_exists($filename) || !is_readable($filename))
	return FALSE;
	
		$header[0] = 'Name';
		$header[1] = 'Active';
		$header[2] = 'ActiveCount';
		$header[3] = 'FrontMag1';
		$header[4] = 'FrontMag2';
		$header[5] = 'FrontMag3';
		$header[6] = 'BackMag4';
		$header[7] = 'BackMag5';
		$header[8] = 'BackMag6';
		$header[9] = 'PowerFront';
		$header[10] = 'PowMode';
		$header[11] = 'PowBack';
		$header[12] = 'PowBackMode';
		$header[13] = 'FreqFront';
		$header[14] = 'FreqBack';
		$header[15] = 'Reverse Time Front';
		$header[16] = 'Reverse Time Back';
		$header[17] = 'Stabilization';
		$header[18] = 'O2SCCM';
		$header[19] = 'AirFrontSCCM';
		$header[20] = 'AirBackSCCM';
		$header[21] = 'Pressure';
		$header[22] = 'PercentO2';
		$header[23] = 'PIDControl';
		$header[24] = 'Pre PID';
		$header[25] = 'Proportional';
		$header[26] = 'Integral';
		$header[27] = 'Derivative';
		$header[28] = 'Sample Rate';
		$header[29] = 'Deposit Time Front';
		$header[30] = 'Deposit Time Back';
		$header[31] = 'Pre Burn';
		$header[32] = 'Condition Target';
		$header[33] = 'Thickness Rate';
		$header[34] = 'MaxPowerWarning';
		$header[35] = 'MaxPowerLimit';
		$header[36] = 'PredPID';
		$header[37] = 'PIDControl';
		$header[38] = 'Layer';
		$header[39] = 'POrV';
		$header[40] = 'UsePID';
		$header[41] = 'PIDSetpointTolerance';
		$header[42] = 'ArMinSccmPid';
		$header[43] = 'ArMaxSccmPid';
		$header[44] = 'FrontValve1';
		$header[45] = 'FrontValve2';
		$header[46] = 'FrontValve3';
		$header[47] = 'BackValve1';
		$header[48] = 'BackValve2';
		$header[49] = 'BackValve3';
	

	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
	//$row = fgetcsv($handle, 1000, $delimiter);
	while (($row = fgetcsv($handle, 3000, $delimiter)) !== FALSE)
	{
	
	    if(!$header)
		$header = $row;
	    else
		//$data[] = array_combine($header, $row);
//		if (!in_array("Comment", $row) && !in_array("Data", $row)) {
		    //echo "Not a valid row";
		    $data[] = array_combine($header, $row);
	
		//print_r($row);
	}
	fclose($handle);
	}
	return $data;
}




/*
	// method declaration
	public function loadErrors() {
		exec('mkdir ../../customers/error_logs');	
		exec('mkdir ../../customers/error_logs/'.$this->customer->code);	
		exec('mkdir ../../customers/error_logs/'.$this->customer->code.'/'.$this->machine_type);	
		$this->csv_dir = '../../customers/error_logs/'.$this->customer->code.'/'.$this->machine_type.'/';
		$csvFiles = scandir($this->csv_dir, 1);
		
		foreach($csvFiles as $csvFile) 
		{ 
			if($csvFile != "." && $csvFile != ".." ){
				if(!$this->hasFileBeenLogged($csvFile)){
					$csv_array = $this->readErrors($csvFile);
					$this->insertErrors($csv_array);
					$this->insertLoggedFile($csvFile);
				}
			}
		} 
	}	
*/
	// method declaration
	public function insertFusionProcessFiles($csv_array) {
		//print_r($csv_array);
		foreach ($csv_array as &$value) {
			if($value['Date'] != "" && $value['Time'] != ""){
			// Default Vals
			$currentCount = -1;
			if($value['Current Count'] != 0)
				$currentCount = $value['Current Count']; 	
			
			$query = sprintf("INSERT INTO `qtmi_mgr_server`.`process_log_full` (
			`id` ,
			`customer_id` ,
			`created_on_date` ,
			`created_on_time` ,
			`Recipe`,
			`Lens ID`,
			`Calibration Run`,
			`Layer #`,
			`Layer Name`,
			`Cal F`,
			`F Pos mm`,
			`F Position Factor`,
			`F Auto Factor`,
			`TF Double F`,
			`TF Single F`,
			`F Final Factor`,
			`F Original Pwr`,
			`F Final Pwr`,
			`F Arc Current Count`,
			`F1 kWh`,
			`F2 kWh`,
			`F3 kWh`,
			`Cal B`,
			`B Pos mm`,
			`B Position Factor`,
			`B Auto Factor`,
			`TF Double B`,
			`TF Single B`,
			`B Final Factor`,
			`B Original Pwr`,
			`B Final Pwr`,
			`B Arc Current Count`,
			`B1 kWh`,
			`B2 kWh`,
			`B3 kWh`,
			`Lens Size`,
			`Lens Constant`,
			`F Lens Factor`,
			`F Original Time`,
			`F Final Time`,
			`B Lens Factor`,
			`B Original Time`,
			`B Final Time`,
			`Original O2`,
			`Final O2`,
			`F AR`,
			`B AR`,
			`F Hydro`,
			`B Hydro`,
			`F Freq`,
			`F Rev Time`,
			`B Freq`,
			`B Rev Time`,
			`Idle`,
			`Base`,
			`Hi`,
			`Lo`,
			`F1 Gas`,
			`F2 Gas`,
			`F3 Gas`,
			`B1 Gas`,
			`B2 Gas`,
			`B3 Gas`,
			`F1 Mag`,
			`F2 Mag`,
			`F3 Mag`,
			`B1 Mag`,
			`B2 Mag`,
			`B3 Mag`,
			`PID On/Off`,
			`PID - Watts/Volts`,
			`Stabilization`,
			`Pressure`,
			`PID SP Tolerance`,
			`Proportional`,
			`Integral`,
			`Derivative`,
			`Sample Rate`,
			`PreBurn`,
			`AR PID Min`,
			`AR PID Max`,
			`Current Count`
				)
				VALUES (
				NULL , 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s' 
				) ON DUPLICATE KEY UPDATE id=id;", 
				mysql_real_escape_string($this->customer_id), 
				mysql_real_escape_string($value['Date']), 
				mysql_real_escape_string($value['Time']), 
				mysql_real_escape_string($value['Recipe']),
				mysql_real_escape_string($value['Lens ID']),
				mysql_real_escape_string($value['Calibration Run']),
				mysql_real_escape_string($value['Layer #']),
				mysql_real_escape_string($value['Layer Name']),
				mysql_real_escape_string($value['Cal F']),
				mysql_real_escape_string($value['F Pos mm']),
				mysql_real_escape_string($value['F Position Factor']),
				mysql_real_escape_string($value['F Auto Factor']),
				mysql_real_escape_string($value['TF Double F']),
				mysql_real_escape_string($value['TF Single F']),
				mysql_real_escape_string($value['F Final Factor']),
				mysql_real_escape_string($value['F Original Pwr']),
				mysql_real_escape_string($value['F Final Pwr']),
				mysql_real_escape_string($value['F Arc Current Count']),
				mysql_real_escape_string($value['F1 kWh']),
				mysql_real_escape_string($value['F2 kWh']),
				mysql_real_escape_string($value['F3 kWh']),
				mysql_real_escape_string($value['Cal B']),
				mysql_real_escape_string($value['B Pos mm']),
				mysql_real_escape_string($value['B Position Factor']),
				mysql_real_escape_string($value['B Auto Factor']),
				mysql_real_escape_string($value['TF Double B']),
				mysql_real_escape_string($value['TF Single B']),
				mysql_real_escape_string($value['B Final Factor']),
				mysql_real_escape_string($value['B Original Pwr']),
				mysql_real_escape_string($value['B Final Pwr']),
				mysql_real_escape_string($value['B Arc Current Count']),
				mysql_real_escape_string($value['B1 kWh']),
				mysql_real_escape_string($value['B2 kWh']),
				mysql_real_escape_string($value['B3 kWh']),
				mysql_real_escape_string($value['Lens Size']),
				mysql_real_escape_string($value['Lens Constant']),
				mysql_real_escape_string($value['F Lens Factor']),
				mysql_real_escape_string($value['F Original Time']),
				mysql_real_escape_string($value['F Final Time']),
				mysql_real_escape_string($value['B Lens Factor']),
				mysql_real_escape_string($value['B Original Time']),
				mysql_real_escape_string($value['B Final Time']),
				mysql_real_escape_string($value['Original O2']),
				mysql_real_escape_string($value['Final O2']),
				mysql_real_escape_string($value['F AR']),
				mysql_real_escape_string($value['B AR']),
				mysql_real_escape_string($value['F Hydro']),
				mysql_real_escape_string($value['B Hydro']),
				mysql_real_escape_string($value['F Freq']),
				mysql_real_escape_string($value['F Rev Time']),
				mysql_real_escape_string($value['B Freq']),
				mysql_real_escape_string($value['B Rev Time']),
				mysql_real_escape_string($value['Idle']),
				mysql_real_escape_string($value['Base']),
				mysql_real_escape_string($value['Hi']),
				mysql_real_escape_string($value['Lo']),
				mysql_real_escape_string($value['F1 Gas']),
				mysql_real_escape_string($value['F2 Gas']),
				mysql_real_escape_string($value['F3 Gas']),
				mysql_real_escape_string($value['B1 Gas']),
				mysql_real_escape_string($value['B2 Gas']),
				mysql_real_escape_string($value['B3 Gas']),
				mysql_real_escape_string($value['F1 Mag']),
				mysql_real_escape_string($value['F2 Mag']),
				mysql_real_escape_string($value['F3 Mag']),
				mysql_real_escape_string($value['B1 Mag']),
				mysql_real_escape_string($value['B2 Mag']),
				mysql_real_escape_string($value['B3 Mag']),
				mysql_real_escape_string($value['PID On/Off']),
				mysql_real_escape_string($value['PID - Watts/Volts']),
				mysql_real_escape_string($value['Stabilization']),
				mysql_real_escape_string($value['Pressure']),
				mysql_real_escape_string($value['PID SP Tolerance']),
				mysql_real_escape_string($value['Proportional']),
				mysql_real_escape_string($value['Integral']),
				mysql_real_escape_string($value['Derivative']),
				mysql_real_escape_string($value['Sample Rate']),
				mysql_real_escape_string($value['PreBurn']),
				mysql_real_escape_string($value['AR PID Min']),
				mysql_real_escape_string($value['AR PID Max']),
				mysql_real_escape_string($value['Lens Count '])				
				);
				echo $query . "\n\n";
				if(mysql_query($query)) echo "inserted!";
			}
		}	
	}

	// method declaration
	public function insertFusionVoltageFiles($csv_array) {
		//print_r($csv_array);
		foreach ($csv_array as &$value) {
			if($value['Date'] != "" && $value['Time'] != ""){
			// Default Vals
			$currentCount = -1;
			if($value['Current Count'] != "")
				$currentCount = $value['Current Count']; 	
			
			$query = sprintf("INSERT INTO `qtmi_mgr_server`.`voltage_log_full` (
			`id` ,
			`customer_id` ,
			`Date` ,
			`Time` ,
			`Type` ,
			`VoltF` ,
			`VoltB` ,
			`PowerF` ,
			`PowerB` ,
			`TurboSpeed` ,
			`DepPressure` ,
			`Material` ,
			`Layer#` ,
			`Recipe` 
				)
				VALUES (
				NULL , 
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'

				) ON DUPLICATE KEY UPDATE id=id;", 
				mysql_real_escape_string($this->customer_id), 
				mysql_real_escape_string($value['Date']),
				mysql_real_escape_string($value['Time']),
				mysql_real_escape_string($value['Type']),
				mysql_real_escape_string($value['Volt F']),
				mysql_real_escape_string($value['Volt B']),
				mysql_real_escape_string($value['Power F']),
				mysql_real_escape_string($value['Power B']),
				mysql_real_escape_string($value['Turbo Speed']),
				mysql_real_escape_string($value['Dep Pressure']),
				mysql_real_escape_string($value['Material']),
				mysql_real_escape_string($value['Layer #']),
				mysql_real_escape_string($value['Recipe'])

				);
				echo $query . "\n\n";
				if(mysql_query($query)) echo "inserted!";
			}
		}	
	}



	// method declaration
	public function insertFusionAset1Files($csv_array, $csvFolder) {
		//print_r($csv_array);
		foreach ($csv_array as &$value) {
			$query = sprintf("INSERT INTO `qtmi_mgr_server`.`fusion_aset1` (
				`id` ,
				`customer_id` ,
				`created_on_date` ,
				`GlowStartPressure` ,
				`DepositionStartPressure` ,
				`HydroStartPressure` ,
				`Atmosphere` ,
				`MinimumDepWheelRPM` ,
				`NumberofSpokesonWheel` ,
				`HydroRVDispenseState` ,
				`GlowArgonSCCM` ,
				`TimetoErrorwhenventingDEPChambertoAtmostphere` ,
				`ValveActuationErrorTimerSetpoint` ,
				`TimetoErrorWhenGettingGlowtoProcessPressure` ,
				`TimetoErrorWhenGettingGlowtoStartPressure` ,
				`TimetoErrorWhenGettingDEPtoStartPressure` ,
				`TimetoErrorWhenGettingHydrotoStartPressure` ,
				`MinimumGlowP/SCurrent` ,
				`DispenseDelayBeforeOpeningHydroRoughValve` ,
				`MiniUnityVersion` ,
				`DelaybetweenopeningglowventandV1valve` ,
				`Delaybeforesensingglowcurrent` ,
				`DepositFrontTime` ,
				`DepositBackTime` ,
				`HydroFrontTime` ,
				`HydroBackTime` ,
				`OvercoatTime` ,
				`AutoTune` ,
				`DelayafterP10atsetpointbeforestartingdeppowersupply` ,
				`PIDTolerance` ,
				`PIDCountforAveraging` ,
				`Pre-PIDtime` ,
				`TimetoErrorwhengettingglowtostartpressureatstartup` ,
				`GlowpressuresetpointatStartup` ,
				`TimetoerrorwhengettinghydrotostartpressureatStartup` ,
				`HydropressuresetpointatStartup` ,
				`HydrodispensetimeatStartup` ,
				`DelayafterdispensebeforeopeningvalvesatStartup` ,
				`OvercoatDispensetimerforstartup` ,
				`TimetoerrorwhengettingDEPtostartpressure2` ,
				`DEPpressuresetpointatstartup` ,
				`Timetoerrorwhengettingturbouptospeed` ,
				`Timetoerrorwhengettingchilleruptotemperature` ,
				`ActivateGlow` ,
				`ActivateBacksideChrome` ,
				`FrontSensorCalibration` ,
				`BackSensorCalibration` ,
				`HardArcCountLimit` ,
				`LensCountSetPoint` ,
				`HydroFire` ,
				`Vent` 
				)
				VALUES (
				NULL , 
				'%s', 
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
				) ON DUPLICATE KEY UPDATE id=id;", 
				mysql_real_escape_string($this->customer_id), 
				mysql_real_escape_string($csvFolder), 
				mysql_real_escape_string($value['Glow Start Pressure']),
				mysql_real_escape_string($value[' Deposition Start Pressure']),
				mysql_real_escape_string($value[' Hydro Start Pressure']),
				mysql_real_escape_string($value[' Atmosphere']),
				mysql_real_escape_string($value[' Minimum Dep Wheel RPM']),
				mysql_real_escape_string($value[' Number of Spokes on Wheel']),
				mysql_real_escape_string($value[' Hydro RV Dispense State']),
				mysql_real_escape_string($value[' Glow Argon SCCM']),
				mysql_real_escape_string($value[' Time to Error when venting DEP Chamber to Atmostphere']),
				mysql_real_escape_string($value[' Valve Actuation Error Timer Setpoint']),
				mysql_real_escape_string($value[' Time to Error When Getting Glow to Process Pressure']),
				mysql_real_escape_string($value[' Time to Error When Getting Glow to Start Pressure']),
				mysql_real_escape_string($value[' Time to Error When Getting DEP to Start Pressure']),
				mysql_real_escape_string($value[' Time to Error When Getting Hydro to Start Pressure']),
				mysql_real_escape_string($value['  Minimum Glow P/S Current']),
				mysql_real_escape_string($value[' Dispense Delay Before Opening Hydro Rough Valve']),
				mysql_real_escape_string($value[' Mini Unity Version']),
				mysql_real_escape_string($value['Delay between opening glow vent and V1 valve']),
				mysql_real_escape_string($value[' Delay before sensing glow current']),
				mysql_real_escape_string($value[' Deposit Front Time']),
				mysql_real_escape_string($value[' Deposit Back Time']),
				mysql_real_escape_string($value[' Hydro Front Time']),
				mysql_real_escape_string($value[' Hydro Back Time']),
				mysql_real_escape_string($value[' Overcoat Time']),
				mysql_real_escape_string($value[' Auto Tune']),
				mysql_real_escape_string($value[' Delay after P10 at setpoint before starting dep power supply']),
				mysql_real_escape_string($value[' PID Tolerance']),
				mysql_real_escape_string($value[' PID Count for Averaging']),
				mysql_real_escape_string($value[' Pre-PID time']),
				mysql_real_escape_string($value[' Time to Error when getting glow to start pressure at startup']),
				mysql_real_escape_string($value[' Glow pressure setpoint at Startup']),
				mysql_real_escape_string($value[' Time to error when getting hydro to start pressure at Startup']),
				mysql_real_escape_string($value[' Hydro pressure setpoint at Startup']),
				mysql_real_escape_string($value[' Hydro dispense time at Startup']),
				mysql_real_escape_string($value[' Delay after dispense before opening valves at Startup']),
				mysql_real_escape_string($value[' Overcoat Dispense timer for startup']),
				mysql_real_escape_string($value[' Time to error when getting DEP to start pressure2']),
				mysql_real_escape_string($value[' DEP pressure set point at startup']),
				mysql_real_escape_string($value[' Time to error when getting turbo up to speed']),
				mysql_real_escape_string($value[' Time to error when getting chiller up to temperature']),
				mysql_real_escape_string($value[' Activate Glow']),
				mysql_real_escape_string($value[' Activate Backside Chrome']),
				mysql_real_escape_string($value[' Front Sensor Calibration']),
				mysql_real_escape_string($value[' Back Sensor Calibration']),
				mysql_real_escape_string($value[' Hard Arc Count Limit']),
				mysql_real_escape_string($value[' Lens Count Set Point']),
				mysql_real_escape_string($value[' Hydro Fire']),
				mysql_real_escape_string($value[' Vent'])

				);
				echo $query . "\n\n";
				if(mysql_query($query)) echo "inserted Aset 1!";
		}	
	}

	// method declaration
	public function insertFusionSettingsFiles($csv_array, $csvFolder) {
		//print_r($csv_array);
		foreach ($csv_array as &$value) {
			$query = sprintf("INSERT INTO `qtmi_mgr_server`.`fusion_settings` (
				`id` ,
				`customer_id` ,
				`created_on_date` ,
				`GlowStartPressure` ,
				`GlowProcessPressure` ,
				`DepositionStartPressure` ,
				`ChromeProcessPressure` ,
				`LowIndexProcessPressure` ,
				`HighIndexProcessPressure` ,
				`LowIndexGasMixture` ,
				`HighIndexGasMixture` ,
				`ChromeGasF/B` ,
				`LowIndexGasF/B` ,
				`HighIndexGasF/B` ,
				`ChromeFrontPower` ,
				`ChromeBackPower` ,
				`LowIndexFrontPower` ,
				`LowIndexBackPower` ,
				`HighIndexFrontPower` ,
				`HighIndexBackPower` ,
				`HydroStartPressure` ,
				`OvercoatStartPressure` ,
				`Atmosphere` 
				)
				VALUES (
				NULL , 
				'%s', 
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
				) ON DUPLICATE KEY UPDATE id=id;", 
				mysql_real_escape_string($this->customer_id), 
				mysql_real_escape_string($csvFolder), 
				mysql_real_escape_string($value['Glow Start Pressure']),
				mysql_real_escape_string($value[' Glow Process Pressure']),
				mysql_real_escape_string($value[' Deposition Start Pressure']),
				mysql_real_escape_string($value[' Chrome Process Pressure']),
				mysql_real_escape_string($value[' Low Index Process Pressure']),
				mysql_real_escape_string($value[' High Index Process Pressure']),
				mysql_real_escape_string($value[' Low Index Gas Mixture']),
				mysql_real_escape_string($value[' High Index Gas Mixture']),
				mysql_real_escape_string($value[' Chrome Gas F/B']),
				mysql_real_escape_string($value[' Low Index Gas F/B']),
				mysql_real_escape_string($value[' High Index Gas F/B']),
				mysql_real_escape_string($value[' Chrome Front Power']),
				mysql_real_escape_string($value[' Chrome Back Power']),
				mysql_real_escape_string($value[' Low Index Front Power']),
				mysql_real_escape_string($value[' Low Index Back Power']),
				mysql_real_escape_string($value[' High Index Front Power']),
				mysql_real_escape_string($value[' High Index Back Power']),
				mysql_real_escape_string($value[' Hydro Start Pressure']),
				mysql_real_escape_string($value[' Overcoat Start Pressure']),
				mysql_real_escape_string($value[' Atmosphere'])

				);
				echo $query . "\n\n";
				if(mysql_query($query)) echo "inserted Settings!";
		}	
	}	


	// method declaration
	public function insertFusionMaterialFiles($csv_array, $csvFolder) {
//		echo "MAterials Array ----";
//		print_r($csv_array);


		foreach ($csv_array as &$value) {
//			if($value[0] != ""){
			if (array_key_exists('Name', $value) && strlen($value['Name']) > 0) {
				$query = sprintf("INSERT INTO `qtmi_mgr_server`.`fusion_materials` (
					`id` ,
					`customer_id` ,
					`created_on_date` ,
					`Name` ,
					`Active` ,
					`ActiveCount` ,
					`FrontMag1` ,
					`FrontMag2` ,
					`FrontMag3` ,
					`BackMag4` ,
					`BackMag5` ,
					`BackMag6` ,
					`PowerFront` ,
					`PowMode` ,
					`PowBack` ,
					`PowBackMode` ,
					`FreqFront` ,
					`FreqBack` ,
					`ReverseTimeFront` ,
					`ReverseTimeBack` ,
					`Stabilization` ,
					`O2SCCM` ,
					`AirFrontSCCM` ,
					`AirBackSCCM` ,
					`Pressure` ,
					`PercentO2` ,
					`PIDControl` ,
					`PrePID` ,
					`Proportional` ,
					`Integral` ,
					`Derivative` ,
					`SampleRate` ,
					`DepositTimeFront` ,
					`DepositTimeBack` ,
					`PreBurn` ,
					`ConditionTarget` ,
					`ThicknessRate` ,
					`MaxPowerWarning` ,
					`MaxPowerLimit` ,
					`PredPID` ,
					`PIDControl2` ,
					`Layer` ,
					`POrV` ,
					`UsePID` ,
					`PIDSetpointTolerance` ,
					`ArMinSccmPid` ,
					`ArMaxSccmPid` ,
					`FrontValve1` ,
					`FrontValve2` ,
					`FrontValve3` ,
					`BackValve1` ,
					`BackValve2` ,
					`BackValve3` 
					)
					VALUES (
					NULL , 
					'%s', 
					'%s', 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
					) ON DUPLICATE KEY UPDATE id=id;", 
					mysql_real_escape_string($this->customer_id), 
					mysql_real_escape_string($csvFolder),
					mysql_real_escape_string($value['Name']),
					mysql_real_escape_string($value['Active']),
					mysql_real_escape_string($value['ActiveCount']),
					mysql_real_escape_string($value['FrontMag1']),
					mysql_real_escape_string($value['FrontMag2']),
					mysql_real_escape_string($value['FrontMag3']),
					mysql_real_escape_string($value['BackMag4']),
					mysql_real_escape_string($value['BackMag5']),
					mysql_real_escape_string($value['BackMag6']),
					mysql_real_escape_string($value['PowerFront']),
					mysql_real_escape_string($value['PowMode']),
					mysql_real_escape_string($value['PowBack']),
					mysql_real_escape_string($value['PowBackMode']),
					mysql_real_escape_string($value['FreqFront']),
					mysql_real_escape_string($value['FreqBack']),
					mysql_real_escape_string($value['Reverse Time Front']),
					mysql_real_escape_string($value['Reverse Time Back']),
					mysql_real_escape_string($value['Stabilization']),
					mysql_real_escape_string($value['O2SCCM']),
					mysql_real_escape_string($value['AirFrontSCCM']),
					mysql_real_escape_string($value['AirBackSCCM']),
					mysql_real_escape_string($value['Pressure']),
					mysql_real_escape_string($value['PercentO2']),
					mysql_real_escape_string($value['PIDControl']),
					mysql_real_escape_string($value['Pre PID']),
					mysql_real_escape_string($value['Proportional']),
					mysql_real_escape_string($value['Integral']),
					mysql_real_escape_string($value['Derivative']),
					mysql_real_escape_string($value['Sample Rate']),
					mysql_real_escape_string($value['Deposit Time Front']),
					mysql_real_escape_string($value['Deposit Time Back']),
					mysql_real_escape_string($value['Pre Burn']),
					mysql_real_escape_string($value['Condition Target']),
					mysql_real_escape_string($value['Thickness Rate']),
					mysql_real_escape_string($value['MaxPowerWarning']),
					mysql_real_escape_string($value['MaxPowerLimit']),
					mysql_real_escape_string($value['PredPID']),
					mysql_real_escape_string($value['PIDControl']),
					mysql_real_escape_string($value['Layer']),
					mysql_real_escape_string($value['POrV']),
					mysql_real_escape_string($value['UsePID']),
					mysql_real_escape_string($value['PIDSetpointTolerance']),
					mysql_real_escape_string($value['ArMinSccmPid']),
					mysql_real_escape_string($value['ArMaxSccmPid']),
					mysql_real_escape_string($value['FrontValve1']),
					mysql_real_escape_string($value['FrontValve2']),
					mysql_real_escape_string($value['FrontValve3']),
					mysql_real_escape_string($value['BackValve1']),
					mysql_real_escape_string($value['BackValve2']),
					mysql_real_escape_string($value['BackValve3'])

					);
					echo $query . "\n\n";
					if(mysql_query($query)) echo "inserted Materials!";
				}
		}
	}	


	// method declaration
	public function insertFusionAset2Files($csv_array, $csvFolder) {
		//print_r($csv_array);
		foreach ($csv_array as &$value) {
			$query = sprintf("INSERT INTO `qtmi_mgr_server`.`fusion_aset2` (
				`id` ,
				`customer_id` ,
				`created_on_date` ,
				`LensCountSetPoint` ,
				`HydroFire` ,
				`Vent` ,
				`GlowHydroRPLimit` ,
				`DepRPLimit` 
				)
				VALUES (
				NULL , 
				'%s', 
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
				) ON DUPLICATE KEY UPDATE id=id;", 
				mysql_real_escape_string($this->customer_id), 
				mysql_real_escape_string($csvFolder), 
				mysql_real_escape_string($value['Lens Count Set Point']),
				mysql_real_escape_string($value[' Hydro Fire']),
				mysql_real_escape_string($value[' Vent']),
				mysql_real_escape_string($value[' Glow Hydro RP Limit']),
				mysql_real_escape_string($value[' Dep RP Limit'])

				);
				echo $query . "\n\n";
				//if(mysql_query($query)) echo "inserted Settings!";
		}	
	}	


	// method declaration
	public function insertFusionHoursFiles($csv_array, $csvFolder) {
		//print_r($csv_array);
		foreach ($csv_array as &$value) {
			$query = sprintf("INSERT INTO `qtmi_mgr_server`.`fusion_hours` (
				`id` ,
				`customer_id` ,
				`created_on_date` ,
				`TurboOn` ,
				`WaterChillerRunTime` ,
				`Glow/HydroRPOn` ,
				`DepRPOn` ,
				`DEPMotorRunTime` ,
				`RotationMotorO-ring` ,
				`GlowHydroRPOilLifeMeter` ,
				`DEPRoughPumpOilLife` ,
				`FSChromeTargetLifeSP` ,
				`FSLowIndexTargetLifeSP` ,
				`FSHighIndexTargetLifeSP` ,
				`BSChromeTargetLifeSP` ,
				`BSLowIndexTargetLifeSP` ,
				`BSHighIndexTargetLifeSP` ,
				`FSChromeTargetLifeValue` ,
				`FSLowIndexTargetLifeValue` ,
				`FSHighIndexTargetLifeValue` ,
				`BSChromeTargetLifeValue` ,
				`BSLowIndexTargetLifeValue` ,
				`BSHighIndexTargetLifeValue` ,
				`FSChromeTargetLife` ,
				`FSLowIndexTargetLife` ,
				`FSHighIndexTargetLife` ,
				`BSChromeTargetLife` ,
				`BSLowIndexTargetLife` ,
				`BSHighIndexTargetLife` ,
				`LensCount` ,
				`LensCountSetpoint` ,
				`MachineOntime` ,
				`MachineinAutoModetime` ,
				`TotalProcessesRun` 
				)
				VALUES (
				NULL , 
				'%s', 
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
				) ON DUPLICATE KEY UPDATE id=id;", 
				mysql_real_escape_string($this->customer_id), 
				mysql_real_escape_string($csvFolder), 
				mysql_real_escape_string($value['Turbo On']),
				mysql_real_escape_string($value[' Water Chiller Run Time']),
				mysql_real_escape_string($value[' Glow/Hydro RP On']),
				mysql_real_escape_string($value[' Dep RP On']),
				mysql_real_escape_string($value[' DEP Motor Run Time']),
				mysql_real_escape_string($value[' Rotation Motor O-ring']),
				mysql_real_escape_string($value[' Glow Hydro RP Oil Life Meter']),
				mysql_real_escape_string($value[' DEP Rough Pump Oil Life']),
				mysql_real_escape_string($value[' FS Chrome Target Life SP']),
				mysql_real_escape_string($value[' FS Low Index Target Life SP']),
				mysql_real_escape_string($value[' FS High Index Target Life SP']),
				mysql_real_escape_string($value[' BS Chrome Target Life SP']),
				mysql_real_escape_string($value[' BS Low Index Target Life SP']),
				mysql_real_escape_string($value[' BS High Index Target Life SP']),
				mysql_real_escape_string($value[' FS Chrome Target Life Value']),
				mysql_real_escape_string($value[' FS Low Index Target Life Value']),
				mysql_real_escape_string($value[' FS High Index Target Life Value']),
				mysql_real_escape_string($value[' BS Chrome Target Life Value']),
				mysql_real_escape_string($value[' BS Low Index Target Life Value']),
				mysql_real_escape_string($value[' BS High Index Target Life Value']),
				mysql_real_escape_string($value[' FS Chrome Target Life']),
				mysql_real_escape_string($value[' FS Low Index Target Life']),
				mysql_real_escape_string($value[' FS High Index Target Life']),
				mysql_real_escape_string($value[' BS Chrome Target Life']),
				mysql_real_escape_string($value[' BS Low Index Target Life']),
				mysql_real_escape_string($value[' BS High Index Target Life']),
				mysql_real_escape_string($value[' Lens Count']),
				mysql_real_escape_string($value[' Lens Count Setpoint']),
				mysql_real_escape_string($value[' Machine On time']),
				mysql_real_escape_string($value[' Machine in AutoMode time']),
				mysql_real_escape_string($value[' Total Processes Run'])
				);
				//echo $query . "\n\n";
				if(mysql_query($query)) echo "inserted Hours!";
		}	
	}	



	// method declaration
	public function hasFileBeenLogged($filename) {
		$returnValue = false;
		
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`process_log_fileLog` WHERE `process_log_fileLog`.`filename` = '%s' AND `process_log_fileLog`.`customer_id` = '%s' AND `process_log_fileLog`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer_id), mysql_real_escape_string($this->machine_type));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}

	// method declaration
	public function hasVoltageFileBeenLogged($filename) {
		$returnValue = false;
		
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`voltage_log_fileLog` WHERE `voltage_log_fileLog`.`filename` = '%s' AND `voltage_log_fileLog`.`customer_id` = '%s' AND `voltage_log_fileLog`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer_id), mysql_real_escape_string($this->machine_type));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}


	// method declaration
	public function hasAsetFileBeenLogged($filename) {
		$returnValue = false;
		
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`aset1_log_fileLog` WHERE `aset1_log_fileLog`.`filename` = '%s' AND `aset1_log_fileLog`.`customer_id` = '%s' AND `aset1_log_fileLog`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer_id), mysql_real_escape_string($this->machine_type));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}

	// method declaration
	public function hasSettingsFileBeenLogged($filename) {
		$returnValue = false;
		
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`settings_log_fileLog` WHERE `settings_log_fileLog`.`filename` = '%s' AND `settings_log_fileLog`.`customer_id` = '%s' AND `settings_log_fileLog`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer_id), mysql_real_escape_string($this->machine_type));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}	

	// method declaration
	public function hasMaterialFileBeenLogged($filename) {
		$returnValue = false;
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`material_log_fileLog` WHERE `material_log_fileLog`.`filename` = '%s' AND `material_log_fileLog`.`customer_id` = '%s' AND `material_log_fileLog`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer_id), mysql_real_escape_string($this->machine_type));
		
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}	


	// method declaration
	public function hasAset2FileBeenLogged($filename) {
		$returnValue = false;
		
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`aset2_log_fileLog` WHERE `aset2_log_fileLog`.`filename` = '%s' AND `aset2_log_fileLog`.`customer_id` = '%s' AND `aset2_log_fileLog`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer_id), mysql_real_escape_string($this->machine_type));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}	


	// methsod declaration
	public function hasHoursFileBeenLogged($filename) {
		$returnValue = false;
		
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`hours_log_fileLog` WHERE `hours_log_fileLog`.`filename` = '%s' AND `hours_log_fileLog`.`customer_id` = '%s' AND `hours_log_fileLog`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer_id), mysql_real_escape_string($this->machine_type));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}	
	
	
	// method declaration
	public function insertLoggedFile($filename) {
		$query = sprintf("INSERT INTO `qtmi_mgr_server`.`process_log_fileLog` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer_id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged CSV File \n";
	
	}

	// method declaration
	public function insertVoltageLoggedFile($filename) {
		$query = sprintf("INSERT INTO `qtmi_mgr_server`.`voltage_log_fileLog` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer_id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged Voltage CSV File \n";
	
	}


	// method declaration
	public function insertAsetLoggedFile($filename) {
		$query = sprintf("INSERT INTO `qtmi_mgr_server`.`aset1_log_fileLog` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer_id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged Aset 1 CSV File \n";
	
	}

	// method declaration
	public function insertSettingsLoggedFile($filename) {
		$query = sprintf("INSERT INTO `qtmi_mgr_server`.`settings_log_fileLog` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer_id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged Settings CSV File \n";
	
	}
	
	// method declaration
	public function insertMaterialLoggedFile($filename) {
		$query = sprintf("INSERT INTO `qtmi_mgr_server`.`material_log_fileLog` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer_id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged Materials CSV File \n";
	
	}	

	// method declaration
	public function insertAset2LoggedFile($filename) {
		$query = sprintf("INSERT INTO `qtmi_mgr_server`.`aset2_log_fileLog` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer_id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged Aset 2 CSV File \n";
	
	}

	// method declaration
	public function insertHoursLoggedFile($filename) {
		$query = sprintf("INSERT INTO `qtmi_mgr_server`.`hours_log_fileLog` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer_id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged Aset 2 CSV File \n";
	
	}



	// method declaration
	public function updateLoggedFile($filename) {
		$query = sprintf("UPDATE  `qtmi_mgr_server`.`process_log_fileLog` 
		SET  `insertNumber` =  '2' WHERE  `process_log_fileLog`.`filename` ='%s';", 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Update CSV File \n";
	}

	// method declaration
	public function loggedFileIs1($filename) {
		$returnValue = false;
		$query = sprintf("SELECT * FROM `qtmi_mgr_server`.`process_log_fileLog` WHERE `process_log_fileLog`.`filename` = '%s' AND `process_log_fileLog`.`insertNumber` = 1 ",  mysql_real_escape_string($filename));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}

	// method declaration
	public function correctNamingError() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/process/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".." ){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/process/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				
				foreach($csvFiles as $csvFile) 
				{ 
					$this->correctNameInFile($this->csv_process_dir.$csvFile);
				} 
			}
		} 		
	}

	// method declaration
	public function correctAset1NamingError() {
		$this->csv_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/';
		$csvFolders = scandir($this->csv_dir, 1);		
		foreach($csvFolders as $csvFolder) 
		{ 
			if($csvFolder != "." && $csvFolder != ".."  && $csvFolder != "process" && $csvFolder != "voltage"  ){
				$this->csv_process_dir = 'data_logs/'.$this->customer_code.'/'.$this->machine_type.'/'.$csvFolder.'/';
				$csvFiles = scandir($this->csv_process_dir, 1);
				//print_r($csvFiles);
				echo "Correct Aset process directory = ".$this->csv_process_dir;
				
				foreach($csvFiles as $csvFile) 
				{ 
					if($csvFile == "aset.csv")
						$this->correctAset1NameInFile($this->csv_process_dir.$csvFile);
				} 
			}
		} 		
	}



function correctNameInFile($filename='')
{
	if(!file_exists($filename)) echo "filename - ".$filename;

	if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

	$oldString = "B Original Pwr,F Final Pwr,B Arc Current Count";
	$newString = "B Original Pwr,B Final Pwr,B Arc Current Count";

	// Read the entire string
//	$str = implode(",", file($filename));
	$str = file_get_contents($filename);

	echo "Filename = ".$filename."\n";
	
	if($filename != "." && $filename != ".."){
		$fp = fopen($filename, 'w');

		// Replace something in the file string - this is a VERY simple example
		$str = str_replace("$oldString", "$newString", $str);
		
		file_put_contents($filename, $str);

//		fwrite($fp, $str, strlen($str));
//		fclose($fp);	
	}
}	


function correctAset1NameInFile($filename='')
{
	if(!file_exists($filename)) echo "filename - ".$filename;

	if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

	$oldString = "Time to error when getting DEP to start pressure, DEP";
	$newString = "Time to error when getting DEP to start pressure2, DEP";

	// Read the entire string
//	$str = implode(",", file($filename));
	$str = file_get_contents($filename);

	echo "Filename = ".$filename."\n";
	
	if($filename != "." && $filename != ".."){
		$fp = fopen($filename, 'w');

		// Replace something in the file string - this is a VERY simple example
		$str = str_replace("$oldString", "$newString", $str);
		
		file_put_contents($filename, $str);

//		fwrite($fp, $str, strlen($str));
//		fclose($fp);	
	}
}	

}



?>