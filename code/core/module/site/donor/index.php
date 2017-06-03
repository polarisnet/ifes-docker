<?php
	require_once 'donor.class.php';
	$objDonor = new Donor($GLOBALS['myDB']);
	
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	$setting = array(
		"title" => SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" => "",
		"meta_description" => "",
		"center_dir" => DIR_ACTIVE_THEME."/donor/view_donor.php"
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
			$firstName = 'susannah';
			$lastName = 'campbell';
			$donorName = $firstName." ".$lastName;
			$donorAccountNumbers = '098764';
			
			$address1 = 'Martin Etcheluz 159';
			$zipCode = '8340';
			$city = 'Zapala';
			$region = 'Neuquen';
			$country = 'Argentina';
			
			$contactMobile = '+54 299 442 5545';
			$contactDay = '+54 299 442 5523';
			$contactNight = ''; 
			
			$email = 'juanarjona@gmail.com';
			
			$firstDate =date("d M Y");
			$total = '1,234.00';
			
			$fullAddress = $address1."<br>";
			$fullAddress.= $zipCode." ".$city." ".$region."<br>";
			$fullAddress.= $country;
			
			$fullContact = "Telephone:<br>";
			
			if($contactDay != ""){
				$fullContact.= "Daytime".$contactDay."<br>";
			}
			if($contactNight != ""){
				$fullContact.= "Night".$contactNight."<br>";
			}
			if($contactMobile != ""){
				$fullContact.= "Mobile".$contactMobile."<br>";
			}
			$fullEmail = "Email Address:<br>".$email;
			
			$listCountries = $objDonor->listCountries();
			
			$givingHistory = array("core mission fund", "$100", "26 Dec 2016", "credit card", '<span class="span-link">DOWNLOAD</span>', '<span class="span-link">GIVE AGAIN</span>');

			
			if($action == "ajax"){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case "list_giving":
						$output['data'] = array();
						//$output['draw'] = checkParam('draw');
						//$output['recordsTotal'] = "1";
						//$output['recordsFiltered'] = "1";
						$output['success'] = true;
						array_push($output['data'], $givingHistory);
					break;
				}
				echo json_encode($output);
				exit;
			}
		break;
	}
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>