<?php 
	define('DIR_COMMON', DIR_FRAMEWORK.'/common');
	define('DIR_LOCALIZATION', DIR_FRAMEWORK.'/localization');
	define('DIR_LOGS', DIR_FRAMEWORK.'/logs');
	define('DIR_LIBS', DIR_FRAMEWORK.'/libs');
	
	if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443'){
		define('PROTOCOL', 'https://');
	}else{
		define('PROTOCOL', 'http://');
	}

	if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '80'){
		define('HTTP_SERVER', PROTOCOL.$_SERVER['HTTP_HOST']);
	}else{
		if(isset($_SERVER['SERVER_NAME']) && isset($_SERVER['SERVER_PORT'])){
			define('HTTP_SERVER', PROTOCOL.$_SERVER['HTTP_HOST']);
		}else{
			define('HTTP_SERVER', '');
		}
	}
	
	define('HTTP_PLUGIN', HTTP_SERVER.HTTP_ROOT.'/core/plugins');
	define('HTTP_MEDIA', HTTP_SERVER.HTTP_ROOT.'/media');
	date_default_timezone_set('Asia/Kuala_Lumpur');
	$dtTimeZone = new DateTimeZone(date_default_timezone_get()); // Get default system timezone to create a new DateTimeZone object 
	$dtOffset = $dtTimeZone->getOffset(new DateTime("2012-01-01")); // Offset in seconds to UTC 
	define('TIMEZONE_OFFSET', $dtOffset);
?>