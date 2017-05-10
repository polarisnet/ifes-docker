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
		case 'oz.system.logs.audit.view':
			require DIR_LIBS.'/audit_trails.class.php';
			$objAuditTrails = new AuditTrails($GLOBALS['myDB']);
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.logs.audit')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			$trailsData = $objAuditTrails->getAuditTrailsData($decryptKey);
			if(empty($trailsData)){
				header("Location: ".getModuleURL('oz.system.logs.audit')."?invalid=2");
				exit;
			}
			$gridState = getGridState('oz.system.logs.audit.list_filter', 'id', 'sys_audit_trails');
			getUserCreateModify($trailsData, $trailsData['id']);
			$HTTP_AJAX = getModuleURL('oz.system.logs.audit').'/ajax';
			insertTracker(MODULE_NAME.(isset($trailsData["module"])?": ".$trailsData["module"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/logs/view_audit.php";
		break;
		case 'oz.system.logs.audit':
			require DIR_LIBS.'/audit_trails.class.php';
			$objAuditTrails = new AuditTrails($GLOBALS['myDB']);
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case 'list_audit':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						
						$strOrderBy = "";
						if(isset($_GET['sort'])){
							$arrSorting = (array)json_decode($_GET['sort']);
							if(isset($arrSorting[0])) {
								$arrSorting = (array)$arrSorting[0];
								if(isset($arrSorting["property"]) && isset($arrSorting["direction"])) {
									$strOrderBy .= " ORDER BY `".$arrSorting["property"]."` ".$arrSorting["direction"]." ";
								}
							}
						}
						if($strOrderBy!="") {
							$condition .= $strOrderBy;
						} else {
							$condition .= " ORDER BY `created_date` DESC";
						}
						
						$_SESSION['oz.system.logs.audit.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objAuditTrails->listAuditTrails($condition, $start, $limit, "*");
						$output['total'] = $objAuditTrails->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_trails':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objAuditTrails->checkAuditTrailsExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Audit trails does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected audit trails successfully deleted.";
							foreach($delId AS $value){
								$objAuditTrails->deleteAuditTrails($value);
							}
						}
					break;
					case 'clear_trails':
						$objAuditTrails->clearAuditTrails();
						$output['success'] = true;
						$output['message'] = "Audit trails successfully cleared.";
					break;
				}
				echo json_encode($output);
				exit;
			}
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/logs/list_audit.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objAuditTrails->listAuditTrailsField();

			if(!empty($_GET)){
				$invalidGet = checkParam('invalid');
				if($invalidGet == '1'){
					$error['content'] = "Missing URL key. Your previous session may have ended unexpectedly. Please select the record that you wish to view/edit again.";
					$error['autoclose'] = true;
				}else if($invalidGet == '2'){
					$error['content'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
					$error['autoclose'] = true;
				}
			}
		break;
		case 'oz.system.logs.error':
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case 'read_log':
						$file = checkParam('file');
						$output['success'] = true;
						$output['log'] = readErrorLogHTML($file);
					break;
					default:
					break;
				}
				
				echo json_encode($output);
				exit;
			}else if($action == 'doctor-rebuild-item'){
				echo 'Doctor is here';
				require DIR_LIBS.'/oz.doctor.class.php';
				$objOZDoctor = new OZDoctor($GLOBALS['myDB']);
				$objOZDoctor->rebuildMediaFolder('clean', 'item-image', 'items', 'uid');
				exit;
			}else if($action == 'doctor-rebuild-user'){
				echo 'Doctor is here';
				require DIR_LIBS.'/oz.doctor.class.php';
				$objOZDoctor = new OZDoctor($GLOBALS['myDB']);
				$objOZDoctor->rebuildMediaFolder('clean', 'user-image', 'sys_users', 'uid');
				exit;
			}else if($action == 'doctor-rebuild-device'){
				echo 'Doctor is here';
				require DIR_LIBS.'/oz.doctor.class.php';
				$objOZDoctor = new OZDoctor($GLOBALS['myDB']);
				$objOZDoctor->rebuildMediaFolder('clean', 'device-data', '	touchsales_devices', 'uid');
				exit;
			}else if($action == 'doctor-rebuild-item-image'){
				ini_set("memory_limit","-1");
				ini_set('max_execution_time', 0);
				echo 'Doctor is here';
				require DIR_LIBS.'/oz.doctor.class.php';
				$objOZDoctor = new OZDoctor($GLOBALS['myDB']);
				$objOZDoctor->rebuildItemImage();
				exit;
			}
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/logs/view_error.php";
		break;
		default:
			insertTracker(MODULE_NAME);
		break;
	}
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>