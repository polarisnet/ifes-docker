<?php
	require_once DIR_LIBS.'/thankq.pdo.class.php';
	$objThankQPDO = new ThankQPDO();

	require_once 'giving.class.php';
	$objGiving = new Giving($GLOBALS['myDB']);
	
	require_once DIR_LIBS.'/stripe-php/init.php';
	\Stripe\Stripe::setApiKey(STRIPE_PRIVATE_KEY);

	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	$setting = array(
		"title" => SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" => "",
		"meta_description" => "",
		"center_dir" => DIR_ACTIVE_PUBLIC_THEME."/giving/giving.php"
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
			$isLogin = matchCookieSession();
			if($isLogin){
				if(isset($_SESSION["login"]["give_behalf"])&&$_SESSION["login"]["give_behalf"]&&isset($_SESSION["login"]["give_behalf_username"])&&$_SESSION["login"]["give_behalf_username"]!="") {
					$formDonorAccountData = $objGiving->getDonorAccountData($_SESSION["login"]["give_behalf_username"]);
				} else {
					$formDonorAccountData = $objGiving->getDonorAccountData($_SESSION['username']);
				}
				//echo "<pre>";print_r($formDonorAccountData);echo "</pre>";exit;
				$stripe_customer = \Stripe\Customer::retrieve($formDonorAccountData['stripe_cust_id']);
			}
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

			if($action == "ajax"){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case "login":
						require DIR_LIBS.'/user.class.php';
						$objUser = new User($GLOBALS['myDB']);

						$username = checkParam('username');
						$password = checkParam('password');

						$credentialData = $objGiving->getDonorAccountData($username);
						$output['success'] = true;
						$output['login'] = false;
						if(empty($credentialData)){
							$output['message'] = 'Invalid login details.';
						}else{
							$hashPassword = hashPassword(strtolower($username), $password, $credentialData['salt']);
							if($credentialData['status'] == '0'){
								$output['message'] = 'Your account has been deactivated.';
							}else if(!empty($credentialData) && $credentialData['password'] == $hashPassword){
								if($objUser->createLoginSession($username)){
									$objUser->createLoginCookies("1", $username);
								}

								$trails = array();
								$trails['session'] = session_id();
								$trails['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
								$trails['ip_address'] = $_SERVER['REMOTE_ADDR'];
								insertAuditTrails('', 'login', json_encode($trails));
							
								setCookieValue($credentialData['username'], 'remember');
								$output['login'] = true;
							}
						}
					break;
					case "send_reset_password":
						$resetEmail = checkParam('email');
						//TODO: code here for reset password
						$output['success'] = true;
					break;
					case "search_gift_catalog":
						$page = checkParam('page');
						$searchType = checkParam('type');
						$searchQuery = checkParam('query');
						$currencyCode = checkParam('currency_code');
						$currencySymbol = checkParam('currency_symbol');
						$condition = " AND destinationgroup IS NOT NULL ";
						if($searchQuery != ""){
							$condition .= " AND LOWER(destinationdescription) LIKE '%".strtolower($searchQuery)."%'";
						}
						if($searchType == "staff"){
							$condition .= " ORDER BY FIELD (destinationgroup, 'Staff-Global Ministries', 'Staff-International Services', 'Staff-National Movement', 'Staff-Region', 'Ministries-General', 'Ministries-Global', 'Ministries-International Services', 'Ministries-Region', 'National Movement'), destinationdescription ASC";
						}else if($searchType == "ministry"){
							$condition .= " ORDER BY FIELD (destinationgroup, 'Ministries-General', 'Ministries-Global', 'Ministries-International Services', 'Ministries-Region', 'National Movement', 'Staff-Global Ministries', 'Staff-International Services', 'Staff-National Movement', 'Staff-Region'), destinationdescription ASC";
						}else if($searchType == "movement"){
							$condition .= " ORDER BY FIELD (destinationgroup, 'National Movement', 'Ministries-General', 'Ministries-Global', 'Ministries-International Services', 'Ministries-Region', 'Staff-Global Ministries', 'Staff-International Services', 'Staff-National Movement', 'Staff-Region'), destinationdescription ASC";
						}
						$searchResult = $objThankQPDO->listDestinationCodes($condition." LIMIT ".($page*50).", 50");
						if(is_array($searchResult) && !empty($searchResult)){
							$totalSearchResult = $objThankQPDO->countDestinationCodes($condition);
							$output['total_all'] = intval($totalSearchResult[0]['total']);
							$output['total'] = count($searchResult);
						}else{
							$output['total'] = 0;
						}
						foreach($searchResult AS $resultKey => $result){
							$searchResult[$resultKey]['destinationdescription'] = ucwords($result['destinationdescription']);	
						}
						$output['result'] = $searchResult;
						$output['success'] = true;
					break;
					case "get_cc_details":
						$creditCardId = checkParam('id');
						$creditCardData = $objGiving->getPaymentData($creditCardId);
						
						if(!empty($creditCardData) && $creditCardData['user_id'] == $formDonorAccountData['id'] && $creditCardData['display_info'] == "1"){
							$output['valid'] = true;
							$output['number'] = $creditCardData['number'];
							$output['name'] = $creditCardData['name'];
							$output['expiration'] = $creditCardData['name_1'];
						}else{
							$output['valid'] = false;
						}
						$output['success'] = true;
					break;
				}
				echo json_encode($output);
				exit;
			}

			$listCustomBanner = $objGiving->listCustomBanner();
			$listOfferingEvents = $objThankQPDO->listOfferingEvents();
			$listCreditCards = array();
			if($isLogin){
				$listCreditCards = $objGiving->listCreditCards($formDonorAccountData['id']);
			}
			$listCountries = $objGiving->listCountries();

			$formPaymentUSPaymode = "cc";

			$formPaymentUKExtraAid = "";
			$formPaymentUKExtraAidDate = "";

			$formPaymentECheckAccNo = "";
			$formPaymentECheckRouterNo = "";
			$formPaymentECheckBankName = "";
			$formPaymentECheckName = "";
			$formPaymentECheckType = "";

			$formPaymentCCProcessFee = "";
			$formPaymentCCSelect = "";
			$formPaymentCCNumber = "";
			$formPaymentCCName = "";
			$formPaymentCCExpiration = "";
			$formPaymentCCCVV = "";

			if($isLogin){
				$formPaymentBillingName = $formDonorAccountData['billing_fullname'];
				$formPaymentBillingAddress1 = $formDonorAccountData['billing_address1'];
				$formPaymentBillingAddress2 = $formDonorAccountData['billing_address2'];
				$formPaymentBillingCity = $formDonorAccountData['billing_city'];
				$formPaymentBillingState = $formDonorAccountData['billing_state'];
				$formPaymentBillingZipcode = $formDonorAccountData['billing_zipcode'];
				$formPaymentBillingCountry = $formDonorAccountData['billing_country'];
				$formPaymentBillingEmail = $formDonorAccountData['email'];
				$formPaymentBillingPhone = $formDonorAccountData['phone_day'];
				$formPaymentBillingCountryCode = $formDonorAccountData['phone_country'];
				$formPaymentBillingPhoneExtension = $formDonorAccountData['phone_day_extension'];
				$stripeCustomerID = $formDonorAccountData['stripe_cust_id'];
			}else{
				$formPaymentBillingName = "";
				$formPaymentBillingAddress1 = "";
				$formPaymentBillingAddress2 = "";
				$formPaymentBillingCity = "";
				$formPaymentBillingState = "";
				$formPaymentBillingZipcode = "";
				if(REGION == "us"){
					$formPaymentBillingCountry = "us";
				}else if(REGION == "uk"){
					$formPaymentBillingCountry = "uk";
				}else{
					$formPaymentBillingCountry = "";
				}
				$formPaymentBillingEmail = "";
				$formPaymentBillingPhone = "";
				$formPaymentBillingCountryCode = "";
				$formPaymentBillingPhoneExtension = "";
			}

			$formPaymentAddMailing = "";
			if($isLogin){
				$formPaymentMailingName = $formDonorAccountData['mailing_fullname'];
				$formPaymentMailingAddress1 = $formDonorAccountData['mailing_address1'];
				$formPaymentMailingAddress2 = $formDonorAccountData['mailing_address2'];
				$formPaymentMailingCity = $formDonorAccountData['mailing_city'];
				$formPaymentMailingState = $formDonorAccountData['mailing_state'];
				$formPaymentMailingZipcode = $formDonorAccountData['mailing_zipcode'];
				$formPaymentMailingCountry = $formDonorAccountData['mailing_country'];
				/*
				//DISABLE: Remove phone & email for mailing address, billing & mailing will share the same email and phone
				//$formPaymentMailingEmail = $formDonorAccountData['mailing_email'];
				//$formPaymentMailingPhone = $formDonorAccountData['mailing_phone'];
				*/
			}else{
				$formPaymentMailingName = "";
				$formPaymentMailingAddress1 = "";
				$formPaymentMailingAddress2 = "";
				$formPaymentMailingCity = "";
				$formPaymentMailingState = "";
				$formPaymentMailingZipcode = "";
				$formPaymentMailingCountry = "";
				$formPaymentMailingEmail = "";
				$formPaymentMailingPhone = "";
			}

			$formPaymentCreateAccount = "";
			$formPaymentAccountPassword = "";
			$formPaymentAccountConfirmPassword = "";

			$formPaymentSaveInformation = "";

			$formPaymentPreferredReceipt = "email";

			if($isLogin && REGION == "us"){
				if($formDonorAccountData['newsletter_us_weekly'] == "1"){
					$formNewsletterUSWeekly = "on";
				}else{
					$formNewsletterUSWeekly = "";
				}
				if($formDonorAccountData['newsletter_us_bimonthly'] == "1"){
					$formNewsletterUSBimonthly = "on";
				}else{
					$formNewsletterUSBimonthly = "";
				}
				$formNewsletterUSBimonthlyMode = $formDonorAccountData['newsletter_us_bimonthly_mode'];
			}else{
				$formNewsletterUSWeekly = "on";
				$formNewsletterUSBimonthly = "on";
				$formNewsletterUSBimonthlyMode = "email";
			}

			if($isLogin && REGION == "uk"){
				if($formDonorAccountData['newsletter_uk_email'] == "1"){
					$formNewsletterUKEmail = $formDonorAccountData['newsletter_uk_email'];
				}else{
					$formNewsletterUKEmail = "";
				}
				if($formDonorAccountData['newsletter_uk_not'] == "1"){
					$formNewsletterUKNot = $formDonorAccountData['newsletter_uk_not'];
				}else{
					$formNewsletterUKNot = "";
				}
				if($formDonorAccountData['newsletter_uk_email_weekly'] == "1"){
					$formNewsletterUKEmailWeekly = $formDonorAccountData['newsletter_uk_email_weekly'];
				}else{
					$formNewsletterUKEmailWeekly = "";
				}
				if($formDonorAccountData['newsletter_uk_contact_email'] == "1"){
					$formNewsletterUKContactEmail = $formDonorAccountData['newsletter_uk_contact_email'];
				}else{
					$formNewsletterUKContactEmail = "";
				}
				if($formDonorAccountData['newsletter_uk_contact_post'] == "1"){
					$formNewsletterUKContactPost = $formDonorAccountData['newsletter_uk_contact_post'];
				}else{
					$formNewsletterUKContactPost = "";
				}
				if($formDonorAccountData['newsletter_uk_contact_phone'] == "1"){
					$formNewsletterUKContactPhone = $formDonorAccountData['newsletter_uk_contact_phone'];
				}else{
					$formNewsletterUKContactPhone = "";
				}
			}else{
				$formNewsletterUKEmail = "";
				$formNewsletterUKNot = "";
				$formNewsletterUKEmailWeekly = "";
				$formNewsletterUKContactEmail = "on";
				$formNewsletterUKContactPost = "on";
				$formNewsletterUKContactPhone = "on";
			}

			if($isLogin && REGION != "us" && REGION != "uk"){
				if($formDonorAccountData['newsletter_row_weekly'] == "1"){
					$formNewsletterROWWeekly = "on";
				}else{
					$formNewsletterROWWeekly = "";
				}
				if($formDonorAccountData['newsletter_row_yearly'] == "1"){
					$formNewsletterROWYearly = "on";
				}else{
					$formNewsletterROWYearly = "";
				}
				if($formDonorAccountData['newsletter_row_email'] == "1"){
					$formNewsletterROWEmail = "on";
				}else{
					$formNewsletterROWEmail = "";
				}
				if($formDonorAccountData['newsletter_row_post'] == "1"){
					$formNewsletterROWPost = "on";
				}else{
					$formNewsletterROWPost = "";
				}
				if($formDonorAccountData['newsletter_row_phone'] == "1"){
					$formNewsletterROWPhone = "on";
				}else{
					$formNewsletterROWPhone = "";
				}
			}else{
				$formNewsletterROWWeekly = "on";
				$formNewsletterROWYearly = "on";
				$formNewsletterROWEmail = "on";
				$formNewsletterROWPost = "on";
				$formNewsletterROWPhone = "on";
			}

			$formGiftLists = array();
			if(!empty($_POST)){
				if(REGION == 'ca'){
					$changeRegion = checkParam('canada-select-region');
					if($changeRegion == ""){
						$error['content'] = "Please select a region where you are reside in.";
					}else{
						$newUserData = array();
						if(isset($_SESSION["login"]["give_behalf"])&&$_SESSION["login"]["give_behalf"]&&isset($_SESSION["login"]["give_behalf_id"])&&$_SESSION["login"]["give_behalf_id"]!="") {
							$newUserData['id'] = $_SESSION["login"]["give_behalf_id"];
						} else {
							$newUserData['id'] = $_SESSION['user_id'];
						}
						$newUserData['region'] = $changeRegion;
						$newUserData['modified_by'] = $_SESSION['user_id'];
						$newUserData['modified_date'] = date("Y-m-d H:i:s");
						$GLOBALS['myDB']->update('sys_users', $newUserData, "`id`='".$newUserData['id']."'");
						header('Location: '.getModuleURL('giving'));
						exit;
					}
					break;
				}

				$formLoginMode = checkParam('login-mode');
				$formStripeToken = checkParam('stripeToken');
				
				$formCurrencyCode = checkParam('submit-currency-code');
				$formCurrencySymbol = checkParam('submit-currency-symbol');

				$formPaymentUSPaymode = checkParam('payment-us-paymode');

				$formPaymentUKExtraAid = checkParam('payment-uk-extra-aid');
				$formPaymentUKExtraAidDate = checkParam('payment-uk-extra-aid-date');

				$formPaymentECheckAccNo = checkParam('payment-echeck-acc-no');
				$formPaymentECheckRouterNo = checkParam('payment-echeck-router-no');
				$formPaymentECheckBankName = checkParam('payment-echeck-bank-name');
				$formPaymentECheckName = checkParam('payment-echeck-name');
				$formPaymentECheckType = checkParam('payment-echeck-type');

				$formPaymentCCProcessFee = checkParam('payment-cc-process-fee');
				$formPaymentCCSelect = checkParam('payment-cc-select');
				$formPaymentCCMode = checkParam('payment-cc-mode');
				$formPaymentCCNumber = checkParam('payment-cc-number');
				$formPaymentCCName = checkParam('payment-cc-name');
				$formPaymentCCExpiration = checkParam('payment-cc-expiration');
				$formPaymentCCCVV = checkParam('payment-cc-cvv');

				if($formLoginMode != "1"){
					$formPaymentBillingName = checkParam('payment-billing-name');
					$formPaymentBillingAddress1 = checkParam('payment-billing-address1');
					$formPaymentBillingAddress2 = checkParam('payment-billing-address2');
					$formPaymentBillingCity = checkParam('payment-billing-city');
					$formPaymentBillingState = checkParam('payment-billing-state');
					$formPaymentBillingZipcode = checkParam('payment-billing-zipcode');
					$formPaymentBillingCountry = checkParam('payment-billing-country');
					$formPaymentBillingEmail = checkParam('payment-billing-email');
					$formPaymentBillingPhone = checkParam('payment-billing-phone');
					$formPaymentBillingCountryCode = checkParam('payment-billing-countrycode');
					$formPaymentBillingPhoneExtension = checkParam('payment-billing-extension');

					$formPaymentAddMailing = checkParam('payment-add-mailing-address');
					$formPaymentMailingName = checkParam('payment-mailing-name');
					$formPaymentMailingAddress1 = checkParam('payment-mailing-address1');
					$formPaymentMailingAddress2 = checkParam('payment-mailing-address2');
					$formPaymentMailingCity = checkParam('payment-mailing-city');
					$formPaymentMailingState = checkParam('payment-mailing-state');
					$formPaymentMailingZipcode = checkParam('payment-mailing-zipcode');
					$formPaymentMailingCountry = checkParam('payment-mailing-country');
					
					/*
					//DISABLE: Remove phone & email for mailing address, billing & mailing will share the same email and phone
					$formPaymentMailingEmail = checkParam('payment-mailing-email');
					$formPaymentMailingPhone = checkParam('payment-mailing-phone');
					*/
				}

				$formPaymentCreateAccount = checkParam('payment-create-account');
				$formPaymentAccountPassword = checkParam('payment-account-password');
				$formPaymentAccountConfirmPassword = checkParam('payment-account-confirm-password');

				$formPaymentSaveInformation = checkParam('payment-save-information');

				if($formLoginMode != "1"){
					$formPaymentPreferredReceipt = checkParam('payment-preferred-receipt');

					$formNewsletterUSWeekly = checkParam('newsletter-us-weekly');
					$formNewsletterUSBimonthly = checkParam('newsletter-us-bimonthly');
					$formNewsletterUSBimonthlyMode = checkParam('newsletter-us-bimonthly-mode');

					$formNewsletterUKEmail = checkParam('newsletter-uk-email');
					$formNewsletterUKNot = checkParam('newsletter-uk-not');
					$formNewsletterUKEmailWeekly = checkParam('newsletter-uk-email-weekly');
					$formNewsletterUKContactEmail = checkParam('newsletter-uk-contact-email');
					$formNewsletterUKContactPost = checkParam('newsletter-uk-contact-post');
					$formNewsletterUKContactPhone = checkParam('newsletter-uk-contact-phone');
					
					$formNewsletterROWWeekly = checkParam('newsletter-row-weekly');
					$formNewsletterROWYearly = checkParam('newsletter-row-yearly');
					$formNewsletterROWEmail = checkParam('newsletter-row-email');
					$formNewsletterROWPost = checkParam('newsletter-row-post');
					$formNewsletterROWPhone = checkParam('newsletter-row-phone');
				}

				$formGiftCatalogType = checkParam('catalog-value-type');
				$formGiftCatalogMode = checkParam('catalog-value-mode');
				$formGiftCatalogCode = checkParam('catalog-value-code');
				$formGiftCatalogDescription = checkParam('catalog-value-description');
				$formGiftCatalogComment = checkParam('catalog-value-comment');
				$formGiftCatalogAnonymous = checkParam('catalog-value-anonymous');
				$formGiftCatalogAmount = checkParam('catalog-value-amount');
				$formGiftCatalogRecurring = checkParam('catalog-value-recurring');
				
				$formTotalOneTime = 0.00;
				$formTotalRecurring = 0.00;
				$stripeDescription = "";
				foreach($formGiftCatalogType AS $typeKey => $typeVal){
					$formGiftLists[$typeKey]['type'] = $formGiftCatalogType[$typeKey];
					$formGiftLists[$typeKey]['mode'] = $formGiftCatalogMode[$typeKey];
					$formGiftLists[$typeKey]['code'] = $formGiftCatalogCode[$typeKey];
					$formGiftLists[$typeKey]['description'] = $formGiftCatalogDescription[$typeKey];
					$formGiftLists[$typeKey]['comment'] = $formGiftCatalogComment[$typeKey];
					$formGiftLists[$typeKey]['anonymous'] = $formGiftCatalogAnonymous[$typeKey];
					$formGiftLists[$typeKey]['amount'] = $formGiftCatalogAmount[$typeKey];
					$formGiftLists[$typeKey]['recurring'] = $formGiftCatalogRecurring[$typeKey];
					if($formGiftLists[$typeKey]['recurring'] == ""){
						$formTotalOneTime += $formGiftCatalogAmount[$typeKey];
					}else{
						$formTotalRecurring += $formGiftCatalogAmount[$typeKey];
					}
					if($stripeDescription == ""){
						$stripeDescription = $formGiftLists[$typeKey]['description']." - ".$formGiftLists[$typeKey]['amount'];
					}else{
						$stripeDescription .= ", ".$formGiftLists[$typeKey]['description']." - ".$formGiftLists[$typeKey]['amount'];
					}
				}

				if($formLoginMode == "1"){
					if(isset($_SESSION['user_fullname'])){
						$message['content'] = "Welcome back ".$_SESSION['user_fullname'];
					}else{
						$message['content'] = "Welcome back ";
					}
					break;
				}

				$GLOBALS['myDB']->beginTrans();

				$formDonorId = "";
				$formDonorAccountData = array();
				if($isLogin){
					if(isset($_SESSION["login"]["give_behalf"])&&$_SESSION["login"]["give_behalf"]&&isset($_SESSION["login"]["give_behalf_id"])&&$_SESSION["login"]["give_behalf_id"]!="") {
						$formDonorId = $_SESSION["login"]["give_behalf_id"];
					} else {
						$formDonorId = $_SESSION['user_id'];
					}
				}else{
					$formDonorAccountData = $objGiving->getDonorAccountData($formPaymentBillingEmail);
					if(!empty($formDonorAccountData) && $formPaymentCreateAccount != "" && $formDonorAccountData['status'] == '1'){
						$GLOBALS['myDB']->rollbackTrans();
						$error['content'] = "Email address has been registered with a donor. Please login as a donor to proceed.";
						break;
					}
					
					//create new donor/customer in database/stripe if no existing data
					if(empty($formDonorAccountData)){
						$formDonorAccountData['username'] = $formPaymentBillingEmail;
						$formDonorAccountData['email'] = $formDonorAccountData['username'];
						$formDonorAccountData['region'] = REGION;
						$formDonorAccountData['access'] = "fo";
						$formDonorAccountData['first_name'] = $formPaymentBillingName;
						$formDonorAccountData['salt'] = generateSalt(15);
						$formDonorAccountData['uid'] = GUID();
						if($formPaymentCreateAccount != ""){
							$formDonorAccountData['status'] = 1;
							$formDonorAccountData['password'] = hashPassword($formDonorAccountData['username'], $formPaymentAccountPassword, $formDonorAccountData['salt']);
						}else{
							$formDonorAccountData['status'] = 0;
						}
						
						/** Stripe Customer - Start **/
							try{
								$stripe_customer = \Stripe\Customer::create(array(
									'email' => $formPaymentBillingEmail
								));
							} catch (\Stripe\Error\RateLimit $e) {
								// Too many requests made to the API too quickly
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\InvalidRequest $e) {
								// Invalid parameters were supplied to Stripe's API
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\Authentication $e) {
								// Authentication with Stripe's API failed
								// (maybe you changed API keys recently)
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\ApiConnection $e) {
							  // Network communication with Stripe failed
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\Base $e) {
							  // Display a very generic error to the user, and maybe send
							  // yourself an email
								$error['content'] = $e->getMessage();
								break;
							} catch (Exception $e) {
							  // Something else happened, completely unrelated to Stripe
								$error['content'] = $e->getMessage();
								break;
							}
						/** Stripe Customer - End **/
						$formDonorAccountData['stripe_cust_id'] = $stripe_customer->id;

						if($GLOBALS['myDB']->insert('sys_users', $formDonorAccountData)){
							$formDonorId = $GLOBALS['myDB']->getInsertedId();
						}
					}else{
						$formDonorId = $formDonorAccountData['id'];
						$stripe_customer = \Stripe\Customer::retrieve($formDonorAccountData['stripe_cust_id']);
					}
				}

				$formPaymentId = "";
				//echo "token is ".$formStripeToken; //debug
				//exit();//debug
				
				if($formPaymentCCMode === "select"){ //$formPaymentCCMode == "edit" ||  //debug
					$paymentData = array();
					$paymentData = $objGiving->getPaymentData($formPaymentCCSelect);

					if(empty($paymentData)){
						$GLOBALS['myDB']->rollbackTrans();
						$error['content'] = "Could not retrieve your payment card details. Please try again.";
						break;
					}else{
						$formPaymentId = $paymentData['id'];
						$stripe_source = $paymentData['stripe_source_id'];
						/*
						//disable
						if($formPaymentCCMode == "edit"){
							$paymentData['user_id'] = $formDonorId;
							$paymentData['type'] = "card";
							$paymentData['type_1'] = "";
							$paymentData['number'] = $formPaymentCCNumber;
							$paymentData['number_1'] = $formPaymentCCCVV;
							$paymentData['name_1'] = $formPaymentCCExpiration;
							$paymentData['name'] = $formPaymentCCName;
							$paymentData['display_info'] = "1";
							$paymentData['modified_by'] = $formDonorId;
							$paymentData['modified_date'] = date("Y-m-d H:i:s");
							if(!$GLOBALS['myDB']->update('payments', $paymentData, "`id`='$formPaymentId'")){
								$GLOBALS['myDB']->rollbackTrans();
								$error['content'] = "Could not update your payment card details. Please try again.";
								break;
							}
						}
						*/
					}
				}
				
				if($formPaymentSaveInformation == ""){
					$formPaymentId  = "-1";
					
					if($formPaymentCCMode === "new"){
					
						/** Stripe Card - Start **/							
						try{
							$stripe_card = $stripe_customer->sources->create(array(
								"source" => $formStripeToken
							));
						} catch (\Stripe\Error\RateLimit $e) {
							// Too many requests made to the API too quickly
							$error['content'] = $e->getMessage();
							break;
						} catch (\Stripe\Error\InvalidRequest $e) {
							// Invalid parameters were supplied to Stripe's API
							$error['content'] = $e->getMessage();
							break;
						} catch (\Stripe\Error\Authentication $e) {
							// Authentication with Stripe's API failed
							// (maybe you changed API keys recently)
							$error['content'] = $e->getMessage();
							break;
						} catch (\Stripe\Error\ApiConnection $e) {
						  // Network communication with Stripe failed
							$error['content'] = $e->getMessage();
							break;
						} catch (\Stripe\Error\Base $e) {
						  // Display a very generic error to the user, and maybe send
						  // yourself an email
							$error['content'] = $e->getMessage();
							break;
						} catch (Exception $e) {
						  // Something else happened, completely unrelated to Stripe
							$error['content'] = $e->getMessage();
							break;
						}
						
						$stripe_source = $stripe_card->id;
						/** Stripe Card - End **/
					}
				}else{
					if(REGION == "US" && $formPaymentUSPaymode == "check"){
						$paymentData = array();
						$paymentData['user_id'] = $formDonorId;
						$paymentData['type'] = "check";
						$paymentData['type_1'] = $formPaymentECheckType;
						$paymentData['number'] = $formPaymentECheckAccNo;
						$paymentData['number_1'] = $formPaymentECheckRouterNo;
						$paymentData['name_1'] = $formPaymentECheckBankName;
						$paymentData['name'] = $formPaymentECheckName;
						$paymentData['created_by'] = $formDonorId;
						$paymentData['created_date'] = date("Y-m-d H:i:s");
						if($GLOBALS['myDB']->insert('payments', $paymentData)){
							$formPaymentId = $GLOBALS['myDB']->getInsertedId();
						}
					}else{				
						if($formPaymentCCMode == "new"){	
							/** Stripe Card - Start **/							
							try{
								$stripe_card = $stripe_customer->sources->create(array(
									"source" => $formStripeToken
								));
							} catch (\Stripe\Error\RateLimit $e) {
								// Too many requests made to the API too quickly
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\InvalidRequest $e) {
								// Invalid parameters were supplied to Stripe's API
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\Authentication $e) {
								// Authentication with Stripe's API failed
								// (maybe you changed API keys recently)
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\ApiConnection $e) {
							  // Network communication with Stripe failed
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\Base $e) {
							  // Display a very generic error to the user, and maybe send
							  // yourself an email
								$error['content'] = $e->getMessage();
								break;
							} catch (Exception $e) {
							  // Something else happened, completely unrelated to Stripe
								$error['content'] = $e->getMessage();
								break;
							}
							
							$stripe_source = $stripe_card->id;
							/** Stripe Card - End **/
							
							$paymentData = array();
							$paymentData['user_id'] = $formDonorId;
							$paymentData['stripe_source_id'] = $stripe_card->id;
							$paymentData['type'] = "card";
							$paymentData['type_1'] = "";
							$paymentData['display_info'] = "1";
							$paymentData['number'] = $stripe_card->last4; //last 4 only
							//$paymentData['number_1'] = $formPaymentCCCVV;
							$paymentData['name_1'] = str_pad($stripe_card->exp_month, 2, "0", STR_PAD_LEFT)."/".substr($stripe_card->exp_year, -2);
							//$paymentData['name'] = $formPaymentCCName;
							$paymentData['created_by'] = $formDonorId;
							$paymentData['created_date'] = date("Y-m-d H:i:s");
							if($formPaymentSaveInformation == ""){
								$paymentData['display_info'] = "0";
							}
							if($GLOBALS['myDB']->insert('payments', $paymentData)){
								$formPaymentId = $GLOBALS['myDB']->getInsertedId();
							}
							
						}
						
					}
				}

				if($formPaymentId != "" && $formDonorId != ""){
					$stripeStatus = false;
					$errorStatus = "";

					if($formTotalOneTime != ''){
						/** Stripe Payment - Start **/
							
							// Charge the user's card:
							try{
								$charge = \Stripe\Charge::create(array(
									"amount" => $formTotalOneTime*100, //convert amount to cents
									"currency" => strtolower($formCurrencyCode),
									"description" => $stripeDescription,
									"source" => $stripe_source,
									"customer" => $stripe_customer->id
								));

							}catch(\Stripe\Error\Card $e) {
								// The card has been declined
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\RateLimit $e) {
								// Too many requests made to the API too quickly
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\InvalidRequest $e) {
								// Invalid parameters were supplied to Stripe's API
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\Authentication $e) {
								// Authentication with Stripe's API failed
								// (maybe you changed API keys recently)
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\ApiConnection $e) {
							  // Network communication with Stripe failed
								$error['content'] = $e->getMessage();
								break;
							} catch (\Stripe\Error\Base $e) {
							  // Display a very generic error to the user, and maybe send
							  // yourself an email
								$error['content'] = $e->getMessage();
								break;
							} catch (Exception $e) {
							  // Something else happened, completely unrelated to Stripe
								$error['content'] = $e->getMessage();
								break;
							}
							
						/** Stripe Payment - End **/
					}
					
					
					if($errorStatus == '' || ($formTotalOneTime == '' && $formTotalRecurring != '')){
						$stripeStatus = true;
					}

					if($stripeStatus){ //$stripeStatus //debug
						$headerData = array();
						$headerData['stripe_charge_id'] = $charge->id;
						$headerData['total_onetime'] = $formTotalOneTime;
						$headerData['total_recurring'] = $formTotalRecurring;
						$headerData['billing_fullname'] = $formPaymentBillingName;
						$headerData['billing_address1'] = $formPaymentBillingAddress1;
						$headerData['billing_address2'] = $formPaymentBillingAddress2;
						$headerData['billing_city'] = $formPaymentBillingCity;
						$headerData['billing_state'] = $formPaymentBillingState;
						$headerData['billing_zipcode'] = $formPaymentBillingZipcode;
						$headerData['billing_country'] = $formPaymentBillingCountry;
						$headerData['billing_email'] = $formPaymentBillingEmail;
						$headerData['billing_phone'] = $formPaymentBillingPhone;
						if($formPaymentAddMailing != ""){
							$headerData['mailing_fullname'] = $formPaymentMailingName;
							$headerData['mailing_address1'] = $formPaymentMailingAddress1;
							$headerData['mailing_address2'] = $formPaymentMailingAddress2;
							$headerData['mailing_city'] = $formPaymentMailingCity;
							$headerData['mailing_state'] = $formPaymentMailingState;
							$headerData['mailing_zipcode'] = $formPaymentMailingZipcode;
							$headerData['mailing_country'] = $formPaymentMailingCountry;
							//$headerData['mailing_email'] = $formPaymentMailingEmail;
							//$headerData['mailing_phone'] = $formPaymentMailingPhone;
						}
						$headerData['payment_id'] = $formPaymentId;
						$headerData['user_id'] = $formDonorId;
						$headerData['payment_status'] = "1";
						$headerData['currency_code'] = $formCurrencyCode;
						if($formPaymentCreateAccount != ""){
							$headerData['as_guest'] = "1";
						}else{
							$headerData['as_guest'] = "0";
						}
						if(REGION == "uk" && $formPaymentUKExtraAid != ""){
							$headerData['add_uk_extra_aid'] = "1";
							$headerData['uk_extra_aid_amount'] = "15";
							$headerData['uk_extra_aid_date'] = $formPaymentUKExtraAidDate;
						}
						if(REGION == "us"){
							$headerData['preferred_receipt'] = $formPaymentPreferredReceipt;
						}
						if(REGION != "US" && $formPaymentUSPaymode != "check" && $formPaymentCCProcessFee != ""){
							$headerData['add_transaction_fee'] = "1";
							$headerData['transaction_fee'] = "15";
						}
						if(REGION == "US" && $formPaymentUSPaymode == "check"){
							$headerData['type'] = "check";
						}else{
							$headerData['type'] = "card";
						}
						
						// Donate/ Giving on behalf
						if(isset($_SESSION["login"]["give_behalf"])&&$_SESSION["login"]["give_behalf"]&&isset($_SESSION["login"]["give_behalf_id"])&&$_SESSION["login"]["give_behalf_id"]!="") {
							$headerData['donate_on_behalf'] = "1";
						}

						$headerData['transaction_date'] = date("Y-m-d H:i:s");
						$headerData['created_date'] = $headerData['transaction_date'];
						$headerData['created_by'] = $formDonorId;

						if($GLOBALS['myDB']->insert('donations', $headerData)){
							$headerId = $GLOBALS['myDB']->getInsertedId();
							foreach($formGiftLists AS $listKey => $listData){
								$detailsData = array();
								$detailsData['header_id'] = $headerId;
								$detailsData['type'] = $listData['type'];
								$detailsData['mode'] = $listData['mode'];
								$detailsData['code'] = $listData['code'];
								$detailsData['description'] = $listData['description'];
								$detailsData['comment'] = $listData['comment'];
								$detailsData['amount'] = $listData['amount'];
								$detailsData['recurring'] = $listData['recurring'];
								if($listData["anonymous"] != ""){
									$detailsData['is_anonymous'] = "1";
								}
								if(!$GLOBALS['myDB']->insert('donations_details', $detailsData)){
									$GLOBALS['myDB']->rollbackTrans();
									$error['content'] = "Could not save your donation details. Please try again.";
									break;
								}
								
								/** Recurring Gift - Start **/
								if($detailsData['recurring'] != ''){
									$subscriptionData = array();
									$subscriptionData['user_id'] = $formDonorId;
									$subscriptionData['stripe_source_id'] = $stripe_source;
									$subscriptionData['description'] = $detailsData['description'];
									$subscriptionData['currency_code'] = $formCurrencyCode;
									$subscriptionData['amount'] = $detailsData['amount'];
									if($formPaymentUSPaymode == 'check'){
										$subscriptionData['type'] = 'check';
									}else{
										$subscriptionData['type'] = 'card';
									}
									$subscriptionData['billing_interval'] = 'month';
									$subscriptionData['billing_date'] =  substr($detailsData['recurring'], 0, -2);
									$subscriptionData['created_by'] = $formDonorId;
									$subscriptionData['created_date'] = date("Y-m-d H:i:s");
									
								}
								if(!$GLOBALS['myDB']->insert('subscriptions', $subscriptionData)){
									$GLOBALS['myDB']->rollbackTrans();
									$error['content'] = "Could not save your donation details. Please try again.";
									break;
								}
								/** Recurring Gift - End **/
							}

							if($formPaymentSaveInformation != ""){
								$formDonorAccountData['billing_fullname'] = $formPaymentBillingName;
								$formDonorAccountData['billing_address1'] = $formPaymentBillingAddress1;
								$formDonorAccountData['billing_address2'] = $formPaymentBillingAddress2;
								$formDonorAccountData['billing_city'] = $formPaymentBillingCity;
								$formDonorAccountData['billing_state'] = $formPaymentBillingState;
								$formDonorAccountData['billing_zipcode'] = $formPaymentBillingZipcode;
								$formDonorAccountData['billing_country'] = $formPaymentBillingCountry;
								$formDonorAccountData['email'] = $formPaymentBillingEmail;
								$formDonorAccountData['phone_day'] = $formPaymentBillingPhone;
								$formDonorAccountData['phone_day_extension'] = $formPaymentBillingPhoneExtension;
								$formDonorAccountData['phone_country'] = $formPaymentBillingCountryCode;
								if($formPaymentAddMailing != ""){
									$formDonorAccountData['mailing_fullname'] = $formPaymentMailingName;
									$formDonorAccountData['mailing_address1'] = $formPaymentMailingAddress1;
									$formDonorAccountData['mailing_address2'] = $formPaymentMailingAddress2;
									$formDonorAccountData['mailing_city'] = $formPaymentMailingCity;
									$formDonorAccountData['mailing_state'] = $formPaymentMailingState;
									$formDonorAccountData['mailing_zipcode'] = $formPaymentMailingZipcode;
									$formDonorAccountData['mailing_country'] = $formPaymentMailingCountry;
									$formDonorAccountData['mailing_email'] = $formPaymentMailingEmail;
									$formDonorAccountData['mailing_phone'] = $formPaymentMailingPhone;
								}
							}

							if(REGION == "us"){
								if($formNewsletterUSWeekly == "on"){
									$formDonorAccountData['newsletter_us_weekly'] = "1";
								}else{
									$formDonorAccountData['newsletter_us_weekly'] = "0";
								}
								if($formNewsletterUSBimonthly == "on"){
									$formDonorAccountData['newsletter_us_bimonthly'] = "1";
								}else{
									$formDonorAccountData['newsletter_us_bimonthly'] = "0";
								}
								$formDonorAccountData['newsletter_us_bimonthly_mode'] = $formNewsletterUSBimonthlyMode;
							}else if(REGION == "uk"){
								if($formNewsletterUKEmail == "on"){
									$formDonorAccountData['newsletter_uk_email'] = "1";
								}else{
									$formDonorAccountData['newsletter_uk_email'] = "0";
								}
								if($formNewsletterUKNot == "on"){
									$formDonorAccountData['newsletter_uk_not'] = "1";
								}else{
									$formDonorAccountData['newsletter_uk_not'] = "0";
								}
								if($formNewsletterUKEmailWeekly == "on"){
									$formDonorAccountData['newsletter_uk_email_weekly'] = "1";
								}else{
									$formDonorAccountData['newsletter_uk_email_weekly'] = "0";
								}
								if($formNewsletterUKContactEmail == "on"){
									$formDonorAccountData['newsletter_uk_contact_email'] = "1";
								}else{
									$formDonorAccountData['newsletter_uk_contact_email'] = "0";
								}
								if($formNewsletterUKContactPost == "on"){
									$formDonorAccountData['newsletter_uk_contact_post'] = "1";
								}else{
									$formDonorAccountData['newsletter_uk_contact_post'] = "0";
								}
								if($formNewsletterUKContactPhone == "on"){
									$formDonorAccountData['newsletter_uk_contact_phone'] = "1";
								}else{
									$formDonorAccountData['newsletter_uk_contact_phone'] = "0";
								}
							}else{
								if($formNewsletterROWWeekly == "on"){
									$formDonorAccountData['newsletter_row_weekly'] = "1";
								}else{
									$formDonorAccountData['newsletter_row_weekly'] = "0";
								}
								if($formNewsletterROWYearly == "on"){
									$formDonorAccountData['newsletter_row_yearly'] = "1";
								}else{
									$formDonorAccountData['newsletter_row_yearly'] = "0";
								}
								if($formNewsletterROWEmail == "on"){
									$formDonorAccountData['newsletter_row_email'] = "1";
								}else{
									$formDonorAccountData['newsletter_row_email'] = "0";
								}
								if($formNewsletterROWPost == "on"){
									$formDonorAccountData['newsletter_row_post'] = "1";
								}else{
									$formDonorAccountData['newsletter_row_post'] = "0";
								}
								if($formNewsletterROWPhone == "on"){
									$formDonorAccountData['newsletter_row_phone'] = "1";
								}else{
									$formDonorAccountData['newsletter_row_phone'] = "0";
								}
							}
							
							//update existing donor if already donate before and now decided to register for a profile
							if($formPaymentCreateAccount != "" && $formDonorAccountData['status'] == '0'){
								$formDonorAccountData['status'] = 1;
								$formDonorAccountData['password'] = hashPassword($formDonorAccountData['username'], $formPaymentAccountPassword, $formDonorAccountData['salt']);
							}

							if(!$GLOBALS['myDB']->update('sys_users', $formDonorAccountData, "`id`='$formDonorId'")){
								$GLOBALS['myDB']->rollbackTrans();
								$error['content'] = "Could not update your donor account. Please try again.";
								break;
							}							
							
							$GLOBALS['myDB']->commitTrans();

							$newHeaderData = array("id" => $headerId);
							$runningNumberMode = 'donation_form.row';
							if(REGION == "us"){
								$runningNumberMode = 'donation_form.us';
							}else if(REGION == "uk"){
								$runningNumberMode = 'donation_form.uk';
							}
							$newHeaderData['transaction_no'] = generateRunningNo($runningNumberMode);
							updateRunningNo($runningNumberMode);
							$GLOBALS['myDB']->update('donations', $newHeaderData, "`id`='$headerId'");
							$setting['center_dir'] = DIR_ACTIVE_PUBLIC_THEME."/giving/thankyou.php";
						}else{
							$GLOBALS['myDB']->rollbackTrans();
							$error['content'] = "Could not save your donation form. Please try again.";
							break;
						}
					}else{
						$GLOBALS['myDB']->rollbackTrans();
						$error['content'] = "You payment could not be processed at this moment. Please try again.";
						break;
					}
				}
			}
		break;
	}
	require DIR_ACTIVE_PUBLIC_THEME.'/site_builder.php';
?>