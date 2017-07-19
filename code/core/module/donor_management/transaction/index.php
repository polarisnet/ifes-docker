<?php
	require DIR_MODULE.'/site/donor/donor.class.php';
	require DIR_LIBS.'/user.class.php';
	$objUser = new User($GLOBALS['myDB']);
	$objDonor = new Donor($GLOBALS['myDB']);
	
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
		case 'donor_management.transaction.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = getUserSpecificField($decryptKey, "`username`");
						if(!empty($data)){
							$message['content'] = ucfirst($data['username'])." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/user_management/new_user.php";
			
			$formUsername = "";
			$formEmail = "";
			$formFName = "";
			$formLName = "";
			$formTelephone = "";
			$formLang = "1";
			$formStatus = "1";
			$formAccess = "both";
			$formUserGroup = "1";
			$formSecQuestionId = "1";
			$formSecQuestion = "";
			$formSecAnswer = "";
			$listUserGroup = $objUser->listUserGroup(' ORDER BY `group_name` ASC', 0, 0, false, false);
			$listSecQuestion = $objUser->listSecurityQuestion('', 0, 0, false, false);
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formUsername = checkParam('username');
				$formEmail = checkParam('email');
				$formFName = checkParam('first_name');
				$formLName = checkParam('last_name');
				$formStatus = checkParam('status');
				$formLang = checkParam('language');
				$formPassword = checkParam('password');
				$formRetypePassword = checkParam('retype_password');
				
				if(!validateEmptyField($formUsername, 'username', $error)){break;}
				if($objUser->checkUsernameExist($formUsername)){
					$error['content'] = "Username already exist. Please input another username.";
					break;
				}
				if(!validateEmptyField($formPassword, 'password', $error)){break;}
				if($formPassword != $formRetypePassword){
					$error['content'] = 'Password does not match with retype password. Please input match password.';
					$error['autoclose'] = false;
					break;
				}
				if(!validateEmptyField($formEmail, 'email', $error)){break;}
				if(!filter_var($formEmail, FILTER_VALIDATE_EMAIL)){
					$error['content'] = "Invalid email address. Please input correct email address.";
					break;
				}else if($objUser->checkEmailExist($formEmail, '')){
					$error['content'] = "Email address already exist. Please input another email address.";
				}
				if(!validateEmptyField($formFName, 'first name', $error)){break;}
				if(!validateEmptyField($formLName, 'last name', $error)){break;}
				
				$data = array();
				$data['username'] = strtolower($formUsername);
				$data['salt'] = generateSalt(15);
				$data['uid'] = $objUser->generateUID();
				$data['password'] = hashPassword($data['username'], $formPassword, $data['salt']);
				$data['first_name'] = $formFName;
				$data['last_name'] = $formLName;
				$data['email'] = $formEmail;
				$data['access'] = $formAccess;
				$data['status'] = $formStatus;
				$data['lang_id'] = $formLang;
				$data['created_by'] = $_SESSION['user_id'];
				$data['created_date'] = date("Y-m-d H:i:s");
				
				if($objUser->saveUser($data)){
					$insertedId = $objUser->getInsertedId();
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					$objUser->createFolderUID($data['uid']);
					// $objUser->newFieldPrivileges("user_".$insertedId);
					$data = array();
					$data['user_id'] = $insertedId;
					$data['group_id'] = $formUserGroup;
					$data['created_by'] = $_SESSION['user_id'];
					$data['created_date'] = date("Y-m-d H:i:s");
					if($objUser->saveUserUsergroup($data)){
						insertAuditTrails(MODULE_UID, 'insert join', "", $data);
					}
					setCookieValue($encInsertedId, 'added_key');
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.user_management.users.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save user details. Please try again.";
				}
			}
		break;
case 'donor_management.transaction.view':
//case 'donor_management.transaction.edit':
	if(!empty($_GET)){
		$redirect = checkParam('redir');
		if($redirect == 'new'){
			$decryptKey = getCookieValue('added_key');
			if($decryptKey != ""){
				$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
				$data = getUserSpecificField($decryptKey, "`username`");
				if(!empty($data)){
					$message['content'] = ucfirst($data['username'])." has been successfully created.";
					deleteCookieValue('added_key');
				}
			}
		}else if($redirect == 'update'){
			$decryptKey = getCookieValue('added_key');
			if($decryptKey != ""){
				$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
				$data = getUserSpecificField($decryptKey, "`username`");
				if(!empty($data)){
					$message['content'] = ucfirst($data['username'])." has been successfully updated.";
					deleteCookieValue('added_key');
				}
			}
		}
	}
	$encryptKey = checkParam('key', '', 'get');
	$decryptKey = $encryptKey;
	
	if($decryptKey == ''){
		header("Location: ".getModuleURL('donor_management.transaction.list')."?invalid=1");
		exit;
	}else{
		$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
	}
	
	$arrKeys = explode("-",$decryptKey);
	if(count($arrKeys)!=3) {
		header("Location: ".getModuleURL('donor_management.transaction.list')."?invalid=2");
		exit;
	}
	$transactionData = $objDonor->getGivingHistoryData(" AND p.`id`='".$arrKeys[0]."' AND d.`id`='".$arrKeys[1]."' AND dd.`id`='".$arrKeys[2]."' ");
	//echo "<pre>";print_r($transactionData);echo "</pre>";exit;
	
	$allowView 		= checkAccess('donor_management.transaction.view');
	$allowDelete 	= false;
	$allowEdit 		= false;
	
	if(MODULE_UID == 'donor_management.transaction.view'){
		$mode = "view";
	}else{
		$mode = "edit";
	}
	
	$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
	insertTracker(MODULE_NAME.(isset($userData["first_name"])&&isset($userData["last_name"])?": ".$userData["first_name"]." ".$userData["last_name"]:""));
	$setting['load_tile'] = "0";
	$setting['left_uid'] = MODULE_PARENT_UID;
	$setting['center_dir'] = DIR_ACTIVE_THEME."/donor_management/transaction/view_transaction.php";
	/*if($allowEdit){
		$formEmail = $userData['email'];
		$formFName = $userData['first_name'];
		$formLName = $userData['last_name'];
		$formStatus = $userData['status'];
		$formAccess = $userData['access'];
		
		if(!empty($_POST)){
			$mode = "edit";
			$submitMode = checkParam('submit_mode');
			$formEmail = checkParam('email');
			$formFName = checkParam('first_name');
			$formLName = checkParam('last_name');
			$formStatus = checkParam('status');
			$formAccess = checkParam('access');
			
			if(!validateEmptyField($formEmail, 'email', $error)){break;}
			if(!filter_var($formEmail, FILTER_VALIDATE_EMAIL)){
				$error['content'] = "Invalid email address. Please input correct email address.";
				break;
			}else if($objUser->checkEmailExist($formEmail, $userData['id'])){
				$error['content'] = "Email address already exist. Please input another email address.";
				break;
			}
			if(!validateEmptyField($formFName, 'first name', $error)){break;}
			if(!validateEmptyField($formLName, 'last name', $error)){break;}

			$newData = array();
			$newData['id'] = $userData['id'];
			$newData['first_name'] = $formFName;
			$newData['last_name'] = $formLName;
			$newData['email'] = $formEmail;
			$newData['status'] = $formStatus;
			$newData['modified_by'] = $_SESSION['user_id'];
			$newData['modified_date'] = date("Y-m-d H:i:s");
			
			if($objUser->updateUser($newData)){
				insertAuditTrails('donor_management.transaction.edit', 'update', "", $userData, $newData);
				$encInsertedId = encryption($userData['id'], $_SESSION['salt'], true);
				setCookieValue($encInsertedId, 'added_key');
				header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encryptKey)."&redir=update");
				exit;
			}
		}
	}*/
break;
case 'donor_management.transaction.list':
	$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
	insertTracker(MODULE_NAME);
	$setting['load_tile'] = "0";
	$setting['left_uid'] = MODULE_PARENT_UID;
	$setting['center_dir'] = DIR_ACTIVE_THEME."/donor_management/transaction/list_transaction.php";
	$start = 0;
	$itemsPerPage = 15;
	$fields = $objDonor->listGivingHistoryField();
	//echo "<pre>";print_r($fields);echo "</pre>";exit;
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
		case 'oz.system.user_management.usergroups.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objUser->getUserGroupNameById($decryptKey);
						if($data != ""){
							$message['content'] = ucfirst($data)." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/user_management/new_user_group.php";
			
			$formGroupName = "";
			
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formGroupName = checkParam('groupname');
				
				if(!validateEmptyField($formGroupName, 'group name', $error)){break;}
				if($objUser->checkUserGroupNameExist($formGroupName, '')){
					$error['content'] = "Group name already exist. Please input another group name.";
				}
				
				$data = array();
				$data['group_name'] = $formGroupName;
				$data['created_by'] = $_SESSION['user_id'];
				$data['created_date'] = date("Y-m-d H:i:s");
				
				if($objUser->saveUserGroup($data)){
					$insertedId = $objUser->getInsertedId();
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					$objUser->newFieldPrivileges("group_".$insertedId);
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					setCookieValue($encInsertedId, 'added_key');
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('oz.system.user_management.usergroups.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save user group. Please try again.";
				}
			}
		break;
		case 'oz.system.user_management.usergroups.view':
		case 'oz.system.user_management.usergroups.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objUser->getUserGroupNameById($decryptKey);
						if($data != ""){
							$message['content'] = ucfirst($data)." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objUser->getUserGroupNameById($decryptKey);
						if($data != ""){
							$message['content'] = ucfirst($data)." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
		
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.user_management.usergroups.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$groupData = $objUser->getUserGroupData($decryptKey);
			if(empty($groupData)){
				header("Location: ".getModuleURL('oz.system.user_management.usergroups.list')."?invalid=2");
				exit;
			}
			
			if(MODULE_UID == 'oz.system.user_management.usergroups.view'){
				$mode = "view";
				$allowEdit = checkAccess('oz.system.user_management.usergroups.edit');
			}else{
				$mode = "edit";
				$allowEdit = true;
			}
			if($decryptKey == '1' || $decryptKey == '-1'){
				$allowDelete = false;
			}else{
				$allowDelete = true;
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($groupData["group_name"])?": ".$groupData["group_name"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/user_management/view_user_group.php";		
			
			$recordPermissionStart = 0;
			$recordPermissionPerPage = 15;
			$recordPermissionFields = $objUser->listRecordPermissionField();
			
			if($allowEdit){
				if($groupData['id'] != '1'){
					$groupModule = "group_".$groupData['id'];
					$getMainModule = $objUser->getModulePrivileges($groupModule);
					$formGroupName = $groupData['group_name'];
					$arrSubModule = array();
					foreach($getMainModule AS $key => $mainModule){//main module - level 1
						if(!empty($mainModule['child'])){
							foreach($mainModule['child'] AS $submainModule){ //sub module - level 2
								array_push($arrSubModule, $submainModule['name']);
							}
						}
					}	
				}
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formGroupName = checkParam('groupname');
					
					if(!validateEmptyField($formGroupName, 'group name', $error)){break;}
					if($objUser->checkUserGroupNameExist($formGroupName, $groupData['id'])){
						$error['content'] = "Group name already exist. Please input another group name.";
						break;
					}
					
					$newData = array();
					$newData['id'] = $groupData['id'];
					$newData['group_name'] = $formGroupName;
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");					
					if($groupData['id'] != '1'){
						$privilegesData = array();
						foreach($getMainModule AS $key => $mainModule){//main module - level 1
							$tempMainAccess = false;						
							if(!empty($mainModule['child'])){
								foreach($mainModule['child'] AS $submainModule){ //sub module - level 2
									if($submainModule['uid'] == 'reports.activities' || $submainModule['uid'] == 'reports.customers' || $submainModule['uid'] == 'reports.projects' || $submainModule['uid'] == 'oz.system.logs' ||  $submainModule['uid'] == 'reports.transactions'){
										$accesssubmainModule = checkParam($submainModule['name']);
										$getSubMainModule = $objUser->getSubModulebyUID($submainModule['uid']);
										if(!empty($getSubMainModule)){
											foreach($getSubMainModule AS $strSubModule){
												$privilegesData[$strSubModule] = $accesssubmainModule;
											}
										}
										$privilegesData[$submainModule['uid']] = $accesssubmainModule;
										if($accesssubmainModule == 1){
											$tempMainAccess = true;	
										}
									}else{
										$temp_new 		= checkParam($submainModule['name'].'_new');
										$temp_edit 		= checkParam($submainModule['name'].'_edit');
										$temp_delete 	= checkParam($submainModule['name'].'_delete');
										$temp_view 		= checkParam($submainModule['name'].'_view');
										$temp_list 		= checkParam($submainModule['name'].'_list');
										
										if($submainModule['uid'] == 'oz.system.settings' || $submainModule['uid'] == 'oz.system.user_management'){										
											$getSubMainModule = $objUser->getSubModulebyUID($submainModule['uid']);
											if(!empty($getSubMainModule)){
												foreach($getSubMainModule AS $strSubModule){
													if(strpos($strSubModule,'.new') == true){
														$privilegesData[$strSubModule] = $temp_new;
													}else if(strpos($strSubModule,'.edit') == true){
														$privilegesData[$strSubModule] = $temp_edit;
													}else if(strpos($strSubModule,'.delete') == true){
														$privilegesData[$strSubModule] = $temp_delete;
													}else if(strpos($strSubModule,'.view') == true){
														$privilegesData[$strSubModule] = $temp_view;
													}else if(strpos($strSubModule,'.list') == true){
														$privilegesData[$strSubModule] = $temp_list;
													}else{
														if($temp_new == 1 || $temp_edit == 1 || $temp_delete == 1 || $temp_view == 1 || $temp_list == 1){	
															$privilegesData[$strSubModule] = 1;
														}else{
															$privilegesData[$strSubModule] = 0;
														}
													}
												}
											}
											if($temp_new == 1 || $temp_edit == 1 || $temp_delete == 1 || $temp_view == 1 || $temp_list == 1){	
												$privilegesData[$submainModule['uid']] = 1;
											}else{
												$privilegesData[$submainModule['uid']] = 0;
											}
										}else{	
											$privilegesData[$submainModule['uid'].'.new'] = $temp_new;
											$privilegesData[$submainModule['uid'].'.edit'] = $temp_edit;
											$privilegesData[$submainModule['uid'].'.delete'] = $temp_delete;
											$privilegesData[$submainModule['uid'].'.view'] = $temp_view;
											$privilegesData[$submainModule['uid'].'.list'] = $temp_list;
										}
										if($temp_new == 1 || $temp_edit == 1 || $temp_delete == 1 || $temp_view == 1 || $temp_list == 1){	
											$privilegesData[$submainModule['uid']] = 1;
											$tempMainAccess = true;	
										}else{
											$privilegesData[$submainModule['uid']] = 0;
										}
										
									}
								}
							}
							if($tempMainAccess){
								$privilegesData[$mainModule['uid']] = 1;
							}else{
								$privilegesData[$mainModule['uid']] = 0;
							}
						}
					}
					if($objUser->updateUserGroup($newData)){
						insertAuditTrails('oz.system.user_management.usergroups.edit', 'update', "", $groupData, $newData);
						$encInsertedId = encryption($groupData['id'], $_SESSION['salt'], true);
						setCookieValue($encInsertedId, 'added_key');
						if($objUser->checkPrivilegesField($groupModule)){
							foreach($privilegesData as $moduleUID => $moduleAccess){
								$oldDataAccess = array();
								$oldDataAccess['module_uid'] = $moduleUID;
								$oldDataAccess[$groupModule] = $objUser->getAccessByUID($groupModule, $moduleUID);
								
								$newDataPrivilege = array();
								$newDataPrivilege['module_uid'] = $moduleUID;
								$newDataPrivilege[$groupModule] = $moduleAccess;	
								$newDataPrivilege['modified_by'] = $_SESSION['user_id'];
								$newDataPrivilege['modified_date'] = date("Y-m-d H:i:s");						
								$objUser->updatePrivileges($newDataPrivilege);
								insertAuditTrails('oz.system.user_management.usergroups.edit', 'update privileges', "", $oldDataAccess, $newDataPrivilege);
							}
						}else{
							$error['content'] = "Group field does not exist. Please try again.";
							break;
						}
						header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encryptKey)."&redir=update");
						exit;
					}
				}
			}
		break;
		case 'oz.system.user_management.usergroups.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/user_management/list_usergroup.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objUser->listUserGroupField();
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
		break;*/
		default:
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case 'list_donor_transaction':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition = str_replace(array("LOWER(type)","LOWER(description)","LOWER(currency_code)","amount_only","LOWER(recurring)","LOWER(created_by)"), 
							array("LOWER(p.type)","LOWER(dd.description)","LOWER(d.currency_code)","dd.amount","LOWER(dd.recurring)","LOWER(d.created_by)"), $condition);
						//echo $condition;exit;
						
						$strOrderBy = "";
						if(isset($_GET['sort'])){
							$arrSorting = (array)json_decode($_GET['sort']);
							if(isset($arrSorting[0])) {
								$arrSorting = (array)$arrSorting[0];
								if(isset($arrSorting["property"]) && isset($arrSorting["direction"])) {
									if($arrSorting["property"]=="amount_only") { $arrSorting["property"] = "amount"; }
									$strOrderBy .= " ORDER BY `".$arrSorting["property"]."` ".$arrSorting["direction"]." ";
								}
							}
						}
						if($strOrderBy!="") {
							$condition .= $strOrderBy;
						} else {
							$condition .= " ORDER BY p.`id` DESC ";
						}
						$_SESSION['donor_management.transaction.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['total'] = $objDonor->listGivingHistory($condition, false, false, true);
						if(strlen($start) != 0 && strlen($limit) != 0){
							$condition .= " LIMIT ".$start.", ".$limit;
						}
						//$selection = "`id`, `username`, `email`, `uid`, `first_name`, `last_name`, `access`, `status`, `created_by`, `created_date`, `modified_by`, `modified_date`";
						$output['table'] = $objDonor->listGivingHistory($condition, false, false, false);
						//echo "<pre>";print_r($output);echo "</pre>";exit;
						$output['success'] = true;
					break;
					
					
					
					
					
					
					
					
					case 'check_duplicate_user':
						$username = checkParam('username');
						if($objUser->checkUsernameExist($username)){
							$output['message'] = "Username already exist. Please input another username.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'check_duplicate_email':
						$email = checkParam('email');
						$userId = encryption(rawurldecode(checkParam('user_id')), $_SESSION['salt'], false);
						if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
							$output['message'] = "Invalid email address. Please input correct email address.";
						}else if($objUser->checkEmailExist($email, $userId)){
							$output['message'] = "Email address already exist. Please input another email address.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'check_duplicate_usergroup':
						$groupName = checkParam('val');
						$groupId = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objUser->checkUserGroupNameExist($groupName, $groupId)){
							$output['message'] = "Group name already exist. Please input another group name.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'list_user':
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
							$condition .= " ORDER BY `id` DESC ";
						}
						
						$_SESSION['oz.system.user_management.users.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$selection = "`id`, `username`, `email`, `uid`, `first_name`, `last_name`, `access`, `status`, `created_by`, `created_date`, `modified_by`, `modified_date`";
						$output['table'] = $objUser->listUser($condition, $start, $limit, $selection);
						$output['total'] = $objUser->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_users':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($value == '1' || $value == '-1'){
								$output['message'] = "Some of the selected user is protected. Please contact administrator for further details.";
								break 2;
							}
							if($objUser->checkUserExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Username does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected users successfully deleted.";
							foreach($delId AS $value){
								$uid = getUserUID($value);
								$data = $objUser->getUserData($value);
								if($objUser->deleteUser($value)){
									insertAuditTrails('oz.system.user_management.users.delete', 'delete', "", $data);
									$data = getUserGroupByUserId($value);
									$data['user_id'] = $value;
									insertAuditTrails('oz.system.user_management.users.delete', 'delete', "", $data);
									$objUser->deleteUserUsergroupByUserId($value);
									if($uid != ""){$objUser->removeFolderUID($uid);}
									// if($objUser->checkPrivilegesField("user_".$value)){
										// $objUser->dropFieldPrivileges("user_".$value);
									// }
								}
							}
						}
					break;
					case 'list_usergroup':
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
							$condition .= " ORDER BY `id` DESC ";
						}
						
						$_SESSION['oz.system.user_management.usergroups.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objUser->listUserGroup($condition, $start, $limit, "*");
						$output['total'] = $objUser->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_usergroups':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($value == '1' || $value == '-1' || $value == '2'){
								$output['message'] = "Some of the selected user group is protected. Please contact administrator for further details.";
								break 2;
							}
							if($objUser->checkUserGroupExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "User group does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected user groups successfully deleted.";
							foreach($delId AS $value){
								$data = $objUser->getUserGroupData($value);
								if($objUser->deleteUserGroup($value)){
									insertAuditTrails('oz.system.user_management.usergroups.delete', 'delete', "", $data);
									$objUser->reassignDeleteUserGroup($value);
									$objUser->dropFieldPrivileges("group_".$value);
								}
							}
						}
					break;
					case 'combo_module_record_permission':
						$start		= checkParam('start');
						$limit		= checkParam('limit');
						$query		= checkParam('query');
						$condition	= '';
						if($query != ''){
							$condition .= " AND (`uid` LIKE '%".$query."%' OR `module_display` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objUser->getModuleRecordPermissionCombo($condition, $start, $limit);
						$output['total_row'] = $objUser->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_module_field':
						$start		= checkParam('start');
						$limit		= checkParam('limit');
						$query		= checkParam('query');
						$conditions = checkParam('conditions');
						$strSorting = '';
						if($conditions != ''){
							$strSorting .= " AND `module_uid` = '".$conditions."' ";
						}else{
							$strSorting .= " AND `module_uid` = '' ";
						}
						if($query != ''){
							$strSorting .= " AND (`field` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objUser->getModuleFieldCombo($strSorting, $start, $limit);
						$output['total_row'] = $objUser->getTotalRow();
						$output['success'] = true;
					break;					
					case 'list_record_permission':
						$start 		= checkParam('start');
						$limit 		= checkParam('limit');
						$parent 	= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter 	= array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND `group_id`='".$parent."' ";
						$output['table'] = $objUser->listRecordPermission($condition, $start, $limit, "*");
						$output['total'] = $objUser->getTotalRow();
						$output['success'] = true;
					break;
					case 'add_record_permission':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$newData = array();
						$newData['group_id'] = encryption(rawurldecode(checkParam('group_id')), $_SESSION['salt'], false);
						$newData['module_uid'] = checkParam('module_uid');
						$newData['module_display'] = checkParam('module_display');
						$newData['field_id'] = checkParam('field_id');
						$newData['field'] = checkParam('field');
						$newData['view'] = checkParam('view');
						
						if($operation == 'new'){
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objUser->saveRecordPermission($newData)){
								$insertedId = $objUser->getInsertedId();								
								insertAuditTrails('oz.system.user_management.usergroups.recordpermission', 'insert', "Records Permission", $newData);
								$output['success'] = true;
								$output['message'] = 'Records Permission has been successfully created.';								
							}
						}else{
							$detailsData = $objUser->getRelatedCustomersData('customers_record_permission', $id);
							if(empty($detailsData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							if($objUser->validateRelatedCustomer($id, $newData['mainCust_id'], $newData['customer_id'])){
								$output['message'] = "Selected customer already exist in the relationship. Please select another customer.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objUser->updateRecordPermission($newData)){
								insertAuditTrails('oz.system.user_management.usergroups.recordpermission', 'update', "Related Customers", $detailsData, $newData);
								$output['success'] = true;
								$output['message'] = 'Related Customer has been successfully updated.';								
							}
						}
					break;
					case 'delete_record_permission':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objUser->checkRecordPermissionExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Record Permission does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected permission successfully deleted.";
							$parentId = "";
							foreach($delId AS $value){
								$data = $objUser->getRecordPermissionData($value);
								if($objUser->deleteRecordPermission($value)){									
									insertAuditTrails('oz.system.user_management.usergroups.recordpermission', 'delete', "Record Permission", $data);
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