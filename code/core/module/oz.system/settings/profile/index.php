<?php
	require DIR_LIBS.'/user.class.php';
	$objUser = new User($GLOBALS['myDB']);
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	$setting = array(
		"title" => SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" => "",
		"meta_description" => "",
		"extjs" => "1",
		"header" => "1",
		"header_dir" => DIR_ACTIVE_THEME."/header.php",
		"left" => "1",
		"left_dir" => DIR_ACTIVE_THEME."/leftbar.php",
		"left_width" => "180",
		"left_maximize" => getCookieValue('toggle_leftbar'),
		"left_uid" => '',
		"left_module" => MODULE_NAME,
		"center_dir" => DIR_ACTIVE_THEME."/oz.system/system.php",
		"right" => "0",
		"right_dir" => "",
		"footer" => "1",
		"footer_dir" => DIR_ACTIVE_THEME."/footer.php",
		"widgets" => "0",
		"current" => "",
		"load_tile" => "1",
		"load_breadcrumb" => "1"
	);
	$access 	= checkAccess(MODULE_UID);
	$actionData = $GLOBALS['seo']->getActionURL();
	$action 	= array_shift($actionData);
	$error 		= array('type' => 'error', 'title' => 'Error', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$warning 	= array('type' => 'warning', 'title' => 'Warning', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$message 	= array('type' => 'message', 'title' => 'Message', 'content' => '', 'position' => 'right', 'autoclose' => false);	
	$markError 	= array();
	$HTTP_AJAX 	= HTTP_ACTIVE_MODULE.'/ajax';
	switch(MODULE_UID){		
		case 'oz.system.settings.profile.view':
		case 'oz.system.settings.profile.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = getUserSpecificField($decryptKey, "`username`");
						if(!empty($data)){
							$message['content'] = ucfirst($data['username'])." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			$encryptKey = $_SESSION['enc_user_id'];
			$decryptKey = $_SESSION['user_id'];
			$userData = $objUser->getUserData($decryptKey);
			$userGroupData = getUserGroupByUserId($userData['id']);
			if(empty($userData)){
				header("Location: ".getModuleURL('oz.system.settings.profile')."?invalid=2");
				exit;
			}
			
			if(MODULE_UID == 'oz.system.settings.profile.view'){
				$mode = "view";
				$allowEdit = checkAccess('oz.system.settings.profile.edit');
			}else{
				$mode = "edit";
				$allowEdit = true;
			}
			if($decryptKey == '1' || $decryptKey == '-1'){
				$allowDelete = false;
			}else{
				$allowDelete = false;
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($userData["first_name"])&&isset($userData["last_name"])?": ".$userData["first_name"]." ".$userData["last_name"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/profile/view_user.php";
			if($allowEdit){
				$formEmail = $userData['email'];
				$formFName = $userData['first_name'];
				$formLName = $userData['last_name'];
				$formStatus = $userData['status'];
				$formAccess = $userData['access'];
				
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formEmail = checkParam('email');
					$formFName = checkParam('first_name');
					$formLName = checkParam('last_name');
					
					if(!validateEmptyField($formEmail, 'email', $error)){break;}
					if(!filter_var($formEmail, FILTER_VALIDATE_EMAIL)){
						$error['content'] = "Invalid email address. Please input correct email address.";
						break;
					}else if($objUser->checkEmailExist($formEmail, $userData['id'])){
						$error['content'] = "Email address already exist. Please input another email address.";
						break;
					}
					if(!validateEmptyField($formFName, 'first name', $error)){break;}
					if(!validateEmptyField($formLName, 'last name', $error)){break;}

					$newData = array();
					$newData['id'] = $userData['id'];
					$newData['first_name'] = $formFName;
					$newData['last_name'] = $formLName;
					$newData['email'] = $formEmail;
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objUser->updateUser($newData)){
						insertAuditTrails('oz.system.user_management.users.edit', 'update', "", $userData, $newData);
						$encInsertedId = encryption($userData['id'], $_SESSION['salt'], true);
						setCookieValue($encInsertedId, 'added_key');
						header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encryptKey)."&redir=update");
						exit;
					}
				}
			}
		break;		
		case 'oz.system.settings.profile.changepassword': 
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'changepassword'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = getUserSpecificField($decryptKey, "`username`");
						if(!empty($data)){
							$message['content'] = ucfirst($data['username'])." password has been changed successfully.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			$encryptKey = $_SESSION['enc_user_id'];
			$decryptKey = $_SESSION['user_id'];
			// if($decryptKey == ''){
				// header("Location: ".getModuleURL('oz.system.settings.profile')."?invalid=1");
				// exit;
			// }else{
				// $decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			// }
			$userData = $objUser->getUserData($decryptKey);
			
			if(empty($userData)){
				header("Location: ".getModuleURL('oz.system.settings.profile')."?invalid=2");
				exit;
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/profile/change_password.php";
			
			$formUsername = $userData['username'];
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formCurrentPassword = checkParam('current_password');
				$formNewPassword = checkParam('password');
				$formRetypePassword = checkParam('retype_password');				
				$encCurrentPassword = hashPassword($formUsername, $formCurrentPassword, $userData['salt']);
				if(!validateEmptyField($formCurrentPassword, 'current password', $error)){break;}
				if(!validateEmptyField($formNewPassword, 'new password', $error)){break;}
				if(!validateEmptyField($formRetypePassword, 'retype password', $error)){break;}
				if($formNewPassword != $formRetypePassword){
					$error['content'] = 'New Password does not match with retype password. Please input match password.';
					$error['autoclose'] = false;
					break;
				}			
				if($encCurrentPassword == $userData['password']){
					$newData 					= array();
					$newData['id']		 		= $userData['id'];
					$newData['username'] 		= strtolower($formUsername);
					$newData['password'] 		= hashPassword($newData['username'], $formNewPassword, $userData['salt']);				
					$newData['modified_by'] 	= $_SESSION['user_id'];
					$newData['modified_date'] 	= date("Y-m-d H:i:s");
						
					if($objUser->updateUser($newData)){
						insertAuditTrails('oz.system.settings.profile.changepassword', 'changepassword', "", $userData, $newData);					
						$encInsertedId = encryption($userData['id'], $_SESSION['salt'], true);
						setCookieValue($encInsertedId, 'added_key');					
						header("Location: ".HTTP_SERVER.HTTP_ROOT.SITE_BO_LOGIN."?action=relogin");
						exit;
					}
				} else {
					$error['content'] = 'Current Password is incorrect. Please input correct the current password.';
					$error['autoclose'] = false;
					break;
				}
			}
		break;
		default:
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case 'check_duplicate_user':
						$username = checkParam('username');
						if($objUser->checkUsernameExist($username)){
							$output['message'] = "Username already exist. Please input another username.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'check_duplicate_email':
						$email = checkParam('email');
						$userId = encryption(rawurldecode(checkParam('user_id')), $_SESSION['salt'], false);
						if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
							$output['message'] = "Invalid email address. Please input correct email address.";
						}else if($objUser->checkEmailExist($email, $userId)){
							$output['message'] = "Email address already exist. Please input another email address.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'check_duplicate_usergroup':
						$groupName = checkParam('val');
						$groupId = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objUser->checkUserGroupNameExist($groupName, $groupId)){
							$output['message'] = "Group name already exist. Please input another group name.";
						}else{
							$output['success'] = true;
						}
					break;
				}
				echo json_encode($output);
				exit;
			}
			insertTracker(MODULE_NAME);
		break;
	}
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>