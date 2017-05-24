<?php 
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	require DIR_MODULE.'/site/donor/donor.class.php';
	
	$objDonor 			= new Donor($GLOBALS['myDB']);
	
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
						$data = $objDonor->getDonorNameById($decryptKey);
						if($data != ""){
							$message['content'] = "Donor ".$data." has been successfully created.";
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
			
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				
				//TODO: check param values
				$response = checkParam('g-recaptcha-response');
				$formUsername = checkParam('username');
				$formEmail = checkParam('email_address');
				$formPassword = checkParam('password');
				//Validation for Server Side
				//TODO: add validation

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
				
				$data['username'] = $formUsername;
				$data['salt'] = generateSalt(15);
				$data['uid'] = $objDonor->generateUID();
				$data['password'] = hashPassword($data['username'], $formPassword, $data['salt']);
				$data['email'] = $formEmail;
				
				$data['created_date'] = date("Y-m-d H:i:s");
				if($objDonor->saveDonor($data)){ 
					$insertedId = $objDonor->getInsertedId();
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
									
					case 'email_change_password':
					
						$intCustomerID = checkParam('id');
						if($intCustomerID == ""){
							$output['message'] = "Invalid customer Id. Please try again.";
						} else {
							
							$arrCustomerDetails = $objCustomer->getCustomerData($intCustomerID);
							if(count($arrCustomerDetails)>0) {
								$strResetStatus = "9".str_pad($arrCustomerDetails['id'], 6, "0", STR_PAD_LEFT);
								$arrCustomerDetails["cid"] = $strResetStatus;
								//echo $strResetStatus;exit;
								if(empty($arrCustomerDetails["email"])) {
									$output['message'] = "Please provide email to send change password request.";
								} else {
									$changePasswordStatus = $objCatalogue->sendCustomerChangePasswordEmail($arrCustomerDetails);
									if($changePasswordStatus){
										$output['success'] = true;
									} else {
										$output['message'] = "Your account cannot be change password at this moment. Please try again.";
									}
								}
								
							} else {
								$output['message'] = "We cannot find any account with this customer id.";
							}
						}
					break;
				}
				echo json_encode($output);
				exit;
			}
			insertTracker(MODULE_NAME);
		break;
	}
	require DIR_ACTIVE_PUBLIC_THEME.'/site_builder.php';
?>