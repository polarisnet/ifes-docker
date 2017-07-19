<?php
	define('HTTP_ROOT', '/ifes-docker/code'); ///ifes-docker/code
	define('HTTP_VERSION', '1');
	define('SITE_NAME', 'IFES');
	define('PUBLIC_SALT', 'JL2Y7nKpaimZjN');
	define('PRODUCTION_MODE', '1');
	if(PRODUCTION_MODE == '1'){
		define('MY_DB_DATABASE', 'staging'); //admin_oz
		define('MY_DB_SERVER', 'ifes.cqplcgg2jwao.ap-southeast-1.rds.amazonaws.com');
		define('MY_DB_USER', 'ifesroot');
		define('MY_DB_PASS', 'SPhptF7ZltCQYP');
	}else{
		define('MY_DB_DATABASE', 'ifes_db'); //admin_oz
		define('MY_DB_SERVER', 'localhost');
		define('MY_DB_USER', 'root');
		define('MY_DB_PASS', 'root');
	}
	define('ERROR_HANDLER', '1');
	define('ERROR_DISPLAY', '0');
	
	define('DIR_EMAS_SOFTWARE_DATA', 'D:\\data'); // C:\\EMAS\\yuta\\DATA  C:\csb\acc\data OR E:\EMASKTH\acc\data OR E:\\EMAS\\ACC\\data OR \\\\192.168.1.101\\wmsemas/ACC/DATA
	define('DIR_EMAS_CONTENTS_TYPE', 'GBK'); //GBK //Big5 //GB2312 //GB18030
	define('DIR_EMAS_CONTENTS_TYPE_ALTERNATIVE', 'GB2312'); //Big5 //GB2312 //GB18030
	
	define('ENABLED_EMAS_SYNC_EXPORT', 'YES'); //YES
	define('EXPORT_SO_VERSION', 'GST'); //NONGST
	
	define('DIR_UBS_SOFTWARE_DATA', 'C:\UBSSTK90\Sample');
	define('DIR_UBS_SOFTWARE_DATA_A', 'C:\UBSSTK90\Sample');
	
	define('STRIPE_PUBLIC_KEY', 'pk_test_dyLANJjgegRJ1uHgPNGSXKZ2');
	define('STRIPE_PRIVATE_KEY', 'sk_test_ChfV2xHC0WAUiDODIIOm57Ek');
	
	require_once 'port.config.php';
?>