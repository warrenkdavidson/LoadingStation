<?php 

class PlaneCalc {
    public $rowCounter = 0;
    public $sag1 = 0;
    public $sag2 = 0;
    public $sag3 = 0;
    public $sag4 = 0;
    public $sag5 = 0;
    public $sag6 = 0;

    public $real_apogee1 = 0;
    public $real_apogee2 = 0;
    public $real_apogee3 = 0;
    public $real_apogee4 = 0;
    public $real_apogee5 = 0;
    public $real_apogee6 = 0;

    public $real_apogees = array();
    public $sag_diff_array = array();
    public $sag_fixtures = array();


    public $sag1_diff = 0;
    public $sag2_diff = 0;
    public $sag3_diff = 0;
    public $sag4_diff = 0;
    public $sag5_diff = 0;
    public $sag6_diff = 0;

    public $sag_diff_avg = 0;
    public $sag_diff_std = 0;
    public $sag_plane = 0;


    public $plano_sag = 7.24;

    public $fixture1 = 4.53;
    public $fixture2 = 2.15;
    public $fixture3 = 0;
    public $fixture4 = -2.37;
    public $fixture5 = -4.73;
    public $fixture6 = -7.1;

    public $fixture1_mid = 3.34;
    public $fixture2_mid = 1.075;
    public $fixture3_mid = 1.185;
    public $fixture4_mid = 3.55;
    public $fixture5_mid = 5.915;



   function __construct() {
   }

   function __destruct() {
   }
   


    function wheelPlane($sag1, $sag2, $sag3, $sag4, $sag5, $sag6){
        $this->sag_fixtures = array();

        $this->sag_diff_array = array();
        $this->sag_diff_array[] = $this->plano_sag - $sag1;
        $this->sag_diff_array[] = $this->plano_sag - $sag2;
        $this->sag_diff_array[] = $this->plano_sag - $sag3;
        $this->sag_diff_array[] = $this->plano_sag - $sag4;
        $this->sag_diff_array[] = $this->plano_sag - $sag5;
        $this->sag_diff_array[] = $this->plano_sag - $sag6;



        $this->realApogees($this->sag_diff_array);
        $this->wheelSagPlane();

        echo "Wheel: \n";
        echo "Sag1: ".$this->sag1."\n";
        echo "Sag2: ".$this->sag2."\n";
        echo "Sag3: ".$this->sag3."\n";
        echo "Sag4: ".$this->sag4."\n";
        echo "Sag5: ".$this->sag5."\n";
        echo "Sag6: ".$this->sag6."\n";

        echo "SAG Difs: \n";
        foreach ($this->sag_diff_array as $sag_diff)
        {
            echo "Sag dif: ".$sag_diff."\n";
        }

        echo "SAG Fixtures: \n";
        print_r($this->sag_diff_array);
        foreach ($this->sag_diff_array as $sag_diff)
        {
            $this->sag_fixtures[] = $this->pickFixture($sag_diff);
            echo "Sag Fixture: ".$this->pickFixture($sag_diff)."\n";
        }



        echo "Real Apogees: \n";
        $countA = 0;
        foreach ($this->real_apogees as $apogee)
        {
            echo "Apogee ".$countA.": ".$apogee."\n";
            $countA++;
        }
        echo "Wheel Plane: \n";
        echo "Plane: ".$this->sag_plane."\n";
        echo "Std Dev: ".$this->sag_diff_std."\n";

        return $this->sag_fixtures;

    }   
 

    function realApogees($sag_diff_array){    

        $this->real_apogee1;
        $this->real_apogee2;
        $this->real_apogee3;
        $this->real_apogee4;
        $this->real_apogee5;
        $this->real_apogee6;

        $this->real_apogees;

        $count = 0;
        $this->real_apogees = array();
        foreach ($this->sag_diff_array as $sag_diff)
        {
            $this->real_apogees[] = $this->getApogee($this->pickFixture($sag_diff), $count);
            $count++;
        }
    }

    function getApogee($fixture, $position){    

        global $sag1;
        global $sag2;
        global $sag3;
        global $sag4;
        global $sag5;
        global $sag6;

        global $fixture1;
        global $fixture2;
        global $fixture3;
        global $fixture4;
        global $fixture5;
        global $fixture6;    


        $sag = "";
        $returnApogee = 0;

        if($position == 0)
            $sag = $this->sag1;
        if($position == 1)
            $sag = $this->sag2;
        if($position == 2)
            $sag = $this->sag3;
        if($position == 3)
            $sag = $this->sag4;
        if($position == 4)
            $sag = $this->sag5;
        if($position == 5)
            $sag = $this->sag6;

        if($fixture == 1)
            $returnApogee = $sag + $this->fixture1;
        if($fixture == 2)
            $returnApogee = $sag + $this->fixture2;
        if($fixture == 3)
            $returnApogee = $sag + $this->fixture3;
        if($fixture == 4)
            $returnApogee = $sag + $this->fixture4;
        if($fixture == 5)
            $returnApogee = $sag + $this->fixture5;
        if($fixture == 6)
            $returnApogee = $sag + $this->fixture6;

        return $returnApogee;
    }

    function wheelSagPlane(){    
        $this->real_apogees;
        $this->sag_diff_avg;
        $this->sag_diff_std;
        $this->sag_plane;

        $this->sag_diff_avg = 
            array_sum($this->real_apogees) / count($this->real_apogees);
        $this->sag_diff_std = $this->standard_deviation($this->real_apogees);
        $this->sag_plane = $this->sag_diff_avg;
    }

    function pickFixture($sagDiff){
        $returnValue = -1;
        if($sagDiff <= 0){
            if(abs($sagDiff) <= abs($this->fixture4)){
                if(abs($sagDiff) <= abs($this->fixture3_mid))
                    $returnValue = 3;
                else
                    $returnValue = 4;                
            }else if (abs($sagDiff) < abs($this->fixture5)){
                if(abs($sagDiff) <= abs($this->fixture4_mid))
                    $returnValue = 4;
                else
                    $returnValue = 5;                
            }else if (abs($sagDiff) < abs($this->fixture6)){
                if(abs($sagDiff) <= abs($this->fixture5_mid))
                    $returnValue = 5;
                else
                    $returnValue = 6;                
            }else if (abs($sagDiff) >= abs($this->fixture6)){
                $returnValue = 6;
            }
        }else{
            if(abs($sagDiff) <= abs($this->fixture2)){
                if(abs($sagDiff) <= abs($this->fixture2_mid))
                    $returnValue = 3;
                else
                    $returnValue = 2;          
            }else if(abs($sagDiff) <= abs($this->fixture1)){
                if(abs($sagDiff) <= abs($this->fixture1_mid))
                    $returnValue = 2;
                else
                    $returnValue = 1;          
            }else if(abs($sagDiff) > abs($this->fixture1)){
                    $returnValue = 1;                      
            }
        }
        return $returnValue;
    }

    function standard_deviation($aValues, $bSample = false)
    {
        $fMean = array_sum($aValues) / count($aValues);
        $fVariance = 0.0;
        foreach ($aValues as $i)
        {
            $fVariance += pow($i - $fMean, 2);
        }
        $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
        return (float) sqrt($fVariance);
    }


}
?>