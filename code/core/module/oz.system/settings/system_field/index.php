<?php
	require 'system_field.class.php';
	$objSystemField = new SystemField($GLOBALS['myDB']);
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
		case 'oz.system.settings.system_field.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/system_field/list_system_field.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objSystemField->listSystemFieldField();
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
		/*
		case 'oz.system.settings.system_field.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objSystemField->getSystemFieldLabelById($decryptKey);
						if($data != ""){
							$message['content'] = "System field ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/system_field/new_system_field.php";
						
			$formModuleForm 	= "";
			$formUID			= "";
			$formSection 		= "";
			$formFieldType 		= "";
			$formStatus 		= "";
			$formFieldLabel		= "";
			$formFieldCode		= "";
			
			if(!empty($_POST)){
				$submitMode     = checkParam('submit_mode');
				$formModuleForm = checkParam('ext-moduleform');
				$formUID        = checkParam('form_uid');
				$formSection    = checkParam('ext-section');		
				$formFieldType 	= "dropbox";
				$formStatus     = checkParam('combo-status');	
				$formFieldLabel = checkParam('fieldlabel');
				$formFieldCode = checkParam('fieldcode');
				
				if(!validateEmptyField($formModuleForm, 'form', $error)){break;}
				if(!validateEmptyField($formSection, 'form section', $error)){break;}
				if(!validateEmptyField($formFieldType, 'field type', $error)){break;}
				if(!validateEmptyField($formFieldLabel, 'field label', $error)){break;}				
				if(!validateEmptyField($formFieldCode, 'field code', $error)){break;}				
				$decForm = encryption(rawurldecode($formModuleForm), $_SESSION['salt'], false);	
				$decSection = encryption(rawurldecode($formSection), $_SESSION['salt'], false);							
				if($objSystemField->checkLabelExist($formFieldLabel, $decForm, '')){
					$error['content'] = "Field label already exist. Please input another title.";
					break;
				}
				
				$data = array();
				$data['sys_module_id']	= $decForm;
				$data['module_uid']		= strtolower($formUID);
				$data['cf_section_id']	= $decSection;
				$data['cf_type']		= $formFieldType;
				$data['cf_status']		= $formStatus;
				$data['cf_label']		= $formFieldLabel;
				$data['cf_code']		= strtolower(str_replace(' ', '_', $formFieldCode));
				$data['created_by']		= $_SESSION['user_id'];
				$data['created_date']	= date("Y-m-d H:i:s");
				
				if($objSystemField->saveSystemField($data)){					
					$insertedId = $objSystemField->getInsertedId();
					//$updatedata = array();
					//$updatedata['id'] = $insertedId;
					//$updatedata['cf_code'] = $insertedId."_".$data['cf_code'];
					//$objSystemField->updateSystemField($updatedata);
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.settings.system_field.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save system field. Please try again.";
				}
			}
		break;
		*/
		case 'oz.system.settings.system_field.view':
		case 'oz.system.settings.system_field.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objSystemField->getSystemFieldLabelById($decryptKey);
						if($data != ""){
							$message['content'] = "System field ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objSystemField->getSystemFieldLabelById($decryptKey);
						if($data != ""){
							$message['content'] = "System field ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
						
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.system_field.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$systemfieldData = $objSystemField->getSystemFieldData($decryptKey);
			if(empty($systemfieldData)){
				header("Location: ".getModuleURL('oz.system.settings.system_field.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.system_field.view');
			$allowDelete 	= checkAccess('oz.system.settings.system_field.delete');
			$allowEdit 		= checkAccess('oz.system.settings.system_field.edit');
			
			if(MODULE_UID == 'oz.system.settings.system_field.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			if($decryptKey == '1' || $decryptKey == '-1'){
				$allowDelete = false;
			}else{
				$allowDelete = true;
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($systemfieldData["cf_label"])?": ".$systemfieldData["cf_label"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/system_field/view_system_field.php";
			
			$detailsStart = 0;
			$detailsItemsPerPage = 15;
			$optionFields = $objSystemField->listOptionsField();
			
			if($allowEdit){
				$formModuleForm = $systemfieldData['sys_module_id'];
				$formUID = $systemfieldData['module_uid'];
				$formFieldType = $systemfieldData['cf_type'];
				$formStatus = $systemfieldData['cf_status'];
				$formFieldLabel = $systemfieldData['cf_label'];
				$formFieldCode = preg_replace("/[^\sa-zA-Z0-9_.-]/", "", strtolower(str_replace(' ', '_', $formFieldLabel)));
				$formMandatory = $systemfieldData['cf_mandatory'];
				
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formModuleForm = checkParam('ext-moduleform');
					$formUID = checkParam('form_uid');
					$formFieldType = "dropbox";		
					$formStatus = "1";
					$formFieldLabel = checkParam('fieldlabel');
					$formFieldCode = preg_replace("/[^\sa-zA-Z0-9_.-]/", "", strtolower(str_replace(' ', '_', $formFieldLabel)));
					$formMandatory = checkParam('combo-mandatory');	
					
					$decForm = encryption(rawurldecode($formModuleForm), $_SESSION['salt'], false);	
					
					if(!validateEmptyField($formFieldType, 'field type', $error)){break;}
					if(!validateEmptyField($formFieldLabel, 'field label', $error)){break;}				
					if(!validateEmptyField($formFieldCode, 'field code', $error)){break;}				
					if($objSystemField->checkLabelExist($formFieldLabel, $decForm, $systemfieldData['id'])){
						$error['content'] = "Field label already exist. Please input another field label.";
						break;
					}
					
					$newData = array();
					$newData['id'] = $systemfieldData['id'];
					$newData['sys_module_id'] = $decForm;
					$newData['module_uid'] = strtolower($formUID);
					$newData['cf_type'] = $formFieldType;
					$newData['cf_status'] = $formStatus;
					$newData['cf_label'] = $formFieldLabel;
					$newData['cf_code'] = $formFieldCode;
					$newData['cf_mandatory'] = $formMandatory;
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objSystemField->updateSystemField($newData)){
						insertAuditTrails('oz.system.settings.system_field.edit', 'update', "", $systemfieldData, $newData);
						$encInsertedId = encryption($systemfieldData['id'], $_SESSION['salt'], true);
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
					case 'list_system_field':
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
							$condition .= " ORDER BY `id` ASC ";
						}
						
						$_SESSION['oz.system.settings.system_field.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objSystemField->listSystemField($condition, $start, $limit);
						$output['total'] = $objSystemField->getTotalRow();
						$output['success'] = true;
					break;
					case 'list_options':
						$start 		= checkParam('start');
						$limit 		= checkParam('limit');						
						$parentType = checkParam('parentType');						
						$filter 	= array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition 	= getFilterSQL($filter);
						$parent 	= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$condition .= " AND `cf_id`='".$parent."' ";
						
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
							$condition .= " ";
						}
						
						$_SESSION['oz.system.settings.system_field.list_options_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objSystemField->listOptions('', $condition, $start, $limit, "*");
						$output['total'] = $objSystemField->getTotalRow();
						$output['success'] = true;
					break;	
					case 'add_option':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);						
						$newData = array();
						$newData['cf_content_label'] 	= checkParam('label');
						$newData['cf_content_value']	= checkParam('value');
						$newData['cf_content_value']	= checkParam('value');
						$newData['cf_content_order']	= checkParam('order');
						$newData['cf_remark'] 			= checkParam('parentType');		
						$newData['cf_id'] 				= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);	
						
						if($operation == 'new'){ 
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objSystemField->saveOptions($newData)){
								insertAuditTrails('oz.system.settings.system_field', 'insert', "System Field - Add options", $newData);
								$output['success'] = true;
								$output['message'] = 'Option has been successfully created.';
							}
						}else{
							$detailsData = $objSystemField->getOptionsData($id);
							if(empty($detailsData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objSystemField->updateOptions($newData)){
								insertAuditTrails('oz.system.settings.system_field.edit', 'update', "System Field - Edit options", $detailsData, $newData);
								$output['success'] = true;
								$output['message'] = 'Option has been successfully updated.';
							}
						}
					break;
					case 'delete_options':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							array_push($delId, $value);
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected system field successfully deleted.";
							foreach($delId AS $value){
								$data = $objSystemField->getOptionsData($value);
								if($objSystemField->deleteOptions($value, $data)){									
									insertAuditTrails('oz.system.settings.system_field.edit', 'delete', "System Field - Delete options", $data);									
								}
							}
						}
					break;
					case 'combo_moduleform':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = ' AND `display_system_field` = "1"';
						if($query != ''){
							$condition .= " AND (`module_display` LIKE '%".$query."%' OR `uid` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objSystemField->getModuleFormCombo($condition, $start, $limit);
						$output['total_row'] = $objSystemField->getTotalRow();
						$output['success'] = true;
					break;
					/*case 'combo_section':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$conditions = checkParam('conditions');
						$strSorting = '';
						if($query != ''){
							$strSorting .= " AND (`section_name` LIKE '%".$query."%') ";
						}
						$conditions = encryption(rawurldecode($conditions), $_SESSION['salt'], false);						
						$filter_query = "";
						if($conditions != ''){
							$filter_query .= $conditions;
						}  
						$output['combo'] = $objSystemField->getSectionCombo($filter_query, $strSorting, $start, $limit);
						$output['total_row'] = $objSystemField->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_system_field':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objSystemField->checkSystemFieldExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "System field does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected system field successfully deleted.";
							foreach($delId AS $value){
								$data = $objSystemField->getSystemFieldData($value);
								if($objSystemField->deleteSystemField($value)){									
									insertAuditTrails('oz.system.settings.system_field.delete', 'delete', "", $data);									
								}
							}
						}
					break;*/
				}
				echo json_encode($output);
				exit;
			}
			insertTracker(MODULE_NAME);
		break;
	}
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>