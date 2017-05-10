<?php
	require 'search_management.class.php';
	$objSearchManagement = new SearchManagement($GLOBALS['myDB']);
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
		case 'oz.system.settings.search_management.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/search_management/list_search_management.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objSearchManagement->listSearchManagementField();
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
		case 'oz.system.settings.search_management.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objSearchManagement->getCurrencyCodeById($decryptKey);
						if($data != ""){
							$message['content'] = "Currency ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/search_management/new_search_management.php";
			
			$formModule = "";
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formModule = checkParam('ext-modulesearch');				
				if(!validateEmptyField($formModule, 'module name', $error)){break;}
				
				$data = array();
				encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
				$data['id'] = encryption(rawurldecode($formModule), $_SESSION['salt'], false);
				$data['status'] = '1';
				$data['modified_by'] = $_SESSION['user_id'];
				$data['modified_date'] = date("Y-m-d H:i:s");
				
				if($objSearchManagement->updateSearchManagement($data)){
					$encInsertedId = encryption($data['id'], $_SESSION['salt'], true);
					setCookieValue($data['id'], 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.settings.search_management.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save search management. Please try again.";
				}
			}
		break;
		case 'oz.system.settings.search_management.view':
		case 'oz.system.settings.search_management.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objSearchManagement->getModuleNameById($decryptKey);
						if($data != ""){
							$message['content'] = "Search Management for ".$data." module has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objSearchManagement->getModuleNameById($decryptKey);
						if($data != ""){
							$message['content'] = "Search Management for ".$data." module has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
		
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.search_management.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$searchData = $objSearchManagement->getSearchManagementData($decryptKey);
			if(empty($searchData)){
				header("Location: ".getModuleURL('oz.system.settings.search_management.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.search_management.view');
			$allowDelete 	= checkAccess('oz.system.settings.search_management.delete');
			$allowEdit 		= checkAccess('oz.system.settings.search_management.edit');
			
			if(MODULE_UID == 'oz.system.settings.search_management.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			
			$searchfieldsStart = 0;
			$searchfieldsPerPage = 15;
			$searchfieldsFields = $objSearchManagement->listSearchFieldField();
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($searchData["module_name"])?": ".$searchData["module_name"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/search_management/view_search_management.php";
			if($allowEdit){
				$formModule = rawurlencode(encryption($searchData['id'], $_SESSION['salt'], true));
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$newData = array();
					$newData['id'] = $searchData['id'];
					$newData['status'] = '1';
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objSearchManagement->updateSearchManagement($newData)){
						insertAuditTrails('oz.system.settings.search_management.edit', 'update', "", $searchData, $newData);
						$encInsertedId = encryption($searchData['id'], $_SESSION['salt'], true);
						setCookieValue($encInsertedId, 'added_key');
						header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encInsertedId)."&redir=update");
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
					case 'combo_modulesearch':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = " AND `status`=0 ";
						
						if($query != ''){
							$condition .= " AND (`module_name` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objSearchManagement->getSearchManagementCombo($condition, $start, $limit);
						$output['total_row'] = $objSearchManagement->getTotalRow();
						$output['success'] = true;
					break;
					case 'list_search_management':
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
							$condition .= " ORDER BY `module_name` ASC ";
						}
						
						$_SESSION['oz.system.settings.search_management.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objSearchManagement->listSearchManagement($condition, $start, $limit, "*");
						$output['total'] = $objSearchManagement->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_search_management':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objSearchManagement->checkModuleNameExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Search Management does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected search management successfully deleted.";
							foreach($delId AS $value){
								$data = $objSearchManagement->getSearchManagementData($value);
								
								$deldata['id'] = $value;
								$deldata['status'] = '0';
								$deldata['modified_by'] = $_SESSION['user_id'];
								$deldata['modified_date'] = date("Y-m-d H:i:s");
								
								if($objSearchManagement->updateSearchManagement($deldata)){
									insertAuditTrails('oz.system.settings.search_management.delete', 'delete', "", $data);
								}
							}
						}
					break;
					case 'list_search_fields':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND `search_id`='".$parent."' ";
						
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
							$condition .= " ORDER BY `field_name` ASC ";
						}
						
						$_SESSION['oz.system.settings.search_fields.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objSearchManagement->listSearchField($condition, $start, $limit, "*");
						$output['total'] = $objSearchManagement->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_searchfield':						
						$parent		= checkParam('parent');
						$table		= checkParam('search');
						$output['combo'] = $objSearchManagement->listSearchFieldCombo($table);
						$output['total_row'] = $objSearchManagement->getTotalRow();
						$output['success'] = true;
					break;
					case 'add_search_field':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$newData = array();
						$newData['search_id'] = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						if(!$objSearchManagement->checkModuleNameExist($newData['search_id'])){
							$output['message'] = "Search Management data corrupted. Please try again.";
							break;
						}
						$newData['field'] = checkParam('field');						
						$newData['field_name'] = checkParam('field_name');
						
						if($operation == 'new'){
							if($objSearchManagement->validateField('', $newData['search_id'], $newData['field'])){
								$output['message'] = "Selected field already exist. Please select another field.";
								break;
							}
							
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objSearchManagement->saveSearchField($newData)){
								$insertedId = $objSearchManagement->getInsertedId();								
								insertAuditTrails('oz.system.settings.search_management.new', 'insert', "Search Fields", $newData);
								$output['success'] = true;
								$output['message'] = 'Search Field has been successfully created.';								
							}
						}else{
							$detailsData = $objSearchManagement->getSearchFieldData($id);
							if(empty($detailsData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							if($objSearchManagement->validateField($id, $newData['search_id'], $newData['field'])){
								$output['message'] = "Selected field already exist. Please select another field.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objSearchManagement->updateSearchField($newData)){
								insertAuditTrails('oz.system.settings.search_management.edit', 'update', "Search Fields", $detailsData, $newData);
								
								$output['success'] = true;
								$output['message'] = 'Search Field has been successfully updated.';								
							}
						}
					break;
					case 'delete_search_field':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objSearchManagement->checkFieldExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Field does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected field successfully deleted.";
							foreach($delId AS $value){
								$data = $objSearchManagement->getSearchFieldData($value);
								if($objSearchManagement->deleteSearchField($value)){
									insertAuditTrails('oz.system.settings.search_management.delete', 'delete', "Search Fields - Delete field", $data);
								}
							}
						}
					break;
					case 'search_module':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$output['combo'] = $objSearchManagement->getSearchFuncCombo($query, $start, $limit);
						$output['total_row'] = $objSearchManagement->getTotalRow();
						$output['success'] = true;
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