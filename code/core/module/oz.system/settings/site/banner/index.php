<?php
	require 'banner.class.php';
	$objBanner = new Banner($GLOBALS['myDB']);
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	$setting = array(
		"title" 			=> SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" 		=> "",
		"meta_description" 	=> "",
		"extjs" 			=> "1",
		"header" 			=> "1",
		"header_dir" 		=> DIR_ACTIVE_THEME."/header.php",
		"left" 				=> "1",
		"left_dir" 			=> DIR_ACTIVE_THEME."/leftbar.php",
		"left_width" 		=> "180",
		"left_maximize" 	=> getCookieValue('toggle_leftbar'),
		"left_uid" 			=> '',
		"left_module" 		=> MODULE_NAME,
		"center_dir" 		=> DIR_ACTIVE_THEME."/oz.system/blank.php",
		"right" 			=> "0",
		"right_dir" 		=> "",
		"footer" 			=> "1",
		"footer_dir" 		=> DIR_ACTIVE_THEME."/footer.php",
		"widgets" 			=> "0",
		"current" 			=> "",
		"load_tile" 		=> "1",
		"load_breadcrumb" 	=> "1"
	);
	$access 	= checkAccess(MODULE_UID);
	$actionData = $GLOBALS['seo']->getActionURL();
	$action 	= array_shift($actionData);
	$error 		= array('type' => 'error', 'title' => 'Error', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$warning 	= array('type' => 'warning', 'title' => 'Warning', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$message 	= array('type' => 'message', 'title' => 'Message', 'content' => '', 'position' => 'right', 'autoclose' => false);	
	$markError 	= array();
	$HTTP_AJAX 	= HTTP_ACTIVE_MODULE.'/ajax';
	$bannerPath = HTTP_MEDIA.'/site-image/banner/';
	switch(MODULE_UID){
		case 'oz.system.settings.site.banner.list':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/site/banner/list_banner.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objBanner->listBannerField();			
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
		case 'oz.system.settings.site.banner.new':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objBanner->getBannerPathById($decryptKey);
						if($data != ""){
							$message['content'] = "Banner ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/site/banner/new_banner.php";
						
			$formImage			= "";
			$formCaption		= "";
			$formEffect 		= "";
			$formOrder			= 0;
			$formType			= "";
			$formStatus			= "";
			$formRemarks		= "";
						
			if(!empty($_POST)){
				$submitMode     = checkParam('submit_mode');
				$getImage 		= $_FILES['image'];
				$formImage		= $getImage['name'];
				$formCaption    = checkParam('caption');
				$formEffect     = checkParam('imageEffect');
				$formOrder      = checkParam('order');
				$formType		= checkParam('type');
				$formStatus		= checkParam('status');
				$formRemarks    = checkParam('remarks');	
				
				if(!validateEmptyField($formImage, 'image', $error)){break;}
				if($formOrder != '' && !validateNumericField($formOrder, 'field order', $error)){break;}
						
				$data = array();
				$data['path']			= strtolower($formImage);
				$data['caption']		= $formCaption;
				$data['effect']			= $formEffect;
				$data['order']			= $formOrder;
				$data['type']			= $formType;
				$data['status']			= $formStatus;
				$data['remarks']		= $formRemarks;				
				$data['created_by']		= $_SESSION['user_id'];
				$data['created_date']	= date("Y-m-d H:i:s");
				
				if($objBanner->saveUploadImg($_FILES['image'])){
					if($objBanner->saveBanner($data)){					
						$insertedId = $objBanner->getInsertedId();					
						$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
						setCookieValue($encInsertedId, 'added_key');
						insertAuditTrails(MODULE_UID, 'insert', "", $data);
						if($submitMode == 'new'){
							header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
							exit;
						}else{
							header("Location: ".getModuleURL('oz.system.settings.site.banner.view')."?redir=new&key=".rawurlencode($encInsertedId));
							exit;
						}
					}else{
						$error['content'] = "Cannot save banner. Please try again.";
					}
				}else{
					$error['content'] = "Cannot save banner. Please try again.";
				}
			}
		break;
		case 'oz.system.settings.site.banner.view':
		case 'oz.system.settings.site.banner.edit':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objBanner->getBannerPathById($decryptKey);
						if($data != ""){
							$message['content'] = "Banner ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objBanner->getBannerPathById($decryptKey);
						if($data != ""){
							$message['content'] = "Banner ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
						
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			if($decryptKey == ''){
				header("Location: ".getModuleURL('oz.system.settings.site.banner.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$bannerData = $objBanner->getBannerData($decryptKey);
			if(empty($bannerData)){
				header("Location: ".getModuleURL('oz.system.settings.site.banner.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('oz.system.settings.site.banner.view');
			$allowDelete 	= checkAccess('oz.system.settings.site.banner.delete');
			$allowEdit 		= checkAccess('oz.system.settings.site.banner.edit');
			
			if(MODULE_UID == 'oz.system.settings.site.banner.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($bannerData["path"])?": ".$bannerData["path"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/site/banner/view_banner.php";
			
			if($allowEdit){
				$formImage 		= $bannerData['path'];
				$formCaption 	= $bannerData['caption'];
				$formEffect 	= $bannerData['effect'];
				$formOrder 		= $bannerData['order'];
				$formRemarks 	= $bannerData['remarks'];
				$formStatus		= $bannerData['status'];
				$formType		= $bannerData['type'];
				
				if(!empty($_POST)){
					$mode 			= "edit";
					$submitMode 	= checkParam('submit_mode');
					$getImage 		= $_FILES['image'];
					$formImage		= $getImage['name'];
					$formCaption    = checkParam('caption');
					$formEffect     = checkParam('imageEffect');
					$formOrder      = checkParam('order');
					$formType		= checkParam('type');
					$formStatus		= checkParam('status');
					$formRemarks    = checkParam('remarks');	
					if($submitMode != '' && $submitMode == 'reupload'){
						if(!validateEmptyField($formImage, 'image', $error)){break;}	
					}							
					if($formOrder != '' && !validateNumericField($formOrder, 'field order', $error)){break;}
										
					$newData 					= array();
					$newData['id'] 				= $bannerData['id'];
					$newData['caption']			= $formCaption;
					$newData['effect']			= $formEffect;
					$newData['order']			= $formOrder;
					$newData['type']			= $formType;
					$newData['status']			= $formStatus;
					$newData['remarks']			= $formRemarks;	
					$newData['modified_by'] 	= $_SESSION['user_id'];
					$newData['modified_date'] 	= date("Y-m-d H:i:s");
					
					if($submitMode != '' && $submitMode == 'reupload'){
						$newData['path']		= strtolower($formImage);
						$objBanner->removePhysicalImg($bannerData['id']);	
						$objBanner->saveUploadImg($_FILES['image']);
					}
					if($objBanner->updateBanner($newData)){
						insertAuditTrails('oz.system.settings.site.banner.edit', 'update', "", $bannerData, $newData);
						$encInsertedId = encryption($bannerData['id'], $_SESSION['salt'], true);
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
					case 'list_banner':
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
							$condition .= " ORDER BY `order` ASC ";
						}
						
						$_SESSION['oz.system.settings.site.banner.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objBanner->listBanner($condition, $start, $limit, "*");
						$output['total'] = $objBanner->getTotalRow();
						$output['success'] = true;
					break;					
					case 'delete_banner':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objBanner->checkBannerExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Banner does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected banner successfully deleted.";
							foreach($delId AS $value){
								$data = $objBanner->getBannerData($value);
								if($objBanner->deleteBanner($value)){									
									insertAuditTrails('oz.system.settings.site.banner.delete', 'delete', "", $data);									
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