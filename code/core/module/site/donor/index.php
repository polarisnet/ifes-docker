<?php
	require_once 'donor.class.php';
	require DIR_LIBS.'/user.class.php';
	$objUser = new User($GLOBALS['myDB']);
	$objDonor = new Donor($GLOBALS['myDB']);
	
	require_once DIR_LIBS.'/stripe-php/init.php';
	\Stripe\Stripe::setApiKey(STRIPE_PRIVATE_KEY);
	
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	$setting = array(
		"title" => SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" => "",
		"meta_description" => "",
		"center_dir" => DIR_ACTIVE_THEME."/donor/donor.php"
	);

	$actionData = $GLOBALS['seo']->getActionURL();
	$action 	= array_shift($actionData);
	$HTTP_AJAX 	= HTTP_ACTIVE_MODULE.'/ajax';
	$error 		= array('type' => 'error', 'title' => 'Error', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$warning 	= array('type' => 'warning', 'title' => 'Warning', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$message 	= array('type' => 'message', 'title' => 'Message', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$message2 	= array('type' => 'message', 'title' => 'Message', 'content' => '', 'position' => 'right', 'autoclose' => false);

	switch(MODULE_UID){
		default:
			$userData = $objUser->getUserData($_SESSION['user_id']);
			if($userData['stripe_cust_id'] != ''){
				$stripe_customer = \Stripe\Customer::retrieve($userData['stripe_cust_id']);
			}
			
			$listCountries = $objDonor->listCountries();
			
			$formCurrencySymbol = "&dollar;";
			$formCurrencyCode = "USD";
			$formCurrencyToogle = '<ul class="dropdown-menu">';
			if(REGION == "uk"){
				$formCurrencySymbol = "&pound;";
				$formCurrencyCode = "GBP";
			}else if(REGION == "us"){
				$formCurrencySymbol = "&dollar;";
				$formCurrencyCode = "USD";
			}
			if(REGION == "us"){
				$formCurrencyToogle .= "<li><a onclick=\"toggleCurrency('USD', '&dollar;');\">USD</a></li>";
			}else{
				$formCurrencyToogle .= "<li><a onclick=\"toggleCurrency('EUR', '&euro;');\">EUR</a></li><li><a onclick=\"toggleCurrency('GBP', '&pound;');\">GBP</a></li><li><a onclick=\"toggleCurrency('USD', '&dollar;');\">USD</a></li>";
			}
			$formCurrencyToogle .= '</ul>'; 
			
			$formNameFirst = "";
			$formNameLast = "";
			$formNameSpouse = ""; 
			$formAddress1 = "";
			$formAddress2 = "";
			$formCity = "";
			$formState = "";
			$formZIP = "";
			$formCountry = "";
			$formCountryCode = "";
			$formTelephoneMobile = "";
			$formTelephoneDaytime = "";
			$formTelephoneEvening = "";
			$formTelephoneDaytimeExtension = "";
			$formTelephoneEveningExtension = "";
			$formEmail = "";
			
			$formBillAddress1 = "";
			$formBillAddress2 = "";
			$formBillCity = "";
			$formBillState = "";
			$formBillZIP = "";
			$formBillCountry = "";
			
			$formNameFirst = $userData['first_name'];
			$formNameLast = $userData['last_name'];
			$donorName = $formNameFirst." ".$formNameLast;
			$donorAccountNumbers = "-";
			
			$formAddress1 = $userData['mailing_address1'];
			$formAddress2 = $userData['mailing_address2'];
			$formZIP = $userData['mailing_zipcode'];
			$formCity = $userData['mailing_city'];
			$formState = $userData['mailing_state'];
			$formCountryISO = $userData['mailing_country'];
			
			$key = array_search($formCountryISO, array_column($listCountries, 'iso'));
			$formCountry = $listCountries[$key]['name'];
			
			if($userData['phone_country'] !== ""){
				$formCountryCode = $userData['phone_country'];
			}else{
				$formCountryCode = $userData['mailing_country'];
			}
			
			$formTelephoneMobile = $userData['phone'];
			$formTelephoneDaytime = $userData['phone_day'];
			$formTelephoneEvening = $userData['phone_night'];
			$formTelephoneDaytimeExtension = $userData['phone_day_extension'];
			$formTelephoneEveningExtension = $userData['phone_night_extension'];
			
			$formBillAddress1 =  $userData['billing_address1'];
			$formBillAddress2 =  $userData['billing_address2'];
			$formBillCity =  $userData['billing_city'];
			$formBillState =  $userData['billing_state'];
			$formBillZIP =  $userData['billing_zipcode'];
			$formBillCountry =  $userData['billing_country'];
			
			$formEmail = $userData['email']; 
			$subscriptionList = $userData['subscriptions']; 
			$formLanguage = $userData['preferred_language'];
			
			$subscriptionArray = explode(",", $subscriptionList);
			
			foreach($subscriptionArray as $value){
				$$value = true;
			}
			
			$firstDate = $objDonor->getFirstDateByUserID($userData['id']);
			$total = $objDonor->getGivingSumByUserID($userData['id']);
			
			$fullAddress = $formAddress1."<br>";
			$fullAddress.= $formZIP." ".$formCity." ".$formState."<br>";
			$fullAddress.= $formCountry;
			
			$fullContact = "Telephone:<br>";
			
			if($formTelephoneDaytime != ""){
				$fullContact.= "Daytime +".$formTelephoneDaytime."<br>";
			}
			if($formTelephoneEvening != ""){
				$fullContact.= "Night +".$formTelephoneEvening."<br>";
			}
			if($formTelephoneMobile != ""){
				$fullContact.= "Mobile +".$formTelephoneMobile."<br>";
			}
			$fullEmail = "Email Address:<br>".$formEmail;
			
			
			$condition = " AND d.`user_id` = '".$userData['id']."'";
			$condition .= " ORDER BY d.`created_date` DESC";
			$recentGivings = $objDonor->listGivingHistory($condition, true);
			
			if(empty($recentGivings)){
				$recentGivingTemplate = '<tr><td> No giving history found </tr></td>';
			}else{
				$recentGivingTemplate = "";
			}
			
			foreach($recentGivings as $key =>$value){
				$recentGivingTemplate .= "<tr>";
				$recentGivingTemplate .= "<td>".$value['designation']."</td>";
				$recentGivingTemplate .= "<td>".$value['amount']."</td>";
				$recentGivingTemplate .= "<td>".$value['date']."</td>";
				$recentGivingTemplate .= "<td>".$value['link']."</td>";
				$recentGivingTemplate .= "</tr>";
			}
			
			if(!empty($_POST)){
				$submitMode = checkParam('submit_mode');
				if($submitMode == "reset_password"){
					$password_length = 8;
					$formCurrentPassword = checkParam('donor-password-input-current');
					$formPassword = checkParam('donor-password-input-new');
					$formRetypePassword = checkParam('donor-password-input-confirm');
					
					$credentialData = $objUser->getLoginCredential($userData['username']);
					$hashPassword = hashPassword(strtolower($userData['username']), $formCurrentPassword, $credentialData['salt']);
					
					if($credentialData['password'] !== $hashPassword){
						$error['content'] = 'Invalid Credentials, please enter correct password.';
						$error['autoclose'] = false;
						break;
					}

					if($formPassword != $formRetypePassword){
						$error['content'] = 'Password does not match with retype password. Please input match password.';
						$error['autoclose'] = false;
						break;
					}

					/*
					Check for password strength, password should be at least 8 characters, 
					contain at least one number, 
					contain at least one lowercase letter, 
					contain at least one uppercase letter, 
					contain at least one special character. 
					*/
					
					if(strlen($formPassword) < $password_length){
						$error['content'] = 'Password length must be more than 8 characters';
						$error['autoclose'] = false;
						break;
					}
					
					if(!preg_match("#[0-9]+#", $formPassword)){
						$error['content'] = 'Password must have at least one number';
						$error['autoclose'] = false;
						break;
					}
					
					if(!preg_match("#[a-z]+#", $formPassword)){
						$error['content'] = 'Password must have at least one lowercase alphabet';
						$error['autoclose'] = false;
						break;
					}
					
					if(!preg_match("#[A-Z]+#", $formPassword)){
						$error['content'] = 'Password must have at least one uppercase alphabet';
						$error['autoclose'] = false;
						break;
					}
					
					if(!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $formPassword)){
						$error['content'] = 'Password must have at least one special character';
						$error['autoclose'] = false;
						break;
					}

					$newData 					= array();
					$newData['id']		 		= $userData['id'];
					$newData['password'] 		= hashPassword(strtolower($_SESSION['username']), $formPassword, $userData['salt']);				
					$newData['modified_by'] 	= $_SESSION['user_id'];
					$newData['modified_date'] 	= date("Y-m-d H:i:s");
					
					if($objUser->updateUser($newData)){
						insertAuditTrails('donor.resetpassword', 'resetpassword', "", $userData, $newData);					
						$message['content'] = 'Password changed successfully!';						
						break;
					}
				}
				
				if($submitMode == "profile_update"){
					$formNameFirst = checkParam("donor-profile-input-firstname");
					$formNameLast = checkParam("donor-profile-input-lastname");
					$formNameSpouse = checkParam("donor-profile-input-spouse");
					$formAddress1 = checkParam("donor-profile-input-address1");
					$formAddress2 = checkParam("donor-profile-input-address2");
					$formCity = checkParam("donor-profile-input-city");
					$formState = checkParam("donor-profile-input-state");
					$formZIP = checkParam("donor-profile-input-zipcode");
					$formCountry = checkParam("donor-profile-input-country");
					$formCountryCode = checkParam("donor-profile-input-countrycode");
					$formTelephoneMobile = checkParam("donor-profile-input-mobile");
					$formTelephoneDaytime = checkParam("donor-profile-input-daytime");
					$formTelephoneEvening = checkParam("donor-profile-input-evening");
					$formTelephoneDaytimeExtension = checkParam("donor-profile-input-extension-daytime");
					$formTelephoneEveningExtension = checkParam("donor-profile-input-extension-evening");
					$formEmail = checkParam("donor-profile-input-email");
					
					//TODO: Validation
					
					$newData 							= array();
					$newData['id']		 				= $userData['id'];
					$newData['username'] 				= $formEmail;
					$newData['first_name'] 				= $formNameFirst;
					$newData['last_name'] 				= $formNameLast;
					$newData['spouse_name'] 			= $formNameSpouse; 
					$newData['mailing_fullname'] 		= $formNameFirst." ".$formNameLast;
					$newData['mailing_address1'] 		= $formAddress1;
					$newData['mailing_address2'] 		= $formAddress2;
					$newData['mailing_city'] 			= $formCity;
					$newData['mailing_state'] 			= $formState;
					$newData['mailing_country'] 		= $formCountry;
					$newData['mailing_zipcode'] 		= $formZIP;
					$newData['mailing_email'] 			= $formEmail;
					$newData['email'] 					= $formEmail;
					$newData['phone'] 					= $formTelephoneMobile;
					$newData['mailing_phone'] 			= $formTelephoneMobile;
					$newData['billing_phone'] 			= $formTelephoneMobile;
					$newData['phone_country'] 			= $formCountryCode;
					$newData['phone_day'] 				= $formTelephoneDaytime;
					$newData['phone_night'] 			= $formTelephoneEvening;
					$newData['phone_day_extension'] 	= $formTelephoneDaytimeExtension;
					$newData['phone_night_extension'] 	= $formTelephoneEveningExtension;
					$newData['modified_by'] 			= $_SESSION['user_id'];
					$newData['modified_date'] 			= date("Y-m-d H:i:s");
					
					//Check if Add Billing is on
					$formBillData = checkParam("add-billing");
					if($formBillData){
						$formBillAddress1 = checkParam("donor-billing-input-address1");
						$formBillAddress2 = checkParam("donor-billing-input-address2");
						$formBillCity = checkParam("donor-billing-input-city");
						$formBillState = checkParam("donor-billing-input-state");
						$formBillZIP = checkParam("donor-billing-input-zipcode");
						$formBillCountry = checkParam("donor-billing-input-country");

						$newData['billing_address1'] 		= $formBillAddress1;
						$newData['billing_address2'] 		= $formBillAddress2;
						$newData['billing_city'] 			= $formBillCity;
						$newData['billing_state'] 			= $formBillState;
						$newData['billing_country'] 		= $formBillCountry;
						$newData['billing_zipcode'] 		= $formBillZIP;
					}
					
					if($objUser->updateUser($newData)){
						insertAuditTrails('donor.profile.update', 'update', "", $userData, $newData);					
						$message['content'] = 'Donor profile have been updated!';						
						break;
					}

				}
				
				if($submitMode == "comm_preference"){
					$formSubscription = checkParam("subscriptions");
					$formSubscription = implode(',', $formSubscription);
					$formLanguage = checkParam("radio-language");
					
					$newData 						= array();
					$newData['id']		 			= $userData['id'];
					$newData['subscriptions']		= $formSubscription;
					$newData['preferred_language'] 	= $formLanguage;				
					$newData['modified_by'] 		= $_SESSION['user_id'];
					$newData['modified_date'] 		= date("Y-m-d H:i:s");
					
					if($objUser->updateUser($newData)){
						insertAuditTrails('donor.updateCommPreference', 'update', "", $userData, $newData);					
						$message['content'] = 'Communication preference saved successfully!';						
						break;
					}
				}
				
				if($submitMode == "subscription_update"){
					$id = checkParam("subscription-input-id");
					$formSubscriptionAmount = checkParam("subscription-input-amount");
					$formSubscriptionCurrCode = checkParam("subscription-input-currency");
					
					$id = encryption($id, $_SESSION['salt'], false);
					
					$newData 						= array();
					$newData['id']		 			= $id;
					$newData['amount']				= $formSubscriptionAmount;
					$newData['currency_code']		= $formSubscriptionCurrCode;
					$newData['modified_by']			= $_SESSION['user_id'];
					$newData['modified_date']		= date("Y-m-d H:i:s");
					
					if($objDonor->updateSubscription($newData)){
						insertAuditTrails('subscriptions', 'edit', "", $newData);					
						$message['content'] = 'Subscription updated successfully!';						
						break;
					}
				}
				
				if($submitMode == "payment_new"){
					$formCardCustName = checkParam("payment-cc-customname");
					$formCardHolderName = checkParam("payment-cc-name");
					$formStripeToken = checkParam("stripeToken");
					
					
					/** Stripe Card - Start **/							
					try{
						$stripe_card = $stripe_customer->sources->create(array(
							"source" => $formStripeToken
						));
					} catch (\Stripe\Error\RateLimit $e) {
						// Too many requests made to the API too quickly
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\InvalidRequest $e) {
						// Invalid parameters were supplied to Stripe's API
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\Authentication $e) {
						// Authentication with Stripe's API failed
						// (maybe you changed API keys recently)
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\ApiConnection $e) {
					  // Network communication with Stripe failed
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\Base $e) {
					  // Display a very generic error to the user, and maybe send
					  // yourself an email
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (Exception $e) {
					  // Something else happened, completely unrelated to Stripe
						$error['content'] = addslashes($e->getMessage());
						break;
					}
					
					/** Stripe Card - End **/
					
					$data 						= array();
					$data['user_id']		 	= $userData['id'];
					$data['custom_name']		= $formCardCustName;
					$data['name']				= $formCardHolderName;
					$data['stripe_source_id']	= $stripe_card->id;
					$data['type'] 				= "card";
					$data['type_1'] 			= "";
					$data['display_info'] 		= "1";
					$data['number'] 			= $stripe_card->last4; //last 4 only
					$data['name_1'] 			= str_pad($stripe_card->exp_month, 2, "0", STR_PAD_LEFT)."/".substr($stripe_card->exp_year, -2);
					$data['created_by']			= $_SESSION['user_id'];
					$data['created_date']		= date("Y-m-d H:i:s");
										
					if($objDonor->savePaymentMethod($data)){
						insertAuditTrails('payments', 'new', "", $data);					
						$message['content'] = 'New payment method saved successfully!';						
						break;
					}
				}
				
				if($submitMode == "payment_update"){
					$id = checkParam("payment-cc-id");
					$formCardCustName = checkParam("payment-cc-customname");
					$formCardName = checkParam("payment-cc-name");
					$formCardExpiration = checkParam("payment-cc-expiration");
					
					$id = encryption($id, $_SESSION['salt'], false);
					$exp_date = explode('/', $formCardExpiration);
					$exp_month = intval($exp_date[0]);
					$exp_year = intval($exp_date[1]);
					
					/** Stripe Card - Start **/
					$paymentData = $objDonor->getPaymentData($id);
					
					try{
						$stripe_card = $stripe_customer->sources->retrieve($paymentData['stripe_source_id']);
						$stripe_card->exp_month = $exp_month;
						$stripe_card->exp_year = $exp_year;
						$stripe_card->save();
					} catch (\Stripe\Error\RateLimit $e) {
						// Too many requests made to the API too quickly
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\InvalidRequest $e) {
						// Invalid parameters were supplied to Stripe's API
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\Authentication $e) {
						// Authentication with Stripe's API failed
						// (maybe you changed API keys recently)
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\ApiConnection $e) {
					  // Network communication with Stripe failed
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (\Stripe\Error\Base $e) {
					  // Display a very generic error to the user, and maybe send
					  // yourself an email
						$error['content'] = addslashes($e->getMessage());
						break;
					} catch (Exception $e) {
					  // Something else happened, completely unrelated to Stripe
						$error['content'] = addslashes($e->getMessage());
						break;
					}
					
					/** Stripe Card - End **/
					
					$newData 						= array();
					$newData['id']		 			= $id;
					$newData['custom_name']			= $formCardCustName;
					$newData['name']				= $formCardName;
					$newData['name_1']				= $formCardExpiration;
					$newData['modified_by']			= $_SESSION['user_id'];
					$newData['modified_date']		= date("Y-m-d H:i:s");
					
					if($objDonor->updatePaymentMethod($newData)){
						insertAuditTrails('payments', 'edit', "", $newData);					
						$message['content'] = 'Payment method updated successfully!';						
						break;
					}
				}
			}
			if($action == "ajax"){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case "list_giving":
						$userData = $objUser->getUserData($_SESSION['user_id']);
						
						$output['data'] = array();
						$dateStart = checkParam('search_date_start');
						$dateStart .= " 23:59:59";
						$dateEnd = checkParam('search_date_end');
						$dateEnd .= " 23:59:59";
						$condition = " AND d.`user_id` = '".$userData['id']."'";
						$condition .= " AND d.`created_date` >= '".$dateStart."'";
						$condition .= " AND d.`created_date` <= '".$dateEnd."'";
						$condition .= " ORDER BY d.`created_date` DESC";
						
						$givingHistory = $objDonor->listGivingHistory($condition);
						$output['data'] = $givingHistory;
						$output['success'] = true;						
					break;
					case "list_subscription":
						$userData = $objUser->getUserData($_SESSION['user_id']);
						
						$output['data'] = array();
						
						$condition = " AND `user_id` = '".$userData['id']."'";
						$condition .= " ORDER BY `created_date` DESC";
						$subscriptionHistory = $objDonor->listSubscription($condition);
						
						$output['data'] = $subscriptionHistory;
						$output['success'] = true;
					break;
					case "get_subscription_data":
						$id = checkParam('enc_id');
						$id = encryption($id, $_SESSION['salt'], false);
						
						$data = $objDonor->getSubscriptionData($id);
						if($data !== ""){
							$output['success'] = true;							
							$output['data'] = $data;					
							break;
						}
					break;
					case "delete_subscription":
						$id = checkParam('enc_id');
						$id = encryption($id, $_SESSION['salt'], false);
						
						$data = $objDonor->getSubscriptionData($id);
						if($objDonor->deleteSubscription($id)){
							insertAuditTrails('subscriptions', 'delete', "", $data);
							$output['success'] = true;
							$message['content'] = 'Subscription deleted successfully!';						
							break;
						}
					break;
					case "list_payment":
						$userData = $objUser->getUserData($_SESSION['user_id']);
						
						$output['data'] = array();
						
						$condition = " AND `user_id` = '".$userData['id']."'";
						$condition .= " ORDER BY `created_date` DESC";
						$paymentMethods = $objDonor->listPaymentMethods($condition);
						
						$output['data'] = $paymentMethods;
						$output['success'] = true;
					break;
					case "get_payment_data":
						$id = checkParam('enc_id');
						$id = encryption($id, $_SESSION['salt'], false);
						
						$data = $objDonor->getPaymentData($id);
						if($data !== ""){
							$output['success'] = true;							
							$output['data'] = $data;					
							break;
						}
					break;
					case "delete_payment":
						$id = checkParam('enc_id');
						$id = encryption($id, $_SESSION['salt'], false);
						
						$data = $objDonor->getPaymentData($id);
						
						/** Stripe Card - Start **/
						$paymentData = $objDonor->getPaymentData($id);
						
						try{
							$stripe_card = $stripe_customer->sources->retrieve($paymentData['stripe_source_id'])->delete();
						} catch (\Stripe\Error\RateLimit $e) {
							// Too many requests made to the API too quickly
							$error['content'] = addslashes($e->getMessage());
							break;
						} catch (\Stripe\Error\InvalidRequest $e) {
							// Invalid parameters were supplied to Stripe's API
							$error['content'] = addslashes($e->getMessage());
							break;
						} catch (\Stripe\Error\Authentication $e) {
							// Authentication with Stripe's API failed
							// (maybe you changed API keys recently)
							$error['content'] = addslashes($e->getMessage());
							break;
						} catch (\Stripe\Error\ApiConnection $e) {
						  // Network communication with Stripe failed
							$error['content'] = addslashes($e->getMessage());
							break;
						} catch (\Stripe\Error\Base $e) {
						  // Display a very generic error to the user, and maybe send
						  // yourself an email
							$error['content'] = addslashes($e->getMessage());
							break;
						} catch (Exception $e) {
						  // Something else happened, completely unrelated to Stripe
							$error['content'] = addslashes($e->getMessage());
							break;
						}
						
						/** Stripe Card - End **/
						
						if($objDonor->deletePaymentMethod($id)){
							$output['success'] = true;							
							$output['data'] = $data;					
							break;
						}
					break;
					
				}
				echo json_encode($output);
				exit;
			}
		break;
	}
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>