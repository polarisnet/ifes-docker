<?php
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
			$formCurrencySymbol = "$";
			$formCurrencyCode = "USD";
			//$HTTP_AJAX 	= HTTP_ACTIVE_MODULE.'/ajax';
			//print_r($action);

			require_once DIR_LIBS.'/pdo.class.php';

			$objIFESPDO = new OZPDO(array(
				"mode" => "mysql",
				"server" => "jehieltestdb.cfevskzua3nd.eu-west-2.rds.amazonaws.com",
				"port" => "3306",
				"db" => "jehieltestdb",
				"user" => "PolarisDBA",
				"password" => 'T6(be3$A*B2d8$Gt4#aH',
				"ssl_ca_cert" => DIR_FRAMEWORK.'/config/rds-combined-ca-bundle.pem'
			));
			//$result = $objIFESPDO->selectAll("SHOW COLUMNS FROM `thankq_sourcecode`", array());
			//print_r($result); exit;
		break;
	}
	require DIR_ACTIVE_PUBLIC_THEME.'/site_builder.php';
?>