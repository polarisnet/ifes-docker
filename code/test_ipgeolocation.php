<?php
	define('DIR_ROOT', dirname(__FILE__));
	define('DIR_CORE', DIR_ROOT.'/core');
	define('DIR_PLUGINS', DIR_CORE.'/plugins');
	
	require DIR_PLUGINS.'/ip_geolocation/geoip2.phar';
		
	use GeoIp2\WebService\Client;
	use GeoIp2\Database\Reader;
	
	$output = '';
	$caught = false;

	// This creates the Reader object, which should be reused across lookups.
	$reader = new Reader(DIR_PLUGINS.'/ip_geolocation/GeoLite2-Country.mmdb');

	// Replace "city" with the appropriate method for your database, e.g., "country".
	try{
		$record = $reader->country($_SERVER['REMOTE_ADDR']); //e.g., '175.139.129.71', '128.101.101.101'
	}catch (Exception $e){
		//default to US if address not found in database
		$caught = true;
		echo $e->getMessage();
	}
	
	if(!$caught) {
		$output = $record->country->isoCode;
	}
	
	echo $output;
	
?>