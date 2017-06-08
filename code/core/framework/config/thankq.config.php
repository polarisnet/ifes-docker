<?php
	if(PRODUCTION_MODE == '1'){
		define('THANKQ_HOST', 'jehieltestdb.cfevskzua3nd.eu-west-2.rds.amazonaws.com');
		define('THANKQ_PORT', '3306');
		define('THANKQ_DB', 'jehieltestdb');
		define('THANKQ_USERNAME', 'PolarisDBA');
		define('THANKQ_PASSWORD', 'T6(be3$A*B2d8$Gt4#aH');
		define('THANKQ_CA_CERT', DIR_FRAMEWORK.'/config/rds-combined-ca-bundle.pem');
	}else{
		define('THANKQ_HOST', '192.168.0.3');
		define('THANKQ_PORT', '3306');
		define('THANKQ_DB', 'thankq_db');
		define('THANKQ_USERNAME', 'root');
		define('THANKQ_PASSWORD', 'root');
		define('THANKQ_CA_CERT', '');
	}
?>