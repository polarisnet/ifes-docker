<?php
	require DIR_LIBS.'/mailer.class.php';
	$objMailer = new Mailer($GLOBALS['myDB']);
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
		case 'oz.system.settings.mailer.settings':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$message['content'] = "Mailer setting has been successfully updated.";
						deleteCookieValue('added_key');
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/mailer/mailer_settings.php";
			
			$settingsData = $objMailer->getSettingsData('1');
			$formAuth = $settingsData['auth'];
			$formHost = $settingsData['host'];
			$formPort = $settingsData['port'];
			$formUser = $settingsData['user'];
			$formPassword = $settingsData['pass'];
			$formSenderMail = $settingsData['default_sender_mail'];
			$formSender = $settingsData['default_sender'];
			$formReplyMail = $settingsData['default_reply_mail'];
			$formReply = $settingsData['default_reply'];
			if(!empty($_POST)){
				$formAuth = checkParam('auth', '0');
				$formHost = checkParam('host');
				$formPort = checkParam('port');
				$formUser = checkParam('user');
				$formPassword = checkParam('password');
				$formSender = checkParam('sender');
				$formSenderMail = checkParam('sender_mail');
				$formReply = checkParam('reply');
				$formReplyMail = checkParam('reply_mail');
				
				if(!validateEmptyField($formHost, 'SMTP host', $error)){break;}
				if(!validateEmptyField($formPort, 'SMTP port', $error)){break;}
				if($formAuth == '1'){
					if(!validateEmptyField($formUser, 'SMTP user', $error)){break;}
					if(!validateEmptyField($formPassword, 'SMTP password', $error)){break;}
				}
				if(!validateEmptyField($formSender, 'default sender', $error)){break;}
				if(!filter_var($formSenderMail, FILTER_VALIDATE_EMAIL)){
					$error['content'] = "Invalid sender email address. Please input correct email address.";
					break;
				}
				if($formReplyMail != '' && !filter_var($formSenderMail, FILTER_VALIDATE_EMAIL)){
					$error['content'] = "Invalid sender email address. Please input correct email address.";
					break;
				}
				
				$newData = array();
				$newData['id'] = '1';
				$newData['auth'] = $formAuth;
				$newData['host'] = $formHost;
				$newData['port'] = $formPort;
				$newData['user'] = $formUser;
				$newData['pass'] = $formPassword;
				$newData['default_sender'] = $formSender;
				$newData['default_sender_mail'] = $formSenderMail;
				$newData['default_reply'] = $formReply;
				$newData['default_reply_mail'] = $formReplyMail;
				$newData['modified_by'] = $_SESSION['user_id'];
				$newData['modified_date'] = date("Y-m-d H:i:s");
			
				if($objMailer->updateSettings($newData)){
					insertAuditTrails('oz.system.settings.mailer.settings', 'update', "", $settingsData, $newData);
					$encInsertedId = encryption($settingsData['id'], $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encInsertedId)."&redir=update");
					exit;
				}
			}
		break;
		case 'oz.system.settings.mailer.template':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'update'){
					$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
					$data = $objMailer->getTemplateNameById($decryptKey);
					if($data != ""){
						$message['content'] = "Template ".$data." has been successfully updated.";
						deleteCookieValue('added_key');
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/mailer/mailer_template.php";
			
			$listTemplate = $objMailer->listTemplate(' ORDER BY `name` ASC', 0, 0, false, false);
			if(!empty($listTemplate)){
				$key = checkParam('key', '', 'get');
				$decryptKey = encryption($key, $_SESSION['salt'], false);
				$templateData = $objMailer->getTemplateData($decryptKey);
				if(empty($templateData)){
					$templateData = $objMailer->getTemplateData($listTemplate[0]['id']);
				}
				$formTemplate = $templateData['id'];
				$formReply = $templateData['reply'];
				$formReplyMail = $templateData['reply_mail'];
				$formSender = $templateData['sender'];
				$formSenderMail = $templateData['sender_mail'];
				$formSubject = $templateData['subject'];
				$formBcc = $templateData['bcc'];
				$formContent = $templateData['content'];
				$formNote = $templateData['note'];
				$formCode = $templateData['code'];
			}else{
				$formTemplate = "";
				$formReply = "[DEFAULT]";
				$formReplyMail = "[DEFAULT]";
				$formSender = "[DEFAULT]";
				$formSenderMail = "[DEFAULT]";
				$formSubject = "";
				$formBcc = "";
				$formContent = "";
				$formNote = "";
				$formCode = "";
			}
			if(!empty($_POST)){
				//print_r($_POST); exit;
				$formTemplate = checkParam('template');
				$formReply = checkParam('reply');
				$formReplyMail = checkParam('reply_mail');
				$formSender = checkParam('sender');
				$formSenderMail = checkParam('sender_mail');
				$formSubject = checkParam('subject');
				$formBcc = checkParam('bcc');
				$formContent = checkParam('content', '', '', array('css' => false));
				
				if(!validateEmptyField($formSender, 'sender', $error)){break;}
				if(!validateEmptyField($formSenderMail, 'sender mail', $error)){break;}
				if($formSenderMail != '[DEFAULT]' && !validateEmptyField($formSenderMail, 'sender mail', $error)){break;}
				if($formReplyMail != '[DEFAULT]' && $formReplyMail != '' && !validateEmptyField($formReplyMail, 'reply mail', $error)){break;}
				if(!validateEmptyField($formSubject, 'subject', $error)){break;}
				if(!validateEmptyField($formContent, 'subject', $error)){break;}
				
				$newData = array();
				$newData['id'] = $formTemplate;
				$newData['reply_mail'] = $formReplyMail;
				$newData['reply'] = $formReply;
				$newData['sender'] = $formSender;
				$newData['sender_mail'] = $formSenderMail;
				$newData['subject'] = $formSubject;
				$newData['bcc'] = $formBcc;
				$newData['content'] = $formContent;
				$newData['modified_by'] = $_SESSION['user_id'];
				$newData['modified_date'] = date("Y-m-d H:i:s");
				
				if($objMailer->updateTemplate($newData)){
					insertAuditTrails('oz.system.settings.mailer.template', 'update', "", $templateData, $newData);
					$encInsertedId = encryption($formTemplate, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encInsertedId)."&redir=update");
					exit;
				}
			}
		break;
		default:
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => '');
				$opt = checkParam('opt');
				switch($opt){
					case 'test_smtp':
						$output['success'] = $objMailer->testMailer();
						if(!$output['success']){
							$output['success'] = "Something wrong with mailer. Please try again.";
						}
					break;
					case 'change_template':
						$templateData = $objMailer->getTemplateData(checkParam('template'));
						if(!empty($templateData)){
							$output['code'] = $templateData['code'];
							$output['content'] = $templateData['content'];
							$output['subject'] = $templateData['subject'];
							$output['note'] = $templateData['note'];
							$output['sender'] = $templateData['sender'];
							$output['sender_mail'] = $templateData['sender_mail'];
							$output['reply_mail'] = $templateData['reply_mail'];
							$output['reply'] = $templateData['reply'];
							$output['success'] = true;
						}else{
							$output['message'] = 'Template data is missing. Please try again.';
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