<?php 
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	require DIR_MODULE.'/site/donor/donor.class.php';
	require DIR_LIBS.'/user.class.php';
	
	$objDonor 			= new Donor($GLOBALS['myDB']);
	$objUser = new User($GLOBALS['myDB']);
	
	$setting = array(
		"title" 			=> SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" 		=> "",
		"meta_description" 	=> "",
		"extjs" 			=> "1",
		"header" 			=> "1",
		"header_dir"		=> DIR_ACTIVE_THEME."/header.php",
		"left" 				=> "1",
		"left_dir" 			=> DIR_ACTIVE_THEME."/leftbar.php",
		"left_width" 		=> "180",
		"left_maximize" 	=> getCookieValue('toggle_leftbar'),
		"left_uid" 			=> '',
		"left_module" 		=> MODULE_NAME,
		"center_dir" 		=> DIR_ACTIVE_THEME."/blank.php",
		"right" 			=> "0",
		"right_dir" 		=> "",
		"footer" 			=> "1",
		"footer_dir" 		=> DIR_ACTIVE_THEME."/footer.php",
		"widgets" 			=> "0",
		"current" 			=> "",
		"load_tile" 		=> "0",
		"load_breadcrumb" 	=> "0"
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
		case 'register':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = getUserSpecificField($decryptKey, "`username`");
						if(!empty($data)){
							$message['content'] = ucfirst($data['username'])." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			//insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_PUBLIC_THEME."/register/register.php";
			
			$formNameFirst = "";
			$formNameLast = "";
			$formNameSpouse = ""; 
			$formAddress1 = "";
			$formAddress2 = "";
			$formCity = "";
			$formState = "";
			$formZIP = "";
			$formCountry = "";
			$formTelephoneMobile = "";
			$formTelephoneDaytime = "";
			$formTelephoneEvening = "";
			$formEmail = "";
			
			$password_length = 8;
			$listCountries = $objDonor->listCountries();
			
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				
				$response = checkParam('g-recaptcha-response');
				$formPassword = checkParam('register-input-password-new');
				$formRetypePassword = checkParam('register-input-password-confirm');
					
				$formNameFirst = checkParam("register-input-firstname");
				$formNameLast = checkParam("register-input-lastname");
				$formNameSpouse = checkParam("register-input-spouse");
				$formAddress1 = checkParam("register-input-address1");
				$formAddress2 = checkParam("register-input-address2");
				$formCity = checkParam("register-input-city");
				$formState = checkParam("register-input-state");
				$formZIP = checkParam("register-input-zipcode");
				$formCountry = checkParam("register-input-country");
				$formTelephoneMobile = checkParam("register-input-mobile");
				$formTelephoneDaytime = checkParam("register-input-daytime");
				$formTelephoneEvening = checkParam("register-input-evening");
				$formEmail = checkParam("register-input-email");
				$formEmail = trim($formEmail);
				//Validation for Server Side
				//TODO: add validation
				
				if($formPassword != $formRetypePassword){
					$error['content'] = 'Password does not match with retype password. Please input match password.';
					$error['autoclose'] = false;
					break;
				}

				/*
				Check for password strength, password should be at least 8 characters, 
				contain at least one number, 
				contain at least one lowercase letter, 
				contain at least one uppercase letter, 
				contain at least one special character. 
				*/
				
				if(strlen($formPassword) < $password_length){
					$error['content'] = 'Password length must be more than 8 characters';
					$error['autoclose'] = false;
					break;
				}
				
				if(!preg_match("#[0-9]+#", $formPassword)){
					$error['content'] = 'Password must have at least one number';
					$error['autoclose'] = false;
					break;
				}
				
				if(!preg_match("#[a-z]+#", $formPassword)){
					$error['content'] = 'Password must have at least one lowercase alphabet';
					$error['autoclose'] = false;
					break;
				}
				
				if(!preg_match("#[A-Z]+#", $formPassword)){
					$error['content'] = 'Password must have at least one uppercase alphabet';
					$error['autoclose'] = false;
					break;
				}
				
				if(!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $formPassword)){
					$error['content'] = 'Password must have at least one special character';
					$error['autoclose'] = false;
					break;
				}

				if($error['content']){break;}
				$response = $_POST["g-recaptcha-response"];
				$url = 'https://www.google.com/recaptcha/api/siteverify';
				$data = array(
					'secret' => '6LfxYyIUAAAAABZlrCXa7TqtME_fXmtTle7rZ4xY',
					'remoteip' => $_SERVER['REMOTE_ADDR'],
					'response' => $response
				);
				$options = array(
					'http' => array (
						'method' => 'POST',
						'content' => http_build_query($data)
					)
				);
				
				$context  = stream_context_create($options);
				$verify = file_get_contents($url, false, $context);
				$captcha_success=json_decode($verify);
				if ($captcha_success->success==false) {
					$error['content'] = "You are a bot! Go away!";
					break;
				}
				//End of Validation 
				if($error['content']){break;}
				
				$data = array();
				
				$data['username'] 			= $formEmail;
				$data['first_name'] 		= $formNameFirst;
				$data['last_name'] 			= $formNameLast;
				$data['spouse_name'] 		= $formNameSpouse;
				$data['region'] 			= REGION;
				$data['mailing_fullname'] 	= $formNameFirst." ".$formNameLast;
				$data['mailing_address1'] 	= $formAddress1;
				$data['mailing_address2'] 	= $formAddress2;
				$data['mailing_city'] 		= $formCity;
				$data['mailing_state'] 		= $formState;
				$data['mailing_country'] 	= $formCountry;
				$data['mailing_zipcode'] 	= $formZIP;
				$data['mailing_email'] 		= $formEmail;
				$data['phone'] 				= $formTelephoneMobile;
				$data['phone_day'] 			= $formTelephoneDaytime;
				$data['phone_night'] 		= $formTelephoneEvening;
				$data['salt'] 				= generateSalt(15);
				$data['uid'] 				= $objUser->generateUID();
				$data['password'] 			= hashPassword($data['username'], $formPassword, $data['salt']);
				$data['email'] 				= $formEmail;
				
				$data['created_date'] = date("Y-m-d H:i:s");
				
				if($objUser->saveUser($data)){ 
					$insertedId = $objUser->getInsertedId();
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}
				}else{
					$error['content'] = "Cannot save donor profile. Please try again.";
				}
				
			}
		break;
		default:
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
				}
				echo json_encode($output);
				exit;
			}
			insertTracker(MODULE_NAME);
		break;
	}
	require DIR_ACTIVE_PUBLIC_THEME.'/site_builder.php';
?>