<?php
	require 'printing.class.php';
	$objPrinting = new Printing($GLOBALS['myDB']);
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
		case 'oz.system.settings.printing.image_upload':
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/oz.system/settings/transactions/printing/list_image_upload.php";
			$start = 0;
			$itemsPerPage = 15;
			$fields = $objPrinting->listPrintingField();
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
					case 'check_duplicate_code':
						$code = checkParam('val');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objPrinting->checkPrintingCodeExist($code, $id)){
							$output['message'] = "Printing code already exist. Please input another printing.image_upload code.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'list_image_upload':
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
							$condition .= " ORDER BY `item_order` ASC ";
						}
						
						$_SESSION['oz.system.settings.printing.image_upload'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objPrinting->listPrinting($condition, $start, $limit, "*");
						$output['total'] = $objPrinting->getTotalRow();
						$output['success'] = true;
					break;
					case 'upload_img':
						$data = array();
						$data['caption'] = checkParam('img_caption');
						$data['item_order'] = checkParam('img_order');
						$data['created_by'] = $_SESSION['user_id'];
						$data['created_date'] = date("Y-m-d H:i:s");
						$output = $objPrinting->saveUploadImg($_FILES['img_image']);
						if($output['success'] == true){
							$data['path'] = "/".$output['filename'];
							$output['success'] = $objPrinting->saveImg($data);
							
							if(!$output['success']){
								$output['message'] = "Saving uploaded image failed. Please try again.";
							}
						}
					break;
					case 'submit_item_img':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$newData = array();
						$newData['item_order'] = checkParam('item_order');
						$newData['caption'] = checkParam('caption');
						if($operation == 'edit'){
							if(!$objPrinting->checkImgExist($id)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objPrinting->updateImg($newData)){
								$imgData = $objPrinting->getImgData($id);
							
								$output['success'] = true;
								$output['message'] = 'Item image has been successfully updated.';
							}
						}
					break;
					case 'delete_item_img':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objPrinting->checkImgExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Item image does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							foreach($delId AS $value){
								$objPrinting->deleteImg($value);
							}
                                                        $output['success'] = true;
							$output['message'] = "Selected item images successfully deleted.";
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