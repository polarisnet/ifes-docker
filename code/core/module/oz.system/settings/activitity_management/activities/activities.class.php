<?php
	class Activities{
		var $db;
		var $totalRow;
		
		function Activities($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getActivitiesCombo($condition, $start, $limit){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'activities', $condition);
			$sql = "SELECT `id`, `symbol`, `code` FROM `activities` WHERE 1=1 ".$condition." ORDER BY `code` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['code'] = ($result_data['code']);
				$temp['symbol'] = $result_data['symbol'];
				array_push($output, $temp);
			}
			return $output;
		}
		
		function listActivitiesField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `activities` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'activity_type'");
			array_push($output, "'communication_type'");
			array_push($output, "'activity_owner'");
			array_push($output, "'activity_date_format'");
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			array_push($output, "'enc_type_id'");
			array_push($output, "'enc_activity_type_id'");
			array_push($output, "'enc_communication_type_id'");
			array_push($output, "'enc_activity_owner_id'");
			array_push($output, "'source_type'");
			array_push($output, "'source_name'");
			array_push($output, "'source_customer'");
			array_push($output, "'related_owner_id'");
			array_push($output, "'related_owner'");
			array_push($output, "'related_contact_id'");
			array_push($output, "'related_contact'");
			return $output;
		}
		
		function listActivities($parentType, $parent, $condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			require_once DIR_MODULE.'/contacts/contact.class.php';
			require_once DIR_MODULE.'/customer/customer.class.php';
                        require_once DIR_MODULE.'/vendor/vendor.class.php';
			require_once DIR_MODULE.'/project_management/projects/project.class.php';
			require_once DIR_MODULE.'/oz.system/settings/activitity_management/activity_type/activity_type.class.php';
			require_once DIR_MODULE.'/oz.system/settings/activitity_management/communication_type/communication_type.class.php';	
			require_once DIR_MODULE.'/salesperson/salesperson.class.php';		
			global $db;
			$objCustomer = new Customer($GLOBALS['myDB']);
                        $objVendor = new Vendor($GLOBALS['myDB']);
			$objContact = new Contact($GLOBALS['myDB']);
			$objProject = new Project($GLOBALS['myDB']);
			$objActivityType = new ActivityType($GLOBALS['myDB']);
			$objCommunicationType = new CommunicationType($GLOBALS['myDB']);
			$objSalesPerson = new SalesPerson($GLOBALS['myDB']);
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
						
			$join = "LEFT JOIN `activity_type` ON `activities`.`activity_type_id` = `activity_type`.`id` ";
			$join .= "LEFT JOIN `communication_type` ON `activities`.`communication_type_id` = `communication_type`.`id` ";
			$join .= "LEFT JOIN `salesperson` ON `activities`.`activity_owner_id` = `salesperson`.`id` ";
			$this->totalRow = $this->db->countJoinRow('`activities`.`id`', 'activities', $join, $condition);
			$sql = "SELECT `activities`.*, `activity_type`.`type` AS `activity_type`, `communication_type`.`type` AS `communication_type`, `salesperson`.`name` AS `activity_owner` FROM `activities` ".$join." WHERE 1=1 ";
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
					$result['enc_type_id'] = rawurlencode(encryption($result['type_id'], $salt, true));
					$result['enc_activity_type_id'] = rawurlencode(encryption($result['activity_type_id'], $salt, true));
					$result['enc_communication_type_id'] = rawurlencode(encryption($result['communication_type_id'], $salt, true));
					$result['enc_activity_owner_id'] = rawurlencode(encryption($result['activity_owner_id'], $salt, true));
				} else {
					$result['enc_id'] = '';
					$result['enc_type_id'] = '';
					$result['enc_activity_type_id'] = '';
					$result['enc_communication_type_id'] = '';
					$result['enc_activity_owner_id'] = '';
				}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);	
					$output[$key]['related_owner_id'] = implode(",", $this->getActivitiesRelatedOwnerIDByActivityID($value['id']));
					$output[$key]['related_owner'] = implode(", ", $this->getActivitiesRelatedOwnerByActivityID($value['id']));
					$output[$key]['related_contact_id'] = implode(",", $this->getActivitiesRelatedContactIDByActivityID($value['id']));
					$output[$key]['related_contact'] = implode(", ", $this->getActivitiesRelatedContactByActivityID($value['id']));
					$output[$key]['activity_date_format'] = convertToDate($output[$key]['activity_date'])." ".convertToDate($output[$key]['activity_time'],'H:i');		
					$output[$key]['activity_date'] = convertToDate($output[$key]['activity_date']);
					$output[$key]['activity_time'] = convertToDate($output[$key]['activity_time'],'H:i');
					if($output[$key]['type']== 'Project'){
						$output[$key]['source_name'] = $objProject->getProjectNameById($output[$key]['type_id']);
						$output[$key]['source_customer'] = rawurlencode(encryption(getstrVariousIdByConditions("customer_id", "projects", " AND `id`='".$output[$key]['type_id']."' "), $salt, true));
					} else if($output[$key]['type']== 'Contact'){
						$output[$key]['source_name'] = $objContact->getContactFullNameById($output[$key]['type_id']);
						$output[$key]['source_customer'] = rawurlencode(encryption(getstrVariousIdByConditions("customer_id", "contacts", " AND `id`='".$output[$key]['type_id']."' "), $salt, true));
					} else if($output[$key]['type']== 'Customer'){
						$output[$key]['source_name'] = $objCustomer->getCustomerNameById($output[$key]['type_id']);
						$output[$key]['source_customer'] = rawurlencode(encryption($output[$key]['type_id'], $salt, true)); 
					}  else if($output[$key]['type']== 'Vendor'){
						$output[$key]['source_name'] = $objVendor->getVendorNameById($output[$key]['type_id']);
						$output[$key]['source_customer'] = rawurlencode(encryption($output[$key]['type_id'], $salt, true)); 
					}
				}
			}
			return $output;
		}
		
		function getActivitiesCodeById($id){
			$output = "";
			$sql = "SELECT `code` FROM `activities` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['code'];
			}
			return $output;
		}
		
		function getActivitiesSymbolById($id){
			$output = "";
			$sql = "SELECT `symbol` FROM `activities` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['symbol'];
			}
			return $output;
		}	
		
		function checkActivitiesExist($id){
			$sql = "SELECT * FROM `activities` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkActivitiesCodeExist($code, $id = ""){
			$sql = "SELECT * FROM `activities` WHERE LOWER(`code`)='".strtolower($code)."'";
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
		
		function getRelativeActivitiesData($type, $type_id){
			$output = array();
			$sql = "SELECT * FROM `activities` WHERE `type`='".$type."' AND `type_id`='".$type_id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function getActivitiesData($id){
			$output = array();
			$sql = "SELECT * FROM `activities` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function deleteRelativeActivities($type, $type_id){
			if($this->db->delete("activities", "`type`='".$type."' AND `type_id`='".$type_id."'")){
				return true;
			}else{
				return false;
			}
		}
				
		function deleteActivities($id){
			if($this->db->delete("activities", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveActivities($data){
			if($this->db->insert("activities", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateActivities($data){ //print_r($data);exit;
			if($this->db->update("activities", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		//***Activity Related Owner & Contact - Start ***//
		function getActivitiesRelatedOwnerIDByActivityID($id){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT `related_owner_id` FROM `activities_related_owner` WHERE 1=1 AND `activity` = '".$id."' ORDER BY `id` ASC ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();;
				array_push($output, $result_data['related_owner_id']);
			}		
			return $output;
		}
		
		function getActivitiesRelatedOwnerByActivityID($id){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT `related_owner` FROM `activities_related_owner` WHERE 1=1 AND `activity` = '".$id."' ORDER BY `id` ASC ";
			$this->db->query($sql); 
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();	
				array_push($output, $result_data['related_owner']);
			}
			return $output;
		}
		
		function deleteActivitiesRelatedOwner($id){
			if($this->db->delete("activities_related_owner", "`activity`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		function saveActivitiesRelatedOwner($data){
			if($this->db->insert("activities_related_owner", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function getActivitiesRelatedContactIDByActivityID($id){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT `related_contact_id` FROM `activities_related_contact` WHERE 1=1 AND `activity` = '".$id."' ORDER BY `id` ASC ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				array_push($output, $result_data['related_contact_id']);
			}		
			return $output;
		}
		
		function getActivitiesRelatedContactByActivityID($id){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT `related_contact` FROM `activities_related_contact` WHERE 1=1 AND `activity` = '".$id."' ORDER BY `id` ASC ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();	
				array_push($output, $result_data['related_contact']);
			}
			return $output;
		}
		
		function deleteActivitiesRelatedContact($id){
			if($this->db->delete("activities_related_contact", "`activity`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveActivitiesRelatedContact($data){
			if($this->db->insert("activities_related_contact", $data)){
				return true;
			}else{
				return false;
			}
		}
		//***Activity Related Owner & Contact - End  ***//
		
		//***Report Activity by Customer - Start ***//
		function listActivitiesByCustomer($condition, $encrypt = true, $format = true){
			require_once DIR_MODULE.'/contacts/contact.class.php';
			require_once DIR_MODULE.'/customer/customer.class.php';
			require_once DIR_MODULE.'/project_management/projects/project.class.php';
			require_once DIR_MODULE.'/oz.system/settings/activitity_management/activity_type/activity_type.class.php';
			require_once DIR_MODULE.'/oz.system/settings/activitity_management/communication_type/communication_type.class.php';	
			require_once DIR_MODULE.'/salesperson/salesperson.class.php';		
			global $db;
			$objCustomer = new Customer($GLOBALS['myDB']);
			$objContact = new Contact($GLOBALS['myDB']);
			$objProject = new Project($GLOBALS['myDB']);
			$objActivityType = new ActivityType($GLOBALS['myDB']);
			$objCommunicationType = new CommunicationType($GLOBALS['myDB']);
			$objSalesPerson = new SalesPerson($GLOBALS['myDB']);
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
						
			$join = "LEFT JOIN `activity_type` ON `activities`.`activity_type_id` = `activity_type`.`id` ";
			$join .= "LEFT JOIN `communication_type` ON `activities`.`communication_type_id` = `communication_type`.`id` ";
			$join .= "LEFT JOIN `salesperson` ON `activities`.`activity_owner_id` = `salesperson`.`id` ";
			$this->totalRow = $this->db->countJoinRow('`activities`.`id`', 'activities', $join, $condition);
			$sql = "SELECT `activities`.*, `activity_type`.`type` AS `activity_type`, `communication_type`.`type` AS `communication_type`, `salesperson`.`name` AS `activity_owner` FROM `activities` ".$join." WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){
					$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); 
					$result['raw'] = encryption($result['id'], $salt, true);
					$result['enc_type_id'] = rawurlencode(encryption($result['type_id'], $salt, true));
					$result['enc_activity_type_id'] = rawurlencode(encryption($result['activity_type_id'], $salt, true));
					$result['enc_communication_type_id'] = rawurlencode(encryption($result['communication_type_id'], $salt, true));
					$result['enc_activity_owner_id'] = rawurlencode(encryption($result['activity_owner_id'], $salt, true));
				} else {
					$result['enc_id'] = '';
					$result['enc_type_id'] = '';
					$result['enc_activity_type_id'] = '';
					$result['enc_communication_type_id'] = '';
					$result['enc_activity_owner_id'] = '';
				}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);				
					$output[$key]['related_owner'] = implode(", ", $this->getActivitiesRelatedOwnerByActivityID($value['id']));					
					$output[$key]['related_contact'] = implode(", ", $this->getActivitiesRelatedContactByActivityID($value['id']));
					$output[$key]['activity_date_format'] = convertToDate($output[$key]['activity_date'])." ".convertToDate($output[$key]['activity_time'],'H:i');
					if($output[$key]['type']== 'Project'){
						$output[$key]['source_name'] = $objProject->getProjectNameById($output[$key]['type_id']);
						$temp_proj_cust = $objProject->getCustIDByProjId($output[$key]['type_id']);
						$output[$key]['source_customer'] = $objCustomer->getCustomerNameById($temp_proj_cust);				
					} else if($output[$key]['type']== 'Contact'){
						$output[$key]['source_name'] = $objContact->getContactFullNameById($output[$key]['type_id']);
						$temp_contact_cust = $objContact->getContactCustomerById($output[$key]['type_id']);
						$output[$key]['source_customer'] = $objCustomer->getCustomerNameById($temp_contact_cust);
					} else if($output[$key]['type']== 'Customer'){
						$output[$key]['source_name'] = $objCustomer->getCustomerNameById($output[$key]['type_id']);
						$output[$key]['source_customer'] = $output[$key]['source_name'];
						
					}
				}
			}
			return $output;
		}
		//***Report Activity by Customer - End  ***//
		
		//***Report Activity by Customer Business Type- Start ***//
		function listActivitiesByCustomerBusinessType($condition, $encrypt = true, $format = true){
			require_once DIR_MODULE.'/contacts/contact.class.php';
			require_once DIR_MODULE.'/customer/customer.class.php';
			require_once DIR_MODULE.'/project_management/projects/project.class.php';
			require_once DIR_MODULE.'/oz.system/settings/activitity_management/activity_type/activity_type.class.php';
			require_once DIR_MODULE.'/oz.system/settings/activitity_management/communication_type/communication_type.class.php';	
			require_once DIR_MODULE.'/salesperson/salesperson.class.php';		
			global $db;
			$objCustomer = new Customer($GLOBALS['myDB']);
			$objContact = new Contact($GLOBALS['myDB']);
			$objProject = new Project($GLOBALS['myDB']);
			$objActivityType = new ActivityType($GLOBALS['myDB']);
			$objCommunicationType = new CommunicationType($GLOBALS['myDB']);
			$objSalesPerson = new SalesPerson($GLOBALS['myDB']);
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
						
			$join = "LEFT JOIN `activity_type` ON `activities`.`activity_type_id` = `activity_type`.`id` ";
			$join .= "LEFT JOIN `communication_type` ON `activities`.`communication_type_id` = `communication_type`.`id` ";
			$join .= "LEFT JOIN `salesperson` ON `activities`.`activity_owner_id` = `salesperson`.`id` ";
			$this->totalRow = $this->db->countJoinRow('`activities`.`id`', 'activities', $join, $condition);
			$sql = "SELECT `activities`.*, `activity_type`.`type` AS `activity_type`, `communication_type`.`type` AS `communication_type`, `salesperson`.`name` AS `activity_owner` FROM `activities` ".$join." WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$temp = $result['activity_type_id'];
				array_push($output, $temp);
			}
			$output = array_count_values($output);
			return $output;
		}
		//***Report Activity by Customer Business Type- End  ***//
	}
?>