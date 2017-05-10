<?php
	require DIR_LIBS.'/user.class.php';
	$objUser = new User($GLOBALS['myDB']);
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	$setting = array(
		"title" 			=> SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" 		=> "",
		"meta_description" 	=> "",
		"extjs" 			=> "1",
		"header" 			=> "0",
		"header_dir" 		=> "",
		"left" 				=> "1",
		"left_dir" 			=> DIR_ACTIVE_THEME."/oz.login/login_bo_left.php",
		"left_width" 		=> "180",
		"left_maximize" 	=> getCookieValue('toggle_leftbar'),
		"left_uid" 			=> '',
		"left_module" 		=> MODULE_NAME,
		"center_dir" 		=> DIR_ACTIVE_THEME."/oz.reset/reset_center.php",
		"right" 			=> "0",
		"right_dir" 		=> "",
		"footer" 			=> "1",
		"footer_dir" 		=> DIR_ACTIVE_THEME."/footer.php",
		"widgets" 			=> "0",
		"current" 			=> "",
		"load_tile" 		=> "1",
		"load_breadcrumb" 	=> "1"
	);
	$actionData 	= $GLOBALS['seo']->getActionURL();
	$action 		= array_shift($actionData);
	$error 			= array('type' => 'error', 'title' => 'Error', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$warning 		= array('type' => 'warning', 'title' => 'Warning', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$message 		= array('type' => 'message', 'title' => 'Message', 'content' => '', 'position' => 'right', 'autoclose' => false);	
	$markError 		= array();
	$HTTP_AJAX 		= HTTP_ACTIVE_MODULE.'/ajax';
	$allowRecover 	= false;
	$secanswer 		= '';
	switch(MODULE_UID){
		default:
			$formEmail = "";
			$objUser->clearExpireDateReset();				
			if(!empty($_GET)){
				$resetKey = checkParam('token');
				if($resetKey != ""){
					if($objUser->checkResetExist($resetKey)){
						$mode = "reset";
					}else{
						$error = "Invalid Reset Key.";
					}
				}
			}
			if(!empty($_POST)){
				$resetKey = checkParam('token');
				$formNewPassword = checkParam('password');
				$formRetypePassword = checkParam('retype_password');				
				if(!validateEmptyField($formNewPassword, 'new password', $error)){break;}
				if(!validateEmptyField($formRetypePassword, 'retype password', $error)){break;}
				if($formNewPassword != $formRetypePassword){
					$error['content'] = 'New Password does not match with retype password. Please input match password.';
					$error['autoclose'] = false;
					break;
				}
				$userID = $objUser->getUserIdByResetKey($resetKey);
				$userData = $objUser->getUserData($userID);	
				if($userID != ""){
					$newData 					= array();
					$newData['id']				= $userData['id'];
					$newData['password']		= hashPassword($userData['username'], $formNewPassword, $userData['salt']);
					$newData['modified_by'] 	= $userData['id'];
					$newData['modified_date'] 	= date("Y-m-d H:i:s");
					if($objUser->updateUser($newData)){
						insertAuditTrails('oz.system.user_management.users.changepassword', 'changepassword', "", $userData, $newData);	
						header("Location: ".HTTP_SERVER.HTTP_ROOT.SITE_BO_LOGIN."?action=reset");
						exit;
					}
				}
			}
		break;
	}
	
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>