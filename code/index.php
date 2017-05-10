<?php
	error_reporting(E_ALL & ~E_DEPRECATED);
	ini_set('display_errors','On');
	
	define('DIR_ROOT', dirname(__FILE__));
	define('DIR_CORE', DIR_ROOT.'/core');
	define('DIR_MEDIA', DIR_ROOT.'/media');
	define('DIR_THEME', DIR_ROOT.'/theme');
	define('DIR_MODULE', DIR_CORE.'/module');
	define('DIR_PLUGINS', DIR_CORE.'/plugins');
	define('DIR_FRAMEWORK', DIR_CORE.'/framework');
	require DIR_FRAMEWORK.'/config/site.config.php';
	require DIR_FRAMEWORK.'/config/core.config.php';
	require DIR_FRAMEWORK.'/config/date.config.php';
	require DIR_LOCALIZATION.'/en/shortcut.php';
	
	require DIR_COMMON.'/error_handler.php';
	require DIR_COMMON.'/db_open.php';
	require DIR_COMMON.'/site_setting.php';
	require DIR_COMMON.'/stdlib.php';
	
	require DIR_LIBS.'/seo.class.php';
	
	if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
		ob_start('ob_gzhandler');
	}else{
		ob_start();
	}

	$seo = new SEO($myDB);
	$seo->execute();
	ob_end_flush();
	
	//print_r(get_defined_constants(true)['user']);
	require DIR_COMMON.'/db_close.php';
	echo "\n<!-- Memory Usage: ".memory_get_usage()." bytes -->";
?>