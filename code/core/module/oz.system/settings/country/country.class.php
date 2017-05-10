<?php
	class Country{
		var $db;
		var $totalRow;
		
		function Country($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getCountryCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_country', $condition);
			$sql = "SELECT `id`, `iso`, `name` FROM `sys_country` WHERE 1=1 ".$condition." ORDER BY `name` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['name'] = ($result_data['name']);
				$temp['iso'] = strtoupper($result_data['iso']);
				array_push($output, $temp);
			}
			return $output;
		}
		
		function listCountryField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_country` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			return $output;
		}
		
		function getCountryNameById($id){
			$output = "";
			$sql = "SELECT `name` FROM `sys_country` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['name'];
			}
			return $output;
		}
		
		function listCountry($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_country', $condition);
			$sql = "SELECT * FROM `sys_country` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); $result['raw'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);
				}
			}
			return $output;
		}
		
		function checkCountryExist($id){
			$sql = "SELECT * FROM `sys_country` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkCountryNameExist($name, $id = ""){
			$sql = "SELECT * FROM `sys_country` WHERE LOWER(`name`)='".strtolower($name)."'";
			if($id != ""){
				$sql .= " AND `id` != '".$id."'";
			}
			$sql .= " LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getCountryData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_country` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		
		function deleteCountry($id){
			if($this->db->delete("sys_country", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveCountry($data){
			if($this->db->insert("sys_country", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateCountry($data){
			if($this->db->update("sys_country", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
	}
?>