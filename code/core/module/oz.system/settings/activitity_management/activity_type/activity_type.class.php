<?php
	class ActivityType{
		var $db;
		var $totalRow;
		
		function ActivityType($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getActivityTypeCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'activity_type', $condition);
			$sql = "SELECT `id`, `type` FROM `activity_type` WHERE 1=1 ".$condition." ORDER BY `type` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['type'] = $result_data['type'];
				array_push($output, $temp);
			}
			return $output;
		}
		
		function listActivityTypeField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `activity_type` ";
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
		
		function getActivityTypeById($id){
			$output = "";
			$sql = "SELECT `type` FROM `activity_type` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['type'];
			}
			return $output;
		}		
		
		function listActivityType($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'activity_type', $condition);
			$sql = "SELECT * FROM `activity_type` WHERE 1=1 ";
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
		
		function checkActivityTypeIDExist($id){
			$sql = "SELECT * FROM `activity_type` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkActivityTypeExist($type, $id = ""){
			$sql = "SELECT * FROM `activity_type` WHERE LOWER(`type`)='".strtolower($type)."'";
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
		
		function getActivityTypeData($id){
			$output = array();
			$sql = "SELECT * FROM `activity_type` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}		
		
		function deleteActivityType($id){
			if($this->db->delete("activity_type", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveActivityType($data){
			if($this->db->insert("activity_type", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateActivityType($data){
			if($this->db->update("activity_type", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
	}
?>