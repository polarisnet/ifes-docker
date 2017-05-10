<?php
	class CustomField{
		var $db;
		var $totalRow;
		
		function CustomField($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function listCustomFieldField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_custom_field` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			array_push($output, "'form_name'");
			array_push($output, "'section_name'");
			return $output;
		}
		
		function checkOptionLabelExist($label, $moduleID, $id = ""){ 
			$sql = "SELECT * FROM `sys_custom_field` WHERE LOWER(`cf_label`)='".strtolower($label)."' AND `sys_module_id` = '".$moduleID."' ";
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
				
		function getSectionCombo($filter_query, $strSorting, $start, $limit){			
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_custom_field_section', $strSorting);
			$sql = "SELECT * FROM `sys_custom_field_section` WHERE 1=1 AND `module_id` = '".$filter_query."' ".$strSorting." ORDER BY `section_name` ASC LIMIT ".$start.", ".$limit.""; 
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));				
				$temp['section_name'] = ucfirst($result_data['section_name']);	
				$temp['section_column'] = $result_data['section_column'];
				array_push($output, $temp);
			}
			return $output;
		}
		
		function getCustomFieldLabelById($id){
			$output = "";
			$sql = "SELECT `cf_label` FROM `sys_custom_field` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['cf_label'];
			}
			return $output;
		}	
		
		function getSectionNameById($id){
			$output = "";
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$decID = encryption(rawurldecode($id), $_SESSION['salt'], false);
			$sql = "SELECT `section_name` FROM `sys_custom_field_section` WHERE `id`='".$decID."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['section_name'];
			}
			return $output;
		}
		
		function getSectionColumnById($id){
			$output = "";
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$decID = encryption(rawurldecode($id), $_SESSION['salt'], false);
			$sql = "SELECT `section_column` FROM `sys_custom_field_section` WHERE `id`='".$decID."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['section_column'];
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
		
		function listCustomField($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_custom_field', $condition);
			$sql = "SELECT * FROM `sys_custom_field` WHERE 1=1 ";
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
				if($result['cf_position'] == "right") {
					$result['cf_position'] = "Right Side";
				} else {
					$result['cf_position'] = "Left Side";
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
				if($result['cf_type'] == "textfield"){
					$result['cf_type'] = "Text Input";
				} else if($result['cf_type'] == "textarea"){
					$result['cf_type'] = "Text Area";
				} else if($result['cf_type'] == "numeric"){
					$result['cf_type'] = "Numeric Input";
				} else if($result['cf_type'] == "date"){
					$result['cf_type'] = "Date";
				} else if($result['cf_type'] == "dropdown"){
					$result['cf_type'] = "Drop Down";
				} else if($result['cf_type'] == "checkbox"){
					$result['cf_type'] = "Check Box";
				} else if($result['cf_type'] == "radio"){
					$result['cf_type'] = "Radio Button";
				}
				
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);									
					$output[$key]['form_name'] = $this->getFormNameById(rawurlencode(encryption($value['sys_module_id'], $salt, true)),$value['module_uid']);
					$output[$key]['section_name'] = $this->getSectionNameById(rawurlencode(encryption($value['cf_section_id'], $salt, true)));	
				}
			}
			return $output;
		}
		
		function checkCustomFieldExist($id){
			$sql = "SELECT * FROM `sys_custom_field` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		
		function checkLabelExist($label, $formID, $id = ""){
			$sql = "SELECT * FROM `sys_custom_field` WHERE LOWER(`cf_label`)='".strtolower($label)."' AND `sys_module_id` = '".$formID."' ";
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
		
		function getCustomFieldData($id){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT * FROM `sys_custom_field` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
				$output['sys_module_id']	= rawurlencode(encryption($output['sys_module_id'], $salt, true)); 
				$output['cf_section_id']	= rawurlencode(encryption($output['cf_section_id'], $salt, true)); 
				$output['cf_column']		= $this->getSectionColumnById($output['cf_section_id']); 
			}
			return $output;
		}	
		
		function getRelativeCustomFieldData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_custom_field_data` WHERE `module_data_id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function deleteRelativeCustomFields($id){
			if($this->db->delete("sys_custom_field_data", "`module_data_id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function deleteCustomField($id){
			if($this->db->delete("sys_custom_field", "`id`='".$id."'")){
				$this->db->delete("sys_custom_field_content", "`cf_id`='".$id."'");
				$this->db->delete("sys_custom_field_data", "`cf_id`='".$id."'");
				return true;
			}else{
				return false;
			}
		}
		
		function saveCustomField($data){
			if($this->db->insert("sys_custom_field", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateCustomField($data){
			if($this->db->update("sys_custom_field", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}			
				
		//***Option Settings - Start ***//
		function listOptionsField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_custom_field_content` ";
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
			$this->totalRow = $this->db->countRow('id', 'sys_custom_field_content', $condition);
			$sql = "SELECT * FROM `sys_custom_field_content` WHERE 1=1 ";
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
			$sql = "SELECT * FROM `sys_custom_field_content` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function saveOptions($data){
			if($this->db->insert("sys_custom_field_content", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateOptions($data){
			if($this->db->update("sys_custom_field_content", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		function deleteOptions($id, $data){
			if($this->db->delete("sys_custom_field_content", "`id`='".$id."'")){
				$this->db->delete("sys_custom_field_data", "`cf_id`='".$data['cf_id']."' AND `cf_data`='".$data['cf_content_value']."'");
				return true;
			}else{
				return false;
			}
		}
		//***Option Settings - End ***//
		
		//***Displat Custom Field - Start ***//
		function checkFormwithCustomeField($uid){
			$sql = "SELECT * FROM `sys_custom_field` WHERE `module_uid`='".$uid."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function listCustomerFieldbyUID($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_custom_field', $condition);
			$sql = "SELECT * FROM `sys_custom_field` WHERE 1=1 ";
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
                
                function listVendorFieldbyUID($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_custom_field', $condition);
			$sql = "SELECT * FROM `sys_custom_field` WHERE 1=1 ";
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
		
		function getCustomFieldNew($UID, $section, $position){
			$newsTemplate = "";					
			$sql = "SELECT `sys_custom_field`.*, `sys_custom_field_section`.`section_name`, `sys_custom_field_section`.`section_num` FROM `sys_custom_field` LEFT JOIN `sys_custom_field_section` ON `sys_custom_field`.`cf_section_id` = `sys_custom_field_section`.`id` WHERE `sys_custom_field`.`cf_status` = 1 AND `sys_custom_field`.`module_uid` = '".$UID."' AND `sys_custom_field_section`.`section_num` = '".$section."' AND `cf_position` = '".$position."' ORDER BY `sys_custom_field`.`cf_order` ASC ";			
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
				
				if($result['cf_type'] == "textfield" || $result['cf_type'] == "numeric"){					
					$newsTemplate .= '<td><input type="text" class="flat-input" id="'.$result['cf_code'].'" name="'.$result['cf_code'].'" value= "'.$_SESSION[$result['cf_code']].'"></td>';
					$newsTemplate .= '</tr>';				
					
				} else if($result['cf_type'] == "textarea"){					
					$newsTemplate .= '<td><textarea class="flat-textarea" style="height: 70px;" id="'.$result['cf_code'].'" name="'.$result['cf_code'].'">'.$_SESSION[$result['cf_code']].'</textarea></td>';
					$newsTemplate .= '</tr>';		
							
				} else if($result['cf_type'] == "date"){					
					$newsTemplate .= '<td><div id="input_'.$result['cf_code'].'_date"></div></td>';
					$newsTemplate .= '</tr>';
					$newsTemplate .= '<script type="text/javascript">';
					$newsTemplate .= 'Ext.onReady(function(){';
					$newsTemplate .= 'Ext.create("Ext.form.field.Date", {renderTo: "input_'.$result['cf_code'].'_date", id: "'.$result['cf_code'].'", name: "'.$result['cf_code'].'", width: 208, format: "d/m/Y", value: "'.$_SESSION[$result['cf_code']].'", maskRe: /[0-9\/]/});';
					$newsTemplate .= '}); </script>';
					
				} else if($result['cf_type'] == "dropdown" || $result['cf_type'] == "checkbox" || $result['cf_type'] == "radio"){					
					$outputDB = array();
					$sqlDB = "SELECT `cf_content_label`,`cf_content_value` FROM `sys_custom_field_content` WHERE `cf_id` = '".$result['id']."' ORDER BY `cf_content_order`,`cf_content_label` ASC";					
					//$rs = mysql_query($sqlDB); //for php < 5.5
					$rs = mysqli_query($this->db->getConnection(), $sqlDB);
					while($row = mysqli_fetch_assoc($rs)) {
						array_push($outputDB, $row);
					}

					if($result['cf_type'] == "dropdown"){					
						$newsTemplate .= '<td><select id="'.$result['cf_code'].'" name="'.$result['cf_code'].'" class="flat-selectbox">';
						$newsTemplate .= '<option value="">Please select '.strtolower($result['cf_label']).'</option>';										
						foreach($outputDB AS $key => $value){
							$select = "";
							if($value['cf_content_value'] == $_SESSION[$result['cf_code']]){ $select = "selected"; }
							$newsTemplate .= '<option value="'.$value['cf_content_value'].'" '.$select.'>'.$value['cf_content_label'].'</option>';
						}												
					} else if($result['cf_type'] == "checkbox"){
						$newsTemplate .= '<td>';
						$checkboxData = '';
						if($_SESSION[$result['cf_code']] != ''){
							$checkboxData = explode(",", $_SESSION[$result['cf_code']]);	
						}
						foreach($outputDB AS $key => $value){
							$checked = "";
							if($checkboxData != ''){									
								foreach($checkboxData AS $key => $data){
									if($value['cf_content_value'] == trim($data)){ $checked = "checked"; }
								}
							}						
							$newsTemplate .= '<input type="checkbox" class="flat-checkbox2" id="'.$value['cf_content_label'].'" name="'.$result['cf_code'].'[]" value= "'.$value['cf_content_value'].'" '.$checked.'><label class="flat-checkbox3">'.$value['cf_content_label'].'</label></br>';							
						}																
					} else if($result['cf_type'] == "radio"){
						$newsTemplate .= '<td>';
						foreach($outputDB AS $key => $value){
							$checked = "";
							if($value['cf_content_value'] == $_SESSION[$result['cf_code']]){ $checked = "checked"; }						
							$newsTemplate .= '<input type="radio" class="flat-checkbox2" id="'.$value['cf_content_label'].'" name="'.$result['cf_code'].'" value= "'.$value['cf_content_value'].'" '.$checked.'><label class="flat-checkbox3">'.$value['cf_content_label'].'</label></br>';
						}
					}
					$newsTemplate .= '</td></tr>';
				} 
			}
			return $newsTemplate;
		}
		
		function getCustomField($UID, $section, $position, $parentID, $mode ){	
			$newsTemplate = "";	
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$dec_parentID = encryption($parentID, $salt, false);
			$sql = "SELECT `sys_custom_field`.*, `sys_custom_field_section`.`section_name`, `sys_custom_field_section`.`section_num` FROM `sys_custom_field` LEFT JOIN `sys_custom_field_section` ON `sys_custom_field`.`cf_section_id` = `sys_custom_field_section`.`id` WHERE `sys_custom_field`.`cf_status` = 1 AND `sys_custom_field`.`module_uid` = '".$UID."' AND `sys_custom_field_section`.`section_num` = '".$section."' AND `sys_custom_field`.`cf_position` = '".$position."' ORDER BY `sys_custom_field`.`cf_order` ASC  ";			
			$this->db->query($sql);	
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();				
				$newsTemplate .= '<tr>';
				$newsTemplate .= '<td class="lbl-field">'.$result['cf_label'].'</td>';		
				$sqlCheck = "SELECT * FROM `sys_custom_field_data` WHERE `cf_id` = '".$result['id']."' AND `module_data_id`='".$dec_parentID."' ";
				//$rsCheck = mysql_query($sqlCheck); //for php < 5.5
				$rsCheck = mysqli_query($this->db->getConnection(), $sqlCheck);
				if($mode == "view"){
					$newsTemplate .= '<td class="lbl-gap">:</td>';
					if($rowCheck = mysqli_fetch_assoc($rsCheck)){	
						if($result['cf_type'] == "textarea"){
							$newsTemplate .= '<td>'.nl2br($rowCheck['cf_data']).'</td>';						
						} else if($result['cf_type'] == "dropdown" || $result['cf_type'] == "radio"){						
							$sqlDB = "SELECT `cf_content_label` FROM `sys_custom_field_content` WHERE `cf_id` = '".$result['id']."' AND `cf_content_value` = '".$rowCheck['cf_data']."' ";					
							//$rs = mysql_query($sqlDB); //for php < 5.5
							$rs = mysqli_query($this->db->getConnection(), $sqlDB);
							while ($row = mysqli_fetch_assoc($rs)) {							
								$newsTemplate .= '<td>'.$row['cf_content_label'].'</td>';							
							}
						} else if($result['cf_type'] == "checkbox"){
							$checkboxData  = explode(",",$rowCheck['cf_data']);					
							$newsTemplate .= '<td>';						
							foreach($checkboxData AS $key => $value){
								$sqlDB = "SELECT `cf_content_label` FROM `sys_custom_field_content` WHERE `cf_id` = '".$result['id']."' AND `cf_content_value` = '".trim($value)."' ";					
								//$rs = mysql_query($sqlDB); //for php < 5.5
								$rs = mysqli_query($this->db->getConnection(), $sqlDB);
								while ($row = mysqli_fetch_assoc($rs)) {													
									$newsTemplate .= $row['cf_content_label']."</br>";								
								}
							}						
							$newsTemplate .= '</td>';
						} else {							
							$newsTemplate .= '<td>'.$rowCheck['cf_data'].'</td>';						
						}
						$newsTemplate .= '</tr>';
					}else{									
						$newsTemplate .= '<td>&nbsp;</td>';	
					}
				} else { //mode Edit
					if($result['cf_mandatory'] == 1){
						$newsTemplate .= '<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>';
					} else {
						$newsTemplate .= '<td class="lbl-gap">&nbsp;</td>';
					}
					
					if($result['cf_type'] == "textfield" || $result['cf_type'] == "numeric"){
						$newsTemplate .= '<td><input type="text" class="flat-input" id="'.$result['cf_code'].'" name="'.$result['cf_code'].'" value="'.$_SESSION[$result['cf_code']].'"></td>';
						$newsTemplate .= '</tr>';						
					} else if($result['cf_type'] == "textarea"){			
						$newsTemplate .= '<td><textarea class="flat-textarea" style="height: 70px;" id="'.$result['cf_code'].'" name="'.$result['cf_code'].'">'.stripslashes(str_replace('\r\n', "\n", $_SESSION[$result['cf_code']])).'</textarea></td>';
						$newsTemplate .= '</tr>';
						
					} else if($result['cf_type'] == "date"){						
						$newsTemplate .= '<td><div id="input_'.$result['cf_code'].'_date"></div></td>';
						$newsTemplate .= '</tr>';
						$newsTemplate .= '<script type="text/javascript">';
						$newsTemplate .= 'Ext.onReady(function(){';
						$newsTemplate .= 'Ext.create("Ext.form.field.Date", {renderTo: "input_'.$result['cf_code'].'_date", id: "'.$result['cf_code'].'", name: "'.$result['cf_code'].'", width: 208, format: "d/m/Y", value: "'.$_SESSION[$result['cf_code']].'", maskRe: /[0-9\/]/});';
						$newsTemplate .= '}); </script>';
						
					} else if($result['cf_type'] == "dropdown" || $result['cf_type'] == "checkbox" || $result['cf_type'] == "radio"){
						$outputDB = array();
						$sqlDB = "SELECT `cf_content_label`,`cf_content_value` FROM `sys_custom_field_content` WHERE `cf_id` = '".$result['id']."' ORDER BY `cf_content_order`,`cf_content_label` ASC";					
						//$rs = mysql_query($sqlDB); //for php < 5.5
						$rs = mysqli_query($this->db->getConnection(), $sqlDB);
						while($row = mysqli_fetch_assoc($rs)){
							array_push($outputDB, $row);
						}
						if($result['cf_type'] == "dropdown"){					
							$newsTemplate .= '<td><select id="'.$result['cf_code'].'" name="'.$result['cf_code'].'" class="flat-selectbox">';
							$newsTemplate .= '<option value="">Please select '.strtolower($result['cf_label']).'</option>';										
							foreach($outputDB AS $key => $value){
								$select = "";
								if($value['cf_content_value'] == $_SESSION[$result['cf_code']]){ $select = "selected"; }
								$newsTemplate .= '<option value="'.$value['cf_content_value'].'" '.$select.'>'.$value['cf_content_label'].'</option>';
							}
							
						} else if($result['cf_type'] == "checkbox"){			
							$checkboxData = array();
							if($_SESSION[$result['cf_code']] != ''){
								$checkboxData = explode(",", $_SESSION[$result['cf_code']]);	
							} 
							$newsTemplate .= '<td>';
							foreach($outputDB AS $key => $value){
								$checked = "";								
								if($checkboxData != ''){									
									foreach($checkboxData AS $key => $data){
										if($value['cf_content_value'] == trim($data)){ $checked = "checked"; }
									}
								}
								$newsTemplate .= '<input type="checkbox" class="flat-checkbox2" id="'.$value['cf_content_label'].'" name="'.$result['cf_code'].'[]" value= "'.$value['cf_content_value'].'" '.$checked.'><label class="flat-checkbox3">'.$value['cf_content_label'].'</label></br>';							
							}	
						} else if($result['cf_type'] == "radio"){
							$newsTemplate .= '<td>';
							foreach($outputDB AS $key => $value){
								$checked = "";
								if($value['cf_content_value'] == $_SESSION[$result['cf_code']]){ $checked = "checked"; }						
								$newsTemplate .= '<input type="radio" class="flat-checkbox2" id="'.$value['cf_content_label'].'" name="'.$result['cf_code'].'" value= "'.$value['cf_content_value'].'" '.$checked.'><label class="flat-checkbox3">'.$value['cf_content_label'].'</label></br>';
							}	
						}
						$newsTemplate .= '</td></tr>';
					}
				}
			}
			return $newsTemplate;
		}
		//***Displat Custom Field - End ***//
		
		//***Custom Field Data settings - Start ***//
		function getCustomFieldModuleName($uid){
			$output = array();
			$sql = "SELECT `sys_custom_field`.* FROM `sys_custom_field` WHERE `sys_custom_field`.`cf_status` = 1 AND `sys_custom_field`.`module_uid` = '".$uid."' ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, $result);
			}	
			return $output;
		}
		
		function getCustomFieldModuleData($id, $cf_id){
			$output = "";
			$sql = "SELECT `sys_custom_field_data`.`cf_data`FROM `sys_custom_field_data` LEFT JOIN `sys_custom_field` ON `sys_custom_field_data`.`cf_id` = `sys_custom_field`.`id` WHERE `sys_custom_field_data`.`module_data_id`='".$id."' AND `sys_custom_field_data`.`cf_id`='".$cf_id."' ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['cf_data'];
			}
			return $output;
		}
		
		function checkCustomFieldModuleData($id){
			$sql = "SELECT `sys_custom_field_data`.* FROM `sys_custom_field_data` WHERE `sys_custom_field_data`.`id`='".$id."' LIMIT 1";			
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getCustomFieldDataID($id, $cf_id){
			$output = "";
			$sql = "SELECT `sys_custom_field_data`.`id` FROM `sys_custom_field_data` LEFT JOIN `sys_custom_field` ON `sys_custom_field_data`.`cf_id` = `sys_custom_field`.`id` WHERE `sys_custom_field_data`.`module_data_id`='".$id."' AND `sys_custom_field_data`.`cf_id`='".$cf_id."' ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['id'];
			}
			return $output;
		}
		
		function getCustomFieldContentData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_custom_field_content` WHERE `cf_id`='".$id."'";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, $result);
			}
			return $output;
		}
		
		function saveCustomFieldModuleData($data){
			if($this->db->insert("sys_custom_field_data", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateCustomFieldModuleData($data){print_r($data);
			if($this->db->update("sys_custom_field_data", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}exit;
		}
		//***Custom Field Data settings - End ***//
	}
?>