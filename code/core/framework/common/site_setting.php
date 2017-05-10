<?php
	$siteSetting = array();
	$sql = "SELECT * FROM `sys_settings` WHERE `id`='1' LIMIT 1";
	$myDB->query($sql);
	if($myDB->nextRecord()){
		$siteSetting = $myDB->getRecord();
		if($siteSetting['polaris_cdn'] == 1){
			define('HTTP_CDN_PLUGIN', 'http://touchsales.net/core/plugins');
		}else{
			define('HTTP_CDN_PLUGIN', HTTP_PLUGIN);
		}
	}
	
	function loadModule($module){
		switch($module){
			case '404':
				echo 'Not found..<br>Custom 404';
			break;
			default:
				$module = DIR_MODULE.$module."/index.php";
				require $module;
			break;
		}
	}
?>