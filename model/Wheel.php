<?php 
include_once "FixtureSort.php";

class Wheel extends QtmiBaseClass {
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

    // Wheel Vars
    public $wheel_id = "";
    public $job1 = "";
    public $job2 = "";
    public $job3 = "";
    public $wheel_ar = "";

    public $num_loadedWheels = "";

    // Fixture Sort Vars
    public $fixtureDivs = "";
    public $fixtureJquery = "";

    // Batch Id Vars
    public $batch_id1 = "";
    public $batch_id2 = "";
    public $batch_id3 = "";
    public $batch_id4 = "";
    public $batch_id5 = "";
    public $batch_id6 = "";

    public $fixtureSort;



   function __construct($link) {
    parent::__construct($link);
    $this->fixtureSort = new FixtureSort($link);
   }

   function __destruct() {
   }


   

 
	// method declaration
	public function insertLoadedWheel($fixture_positions) {
			$query = sprintf("INSERT INTO `loading_station`.`wheel` (
			`id` ,
			`created_on_date` ,
			`created_on_time`, 
			`wheel`, 
			`job1`, 
			`job2`, 
			`job3`, 
            `wheel_ar`,             
            `status`, 
            `fixture1`, 
            `fixture2`, 
            `fixture3`, 
            `fixture4`, 
            `fixture5`, 
            `fixture6`, 
            `batch_id1`, 
            `batch_id2`, 
            `batch_id3`, 
            `batch_id4`, 
            `batch_id5`, 
            `batch_id6` 
				)
				VALUES (
				NULL , 
                CURRENT_DATE(), 
                CURRENT_TIME(), 
				'%s', 
				'%s', 
				'%s', 
                '%s', 
                '%s', 
                '1',
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
				) ;", 
				mysqli_real_escape_string($this->link, $this->wheel_id), 
				mysqli_real_escape_string($this->link, $this->job1), 
                mysqli_real_escape_string($this->link, $this->job2), 
				mysqli_real_escape_string($this->link, $this->job3),
                mysqli_real_escape_string($this->link, $this->wheel_ar),
                mysqli_real_escape_string($this->link, $fixture_positions[0]),
                mysqli_real_escape_string($this->link, $fixture_positions[1]),
                mysqli_real_escape_string($this->link, $fixture_positions[2]),
                mysqli_real_escape_string($this->link, $fixture_positions[3]),
                mysqli_real_escape_string($this->link, $fixture_positions[4]),
                mysqli_real_escape_string($this->link, $fixture_positions[5]),
                mysqli_real_escape_string($this->link, $this->batch_id1),
                mysqli_real_escape_string($this->link, $this->batch_id2),
                mysqli_real_escape_string($this->link, $this->batch_id3),
                mysqli_real_escape_string($this->link, $this->batch_id4),
                mysqli_real_escape_string($this->link, $this->batch_id5),
                mysqli_real_escape_string($this->link, $this->batch_id6)
				);
				echo $query . "\n\n";

                if ($this->link->query($query) === TRUE) {
                    echo "New record created successfully";
                } else {
                    //echo "Error: " . $sql . "<br>" . $conn->error;
                }                
	}


  
    public function writeLoadedWheelsCsv() {
        $list = array (
            array('wheelId', 'job1', 'job2', 'job3'),
            array($this->wheel_id, $this->job1, $this->job2, $this->job3)
        );

        $fp = fopen('../bash/lswhl.csv', 'w');

        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp); 

        // Send File with Id to HMI
        //sleep(1);
        //exec('C:\wamp64\www\LoadingStation\ftp\CMD\wheel\wheelIds.cmd');   
        // Send command to get LMS data to HMI
        //exec('C:\wamp64\www\LoadingStation\ftp\CMD\wheel\getLMS.cmd');   
    }

    public function wheelInProcess($wheelId) {
            $query = sprintf("
                UPDATE `loading_station`.`wheel` 
                SET `status` = '2' 
                WHERE `loading_station`.`wheel`.`wheel` = %s 
                AND `loading_station`.`wheel`.`status` = '1';",
                mysqli_real_escape_string($this->link, $wheelId));

                echo $query . "\n\n";

                if ($this->link->query($query) === TRUE) {
                    echo "In Process wheel added successfully";
                } else {
                    echo "Error: " . $query . "<br>" . $this->link->error;
                }  
    }    

/*
    public function readLoadedWheelsCsv() {

        $row = 1;
        if (($handle = fopen("../ftp/wheel/lswhl2.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";
                if($row == 3) {
                  $this->job1_ar = $data[1];  
                  $this->job2_ar = $data[2];  
                  $this->job3_ar = $data[3];  
                } 
                $row++;                
            }
            fclose($handle);
        }  
    }

    public function writeWheelsToMachine() {
        $rowCounter = 0;
        $query = "SELECT * FROM `loading_station`.`loaded_wheel` WHERE `loaded_wheel`.`status` = 1 ";

        $list = array (
            array('recordId', 'wheelId', 'job1', 'job2', 'job3')
        );

//        echo $query;
        $result = mysql_query($query);                
            while ($row = mysql_fetch_assoc($result)) {
                $list[] = array($row['id'], $row['wheel_id'], $row['job1_ar'], $row['job2_ar'], $row['job3_ar']); 
                $rowCounter++;
            }


        $fp = fopen('../ftp/wheel/lsw2m.csv', 'w');

        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp); 

        // Send File with Id to HMI
//        exec('C:\wamp64\www\LoadingStation\ftp\CMD\wheel\wheelIds.cmd');   
        // Send command to get LMS data to HMI
//        exec('C:\wamp64\www\LoadingStation\ftp\CMD\wheel\getLMS.cmd');   
    }    
*/


    public function NumLoadedWheels() {
        $this->num_loadedWheels = 0;
        $sql = "SELECT * FROM `loading_station`.`wheel` WHERE `wheel`.`status` = 1 ";
        $result = $this->link->query($sql);
        if ($result->num_rows > 0) 
            $this->num_loadedWheels = $result->num_rows; 
        echo $this->num_loadedWheels;

    }

    public function NumInProcessWheels() {
        $this->num_loadedWheels = 0;
        $sql = "SELECT * FROM `loading_station`.`wheel` WHERE `wheel`.`status` = 2 ";
        $result = $this->link->query($sql);
        if ($result->num_rows > 0) 
            $this->num_loadedWheels = $result->num_rows; 
        echo $this->num_loadedWheels;

    }    

    public function GetLeftWheelBias($rowCounter) {
        $returnValue = "";
        $clockSeconds = time() + $rowCounter;
        if($clockSeconds%2 == 0){
            $returnValue = "F";
        }else if($clockSeconds%5 == 0){
            $returnValue = "B";
        }else{
            $returnValue = "M";
        }
        return $returnValue;
    }       

    public function GetRightWheelBias($rowCounter) {
        $returnValue = "";
        $clockSeconds = time() + $rowCounter;
        if($clockSeconds%7 == 0){
            $returnValue = "F";
        }else if($clockSeconds%4 == 0){
            $returnValue = "M";
        }else{
            $returnValue = "B";
        }
        return $returnValue;
    }       

    public function showLoadedWheels() {

        $loadedWheelsTable = "";

        // Open Container
        $loadedWheelsTable .= "<div class='loadedWheelsContainer' >";

        // Set Labels
        $loadedWheelsTable .= "<!-- Labels for Loaded Wheels -->
                            <div class='fieldLabelsContainer' >
                                <div class='wheelFieldLabel' >
                                Wheel 
                                </div>
                                <div class='dateFieldLabel' >
                                Date 
                                </div>
                                <div class='timeFieldLabel' >
                                Time 
                                </div>
                                <div class='recipeFieldLabel' >
                                Recipe Name
                                </div>
                                <div class='jobFieldLabelContainer'>
                                    <div class='jobFieldLabel' >
                                    Job 1
                                    </div>                  
                                    <div class='jobFieldLabel' >
                                    Job 2
                                    </div>
                                    <div class='jobFieldLabel' >
                                    Job 3
                                    </div>

                                    <div class='biasFieldLabel' >
                                    Bias
                                    </div>                  
                                    <div class='biasFieldLabel' >
                                    Bias
                                    </div>
                                    <div class='biasFieldLabel' >
                                    Bias
                                    </div>                                    
                                </div>   
                            </div>   
                            ";
                                      

        // Show Loaded Wheels
        $loadedWheelsTable .= "<!-- Labels2 for Loaded Wheels -->";


        $rowCounter = 0;
        $sql = "SELECT * FROM `loading_station`.`wheel` WHERE `wheel`.`status` = 1 ORDER BY `wheel`.`id` DESC ";

        $result = $this->link->query($sql);
        if ($result->num_rows > 0) 
        {
            // output data of each row
            while($row = $result->fetch_assoc()) 
            {
                $jobs = $row['job1'].", ".$row['job2'].$row['job3'];
                $job1 = $row['job1']." "."1-".$row['fixture1'].", 2-".$row['fixture2'];
                $job2 = $row['job2']." "."3-".$row['fixture3'].", 4-".$row['fixture4'];
                $job3 = $row['job3']." "."5-".$row['fixture5'].", 6-".$row['fixture6'];
                $rowColor = "";
                if($rowCounter % 2 == 0) 
                    $rowColor = "lineIndicatorLight";
                else
                    $rowColor = "lineIndicatorDark";

                   $loadedWheelsTable ."<!-- Values for A2 Loaded Wheel: Job1 -->";
                   $loadedWheelsTable .= "<div class='wheelFieldValue ".$rowColor."'>".$row['wheel']."</div>";                  
                   $loadedWheelsTable .= "<div class='dateFieldValue ".$rowColor."'>".$row['created_on_date']."</div>";
                   $loadedWheelsTable .= "<div class='timeFieldValue ".$rowColor."'>".$row['created_on_time']."</div>";                   
                   $loadedWheelsTable .= "<div class='recipeFieldValue ".$rowColor."'>".$row['wheel_ar']."</div>";         
                   $loadedWheelsTable .= "<div class='jobFieldValue  ".$rowColor."'>".$job1."</div>";    
                   $loadedWheelsTable .= "<div class='jobFieldValue  ".$rowColor."'>".$job2."</div>";          
                   $loadedWheelsTable .= "<div class='jobFieldValue  ".$rowColor."'>".$job3."</div>";                                             
                if($rowCounter == 0){
                    // Fixture Sort Vars
                    $this->fixtureDivs = "";

                    $this->fixtureDivs .= "<div id='position1'><span class='positionFixtureName'>F.".$row['fixture1']."</span><span class='positionJob'>".$row['job1']."</span><span class='positionBatchName'>".trim($this->fixtureSort->getSagName($row['batch_id1']))."</span></div>";
                    $this->fixtureDivs .= "<div id='position2'><span class='positionFixtureName'>F.".$row['fixture2']."</span><span class='positionJob'>".$row['job1']."</span><span class='positionBatchName'>".trim($this->fixtureSort->getSagName($row['batch_id2']))."</span></div>";

                    $this->fixtureDivs .= "<div id='position3'><span class='positionFixtureName'>F.".$row['fixture3']."</span><span class='positionJob'>".$row['job2']."</span><span class='positionBatchName'>".trim($this->fixtureSort->getSagName($row['batch_id3']))."</span></div>";

                    $this->fixtureDivs .= "<div id='position4'><span class='positionFixtureName'>F.".$row['fixture4']."</span><span class='positionJob'>".$row['job2']."</span><span class='positionBatchName'>".trim($this->fixtureSort->getSagName($row['batch_id4']))."</span></div>";


                    $this->fixtureDivs .= "<div id='position5'><span class='positionFixtureName'>F.".$row['fixture5']."</span><span class='positionJob'>".$row['job3']."</span><span class='positionBatchName'>".trim($this->fixtureSort->getSagName($row['batch_id5']))."</span></div>";

                    $this->fixtureDivs .= "<div id='position6'><span class='positionFixtureName'>F.".$row['fixture6']."</span><span class='positionJob'>".$row['job3']."</span><span class='positionBatchName'>".trim($this->fixtureSort->getSagName($row['batch_id6']))."</span></div>";

                    $this->fixtureJquery = "";

                    $this->fixtureJquery .= " var position1Fix = '../img/fix".$row['fixture1'].".jpg'; ";
                    $this->fixtureJquery .= " var position2Fix = '../img/fix".$row['fixture2'].".jpg'; ";
                    $this->fixtureJquery .= " var position3Fix = '../img/fix".$row['fixture3'].".jpg'; ";
                    $this->fixtureJquery .= " var position4Fix = '../img/fix".$row['fixture4'].".jpg'; ";
                    $this->fixtureJquery .= " var position5Fix = '../img/fix".$row['fixture5'].".jpg'; ";
                    $this->fixtureJquery .= " var position6Fix = '../img/fix".$row['fixture6'].".jpg'; ";

                }

                $rowCounter++;

            }
        } 
        else
        {
            echo "0 results";
        }

                   
        echo $loadedWheelsTable;

    }    
  



    public function showInProcessWheels() {

        $loadedWheelsTable = "";

        // Open Container
        $loadedWheelsTable .= "<div class='loadedWheelsContainer' >";

        // Set Labels
        $loadedWheelsTable .= "<!-- Labels for Loaded Wheels -->
                            <div class='fieldLabelsContainer' >
                                <div class='wheelFieldLabel' >
                                Wheel 
                                </div>
                                <div class='dateFieldLabel' >
                                Date 
                                </div>
                                <div class='timeFieldLabel' >
                                Time 
                                </div>
                                <div class='recipeFieldLabel' >
                                Recipe Name
                                </div>
                                <div class='jobFieldLabelContainer'>
                                    <div class='jobFieldLabel' >
                                    Job 1
                                    </div>                  
                                    <div class='jobFieldLabel' >
                                    Job 2
                                    </div>
                                    <div class='jobFieldLabel' >
                                    Job 3
                                    </div>

                                    <div class='biasFieldLabel' >
                                    Bias
                                    </div>                  
                                    <div class='biasFieldLabel' >
                                    Bias
                                    </div>
                                    <div class='biasFieldLabel' >
                                    Bias
                                    </div>                                    
                                </div>   
                            </div>   
                            ";
                                      

        // Show Loaded Wheels
        $loadedWheelsTable .= "<!-- Labels2 for Loaded Wheels -->";


        $rowCounter = 0;
        $sql = "SELECT * FROM `loading_station`.`wheel` WHERE `wheel`.`status` = 2 ";
        $result = $this->link->query($sql);
        if ($result->num_rows > 0) 
        {
            // output data of each row
            while($row = $result->fetch_assoc()) 
            {
                $jobs = $row['job1'].", ".$row['job2'].$row['job3'];
                $job1 = $row['job1']." "."L-".$this->GetLeftWheelBias($rowCounter).", R-".$this->GetRightWheelBias($rowCounter);
                $job2 = $row['job2']." "."L-".$this->GetLeftWheelBias($rowCounter).", R-".$this->GetRightWheelBias($rowCounter);
                $job3 = $row['job3']." "."L-".$this->GetLeftWheelBias($rowCounter).", R-".$this->GetRightWheelBias($rowCounter);
                $rowColor = "";
                if($rowCounter % 2 == 0) 
                    $rowColor = "lineIndicatorLight";
                else
                    $rowColor = "lineIndicatorDark";

                   $loadedWheelsTable ."<!-- Values for A2 Loaded Wheel: Job1 -->";
                   $loadedWheelsTable .= "<div class='wheelFieldValue ".$rowColor."'>".$row['wheel']."</div>";                  
                   $loadedWheelsTable .= "<div class='dateFieldValue ".$rowColor."'>2017-01-08</div>";
                   $loadedWheelsTable .= "<div class='timeFieldValue ".$rowColor."'>8:50</div>";                   
                   $loadedWheelsTable .= "<div class='recipeFieldValue ".$rowColor."'>".$row['wheel_ar']."</div>";         
                   $loadedWheelsTable .= "<div class='jobFieldValue  ".$rowColor."'>".$job1."</div>";    
                   $loadedWheelsTable .= "<div class='jobFieldValue  ".$rowColor."'>".$job2."</div>";          
                   $loadedWheelsTable .= "<div class='jobFieldValue  ".$rowColor."'>".$job3."</div>";                                             
                    $rowCounter++;

            }
        } 
        else
        {
            echo "0 results";
        }

                   
        echo $loadedWheelsTable;

}

    public function fixtureSortDivs() {
        return $this->fixtureDivs;
    }


    public function fixtureSortJquery() {
        return $this->fixtureJquery;
    }

}
?>