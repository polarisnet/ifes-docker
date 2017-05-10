<?php
	class SystemField{
		var $db;
		var $totalRow;
		
		function SystemField($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function listSystemFieldField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_additional_field` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			array_push($output, "'form_name'");
			return $output;
		}
		
		function getSystemFieldLabelById($id){
			$output = "";
			$sql = "SELECT `cf_label` FROM `sys_additional_field` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['cf_label'];
			}
			return $output;
		}	
		
		function listSystemField($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_additional_field', $condition);
			$sql = "SELECT * FROM `sys_additional_field` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){
					$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); 
					$result['raw'] = encryption($result['id'], $salt, true);					
				} else {
					$result['enc_id'] = '';
				}
				if($result['cf_mandatory'] == 1) {
					$result['cf_mandatory'] = "Yes";
				} else {
					$result['cf_mandatory'] = "No";
				}
				if($result['cf_status'] == 1) {
					$result['cf_status'] = "Enabled";
				} else {
					$result['cf_status'] = "Disabled";
				}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);									
					$output[$key]['form_name'] = $this->getFormNameById(rawurlencode(encryption($value['sys_module_id'], $salt, true)),$value['module_uid']);
				}
			}
			return $output;
		}
		
		function checkLabelExist($label, $formID, $id = ""){
			$sql = "SELECT * FROM `sys_additional_field` WHERE LOWER(`cf_label`)='".strtolower($label)."' AND `sys_module_id` = '".$formID."' ";
			if($id != ""){
				$sql .= " AND `id` != '".$id."' ";
			}
			$sql .= " LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getSystemFieldData($id){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT * FROM `sys_additional_field` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
				$output['sys_module_id']	= rawurlencode(encryption($output['sys_module_id'], $salt, true)); 
			}
			return $output;
		}	
		
		function updateSystemField($data){
			if($this->db->update("sys_additional_field", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}			
				
		function getModuleFormCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_module', $condition);
			$sql = "SELECT `id`, `uid`, `module_display` FROM `sys_module` WHERE 1=1 ".$condition." ORDER BY `module_display` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['uid'] = strtoupper($result_data['uid']);
				$temp['module_display'] = $result_data['module_display'];
				array_push($output, $temp);
			}
			return $output;
		}
		
		function getFormNameById($id, $module_uid){
			$output = "";
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$decID = encryption(rawurldecode($id), $_SESSION['salt'], false);
			$sql = "SELECT `module_display` FROM `sys_module` WHERE `id`='".$decID."' AND `uid`='".$module_uid."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['module_display'];
			}
			return $output;
		}
		
		 //***Get System Field - Start ***//
        function getSystemTypeCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_additional_field_content', $condition);
			$sql = "SELECT `id`,`cf_content_label`,`cf_content_value` FROM `sys_additional_field_content` WHERE 1=1 ".$condition." ORDER BY `cf_content_order` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));				
				$temp['label'] = $result_data['cf_content_label'];
				$temp['value'] = $result_data['cf_content_value'];
				array_push($output, $temp);
			}
			return $output;
		}
        
        function getSystemFielContentdLabelByID($id){
			$output = ""; 
			$sql = "SELECT `cf_content_value` FROM `sys_additional_field_content` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['cf_content_value'];
			}
			return $output;
		}
		
		function getSystemFielContentdValueByID($id){
			$output = ""; 
			$sql = "SELECT `cf_content_label` FROM `sys_additional_field_content` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['cf_content_label'];
			}
			return $output;
		}
        //***Get System Field - End ***//
		
		//***Option Settings - Start ***//
		function listOptionsField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_additional_field_content` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}			
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			array_push($output, "'enc_parent_id'");
			array_push($output, "'type'");
			return $output;
		}
		
		function listOptions($parent = '', $condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_additional_field_content', $condition);
			$sql = "SELECT * FROM `sys_additional_field_content` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}			
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){
					$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); 
					$result['raw'] = encryption($result['id'], $salt, true);
					$result['enc_parent_id'] = rawurlencode(encryption($result['id'], $salt, true)); 
					$result['type'] = ucfirst($result['cf_remark']);
					
				}else{
					$result['enc_id'] = '';	
					$result['enc_parent_id'] = '';
					$result['type'] = '';									
				}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);
				}
			}
			return $output;
		}
		
		function getOptionsData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_additional_field_content` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function checkOptionValueExist($value, $formID, $id = ""){
			$sql = "SELECT * FROM `sys_additional_field_content` WHERE `cf_id` = '".$formID."' AND `cf_content_value` ='".$value."' ";
			if($id != ""){
				$sql .= " AND `id` != '".$id."' ";
			}
			$sql .= " LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function saveOptions($data){
			if($this->db->insert("sys_additional_field_content", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateOptions($data){
			if($this->db->update("sys_additional_field_content", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		function deleteOptions($id, $data){
			if($this->db->delete("sys_additional_field_content", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		//***Option Settings - End ***//
		
		//***Display System Field - Start ***//
		function listSystemFieldbyUID($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_additional_field', $condition);
			$sql = "SELECT * FROM `sys_additional_field` WHERE 1=1 ";
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
		
		function getSystemFieldNew($UID, $id, $fieldname){
			$newsTemplate = "";					
			$sql = "SELECT `sys_additional_field`.* FROM `sys_additional_field` WHERE `sys_additional_field`.`cf_status` = 1 AND `sys_additional_field`.`id` = '".$id."' AND `sys_additional_field`.`module_uid` = '".$UID."' ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();				
				$newsTemplate .= '<tr>';
				$newsTemplate .= '<td class="lbl-field">'.$result['cf_label'].'</td>';
				if($result['cf_mandatory'] == 1){
					$newsTemplate .= '<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>';
				} else {
					$newsTemplate .= '<td class="lbl-gap">&nbsp;</td>';
				}
				
				if($result['cf_type'] == "dropbox"){					
					$outputDB = array();
					$sqlDB = "SELECT `cf_content_label`,`cf_content_value` FROM `sys_additional_field_content` WHERE `cf_id` = '".$result['id']."' ORDER BY `cf_content_order` ASC ";					
					//$rs = mysql_query($sqlDB); //for php < 5.5
					$rs = mysqli_query($this->db->getConnection(), $sqlDB);
					while($row = mysqli_fetch_assoc($rs)){
						array_push($outputDB, $row);
					}
					$newsTemplate .= '<td><select id="'.$result['cf_code'].'" name="'.$fieldname.'" class="flat-selectbox">';
                    $newsTemplate .= '<option value="">Please select '.strtolower($result['cf_label']).'</option>';										
                    if($result['module_uid'] == $UID) {
						foreach($outputDB AS $key => $value){
							$select = "";
							if(isset($_SESSION["system_".$result['cf_code']]) && $value['cf_content_value'] == $_SESSION["system_".$result['cf_code']]){ $select = "selected"; }
							$newsTemplate .= '<option value="'.$value['cf_content_value'].'" '.$select.'>'.$value['cf_content_label'].'</option>';
						}												
					}
					$newsTemplate .= '</td></tr>';
				} 
			}
			return $newsTemplate;
		}
		
		function getSystemField($UID, $id, $fieldname, $parentID, $mode, $currValue){	
			$newsTemplate = "";	
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$dec_parentID = encryption($parentID, $salt, false);
			$sql = "SELECT `sys_additional_field`.* FROM `sys_additional_field` WHERE `sys_additional_field`.`cf_status` = 1 AND `sys_additional_field`.`id` = '".$id."' "; // AND `sys_additional_field`.`module_uid` = '".$UID."'
			$this->db->query($sql);	
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();				
				$newsTemplate .= '<tr>';
				$newsTemplate .= '<td class="lbl-field">'.$result['cf_label'].'</td>';
				
				$outputDB = array();
				$sqlDB = "SELECT `cf_content_label`,`cf_content_value` FROM `sys_additional_field_content` WHERE `cf_id` = '".$result['id']."' ORDER BY `cf_content_order` ASC ";					
				//$rs = mysql_query($sqlDB); //for php < 5.5
				$rs = mysqli_query($this->db->getConnection(), $sqlDB);
				while ($row = mysqli_fetch_assoc($rs)) {
					array_push($outputDB, $row);
				}
				
				if($mode == "view"){
					$newsTemplate .= '<td class="lbl-gap">:</td>';
					$select = "";
					foreach($outputDB AS $key => $value){
						if($value['cf_content_value'] == $currValue){ $select = $value['cf_content_label']; }
					}
					$newsTemplate .= '<td>'.$select.'</td>';	
					$newsTemplate .= '</tr>';
				} else { //mode Edit
					if($result['cf_mandatory'] == 1){
						$newsTemplate .= '<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>';
					} else {
						$newsTemplate .= '<td class="lbl-gap">&nbsp;</td>';
					}
					if($result['cf_type'] == "dropbox"){
						$newsTemplate .= '<td><select id="'.$result['cf_code'].'" name="'.$fieldname.'" class="flat-selectbox">';
						$newsTemplate .= '<option value="">Please select '.strtolower($result['cf_label']).'</option>';										
						if($result['module_uid'] == $UID) {
							foreach($outputDB AS $key => $value){
								$select = "";
								if($value['cf_content_value'] == $currValue){ $select = "selected"; }
								$newsTemplate .= '<option value="'.$value['cf_content_value'].'" '.$select.'>'.$value['cf_content_label'].'</option>';
							}
						}
						$newsTemplate .= '</td></tr>';
					}
				}
			}
			return $newsTemplate;
		}
		
		function getOptionofSFByCF_ID($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_additional_field_content', $condition);
			$sql = "SELECT * FROM `sys_additional_field_content` WHERE 1=1 ".$condition." ORDER BY `cf_content_order` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['cf_content_label'] = $result_data['cf_content_label'];
				$temp['cf_content_value'] = $result_data['cf_content_value'];
				array_push($output, $temp);
			}			
			return $output;
		}
		
		function getSystemFieldModuleName($uid){
			$output = array();
			$sql = "SELECT `sys_additional_field`.* FROM `sys_additional_field` WHERE `sys_additional_field`.`cf_status` = 1 AND `sys_additional_field`.`module_uid` = '".$uid."' ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, $result);
			}	
			return $output;
		}
		//***Display System Field - End ***//
		
	}
?>