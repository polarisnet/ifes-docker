<?php
	class User{
		var $db;
		var $totalRow;
		
		function User($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		/** Security Question - Start **/
		function listSecurityQuestion($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_security_question', $condition);
			$sql = "SELECT * FROM `sys_security_question` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){$result['enc_id'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);
				}
			}
			return $output;
		}
		
		function getSecurityQuestion($id){
			$output = array();
			$sql = "SELECT * FROM `sys_security_question` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		/** Security Question - Last **/
		
		/** User Group - Start **/
		function listUserGroupField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_usergroups` ";
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
		
		function checkUserGroupNameExist($groupName, $groupId = ""){
			$sql = "SELECT * FROM `sys_usergroups` WHERE LOWER(`group_name`)='".strtolower($groupName)."'";
			if($groupId != ""){
				$sql .= " AND `id` != '".$groupId."'";
			}
			$sql .= " LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getUserGroupNameById($id){
			$output = "";
			$sql = "SELECT `group_name` FROM `sys_usergroups` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['group_name'];
			}
			return $output;
		}
		
		function listUserGroup($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_usergroups', $condition);
			$sql = "SELECT * FROM `sys_usergroups` WHERE 1=1 ";
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
		
		function checkUserGroupExist($id){
			$sql = "SELECT * FROM `sys_usergroups` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getUserGroupData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_usergroups` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}

		function getUserGroupIdByUserId($userId){
			$output = "";
			$sql = "SELECT `group_id` FROM `sys_users_groups` WHERE `user_id`='".$userId."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['group_id'];
			}
			return $output;
		}
		
		function reassignDeleteUserGroup($id){
			$joinId = array();
			$sql = "UPDATE `sys_users_groups` SET `group_id`='2' WHERE `group_id`='".$id."'";
			$this->db->query($sql);
		}
		
		function deleteUserGroup($id){
			if($this->db->delete("sys_usergroups", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveUserGroup($data){
			if($this->db->insert("sys_usergroups", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateUserGroup($data){
			if($this->db->update("sys_usergroups", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		function deleteUserUsergroupByUserId($id){
			if($this->db->delete("sys_users_groups", "`user_id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveUserUsergroup($data){
			if($this->db->insert("sys_users_groups", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateUserUsergroup($data){
			if($this->db->update("sys_users_groups", $data, "`user_id`='".$data['user_id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		/** User Group - End **/
		
		/** User - Start **/
		function getUserCombo($condition, $start, $limit, $none = false){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_users', $condition);
			$sql = "SELECT * FROM `sys_users` WHERE 1=1 ".$condition." ORDER BY `username` ASC LIMIT ".$start.", ".$limit."";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result_data = $this->db->getRecord();
				$temp = array();
				$temp['id'] = rawurlencode(encryption($result_data['id'], $salt, true));
				$temp['username'] = $result_data['username'];
				$temp['name'] = $result_data['first_name'].' '.$result_data['last_name'];
				array_push($output, $temp);
			}
			
			if($none){
				$temp = array();
				$temp['id'] = rawurlencode(encryption('0', $salt, true));
				$temp['username'] = '-';
				$temp['name'] = '-';
				array_push($output, $temp);
			}
			$this->totalRow++;
			return $output;
		}
		
		function listUserField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_users` WHERE `Field` NOT IN ('password', 'sec_question', 'sec_id', 'sec_answer', 'salt')";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			array_push($output, "'profile_pic'");
			return $output;
		}
		
		function listUser($condition = '', $start = 0, $limit = 0, $selection = "*", $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_users', $condition);
			$sql = "SELECT ".$selection." FROM `sys_users` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$result['profile_pic'] = getDefaultPicture($result['uid']);
				if($encrypt){$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); $result['raw'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key]);
				}
			}
			return $output;
		}
		
		function getUserData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_users` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function deleteUser($id){
			if($this->db->delete("sys_users", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveUser($data){
			if($this->db->insert("sys_users", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateUser($data){
			if($this->db->update("sys_users", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		function getUsernameById($id){
			$output = "";
			$sql = "SELECT `username` FROM `sys_users` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['username'];
			}
			return $output;
		}
		
		function getFullnameById($id){
			$output = "";
			$sql = "SELECT `first_name`, `last_name`, `username` FROM `sys_users` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['first_name'].' '.$result['last_name'].'('.$result['username'].')';
			}
			return $output;
		}
		
		function checkUserExist($id){
			$sql = "SELECT * FROM `sys_users` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkUsernameExist($username){
			$sql = "SELECT * FROM `sys_users` WHERE LOWER(`username`)='".strtolower($username)."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkEmailExist($email, $userId = ""){
			$sql = "SELECT * FROM `sys_users` WHERE LOWER(`email`)='".strtolower($email)."'";
			if($userId != ""){
				$sql .= " AND `id` != '".$userId."'";
			}
			$sql .= " LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}		
		
		function checkUIDExist($uid){
			$sql = "SELECT * FROM `sys_users` WHERE `uid`='".$uid."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function generateUID(){
			$uid = strtolower(generateSalt('30'));
			while($this->checkUIDExist($uid)){
				$uid = strtolower(generateSalt('30'));
			}
			return $uid;
		}
		
		function createFolderUID($uid){
			$path = DIR_MEDIA."/user-image/".$uid;
			return mkdir($path, 0755);
		}
		
		function removeFolderUID($uid){
			$path = DIR_MEDIA."/user-image/".$uid;
			if(file_exists($path)){
				rrmdir($path);
			}
		}
		/** User - End **/
		
		/** Privileges - Start **/
		function checkPrivilegesField($field){
			$sql = "SHOW COLUMNS FROM `sys_privileges` LIKE '".$field."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function newFieldPrivileges($field){
			$sql = "ALTER TABLE `sys_privileges` ADD `".$field."` tinyint(1) NOT NULL DEFAULT '1' AFTER `module_uid` ";
			$this->db->query($sql);
		}
		
		function dropFieldPrivileges($field){
			$sql = "ALTER TABLE `sys_privileges` DROP `".$field."` ";
			$this->db->query($sql);
		}
		
		function getModulePrivileges($usergroup, $submodule = ""){
			$output = array();
			$sql = "SELECT `sys_module`.`uid`, `sys_privileges`.`".$usergroup."`, `sys_module`.`module_display` FROM `sys_privileges` LEFT JOIN `sys_module` ON `sys_privileges`.`module_uid` = `sys_module`.`uid` WHERE `sys_module`.`status` = '1' AND `sys_module`.`header`='1' AND `sys_module`.`parent_uid` = '".$submodule."' ";
			if(isset($_SESSION['login']['mode'])){
				if($_SESSION['login']['mode'] != 'both'){
					$sql .= " AND `sys_module`.`secure_mode`='".$_SESSION['login']['mode']."'";
				}
			}else{
				$sql .= " AND `sys_module`.`secure_mode`='none'";
			}
			if($GLOBALS["siteSetting"]["emas"] == "0") { //!(defined("ENABLED_EMAS_SYNC_EXPORT") && constant("ENABLED_EMAS_SYNC_EXPORT")=="YES")
				$sql .= " AND `sys_module`.`uid`!='oz.system.settings.emas_sync'";
			}
			$sql .= " AND (`sys_module`.`module_level` = '1' OR `sys_module`.`module_level` = '2') ORDER BY `sys_module`.`item_order` ASC ";
			$this->db->query($sql); 
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, $result);
			}
			foreach($output AS $key => $value){
				$output[$key]['name'] = str_replace('.', '_', $value['uid']);
				$output[$key]['child'] = $this->getModulePrivileges($usergroup, $value['uid']);				
			}
			return $output;
		}
		
		function getSubModulebyUID($submodule){
			$output = array();
			$sql = "SELECT `uid` FROM `sys_module` WHERE `status` = '1' AND `parent_uid` = '".$submodule."' ";
			$this->db->query($sql); 
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, $result['uid']);
			}	
			foreach($output AS $key => $value){
				$checkChildModule = $this->getSubModulebyUID($value);		
				if(!empty($checkChildModule)){
					foreach($checkChildModule AS $childModule){
						array_push($output, $childModule);
					}
				}
			}	
			return $output;
		}
		
		function getMainModulebyUID($submodule){
			$output = array();
			$sql = "SELECT `parent_uid` FROM `sys_module` WHERE `status` = '1' AND `uid` = '".$submodule."' AND `header`='1' AND `parent_uid` <> '' ";
			$this->db->query($sql); 
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, $result['parent_uid']);
			}
			foreach($output AS $key => $value){
				$checkChildModule = $this->getMainModulebyUID($value);		
				if(!empty($checkChildModule)){
					foreach($checkChildModule AS $childModule){
						array_push($output, $childModule);
					}
				}
			}	
			return $output;
		}
		
		function getAccessByUID($usergroup, $moduleUID){
			$output = "";
			$sql = "SELECT `".$usergroup."` FROM `sys_privileges` WHERE `module_uid`='".$moduleUID."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result[$usergroup];
			}
			return $output;
		}
		
		function savePrivileges($data){
			if($this->db->insert("sys_privileges", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updatePrivileges($data){
			if($this->db->update("sys_privileges", $data, "`module_uid`='".$data['module_uid']."'")){
				return true;
			}else{
				return false;
			}
		}	
		/** Privileges - End **/
		
		/** Patcher - Start **/
		function patchLanguage($langId){
			return 'English';
		}
		
		function patchSecQuestion($secId){
			$output = "";
			$sql = "SELECT * FROM `sys_security_question` WHERE `id`='".$secId."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['question'];
			}
			return $output;
		}
		/** Patcher - End **/
		
		/** Login - Start **/
		function getLoginCredential($username){
			$output = array();
			$sql = "SELECT `password`, `salt`, `access`, `status` FROM `sys_users` WHERE LOWER(`username`)='".strtolower($username)."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function getLoginAttempt($ipAddress){
			$output = array();
			$sql = "SELECT * FROM `sys_login_attempt` WHERE `ip_address`='".$ipAddress."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function autoClearLoginAttempt(){
			$date = date("Y-m-d H:i:s", time() - ($GLOBALS['siteSetting']['max_login_lockdown']*60));
			if($this->db->delete("sys_login_attempt", "`created_date`<='".$date."'")){
				return true;
			}else{
				return false;
			}
		}
		function deleteLoginAttempt($ip){
			if($this->db->delete("sys_login_attempt", "`ip_address`='".$ip."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveLoginAttempt($data){
			if($this->db->insert("sys_login_attempt", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateLoginAttempt($data){
			if($this->db->update("sys_login_attempt", $data, "`ip_address`='".$data['ip_address']."'")){
				return true;
			}else{
				return false;
			}
		}	
		
		function createLoginAttempt($attemptData = array()){
			if(empty($attemptData)){
				$attemptData = $this->getLoginAttempt($_SERVER['REMOTE_ADDR']);
			}
			$data = array();
			$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
			$data['created_date'] = date("Y-m-d H:i:s");
			if(!empty($attemptData)){
				$data['attempt'] = $attemptData['attempt'] + 1;
				$this->updateLoginAttempt($data);
			}else{
				$data['attempt'] = '1';
				$this->saveLoginAttempt($data);
			}
		}
		
		function createLoginSession($username){
			// Create session
			$sql = "SELECT * FROM `sys_users` WHERE `username` = '".$username."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
				$_SESSION['login']['mode'] = $output['access'];
				$_SESSION['user_id'] = $output['id'];				
				$_SESSION['salt'] = $output['salt'];
				$_SESSION['username'] = $output['username'];
				$_SESSION['user_fname'] = $output['first_name'];
				$_SESSION['user_lname'] = $output['last_name'];
				$_SESSION['user_fullname'] = $output['first_name'];
				if($_SESSION['user_lname'] != ''){$_SESSION['user_fullname'] .= ' '.$_SESSION['user_lname'];}
				$_SESSION['email'] = $output['email'];
				$_SESSION['uid'] = $output['uid'];
				$_SESSION['login']['token'] = generateSalt(12);
				$_SESSION['dynamic_salt'] = generateSalt(12);
				$_SESSION['enc_user_id'] =  encryption($output['id'], $_SESSION['salt'], true);
			}
			
			$sql = "SELECT `sys_usergroups`.* FROM `sys_usergroups`, `sys_users_groups` WHERE `sys_users_groups`.`user_id` = '".$_SESSION['user_id']."' AND  `sys_users_groups`.`group_id`=`sys_usergroups`.`id` LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
				$_SESSION['group_id'] = $output['id'];
				$_SESSION['group_name'] = $output['group_name'];
			}
			
			$this->db->delete("sys_session", "`user_id`='".$_SESSION['user_id']."'");
			
			// Save session details to db
			$data['session'] = session_id();
			$data['user_id'] = $_SESSION['user_id'];
			$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
			$data['created_date'] = date("Y-m-d H:i:s");
			
			if($this->db->insert("sys_session", $data)){
				$this->db->delete("sys_event_tracker", "`session`='".session_id()."'");
				return true;
			}else{
				return false;
			}
		}
		
		function createLoginCookies($remember, $username){
			$siteName = str_replace(" ", "", COOKIE_NAME);
			if($remember == "on" || $remember == "1"){
				$set_time = time() + (7*24*3600);
				setcookie($siteName."_REMEMBER_ME", $username, 0, "/", "", false, true);
			}else{
				$set_time = 0;
				setcookie($siteName."_REMEMBER_ME", "", time()-9600, "/", "", false, true);
			}
			setcookie($siteName."_SESSION[id]", session_id(), $set_time, "/", "", false, true);
			setcookie($siteName."_SESSION[token]", $_SESSION['login']['token'], $set_time, "/", "", false, true);
			setcookie($siteName."_SESSION[toggle_leftbar]", 1, $set_time, "/", "", false, false);
			setcookie($siteName."_SESSION[toggle_leftbar_initial]", 1, $set_time, "/", "", false, false);
		}
		
		function logout(){
			if(matchCookieSession()){
				$trails = array();
				$trails['session'] = session_id();
				insertAuditTrails('', 'logout', json_encode($trails));
				
				$sessionID = session_id();			
				$siteName = str_replace(" ", "", COOKIE_NAME);
				deleteAllCookies();
				session_destroy(); // delete session
				$this->db->delete("sys_session", "`session`='".$sessionID ."'");
				$this->db->delete("sys_event_tracker", "`session`='".$sessionID ."'");
				return true;
			}else{
				return false;
			}
		}
		/** Login - End **/
		
		/** Change & Recover Password - Start **/
		function getRecoverByUsername($username){
			$output = array();
			$sql = "SELECT `id`, `salt`, `status`, `email`, `sec_id`, `sec_question`, `sec_answer` FROM `sys_users` WHERE `username`='".$username."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
				
		function checkUserStatusByEmail($email){
			$output = array();
			$sql = "SELECT `status`, `username` FROM `sys_users` WHERE LOWER(`email`)='".strtolower($email)."'";			
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['status'];
				$output = $result['username'];
			}
			return $output;
		}
		
		function getUserDataByEmail($recoveremail){
			$output = array();
			$sql = "SELECT * FROM `sys_users` WHERE LOWER(`email`)='".strtolower($recoveremail)."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function clearExpireDateReset(){
			if($this->db->delete("sys_users_reset", "`created_date`<'".date('Y-m-d', strtotime(' -1 day'))."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function checkResetExist($reset){
			$sql = "SELECT `id` FROM `sys_users_reset` WHERE `reset_token` LIKE BINARY '".$reset."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}		
		
		function saveUserReset($data){
			if($this->db->insert("sys_users_reset", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function sendMailToRecover($recoveremail){
			$output = false;
			$userData = $this->getUserDataByEmail($recoveremail);
			$data['reset_token'] = generateSalt('40');
			while($this->checkResetExist($data['reset_token'] )){
				$data['reset_token']  = generateSalt('30');
			}
			$data['user_id'] = $userData['id'];
			$data['created_date'] = date("Y-m-d H:i:s");
			$this->saveUserReset($data);
			
			if(!empty($userData)){				
				require_once DIR_LIBS.'/mailer.class.php';
				$objMailer = new Mailer($GLOBALS['myDB']);
				$settingData = $objMailer->getSettingsData('1');
				if(!empty($settingData)){
					$templateData = $objMailer->getTemplateDataByCode('oz.reset.bo');
					if(!empty($templateData)){
						$mail = $objMailer->constructTemplateHeader($settingData, $templateData);
						$mail->AddAddress($userData['email']);
						$mail->Subject = $templateData['subject'];
						$content = $templateData['content'];
						$content = str_replace("[target_username]",  $userData['username'], $content);
						$content = str_replace("[target_name]", ucfirst($userData['first_name']).' '.ucfirst($userData['last_name']), $content);
						$content = str_replace("[target_mail]", $userData['email'], $content);
						$content = str_replace("[reset_date]", date("Y-m-d H:i:s"), $content);
						$content = str_replace("[reset_link]", getModuleURL('oz.reset.bo').'?token='.$data['reset_token'], $content);						
						$mail->Body = $content;
						$mail->IsHTML(true);
						try{
							$mail->Send();
							$output = true;
						}catch (phpmailerException $e){
							errorHandler('MAILER', $e->getMessage(), 'mailer.class.php', '-', '-');
						}catch (Exception $e) {
							errorHandler('MAILER', $e->getMessage(), 'mailer.class.php', '-', '-');
						}
					}
				}				
			}
			return $output;
		}
		
		function getTokenToRecover($username){
			$output = false;
			$userData = $this->getRecoverByUsername($username);
			$data['reset_token'] = generateSalt('40');
			while($this->checkResetExist($data['reset_token'] )){
				$data['reset_token']  = generateSalt('30');
			}
			$data['user_id'] = $userData['id'];
			$data['created_date'] = date("Y-m-d H:i:s");
			$this->saveUserReset($data);
			$output = $data['reset_token'];
			return $output;
		}
		
		function getUserIdByResetKey($resetKey){
			$output = "";
			$sql = "SELECT `user_id` FROM `sys_users_reset` WHERE `reset_token` LIKE BINARY '".$resetKey."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['user_id'];
			}			
			return $output;
		}
		/** Change & Recover Password - End **/
		
		/** Record Permission - Start **/
		function listRecordPermissionField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `sys_privileges_records` ";
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
		
		function listRecordPermission($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){	
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_privileges_records', $condition);
			$sql = "SELECT * FROM `sys_privileges_records` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			if (!$this->query_id = $this->db->query($sql)){
				$this->displayErrors();
				exit;
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
		
		function getModuleRecordPermissionCombo($condition = ''){
			$output = array();
			$sql = "SELECT `uid`, `module_display` FROM `sys_module` WHERE `status` = '1' AND `record_permission` = '1' ";
			if($condition != ""){
				$sql .= $condition;
			}
			$this->db->query($sql); 
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, $result);
			}
			return $output;
		}
		
		function getModuleFieldCombo($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			$sql = "SELECT `id`, `field` FROM `sys_privileges_fields` WHERE `status` = '1' AND `access` = '0' "; 
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			if (!$this->query_id = $this->db->query($sql)){
				$this->displayErrors();
				exit;
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
		
		function checkRecordPermissionExist($id){
			$sql = "SELECT * FROM `sys_privileges_records` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getRecordPermissionData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_privileges_records` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function deleteRecordPermission($id){
			if($this->db->delete("sys_privileges_records", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveRecordPermission($data){
			if($this->db->insert("sys_privileges_records", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateRecordPermission($data){
			if($this->db->update("sys_privileges_records", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}	
		/** Record Permission - End **/
	}
?>