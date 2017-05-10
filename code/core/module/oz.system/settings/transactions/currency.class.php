<?php
	class Currency{
		var $db;
		var $totalRow;
		
		function Currency($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getCurrencyCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_currency', $condition);
			$sql = "SELECT `id`, `symbol`, `code` FROM `sys_currency` WHERE 1=1 ".$condition." ORDER BY `code` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['code'] = strtoupper($result_data['code']);
				$temp['symbol'] = $result_data['symbol'];
				array_push($output, $temp);
			}
			return $output;
		}
		
		function listCurrencyField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_currency` ";
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
		
		function getCurrencyIdByCode($code){
			$output = "";
			$sql = "SELECT `id` FROM `sys_currency` WHERE `code`='".$code."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['id'];
			}
			return $output;
		}
		
		function getCurrencyCodeById($id){
			$output = "";
			$sql = "SELECT `code` FROM `sys_currency` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['code'];
			}
			return $output;
		}
		
		function getCurrencySymbolById($id){
			$output = "";
			$sql = "SELECT `symbol` FROM `sys_currency` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['symbol'];
			}
			return $output;
		}
		
		function listCurrency($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_currency', $condition);
			$sql = "SELECT * FROM `sys_currency` WHERE 1=1 ";
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
		
		function checkCurrencyExist($id){
			$sql = "SELECT * FROM `sys_currency` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkCurrencyCodeExist($code, $id = ""){
			$sql = "SELECT * FROM `sys_currency` WHERE LOWER(`code`)='".strtolower($code)."'";
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
		
		function getCurrencyData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_currency` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		
		function deleteCurrency($id){
			if($this->db->delete("sys_currency", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveCurrency($data){
			if($this->db->insert("sys_currency", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateCurrency($data){
			if($this->db->update("sys_currency", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
	}
?>