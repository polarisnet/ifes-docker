<?php
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
		"center_dir" => DIR_ACTIVE_THEME."/oz.message/message.php",
		"right" => "0",
		"right_dir" => "",
		"footer" => "1",
		"footer_dir" => DIR_ACTIVE_THEME."/footer.php",
		"widgets" => "0",
		"current" => "",
		"load_tile" => "0",
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
			if($action == 'savestates'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				
				$sid = substr(checkParam("sid", "sid"), 1, -1);
				$smid = checkParam("smid", "");
				$gridid = checkParam("gridid", "");
				$userid = checkParam("userid", "0");
				$settings = checkParam("settings", "");
				$settings = serialize($settings);
				
				require DIR_LIBS.'/general.class.php';
				$objGeneral = new General($GLOBALS['myDB']);
			
				switch($opt){
					case 'save':
						$gridData = array();
						$gridData["settings"] = $settings;
						$intTotal = $objGeneral->getTotalRecordsWithConditions("id", "sys_grids_settings", " AND `user_id`='".$userid."' AND `grid_id`='".$smid."' ");
						if($intTotal>0) {
							$objGeneral->updateRecordByConditions($gridData, "sys_grids_settings", " AND `user_id`='".$userid."' AND `grid_id`='".$smid."' ", true);
						} else {
							$gridData["grid_id"] = $smid;
							$gridData["user_id"] = $userid;
							$objGeneral->saveRecord($gridData, "sys_grids_settings", true);
						}
						$output["success"] = true;
					break;
					case 'restore':
						$output["settingid"] = $gridid;
						$objGeneral->getDetailsByTableConditions('sys_grids_settings', " AND `user_id`='".$userid."' AND `grid_id`='".$smid."' ");
						if($objGeneral->db->nextRecord()) {
							$objGeneral->db->data = $objGeneral->db->getRecord();
							$output["settings"]	= unserialize(html_entity_decode($objGeneral->db->Get("settings"), ENT_QUOTES));
							$output["success"] = true;
						}
					break;
					case 'delete':
						$objGeneral->deleteRecord("sys_grids_settings", " AND `user_id`='".$userid."' AND `grid_id`='".$smid."' ");
						$output["success"] = true;
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