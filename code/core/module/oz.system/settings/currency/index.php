<?php
	require 'currency.class.php';
	$objCurrency = new Currency($GLOBALS['myDB']);
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
		case 'oz.system.settings.currency.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/currency/list_currency.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objCurrency->listCurrencyField();
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
		case 'oz.system.settings.currency.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCurrency->getCurrencyCodeById($decryptKey);
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
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/currency/new_currency.php";
			
			$formCurrencyCode = "";
			$formCurrencySymbol = "";
			$formCurrencyText = "";
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formCurrencyCode = checkParam('code');
				$formCurrencySymbol = checkParam('symbol');
				$formCurrencyText = checkParam('currency_text');
				
				if(!validateEmptyField($formCurrencyCode, 'currency code', $error)){break;}
				if($objCurrency->checkCurrencyCodeExist($formCurrencyCode, '')){
					$error['content'] = "Currency code already exist. Please input another currency code.";
					break;
				}
				if(!validateEmptyField($formCurrencySymbol, 'symbol', $error)){break;}
				if(!validateEmptyField($formCurrencyText, 'text', $error)){break;}
				
				$data = array();
				$data['code'] = $formCurrencyCode;
				$data['symbol'] = $formCurrencySymbol;
				$data['text'] = $formCurrencyText;
				$data['created_by'] = $_SESSION['user_id'];
				$data['created_date'] = date("Y-m-d H:i:s");
				
				if($objCurrency->saveCurrency($data)){
					$insertedId = $objCurrency->getInsertedId();
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.settings.currency.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save currency. Please try again.";
				}
			}
		break;
		case 'oz.system.settings.currency.view':
		case 'oz.system.settings.currency.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCurrency->getCurrencyCodeById($decryptKey);
						if($data != ""){
							$message['content'] = "Currency ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCurrency->getCurrencyCodeById($decryptKey);
						if($data != ""){
							$message['content'] = "Currency ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
		
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.currency.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$currencyData = $objCurrency->getCurrencyData($decryptKey);
			if(empty($currencyData)){
				header("Location: ".getModuleURL('oz.system.settings.currency.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.currency.view');
			$allowDelete 	= checkAccess('oz.system.settings.currency.delete');
			$allowEdit 		= checkAccess('oz.system.settings.currency.edit');
			
			if(MODULE_UID == 'oz.system.settings.currency.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($currencyData["text"])?": ".$currencyData["text"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/currency/view_currency.php";
			if($allowEdit){
				$formCurrencyCode = $currencyData['code'];
				$formCurrencySymbol = $currencyData['symbol'];
				$formCurrencyText = $currencyData['text'];
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formCurrencyCode = checkParam('code');
					$formCurrencySymbol = checkParam('symbol');
					$formCurrencyText = checkParam('currency_text');
					
					if(!validateEmptyField($formCurrencyCode, 'currency code', $error)){break;}
					if($objCurrency->checkCurrencyCodeExist($formCurrencyCode, $currencyData['id'])){
						$error['content'] = "Currency code already exist. Please input another currency code.";
						break;
					}
					if(!validateEmptyField($formCurrencySymbol, 'symbol', $error)){break;}
					if(!validateEmptyField($formCurrencyText, 'text', $error)){break;}
					
					$newData = array();
					$newData['id'] = $currencyData['id'];
					$newData['code'] = $formCurrencyCode;
					$newData['symbol'] = $formCurrencySymbol;
					$newData['text'] = $formCurrencyText;
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objCurrency->updateCurrency($newData)){
						insertAuditTrails('oz.system.settings.currency.edit', 'update', "", $currencyData, $newData);
						$encInsertedId = encryption($currencyData['id'], $_SESSION['salt'], true);
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
					case 'check_duplicate_code':
						$code = checkParam('val');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objCurrency->checkCurrencyCodeExist($code, $id)){
							$output['message'] = "Currency code already exist. Please input another currency code.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'list_currency':
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
							$condition .= " ORDER BY `code` ASC ";
						}
						
						$_SESSION['oz.system.settings.currency.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCurrency->listCurrency($condition, $start, $limit, "*");
						$output['total'] = $objCurrency->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_currency':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCurrency->checkCurrencyExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Currency does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected currency successfully deleted.";
							foreach($delId AS $value){
								$data = $objCurrency->getCurrencyData($value);
								if($objCurrency->deleteCurrency($value)){
									insertAuditTrails('oz.system.settings.currency.delete', 'delete', "", $data);
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