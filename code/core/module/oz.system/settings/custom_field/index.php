<?php
	require 'custom_field.class.php';
	$objCustomField = new CustomField($GLOBALS['myDB']);
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
		case 'oz.system.settings.custom_field.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/custom_field/list_custom_field.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objCustomField->listCustomFieldField();
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
		case 'oz.system.settings.custom_field.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCustomField->getCustomFieldLabelById($decryptKey);
						if($data != ""){
							$message['content'] = "Custom field ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/custom_field/new_custom_field.php";
						
			$formModuleForm 	= "";
			$formSection 		= $formModuleForm;
			$formUID			= "";
			$formFieldLabel 	= "";
			$formFieldType 		= "";
			$formMandatory 		= "";
			$formPosition 		= "";			
			$formSection 		= "";
			$formTooltip 		= "";
			$formStatus 		= "";
			$formOptionContent 	= "";
			$formOrder			= 0;
						
			if(!empty($_POST)){
				$submitMode     = checkParam('submit_mode');
				$formModuleForm = checkParam('ext-moduleform');
				$formUID        = checkParam('form_uid');
				$formSection    = checkParam('ext-section');
				$formPosition   = checkParam('ext-position');
				$formFieldType  = checkParam('combo-fieldtype');		
				$formStatus     = checkParam('combo-status');
				$formFieldLabel = checkParam('fieldlabel');
				$formTooltip    = checkParam('tooltip');
				$formOrder      = checkParam('fieldorder');
				$formMandatory  = checkParam('combo-mandatory');	
				
				if(!validateEmptyField($formModuleForm, 'form', $error)){break;}
				if(!validateEmptyField($formSection, 'form section', $error)){break;}
				if(!validateEmptyField($formPosition, 'field position', $error)){break;}
				if(!validateEmptyField($formFieldType, 'field type', $error)){break;}
				if(!validateEmptyField($formFieldLabel, 'field label', $error)){break;}				
				if(!validateEmptyField($formMandatory, 'mandatory', $error)){break;}	
				if($formOrder != '' && !validateNumericField($formOrder, 'field order', $error)){break;}
				
				$decForm = encryption(rawurldecode($formModuleForm), $_SESSION['salt'], false);	
				$decSection = encryption(rawurldecode($formSection), $_SESSION['salt'], false);							
				if($objCustomField->checkLabelExist($formFieldLabel, $decForm, '')){
					$error['content'] = "Field label already exist. Please input another title.";
					break;
				}
				$data = array();
				$data['sys_module_id']	= $decForm;
				$data['module_uid']		= strtolower($formUID);
				$data['cf_section_id']	= $decSection;
				$data['cf_position']	= $formPosition;
				$data['cf_type']		= $formFieldType;
				$data['cf_status']		= $formStatus;
				$data['cf_label']		= $formFieldLabel;
				$getCode				= strtolower(str_replace(' ', '_', $formFieldLabel));
				$data['cf_code']		= preg_replace("/[^\sa-zA-Z0-9_.-]/", "", $getCode);
				$data['cf_tooltip']		= $formTooltip;
				$data['cf_order']		= $formOrder;
				$data['cf_mandatory']	= $formMandatory;				
				$data['cf_mandatory']	= $formMandatory;	
				$data['created_by']		= $_SESSION['user_id'];
				$data['created_date']	= date("Y-m-d H:i:s");
				
				if($objCustomField->saveCustomField($data)){					
					$insertedId = $objCustomField->getInsertedId();
					$updatedata = array();
					$updatedata['id'] = $insertedId;
					$updatedata['cf_code'] = $insertedId."_".$data['cf_code'];
					$objCustomField->updateCustomField($updatedata);
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.settings.custom_field.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save custom field. Please try again.";
				}
			}
		break;
		case 'oz.system.settings.custom_field.view':
		case 'oz.system.settings.custom_field.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCustomField->getCustomFieldLabelById($decryptKey);
						if($data != ""){
							$message['content'] = "Custom field ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCustomField->getCustomFieldLabelById($decryptKey);
						if($data != ""){
							$message['content'] = "Custom field ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
						
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.custom_field.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$customfieldData = $objCustomField->getCustomFieldData($decryptKey);
			if(empty($customfieldData)){
				header("Location: ".getModuleURL('oz.system.settings.custom_field.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.custom_field.view');
			$allowDelete 	= checkAccess('oz.system.settings.custom_field.delete');
			$allowEdit 		= checkAccess('oz.system.settings.custom_field.edit');
			
			if(MODULE_UID == 'oz.system.settings.custom_field.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($customfieldData["cf_label"])?": ".$customfieldData["cf_label"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/custom_field/view_custom_field.php";
			
			$detailsStart = 0;
			$detailsItemsPerPage = 15;
			$optionFields = $objCustomField->listOptionsField();
			
			if($allowEdit){
				$formModuleForm = $customfieldData['sys_module_id'];
				$formUID = $customfieldData['module_uid'];
				$formSection = $customfieldData['cf_section_id'];
				$formColumn = $customfieldData['cf_column'];
				$formPosition = $customfieldData['cf_position'];
				$formFieldType = $customfieldData['cf_type'];
				$formStatus = $customfieldData['cf_status'];
				$formFieldLabel = $customfieldData['cf_label'];
				$formTooltip = $customfieldData['cf_tooltip'];
				$formOrder = $customfieldData['cf_order'];
				$formMandatory = $customfieldData['cf_mandatory'];
				
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formModuleForm = checkParam('ext-moduleform');
					$formUID = checkParam('form_uid');
					$formSection = checkParam('ext-section');
					$formPosition = checkParam('ext-position');
					$formFieldType = checkParam('combo-fieldtype');		
					$formStatus = checkParam('combo-status');
					$formFieldLabel = checkParam('fieldlabel');
					$formTooltip = checkParam('tooltip');
					$formOrder = checkParam('fieldorder');
					$formMandatory = checkParam('combo-mandatory');	
					
					$decForm = encryption(rawurldecode($formModuleForm), $_SESSION['salt'], false);	
					$decSection = encryption(rawurldecode($formSection), $_SESSION['salt'], false);	
					
					if(!validateEmptyField($formModuleForm, 'form', $error)){break;}
					if(!validateEmptyField($formSection, 'form section', $error)){break;}
					if(!validateEmptyField($formPosition, 'field position', $error)){break;}
					if(!validateEmptyField($formFieldType, 'field type', $error)){break;}
					if(!validateEmptyField($formFieldLabel, 'field label', $error)){break;}	
					if($objCustomField->checkOptionLabelExist($formFieldLabel, $decForm, $customfieldData['id'])){
						$error['content'] = "Field label already exist. Please input another field label.";
						break;
					}
					if(!validateEmptyField($formMandatory, 'mandatory field', $error)){break;}	
					if($formOrder != '' && !validateNumericField($formOrder, 'field order', $error)){break;}
					
					
					$newData = array();
					$newData['id'] = $customfieldData['id'];
					$newData['sys_module_id'] = $decForm;
					$newData['module_uid'] = strtolower($formUID);
					$newData['cf_section_id'] = $decSection;
					$newData['cf_position'] = $formPosition;
					$newData['cf_type'] = $formFieldType;
					$newData['cf_status'] = $formStatus;
					$newData['cf_label'] = $formFieldLabel;
					$getnewCode = strtolower(str_replace(' ', '_', $formFieldLabel));
					$newData['cf_code'] = $customfieldData['id']."_".preg_replace("/[^\sa-zA-Z0-9_.-]/", "", $getnewCode);
					$newData['cf_tooltip'] = $formTooltip;
					$newData['cf_order'] = $formOrder;
					$newData['cf_mandatory'] = $formMandatory;
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objCustomField->updateCustomField($newData)){
						insertAuditTrails('oz.system.settings.custom_field.edit', 'update', "", $customfieldData, $newData);
						$encInsertedId = encryption($customfieldData['id'], $_SESSION['salt'], true);
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
					case 'check_duplicate_fieldlabel':
						$label = checkParam('val');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$moduleID = encryption(rawurldecode(checkParam('val2')), $_SESSION['salt'], false);
						if($objCustomField->checkOptionLabelExist($label, $moduleID, $id)){
							$output['message'] = "Field label already exist. Please input another field label.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'list_custom_field':
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
							$condition .= " ORDER BY `sys_module_id`,`cf_order` ASC ";
						}
						
						$_SESSION['oz.system.settings.custom_field.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCustomField->listCustomField($condition, $start, $limit, "*");
						$output['total'] = $objCustomField->getTotalRow();
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
							$condition .= " ORDER BY `cf_content_order`,`cf_content_label` ASC ";
						}
						
						$_SESSION['oz.system.settings.custom_field.list_options_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCustomField->listOptions('', $condition, $start, $limit, "*");
						$output['total'] = $objCustomField->getTotalRow();
						$output['success'] = true;
					break;	
					case 'add_option':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);						
						$newData = array();
						$newData['cf_content_label'] 	= checkParam('label');
						$newData['cf_content_value']	= checkParam('value');
						$newData['cf_content_order']	= checkParam('order');
						$newData['cf_remark'] 			= checkParam('parentType');		
						$newData['cf_id'] 				= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);		
						if($operation == 'new'){ 
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objCustomField->saveOptions($newData)){
								insertAuditTrails('oz.system.settings.custom_field', 'insert', "Custom Field - Add options", $newData);
								$output['success'] = true;
								$output['message'] = 'Option has been successfully created.';
							}
						}else{
							$detailsData = $objCustomField->getOptionsData($id);
							if(empty($detailsData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objCustomField->updateOptions($newData)){
								insertAuditTrails('oz.system.settings.custom_field.edit', 'update', "Custom Field - Edit options", $detailsData, $newData);
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
							$output['message'] = "Selected custom field successfully deleted.";
							foreach($delId AS $value){
								$data = $objCustomField->getOptionsData($value);
								if($objCustomField->deleteOptions($value, $data)){									
									insertAuditTrails('oz.system.settings.custom_field.edit', 'delete', "Custom Field - Delete options", $data);									
								}
							}
						}
					break;
					case 'combo_moduleform':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = ' AND `display_custom_field` = "1"';
						if($query != ''){
							$condition .= " AND (`module_display` LIKE '%".$query."%' OR `uid` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objCustomField->getModuleFormCombo($condition, $start, $limit);
						$output['total_row'] = $objCustomField->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_section':
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
						$output['combo'] = $objCustomField->getSectionCombo($filter_query, $strSorting, $start, $limit);
						$output['total_row'] = $objCustomField->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_custom_field':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCustomField->checkCustomFieldExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Custom field does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected custom field successfully deleted.";
							foreach($delId AS $value){
								$data = $objCustomField->getCustomFieldData($value);
								if($objCustomField->deleteCustomField($value)){									
									insertAuditTrails('oz.system.settings.custom_field.delete', 'delete', "", $data);									
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