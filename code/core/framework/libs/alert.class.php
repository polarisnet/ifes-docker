<?php
	class Alert{
		var $db;
		var $totalRow;
		
		function Alert($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function checkAlertExist($id){
			$sql = "SELECT * FROM `sys_alerts` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getAlertData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_alerts` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function deleteAlert($id){
			if($this->db->delete("sys_alerts", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveAlert($data){
			if($this->db->insert("sys_alerts", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateAlert($data){
			if($this->db->update("sys_alerts", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function listAlertField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_alerts` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			if(isset($output['content'])){unset($output['content']);}
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			return $output;
		}
		
		function listAlert($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_alerts', $condition);
			$sql = "SELECT * FROM `sys_alerts` WHERE 1=1 ";
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
		
		function getHeaderById($id){
			$output = "";
			$sql = "SELECT `header` FROM `sys_alerts` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['header'];
			}
			return $output;
		}
		
		function getTableCombo(){
			$array = array();
			$temp = array();
			$temp['table'] = 'dashboard';
			$temp['label'] = 'Dashboard';
			$temp['column'] = '';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'customers';
			$temp['label'] = 'Customer Form';
			$temp['column'] = 'name, cust_no';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'contacts';
			$temp['label'] = 'Contact Form';
			$temp['column'] = 'first_name, last_name';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'items';
			$temp['label'] = 'Item Form';
			$temp['column'] = 'name, item_no_1';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'items_category';
			$temp['label'] = 'Category Form';
			$temp['column'] = 'category, code';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'items_departments';
			$temp['label'] = 'Department Form';
			$temp['column'] = 'department, code';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'items_misc';
			$temp['label'] = 'Miscellaneous Form';
			$temp['column'] = 'name, code';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'items_groups';
			$temp['label'] = 'Group Form';
			$temp['column'] = 'group, code';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'projects';
			$temp['label'] = 'Project Form';
			$temp['column'] = 'project_name';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'salesperson';
			$temp['label'] = 'Sales Person Form';
			$temp['column'] = 'name, code';
			array_push($array, $temp);
			$temp = array();
			$temp['table'] = 'transactions_promotions';
			$temp['label'] = 'Promotion Form';
			$temp['column'] = 'code, name';
			array_push($array, $temp);
			return $array;
		}
		
		function getTableLabel($table){
			$output = "";
			$cmbTable = $this->getTableCombo();
			foreach($cmbTable AS $key => $data){
				if($table == $data['table']){
					$output = $data['label'];
					break;
				}
			}
			return $output;
		}
		
		function getTargetCombo($table, $column, $condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', $table, $condition);
			$selectColumn = "";
			foreach($column AS $col){
				$selectColumn .= ", ".$col;
			}
			$sql = "SELECT id ".$selectColumn." FROM `".$table."` WHERE 1=1 ".$condition." ORDER BY ".$column[0]." ASC ";
			if($start != "" && $limit != ""){
				$sql .= "LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = $result_data['id'];
				$temp['label'] = '';

				foreach($column AS $col){
					if($temp['label'] != ''){$temp['label'] .= '<br>';}
					$temp['label'] .= strtoupper($col).": ".$result_data[$col];
				}
				array_push($output, $temp);
			}
			return $output;
		}
		
		function getTargetLabel($table, $target){
			$output = 'all';
			if($table != 'dashboard' && $target != 'all'){
				$output = '';
				$tableCombo = $this->getTableCombo();
				$column = array();
				foreach($tableCombo AS $key => $tableData){
					if($tableData['table'] == $table){
						$targetTable = $tableData['table'];
						$column = explode(",", $tableData['column']);
						break;
					}
				}
				
				$condition = " AND id IN(".$target.")";
				$sql = "SELECT id, ".$column[0]." FROM `".$table."` WHERE 1=1 ".$condition." ORDER BY ".$column[0]." ASC";
				$this->db->query($sql);
				while($this->db->nextRecord()){
					$result_data = $this->db->getRecord();
					if($output != ''){$output .= ', ';}
					$output .= $result_data[$column[0]];
				}
			}
			return $output;
		}
	}
?>