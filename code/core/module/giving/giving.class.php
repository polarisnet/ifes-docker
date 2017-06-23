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

		function listCreditCards($userId){
			$output = array();
			$sql = "SELECT * FROM `payments` WHERE user_id = '$userId' AND type = 'card' AND display_info = '1' ORDER BY id ASC";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				array_push($output, $this->db->getRecord());
			}
			return $output;
		}

		function getPaymentData($id){
			$output = array();
			$sql = "SELECT * FROM `payments` WHERE `id`='$id'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}

		function listCustomBanner(){
			$output = array();
			$sql = "SELECT * FROM `banner_custom` ORDER BY item_order ASC";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				array_push($output, $this->db->getRecord());
			}
			return $output;
		}

		function getDonorAccountData($username){
			$output = array();
			$sql = "SELECT * FROM `sys_users` WHERE `username`='$username'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
	}
?>