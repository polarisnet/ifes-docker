<?php
	class Donor{
		var $db;
		var $totalRow;
		
		function Donor($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function generateUID(){
			$uid = strtolower(generateSalt('30'));
			while($this->checkUIDExist($uid)){
				$uid = strtolower(generateSalt('30'));
			}
			return $uid;
		}
		
		function checkUIDExist($uid){
			$sql = "SELECT * FROM `donors` WHERE `uid`='".$uid."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getDonorNameById($id){
			$output = "";
			$sql = "SELECT `username` FROM `donors` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['username'];
			}
			return $output;
		}
		
		function deleteDonor($id){
			if($this->db->delete("donors", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveDonor($data){
			if($this->db->insert("donors", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateDonor($data){
			if($this->db->update("donors", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
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