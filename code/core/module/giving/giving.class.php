<?php
	class Giving{
		var $db;
		var $totalRow;

		function __construct($db){
			$this->db = $db;
		}

		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}

		function listCountries(){
			$output = array();
			$sql = "SELECT * FROM `sys_country` ORDER BY name ASC";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				array_push($output, $this->db->getRecord());
			}
			return $output;
		}
	}
?>