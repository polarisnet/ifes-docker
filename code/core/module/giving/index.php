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
			$region = "row";
			$formCurrencySymbol = "&dollar;";
			$formCurrencyCode = "USD";
			//$HTTP_AJAX 	= HTTP_ACTIVE_MODULE.'/ajax';
			//print_r($action);

			if($action == "ajax"){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case "search_gift_catalog":
						$searchType = checkParam('type');
						$searchQuery = checkParam('query');
						$currencyCode = checkParam('currency_code');
						$currencySymbol = checkParam('currency_symbol');
						$condition = "";
						if($searchType == "staff"){
							$condition .= " AND destinationgroup = 'IFES staff'";
						}else if($searchType == "ministry"){
							$condition .= " AND destinationgroup IS NULL";
						}else if($searchType == "movement"){
							$condition .= " AND destinationgroup = 'National Movement staff'";
						}
						if($searchQuery != ""){
							$condition .= " AND LOWER(destinationdescription) LIKE '%".strtolower($searchQuery)."%'";
						}
						$searchResult = $objThankQPDO->listDestinationCodes($condition);
						if(is_array($searchResult) && !empty($searchResult)){
							$output['total'] = count($searchResult);
						}else{
							$output['total'] = 0;
						}
						$output['template'] = "";
						foreach($searchResult AS $result){
							$output['template'] .= '
								<div class="result-container">
									<div class="col-xs-8 result-label">
										'.ucwords($result['destinationdescription']).'
									</div>
									<div class="col-xs-4 result-form">
										<div class="input-group currency-box">
											<span class="input-group-addon gift-catalog-currency-symbol">'.$currencySymbol.'</span>
											<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
											<div class="input-group-btn">
												<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$currencyCode.' <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a onclick="toggleCurrency('."'EUR', '&euro;'".');">EUR</a></li>
													<li><a onclick="toggleCurrency('."'GBP', '&pound;'".');">GBP</a></li>
													<li><a onclick="toggleCurrency('."'USD', '&dollar;'".');">USD</a></li>
												</ul>
												<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;" onclick="addGift(this, '."'$searchType', 'search', '".ucwords($result['destinationdescription'])."', '".$result['destinationcode']."'".')">ADD GIFT</button>
											</div>
										</div>
									</div>
								</div>';
						}
						$output['success'] = true;
					break;
					case "generate_gift_list_item":
						$templateId = checkParam('id');
						$templateDescription = checkParam('description');
						$templateAmount = checkParam('amount');
						$currencyCode = checkParam('currency_code');
						$currencySymbol = checkParam('currency_symbol');
						$output['template'] = '<div id="gift-list-container-'.$templateId.'" class="gift-list-container">
								<div class="gift-list-container-view" style="display: block;">
									<div class="col-xs-8" style="padding-left: 0;">
										<div style="padding: 6px 0;">'.$templateDescription.'</div>
										<div class="gift-list-view-comment">&nbsp;</div>
										<div class="gift-list-view-anonymous">Anonymous Gift</div>
									</div>
									<div class="col-xs-4" style="padding-right: 0; text-align: right;">
										<div style="padding: 6px 0;"><span class="gift-list-currency-symbol">'.$currencySymbol.'</span> <span class="gift-list-currency-value">'.number_format($templateAmount, 2, '.', ',').'</span></div>
										<div class="gift-list-view-recurring">One-time gift</div>
										<div style="padding: 6px 0;">
											<div><a onclick="modifiyGiftList('.$templateId.');">Modify</a> | <a onclick="removeGiftList('.$templateId.');">Remove</a></div>
										</div>
									</div>
								</div>
								<div class="gift-list-container-edit" style="display: none;">
									<div class="col-xs-8" style="padding-left: 0;">
										<div style="padding: 6px 0;">'.$templateDescription.'</div>
										<div style="padding: 5px 0;"><input type="text" class="form-control gift-list-input-comment" placeholder="Add comment or instructions for the finance office."></div>
										<div><label class="checkbox-inline" style="font-size: 16px;"><input type="checkbox" class="gift-list-input-anonymous">Anonymous Gift</label></div>
									</div>
									<div class="col-xs-4" style="padding-right: 0; text-align: right;">
										<div class="input-group currency-box" style="float:right; width: 260px;">
											<span class="input-group-addon gift-catalog-currency-symbol">'.$currencySymbol.'</span>
											<input type="number" min="0" class="form-control gift-list-input-currency" value="'.$templateAmount.'" aria-label="..." placeholder="0.00">
											<div class="input-group-btn">
												<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$currencyCode.' <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a onclick="toggleCurrency('."'EUR', '&euro;'".');">EUR</a></li>
													<li><a onclick="toggleCurrency('."'GBP', '&pound;'".');">GBP</a></li>
													<li><a onclick="toggleCurrency('."'USD', '&dollar;'".');">USD</a></li>
												</ul>
											</div>
										</div>
										<div style="padding: 5px 0; clear: both;">
											<div class="input-group date datetimepicker gift-list-datepicker">
												<input type="text" class="form-control gift-list-input-recurring" />
												<span class="input-group-addon" style="padding-bottom: 7px;">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div style="float: right; line-height: 2.2; padding-right: 10px;">Monthly Gift on the</div>
										</div>
										<div style="padding-right: 0; text-align: right; clear:both; line-height: 2;">
											<div><a class="gift-list-input-save" onclick="saveGiftList('.$templateId.');">Save</a> | <a onclick="cancelGiftList('.$templateId.');">Cancel</a></div>
										</div>
									</div>
								</div>
							</div>';
						$output['success'] = true;
					break;
				}
				echo json_encode($output);
				exit;
			}


			$listOfferingEvents = $objThankQPDO->listOfferingEvents();
			
			$listCreditCards = array('a');
			$listCountries = $objGiving->listCountries();

			//$result = $objIFESPDO->selectAll("SHOW COLUMNS FROM `thankq_sourcecode`", array());
			//print_r($result); exit;
		break;
	}
	require DIR_ACTIVE_PUBLIC_THEME.'/site_builder.php';
?>