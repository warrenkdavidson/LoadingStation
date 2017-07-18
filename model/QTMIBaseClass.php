<?php 

class QtmiBaseClass {
    public $link = "";
	
	function __construct(&$link){
//		echo "In QTMI Base";
		$this->link = &$link;
	}

/*
	   function __destruct() {
	       print "Destroying object \n";
	       mysql_close($this->link);
	   }	
*/
}

?>