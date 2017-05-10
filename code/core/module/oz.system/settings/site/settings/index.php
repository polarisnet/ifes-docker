<?php
	require 'setting.class.php';
	$objSetting = new Setting($GLOBALS['myDB']);

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
		default:
            if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
                   default:
				   break;
                }
                echo json_encode($output);
				exit;
			}

			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						if($decryptKey == "223"){
							$message['content'] = "Site settings has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/site/settings/settings.php";
			
			$settingData = $objSetting->getSettingData();
			
			$formLoginAttempt = $settingData['max_login_attempt'];
			$formLoginLock = $settingData['max_login_lockdown'];
			
			if(!empty($_POST)){
				$newData = array();
				$formLoginAttempt = checkParam('login_attempt');
				$formLoginLock = checkParam('login_lock');
				$newData['max_login_attempt'] = $formLoginAttempt;
				$newData['max_login_lockdown'] = $formLoginLock;

				if($objSetting->updateSetting($newData)){
					insertAuditTrails('oz.system.settings.site.settings.edit', 'update', "", $settingData, $newData);
					setCookieValue(encryption('223', $_SESSION['salt'], true), 'added_key');
					header("Location: ".HTTP_ACTIVE_MODULE."?redir=update");
					exit;
				}
			}
		break;
	}
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>