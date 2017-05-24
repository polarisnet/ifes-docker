<?php 
	$breadCrumbData 		= getBreadCrumbData(MODULE_UID, "/");
	
	$setting = array(
		"title" 			=> SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" 		=> "",
		"meta_description" 	=> "",
		"extjs" 			=> "1",
		"header" 			=> "1",
		"header_dir"		=> DIR_ACTIVE_THEME."/header.php",
		"left" 				=> "1",
		"left_dir" 			=> DIR_ACTIVE_THEME."/leftbar.php",
		"left_width" 		=> "180",
		"left_maximize" 	=> getCookieValue('toggle_leftbar'),
		"left_uid" 			=> '',
		"left_module" 		=> MODULE_NAME,
		"center_dir" 		=> DIR_ACTIVE_THEME."/blank.php",
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
	switch(MODULE_UID){
		case 'customer.signup':
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCustomer->getCustomerNoById($decryptKey);
						if($data != ""){
							$message['content'] = "Donor ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME);
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/donor/signup.php";
			
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				$formName = checkParam('name');
				$formCustNo = checkParam('custno');
				
				//TODO: check param values
				
				//Validation for Server Side
				//TODO: add validation

				if($error['content']){break;}
				//End of Validation 

				$data = array();
				$data['cust_no'] = $formCustNo;
				$data['name'] = $formName;
				$data['roc_no'] = $formRocNo;
				$data['attention'] = $formAttention;
				$data['attention1'] = $formAttention1;
				if($formInvoiceCountry != ''){
					$data['invoice_country_id'] = encryption(rawurldecode($formInvoiceCountry), $_SESSION['salt'], false);
					$data['invoice_country'] = $objCountry->getCountryNameById($data['invoice_country_id']);
				}else{
					$data['invoice_country_id'] = 0;
					$data['invoice_country'] = '';
				}
				$data['invoice_state'] = $formInvoiceState;
				$data['invoice_city'] = $formInvoiceCity;
				$data['invoice_address'] = $formInvoiceAddress;
				$data['invoice_postcode'] = $formInvoicePostcode;
				if($formDeliveryCountry != ''){
					$data['delivery_country_id'] = encryption(rawurldecode($formDeliveryCountry), $_SESSION['salt'], false);
					$data['delivery_country'] = $objCountry->getCountryNameById($data['delivery_country_id']);
				}else{
					$data['delivery_country_id'] = 0;
					$data['delivery_country'] = '';
				}
				$data['delivery_state'] = $formDeliveryState;
				$data['delivery_city'] = $formDeliveryCity;
				$data['delivery_address'] = $formDeliveryAddress;
				$data['delivery_postcode'] = $formDeliveryPostcode;
				$data['email'] = $formEmail;
                                $data['channel'] = $formChannel;
				$data['team'] = $formTeam;
				if($formAgent != ''){
					$data['agent_id'] = encryption(rawurldecode($formAgent), $_SESSION['salt'], false);
					$data['agent_code'] = $objSalesPerson->getSalesPersonCodeById($data['agent_id']);
				}else{
					$data['agent_id'] = 0;
					$data['agent_code'] = '';
				}
				$data['area'] = $formArea;
				$data['phone1'] = $formPhone1;
				$data['phone2'] = $formPhone2;
				$data['fax'] = $formFax;
				$data['business'] = $formBusiness;
				$data['sales_potential'] = $formSalesPotential;
				$data['status'] = $formStatus;
				$data['password'] = rawurlencode(encryption($formPassword, PUBLIC_SALT, true)); //$formPassword;
				if($formTax != ''){
					$data['tax_id'] = encryption(rawurldecode($formTax), $_SESSION['salt'], false);
					$data['tax_code'] = $objMisc->getMiscCodeById($data['tax_id']);
				}else{
					$data['tax_id'] =  0;
					$data['tax_code'] = '';
				}
				$data['credit_limit'] = str_replace(',', '', $formCreditLimit);
				$data['credit_balance'] = str_replace(',', '', $formCreditBalance);
				$data['credit_date'] = convertDate($formCreditDate);
				$data['credit_terms'] = str_replace(',', '', $formCreditTerms);
				$data['website'] = $formWebsite;
				$data['remarks'] = $formRemarks;
				$data['gst_no'] = $formGSTno;
				$data['price_group'] = $formPricingGroup;
				if($formCurrency != ''){
					$data['currency_id'] = encryption(rawurldecode($formCurrency), $_SESSION['salt'], false);
					$data['currency_code'] = $objCurrency->getCurrencyCodeById($data['currency_id']);
				}else{
					$data['currency_id'] =  0;
					$data['currency_code'] = '';
				}
				
				$data['created_by'] = $_SESSION['user_id'];
				$data['created_date'] = date("Y-m-d H:i:s");
				if(false){ //debug
					$insertedId = $objCustomer->getInsertedId();
					foreach($fields AS $key => $value){	
						$cf_data = array();					
						if($value['cf_status'] == 1){							
							$cf_data['cf_id'] = $value['id'];
							$cf_data['module_data_id'] = $insertedId;
							unset($_SESSION[$value['cf_code']]);				
							$cf_data['cf_data'] = ${"form".$value['cf_code']};
							$cf_data['created_by'] = $_SESSION['user_id'];
							$cf_data['created_date'] = date("Y-m-d H:i:s");					
							$objCustomField->saveCustomFieldModuleData($cf_data);	
						}
						insertAuditTrails(MODULE_UID, 'insert', "", $cf_data);
					}
					$encInsertedId = encryption($insertedId, $_SESSION['salt'], true);
					setCookieValue($encInsertedId, 'added_key');
					insertAuditTrails(MODULE_UID, 'insert', "", $data);
					if($submitMode == 'new'){
						header("Location: ".HTTP_ACTIVE_MODULE."?redir=new");
						exit;
					}else{
						header("Location: ".getModuleURL('donor.view')."?redir=new&key=".rawurlencode($encInsertedId));
						exit;
					}
				}else{
					$error['content'] = "Cannot save donor. Please try again.";
				}
			}
		break;
		case 'donor.view':
		case 'donor.edit':
		/*
			if(!empty($_GET)){
				$redirect = checkParam('redir');
				if($redirect == 'new'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCustomer->getCustomerNoById($decryptKey);
						if($data != ""){
							$message['content'] = "Customer ".$data." has been successfully created.";
							deleteCookieValue('added_key');
						}
					}
				}else if($redirect == 'update'){
					$decryptKey = getCookieValue('added_key');
					if($decryptKey != ""){
						$decryptKey = encryption(getCookieValue('added_key'), $_SESSION['salt'], false);
						$data = $objCustomer->getCustomerNoById($decryptKey);
						if($data != ""){
							$message['content'] = "Customer ".$data." has been successfully updated.";
							deleteCookieValue('added_key');
						}
					}
				}
			}
			
			$encryptKey = checkParam('key', '', 'get');
			$decryptKey = $encryptKey;
			
			$returnCust = checkParam('redir', '', 'get');
			$returnType = checkParam('opt', '', 'get');
			
			if($decryptKey == ''){
				header("Location: ".getModuleURL('customer.list')."?invalid=1");
				exit;
			}else{
				$decryptKey = encryption($decryptKey, $_SESSION['salt'], false);
			}
			
			$customerData = $objCustomer->getCustomerData($decryptKey);
			$getRecordAccess = checkAccessRecords(MODULE_PARENT_UID);
			if($getRecordAccess){
                if(!getAccessView(MODULE_PARENT_UID, $customerData)){
					header("Location: ".getModuleURL('customer.list')."?invalid=2");
					exit;
				}
			}
			if(empty($customerData)){
				header("Location: ".getModuleURL('customer.list')."?invalid=2");
				exit;
			}
			
			$allowView 		= checkAccess('customer.view');
			$allowDelete 	= checkAccess('customer.delete');
			$allowEdit 		= checkAccess('customer.edit');
			
			if(MODULE_UID == 'customer.view'){
				$mode = "view";
			}else{
				$mode = "edit";
			}			
			
			$HTTP_AJAX = HTTP_ACTIVE_PARENT.'/ajax';
			insertTracker(MODULE_NAME.(isset($customerData["name"])?": ".$customerData["name"]:""));
			$setting['load_tile'] = "0";
			$setting['left_uid'] = MODULE_PARENT_UID;
			$setting['center_dir'] = DIR_ACTIVE_THEME."/customer/view_customer.php";
			
			$relatedSalesPersonStart = 0;
			$relatedSalesPersonPerPage = 15;
			$relatedSalesPersonFields = $objCustomer->listRelatedSalesPersonField();
			
			$relatedCustomersStart = 0;
			$relatedCustomersPerPage = 15;
			$relatedCustomerFields = $objCustomer->listRelatedCustomersField('projects_related_customers');
			
			$relatedContactsStart = 0;
			$relatedContactsPerPage = 15;
			$relatedContactFields = $objContact->listContactField();
			
			$relatedProjectsStart = 0;
			$relatedProjectsPerPage = 15;
			$relatedProjectFields = $objProject->listRelatedProjectField();
			
			$detailsStart = 0;
			$detailsItemsPerPage = 15;
			$activitiesFields = $objActivities->listActivitiesField();
                        
            $relatedItemsStart 			= 0;
			$relatedItemsPerPage 		= 15;
			$relatedItemFields 			= $objProject->listRelatedItemsField();
			
			$shipToStart = 0;
			$shipToPerPage = 15;
			$shipToFields = $objCustomer->listShipToField();
			
			$customfieldName = $objCustomField->getCustomFieldModuleName(MODULE_PARENT_UID);
			$systemfieldName = $objSystemField->getSystemFieldModuleName(MODULE_PARENT_UID);

			$moduleProjectStatus = getModuleStatus('project_management');

			if($allowEdit){
				$formCustNo = $customerData['cust_no'];
				$formName = $customerData['name'];
				$formRocNo = $customerData['roc_no'];
				$formAttention = $customerData['attention'];
				$formAttention1 = $customerData['attention1'];
				$formInvoiceCountry = rawurlencode(encryption($customerData['invoice_country_id'], $_SESSION['salt'], true));
				$formInvoiceState = $customerData['invoice_state'];
				$formInvoiceCity = $customerData['invoice_city'];
				$formInvoicePostcode = $customerData['invoice_postcode'];
				$formInvoiceAddress = $customerData['invoice_address'];
				$formDeliveryCountry = rawurlencode(encryption($customerData['delivery_country_id'], $_SESSION['salt'], true));
				$formDeliveryState = $customerData['delivery_state'];
				$formDeliveryCity = $customerData['delivery_city'];
				$formDeliveryPostcode = $customerData['delivery_postcode'];
				$formDeliveryAddress = $customerData['delivery_address'];
				$formWebsite = $customerData['website'];
				$formEmail = $customerData['email'];
                                $formChannel = $customerData['channel'];
				$formTeam = $customerData['team'];
				$formAgent = rawurlencode(encryption($customerData['agent_id'], $_SESSION['salt'], true));
				$formArea = $customerData['area'];
				$formPhone1 = $customerData['phone1'];
				$formPhone2 = $customerData['phone2'];
				$formFax = $customerData['fax'];
				$formBusiness = $customerData['business'];
				$formSalesPotential = $customerData['sales_potential'];
				$formStatus = $customerData['status'];
				if($customerData['password']!="") {
					$formPassword = encryption(rawurldecode($customerData['password']), PUBLIC_SALT, false);
				} else {
					$formPassword = "";
				}
				$formTax = rawurlencode(encryption($customerData['tax_id'], $_SESSION['salt'], true));
				$formCreditLimit = $customerData['credit_limit'];
				$formCreditBalance = $customerData['credit_balance'];
				if($customerData['credit_date'] != '0000-00-00 00:00:00' && $customerData['credit_date'] != ''){
					$formCreditDate = convertDate($customerData['credit_date'], 'Y-m-d H:i:s', 'd/m/Y');
				}else{
					$formCreditDate = '';
				}
				$formCreditTerms = $customerData['credit_terms'];
				$formRemarks = $customerData['remarks'];
				$formGSTno = $customerData['gst_no'];
				$formPricingGroup = $customerData['price_group'];
				$formCurrency = rawurlencode(encryption($customerData['currency_id'], $_SESSION['salt'], true));
				////$listArea = $objCustomer->listAddFields(" AND `type`='area' ORDER BY `order` ASC", 0, 0, false, false);
				////$listBusinessType = $objCustomer->listAddFields(" AND `type`='business_type' ORDER BY `order` ASC", 0, 0, false, false);
				////$listSalesPotential = $objCustomer->listAddFields(" AND `type`='sales_potential' ORDER BY `order` ASC", 0, 0, false, false);
						
				foreach($customfieldName AS $key => $value){
					if($value['cf_status'] == 1){						
						$customfieldData = $objCustomField->getCustomFieldModuleData($decryptKey,$value['id']);
						if($customfieldData != ''){	
							${"form".$value['cf_code']} = $customfieldData;
							$_SESSION[$value['cf_code']] = $customfieldData;
						} else {
							${"form".$value['cf_code']} = '';
							$_SESSION[$value['cf_code']] = '';
						}
					}
				}
				foreach($systemfieldName AS $key => $value){
					if($value['cf_status'] == 1){						
						$_SESSION["system_".$value['cf_code']] = '';
					}
				}
				
				if(!empty($_POST)){
					$mode = "edit";
					$submitMode = checkParam('submit_mode');
					$formName = checkParam('name');
					$formCustNo = checkParam('custno');
					$formRocNo = checkParam('roc');
					$formAttention = checkParam('attention');
					$formAttention1 = checkParam('attention1');
					$formInvoiceCountry = checkParam('ext-invoice-country');
					$formInvoiceState = checkParam('invoice_state');
					$formInvoiceCity = checkParam('invoice_city');
					$formInvoiceAddress = checkParam('invoice_address');
					$formInvoicePostcode = checkParam('invoice_postcode');
					$formDeliveryCountry = checkParam('ext-delivery-country');
					$formDeliveryState = checkParam('delivery_state');
					$formDeliveryCity = checkParam('delivery_city');
					$formDeliveryAddress = checkParam('delivery_address');
					$formDeliveryPostcode = checkParam('delivery_postcode');
					$formWebsite = checkParam('website');
					$formEmail = checkParam('email');
					$formChannel = checkParam('channel');
					$formTeam = checkParam('team');
					$formAgent = checkParam('ext-agent');
					$formArea = checkParam('area');
					$formPhone1 = checkParam('phone1');
					$formPhone2 = checkParam('phone2');
					$formFax = checkParam('fax');
					$formBusiness = checkParam('business');
					$formSalesPotential = checkParam('sales_potential');
					$formStatus = checkParam('status');
					$formPassword = checkParam('password');
					$formTax = checkParam('ext-tax');
					$formCreditLimit = checkParam('limit');
					$formCreditBalance = checkParam('balance');
					$formCreditDate = checkParam('date');
					$formCreditTerms = checkParam('terms');
					$formRemarks = checkParam('remarks');
					$formGSTno = checkParam('gst_no');
					$formPricingGroup = checkParam('pricing_group');
					$formCurrency = checkParam('ext-currency');
					if($objCustomField->checkFormwithCustomeField(MODULE_PARENT_UID)){
						foreach($customfieldName AS $key => $value){
							if($value['cf_status'] == 1){									
								if($value['cf_type'] == "checkbox"){								
									${"get".$value['cf_code']} = checkParam($value['cf_code']); 
									if(${"get".$value['cf_code']} != ''){
										${"form".$value['cf_code']} = implode(',', ${"get".$value['cf_code']});
										$_SESSION[$value['cf_code']] = implode(',', ${"get".$value['cf_code']});
									}								
								} else {
									${"form".$value['cf_code']} = checkParam($value['cf_code']);
									$_SESSION[$value['cf_code']] = ${"form".$value['cf_code']};
								}
							}
						}
					}
					foreach($systemfieldName AS $key => $value){
						if($value['cf_status'] == 1){						
							$_SESSION["system_".$value['cf_code']] = checkParam($value['cf_code']);
						}
					}
					
					if(!validateEmptyField($formCustNo, 'customer no', $error)){break;}
					if($objCustomer->checkCustomerNoExist($formCustNo, $customerData['id'])){
						$error['content'] = "Customer no already exist. Please input another customer no.";
						break;
					}
					if(!validateEmptyField($formName, 'name', $error)){break;}
					if($formEmail != '' && !filter_var($formEmail, FILTER_VALIDATE_EMAIL)){
						$error['content'] = "Invalid email address. Please input correct email address.";
						break;
					}
					if($formPhone1 != "" && !validatePhoneField($formPhone1, 'phone 1', $error)){break;}
					if($formPhone2 != "" && !validatePhoneField($formPhone2, 'phone 2', $error)){break;}
					if(!validateEmptyField($formInvoiceAddress, 'billing address', $error)){break;}					
					// if(!validateNumericField($formCreditBalance, 'current balance', $error, true, true, true)){break;}
					// if(!validateNumericField($formCreditLimit, 'credit limit', $error, true, true, true)){break;}
					if(!validateEmptyField($formCreditTerms, 'credit terms', $error)){break;}
					if($formCreditDate != "" && !validateDateField($formCreditDate, 'credit date', $error)){break;}
					// if($formPassword=="") {
					// 	$error['content'] = "Passphrase cannot be empty.";
					// 	break;
					// }
					foreach($customfieldName AS $key => $value){
						if($value['cf_status'] == 1){ 
							if($value['cf_mandatory'] == 1){								
								if(!validateEmptyField($_SESSION[$value['cf_code']], $value['cf_label'], $error)){break;}
								if($value['cf_type'] == "numeric"){
									if(!validateNumericField($_SESSION[$value['cf_code']], $value['cf_label'], $error, true)){break;}
								} else if($value['cf_type'] == "date"){ 
									if(!validateDateField($_SESSION[$value['cf_code']], $value['cf_label'], $error)){break;}							
								}
							} else {
								if($value['cf_type'] == "numeric" && ${"form".$value['cf_code']} != ''){
									if(!validateNumericField($_SESSION[$value['cf_code']], $value['cf_label'], $error, true)){break;}
								} else if($value['cf_type'] == "date" && ${"form".$value['cf_code']} != ''){
									if(!validateDateField($_SESSION[$value['cf_code']], $value['cf_label'], $error)){break;}							
								}
							}
						} 
					}	
					foreach($systemfieldName AS $key => $value){
						if($value['cf_status'] == 1){ 
							if($value['cf_mandatory'] == 1){								
								if(!validateEmptyField($_SESSION["system_".$value['cf_code']], $value['cf_label'], $error)){break;}
								if($value['cf_type'] == "numeric"){
									if(!validateNumericField($_SESSION["system_".$value['cf_code']], $value['cf_label'], $error, true)){break;}
								} else if($value['cf_type'] == "date"){ 
									if(!validateDateField($_SESSION["system_".$value['cf_code']], $value['cf_label'], $error)){break;}							
								}
							} else {
								if($value['cf_type'] == "numeric" && ${"form".$value['cf_code']} != ''){
									if(!validateNumericField($_SESSION["system_".$value['cf_code']], $value['cf_label'], $error, true)){break;}
								} else if($value['cf_type'] == "date" && ${"form".$value['cf_code']} != ''){
									if(!validateDateField($_SESSION["system_".$value['cf_code']], $value['cf_label'], $error)){break;}							
								}
							}
						} 
					}
					
					if($error['content']){break;}
					
					$newData = array();
					$newData['id'] = $customerData['id'];
					$newData['name'] = $formName;
					$newData['cust_no'] = $formCustNo;
					$newData['roc_no'] = $formRocNo;
					$newData['attention'] = $formAttention;
					$newData['attention1'] = $formAttention1;
					if($formInvoiceCountry != ''){
						$newData['invoice_country_id'] = encryption(rawurldecode($formInvoiceCountry), $_SESSION['salt'], false);
						$newData['invoice_country'] = $objCountry->getCountryNameById($newData['invoice_country_id']);
					}else{
						$newData['invoice_country_id'] = 0;
						$newData['invoice_country'] = '';
					}
					$newData['invoice_state'] = $formInvoiceState;
					$newData['invoice_city'] = $formInvoiceCity;
					$newData['invoice_address'] = $formInvoiceAddress;
					$newData['invoice_postcode'] = $formInvoicePostcode;
					if($formDeliveryCountry != ''){
						$newData['delivery_country_id'] = encryption(rawurldecode($formDeliveryCountry), $_SESSION['salt'], false);
						$newData['delivery_country'] = $objCountry->getCountryNameById($newData['delivery_country_id']);
					}else{
						$newData['delivery_country_id'] = 0;
						$newData['delivery_country'] = '';
					}
					$newData['delivery_state'] = $formDeliveryState;
					$newData['delivery_city'] = $formDeliveryCity;
					$newData['delivery_address'] = $formDeliveryAddress;
					$newData['delivery_postcode'] = $formDeliveryPostcode;
					$newData['email'] = $formEmail;
					$newData['channel'] = $formChannel;
					$newData['team'] = $formTeam;
					if($formAgent != ''){
						$newData['agent_id'] = encryption(rawurldecode($formAgent), $_SESSION['salt'], false);
						$newData['agent_code'] = $objSalesPerson->getSalesPersonCodeById($newData['agent_id']);
					}else{
						$newData['agent_id'] = 0;
						$newData['agent_code'] = '';
					}
					$newData['area'] = $formArea;
					$newData['phone1'] = $formPhone1;
					$newData['phone2'] = $formPhone2;
					$newData['fax'] = $formFax;
					$newData['business'] = $formBusiness;
					$newData['sales_potential'] = $formSalesPotential;
					$newData['status'] = $formStatus;
					$newData['password'] = rawurlencode(encryption($formPassword, PUBLIC_SALT, true)); //$formPassword;
					if($formTax != ''){
						$newData['tax_id'] =  encryption(rawurldecode($formTax), $_SESSION['salt'], false);
						$newData['tax_code'] = $objMisc->getMiscCodeById($newData['tax_id']);
					}else{
						$newData['tax_id'] = 0;
						$newData['tax_code'] = '';
					}
					$newData['credit_limit'] = str_replace(',', '', $formCreditLimit);
					$newData['credit_balance'] = str_replace(',', '', $formCreditBalance);
					$newData['credit_date'] = convertDate($formCreditDate);
					$newData['credit_terms'] = str_replace(',', '', $formCreditTerms);
					$newData['website'] = $formWebsite;
					$newData['remarks'] = $formRemarks;
					$newData['gst_no'] = $formGSTno;
					$newData['price_group'] = $formPricingGroup;
					if($formCurrency != ''){
						$newData['currency_id'] = encryption(rawurldecode($formCurrency), $_SESSION['salt'], false);
						$newData['currency_code'] = $objCurrency->getCurrencyCodeById($newData['currency_id']);
					}else{
						$newData['currency_id'] = 0;
						$newData['currency_code'] = '';
					}
					
					$newData['modified_by'] = $_SESSION['user_id'];
					$newData['modified_date'] = date("Y-m-d H:i:s");
					
					if($objCustomer->updateCustomer($newData)){
						foreach($customfieldName AS $key => $value){
							$cf_newdata = array();	
							if($value['cf_status'] == 1){	
								$value['cf_label'] = str_replace(' ', '_', $value['cf_label']);	
								$customfieldData = $objCustomField->getCustomFieldModuleData($decryptKey,$value['id']);
								$customfieldID = $objCustomField->getCustomFieldDataID($decryptKey,$value['id']);
								if($objCustomField->checkCustomFieldModuleData($customfieldID)){									
									$cf_newdata['id'] = $customfieldID;
									unset($_SESSION[$value['cf_code']]);																
									$cf_newdata['cf_data'] = ${"form".$value['cf_code']};									
									$cf_newdata['modified_by'] = $_SESSION['user_id'];
									$cf_newdata['modified_date'] = date("Y-m-d H:i:s");				
									$objCustomField->updateCustomFieldModuleData($cf_newdata);
								} else {
									$cf_newdata['cf_id'] = $value['id'];
									$cf_newdata['module_data_id'] = $customerData['id'];
									unset($_SESSION[$value['cf_code']]);																
									$cf_newdata['cf_data'] = ${"form".$value['cf_code']};
									$cf_newdata['created_by'] = $_SESSION['user_id'];
									$cf_newdata['created_date'] = date("Y-m-d H:i:s");			
									$objCustomField->saveCustomFieldModuleData($cf_newdata);
								}								
							}
							insertAuditTrails('customer.edit', 'update', "", $customerData, $cf_newdata);
						}
						insertAuditTrails('customer.edit', 'update', "", $customerData, $newData);
						$encInsertedId = encryption($customerData['id'], $_SESSION['salt'], true);
						setCookieValue($encInsertedId, 'added_key');
						header("Location: ".HTTP_ACTIVE_MODULE."?key=".rawurlencode($encryptKey)."&redir=update");
						exit;
					}
				}
			}
		break;
		
		*/
		default:
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case 'check_duplicate_custno':
						$code = checkParam('val');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objCustomer->checkCustomerNoExist($code, $id)){
							$output['message'] = "Customer no already exist. Please input another customer no.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'check_custname_similarity':
						$name = checkParam('val');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						if($objCustomer->checkCustomerNameSimilarity($name, $id)){
							$output['message'] = "A customer with the same name already exists. However, you may still proceed with the creation of this customer record.";
						}else{
							$output['success'] = true;
						}
					break;
					case 'combo_agent_code':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`name` LIKE '%".$query."%' OR `code` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objSalesPerson->getSalesPersonCombo($condition, $start, $limit);
						$output['total_row'] = $objSalesPerson->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_currency_code':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`symbol` LIKE '%".$query."%' OR `code` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objCurrency->getCurrencyCombo($condition, $start, $limit);
						$output['total_row'] = $objCurrency->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_country_code':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`name` LIKE '%".$query."%' OR `iso` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objCountry->getCountryCombo($condition, $start, $limit);
						$output['total_row'] = $objCountry->getTotalRow();
						$output['success'] = true;
					break;
					case 'list_customer':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
                                                $mainTable = 'customers';
						$condition = getFilterSQL($filter);
                                                
                                                $searchValue = checkParam('search_value');
                                                $searchColumns = isset($_GET['search_columns']) ? json_decode($_GET['search_columns']):'';
                                                if($searchValue != '') {
                                                    $condition .= " AND ( ";
                                                    $arrSearchConditions = array();
                                                    foreach($searchColumns AS $key => $value) {
                                                        $searchConditions = " `".$value."` LIKE '%".$searchValue."%' ";
                                                        array_push($arrSearchConditions, $searchConditions);
                                                    }
                                                    $condition .= implode(" OR ", $arrSearchConditions);
                                                    $condition .= ")";
                                                }
                                                
						$getRecordAccess = checkAccessRecords(MODULE_UID);
						if($getRecordAccess){
                                                    $condition .=  getAccessQueriesCondition(MODULE_UID, $mainTable);
//							$agent_id = encryption(rawurldecode($_SESSION['salesperson_id']), $_SESSION['salt'], false);
//							$condition .= " AND `salesperson_id`= '".$agent_id."' ";
						}
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
							$condition .= " ORDER BY `cust_no` ASC ";
						}
						
						$_SESSION['touchsales.customer.exportlist_filter'] = $condition;
						$_SESSION['touchsales.customer.list_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCustomer->listCustomer($condition, $start, $limit, "*");
						$output['total'] = $objCustomer->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_customers':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCustomer->checkCustomerExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Customer does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected customers successfully deleted.";
							foreach($delId AS $value){
								$data = $objCustomer->getCustomerData($value);
								if($objCustomer->deleteCustomer($value)){
									insertAuditTrails('customer.delete', 'delete', "", $data);
								}
								if($objCustomer->deleteParentCustomers('customers_related_salesperson', 'customer_id', $value)){									
									insertAuditTrails('customer.related_salesperson.delete', 'delete', "Related Sales Person", $data);
								}
								if($objCustomer->deleteParentCustomers('customers_related_customers', 'mainCust_id', $value)){									
									insertAuditTrails('customer.related_customer.delete', 'delete', "Related Customer", $data);
								}
								$dataCF = $objCustomField->getRelativeCustomFieldData($value);
								if($objCustomField->deleteRelativeCustomFields($value)){
									insertAuditTrails('customer.delete', 'delete', "", $dataCF);
								}
								$dataActivities = $objActivities->getRelativeActivitiesData('customer', $value);
								if($objActivities->deleteRelativeActivities('customer', $value)){
									insertAuditTrails('customer.activities.delete', 'delete', "Customer details - Delete activities", $dataActivities);
								}
								$dataCustomFieldss = $objCustomField->getRelativeCustomFieldData($value);
								if($objCustomField->deleteRelativeCustomFields($value)){
									insertAuditTrails('customer.customfield.delete', 'delete', "Customer details - Delete custom field", $dataCustomFieldss);
								}
							}
						}
					break;
					case 'download_customers':
						$strFileName = checkParam('filename');						
						$arrHeaders = explode(';', checkParam('arrHeaders'));
						$arrIndexes = explode(';', checkParam('arrIndexes'));
						//$_SESSION['touchsales.customer.list_filter']
						$numRecordPerBatch = 300;
						$condition = $_SESSION['touchsales.customer.list_filter'];
						$output['table'] = $objCustomer->listCustomer($condition);
						$output['total'] = $objCustomer->getTotalRow();
						if($output['total']>0) {
							$strExcelExport = "Customer List";							
							$numBatch = ceil($output['total']/$numRecordPerBatch);
							header("Content-type: application/ms-excel");
							header('Content-Disposition: attachment; filename="' . $strFileName . '.xls"');
							if (preg_match('@MSIE ([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
								header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
								header('Pragma: public');
							} else {
								header('Pragma: no-cache');
							}
							include_once(DIR_ACTIVE_THEME."/customer/cust_template.php");

							exit; // exit the application for download process.
						}	
					break;
					case 'combo_tax_code':
						$objMisc = new Misc($GLOBALS['myDB']);
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = " AND `type` = 'tax'";
						if($query != ''){
							$condition .= " AND (`code` LIKE '%".$query."%' OR `name` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objMisc->getMiscCombo($condition, $start, $limit);
						$output['total_row'] = $objMisc->getTotalRow();
						$output['success'] = true;
					break;	
                                        case 'combo_vendor':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`name` LIKE '%".$query."%' OR `vend_no` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objVendor->getVendorComboComplete($condition, $start, $limit);
						$output['total_row'] = $objVendor->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_communication_type':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`type` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objCommunicationType->getCommunicationTypeCombo($condition, $start, $limit);
						$output['total_row'] = $objCommunicationType->getTotalRow();
						$output['success'] = true;
					break;
					case 'list_related_salesperson':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND `customer_id`='".$parent."' ";
						
						$strOrderBy = "";
						if(isset($_GET['sort'])){
							$arrSorting = (array)json_decode($_GET['sort']);
							if(isset($arrSorting[0])) {
								$arrSorting = (array)$arrSorting[0];
								if(isset($arrSorting["property"]) && isset($arrSorting["direction"])) {
									if($arrSorting["property"] == 'code' || $arrSorting["property"] == 'name'){
										$strOrderBy .= " ORDER BY `".$arrSorting["property"]."` ".$arrSorting["direction"]." ";
									} else {
										$strOrderBy .= " ORDER BY `customers_related_salesperson`.`".$arrSorting["property"]."` ".$arrSorting["direction"]." ";
									}
								}
							}
						}
						if($strOrderBy!="") {
							$condition .= $strOrderBy;
						} else {
							$condition .= " ORDER BY `customers_related_salesperson`.`id` DESC ";
						}
						
						$_SESSION['customer.sm_sales_person_listing'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCustomer->listRelatedSalesPerson($condition, $start, $limit, "*");
						foreach($output['table'] AS $tK => $tV){
							$agentData = $objSalesPerson->getSalesPersonData($tV['salesperson_id']);
							if(empty($agentData)){
								$output['table'][$tK]['code'] = '';
								$output['table'][$tK]['name'] = '';
							}else{
								$output['table'][$tK]['code'] = $agentData['code'];
								$output['table'][$tK]['name'] = $agentData['name'];
							}
						}
						$output['total'] = $objCustomer->getTotalRow();
						$output['success'] = true;
					break;
					case 'submit_sales_person':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$newData = array();
						$newData['salesperson_id'] = encryption(rawurldecode(checkParam('sales_person')), $_SESSION['salt'], false);
						$newData['customer_id'] = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$customerData = $objCustomer->getCustomerData($newData['customer_id']);
						if(empty($customerData)){
							$output['message'] = "Customer data corrupted. Please try again.";
							break;
						}
						if($customerData['agent_id'] != '0' && $customerData['agent_id'] == $newData['salesperson_id']){
							$output['message'] = "Selected sales person already exist as master sales person. Please select another sales person.";
							break;
						}
						if($operation == 'new'){
							if($objCustomer->validateRelatedSalesPerson('', $newData['customer_id'], $newData['salesperson_id'])){
								$output['message'] = "Selected sales person already exist in the relationship. Please select another sales person.";
								break;
							}
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objCustomer->saveRelatedSalesPerson($newData)){
								insertAuditTrails('customers', 'insert', "Related Sales Person", $newData);
								$output['success'] = true;
								$output['message'] = 'Sales person has been successfully created.';
							}
						}else{
							if($objCustomer->validateRelatedSalesPerson($id, $newData['customer_id'], $newData['salesperson_id'])){
								$output['message'] = "Selected sales person already exist in the relationship. Please select another sales person.";
								break;
							}
							$relatedSalesPersonData = $objCustomer->getRelatedSalesPersonData($id);
							if(empty($relatedSalesPersonData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objCustomer->updateRelatedSalesPerson($newData)){
								insertAuditTrails('customer.edit', 'update', "Related Sales Person", $relatedSalesPersonData, $newData);
								$output['success'] = true;
								$output['message'] = 'Sales person has been successfully updated.';
							}
						}
					break;
					case 'delete_sales_person':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCustomer->checkRelatedSalesPersonExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Sales person does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected sales person successfully deleted.";
							foreach($delId AS $value){
								$data = $objCustomer->getRelatedSalesPersonData($value);
								if($objCustomer->deleteRelatedSalesPerson($value)){
									insertAuditTrails('customer.edit', 'delete', "Related Sales Person", $data);
								}
							}
						}
					break;
					case 'list_ship_to':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND `customer_id`='".$parent."' ";
						
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
							$condition .= " ORDER BY `created_date` DESC ";
						}
						
						$_SESSION['customer.sm_ship_to_listing'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCustomer->listShipTo($condition, $start, $limit, "*");
						$output['total'] = $objCustomer->getTotalRow();
						$output['success'] = true;
					break;
					case 'submit_ship_to':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$country = checkParam('country');
						$newData = array();
						$newData['customer_id'] = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$customerData = $objCustomer->getCustomerData($newData['customer_id']);
						if(empty($customerData)){
							$output['message'] = "Customer data corrupted. Please try again.";
							break;
						}
						$newData['code'] = checkParam('code');
						$newData['postcode'] = checkParam('postcode');
						if($country != ''){
							$newData['country_id'] = encryption(rawurldecode($country), $_SESSION['salt'], false);
							$newData['country'] = $objCountry->getCountryNameById($newData['country_id']);
						}else{
							$newData['country_id'] = 0;
							$newData['country'] = '';
						}
						$newData['city'] = checkParam('city');
						$newData['state'] = checkParam('state');
						$newData['attention'] = checkParam('attention');
						$newData['address'] = checkParam('address');
						if($operation == 'new'){
							if($objCustomer->validateShipTo('', $newData['customer_id'], $newData['code'])){
								$output['message'] = "Selected ship to code already exist in the relationship. Please input another code.";
								break;
							}
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objCustomer->saveShipTo($newData)){
								insertAuditTrails('customers', 'insert', "Ship To Address", $newData);
								$output['success'] = true;
								$output['message'] = 'Ship to address has been successfully created.';
							}
						}else{
							if($objCustomer->validateShipTo($id, $newData['customer_id'], $newData['code'])){
								$output['message'] = "Selected ship to code already exist in the relationship. Please input another code.";
								break;
							}
							$shipToData = $objCustomer->getShipToData($id);
							if(empty($shipToData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objCustomer->updateShipTo($newData)){
								insertAuditTrails('customer.edit', 'update', "Ship To Address", $shipToData, $newData);
								$output['success'] = true;
								$output['message'] = 'Ship to address has been successfully updated.';
							}
						}
					break;
					case 'delete_ship_to':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCustomer->checkShipToExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Ship to address does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected ship to address successfully deleted.";
							foreach($delId AS $value){
								$data = $objCustomer->getShipToData($value);
								if($objCustomer->deleteShipTo($value)){
									insertAuditTrails('customer.edit', 'delete', "Ship To Address", $data);
								}
							}
						}
					break;
					case 'list_related_customers':
						$start 		= checkParam('start');
						$limit 		= checkParam('limit');
						$parent 	= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter 	= array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND (`mainCust_id`='".$parent."' OR `customer_id`='".$parent."' )";
						$strOrderBy = "";
						if(isset($_GET['sort'])){
							$arrSorting = (array)json_decode($_GET['sort']);
							if(isset($arrSorting[0])) {
								$arrSorting = (array)$arrSorting[0];
								if(isset($arrSorting["property"]) && isset($arrSorting["direction"])) {
									if($arrSorting["property"] == 'cust_no' || $arrSorting["property"] == 'name' || $arrSorting["property"] == 'content'){
										$strOrderBy .= " ORDER BY `".$arrSorting["property"]."` ".$arrSorting["direction"]." ";
									} else {
										$strOrderBy .= " ORDER BY `customers_related_customers`.`".$arrSorting["property"]."` ".$arrSorting["direction"]." ";
									}
								}
							}
						}
						if($strOrderBy!="") {
							$condition .= $strOrderBy;
						} else {
							$condition .= " ORDER BY `cust_no` ASC ";
						}
						$_SESSION['customer.list_relatedcustomers_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCustomer->listRelatedCustomers('customers_related_customers', $parent, $condition, $start, $limit, "*");
						$output['total'] = $objCustomer->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_customer_no':
						$start		= checkParam('start');
						$limit		= checkParam('limit');
						$query		= checkParam('query');
						$condition	= '';
						if($query != ''){
							$condition .= " AND (`cust_no` LIKE '%".$query."%' OR `name` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objCustomer->getCustomerComboComplete($condition, $start, $limit);
						$output['total_row'] = $objCustomer->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_relationship':
						$start		= checkParam('start');
						$limit		= checkParam('limit');
						$query		= checkParam('query');
						$condition	= ' AND `cf_id` = 11 ';
						if($query != ''){
							$condition .= " AND (`cf_content_label` LIKE '%".$query."%' OR `cf_content_value` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objSystemField->getSystemTypeCombo($condition, $start, $limit);
						$output['total_row'] = $objSystemField->getTotalRow();
						$output['success'] = true;
					break;
					case 'add_related_customers':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$newData = array();
						$newData['mainCust_id'] = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$newData['customer_id'] = encryption(rawurldecode(checkParam('customer_id')), $_SESSION['salt'], false);
						if(!$objCustomer->checkCustomerExist($newData['customer_id'])){
							$output['message'] = "Customer data corrupted. Please try again.";
							break;
						}
						$newData['cust_relationship'] = checkParam('cust_relationship');
						if(!$objSystemField->checkOptionValueExist($newData['cust_relationship'], '11')){
							$output['message'] = "Selected relationship do not exist as master customer. Please select another relationship.";
							break;
						}
						
						if($newData['mainCust_id'] == $newData['customer_id']){
							$output['message'] = "Selected customer already exist as master customer. Please select another customer.";
							break;
						}
						
						if($operation == 'new'){
							if($objCustomer->validateRelatedCustomer('', $newData['mainCust_id'], $newData['customer_id'])){
								$output['message'] = "Selected customer already exist in the relationship. Please select another customer.";
								break;
							}
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objCustomer->saveRelatedCustomers('customers_related_customers', $newData)){
								$insertedId = $objCustomer->getInsertedId();								
								insertAuditTrails('customer.related_customers.new', 'insert', "Related Customers", $newData);
								$output['success'] = true;
								$output['message'] = 'Related Customer has been successfully created.';								
							}
						}else{
							$detailsData = $objCustomer->getRelatedCustomersData('customers_related_customers', $id);
							if(empty($detailsData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							if($objCustomer->validateRelatedCustomer($id, $newData['mainCust_id'], $newData['customer_id'])){
								$output['message'] = "Selected customer already exist in the relationship. Please select another customer.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objCustomer->updateRelatedCustomers('customers_related_customers', $newData)){
								insertAuditTrails('customer.related_customers.edit', 'update', "Related Customers", $detailsData, $newData);
								$output['success'] = true;
								$output['message'] = 'Related Customer has been successfully updated.';								
							}
						}
					break;
					case 'delete_related_customers':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCustomer->checkRelatedCustomersExist('customers_related_customers', $value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Related Customer does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected related customer successfully deleted.";
							$parentId = "";
							foreach($delId AS $value){
								$data = $objCustomer->getRelatedCustomersData('customers_related_customers', $value);
								$parentId = $data['mainCust_id'];
								if($objCustomer->deleteRelatedCustomers('customers_related_customers', $value)){									
									insertAuditTrails('customer.related_customer.delete', 'delete', "Related Customer", $data);
								}
							}
						}
					break;
					case 'list_related_contacts':
						$start 		= checkParam('start');
						$limit 		= checkParam('limit');
						$parent 	= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
                                                $parentType     = checkParam('parentType');
						$filter 	= array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						 if($parent != ''){
							$condition .= " AND parent_type = '".$parentType."' AND `parent_id` = '".$parent."' ";
						} else {
							$condition .= " AND parent_type = '".$parentType."' AND `parent_id` = '0' ";
						}
						$_SESSION['customer.list_relatedcontacts_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objContact->listContact($condition, $start, $limit, "*");
						$output['total'] = $objContact->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_salutation':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = ' AND `cf_id` = 4  ';
						if($query != ''){
							$condition .= " AND `cf_content_label` LIKE '%".$query."%' ";
						}
						$output['combo'] = $objSystemField->getOptionofSFByCF_ID($condition, $start, $limit);
						$output['total_row'] = $objSystemField->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_customer':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`name` LIKE '%".$query."%' OR `cust_no` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objCustomer->getCustomerComboComplete($condition, $start, $limit);
						$output['total_row'] = $objCustomer->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_contact':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
                                                $parent = checkParam('parent');
                                                $companyId = checkParam('companyId');
                                                $companyType = checkParam('companyType');
						$mode = checkParam('mode');
						$conditions = checkParam('conditions');
						$strSorting = '';
						if($companyId != ''){
							$strSorting .= " AND parent_type = '".$companyType."' AND `parent_id` = '".encryption(rawurldecode($companyId), $_SESSION['salt'], false)."' ";
						} else {
							$strSorting .= " AND parent_type = '".$companyType."' AND `parent_id` = '0' ";
						}
						if ($mode == 'edit'){
							$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
							$strSorting .= " AND `id` != '".$parent."' ";
						}
						if($query != ''){
							$strSorting .= " AND (`first_name` LIKE '%".$query."%' OR `last_name` LIKE '%".$query."%') ";
						}						
						$output['combo'] = $objContact->getContactCombo($strSorting, $start, $limit);
						$output['total_row'] = $objContact->getTotalRow();
						$output['success'] = true;
					break;
					case 'add_related_contacts':						
						$operation = checkParam('operation');
						
						$formParentId = checkParam('parentId');
						$formParentType = checkParam('parentType');
						$formAgent = checkParam('related_contact_owner');					
						$formReportTo = checkParam('related_contact_report');	
						$formBirthDate = checkParam('related_contact_birthdate');	
						$newData = array();
						$newData['salutation'] = checkParam('related_contact_salutation');
						$newData['first_name'] = checkParam('related_contact_firstname');						
						$newData['last_name'] = checkParam('related_contact_lastname');
						if($formParentId != ''){
							$newData['parent_id'] = encryption(rawurldecode($formParentId), $_SESSION['salt'], false);
                                                        $newData['parent_type'] = $formParentType;
						} else {
							$newData['parent_id'] = 0;
                                                        $newData['parent_type'] = "";
						}
						if($formAgent != ''){
							$newData['agent_id'] = encryption(rawurldecode($formAgent), $_SESSION['salt'], false);					
						} else {
							$newData['agent_id'] = 0;
						}						
						$newData['title'] = checkParam('related_contact_title');
						$newData['department'] = checkParam('related_contact_department');
						if($formReportTo != ''){
							$newData['report_to_id'] = encryption(rawurldecode($formReportTo), $_SESSION['salt'], false);					
						} else {
							$newData['report_to_id'] = 0;
						}
						$newData['email'] = checkParam('related_contact_email');
						$newData['office_phone'] = checkParam('related_contact_officephone');			
						$newData['office_fax'] = checkParam('related_contact_officefax');
						$newData['mobile'] = checkParam('related_contact_mobile');
						if($formBirthDate != ''){
							$newData['birth_date'] = convertDate($formBirthDate);
						} else {
							$newData['birth_date'] = '';
						}
						$newData['contact_address'] = checkParam('related_contact_address');
						
						if($operation == 'new'){
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objContact->saveContact($newData)){
								$insertedId = $objContact->getInsertedId();							
								insertAuditTrails('customer.related_contact.new', 'insert', "Related Contact", $newData);
								$output['success'] = true;
								$output['message'] = 'Related Contact has been successfully created.';								
							}
						}
					break;
					case 'list_related_projects':
						$start 		= checkParam('start');
						$limit 		= checkParam('limit');
						$parent 	= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
                                                $parentType = checkParam('parentType');
						$filter 	= array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND  `projects`.`customer_id`='".$parent."' OR `projects_related_customers`.`customer_id`='".$parent."' ";
						
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
							$condition .= " ORDER BY `projects`.`created_date` DESC, `projects`.`project_name` ASC ";
						}
						
						$_SESSION['customer.list_relatedprojects_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objProject->listRelatedProject($parent,$parentType, $condition, $start, $limit, "*");
						$output['total'] = $objProject->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_project_type':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`project_type` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objType->getProjectTypeCombo($condition, $start, $limit);
						$output['total_row'] = $objType->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_project_stage':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$projecttypeID = checkParam('conditions');
						$condition = '';						
						if($projecttypeID != ''){
							$projecttypeID = encryption(rawurldecode($projecttypeID), $_SESSION['salt'], false);
							$condition = " AND `project_type_id` = '".$projecttypeID."' ";		
						} else {
							$condition = " AND `project_type_id` = 0 ";
						}
						if($query != ''){
							$condition .= " AND (`project_stage` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objStage->getProjectStageCombo($condition, $start, $limit);
						$output['total_row'] = $objStage->getTotalRow();
						$output['success'] = true;
					break;
					case 'add_related_projects':						
						$operation = checkParam('operation');						
						$formProjectContact = checkParam('related_project_contact');						
						$formProjectFollowUp = checkParam('related_project_followup');	
						
						$newData = array();
                        $newData['project_no'] = checkParam('related_project_no');
						$newData['project_name'] = checkParam('related_project_name');
						$newData['project_type_id'] = encryption(rawurldecode(checkParam('related_project_type')), $_SESSION['salt'], false);			
						$newData['stage_id'] = encryption(rawurldecode(checkParam('related_project_stage')), $_SESSION['salt'], false);
						$newData['customer_id'] = encryption(rawurldecode(checkParam('related_project_customer')), $_SESSION['salt'], false);
                        $newData['vendor_id'] = encryption(rawurldecode(checkParam('related_project_vendor')), $_SESSION['salt'], false);
						$formUpdateProjectNo = false;
                        if(strtolower($newData['project_no'])=="new") {
                            $formUpdateProjectNo = true;
                            $newData['project_no'] = generateRunningNo('projects.project_no');
                            while($objProject->checkProjectNoExist($newData['project_no'])){
                                updateRunningNo('projects.project_no');
                                $newData['project_no'] = generateRunningNo('projects.project_no');
                            }
                                
                        }
                        if($formUpdateProjectNo) {
                            updateRunningNo('projects.project_no');
                        }
                        if(!validateEmptyField($newData['project_no'], 'project no', $error)){break;}
                        if($objProject->checkProjectNoExist($newData['project_no'], '')){
                            $error['content'] = "Project no already exist. Please input another Project no.";
                            break;
                        }
                        if($formProjectContact != ''){
							$newData['contact_id'] = encryption(rawurldecode($formProjectContact), $_SESSION['salt'], false);					
						} else {
							$newData['contact_id'] = 0;
						}
						$newData['pic_id'] = encryption(rawurldecode(checkParam('related_project_pic')), $_SESSION['salt'], false);
						
						if($formProjectFollowUp != ''){
							$newData['follow_up_date'] = convertDate($formProjectFollowUp);
						} else {
							$newData['follow_up_date'] = '';
						}
						$newData['remarks'] = checkParam('related_project_remarks');
						
						if($operation == 'new'){
                            $newData['uid'] = $objProject->generateUID();
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objProject->saveProject($newData)){
								$insertedId = $objProject->getInsertedId();
                                $objProject->createFolderUID($newData['uid']);
								insertAuditTrails('customer.related_project.new', 'insert', "Related Project", $newData);
								$output['success'] = true;
								$output['message'] = 'Related Project has been successfully created.';								
							}
						}
					break;
                    case 'list_related_items':
						$start 		= checkParam('start');
						$limit 		= checkParam('limit');
						$parent 	= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter 	= array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND `customer_id`='".$parent."' ";
						
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
						
						$_SESSION['accounts.customers.list_relateditems_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objCustomer->listRelatedItems($condition, $start, $limit, "*");
						$output['total'] = $objCustomer->getTotalRow();
						$output['totalAmount'] = $objCustomer->getTotalAmount();
						$output['success'] = true;
					break;
					case 'combo_item_code':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
                                                $category = encryption(rawurldecode(checkParam('category')), $_SESSION['salt'], false);;
                                                $group = encryption(rawurldecode(checkParam('group')), $_SESSION['salt'], false);;
						$condition = '';
                                                
                                                if(checkParam('category') != ''){
							$condition .= " AND `category_id` = '".$category."' ";
						}
                                                if(checkParam('group') != ''){
							$condition .= " AND `group_id` = '".$group."' ";
						}
						if($query != ''){
							$condition .= " AND (`item_no_1` LIKE '%".$query."%' OR `name` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objItem->getItemCombo($condition, $start, $limit);
						$output['total_row'] = $objItem->getTotalRow();
						$output['success'] = true;
					break;
                                        case 'combo_item_category':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`code` LIKE '%".$query."%' OR `category` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objItem->getItemCategoryCombo($condition, $start, $limit);
						$output['total_row'] = $objItem->getTotalRow();
						$output['success'] = true;
					break;
                                        case 'combo_item_group':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`code` LIKE '%".$query."%' OR `group` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objItem->getItemGroupCombo($condition, $start, $limit);
						$output['total_row'] = $objItem->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_item_uom':
						$itemId = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$output['combo'] = $objItem->getUOMCombo($itemId);
						$output['success'] = true;
					break;
					case 'add_related_items':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$newData = array();
						$newData['customer_id'] = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$newData['item_id'] = encryption(rawurldecode(checkParam('item')), $_SESSION['salt'], false);
						if(!$objItem->checkItemExist($newData['item_id'])){
							$output['message'] = "Item data corrupted. Please try again.";
							break;
						}
						$newData['item_no'] = $objItem->getItemNoById($newData['item_id']);
						$newData['item_name'] = checkParam('name');	
						$newData['qty'] = checkParam('qty', '0');
						$newData['price'] = checkParam('price', '0');						
						$newData['disc_mode'] = checkParam('disc_mode', 'per');
						$newData['disc_amount'] = checkParam('disc_val');
						$newData['amount'] = number_format($newData['price'] * $newData['qty'], 2, '.', '');
						if($newData['disc_mode'] == 'per'){
							$newData['amount'] -= number_format($newData['amount'] * $newData['disc_amount'] / 100, 2, '.', '');
						}else{
							$newData['amount'] -= number_format($newData['disc_amount'], 2, '.', '');
						}						
						$newData['qty_uom_id'] = encryption(rawurldecode(checkParam('uom')), $_SESSION['salt'], false);
						if($objItem->checkUOMExist($newData['qty_uom_id'])){
							$newData['qty_uom'] = $objItem->getUOMItemUOMById($newData['qty_uom_id']);
						}else{
							if($newData['qty_uom_id'] == '0'){
								$newData['qty_uom'] = $objItem->getItemUOMById($newData['item_id']);
							}else{
								$newData['qty_uom_id'] = '-1';
								$newData['qty_uom'] = checkParam('uom');
							}
						}						
						if($operation == 'new'){
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objCustomer->saveRelatedItems($newData)){
								$insertedId = $objCustomer->getInsertedId();								
								insertAuditTrails('accounts.customers.related_items.new', 'insert', "Products Owned", $newData);
								$output['success'] = true;
								$output['message'] = 'Product Owned '.$newData['item_no'].' has been successfully added.';								
							}
						}else{
							$detailsData = $objCustomer->getRelatedItemsData($id);
							if(empty($detailsData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objCustomer->updateRelatedItems($newData)){
								insertAuditTrails('accounts.customers.related_items.edit', 'update', "Products Owned", $detailsData, $newData);
								$output['success'] = true;
								$output['message'] = 'Product Owned '.$newData['item_no'].' has been successfully updated.';								
							}
						}
					break;
					case 'delete_related_items':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objCustomer->checkRelatedItemsExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Related Item does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected related item successfully deleted.";
							$parentId = "";
							foreach($delId AS $value){
								$data = $objCustomer->getRelatedItemsData($value);
								$parentId = $data['project_id'];
								if($objCustomer->deleteRelatedItems($value)){									
									insertAuditTrails('project_management.projects.related_items.delete', 'delete', "Related Items", $data);
								}
							}
						}
					break;
					case 'list_activities':
						$start 		= checkParam('start');
						$limit 		= checkParam('limit');
						$parent 	= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$parentType = checkParam('parentType');
						$filter 	= array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = "";
						
						$getProjectList = "";
						$projectID = array();
						$projectID = $objProject->getProjectIDByCustID($parent);
						
						
						$getContactList = "";
						$contactID = array();
						$contactID = $objContact->getContactIDByCustID($parent);
						
						$type_filter = 0;
						$filter_query = "";
						if (is_array($filter)) {
							for ($i=0;$i<count($filter);$i++) {
								switch($filter[$i]["field"]) {
									case 'activity_name':
										$filter_query .= extjsTextboxFiltering("", "`activities`.`".$filter[$i]["field"]."`", $filter[$i]["data"]["value"]);
									break;
								
									case 'activity_type':
									case 'communication_type':
									case 'activity_owner':
										if($filter[$i]["field"]=="activity_type") {
											$arrIds = getVariousIdsByConditions("id", "activity_type", " AND `type` LIKE '%".$filter[$i]["data"]["value"]."%' LIMIT 1000 ");
										} else if($filter[$i]["field"]=="communication_type") {
											$arrIds = getVariousIdsByConditions("id", "communication_type", " AND `type` LIKE '%".$filter[$i]["data"]["value"]."%' LIMIT 1000 ");
										} else if($filter[$i]["field"]=="activity_owner") {
											$arrIds = getVariousIdsByConditions("id", "salesperson", " AND `name` LIKE '%".$filter[$i]["data"]["value"]."%' LIMIT 1000 ");
										}
										if(is_array($arrIds) && count($arrIds)>0) {
											if($filter[$i]["field"]=="activity_type") {
												$filter_query .= " AND `activities`.`activity_type_id` IN ('".implode("','", $arrIds)."')";
											} else if($filter[$i]["field"]=="communication_type") {
												$filter_query .= " AND `activities`.`communication_type_id` IN ('".implode("','", $arrIds)."')";
											} else if($filter[$i]["field"]=="activity_owner") {
												$filter_query .= " AND `activities`.`activity_owner_id` IN ('".implode("','", $arrIds)."')";
											}
										} else {
											$filter_query .= " AND `activities`.`id` = '-1' ";
										}
									break;									
									case 'type':
										$tmpValue = $filter[$i]['data']['value'];
										if($tmpValue=="Contact") {
											$type_filter = 1;
											$filter_query .= " AND `activities`.`type` = 'Contact' AND `activities`.`type_id` IN ('".implode("', '", $contactID)."') ";
										} else if($tmpValue=="Customer") {
											$type_filter = 2;
											$filter_query .= " AND `activities`.`type` = 'Customer' AND `activities`.`type_id`='".$parent."' ";
										} else if($tmpValue=="Project") {
											$type_filter = 3;
											$filter_query .= " AND `activities`.`type` = 'Project' AND `activities`.`type_id` IN ('".implode("', '", $projectID)."') ";
										} 
									break;									
									case 'created_date':										
										$dtDate = getDateByTimestampWithTZ($filter[$i]["data"]["value"], "Y-m-d");
										switch ($filter[$i]["data"]["comparison"]) {
											case 'eq':
												$filter_query .= " AND `activities`.`activity_date` >= '".$dtDate." 00:00:00'";
												$filter_query .= " AND `activities`.`activity_date` <= '".$dtDate." 23:59:59'";
											break;
											case 'lt':
												$filter_query .= " AND `activities`.`activity_date` < '".$dtDate." 00:00:00'";
											break;
											case 'gt':
												$filter_query .= " AND `activities`.`activity_date` > '".$dtDate." 23:59:59'";
											break;
										}
									break;
									
									case 'status':
										$tmpStatusValue = $filter[$i]['data']['value'];
										if($tmpStatusValue=="false") {
											$filter_query .= "AND `activities`.`".$filter[$i]["field"]."` = '0' ";
										} else{
											$filter_query .= "AND `activities`.`".$filter[$i]["field"]."` = '1' ";
										}
									break;
									
									case 'remarks':
										$filter_query .= extjsTextboxFiltering("", "`activities`.`".$filter[$i]["field"]."`", $filter[$i]["data"]["value"]);
									break;
								}
							}
						}
						if($filter_query != ""){	
							if($type_filter == 0){
								$condition .= " AND (`activities`.`type`='".$parentType."' AND `activities`.`type_id`='".$parent."' ";								
								if(!empty($projectID)){
									$condition .= " OR (`activities`.`type` = 'Project' AND `activities`.`type_id` IN ('".implode("', '", $projectID)."')) ";
								}
								if(!empty($contactID)){
									$condition .= " OR (`activities`.`type` = 'Contact' AND `activities`.`type_id` IN ('".implode("', '", $contactID)."')) ";
								}
								$condition .= " ) ";
								$condition .= $filter_query;
							} else {
								$condition .= $filter_query;
							}
							
						} else {
							$condition .= " AND (`activities`.`type`='".$parentType."' AND `activities`.`type_id`='".$parent."' ";
							if(!empty($projectID)){
								$condition .= " OR (`activities`.`type` = 'Project' AND `activities`.`type_id` IN ('".implode("', '", $projectID)."')) ";
							}
							if(!empty($contactID)){
								$condition .= " OR (`activities`.`type` = 'Contact' AND `activities`.`type_id` IN ('".implode("', '", $contactID)."')) ";
							}
							$condition .= " ) ";
						}
						
						$strOrderBy = "";
						if(isset($_GET['sort'])){
							$arrSorting = (array)json_decode($_GET['sort']);
							if(isset($arrSorting[0])) {
								$arrSorting = (array)$arrSorting[0];
								if(isset($arrSorting["property"]) && isset($arrSorting["direction"])) {
									if($arrSorting["property"] == 'activity_type'){
										$strOrderBy .= " ORDER BY `activity_type`.`type` ".$arrSorting["direction"]." ";
									} else if($arrSorting["property"] == "communication_type"){
										$strOrderBy .= " ORDER BY `communication_type`.`type` ".$arrSorting["direction"]." ";
									} else if($arrSorting["property"] == "activity_owner"){
										$strOrderBy .= " ORDER BY `salesperson`.`name` ".$arrSorting["direction"]." ";	
									} else if($arrSorting["property"] == "activity_date_format"){
										$strOrderBy .= " ORDER BY `activities`.`activity_date` ".$arrSorting["direction"].", `activities`.`activity_time` ".$arrSorting["direction"]." ";											
									} else {
										$strOrderBy .= " ORDER BY `activities`.`".$arrSorting["property"]."` ".$arrSorting["direction"]." ";
									}
									
								}
							}
						}
						if($strOrderBy!="") {
							$condition .= $strOrderBy;
						} else {
							$condition .= " ORDER BY `activities`.`activity_date` DESC, `activities`.`activity_time` DESC, `activities`.`created_date` DESC ";
						}
						
						$_SESSION['customer.list_activities_filter'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objActivities->listActivities($parentType, $parent, $condition, $start, $limit, "*");
						$output['total'] = $objActivities->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_activity_type':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$condition = '';
						if($query != ''){
							$condition .= " AND (`type` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objActivityType->getActivityTypeCombo($condition, $start, $limit);
						$output['total_row'] = $objActivityType->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_related_owner':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$strcondition = checkParam('conditions');
						$condition = '';
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
							$condition .= " AND (`name` LIKE '%".$query."%' OR `code` LIKE '%".$query."%') ";
						}
						$output['combo'] = $objSalesPerson->getSalesPersonCombo($condition, $start, $limit);
						$output['total_row'] = $objSalesPerson->getTotalRow();
						$output['success'] = true;
					break;
					case 'combo_related_contact':
						$start = checkParam('start');
						$limit = checkParam('limit');
						$query = checkParam('query');
						$parent = checkParam('parent');
                                                $companyId = checkParam('companyId');
                                                $companyType = checkParam('companyType');
						$strcondition = checkParam('conditions');
						$strSorting = '';
						if($companyId != ''){
							$strSorting .= " AND parent_type = '".$companyType."' AND `parent_id` = '".encryption(rawurldecode($companyId), $_SESSION['salt'], false)."' ";
						} else {
							$strSorting .= " AND parent_type = '".$companyType."' AND `parent_id` = '0' ";
						}
						if($strcondition != ''){
							$arrcondition = isset($strcondition) ? explode(',', $strcondition) : false;						
							if ($arrcondition) {
								$strSorting .= "AND (";
								$countcond = 0;
								$totalCount = count($arrcondition);
								foreach($arrcondition AS $value){
									$countcond ++;
									$strSorting .= " `id`='".$value."' ";
									if($countcond < $totalCount){
										$strSorting .= " OR ";
									}
								}
								$strSorting .= ")";
							}	
						}
						if($query != ''){
							$strSorting .= " AND (`first_name` LIKE '%".$query."%' OR `last_name` LIKE '%".$query."%') ";
						}						
						$output['combo'] = $objContact->getContactCombo($strSorting, $start, $limit);
						$output['total_row'] = $objContact->getTotalRow();
						$output['success'] = true;
					break;
					case 'add_activity':
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$strRelatedOwner = checkParam('related_owner');
						if($strRelatedOwner != ''){
							$arrRelatedOwner = explode(',', $strRelatedOwner);
						}						
						$strRelatedContact = checkParam('related_contact');
						if($strRelatedContact != ''){
							$arrRelatedContact = explode(',', $strRelatedContact);
						}
                                                
						$newData = array();
						$newData['activity_name']			= checkParam('activity_name');
						$newData['type_id'] 				= encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$newData['type']					= checkParam('parentType');
						$newData['activity_type_id'] 		= encryption(rawurldecode(checkParam('activity_type_id')), $_SESSION['salt'], false);
						$newData['communication_type_id'] 	= encryption(rawurldecode(checkParam('communication_type_id')), $_SESSION['salt'], false);
						$newData['activity_owner_id'] 		= encryption(rawurldecode(checkParam('activity_owner_id')), $_SESSION['salt'], false);
						$newData['activity_date'] 			= checkParam('activity_date');
						$newData['activity_time'] 			= checkParam('activity_time');
						$newData['status']					= checkParam('status');
						$newData['remarks'] 				= checkParam('remarks');
						
						if($operation == 'new'){ 							
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objActivities->saveActivities($newData)){
								$insertedId = $objActivities->getInsertedId();
								if($strRelatedOwner != ''){
									foreach($arrRelatedOwner AS $value){
										$newDataRelatedOwner = array();
										$newDataRelatedOwner['activity'] = $insertedId;
										$newDataRelatedOwner['related_owner_id'] = $value;
										$newDataRelatedOwner['related_owner'] = $objSalesPerson->getSalesPersonNameById($newDataRelatedOwner['related_owner_id']);
										$newDataRelatedOwner['created_by'] = $_SESSION['user_id'];
										$newDataRelatedOwner['created_date'] = date("Y-m-d H:i:s");
										if($objActivities->saveActivitiesRelatedOwner($newDataRelatedOwner)){
											insertAuditTrails('customer.activity.relatedowner', 'insert', "Customer details - Add activity related owner", $newDataRelatedOwner);										
										}
									}
								}
								if($strRelatedContact != ''){
									foreach($arrRelatedContact AS $value){
										$newDataRelatedContact = array();
										$newDataRelatedContact['activity'] = $insertedId;
										$newDataRelatedContact['related_contact_id'] = $value;
										$newDataRelatedContact['related_contact'] = $objContact->getContactFullNameById($newDataRelatedContact['related_contact_id']);
										$newDataRelatedContact['created_by'] = $_SESSION['user_id'];
										$newDataRelatedContact['created_date'] = date("Y-m-d H:i:s");
										if($objActivities->saveActivitiesRelatedContact($newDataRelatedContact)){
											insertAuditTrails('customer.activity.relatedcontact', 'insert', "Customer details - Add activity related contact", $newDataRelatedContact);										
										}
									}
								}
								insertAuditTrails('customer.activities', 'insert', "Customer details - Add activity", $newData);
								$output['success'] = true;
								$output['message'] = 'Activity has been successfully created.';
							}
						}else{
							$detailsData = $objActivities->getActivitiesData($id);
							if(empty($detailsData)){
								$output['message'] = "The record that you wish to view/edit no longer exists. It may have been deleted by another user.";
								break;
							}
							$newData['id'] = $id;
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objActivities->updateActivities($newData)){
								$objActivities->deleteActivitiesRelatedOwner($newData['id']);
								if($strRelatedOwner != ''){
									foreach($arrRelatedOwner AS $value){
										$newDataRelatedOwner = array();
										$newDataRelatedOwner['activity'] = $newData['id'];
										$newDataRelatedOwner['related_owner_id'] = $value;
										$newDataRelatedOwner['related_owner'] = $objSalesPerson->getSalesPersonNameById($newDataRelatedOwner['related_owner_id']);
										$newDataRelatedOwner['created_by'] = $_SESSION['user_id'];
										$newDataRelatedOwner['created_date'] = date("Y-m-d H:i:s");
										if($objActivities->saveActivitiesRelatedOwner($newDataRelatedOwner)){
											insertAuditTrails('customer.activity.relatedowner', 'update', "Customer details - Edit activity related owner", $newDataRelatedOwner);										
										}
									}
								}
								$objActivities->deleteActivitiesRelatedContact($newData['id']);
								if($strRelatedContact != ''){
									foreach($arrRelatedContact AS $value){
										$newDataRelatedContact = array();
										$newDataRelatedContact['activity'] = $newData['id'];
										$newDataRelatedContact['related_contact_id'] = $value;
										$newDataRelatedContact['related_contact'] = $objContact->getContactFullNameById($newDataRelatedContact['related_contact_id']);
										$newDataRelatedContact['created_by'] = $_SESSION['user_id'];
										$newDataRelatedContact['created_date'] = date("Y-m-d H:i:s");
										if($objActivities->saveActivitiesRelatedContact($newDataRelatedContact)){
											insertAuditTrails('customer.activity.relatedcontact', 'insert', "Customer details - Edit activity related contact", $newDataRelatedContact);										
										}
									}
								}
								insertAuditTrails('customer.activity.edit', 'update', "Customer details - Edit activity", $detailsData, $newData);
								$output['success'] = true;
								$output['message'] = 'Activity has been successfully updated.';
							}
						}
					break;
					case 'mark_completed_activity':
						$id = explode(';', checkParam('id'));
						$markId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);						
							if($objActivities->checkActivitiesExist($value)){
								array_push($markId, $value);
							}else{
								$output['message'] = "Activity does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($markId)){
							$output['success'] = true;
							$output['message'] = 'Activity has been successfully updated.';
							foreach($markId AS $value){
								$newData['id'] = $value;
								$newData['status'] = 0;
								$newData['modified_by'] = $_SESSION['user_id'];
								$newData['modified_date'] = date("Y-m-d H:i:s");						
								$detailsData = $objActivities->getActivitiesData($value);
								if($objActivities->updateActivities($newData)){
									insertAuditTrails('dashboard.activity.markascompleted', 'update', "Activities details - Edit activity", $detailsData, $newData);
								}
							}
						}
					break;
					case 'delete_activity':
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objActivities->checkActivitiesExist($value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Activity does not exist. Please refresh your browser.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected activity successfully deleted.";
							foreach($delId AS $value){
								$data = $objActivities->getActivitiesData($value);
								if($objActivities->deleteActivities($value)){
									insertAuditTrails('customer.activity.delete', 'delete', "Customer details - Delete activity", $data);
								}
							}
						}
					break;
					case 'list_importation':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
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
						
						$_SESSION['customers.list_importation_state'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objImportation->listImportationBase($condition, $start, $limit, "*");
						$output['total'] = $objImportation->getTotalRow();
						$output['success'] = true;
					break;
					case 'delete_importation':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objImportation->checkImportationExist('', $value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Importation data corrupted. Please try again.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected importation successfully deleted.";
							foreach($delId AS $value){
								$data = $objImportation->getBaseData($value);
								if($objImportation->deleteImportation('', $value)){
									insertAuditTrails('customers.import.view', 'delete', "", $data);
									$objImportation->deleteImportationRuleByBaseId($value);
									$objImportation->deleteImportationSelectionByBaseId($value);
									$objImportation->cleanUpImportationFile($importRepo, $data['ori_filename'], $data['filename'], $data['log_file']);
									$objImportation->dropTempTable($data['temp_table']);
								}
							}
						}
					break;
					case 'list_importation_rule':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$start = checkParam('start');
						$limit = checkParam('limit');
						$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$condition .= " AND `importation_id`='".$parent."' ";
						
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
						
						$_SESSION['customers.list_importation_rule_state'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objImportation->listImportationRule($condition, $start, $limit, "*");
						$output['total'] = $objImportation->getTotalRow();
						$output['success'] = true;
					break;
					case 'submit_importation_rule':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$operation = checkParam('operation');
						$id = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$newData = array();
						$newData['source'] = checkParam('source');
						$newData['target'] = checkParam('target');
						$newData['function'] = checkParam('fx');
						$newData['importation_id'] =  encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						if(!$objImportation->checkImportationExist('', $newData['importation_id'])){
							$output['message'] = "Importation data corrupted. Please try again.";
							break;
						}
						$baseData = $objImportation->getBaseData($newData['importation_id']);
						$comboSource = array();
						$comboTarget = $objCustomer->getImportationCombo($baseData['mode']);
						if($baseData['mode'] == 'navi_csv' || $baseData['mode'] == 'oz_excel'){
							$comboSource = $objImportation->getComboSource($baseData['temp_table']);
						}
						$newData['source_label'] = $objImportation->getRuleLabel($comboSource, $newData['source']);
						$newData['target_label'] = $objImportation->getRuleLabel($comboTarget, $newData['target']);
						if($operation == 'new'){
							if($objImportation->checkDuplicateRuleExist('', $newData['importation_id'], $newData['target'])){
								$output['message'] = "Duplicate rule detected. Please assign target is only pointed by one source.";
								break;
							}
							$newData['created_by'] = $_SESSION['user_id'];
							$newData['created_date'] = date("Y-m-d H:i:s");
							if($objImportation->saveImportation('rule', $newData)){
								insertAuditTrails('customers.import.view', 'insert', "rule", $newData);
								$output['success'] = true;
								$output['message'] = 'Importation rule has been successfully created.';
							}
						}else{
							$newData['id'] = $id;
							if(!$objImportation->checkImportationExist('rule', $newData['id'])){
								$output['message'] = "Importation rule data corrupted. Please try again.";
								break;
							}
							if($objImportation->checkDuplicateRuleExist($newData['id'], $newData['importation_id'], $newData['target'])){
								$output['message'] = "Duplicate rule detected. Please set only one target can be pointed by one source.";
								break;
							}
							$newData['modified_by'] = $_SESSION['user_id'];
							$newData['modified_date'] = date("Y-m-d H:i:s");
							if($objImportation->updateImportation('rule', $newData)){
								insertAuditTrails('customers.import.view', 'update', "rule", $baseData, $newData);
								$output['success'] = true;
								$output['message'] = 'Importation rule has been successfully updated.';
							}
						}
					break;
					case 'delete_importation_rule':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objImportation->checkImportationExist('rule', $value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Importation rule data corrupted. Please try again.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected importation rules successfully deleted.";
							foreach($delId AS $value){
								$data = $objImportation->getRuleData($value);
								if($objImportation->deleteImportation('rule', $value)){
									insertAuditTrails('customers.import.view', 'delete', "rule", $data);
								}
							}
						}
					break;
					case 'load_importation_rule':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$baseId =  encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$baseData = $objImportation->getBaseData($baseId);
						$comboTarget = $objCustomer->getImportationCombo($baseData['mode']);
						if(empty($baseData)){
							$output['message'] = "Importation rule data corrupted. Please try again.";
							break;
						}
						$defaultRule = $objCustomer->getImportationDefaultRule($baseData['mode']);
						if(empty($defaultRule)){
							$output['message'] = "Predefined importation rule does not available for this operation.";
							break;
						}
						$validation = $objImportation->validatePredefinedRule($baseId, $defaultRule, $baseData['mode'], $baseData['temp_table'], $comboTarget);
						if($validation['status']){
							$output['message'] = "Predefined importation successfully loaded.";
							$output['success'] = true;
						}else{
							$output['message'] = $validation['message'];
						}
					break;
					case 'list_importation_source':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$start = checkParam('start');
						$limit = checkParam('limit');
						$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$baseData = $objImportation->getBaseData($parent);
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = getFilterSQL($filter);
						$_SESSION['items.groups.list_importation_source_state'] = $condition." LIMIT ".$start.", ".($limit+1);
						if($baseData['mode'] == 'navi_csv' || $baseData['mode'] == 'oz_excel'){
							$output['table'] = $objImportation->listImportationSource($baseData['temp_table'], $condition, $start, $limit, "*");
						}
						$output['total'] = $objImportation->getTotalRow();
						$output['success'] = true;
					break;
					case 'add_importation_source':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$id = explode(';', checkParam('id'));
						$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$addId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if(!$objImportation->checkDuplicateSelectionExist($value, $parent)){
								array_push($addId, $value);
							}else{
								$output['message'] = "Some of the selected data already exists in the selection list. Please ensure that there are no duplicates and try again.";
								break 2;
							}
						}
						if(!empty($addId)){
							$output['success'] = true;
							$output['message'] = "Selected data successfully added.";
							$baseData = $objImportation->getBaseData($parent);
							foreach($addId AS $value){
								$dataSource = array();
								$newData = array();
								$newData['importation_id'] = $parent;
								$newData['selection_id'] = $value;
								if($baseData['mode'] == 'navi_csv' || $baseData['mode'] == 'oz_excel'){
									$dataSource = $objImportation->getTempData($value, $baseData['temp_table']);
									$newData['raw_data'] = addslashes(json_encode($dataSource));
								}
								$newData['created_by'] = $_SESSION['user_id'];
								$newData['created_date'] = date("Y-m-d H:i:s");
								if($objImportation->saveImportation('selection', $newData)){
									insertAuditTrails('customers.import.view', 'insert', "selection", $newData);
								}
							}
						}
					break;
					case 'delete_importation_sel':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$id = explode(';', checkParam('id'));
						$delId = array();
						foreach($id AS $value){
							$value = encryption(rawurldecode($value), $_SESSION['salt'], false);
							if($objImportation->checkImportationExist('selection', $value)){
								array_push($delId, $value);
							}else{
								$output['message'] = "Importation selection data corrupted. Please try again.";
								break 2;
							}
						}
						if(!empty($delId)){
							$output['success'] = true;
							$output['message'] = "Selected importation selection successfully deleted.";
							foreach($delId AS $value){
								$data = $objImportation->getSelectionData($value);
								if($objImportation->deleteImportation('selection', $value)){
									insertAuditTrails('customers.import.view', 'delete', "selection", $data);
								}
							}
						}
					break;
					case 'list_importation_sel':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$start = checkParam('start');
						$limit = checkParam('limit');
						$parent = encryption(rawurldecode(checkParam('parent')), $_SESSION['salt'], false);
						$filter = array();
						if(isset($_GET['filter'])){
							$filter = $_GET['filter'];
						}
						$condition = $objImportation->getFilterRawDataSQL($filter);
						$condition .= " AND `importation_id`='".$parent."' ";
						$_SESSION['customers.list_importation_selection_state'] = $condition." LIMIT ".$start.", ".($limit+1);
						$output['table'] = $objImportation->listImportationSelection($condition, $start, $limit, "*");
						$output['total'] = $objImportation->getTotalRow();
						$output['success'] = true;
					break;
					case 'do_importation':
						require DIR_LIBS.'/importation.class.php';
						$objImportation = new Importation($GLOBALS['myDB'], $importTable);
						$baseId = encryption(rawurldecode(checkParam('id')), $_SESSION['salt'], false);
						$baseData = $objImportation->getBaseData($baseId);
						$flag = checkParam('flag');
						$total = checkParam('total');
						$checkerror = checkParam('checkerror');
						$output['success'] = true;
						$output['done'] = false;
						$output['done_msg'] = '';
						
						$batch = 500;
						ini_set('max_execution_time', 7200);
						
						$logFile = $baseData['log_file'];
						if($flag == 0){
							$newData = array();	
							$newData['id'] = $baseData['id'];
							$newData['log_file'] = $objImportation->createLogFile($importRepo);
							$logFile = $newData['log_file'];
							$objImportation->updateImportation('', $newData);
						}
						
						$ruleData = $objImportation->getFinalRuleData($baseData['id']);
						$sourceData = array();
						if($baseData['mode'] == 'navi_csv' || $baseData['mode'] == 'oz_excel'){
							$sourceData = $objImportation->getSourceDataCSV($flag, $batch, $baseData['id'], $baseData['temp_table'], $baseData['exclude'], $ruleData['select']);
						}
						
						$importationStatus = $objCustomer->doImportation($objImportation, $flag, $ruleData, $sourceData, $baseData['stop_error'], $objSalesPerson);
						if($importationStatus['status'] == false || $checkerror == '1'){
							$objImportation->appendLogFile($importRepo, $logFile, $importationStatus['message']);
							if($baseData['stop_error'] == '1'){
								$output['done'] = true;
								$output['message'] = 'stop on error';
								$objImportation->appendLogFile($importRepo, $logFile, "[".date("Y-m-d H:i:s")."]			[stop error]			Importation stop on error...".PHP_EOL);
								$newData = array();	
								$newData['id'] = $baseData['id'];
								$newData['status'] = '2';
								$objImportation->updateImportation('', $newData);
								break;
							}
							$output['checkerror'] = '1';
						} else {
							$output['checkerror'] = '0';
						}
						$output['flag'] = $flag+$batch;
						if($output['flag'] >= $total){
							$output['flag'] = $total;
							$output['done'] = true;
							$newData = array();	
							$newData['id'] = $baseData['id'];
							if($importationStatus['status'] == false || $checkerror == '1'){
								$newData['status'] = '2';
								$output['message'] = 'done with error';
								$objImportation->appendLogFile($importRepo, $logFile, "[".date("Y-m-d H:i:s")."]			[done error]			Importation done with error...".PHP_EOL);								
							}else{
								$newData['status'] = '1';
								$output['message'] = 'done';
								$objImportation->appendLogFile($importRepo, $logFile, "[".date("Y-m-d H:i:s")."]			[done]				Importation done...".PHP_EOL);								
							}
							$objImportation->updateImportation('', $newData);
						}
					break;
					case 'email_change_password':
					
						$intCustomerID = checkParam('id');
						if($intCustomerID == ""){
							$output['message'] = "Invalid customer Id. Please try again.";
						} else {
							
							$arrCustomerDetails = $objCustomer->getCustomerData($intCustomerID);
							if(count($arrCustomerDetails)>0) {
								$strResetStatus = "9".str_pad($arrCustomerDetails['id'], 6, "0", STR_PAD_LEFT);
								$arrCustomerDetails["cid"] = $strResetStatus;
								//echo $strResetStatus;exit;
								if(empty($arrCustomerDetails["email"])) {
									$output['message'] = "Please provide email to send change password request.";
								} else {
									$changePasswordStatus = $objCatalogue->sendCustomerChangePasswordEmail($arrCustomerDetails);
									if($changePasswordStatus){
										$output['success'] = true;
									} else {
										$output['message'] = "Your account cannot be change password at this moment. Please try again.";
									}
								}
								
							} else {
								$output['message'] = "We cannot find any account with this customer id.";
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