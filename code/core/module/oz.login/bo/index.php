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
		"center_dir" 		=> DIR_ACTIVE_THEME."/oz.login/login_screen.php",
		"right" 			=> "0",
		"right_dir" 		=> "",
		"footer" 			=> "1",
		"footer_dir" 		=> DIR_ACTIVE_THEME."/footer.php",
		"widgets" 			=> "0",
		"current" 			=> "",
		"load_tile" 		=> "1",
		"load_breadcrumb" 	=> "1"
	);
	$actionData = $GLOBALS['seo']->getActionURL();
	$action		= array_shift($actionData);
	$error 		= array('type' => 'error', 'title' => 'Error', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$warning 	= array('type' => 'warning', 'title' => 'Warning', 'content' => '', 'position' => 'right', 'autoclose' => false);	
	$message 	= array('type' => 'message', 'title' => 'Message', 'content' => '', 'position' => 'right', 'autoclose' => false);	
	$markError 	= array();
	$HTTP_AJAX 	= HTTP_ACTIVE_MODULE.'/ajax';
	switch(MODULE_UID){
		default:
			$username = getCookieValue('remember');
			$rememberOn = false;
			if($username != ""){
				$rememberOn = true;
			}
			$password = "";
			if(matchCookieSession()){
				if($_SESSION['login']['mode'] == "fo"){
					header("Location: ".HTTP_SERVER.HTTP_ROOT);
					exit;
				}else{
					if(!empty($_GET)){
						$getAction = checkParam('action');
						if($getAction == 'logout'){
							if($objUser->logout()){
								$message['title'] = $setting['title'];
								$message['content'] = "You have successfully logout.";
								$message['autoclose'] = true;
								break;
							}
						} else if($getAction == 'relogin'){
							if($objUser->logout()){
								$message['title'] = $setting['title'];
								$message['content'] = "You have successfully changed your password. Please login again with the new password you have set.";
								$message['autoclose'] = true;
								break;
							}
						}
					}
					header("Location: ".HTTP_SERVER.HTTP_ROOT.'/dashboard');
					exit;
				}
			} 
			if(!empty($_GET)){
				$getAction = checkParam('action');
				if($getAction == 'reset'){							
					$message['title'] = $setting['title'];
					$message['content'] = "You have successfully changed your password. Please login again with the new password you have set.";
					$message['autoclose'] = true;
					break;							
				} else if ($getAction == 'sendmail'){		
					$message['title'] = $setting['title'];
					$message['content'] = "An email is successfully sent to your email address. Please check your mailbox for further process.";
					$message['autoclose'] = true;
					break;							
				}  else if($getAction == 'expired'){					
					//$message['title'] = $setting['title'];
					$warning['title'] = "Session Expired";
					$warning['content'] = "You have been logged out. Your session may have expired or you may have logged in via another browser/location.";
					$warning['autoclose'] = true;
					break;					
				}				
			}
			if(!empty($_POST)){
				$username = checkParam('username');
				$password = checkParam('password');
				$remember = checkParam('remember');
				if($username == ""){
					$error['content'] = 'Username cannot be empty.';
					array_push($markError, 'username');
					break;
				}
				$objUser->autoClearLoginAttempt();
				$credentialData = $objUser->getLoginCredential($username);
				$attemptData = $objUser->getLoginAttempt($_SERVER['REMOTE_ADDR']);
				if(!empty($attemptData) && $attemptData['attempt'] > $GLOBALS['siteSetting']['max_login_attempt']){
					$error['content'] = "Your IP is lockdown for ".$GLOBALS['siteSetting']['max_login_lockdown']." minutes because you have input invalid login details for more than ".$GLOBALS['siteSetting']['max_login_attempt']." times.";
					break;
				}else{
					if(empty($credentialData)){
						$objUser->createLoginAttempt($attemptData);
						$error['content'] = 'Invalid login details.';
						array_push($markError, 'username', 'password');
						break;
					}else{
						if($credentialData['status'] == '0'){
							$error['content'] = "Your account has been blocked.";
							array_push($markError, 'username');
							break;
						}else{
							$hashPassword = hashPassword(strtolower($username), $password, $credentialData['salt']);
							if($credentialData['password'] == $hashPassword && ($credentialData['access'] == 'both' || $credentialData['access'] == 'bo')){
								if($objUser->createLoginSession($username)){
									$objUser->createLoginCookies($remember, $username);
								}
								$objUser->deleteLoginAttempt($_SERVER['REMOTE_ADDR']);
								$trails = array();
								$trails['session'] = session_id();
								$trails['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
								$trails['ip_address'] = $_SERVER['REMOTE_ADDR'];
								insertAuditTrails('', 'login', json_encode($trails));

								if($remember == "on"){
									setCookieValue($userData['username'], 'remember');
								}else{
									setCookieValue("", 'remember');
								}
								if(isset($_GET['return'])){
									header("Location: ".HTTP_SERVER.HTTP_ROOT.$_GET['return']);
								}else{
									header("Location: ".HTTP_SERVER.HTTP_ROOT."/dashboard");
								}
								exit;
							}else{
								$objUser->createLoginAttempt($attemptData);
								$error['content'] = 'Invalid login details.';
								array_push($markError, 'username', 'password');
								break;
							}
						}
					}
				}
			}
		break;
	}
	require DIR_ACTIVE_THEME."/oz.login/login.php";
	//require DIR_ACTIVE_THEME.'/site_builder.php';
?>