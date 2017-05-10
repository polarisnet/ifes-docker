<?php
	class SearchManagement{
		var $db;
		var $totalRow;
		
		function SearchManagement($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getSearchManagementCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_search', $condition);
			$sql = "SELECT `id`, `module_name` FROM `sys_search` WHERE 1=1 ".$condition." ORDER BY `module_name` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['module_name'] = $result_data['module_name'];
				array_push($output, $temp);
			}
			return $output;
		}
		
		function listSearchManagementField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_search` ";
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
				
		function listSearchManagement($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_search', $condition);
			$sql = "SELECT * FROM `sys_search` WHERE 1=1 AND `status`=1 ";
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
				
		function checkModuleNameExist($id){
			$sql = "SELECT * FROM `sys_search` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getModuleNameById($id){
			$output = "";
			$sql = "SELECT `module_name` FROM `sys_search` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['module_name'];
			}
			return $output;
		}
				
		function getSearchManagementData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_search` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
				
		function deleteSearchManagement($id){
			if($this->db->delete("sys_search", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveSearchManagement($data){
			if($this->db->insert("sys_search", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateSearchManagement($data){
			if($this->db->update("sys_search", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		//Serch Field		
		function listSearchFieldField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_search_field` ";
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
				
		function listSearchField($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_search_field', $condition);
			$sql = "SELECT * FROM `sys_search_field` WHERE 1=1 ";
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
		
		function listSearchFieldCombo($table){
			$output = array();
			$sql = "SHOW FULL COLUMNS FROM `".$table."` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($result['Comment'] != ''){
					array_push($output, $result);
				}	
			}
			return $output;
		}
		
		function validateField($id, $search_id, $field){
			$condition = " AND `search_id`='".$search_id."' AND `field`='".$field."' ";
			if($id != ''){
				$condition .= " AND id != '".$id."'";
			}
			$sql = "SELECT * FROM `sys_search_field` WHERE 1=1 ".$condition." LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkFieldExist($id){
			$sql = "SELECT * FROM `sys_search_field` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getSearchFieldData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_search_field` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function deleteSearchField($id){
			if($this->db->delete("sys_search_field", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveSearchField($data){
			if($this->db->insert("sys_search_field", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateSearchField($data){
			if($this->db->update("sys_search_field", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		//End - Search Field
		
		//Search function
		function getSearchFuncCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$getModuleforSearch = $this->listSearchManagement();
			$totalModuleforSearch = count($getModuleforSearch)-1;
			$checkModule = false;
			if(!empty($getModuleforSearch)){
				$sql = "SELECT `s`.`uid`,`s`.`module_id`, `s`.`module_name`, `s`.`module_desc` FROM ( ";
				foreach($getModuleforSearch AS $key => $value){
					$getFieldSearch = getVariousIdsByConditions("field", "sys_search_field", " AND `search_id`='".$value['id']."' ");
					$totalFieldSearch = count($getFieldSearch)-1;
					$access 	= checkAccess(getModuleURL($value['module_uid']));
					if($access){		
						$value['module_uid'] = getModuleURL($value['module_uid']);
						if(!empty($getFieldSearch)){							
							if($checkModule && $totalModuleforSearch > 0 && $key > 0){
								$sql .=" UNION ALL ";
							}
							$sql .=" SELECT '".$value['module_uid']."' as `uid`, '".$value['module_name']."' as `module_name`, `id` as `module_id`, `".$value['desc_field']."` as `module_desc` FROM `".$value['db']."` WHERE ";
							foreach($getFieldSearch AS $keyField => $valueField){
								$sql .=" `".$valueField."` LIKE '%".$condition."%' ";
								if($totalFieldSearch > 0 && ($keyField == 0 || $keyField < $totalFieldSearch)){
									$sql .=" OR ";
								}
							}
							$checkModule = true;
						}else{
							$checkModule = false;
						}
					}	
				}
				$sql .=" ) `s` ORDER BY `s`.`module_desc` ";
				$result = $this->db->query($sql);
				$this->totalRow = $this->db->numRow();
				$sql .=" LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$result_data['module_id'] = rawurlencode(encryption($result_data['module_id'], $salt, true));
				array_push($output, $result_data);
			}
			
			return $output;
		}
		//End Search function
	}
?>