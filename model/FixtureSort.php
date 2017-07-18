<?php 

class FixtureSort extends QtmiBaseClass {
  public $fixtureName = array();
  public $sagCalculated = array();
  public $sagMeasured = array();


   function __construct($link) {
       parent::__construct($link);
       $this->loadFixtures();
   }

   function __destruct() {

   }

    public function loadFixtures() {

        $selectOptions = "";

        $rowCounter = 0;
        $sql = "SELECT * FROM `loading_station`.`batch_category` ORDER BY `batch_category`.`name` DESC ";

        $result = $this->link->query($sql);
        if ($result->num_rows > 0) 
        {
            // output data of each row
            while($row = $result->fetch_assoc()) 
            {
                $this->fixtureName[$row['id']] = $row['name'];
                $this->sagCalculated[$row['id']] = $row['calculated_sag'];
                $this->sagMeasured[$row['id']] = $row['measured_sag'];
            }
        }
    }      

    public function showCalculatedSagOptions() {
      $selectOptions = "";

      foreach($this->fixtureName as $id => $name){
        $selectOptions .= "<option value='".$id."'>".$name."</option>";
      }

      echo $selectOptions;
    }    

    public function getSagDepth($sagId) {
      $returnVal = "";
      foreach($this->sagCalculated as $id => $sag){
          if($id == $sagId){
            $returnVal = $this->sagCalculated[$id];
          }
      }

      return $returnVal;
    }    

    public function getSagName($sagId) {
      $returnVal = "";
      foreach($this->fixtureName as $id => $sag){
          if($id == $sagId){
            $returnVal = $this->fixtureName[$id];
          }
      }

      return $returnVal;
    }        

}
?>