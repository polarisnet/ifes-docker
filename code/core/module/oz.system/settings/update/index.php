<?php
	$breadCrumbData = getBreadCrumbData(MODULE_UID, "/");
	$setting = array(
		"title" => SITE_NAME.$breadCrumbData['title'],
		"meta_keyword" => "",
		"meta_description" => "",
		"extjs" => "1",
		"header" => "1",
		"header_dir" => DIR_ACTIVE_THEME."/header.php",
		"left" => "1",
		"left_dir" => DIR_ACTIVE_THEME."/leftbar.php",
		"left_width" => "180",
		"left_maximize" => getCookieValue('toggle_leftbar'),
		"left_uid" => '',
		"left_module" => MODULE_NAME,
		"center_dir" => DIR_ACTIVE_THEME."/oz.system/settings/update/update.php",
		"right" => "0",
		"right_dir" => "",
		"footer" => "1",
		"footer_dir" => DIR_ACTIVE_THEME."/footer.php",
		"widgets" => "0",
		"current" => "",
		"load_tile" => "0",
		"load_breadcrumb" => "1"
	);
	$access 	= checkAccess(MODULE_UID);
	$actionData = $GLOBALS['seo']->getActionURL();
	$action 	= array_shift($actionData);
	$error 		= array('type' => 'error', 'title' => 'Error', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$warning 	= array('type' => 'warning', 'title' => 'Warning', 'content' => '', 'position' => 'right', 'autoclose' => false);
	$message 	= array('type' => 'message', 'title' => 'Message', 'content' => '', 'position' => 'right', 'autoclose' => false);	
	$markError 	= array();
	$HTTP_AJAX 	= HTTP_ACTIVE_MODULE.'/ajax';
	switch(MODULE_UID){
		default:
			if($action == 'ajax'){
				$output = array('success' => false, 'message' => 'Missing ajax operation. Please contact administrator.');
				$opt = checkParam('opt');
				switch($opt){
					case 'read_log':
						$file = checkParam('file');
						$output['success'] = true;
						$output['log'] = readUpdateLogHTML($file);
					break;
					case 'update_now':
						updateSysUpdater(array('updater_status' => '1'));
						$output['success'] = true;
						appendUpdateLog('#################################################################'.PHP_EOL.'Start system update at '.date('Y-m-d H:i:s').PHP_EOL.'Please refresh this page regularly to track update progress'.PHP_EOL.PHP_EOL);
					break;
					case 'backup_db':
						ini_set("memory_limit","-1");
						ini_set('max_execution_time', 0);
						appendUpdateLog('#################################################################'.PHP_EOL.'Start database backup at '.date('Y-m-d H:i:s').PHP_EOL);
						if(doUpdateBackupDB()){
							$output['success'] = true;
							$output['list'] = "<option value=''>Please select any update logs...</option>".listUpdateLogHTML();
							appendUpdateLog('Database backup finished at '.date('Y-m-d H:i:s').PHP_EOL);
							updateSysUpdater(array('last_backup_db' => date('Y-m-d H:i:s')));
						
							$updaterData = getSysUpdater();
							$lastBackupDB = "-";
							if(isset($updaterData['last_backup_db']) && $updaterData['last_backup_db'] != '0000-00-00 00:00:00'){
								$date = new DateTime($updaterData['last_backup_db']);
								$lastBackupDB = $date->format('d/m/Y H:i:s');
							}
							$lastBackupScript = "-";
							if(isset($updaterData['last_backup_script']) && $updaterData['last_backup_script'] != '0000-00-00 00:00:00'){
								$date = new DateTime($updaterData['last_backup_script']);
								$lastBackupScript = $date->format('d/m/Y H:i:s');
							}
							$output['last_backup_db'] = $lastBackupDB;
							$output['last_backup_script'] = $lastBackupScript;
						}else{
							appendUpdateLog('Database backup failed at '.date('Y-m-d H:i:s').PHP_EOL);
						}
						appendUpdateLog('#################################################################'.PHP_EOL);
					break;
					case 'backup_scripts':
						ini_set("memory_limit","-1");
						ini_set('max_execution_time', 0);
						appendUpdateLog('#################################################################'.PHP_EOL.'Start scripts backup at '.date('Y-m-d H:i:s').PHP_EOL);
						if(doUpdateBackupScripts()){
							$output['success'] = true;
							$output['list'] = "<option value=''>Please select any update logs...</option>".listUpdateLogHTML();
							appendUpdateLog('Scripts backup finished at '.date('Y-m-d H:i:s').PHP_EOL);
							updateSysUpdater(array('last_backup_script' => date('Y-m-d H:i:s')));
						
							$updaterData = getSysUpdater();
							$lastBackupDB = "-";
							if(isset($updaterData['last_backup_db']) && $updaterData['last_backup_db'] != '0000-00-00 00:00:00'){
								$date = new DateTime($updaterData['last_backup_db']);
								$lastBackupDB = $date->format('d/m/Y H:i:s');
							}
							$lastBackupScript = "-";
							if(isset($updaterData['last_backup_script']) && $updaterData['last_backup_script'] != '0000-00-00 00:00:00'){
								$date = new DateTime($updaterData['last_backup_script']);
								$lastBackupScript = $date->format('d/m/Y H:i:s');
							}
							$output['last_backup_db'] = $lastBackupDB;
							$output['last_backup_script'] = $lastBackupScript;
						}else{
							appendUpdateLog('Scripts backup failed at '.date('Y-m-d H:i:s').PHP_EOL);
						}
						appendUpdateLog('#################################################################'.PHP_EOL);
					break;
				}
				echo json_encode($output);
				exit;
			}

			insertTracker(MODULE_NAME);
			$updaterData = getSysUpdater();
			$lastBackupDB = "-";
			if(isset($updaterData['last_backup_db']) && $updaterData['last_backup_db'] != '0000-00-00 00:00:00'){
				$date = new DateTime($updaterData['last_backup_db']);
				$lastBackupDB = $date->format('d/m/Y H:i:s');
			}
			$lastBackupScript = "-";
			if(isset($updaterData['last_backup_script']) && $updaterData['last_backup_script'] != '0000-00-00 00:00:00'){
				$date = new DateTime($updaterData['last_backup_script']);
				$lastBackupScript = $date->format('d/m/Y H:i:s');
			}

			$updateVersion = getNextUpdateVersion($updaterData);
			$hasUpdate = false;
			$nextVersion = "Your version is up to date";
			if(!empty($updateVersion)){
				$nextVersion = end($updateVersion);
				$hasUpdate = true;
			}
			$showUpdateOption = true;
			if($updaterData['updater_status'] != "0"){
				$showUpdateOption = false;
			}
		break;
	}
	require DIR_ACTIVE_THEME.'/site_builder.php';
?>