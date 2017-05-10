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
		"left" 				=> "0",
		"left_dir" 			=> DIR_ACTIVE_THEME."/oz.login/login_bo_left.php",
		"left_width" 		=> "180",
		"left_maximize" 	=> getCookieValue('toggle_leftbar'),
		"left_uid" 			=> '',
		"left_module" 		=> MODULE_NAME,
		"center_dir" 		=> DIR_ACTIVE_THEME."/oz.lostpass/lostpass_screen.php",
		"right" 			=> "0",
		"right_dir" 		=> "",
		"footer"	 		=> "1",
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
			if(!empty($_POST)){
				switch($_POST['mode']){
					case 'lostpass':
						$preoption = checkParam('preoption');
						if(empty($preoption)){
							$error['content'] = "Please select one of the following options.";
							array_push($markError, 'preoption');
							break;
						}else{					
							if($preoption == '1'){								
								$recoveremail = checkParam('recoveremail');								
								if(empty($recoveremail)){
									$error['content'] = "Please enter a valid email address.";
									array_push($markError, 'recoveremail');
									break;
								}
								if(!validateEmptyField($recoveremail, 'email', $error)){break;}
								if(!filter_var($recoveremail, FILTER_VALIDATE_EMAIL)){
									$error['content'] = "Invalid email address. Please input correct email address.";
									break;
								}
								if($objUser->checkEmailExist($recoveremail)){
									$userstatus = $objUser->checkUserStatusByEmail($recoveremail);
									if($userstatus == '0'){
										$error['content'] = "Your account has been blocked.";
										array_push($markError, 'UserStatus');
										break;
									} else {
										$objUser->sendMailToRecover($recoveremail);
										insertAuditTrails('losspass.sendmail', 'sendmail', "", $recoveremail);
										header("Location: ".HTTP_SERVER.HTTP_ROOT.SITE_BO_LOGIN."?action=sendmail");								
										exit;
									}
								} else{
									$error['content'] = "Email that you wish to recover password no longer exists. Please try again!";
									break;								
								}
							} else {								
								$recoverusername = checkParam('recoverusername');								
								if(empty($recoverusername)){
									$error['content'] = "Username cannot be empty.";
									array_push($markError, 'recoverusername');
									break;
								}
								if($objUser->checkUsernameExist($recoverusername)){
									$userRecover = $objUser->getRecoverByUsername($recoverusername);									
									$encInsertedId = encryption($userRecover['id'], $userRecover['salt'], true);
									if($userRecover['status'] == '0'){
										$error['content'] = "Your account has been blocked.";
										array_push($markError, 'UserRecover');
										break;
									} 
									$userSecQuestion = $objUser->getSecurityQuestion($userRecover['sec_id']);							
									$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.lostpass/recovery_screen.php";		
								} else{
									$error['content'] = "Username that you wish to recover password no longer exists. Please try again!";
									break;								
								}
							}		
						}	
					break;
					case 'secquestion':
						$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';						
						$setting['load_tile'] = "0";
						$setting['left_uid'] = MODULE_PARENT_UID;
						$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.lostpass/recovery_screen.php";
							
						$secanswer = checkParam('secanswer');	
						$secQuestionID = checkParam('encSecQuestionID');
						$encUserName = checkParam('encUserName');
						$userRecover = $objUser->getRecoverByUsername($encUserName);
						$userSecQuestion = $objUser->getSecurityQuestion($userRecover['sec_id']);
						$recoverusername = $encUserName;						
						if(empty($secanswer)){
							$error['content'] = "Security Answer cannot be empty. Please try again!";
							array_push($markError, 'secanswer');
							break;
						}else{	
							$objUser->autoClearLoginAttempt();							
							$attemptData = $objUser->getLoginAttempt($_SERVER['REMOTE_ADDR']);
							if(!empty($attemptData) && $attemptData['attempt'] > $GLOBALS['siteSetting']['max_login_attempt']){
								$error['content'] = "Your IP is lockdown for ".$GLOBALS['siteSetting']['max_login_lockdown']." minutes because you have input incorrect secret answer for more than ".$GLOBALS['siteSetting']['max_login_attempt']." times.";
								break;
							}else{
								$enc_secanswer = encryption($secanswer, $userRecover['salt'], true);								
								if($enc_secanswer == $userRecover['sec_answer']){
									$resetToken = $objUser->getTokenToRecover($encUserName);
									insertAuditTrails('recover.gettoken', 'gettoken', "", $recoveremail);
									header("Location: ".getModuleURL('oz.reset.bo')."?token=".$resetToken);						
									exit;
								} else {
									$objUser->createLoginAttempt($attemptData);
									$error['content'] = "Secret answer is incorrect. Please try again!";
									$error['autoclose'] = false;
									break;
								}								
							}
						}
					break;
				}
			}
		break;
	}
	
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>