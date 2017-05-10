<?php
	require 'country.class.php';
	$objCountry = new Country($GLOBALS['myDB']);
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
		case 'oz.system.settings.country.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/country/list_country.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objCountry->listCountryField();
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
		case 'oz.system.settings.country.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCountry->getCountryNameById($decryptKey);
						if($data != ""){
							$message['content'] = "Country ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/country/new_country.php";
			
			$formCountryName = "";
			$formCountryISO = "";
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formCountryName = checkParam('name');
				$formCountryISO = checkParam('iso');
				
				if(!validateEmptyField($formCountryName, 'country name', $error)){break;}
				if($objCountry->checkCountryNameExist($formCountryName, '')){
					$error['content'] = "Country name already exist. Please input another country name.";
					break;
				}
				if(!validateEmptyField($formCountryISO, 'ISO code', $error)){break;}
				
				$data = array();
				$data['name'] = $formCountryName;
				$data['iso'] = $formCountryISO;
				$data['created_by'] = $_SESSION['user_id'];
				$data['created_date'] = date("Y-m-d H:i:s");
				
				if($objCountry->saveCountry($data)){
					$insertedId = $objCountry->getInsertedId();
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.settings.country.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save country. Please try again.";
				}
			}
		break;
		case 'oz.system.settings.country.view':
		case 'oz.system.settings.country.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCountry->getCountryNameById($decryptKey);
						if($data != ""){
							$message['content'] = "Country ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCountry->getCountryNameById($decryptKey);
						if($data != ""){
							$message['content'] = "Country ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
		
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.country.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$countryData = $objCountry->getCountryData($decryptKey);
			if(empty($countryData)){
				header("Location: ".getModuleURL('oz.system.settings.country.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.country.view');
			$allowDelete 	= checkAccess('oz.system.settings.country.delete');
			$allowEdit 		= checkAccess('oz.system.settings.country.edit');
			
			if(MODULE_UID == 'oz.system.settings.country.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($countryData["name"])?": ".$countryData["name"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/country/view_country.php";
			if($allowEdit){
				$formCountryName = $countryData['name'];
				$formCountryISO = $countryData['iso'];
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formCountryName = checkParam('name');
					$formCountryISO = checkParam('iso');
					
					if(!validateEmptyField($formCountryName, 'country name', $error)){break;}
					if($objCountry->checkCountryNameExist($formCountryName, $countryData['id'])){
						$error['content'] = "Country name already exist. Please input another country name.";
						break;
					}
					if(!validateEmptyField($formCountryISO, 'ISO code', $error)){break;}
					
					$newData = array();
					$newData['id'] = $countryData['id'];
					$newData['name'] = $formCountryName;
					$newData['iso'] = $formCountryISO;
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objCountry->updateCountry($newData)){
						insertAuditTrails('oz.system.settings.country.edit', 'update', "", $countryData, $newData);
						$encInsertedId = encryption($countryData['id'], $_SESSION['salt'], true);
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
					case 'check_duplicate_name':
						$code = checkParam('val');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objCountry->checkCountryNameExist($code, $id)){
							$output['message'] = "Country name already exist. Please input another country name.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'list_country':
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
							$condition .= " ORDER BY `name` ASC ";
						}
						
						$_SESSION['oz.system.settings.country.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCountry->listCountry($condition, $start, $limit, "*");
						$output['total'] = $objCountry->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_country':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCountry->checkCountryExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Country does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected country successfully deleted.";
							foreach($delId AS $value){
								$data = $objCountry->getCountryData($value);
								if($objCountry->deleteCountry($value)){
									insertAuditTrails('oz.system.settings.country.delete', 'delete', "", $data);
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