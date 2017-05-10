<?php
	require 'communication_type.class.php';
	$objCommunicationType = new CommunicationType($GLOBALS['myDB']);
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
		"center_dir" => DIR_ACTIVE_THEME."/oz.system/blank.php",
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
		case 'oz.system.settings.activitity_management.communication_type.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/activitity_management/communication_type/list_communication_type.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objCommunicationType->listCommunicationTypeField();
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
		case 'oz.system.settings.activitity_management.communication_type.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCommunicationType->getCommunicationTypeById($decryptKey);
						if($data != ""){
							$message['content'] = "Activity Type ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/activitity_management/communication_type/new_communication_type.php";
			
			$formCommunicationType = "";
			$formCommunicationOrder = "0";
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formCommunicationType = checkParam('type');				
				$formCommunicationOrder = checkParam('fieldcommorder');
				
				if(!validateEmptyField($formCommunicationType, 'activity type', $error)){break;}
				if($objCommunicationType->checkCommunicationTypeExist($formCommunicationType, '')){
					$error['content'] = "Activity Type already exist. Please input another activity type.";
					break;
				}				
				
				$data = array();
				$data['type'] = $formCommunicationType;
				$data['order'] = $formCommunicationOrder;
				$data['created_by'] = $_SESSION['user_id'];
				$data['created_date'] = date("Y-m-d H:i:s");
				
				if($objCommunicationType->saveCommunicationType($data)){
					$insertedId = $objCommunicationType->getInsertedId();
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.settings.activitity_management.communication_type.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save activity type. Please try again.";
				}
			}
		break;
		case 'oz.system.settings.activitity_management.communication_type.view':
		case 'oz.system.settings.activitity_management.communication_type.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCommunicationType->getCommunicationTypeById($decryptKey);
						if($data != ""){
							$message['content'] = "Activity Type ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCommunicationType->getCommunicationTypeById($decryptKey);
						if($data != ""){
							$message['content'] = "Activity Type ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
		
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.activitity_management.communication_type.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$communicationtypeData = $objCommunicationType->getCommunicationTypeData($decryptKey);
			if(empty($communicationtypeData)){
				header("Location: ".getModuleURL('oz.system.settings.activitity_management.communication_type.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.activitity_management.communication_type.view');
			$allowDelete 	= checkAccess('oz.system.settings.activitity_management.communication_type.delete');
			$allowEdit 		= checkAccess('oz.system.settings.activitity_management.communication_type.edit');
			
			if(MODULE_UID == 'oz.system.settings.activitity_management.communication_type.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($communicationtypeData["type"])?": ".$communicationtypeData["type"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/activitity_management/communication_type/view_communication_type.php";
			if($allowEdit){
				$formCommunicationType = $communicationtypeData['type'];
				$formCommunicationOrder = $communicationtypeData['order'];
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formCommunicationType = checkParam('type');					
					$formCommunicationOrder = checkParam('fieldcommorder');
					if(!validateEmptyField($formCommunicationType, 'activity type', $error)){break;}
					if($objCommunicationType->checkCommunicationTypeExist($formCommunicationType, $communicationtypeData['id'])){
						$error['content'] = "Communication Type already exist. Please input another communication type.";
						break;
					}
					
					$newData = array();
					$newData['id'] = $communicationtypeData['id'];
					$newData['type'] = $formCommunicationType;
					$newData['order'] = $formCommunicationOrder;
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objCommunicationType->updateCommunicationType($newData)){
						insertAuditTrails('oz.system.settings.activitity_management.communication_type.edit', 'update', "", $communicationtypeData, $newData);
						$encInsertedId = encryption($communicationtypeData['id'], $_SESSION['salt'], true);
						setCookieValue($encInsertedId, 'added_key');
						header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encryptKey)."&redir=update");
						exit;
					}
				}
			}
		break;
		default:
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case 'check_duplicate_type':
						$type = checkParam('val');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objCommunicationType->checkCommunicationTypeExist($type, $id)){
							$output['message'] = "Communication Type already exist. Please input another communication type.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'list_communication_type':
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
							$condition .= " ORDER BY `order` ASC, `type` ASC ";
						}
						
						$_SESSION['oz.system.settings.activitity_management.communication_type.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCommunicationType->listCommunicationType($condition, $start, $limit, "*");
						$output['total'] = $objCommunicationType->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_communication_type':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCommunicationType->checkCommunicationTypeIDExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Activity Type does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected activity type successfully deleted.";
							foreach($delId AS $value){
								$data = $objCommunicationType->getCommunicationTypeData($value);
								if($objCommunicationType->deleteCommunicationType($value)){
									insertAuditTrails('oz.system.settings.activitity_management.communication_type.delete', 'delete', "", $data);
								}
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
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>