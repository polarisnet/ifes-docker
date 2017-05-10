<?php
	class AuditTrails{
		var $db;
		var $totalRow;
		
		function AuditTrails($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getAuditTrailsData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_audit_trails` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function listAuditTrailsField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_audit_trails`";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'created_by_format'");
			array_push($output, "'enc_id'");
			return $output;
		}
		
		function listAuditTrails($condition = '', $start = 0, $limit = 0, $selection = "*", $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_audit_trails', $condition);
			$sql = "SELECT ".$selection." FROM `sys_audit_trails` WHERE 1=1 ";
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
		
		function clearAuditTrails(){
			$sql = "TRUNCATE `sys_audit_trails`";
			$this->db->query($sql);
		}
		
		function deleteAuditTrails($id){
			if($this->db->delete("sys_audit_trails", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function checkAuditTrailsExist($id){
			$sql = "SELECT * FROM `sys_audit_trails` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
	}
?>