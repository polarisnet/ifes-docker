<?php
	require_once DIR_LIBS.'/thankq.pdo.class.php';
	$objThankQPDO = new ThankQPDO();

	require_once 'giving.class.php';
	$objGiving = new Giving($GLOBALS['myDB']);

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
			$formCurrencySymbol = "&euro;";
			$formCurrencyCode = "EUR";
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
					case "search_gift_catalog":
						$searchType = checkParam('type');
						$searchQuery = checkParam('query');
						$currencyCode = checkParam('currency_code');
						$currencySymbol = checkParam('currency_symbol');
						$condition = " AND destinationgroup IS NOT NULL ";
						if($searchQuery != ""){
							$condition .= " AND LOWER(destinationdescription) LIKE '%".strtolower($searchQuery)."%'";
						}
						if($searchType == "staff"){
							$condition .= " ORDER BY FIELD (destinationgroup, 'IFES staff', 'Regional Staff', 'National Movement staff', 'Projects', 'IFES InterAction Volunteers')";
						}else if($searchType == "ministry"){
							$condition .= " ORDER BY FIELD (destinationgroup, 'Regional Staff', 'National Movement staff', 'Projects', 'IFES staff', 'IFES InterAction Volunteers')";
						}else if($searchType == "movement"){
							$condition .= " ORDER BY FIELD (destinationgroup, 'National Movement staff', 'Projects', 'IFES staff', 'IFES InterAction Volunteers', 'Regional Staff')";
						}
						$searchResult = $objThankQPDO->listDestinationCodes($condition);
						if(is_array($searchResult) && !empty($searchResult)){
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
				}
				echo json_encode($output);
				exit;
			}

			$listOfferingEvents = $objThankQPDO->listOfferingEvents();
			$listCreditCards = array();
			$listCountries = $objGiving->listCountries();

			$formPaymentUSPaymode = "cc";

			$formPaymentUKExtraAid = "";
			$formPaymentUKExtraAidDate = "";

			$formPaymentECheckAccNo = "";
			$formPaymentECheckRouterNo = "";
			$formPaymentECheckBankName = "";
			$formPaymentECheckName = "";
			$formPaymentECheckType = "";

			$formPaymentCCNumber = "";
			$formPaymentCCName = "";
			$formPaymentCCExpiration = "";
			$formPaymentCCCVV = "";

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

			$formPaymentAddMailing = "";
			$formPaymentMailingName = "";
			$formPaymentMailingAddress1 = "";
			$formPaymentMailingAddress2 = "";
			$formPaymentMailingCity = "";
			$formPaymentMailingState = "";
			$formPaymentMailingZipcode = "";
			$formPaymentMailingCountry = "";
			$formPaymentMailingEmail = "";
			$formPaymentMailingPhone = "";

			$formPaymentCreateAccount = "";
			$formPaymentAccountPassword = "";
			$formPaymentAccountConfirmPassword = "";

			$formPaymentSaveInformation = "";

			$formPaymentPreferredReceipt = "email";

			$formNewsletterUSWeekly = "on";
			$formNewsletterUSBimonthly = "on";
			$formNewsletterUSBimonthlyMode = "email";

			$formNewsletterUKEmail = "";
			$formNewsletterUKNot = "";
			$formNewsletterUKEmailWeekly = "";
			$formNewsletterUKContactEmail = "on";
			$formNewsletterUKContactPost = "on";
			$formNewsletterUKContactPhone = "on";

			$formNewsletterROWWeekly = "on";
			$formNewsletterROWYearly = "on";
			$formNewsletterROWEmail = "on";
			$formNewsletterROWPost = "on";
			$formNewsletterROWPhone = "on";

			$formGiftLists = array();

			if(!empty($_POST)){
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

				$formPaymentBillingName = checkParam('payment-billing-name');
				$formPaymentBillingAddress1 = checkParam('payment-billing-address1');
				$formPaymentBillingAddress2 = checkParam('payment-billing-address2');
				$formPaymentBillingCity = checkParam('payment-billing-city');
				$formPaymentBillingState = checkParam('payment-billing-state');
				$formPaymentBillingZipcode = checkParam('payment-billing-zipcode');
				$formPaymentBillingCountry = checkParam('payment-billing-country');
				$formPaymentBillingEmail = checkParam('payment-billing-email');
				$formPaymentBillingPhone = checkParam('payment-billing-phone');

				$formPaymentAddMailing = checkParam('payment-add-mailing-address');
				$formPaymentMailingName = checkParam('payment-mailing-name');
				$formPaymentMailingAddress1 = checkParam('payment-mailing-address1');
				$formPaymentMailingAddress2 = checkParam('payment-mailing-address2');
				$formPaymentMailingCity = checkParam('payment-mailing-city');
				$formPaymentMailingState = checkParam('payment-mailing-state');
				$formPaymentMailingZipcode = checkParam('payment-mailing-zipcode');
				$formPaymentMailingCountry = checkParam('payment-mailing-country');
				$formPaymentMailingEmail = checkParam('payment-mailing-email');
				$formPaymentMailingPhone = checkParam('payment-mailing-phone');

				$formPaymentCreateAccount = checkParam('payment-create-account');
				$formPaymentAccountPassword = checkParam('payment-account-password');
				$formPaymentAccountConfirmPassword = checkParam('payment-account-confirm-password');

				$formPaymentSaveInformation = checkParam('payment-save-information');

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
				foreach($formGiftCatalogType AS $typeKey => $typeVal){
					$formGiftLists[$typeKey]['type'] = $typeVal;
					$formGiftLists[$typeKey]['mode'] = $formGiftCatalogMode[$typeKey];
					$formGiftLists[$typeKey]['code'] = $formGiftCatalogCode[$typeKey];
					$formGiftLists[$typeKey]['description'] = $formGiftCatalogDescription[$typeKey];
					$formGiftLists[$typeKey]['comment'] = $formGiftCatalogComment[$typeKey];
					$formGiftLists[$typeKey]['anonymous'] = $formGiftCatalogAnonymous[$typeKey];
					$formGiftLists[$typeKey]['amount'] = $formGiftCatalogAmount[$typeKey];
					$formGiftLists[$typeKey]['recurring'] = $formGiftCatalogRecurring[$typeKey];
					if($formGiftLists[$typeKey]['recurring'] == ""){
						$formTotalOneTime += $formGiftCatalogRecurring[$typeKey];
					}else{
						$formTotalRecurring += $formGiftCatalogRecurring[$typeKey];
					}
				}

				$GLOBALS['myDB']->beginTrans();

				$formDonorId = "";
				$formDonorAccountData = array();
				if(isset($_SESSION['login'])){
					$formDonorId = "A";
				}else{
					$formDonorAccountData = $objGiving->getDonorAccountData($formPaymentBillingEmail);
					if(!empty($formDonorAccountData) && $formPaymentCreateAccount != ""){
						$GLOBALS['myDB']->rollbackTrans();
						$error['content'] = "Email address has been registered with a donor. Please login as a donor to proceed.";
						break;
					}

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

						if($GLOBALS['myDB']->insert('sys_users', $formDonorAccountData)){
							$formDonorId = $GLOBALS['myDB']->getInsertedId();
						}
					}else{
						$formDonorId = $formDonorAccountData['id'];
					}
				}

				$formPaymentCreateAccount = "";

				$formPaymentId = "";
				if(REGION == "US" && $formPaymentUSPaymode == "check"){
					$paymentData = array();
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
						$paymentData = array();
						$paymentData['type'] = "card";
						$paymentData['type_1'] = "";
						$paymentData['number'] = $formPaymentCCNumber;
						$paymentData['number_1'] = $formPaymentCCCVV;
						$paymentData['name_1'] = $formPaymentCCExpiration;
						$paymentData['name'] = $formPaymentCCName;
						$paymentData['created_by'] = $formDonorId;
						$paymentData['created_date'] = date("Y-m-d H:i:s");
						if($formPaymentSaveInformation != ""){
							$paymentData['display_info'] = "1";
						}
						if($GLOBALS['myDB']->insert('payments', $paymentData)){
							$formPaymentId = $GLOBALS['myDB']->getInsertedId();
						}
					}else if($formPaymentCCMode == "edit" || $formPaymentCCMode == "select"){
						$paymentData = $objGiving->getPaymentData($formPaymentCCSelect);
						if(empty($paymentData)){
							$GLOBALS['myDB']->rollbackTrans();
							$error['content'] = "Could not retrieve your payment card details. Please try again.";
							break;
						}else{
							$formPaymentId = $paymentData['id'];
							if($formPaymentCCMode == "edit"){
								$paymentData['type'] = "card";
								$paymentData['type_1'] = "";
								$paymentData['number'] = $formPaymentCCNumber;
								$paymentData['number_1'] = $formPaymentCCCVV;
								$paymentData['name_1'] = $formPaymentCCExpiration;
								$paymentData['name'] = $formPaymentCCName;
								$paymentData['modified_by'] = $formDonorId;
								$paymentData['modified_date'] = date("Y-m-d H:i:s");
								if(!$GLOBALS['myDB']->update('payments', $paymentData, "`id`='$formPaymentId'")){
									$GLOBALS['myDB']->rollbackTrans();
									$error['content'] = "Could not update your payment card details. Please try again.";
									break;
								}
							}
						}
					}
				}

				if($formPaymentId != "" && $formDonorId != ""){
					$stripeStatus = true;
					/** Stripe Payment - Start **/
						// Do strip payment here, retrive payment data from array $paymentData 
					/** Stripe Payment - End **/

					if($stripeStatus){
						$headerData = array();
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
							$headerData['mailing_email'] = $formPaymentMailingEmail;
							$headerData['mailing_phone'] = $formPaymentMailingPhone;
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
							}

							if($formPaymentSaveInformation != ""){
								$formDonorAccountData['billing_fullname'] = $formPaymentBillingName;
								$formDonorAccountData['billing_address1'] = $formPaymentBillingAddress1;
								$formDonorAccountData['billing_address2'] = $formPaymentBillingAddress2;
								$formDonorAccountData['billing_city'] = $formPaymentBillingCity;
								$formDonorAccountData['billing_state'] = $formPaymentBillingState;
								$formDonorAccountData['billing_zipcode'] = $formPaymentBillingZipcode;
								$formDonorAccountData['billing_country'] = $formPaymentBillingCountry;
								$formDonorAccountData['billing_email'] = $formPaymentBillingEmail;
								$formDonorAccountData['billing_phone'] = $formPaymentBillingPhone;
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
								$formDonorAccountData['newsletter_us_weekly'] = $formNewsletterUSWeekly;
								$formDonorAccountData['newsletter_us_bimonthly'] = $formNewsletterUSBimonthly;
								$formDonorAccountData['newsletter_us_bimonthly_mode'] = $formNewsletterUSBimonthlyMode;
							}else if(REGION == "uk"){ 
								$formDonorAccountData['newsletter_uk_email'] = $formNewsletterUKEmail;
								$formDonorAccountData['newsletter_uk_not'] = $formNewsletterUKNot;
								$formDonorAccountData['newsletter_uk_email_weekly'] = $formNewsletterUKEmailWeekly;
								$formDonorAccountData['newsletter_uk_contact_email'] = $formNewsletterUKContactEmail;
								$formDonorAccountData['newsletter_uk_contact_post'] = $formNewsletterUKContactPost;
								$formDonorAccountData['newsletter_uk_contact_phone'] = $formNewsletterUKContactPhone;
							}else{
								$formDonorAccountData['newsletter_row_weekly'] = $formNewsletterROWWeekly;
								$formDonorAccountData['newsletter_row_yearly'] = $formNewsletterROWYearly;
								$formDonorAccountData['newsletter_row_email'] = $formNewsletterROWEmail;
								$formDonorAccountData['newsletter_row_post'] = $formNewsletterROWPost;
								$formDonorAccountData['newsletter_row_phone'] = $formNewsletterROWPhone;
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