<?php
	ini_set("memory_limit","-1");
	ini_set('max_execution_time', 0);
	define('DIR_ROOT', dirname(dirname(__FILE__)));
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

	$updaterData = getSysUpdater();
	if($updaterData['updater_status'] == "1"){
		updateSysUpdater(array('updater_status' => '2'));
		appendUpdateLog('Processing update request....'.PHP_EOL);

		$updateVersion = getNextUpdateVersion($updaterData);
		foreach($updateVersion AS $version){
			appendUpdateLog("Downloading version $version".PHP_EOL);

			$connection = curl_init();
			curl_setopt($connection, CURLOPT_URL, $updaterData['updater_server']);
			curl_setopt($connection, CURLOPT_VERBOSE, 1);
			curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($connection, CURLOPT_POST, 1);
			curl_setopt($connection, CURLOPT_CONNECTTIMEOUT, 900);
			curl_setopt($connection, CURLOPT_TIMEOUT, 900);
			$postData = array(
				'opt' => "check_patch_exists",
				'project' => $updaterData['updater_option'],
				'version' => $version
			);
			curl_setopt($connection, CURLOPT_POSTFIELDS, $postData);
			$httpResponse = curl_exec($connection);
			if($httpResponse){
				$response = json_decode($httpResponse, true);
				if($response['success'] == "1"){
					file_put_contents(DIR_ROOT."/_updater/temp/$version.zip", fopen($updaterData['updater_server']."?opt=get_patch&project=".$updaterData['updater_option']."&version=".$version, 'r'));
					appendUpdateLog("Download version $version done".PHP_EOL);
				}else{
					appendUpdateLog("Updater file does not exist for $version".PHP_EOL.PHP_EOL);
					appendUpdateLog("Update operation has been interrupted".PHP_EOL.'#################################################################'.PHP_EOL);
					updateSysUpdater(array('updater_status' => '0'));
					echo "Update operation has been interrupted.";
					exit;
				}
			}else{
				appendUpdateLog("Could not get cURL respond when downloading version $version".PHP_EOL.PHP_EOL);
				appendUpdateLog("Update operation has been interrupted".PHP_EOL.'#################################################################'.PHP_EOL);
				updateSysUpdater(array('updater_status' => '0'));
				echo "Update operation has been interrupted.";
				exit;
			}

			$zip = new ZipArchive;
			if($zip->open(DIR_ROOT."/_updater/temp/$version.zip") === TRUE){
				$zip->extractTo(DIR_ROOT);
				$zip->close();

				unlink(DIR_ROOT."/_updater/temp/$version.zip");
				if(file_exists(DIR_ROOT."/sql.sql")){
					$sqlSource = file_get_contents(DIR_ROOT."/sql.sql");
					$GLOBALS['myDB']->query("USE ".MY_DB_DATABASE);
					if(mysqli_multi_query($GLOBALS['myDB']->getConnection(), $sqlSource)){
						$doLoop = true;
						while($doLoop){
							if($result = mysqli_store_result($GLOBALS['myDB']->getConnection())){
								mysqli_free_result($result);
							}
							if(mysqli_more_results($GLOBALS['myDB']->getConnection())){
								mysqli_next_result($GLOBALS['myDB']->getConnection());
							}else{
								$doLoop = false;
							}
						}
					}else{
						appendUpdateLog("Could not execute SQL file for version $version".PHP_EOL.PHP_EOL);
						appendUpdateLog("Update operation has been interrupted".PHP_EOL.'#################################################################'.PHP_EOL);
						updateSysUpdater(array('updater_status' => '0'));
						echo "Update operation has been interrupted.";
						exit;
					}
					unlink(DIR_ROOT."/sql.sql");
				}
				updateSysUpdater(array('version' => $version));
				appendUpdateLog("Update successful to version $version".PHP_EOL.PHP_EOL);
			}else{
				appendUpdateLog("Could not extract patch compressed file for version $version".PHP_EOL.PHP_EOL);
				appendUpdateLog("Update operation has been interrupted".PHP_EOL.'#################################################################'.PHP_EOL);
				updateSysUpdater(array('updater_status' => '0'));
				echo "Update operation has been interrupted.";
				exit;
			}
		}
		appendUpdateLog("Update operation finished at ".date('Y-m-d H:i:s').PHP_EOL.'#################################################################'.PHP_EOL);		
		updateSysUpdater(array('updater_status' => '0'));
	}
	echo "Updater finish running.";
?>