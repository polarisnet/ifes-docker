<?php 
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	ini_set("memory_limit","-1");
	ini_set('max_execution_time', 0);
	
	define('DIR_ROOT', dirname(dirname(dirname(__FILE__))));
	define('DIR_CORE', DIR_ROOT.'/core');
	define('DIR_MEDIA', DIR_ROOT.'/media');
	define('DIR_THEME', DIR_ROOT.'/theme');
	define('DIR_MODULE', DIR_CORE.'/module');
	define('DIR_PLUGINS', DIR_CORE.'/plugins');
	define('DIR_FRAMEWORK', DIR_CORE.'/framework');
	require DIR_FRAMEWORK.'/config/site.config.php';
	require DIR_FRAMEWORK.'/config/core.config.php';
	require DIR_FRAMEWORK.'/config/date.config.php';
	
	require DIR_COMMON.'/error_handler.php';
	require DIR_COMMON.'/db_open.php';
	require DIR_COMMON.'/site_setting.php';
	require DIR_COMMON.'/stdlib.php';
	
	require_once 'donor.class.php';
	require DIR_LIBS.'/user.class.php';
	$objUser = new User($GLOBALS['myDB']);
	$objDonor = new Donor($GLOBALS['myDB']);

	require_once DIR_LIBS.'/stripe-php/init.php';
	\Stripe\Stripe::setApiKey(STRIPE_PRIVATE_KEY);

	ob_start();

	//get today's subscription
	$condition = " AND s.`billing_date` = '".date('j')."'";
	$subscriptionList = $objDonor->getSubscriptionOfTheDay($condition);
	$retryList = array();

	//charge it to stripe
	foreach($subscriptionList =>$subscriptionKey=>$subscriptionData){
		/** Stripe Payment - Start **/
		try{
			$charge = \Stripe\Charge::create(array(
				"amount" => $subscriptionData['amount']*100, //convert amount to cents
				"currency" => strtolower($subscriptionData['currency_code']),
				"description" => "Monthly Gift for ".$subscriptionData['description'],
				"source" => $subscriptionData['stripe_source_id'],
				"customer" => $subscriptionData['stripe_cust_id']
			));

		}catch(\Stripe\Error\Card $e) {
			// The card has been declined
			$temp = array();
			$temp['subscription_id'] = $subscriptionData['id'];
			$temp['status'] = 0;
			$temp['message'] = $e->getMessage();
			$temp['created_date'] = date("Y-m-d H:i:s");
			
			$objDonor->saveSubscriptionLog($temp);
			$retryList = $subscriptionData['id'];
		} catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
			$temp = array();
			$temp['subscription_id'] = $subscriptionData['id'];
			$temp['status'] = 0;
			$temp['message'] = $e->getMessage();
			$temp['created_date'] = date("Y-m-d H:i:s");
			
			$objDonor->saveSubscriptionLog($temp);
			$retryList = $subscriptionData['id'];
		} catch (\Stripe\Error\InvalidRequest $e) {
			// Invalid parameters were supplied to Stripe's API
			$temp = array();
			$temp['subscription_id'] = $subscriptionData['id'];
			$temp['status'] = 0;
			$temp['message'] = $e->getMessage();
			$temp['created_date'] = date("Y-m-d H:i:s");
			
			$objDonor->saveSubscriptionLog($temp);
			$retryList = $subscriptionData['id'];
		} catch (\Stripe\Error\Authentication $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$temp = array();
			$temp['subscription_id'] = $subscriptionData['id'];
			$temp['status'] = 0;
			$temp['message'] = $e->getMessage();
			$temp['created_date'] = date("Y-m-d H:i:s");
			
			$objDonor->saveSubscriptionLog($temp);
			$retryList = $subscriptionData['id'];
		} catch (\Stripe\Error\ApiConnection $e) {
		  // Network communication with Stripe failed
			$temp = array();
			$temp['subscription_id'] = $subscriptionData['id'];
			$temp['status'] = 0;
			$temp['message'] = $e->getMessage();
			$temp['created_date'] = date("Y-m-d H:i:s");
			
			$objDonor->saveSubscriptionLog($temp);
			$retryList = $subscriptionData['id'];
		} catch (\Stripe\Error\Base $e) {
		  // Display a very generic error to the user, and maybe send
		  // yourself an email
			$temp = array();
			$temp['subscription_id'] = $subscriptionData['id'];
			$temp['status'] = 0;
			$temp['message'] = $e->getMessage();
			$temp['created_date'] = date("Y-m-d H:i:s");
			
			$objDonor->saveSubscriptionLog($temp);
			$retryList = $subscriptionData['id'];
		} catch (Exception $e) {
		  // Something else happened, completely unrelated to Stripe
			$temp = array();
			$temp['subscription_id'] = $subscriptionData['id'];
			$temp['status'] = 0;
			$temp['message'] = $e->getMessage();
			$temp['created_date'] = date("Y-m-d H:i:s");
			
			$objDonor->saveSubscriptionLog($temp);
			$retryList = $subscriptionData['id'];
		}	
			
		/** Stripe Payment - End **/
		
		//Save to log
		$temp = array();
		$temp['subscription_id'] = $subscriptionData['id'];
		$temp['status'] = 1;
		$temp['message'] = "Monthly Gift for ".date('F');
		$temp['created_date'] = date("Y-m-d H:i:s");
		
		$objDonor->saveSubscriptionLog($temp);
	}
	
	//write log
	

	//retry for those that fail (?)

	//retry for two times 
	
	ob_end_flush();
	require DIR_COMMON.'/db_close.php';
	echo "\n<!-- Memory Usage: ".memory_get_usage()." bytes -->";

?>