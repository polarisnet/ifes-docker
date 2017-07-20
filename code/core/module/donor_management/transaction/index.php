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
			$setting['center_dir'] = DIR_ACTIVE_THEME."/donor_management/transaction/new_transaction.php";
			
			$formEncID = "";
			$formUsername = "";
			$formName = "";
			$formFName = "";
			$formLName = "";
			$formAddress = "";
			$formAddress1 = "";
			$formAddress2 = "";
			$formCity = "";
			$formState = "";
			$formZipcode = "";
			$formCountry = "";
			$formTelephone = "";
			$formRegion = "";
			$formEmail = "";

			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$encid = checkParam('ext-donor');
				$id = encryption(rawurldecode($encid), $_SESSION['salt'], false);
				if(!is_numeric($id)&&checkParam('current_id')!="") { $encid = checkParam('current_id'); $id = encryption(rawurldecode($encid), $_SESSION['salt'], false); }
				if(is_numeric($id)) {
					$arrUserData = $objUser->getUserData($id);
					$formEncID = $encid;
					$formUsername = $arrUserData["username"];
					$formName = $arrUserData["first_name"]." ".$arrUserData["last_name"];
					$formFName = $arrUserData["first_name"];
					$formLName = $arrUserData["last_name"];
					$formAddress = $arrUserData["billing_address1"]." ".$arrUserData["billing_address2"]." ".$arrUserData["billing_city"]." ".$arrUserData["billing_state"]." ".$arrUserData["billing_zipcode"]." ".$arrUserData["billing_country"];
					$formAddress1 = $arrUserData["billing_address1"];
					$formAddress2 = $arrUserData["billing_address2"];
					$formCity = $arrUserData["billing_city"];
					$formState = $arrUserData["billing_state"];
					$formZipcode = $arrUserData["billing_zipcode"];
					$formCountry = $arrUserData["billing_country"];
					$formTelephone = $arrUserData["phone"];
					$formRegion = $arrUserData["region"];
					$formEmail = $arrUserData["email"];
					$_SESSION["login"]["give_behalf"] = true;
					$_SESSION["login"]["give_behalf_id"] = $id;
					$_SESSION["login"]["give_behalf_username"] = $formUsername;
					?>
                    <script type="text/javascript">window.open("<?php echo getModuleURL('giving');?>");</script>
                    <?php
				} else {
					$error['content'] = "Cannot proceed to give. Please try again.";
				}
				
				//header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
				//exit;
			}
		break;
		case 'donor_management.transaction.view':
		//case 'donor_management.transaction.edit':
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
			$transactionData = $objDonor->getAdminGivingHistoryData(" AND p.`id`='".$arrKeys[0]."' AND d.`id`='".$arrKeys[1]."' AND dd.`id`='".$arrKeys[2]."' ");
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
			insertTracker(MODULE_NAME.(isset($transactionData["transaction_no"])?": ".$transactionData["transaction_no"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/donor_management/transaction/view_transaction.php";
		break;
		case 'donor_management.transaction.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/donor_management/transaction/list_transaction.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objDonor->listAdminGivingHistoryField();
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
						$output['total'] = $objDonor->listAdminGivingHistory($condition, false, false, true);
						if(strlen($start) != 0 && strlen($limit) != 0){
							$condition .= " LIMIT ".$start.", ".$limit;
						}
						//$selection = "`id`, `username`, `email`, `uid`, `first_name`, `last_name`, `access`, `status`, `created_by`, `created_date`, `modified_by`, `modified_date`";
						$output['table'] = $objDonor->listAdminGivingHistory($condition, false, false, false);
						//echo "<pre>";print_r($output);echo "</pre>";exit;
						$output['success'] = true;
					break;
					case 'combo_donor':
						$start		= checkParam('start');
						$limit		= checkParam('limit');
						$query		= checkParam('query');
						$condition	= " AND `access` IN ('both','fo')";
						if($query != ''){
							$condition .= " AND (`first_name` LIKE '%".$query."%' OR `last_name` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objUser->getUserCombo($condition, $start, $limit);
						$output['total_row'] = $objUser->getTotalRow();
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