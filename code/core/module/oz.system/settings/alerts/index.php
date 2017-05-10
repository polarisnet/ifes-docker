<?php
	require DIR_LIBS.'/alert.class.php';
	$objAlert = new Alert($GLOBALS['myDB']);
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
		case 'oz.system.settings.alerts.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/alerts/list_alert.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objAlert->listAlertField();
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
		case 'oz.system.settings.alerts.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objAlert->getHeaderById($decryptKey);
						if($data != ""){
							$message['content'] = "Alert ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/alerts/new_alert.php";
			
			$formHeader = "";
			$formType = "pin";
			$formContent = "";
			$formTable = "dashboard";
			$formTableId = array("all");
			$formStartDate = date("d/m/Y");
			$formEndDate = date("d/m/Y", strtotime("+7 days"));
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formHeader = checkParam('header');
				$formType = checkParam('type');
				$formContent = checkParam('content', '', '', array('css' => false));
				$formTable = checkParam('ext-table');
				$formStartDate = checkParam('start_date');
				$formEndDate = checkParam('end_date');
				$targetMode = checkParam('target_mode');
				if($targetMode == '0'){
					$formTableId = array("all");
				}else{
					$tmpHideTarget = checkParam('hide_related_target');
					$formTableId = explode(',', $tmpHideTarget);
					if(empty($formTableId)){
						$formTableId = array("all");
					}
				}
				if(!validateEmptyField($formHeader, 'header', $error)){break;}
				if(!validateEmptyField($formContent, 'content', $error)){break;}
				if(convertDate($formStartDate) >= convertDate($formEndDate)){
					$error['content'] = "Alert start date should be earlier than end date.";
					break;
				}
				
				$data = array();
				$data['header'] = $formHeader;
				$data['table'] = $formTable;
				$data['type'] = $formType;
				$data['content'] = $formContent;
				$data['target'] = implode(',', $formTableId);
				$data['start_date'] = convertDate($formStartDate);
				$data['end_date'] = convertDate($formEndDate);
				$data['created_by'] = $_SESSION['user_id'];
				$data['created_date'] = date("Y-m-d H:i:s");
				
				if($objAlert->saveAlert($data)){
					$insertedId = $objAlert->getInsertedId();
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.settings.alerts.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save alert. Please try again.";
				}
			}
		break;
		case 'oz.system.settings.alerts.view':
		case 'oz.system.settings.alerts.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objAlert->getHeaderById($decryptKey);
						if($data != ""){
							$message['content'] = "Alert ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objAlert->getHeaderById($decryptKey);
						if($data != ""){
							$message['content'] = "Alert ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
		
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.alerts.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$alertData = $objAlert->getAlertData($decryptKey);
			if(empty($alertData)){
				header("Location: ".getModuleURL('oz.system.settings.alerts.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.alerts.view');
			$allowDelete 	= checkAccess('oz.system.settings.alerts.delete');
			$allowEdit 		= checkAccess('oz.system.settings.alerts.edit');
			
			if(MODULE_UID == 'oz.system.settings.alerts.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($currencyData["text"])?": ".$currencyData["text"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/alerts/view_alert.php";
			if($allowEdit){
				$formHeader = $alertData['header'];
				$formType = $alertData['type'];
				$formContent = $alertData['content'];
				$formTable = $alertData['table'];
				$formTableId = explode(',', $alertData['target']);
				if($alertData['start_date'] != '0000-00-00 00:00:00' && $alertData['start_date'] != ''){
					$formStartDate = convertDate($alertData['start_date'], 'Y-m-d H:i:s', 'd/m/Y');
				}else{
					$formStartDate = '';
				}
				if($alertData['end_date'] != '0000-00-00 00:00:00' && $alertData['end_date'] != ''){
					$formEndDate = convertDate($alertData['end_date'], 'Y-m-d H:i:s', 'd/m/Y');
				}else{
					$formEndDate = '';
				}
				
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formHeader = checkParam('header');
					$formType = checkParam('type');
					$formContent = checkParam('content', '', '', array('css' => false));
					$formTable = checkParam('ext-table');
					$formStartDate = checkParam('start_date');
					$formEndDate = checkParam('end_date');
					$targetMode = checkParam('target_mode');
					if($targetMode == '0'){
						$formTableId = array("all");
					}else{
						$tmpHideTarget = checkParam('hide_related_target');
						$formTableId = explode(',', $tmpHideTarget);
						if(empty($formTableId)){
							$formTableId = array("all");
						}
					}
					if(!validateEmptyField($formHeader, 'header', $error)){break;}
					if(!validateEmptyField($formContent, 'content', $error)){break;}
					if(convertDate($formStartDate) >= convertDate($formEndDate)){
						$error['content'] = "Alert start date should be earlier than end date.";
						break;
					}
					
					$newData = array();
					$newData['id'] = $alertData['id'];
					$newData['header'] = $formHeader;
					$newData['table'] = $formTable;
					$newData['type'] = $formType;
					$newData['content'] = $formContent;
					$newData['target'] = implode(',', $formTableId);
					$newData['start_date'] = convertDate($formStartDate);
					$newData['end_date'] = convertDate($formEndDate);
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objAlert->updateAlert($newData)){
						insertAuditTrails('oz.system.settings.alerts.edit', 'update', "", $alertData, $newData);
						$encInsertedId = encryption($alertData['id'], $_SESSION['salt'], true);
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
					case 'list_alert':
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
							$condition .= " ORDER BY `header` ASC ";
						}
						
						$_SESSION['oz.system.settings.alerts.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objAlert->listAlert($condition, $start, $limit, "*");
						$output['total'] = $objAlert->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_alert':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objAlert->checkAlertExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Alert does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected alert successfully deleted.";
							foreach($delId AS $value){
								$data = $objAlert->getAlertData($value);
								if($objAlert->deleteAlert($value)){
									insertAuditTrails('oz.system.settings.alerts.delete', 'delete', "", $data);
								}
							}
						}
					break;
					case 'combo_table':
						$output['combo'] = $objAlert->getTableCombo();
						$output['total_row'] = $objAlert->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_related_target':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$table = checkParam('table');
						$intact = checkParam('intact');
						$intactDone = checkParam('intact_done');
						$strcondition = checkParam('conditions');
						if($table != 'dashboard'){
							$tableCombo = $objAlert->getTableCombo();
							$targetTable = "";
							$targetColumn = array();
							foreach($tableCombo AS $key => $tableData){
								if($tableData['table'] == $table){
									$targetTable = $tableData['table'];
									$targetColumn = explode(",", $tableData['column']);
									foreach($targetColumn AS $tK => $tV){
										$targetColumn[$tK] = trim($tV);
									}
									break;
								}
							}
							
							$condition = '';
							if($intactDone == 0 && $intact != ''){
								$condition .= " AND id IN (".stripslashes($intact).")";
								$start = "";
								$limit = "";
							}else{
								if($strcondition != ''){
									$arrcondition = isset($strcondition) ? explode(',', $strcondition) : false;						
									if ($arrcondition) {
										$condition .= "AND (";
										$countcond = 0;
										$totalCount = count($arrcondition);
										foreach($arrcondition AS $value){
											$countcond ++;
											$condition .= " `id`='".$value."' ";
											if($countcond < $totalCount){
												$condition .= " OR ";
											}
										}
										$condition .= ")";
									}	
								}
								if($query != ''){
									$tmpCondition = "";
									foreach($targetColumn AS $column){
										if($tmpCondition != ""){$tmpCondition .= " OR ";}
										$tmpCondition .= "`".trim($column)."` LIKE '%".$query."%'";
									}
									if($tmpCondition != ""){
										$condition .= " AND (".$tmpCondition.") ";
									}
								}
							}
							$output['combo'] = $objAlert->getTargetCombo($targetTable, $targetColumn, $condition, $start, $limit);
							$output['total_row'] = $objAlert->getTotalRow();
						}else{
							$output['combo'] = array();
							$output['total_row'] = 0;
						}
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