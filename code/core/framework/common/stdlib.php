<?php
	require DIR_PLUGINS.'/ip_geolocation/geoip2.phar';
	
	use GeoIp2\Database\Reader;
		
	/** Compatibility - Start **/
	if(!defined('PHP_VERSION_ID')){
		$version = explode('.', PHP_VERSION);
		define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
	}
	
	function swapArrayValue(&$ar,$element1,$element2){
		$temp = $ar[$element1];
		$ar[$element1] = $ar[$element2];
		$ar[$element2] = $temp;
	}
	/** Compatibility - End **/

	/** MYSQL - Start **/
	function cleanMYQuery($input){
		global $myDB;
		if(get_magic_quotes_gpc()){
			$input = stripslashes($input);
		}
		//return mysql_real_escape_string($input); //for php < 5.5
		return mysqli_real_escape_string($myDB->getConnection(), $input);
	}
	/** MYSQL - End **/
	
	/** Visual FoxPro - Start **/
	function escapeVFPSquareBracket($value){
		if(strpos($value, "\"") !== false){
			$newValue = "";
			$arrayVal = str_split($value);
			$last = strlen($value)-1;
			foreach($arrayVal AS $key => $val){
				switch($val){
					case '[':
						$tempVal = "'['";
						if($key != 0){
							$tempVal = "]+".$tempVal;
						}
						if(isset($arrayVal[$key+1])){
							$tempVal .= "+[";
						}
						$newValue .= $tempVal;
					break;
					case ']':
						$tempVal = "']'";
						if($key != 0){
							$tempVal = "]+".$tempVal;
						}
						if(isset($arrayVal[$key+1])){
							$tempVal .= "+[";
						}
						$newValue .= $tempVal;
					break;
					default:
						if($key == 0){$newValue .= "[";}
						$newValue .= $val;
						if($key == $last){$newValue .= "]";}
					break;
				}
			}
		}else{
			$newValue = "\"".$value."\"";
		}
		return $newValue;
	}
	/** Visual FoxPro - End **/
	
	/** Security - Start **/
	function GUID(){
	    if (function_exists('com_create_guid') === true){
	        return trim(com_create_guid(), '{}');
	    }
	    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	function ccMasking($number, $maskingCharacter = 'X') {
    	return substr($number, 0, 4) . str_repeat($maskingCharacter, strlen($number) - 8) . substr($number, -4);
	}

	function checkLogin($seoData, $cleanURL, $getURL){
		session_name(SESSION_NAME); 
		session_start();
		switch($seoData['secure_mode']){
			case 'bo':
				if(matchCookieSession() && isset($_SESSION['login']['mode'])){
					$checkExpired = checkExpired();
					if(is_array($checkExpired) && count($checkExpired)>=0){
						if($checkExpired[0]['session'] != session_id()){	
							deleteAllCookies();
							session_destroy();						
							$returnURL = HTTP_SERVER.HTTP_ROOT.SITE_BO_LOGIN."?action=expired";
							if($cleanURL != ""){						
								$returnURL .= "&return=".$cleanURL;
								if($getURL != ""){
									$returnURL .= $getURL;
								}
							}
							header("Location: ".$returnURL);
							exit;
						}
					}
					if($_SESSION['login']['mode'] == "bo" || $_SESSION['login']['mode'] == "both"){
						loadModule($seoData['module_dir']);
						exit;
					}else{
						header("Location: ".HTTP_SERVER.HTTP_ROOT);
						exit;
					}
				}else{
					$returnURL = HTTP_SERVER.HTTP_ROOT.SITE_BO_LOGIN;
					if($cleanURL != ""){						
						$returnURL .= "?return=".$cleanURL;
						if($getURL != ""){
							$returnURL .= $getURL;
						}
					}
					header("Location: ".$returnURL);
					exit;	
				}
			break;
			case 'fo':
				regionChecking();
				if(matchCookieSession() && isset($_SESSION['login']['mode'])){
					if($_SESSION['login']['mode'] == "fo" || $_SESSION['login']['mode'] == "both"){
						loadModule($seoData['module_dir']);
						exit;
					}else{
						header("Location: ".HTTP_SERVER.HTTP_ROOT);
						exit;
					}
				}else{
					$returnURL = HTTP_SERVER.HTTP_ROOT.SITE_FO_LOGIN;
					if($cleanURL != ""){
						$returnURL .= "?return=".$cleanURL;
						if($getURL != ""){
							$returnURL .= $getURL;
						}
					}
					header("Location: ".$returnURL);
					exit;	
				}
			break;
			case 'none':
			default:
				regionChecking();
				loadModule($seoData['module_dir']);
			break;
		}
	}
	
	function matchCookieSession(){
		$cookieName = str_replace(" ", "", COOKIE_NAME).'_SESSION';
		if(isset($_COOKIE[$cookieName]) && !empty($_SESSION)){
			$cookie = $_COOKIE[$cookieName];
			if(isset($cookie['token']) && isset($_SESSION['login']['token']) && $cookie['token'] == $_SESSION['login']['token'] && $cookie['id'] == session_id()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	function checkExpired(){
		global $myDB;
		$output = array();
		if(isset($_SESSION['user_id'])){
			$sql = "SELECT * FROM `sys_session` WHERE `user_id` = '".$_SESSION['user_id']."' ORDER BY `id` DESC";
			$myDB->query($sql);
			if($myDB->nextRecord()){
				$result = $myDB->getRecord();
				array_push($output, $result);
			}
		}
		return $output;
	}
	
	function setCookieValue($data, $field){
		$siteName = str_replace(" ", "", COOKIE_NAME);
		setcookie($siteName."_SESSION[".$field."]", $data, 0, "/", "", false, true);
	}
	
	function deleteCookieValue($field){
		$siteName = str_replace(" ", "", COOKIE_NAME);
		setcookie($siteName."_SESSION[".$field."]", "", time()-9600, "/", "", false, true);
	}
	
	function deleteAllCookies(){
		$cookieName = str_replace(" ", "", COOKIE_NAME).'_SESSION';
		if(isset($_COOKIE[$cookieName])){
			$cookie = $_COOKIE[$cookieName];
			foreach($cookie AS $key => $value){
				setcookie($cookieName."[".$key."]", "", time()-9600, "/", "", false, true);
			}
		}
	}
	
	function getCookieValue($name){
		$output = "";
		$cookieName = str_replace(" ", "", COOKIE_NAME).'_SESSION';
		if(isset($_COOKIE[$cookieName])){
			$cookie = $_COOKIE[$cookieName];
			if(isset($cookie[$name])){
				$output = $cookie[$name];
			}
		}
		return $output;
	}
	
	function checkAccess($moduleUID){
		$output = false;
		if(isset($_SESSION['login'])){
			$groupId = "group_".$_SESSION['group_id'];
			$userId = "user_".$_SESSION['user_id'];
			if($_SESSION['group_id'] == '1' || $_SESSION['group_id'] == ''){
				$output = true;
			}else{
				global $myDB;
				$groupFlag = false;
				$sql = "SELECT `".$groupId."` FROM `sys_privileges` WHERE `module_uid`='".$moduleUID."'";
				$myDB->query($sql);
				if($myDB->nextRecord()){
					$result = $myDB->getRecord();
					$groupFlag = $result[$groupId];
				}
				
				$userFlag = true;
				// $sql = "SELECT `".$userId."` FROM `sys_privileges` WHERE `module_uid`='".$moduleUID."'";
				// $myDB->query($sql);
				// if($myDB->nextRecord()){
					// $result = $myDB->getRecord();
					// $userFlag = $result[$userId];
				// }
				
				if($groupFlag && $userFlag){
					$output = true;
				}
			}
		}
		return $output;
	}
	
	function getModuleStatus($moduleUID){
		global $myDB;
		$output = "0";
		$sql = "SELECT `status` FROM `sys_module` WHERE `uid`='".$moduleUID."'";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$result = $myDB->getRecord();
			$output = $result['status'];
		}
		return $output;
	}
	
	function getAccess($moduleUID, $groupModule = '', $userModule = ''){
		$output = false;
		if(isset($_SESSION['login'])){
			global $myDB;
			$groupFlag = false;
			if($groupModule != ''){
				$sql = "SELECT `".$groupModule."` FROM `sys_privileges` WHERE `module_uid`='".$moduleUID."'";
				$myDB->query($sql);
				if($myDB->nextRecord()){
					$result = $myDB->getRecord();
					$groupFlag = $result[$groupModule];
				}
			}	
			
			$userFlag = true;
			if($userModule != ''){
				$sql = "SELECT `".$userModule."` FROM `sys_privileges` WHERE `module_uid`='".$moduleUID."'";
				$myDB->query($sql);
				if($myDB->nextRecord()){
					$result = $myDB->getRecord();
					$userFlag = $result[$groupModule];
				}
			}
			if($groupFlag && $userFlag){
				$output = true;
			}
		}
		return $output;
	}
	
	function checkAccessRecords($parentUID){
		$output = false;
		if(isset($_SESSION['login'])){
			$group = $_SESSION['group_id'];
			global $myDB;
			$groupFlag = false;
			$sql = "SELECT `view` FROM `sys_privileges_records` WHERE `group_id`='".$group."' AND `module_uid`='".$parentUID."' LIMIT 1 ";
			$myDB->query($sql);
			if($myDB->nextRecord()){
				$result = $myDB->getRecord();
				$output = true;
			}
		}
		return $output;
	}
	
	function hashPassword($username, $password, $salt){
		$saltLength = strlen($salt);
		$userLength = strlen($username);
		$passLength = strlen($password);
		return hash('sha256', substr($salt, 0, $saltLength/2).substr($username, 0, $userLength/2).substr($password, 0, $passLength/2).substr($username, $userLength/2, $userLength).substr($password, $passLength/2, $passLength).substr($salt, $saltLength/2, $saltLength));
	}
	
	function generateSalt($length, $numeric = true, $lowercase = true, $uppercase = true){
		$salt = array();
		if($numeric){
			$temp = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
			$salt = array_merge($salt, $temp);
		}
		if($lowercase){
			$temp = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
			$salt = array_merge($salt, $temp);
		}
		if($uppercase){
			$temp = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
			$salt = array_merge($salt, $temp);
		}
		
		$saltLength = count($salt)-1;
		$output = "";
		for($i = 0; $i < $length; $i++){
			$output .= $salt[mt_rand(0, $saltLength)];
		}
		return $output;
	}
	
	function encryption($string, $key, $encrypt = true){
		$output = "";
		$key = md5(md5($key));
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		if($encrypt){
			$output = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB, $iv))); 
		}else{
			$output = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB, $iv)); 
		}
		return $output;
	}
	
	function getDateByTimestampWithTZ($timestamp, $format = "Y-m-d") {
		$dtDate = date("Y-m-d H:i:s", time());
		if($timestamp!="" && $format=="Y-m-d") {
			if(defined("TIMEZONE_OFFSET")) {
				$offset = constant("TIMEZONE_OFFSET");
			} else {
				$offset = 28800;
			}
			$ts = $timestamp+$offset;
			//$ts = 631123200;
			$date = new DateTime("@$ts");
			$dtDate = $date->format('Y-m-d');
		}
		return $dtDate;
	}
	
	// For project extjs exact or wilcard filtering
	function extjsTextboxFiltering($prefix = "", $field = "", $value = "") {
		$filter_query = "";
		if($field!="" && $value!="") {
			$value = str_replace("\\", "", $value);
			if((substr($value, 0, 1)=="'" && substr($value, (strlen($value)-1), 1)=="'") || substr($value, 0, 1)=="\"" && substr($value, (strlen($value)-1), 1)=="\"") {
				$value = substr($value, 1, (strlen($value)-2));
				$value = "'".str_replace(array("\"","'"), array("\\\"","\'"), $value)."'";
				$filter_query .= " AND ".($prefix!=""?$prefix.".":"").$field." = ".$value."";
			} else {
				$value = str_replace(array("\"","'"), array("\\\"","\'"), $value);
				$filter_query .= " AND ".($prefix!=""?$prefix.".":"").$field." LIKE '%".$value."%'";
			}
		}
		//echo $filter_query;exit;
		return $filter_query;
	}
	
	function strptimeCustom($sDate, $sFormat){
		$aResult = array( 
			'tm_sec'   => 0, 
			'tm_min'   => 0, 
			'tm_hour'  => 0, 
			'tm_mday'  => 1, 
			'tm_mon'   => 0, 
			'tm_year'  => 0, 
			'tm_wday'  => 0, 
			'tm_yday'  => 0, 
			'unparsed' => $sDate, 
		); 
	 
		while($sFormat != ""){ 
			// ===== Search a %x element, Check the static string before the %x ===== 
			$nIdxFound = strpos($sFormat, '%'); 
			if($nIdxFound === false){
				// There is no more format. Check the last static string. 
				$aResult['unparsed'] = ($sFormat == $sDate) ? "" : $sDate; 
				break; 
			} 
		 
			$sFormatBefore = substr($sFormat, 0, $nIdxFound); 
			$sDateBefore   = substr($sDate,   0, $nIdxFound); 
		 
			if($sFormatBefore != $sDateBefore) break; 
		 
			// ===== Read the value of the %x found ===== 
			$sFormat = substr($sFormat, $nIdxFound); 
			$sDate   = substr($sDate,   $nIdxFound); 
		 
			$aResult['unparsed'] = $sDate; 
		 
			$sFormatCurrent = substr($sFormat, 0, 2); 
			$sFormatAfter   = substr($sFormat, 2); 
		 
			$nValue = -1; 
			$sDateAfter = ""; 
		 
			switch($sFormatCurrent){ 
				case '%S': // Seconds after the minute (0-59) 
					sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter); 
					if(($nValue < 0) || ($nValue > 59)) return false; 
					$aResult['tm_sec']  = $nValue; 
				break;
				case '%M': // Minutes after the hour (0-59) 
					sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter); 
					if(($nValue < 0) || ($nValue > 59)) return false; 
					$aResult['tm_min']  = $nValue; 
				break;
				case '%H': // Hour since midnight (0-23) 
					sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter); 
					if(($nValue < 0) || ($nValue > 23)) return false; 
					$aResult['tm_hour']  = $nValue; 
				break;
				case '%d': // Day of the month (1-31) 
					sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter); 
					if(($nValue < 1) || ($nValue > 31)) return false; 
					$aResult['tm_mday']  = $nValue; 
				break;
				case '%m': // Months since January (0-11) 
					sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter); 
					if(($nValue < 1) || ($nValue > 12)) return false;
					$aResult['tm_mon']  = ($nValue - 1); 
				break;
				case '%Y': // Years since 1900 
					sscanf($sDate, "%4d%[^\\n]", $nValue, $sDateAfter); 
					if($nValue < 1900) return false; 
					$aResult['tm_year']  = ($nValue - 1900); 
				break; 
				default: 
				break 2; // Break Switch and while 
			} // END of case format 
		 
			// ===== Next please ===== 
			$sFormat = $sFormatAfter; 
			$sDate   = $sDateAfter; 
		 
			$aResult['unparsed'] = $sDate; 
		 
		} // END of while($sFormat != "") 
	 
		// ===== Create the other value of the result array ===== 
		$nParsedDateTimestamp = mktime($aResult['tm_hour'], $aResult['tm_min'], $aResult['tm_sec'], 
								$aResult['tm_mon'] + 1, $aResult['tm_mday'], $aResult['tm_year'] + 1900); 
		 
		// Before PHP 5.1 return -1 when error 
		if(($nParsedDateTimestamp === false) 
		||($nParsedDateTimestamp === -1)) return false; 
		 
		$aResult['tm_wday'] = (int) strftime("%w", $nParsedDateTimestamp); // Days since Sunday (0-6) 
		$aResult['tm_yday'] = (strftime("%j", $nParsedDateTimestamp) - 1); // Days since January 1 (0-365) 

		return $aResult; 
	} // END of function
	
	function backCDateCreateFromFormat($dformat, $dvalue){
		$schedule = $dvalue;
		$schedule_format = str_replace(array('Y','m','d', 'H', 'i','a'),array('%Y','%m','%d', '%I', '%M', '%p' ) ,$dformat);
		// %Y, %m and %d correspond to date()'s Y m and d.
		// %I corresponds to H, %M to i and %p to a
		$ugly = strptimeCustom($schedule, $schedule_format);
		$ymd = sprintf(
			// This is a format string that takes six total decimal
			// arguments, then left-pads them with zeros to either
			// 4 or 2 characters, as needed
			'%04d-%02d-%02d %02d:%02d:%02d',
			$ugly['tm_year'] + 1900,  // This will be "111", so we need to add 1900.
			$ugly['tm_mon'] + 1,      // This will be the month minus one, so we add one.
			$ugly['tm_mday'], 
			$ugly['tm_hour'], 
			$ugly['tm_min'], 
			$ugly['tm_sec']
		);
		$new_schedule = new DateTime($ymd);
		return $new_schedule;
	}
	
	function convertDate($input, $format = 'd/m/Y', $target = 'Y-m-d'){
		if($input != ''){
			$date = backCDateCreateFromFormat($format, $input);
			return date_format($date, $target);
		}else{
			return '';
		}
	}
	
	function convertToDate($input, $format = 'd/m/Y'){		
		if($input != "0000-00-00 00:00:00"){
			$date = new DateTime($input);
			$formatDate = $date->format($format);			
		} else {
			$formatDate = "";
		}
		return $formatDate;
	}
	
	function numberWithCommas($input, $decimal = 2){
		return number_format($input,$decimal,'.',',');
	}
        
        function numberWithoutCommas($input, $decimal = 2){
		return number_format($input,$decimal,'.','');
	}
	
	function checkParam($key, $default = "", $request = "", $settings = array()){
		global $myDB;
		if($request == ""){
			$request = $_SERVER['REQUEST_METHOD'];
		}
		$request = strtolower($request);
		
		$param = "";
		switch($request){
			case 'post':		$param = isset($_POST[$key])? $_POST[$key] : '';			break;
			case 'get':			$param = isset($_GET[$key])? $_GET[$key] : '';				break;
			case 'server':		$param = isset($_SERVER[$key])? $_SERVER[$key] : '';		break;
			case 'cookie':		$param = isset($_COOKIE[$key])? $_COOKIE[$key] : '';		break;
			case 'session':		$param = isset($_SESSION[$key])? $_SESSION[$key] : '';		break;
		}
		
		$defaultSettings = array('js' => true, 'css' => true, 'tag' => false, 'multiline' => false);
		$defaultSettings = array_merge($defaultSettings, $settings);
		
		$search = array();
		if($defaultSettings['js']){
			array_push($search, '@<script[^>]*?>.*?</script>@si');
		}
		if($defaultSettings['css']){
			array_push($search, '@<style[^>]*?>.*?</style>@siU');
		}
		if($defaultSettings['tag']){
			array_push($search, '@<[\/\!]*?[^<>]*?>@si');
		}
		if($defaultSettings['multiline']){
			array_push($search, '@<![\s\S]*?--[ \t\n\r]*>@');
		}
		
		if(is_array($param)){
			foreach($param AS $pK => $pV){
				$pV = preg_replace($search, '', $pV);
				if(get_magic_quotes_gpc()){$pV = stripslashes($pV);}
				//$param[$pK] = mysql_real_escape_string($pV); //for php < 5.5
				$param[$pK] = mysqli_real_escape_string($myDB->getConnection(), $pV);
				if($param[$pK]== ''){$param[$pK] = $default;}
			}
		}else{
			$param = preg_replace($search, '', $param);
			if(get_magic_quotes_gpc()){$param = stripslashes($param);}
			//$param = mysql_real_escape_string($param); //for php < 5.5
			$param = mysqli_real_escape_string($myDB->getConnection(), $param);
			if($param == ''){$param = $default;}
		}
		return $param;
	}
		
	function rrmdir($dir){
		if(is_dir($dir)){ 
			$objects = scandir($dir); 
			foreach($objects as $object){
				if($object != "." && $object != ".."){ 
					if(filetype($dir."/".$object) == "dir"){
						rrmdir($dir."/".$object); 
					}else{
						chmod($dir."/".$object, 0777);
						unlink($dir."/".$object); 
					}
			   } 
			} 
			reset($objects); 
			rmdir($dir); 
		} 
	}
	
	function xcopy($source, $dest, $permissions = 0777){
		// Check for symlinks
		if (is_link($source)) {
			return symlink(readlink($source), $dest);
		}

		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}

		// Make destination directory
		if (!is_dir($dest)) {
			mkdir($dest, $permissions);
		}

		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			// Deep copy directories
			xcopy("$source/$entry", "$dest/$entry", $permissions);
		}

		// Clean up
		$dir->close();
		return true;
	}
	
	function backupBeforePatch(){
		if(file_exists(DIR_ROOT.'/_updater/backup')){
			rrmdir(DIR_ROOT.'/_updater/backup');
		}
		mkdir(DIR_ROOT.'/_updater/backup', 07555);
		xcopy(DIR_ROOT."/theme", DIR_ROOT.'/_updater/backup/theme', 0755);
		
		mkdir(DIR_ROOT.'/_updater/backup/core', 07555);
		xcopy(DIR_ROOT."/core/module", DIR_ROOT.'/_updater/backup/core/module', 0755);
		xcopy(DIR_ROOT."/core/cron", DIR_ROOT.'/_updater/backup/core/cron', 0755);
		xcopy(DIR_ROOT."/core/framework", DIR_ROOT.'/_updater/backup/core/framework', 0755);
	}
	
	function backupMysql($db){
		if(file_exists(DIR_ROOT.'/_updater/backup_db')){
			rrmdir(DIR_ROOT.'/_updater/backup_db');
		}
		mkdir(DIR_ROOT.'/_updater/backup_db', 0777);
		
		$listTable = array();
		$sql = "SHOW FULL TABLES WHERE Table_type != 'VIEW'";
		$db->query($sql);
		while($db->nextRecord()){
			$result = $db->getRecord();
			array_push($listTable, $result['Tables_in_'.MY_DB_DATABASE]);
		}
		foreach($listTable AS $table){
			$file = DIR_ROOT."/_updater/backup_db/$table.sql";
			$query = str_replace("\\", "/", "SELECT * INTO OUTFILE '$file' FROM `$table`");
			if(mysqli_query($db->getConnection(), $query)){
				chmod(DIR_ROOT."/_updater/backup_db/$table.sql", 0777);
			}
		}
	}
	
	function write_php_ini($array, $file){
		$res = array();
		foreach($array as $key => $val){
			if(is_array($val)){
				$res[] = "[$key]";
				foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			}else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
		}
		safefilerewrite($file, implode("\r\n", $res));
	}

	function safefilerewrite($fileName, $dataToSave){
		if ($fp = fopen($fileName, 'w')){
			$startTime = microtime(TRUE);
			do{
				$canWrite = flock($fp, LOCK_EX);
				// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
				if(!$canWrite) usleep(round(rand(0, 100)*1000));
			} while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

			//file was locked so now we can store information
			if ($canWrite){
				fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			fclose($fp);
		}
	}
	
	function nl2eol($string) { 
		$string = str_replace(array("\\r\\n", "\\r", "\\n"), PHP_EOL, $string);
		$string = stripslashes($string);
		return $string; 
	} 
	
	/* Not in use since new escaped string implemented (25/10/2013)
	function nl2br2($string) { 
		$string = str_replace(array("\\r\\n", "\\r", "\\n"), "<br />", $string); 
		$string = stripslashes($string);
		return $string; 
	} 
	
	
	*/
	
	function generateRunningNo($id){
		global $myDB;
		$no = "";
		if($id != ""){
			$sql = "SELECT * FROM `sys_running_number` WHERE `module_uid`='".$id."'";
			$myDB->query($sql);
			if($myDB->nextRecord()){
				$result = $myDB->getRecord();
				$no .= $result['prefix'];
				$iteration = $result['current'] + 1;
				$digit = $result['padding'];
				for($i = strlen($iteration); $i<$digit; $i++){
					$no .= "0";
				}
				$no .= $iteration.$result['suffix'];
			}
		}
		return $no;
	}
	
	function updateRunningNo($id){
		global $myDB;
		$sql = "SELECT * FROM `sys_running_number` WHERE `module_uid`='".$id."'";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$result = $myDB->getRecord();
			$current = $result['current'] + 1;
		}
		if(isset($current)){
			$sql = "UPDATE `sys_running_number` SET `current`='".$current."' WHERE `module_uid`='".$id."'";
			$myDB->query($sql);
		}
	}
	/** Security - End **/
	
	/** Internal Memory - Start **/
	function getInternalMemory($label){
		global $myDB;
		$result = array();
		$sql = "SELECT * FROM `sys_internal_memory` WHERE `label`='".$label."'";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$result = $myDB->getRecord();
			$result['memory'] = json_decode($result['memory'], true);
		}
		return $result;
	}
	
	function updateInternalMemory($data){
		global $myDB;
		$data['memory'] = json_encode($data['memory']);
		if($myDB->update("sys_internal_memory", $data, "`label`='".$data['label']."'")){
			return true;
		}else{
			return false;
		}
	}
	/** Internal Memory - End **/
	
	/** Audit Trails - Start **/
	function insertAuditTrails($module , $type = "general", $extra = "", $json_before = array(), $json_after = array()){
		global $myDB;
		$data = array();
		$data['type'] = $type;
		$data['module'] = $module;
		if($data['type'] == 'update' || $data['type'] == 'delete'){
			foreach($json_before AS $jK => $jV){
				$json_before[$jK] = cleanMYQuery($jV);
			}
		}
		$data['json_before'] = stripslashes(json_encode($json_before));
		$data['json_after'] = stripslashes(json_encode($json_after));
		$data['extra'] = $extra;
		if(isset($_SESSION['user_id'])){
			$data['created_by'] = $_SESSION['user_id'];
		}
		$data['created_date'] = date("Y-m-d H:i:s");
		if($myDB->insert("sys_audit_trails", $data)){
			return true;
		}else{
			return false;
		}
	}
	/** Audit Trails - End **/
	
	/** Validation - Start **/
	function validateEmptyField($data, $field, &$error){
		$output = true;
		if($data == ''){
			$output = false;
			$error['content'] = ucfirst($field).' cannot be empty.';
		}
		return $output;
	}
		
	function validatePhoneField($data, $field, &$error){ 
		$output = true;
		$data =  str_replace('+', '', $data);
		$data =  str_replace('-', '', $data);
		$data =  str_replace(' ', '', $data);
                $data =  str_replace('/', '', $data);
		if(!is_numeric($data) && $field == "mobile"){ // only validate mobile phone numbers.
			$output = false;
			$error['content'] = "Invalid ".ucfirst($field).". ".ucfirst($field)." should be in numeric format.";
		}else if(floor($data) != $data  && $field == "mobile"){
			$output = false;
			$error['content'] = "Invalid ".ucfirst($field).". ".ucfirst($field)." should not be in decimal format.";
		}
		return $output;
	}
	
	function validateDateField($data, $field, &$error, $minYear = '',$maxYear = ''){
		$output = true;
		$test_date  = explode('/', $data);
		if($minYear == ''){	$minYear = "1800"; }
		if($maxYear == ''){	$maxYear = "2999"; }		
		if (count($test_date) == 3) {
			$dd = $test_date[0]; $mm = $test_date[1]; $yy = $test_date[2];
			if($dd < 1 || $dd > 31) {
				$output = false;
				$error['content'] = ucfirst($field)." should in date format. Invalid value for day: ".$dd;
			} else if($mm < 1 ||$mm > 12) {
			  	$output = false;
				$error['content'] = ucfirst($field)." should in date format. Invalid value for month: ".$mm;
			} else if($yy < $minYear || $yy > $maxYear) {
			  	$output = false;
				$error['content'] = ucfirst($field)." should in date format. Invalid value for year: ".$yy.". (Year must be between ".$minYear." and ".$maxYear.")";
			}			
	   } else {
		  $output = false;
		  $error['content'] = "Invalid ".ucfirst($field).". ".ucfirst($field)." should in date format. Example: 'dd/mm/yyyy'.";
	   }
		return $output;
	}
	
	function validateNumericField($data, $field, &$error, $allowNegative = false, $allowDecimal = true, $allowZero = true){
		$output = true;
		if(!is_numeric($data)){
			$output = false;
			$error['content'] = "Invalid ".ucfirst($field).". ".ucfirst($field)." should in numeric format.";
		}else{
			if(!$allowNegative && $data < 0){
				$output = false;
				$error['content'] = "Invalid ".ucfirst($field).". ".ucfirst($field)." should not lesser than 0.";
			}
			if(!$allowDecimal && (floor($data) != $data)){
				$output = false;
				$error['content'] = "Invalid ".ucfirst($field).". ".ucfirst($field)." should not in decimal format.";
			}
			if(!$allowZero && $data == 0){
				$output = false;
				$error['content'] = "Invalid ".ucfirst($field).". ".ucfirst($field)." should not be 0.";
			}
		}
		return $output;
	}
	/** Validation - End **/
	
	/** Tracker - Start **/
	function getCurrentURL(){
		return HTTP_SERVER.$_SERVER['REQUEST_URI'];
	}
	
	function insertTracker($title, $url = ''){
		global $myDB;
		if($url == ''){
			$url = getCurrentURL();
		}
		if($title != ''){
			//$title = mysql_real_escape_string($title); //for php < 5.5
			$title = mysqli_real_escape_string($myDB->getConnection(), $title);
		}
		$url = urlencode($url);
		$myDB->delete("sys_event_tracker", "`session`='".session_id()."' AND (`url`='".$url."' OR `title`='".$title."')");

		$limiter = 10;
		$sql = "SELECT `id` FROM `sys_event_tracker` WHERE `session`='".session_id()."' ORDER BY `created_date` ASC";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$output = $myDB->getRecord();
			$total = $myDB->numRow();
			if($total >= $limiter){
				$myDB->delete("sys_event_tracker", "`id`='".$output['id']."'");
			}
		}
		$data = array();
		$data['session'] = session_id();
		$data['url'] = $url;
		$data['title'] = $title;
		$data['created_by'] = $_SESSION['user_id'];
		$data['created_date'] = date("Y-m-d H:i:s");
		$myDB->insert("sys_event_tracker", $data);
	}
	
	function getTrackerData($session = ''){
		global $myDB;
		$output = array();
		if($session == ''){
			$session = session_id();
		}
		$sql = "SELECT * FROM `sys_event_tracker` WHERE `session`='".$session."' ORDER BY `created_date` DESC";
		$myDB->query($sql);
		while($myDB->nextRecord()){
			array_push($output, $myDB->getRecord());
		}
		return $output;
	}
	
	function getTrackerTemplate($class = 'sidebar-navigation'){
		$template = "<div class='flat-content-header' style='margin-left: -4px;'>RECENTLY VISITED</div>";
		$data = getTrackerData();
		if(!empty($data)){
			$template .= "<ul class='".$class."' style='margin-top: 8px;'>";
			foreach($data AS $key => $value){
				$template .= "<li><a href='".urldecode($value['url'])."'>".ucfirst($value['title'])."<a/></li>";
			}
			$template .= "</ul><br>";
		}
		return $template;
	}
	/** Tracker - End **/
	
	/** Template - Start **/
	function getModuleURL($moduleUID){
		global $myDB;
		$output = "";
		$sql = "SELECT `seo_url` FROM `sys_seo` WHERE `module_uid`='".$moduleUID."'";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$result = $myDB->getRecord();
			$output = HTTP_SERVER.HTTP_ROOT.$result['seo_url'];
		}
		return $output;
	}
	
	function getBreadCrumbData($moduleUID, $limiter){
		global $myDB;
		$output = array();
		$sql = "SELECT `sys_module`.`module_display`, `sys_module`.`parent_uid`, `sys_seo`.`seo_url` FROM `sys_module`, `sys_seo` WHERE `sys_module`.`uid`='".$moduleUID."' AND `sys_seo`.`module_uid`=`sys_module`.`uid`";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$output = $myDB->getRecord();
			$output['outer'] = getBreadCrumbData($output['parent_uid'], $limiter);
			if(!empty($output['outer'])){
				$output['title'] = $output['outer']['title']." ".$limiter." ".$output['module_display'];
			}else{
				$output['title'] = " ".$limiter." ".$output['module_display'];
			}
		}
		return $output;
	}
	
	function getBreadCrumbTemplate($data, $id = 'breadcrumb', $class = 'crumbs'){
		$template = "<div id='".$id."'>";
			$contentData = getBreadCrumbContentTemplate($data, 0);
			$template .= "<div style='overflow: hidden;'><ul class='".$class."'>".$contentData['template']."</ul></div>";
			$template .= "<div style='clear: both; height: 2px;'></div>";
		$template .= "</div>";
		return $template;
	}
	
	function getBreadCrumbContentTemplate($data, $iterator){
		$output = array();
		$output['template'] = "";
		$output['index'] = 20;
		if(isset($data['outer']) && !empty($data['outer'])){
			$output = getBreadCrumbContentTemplate($data['outer'], $iterator+1);
		}
		$template = "<li ";
		if($output['index'] == 20){$template .= "class='first' ";}
		if($iterator == 0){
			$template .= "><a class='onhover' style='z-index:".$output['index'].";'>".strtoupper($data['module_display'])."</a></li>";
		}else{
			$template .= "><a href='".HTTP_SERVER.HTTP_ROOT.$data['seo_url']."' style='z-index:".$output['index'].";'>".strtoupper($data['module_display'])."</a></li>";
		}
		$output['template'] = $output['template'].$template;
		$output['index']--;
		return $output;
	}
	
	function onReadyMessage($message, $error, $warning){
		$template = "";
		$content = "";
		if(!empty($error) && isset($error['content']) && $error['content'] != ''){
			$content .= json_encode($error);
		}
		if(!empty($warning) && isset($warning['content']) && $warning['content'] != ''){
			$content .= json_encode($warning);
		}
		if(!empty($message) && isset($message['content']) && $message['content'] != ''){
			$content .= json_encode($message);
		}
		if($content != ""){
			$template .= "$('#oz-noty').oznoty([".$content."]);";
		}
		return $template;
	}
	
	function onReadyMarkError($markError){
		$template = "";
		foreach($markError AS $value){
			if($template != ""){$template .= ", ";}
			$template .= "'".$value."'";
		}
		return $template;
	}
	
	function onReadyMessageCustom($message, $error, $warning){
		$template = "";
		$content = "";
		if(!empty($error) && isset($error['content']) && $error['content'] != ''){
			$error['text'] = $error['content'];
			$content .= json_encode($error);
		}
		if(!empty($warning) && isset($warning['content']) && $warning['content'] != ''){
			$error['text'] = $error['content'];
			$content .= json_encode($warning);
		}
		if(!empty($message) && isset($message['content']) && $message['content'] != ''){
			$error['text'] = $error['content'];
			$content .= json_encode($message);
		}
		if($content != ""){
			//$template .= "$('#oz-noty').oznoty([".$content."]);";
			$template = "noty(".$content.");";
		}
		return $template;
	}
	
	
        function loadBanner($type){
		global $myDB;
		$path = HTTP_MEDIA."/site-image/banner/";
                
                $indicatorTemplate = '';
                $innerTemplate = '';
                
		$template = "";
		$bannerData = array();
		$sql = "SELECT * FROM `banner` Where `type` = '".$type."' AND `status` ='1' ORDER BY `order` ASC";
		$myDB->query($sql);
		while($myDB->nextRecord()){
			array_push($bannerData, $myDB->getRecord());
		}		
		if(count($bannerData)>1)
                    
                    $indicatorTemplate = '<ol class="carousel-indicators">';
                    $innerTemplate = '<div class="carousel-inner" role="listbox" style="height:100%;">';
                    
		$el = 0;
		foreach($bannerData AS $key => $value){
                    
                        $indicatorTemplate .= '<li data-target="#login-pic" data-slide-to="'.$el.'" class="'.($el == 0? "active":"").'"></li>';
                        
                        $innerTemplate .= '<div class="item '.($el == 0? "active":"").'"><img class="img-responsive" src="'.$path.$value['path'].'" alt="'.$value['caption'].'"></div>';
                        
			//$capId = 'caption-'.$el;
			if($value['link'] != ''){$imgTemp .= '<a href="'.$value['link'].'" target="_blank">';}
			
			//if($value['effect'] != ''){$imgTemp .= 'data-transition="'.$value['effect'].'" ';}
//			if($value['caption'] != ''){$imgTemp .= 'title="#'.$capId.'" ';}
//			$imgTemp .= ' alt="" style="display: none;"/>';
//			if($value['link'] != ''){$imgTemp .= '</a>';}
//			if($value['caption'] != ''){
//				$capTemp .= '<div id="'.$capId.'" class="nivo-html-caption">'.$value['caption'].'</div>';
//			}				
			$el++;
		}
                $indicatorTemplate .= '</ol>';
		$innerTemplate .= '</div>';
		$template = $indicatorTemplate.$innerTemplate;	
		return $template;
	}
			
	function checkBannerExist($type){
		global $myDB;
		$bannerData = array();
		$sql = "SELECT * FROM `banner` Where `type` = '".$type."' AND `status` = '1' ORDER BY `order` ASC";
		$myDB->query($sql);
		while($myDB->nextRecord()){
			array_push($bannerData, $myDB->getRecord());
		}	
		return $bannerData;
	}
	/*
	function loadHeaderTemplate($id = '', $class = ''){
		$template = "";
		$condition = " AND `sys_module`.`status`='1' AND `sys_module`.`header`='1'";
		if($GLOBALS["siteSetting"]["emas"] == "0") { //(!(defined("ENABLED_EMAS_SYNC_EXPORT") && constant("ENABLED_EMAS_SYNC_EXPORT")=="YES"))
			$condition .= " AND `sys_module`.`uid`!='oz.system.settings.emas_sync'";
		}
		
		if($GLOBALS["siteSetting"]["ubs"] == "0") { //(!(defined("ENABLED_EMAS_SYNC_EXPORT") && constant("ENABLED_EMAS_SYNC_EXPORT")=="YES"))
			$condition .= " AND `sys_module`.`uid`!='oz.system.settings.ubs_sync'";
		}

		if(isset($_SESSION['login']['mode'])){
			if($_SESSION['login']['mode'] != 'both'){
				$condition .= " AND `sys_module`.`secure_mode`='".$_SESSION['login']['mode']."'";
			}
		}else{
			$condition .= " AND `sys_module`.`secure_mode`='none'";
		}
		$header = loadHeaderItem('', $condition);
		$template .= loadHeaderItemTemplate($header, $id, $class);
		return $template;
	}
	*/
	function loadHeaderItem($parentUID, $condition){
		global $myDB;
		$output = array();
		$sql = "SELECT `sys_module`.`uid`, `sys_module`.`module_display`, `sys_module`.`display_link`, `sys_seo`.`seo_url` FROM `sys_module` LEFT JOIN `sys_seo` ON `sys_module`.`uid`=`sys_seo`.`module_uid` WHERE `sys_module`.`parent_uid`='".$parentUID."' ".$condition." ORDER BY `sys_module`.`item_order` ASC";
		$myDB->query($sql);
		while($myDB->nextRecord()){
			array_push($output, $myDB->getRecord());
		}
		foreach($output AS $key => $value){
			$output[$key]['child'] = loadHeaderItem($value['uid'], $condition, 0);
		}
		return $output;
	}
	
	function loadHeaderItemTemplate($parent, $id, $class, $root = 0){
		$root++;
		$template = "<ul ";
		if($id != ''){
			$template .= "id='".$id."' ";
		}
		if($class != ''){
			$template .= "class='".$class."' ";
		}
		$template .= ">";
		foreach($parent AS $key => $value){
			if(checkAccess($value['uid'])){
				$template .= "<li>";
					$template .= "<a ";
						if($value['display_link'] == '1'){$template .= "href='".HTTP_SERVER.HTTP_ROOT.$value['seo_url']."'";}
					$template .= ">";
					if($root <= 2){
						$template .= strtoupper($value['module_display']);
					}else{
						$template .= ucfirst($value['module_display']);
					}
					$template .= "</a>";
					if(!empty($value['child'])){
						$template .= loadHeaderItemTemplate($value['child'], '', '', $root);
					}
				$template .= "</li>";
			}
		}
		$template .= "</ul>";
		return $template;
	}
        
    function loadHeaderTemplate($id = '', $class = ''){
		$template = "";
		$condition = " AND `sys_module`.`status`='1' AND `sys_module`.`header`='1'";
	
		if(isset($_SESSION['login']['mode'])){
			if($_SESSION['login']['mode'] != 'both'){
				$condition .= " AND `sys_module`.`secure_mode`='".$_SESSION['login']['mode']."'";
			}
		}else{
			$condition .= " AND `sys_module`.`secure_mode`='none'";
		}
		$header = loadHeaderItemBS('', $condition);
		$template .= loadHeaderItemTemplateBS($header, $id, $class);
		return $template;
	}
        
        function loadSpecificHeaderTemplate($name, $id = '', $class = ''){
                $arrSpecific = array("item_management", "transactions", "touchsales", "reports", "oz.system");
                global $myDB;
		$template = "";
		$condition = " AND `sys_module`.`status`='1' AND `sys_module`.`header`='1'";
		
		if(isset($_SESSION['login']['mode'])){
			if($_SESSION['login']['mode'] != 'both'){
				$condition .= " AND `sys_module`.`secure_mode`='".$_SESSION['login']['mode']."'";
			}
		}else{
			$condition .= " AND `sys_module`.`secure_mode`='none'";
		}
                
                
                $mainHeaderContent = array();
                $headerCount = 0;
                
                foreach($arrSpecific AS $key => $value) {
                    $header = array();
                    $sql = "SELECT `sys_module`.`uid`, `sys_module`.`module_display`, `sys_module`.`display_link`, `sys_seo`.`seo_url` FROM `sys_module` LEFT JOIN `sys_seo` ON `sys_module`.`uid`=`sys_seo`.`module_uid` WHERE `sys_module`.`uid`='".$value."' ".$condition." ORDER BY `sys_module`.`item_order` ASC";
                    $myDB->query($sql);
                    while($myDB->nextRecord()){
                            array_push($mainHeaderContent, $myDB->getRecord());
                    }
                    $header = loadHeaderItemBS($value, $condition);
                    $mainHeaderContent[$headerCount]['child'] = $header;
                    $headerCount++;
                }
                
                $arrMore = array();
                $arrItem = array();
                $arrItem['uid'] = 'custom.specific';
                $arrItem['module_display'] = $name;
                $arrItem['display_link'] = '0';
                $arrItem['child'] = $mainHeaderContent;
                array_push($arrMore, $arrItem);
                
		$template .= loadHeaderItemTemplateBS($arrMore, $id, 'moreNav', 0, false, true);
		return $template;
	}
        
        function loadHeaderItemBS($parentUID, $condition){
		global $myDB;
		$output = array();
		$sql = "SELECT `sys_module`.`uid`, `sys_module`.`module_display`, `sys_module`.`display_link`, `sys_seo`.`seo_url` FROM `sys_module` LEFT JOIN `sys_seo` ON `sys_module`.`uid`=`sys_seo`.`module_uid` WHERE `sys_module`.`parent_uid`='".$parentUID."' ".$condition." ORDER BY `sys_module`.`item_order` ASC";
		$myDB->query($sql);
		while($myDB->nextRecord()){
			array_push($output, $myDB->getRecord());
		}
		foreach($output AS $key => $value){
			$output[$key]['child'] = loadHeaderItemBS($value['uid'], $condition, 0);
		}
		return $output;
	}
	
	function loadHeaderItemTemplateBS($parent, $id, $class, $root = 0, $mega = false, $individual = false){
        $root++;
        $template = '';
        $href = '';
        foreach($parent AS $key => $value){
            if(checkAccess($value['uid'])){
                if ($individual) {
                    if($mega) {
                        $template .= generateMegaMenu($value, $root, $mega, $individual, $class);
                    }else {
                        $template .= generateSubMenu($value, $root, $mega, $individual, $class);
                    }
                }else {
                    if($value['uid'] != "oz.system" &&$value['uid'] != "oz.system.settings" && $value['uid'] != "oz.system.logs" && $value['uid'] != "oz.system.user_management") {
                            $template .= generateSubMenu($value, $root);
                    }else {
                            $template .= generateMegaMenu($value, $root);
                    }
                }
            }
        }
        return $template;
	}
        
	function generateSubMenu($value, $root, $mega = false, $individual = false, $class = '') {
        $arrMediumNav = array("touchsales", "touchsales", "reports");
        $arrSmallNav = array("transactions");
        $arrTinyNav = array("item_management");
        
        if($value['display_link'] == '1'){$href = HTTP_SERVER.HTTP_ROOT.$value['seo_url'];}else{$href = "";}
        $template = "";
        if($root == 1){			
            if(!empty($value['child'])){
                if(in_array($value['uid'], $arrMediumNav)) {
                    $template .= '<li class="dropdown mediumNav">';
                }else if(in_array($value['uid'], $arrSmallNav)) {
                    $template .= '<li class="dropdown smallNav">';
                }else if(in_array($value['uid'], $arrTinyNav)) {
                    $template .= '<li class="dropdown tinyNav">';
                }else {
                    $template .= '<li class="dropdown '.$class.'">';
                }
                $template .= '<a class="mainMod" data-submenu="" href="'.$href.'" data-toggle="dropdown" tabindex="0" aria-expanded="false">';
                $template .= strtoupper($value['module_display']);
                $template .= '</a>';
                $template .= '<ul class="dropdown-menu">';
                $template .= loadHeaderItemTemplateBS($value['child'], '', '', $root, $mega, $individual);
                $template .=   '</ul>';
                $template .=   '</li>';
            }else {
                $template .= '<li><a href="'.$href.'" tabindex="0">';
                $template .= strtoupper($value['module_display']);
                $template .= '</a></li>';
            }
        }else {
            if(!empty($value['child'])){
                if(in_array($value['uid'], $arrSmallNav)) {
                    $template .= '<li class="dropdown-submenu smallNavMore">';
                }else if(in_array($value['uid'], $arrTinyNav)) {
                    $template .= '<li class="dropdown-submenu tinyNavMore">';
                }else {
                    $template .= '<li class="dropdown-submenu">';
                }
                $template .= '<a tabindex="0" style="cursor:pointer">';
                $template .= strtoupper($value['module_display']);
                $template .= '</a>';
                $template .= '<ul class="dropdown-menu sub">';
                $template .= loadHeaderItemTemplateBS($value['child'], '', '', $root, $mega, $individual);
                $template .= '</ul>';
                $template .= '</li>';
            }else {
                $template .= '<li><a href="'.$href.'" tabindex="0">';
                $template .= strtoupper($value['module_display']);
                $template .= '</a></li>';
            }
        }
        return $template;
	}
        
        function generateMegaMenu($value, $root, $mega = false, $individual = false) {
            $arrMediumNav = array("oz.system");
            $arrSmallNav = array();
            
            if($value['display_link'] == '1'){$href = HTTP_SERVER.HTTP_ROOT.$value['seo_url'];}else{$href = "";}
            $template = "";
            if($root == 1){
                if(in_array($value['uid'], $arrMediumNav)) {
                    $template .= '<li class="dropdown yamm-aw mediumNav">';
                }else {
                    $template .= '<li class="dropdown yamm-aw">';
                }
                $template .= ' <a class="mainMod" href="'.$href.'" data-toggle="dropdown" class="dropdown-toggle">';
                $template .= strtoupper($value['module_display']);
                $template .= '</a>';
                $template .= '<ul class="dropdown-menu yamm-aw">';
                $template .= '<li>';
                $template .= '<div class="yamm-content yamm-aw">';
                $template .= '<div class="row">';
                $template .= loadHeaderItemTemplateBS($value['child'], '', '', $root, $mega, $individual);
                $template .= '</div></div></li></ul>';
                $template .= '</li>';
            }else if($root == 2){
                $template .= ' <ul class="col-sm-4 list-unstyled">';
                $template .= '<li class="megaheader"><p>';
                $template .= strtoupper($value['module_display']);
                $template .= '</p></li>';
                $template .= '<li class="divider"></li>';
                $template .= loadHeaderItemTemplateBS($value['child'], '', '', $root, $mega, $individual);
                $template .= '</ul>';
            }else{
                $template .=  '<li><a href="'.$href.'">';
                $template .= ucfirst($value['module_display']);
                $template .=  '</a></li>';
            }
            return $template;
        }
	
	function loadTileTemplate($moduleUID){
		global $myDB;
		$template  = '<!--[if lt IE 9]><script src="'.HTTP_CDN_PLUGIN.'/jQuery/thirdparty/excanvas/excanvas.min.js" type="text/javascript"></script><![endif]-->';
		$template .= "<script type='text/javascript' src='".HTTP_CDN_PLUGIN."/jQuery/thirdparty/flippy-master/jquery.flippy.min.js'></script>";
		$template .= "<div class='tile-bed aa-bs' style='margin-left: -5px;margin-right: -5px;'>";
			$condition = " AND `sys_module`.`status`='1' AND `sys_module`.`display_tile`='1' AND `sys_seo`.`module_uid`=`sys_module`.`uid` AND `sys_module`.`uid`!='oz.message'";
			if(isset($_SESSION['login']['mode'])){
				if($_SESSION['login']['mode'] != 'both'){
					$condition .= " AND `sys_module`.`secure_mode`='".$_SESSION['login']['mode']."'";
				}
			}else{
				$condition .= " AND `sys_module`.`secure_mode`='none'";
			}
			$content = array();
			$sql = "SELECT `sys_module`.`uid`, `sys_module`.`tile_pics`, `sys_module`.`module_display`, `sys_module`.`tooltip_text`, `sys_seo`.`seo_url` FROM `sys_module`, `sys_seo` WHERE `sys_module`.`parent_uid`='".$moduleUID."' ".$condition." ";
			
			$sql .= " ORDER BY `sys_module`.`item_order` ASC";
			
			$myDB->query($sql);
			while($myDB->nextRecord()){
				array_push($content, $myDB->getRecord());
			}
			if($moduleUID != "" && HTTP_ACTIVE_PARENT != ''){
				$template .= "<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12 small-padding'><div class='tile-container' onclick='javascript: flipMe(this, \"".HTTP_ACTIVE_PARENT."\");'>";
					$tilePics = '/folder.png';
					if(file_exists(DIR_MEDIA.'/site-image/undo.png')){$tilePics = '/undo.png';}
					$template .= "<div class='tilepic'><img class='mainimg' src='".HTTP_MEDIA.'/site-image'.$tilePics."'>";
					$moduleParent = "Dashboard";
					if(defined('MODULE_PARENT')){$moduleParent = MODULE_PARENT;}
					$template .= "<div class='tile-title'>BACK TO ".strtoupper($moduleParent)."</div>";
//                                        $template .= "<img class='tooltipbutton' src='".HTTP_MEDIA."/site-image/info.png' >";
                                        $template .= "</div>";
//                                        $template .= "<div class='tiletooltip'><span>wo</span><img class='tooltipclose' src='".HTTP_MEDIA."/site-image/down.png' ></div>";
				$template .= "</div></div>";
			}
                        
                        foreach($content AS $key => $value){
				if(checkAccess($value['uid'])){
					$template .= "<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12 small-padding'><div class='tile-container' onclick='javascript: flipMe(this, \"".HTTP_SERVER.HTTP_ROOT.$value['seo_url']."\");'>";
						$tilePics = '/folder.png';
						if($value['tile_pics'] !='' && file_exists(DIR_MEDIA.'/site-image'.$value['tile_pics'])){$tilePics = $value['tile_pics'];}
                                                $tootTipText = $value['tooltip_text'];
						$template .= "<div class='tilepic'><img class='mainimg' src='".HTTP_MEDIA.'/site-image'.$tilePics."'>";
						$template .= "<div class='tile-title'>".strtoupper($value['module_display'])."</div>";
                                                if($tootTipText != "") {
                                                $template .= "<img class='tooltipbutton' src='".HTTP_MEDIA."/site-image/info.png' >";
                                                }
                                                $template .= "</div>";
                                                if($tootTipText != "") {
                                                $template .= "<div class='tiletooltip'><div class='tooltiptext'>".$tootTipText."</div><img class='tooltipclose' src='".HTTP_MEDIA."/site-image/down.png' ></div>";
                                                }
                                                $template .= "</div></div>";
				}
			}
			$template .= "<div style='clear: both;'></div>";
		$template .= "</div>";
		return $template;
	}
	
	function loadSideBarNavigation($moduleUID, $class = 'sidebar-navigation'){
		global $myDB;
		$template = "";
		if($moduleUID != ""){
			$template .= "<div class='flat-content-header' style='margin-left: -4px;'>".strtoupper(MODULE_PARENT)."</div>";
			$template .= "<ul class='".$class."' style='margin-top: 8px;'>";
				$condition = " AND `sys_module`.`status`='1' AND `sys_seo`.`module_uid`=`sys_module`.`uid` AND `sys_module`.`sidebar`='1'";
				if(isset($_SESSION['login']['mode'])){
					if($_SESSION['login']['mode'] != 'both'){
						$condition .= " AND `sys_module`.`secure_mode`='".$_SESSION['login']['mode']."'";
					}
				}else{
					$condition .= " AND `sys_module`.`secure_mode`='none'";
				}
				$content = array();
				$sql = "SELECT `sys_module`.`uid`, `sys_module`.`tile_pics`, `sys_module`.`module_display`, `sys_seo`.`seo_url` FROM `sys_module`, `sys_seo` WHERE `sys_module`.`parent_uid`='".$moduleUID."' ".$condition." ORDER BY `sys_module`.`item_order` ASC";
				$myDB->query($sql);
				while($myDB->nextRecord()){
					array_push($content, $myDB->getRecord());
				}
				foreach($content AS $key => $value){
					if(checkAccess($value['uid'])){
						$template .= "<li><a href='".HTTP_SERVER.HTTP_ROOT.$value['seo_url']."'>".ucfirst($value['module_display'])."</a></li>";
					}
				}
			$template .= "</ul><br>";
		}
		return $template;
	}
	
	function getGridState($gridState, $selector, $table){
		global $myDB;
		$output = array();
		$condition = $_SESSION[$gridState];
		$sql = "SELECT `".$selector."` FROM `".$table."` WHERE 1=1 ".$condition;
		$myDB->query($sql);
		while($myDB->nextRecord()){
			$result = $myDB->getRecord();
			array_push($output, $result[$selector]);
		}
		return $output;
	}
	
	function getGridPreviousButton($gridState, $current, $gridMode){
		$template = "";
		if(!empty($gridState)){
			foreach($gridState AS $key => $value){
				if($current == $value && $key != 0){
					$template .= "<input type=\"button\" value=\"Previous\" class=\"flat-button-default\" onclick=\"javascript: window.location = '".getModuleURL($gridMode)."?key=".urlencode(encryption($gridState[$key-1], $_SESSION['salt'], true))."';\">";
					break;
				}
			}
		}
		return $template;
	}
	
	function getGridNextButton($gridState, $current, $gridMode){
		$template = "";
		if(!empty($gridState)){
			$endPoint = count($gridState)-1;
			foreach($gridState AS $key => $value){
				if($current == $value && $key != $endPoint){
					$template .= "<input type=\"button\" value=\"Next\" class=\"flat-button-default\" onclick=\"javascript: window.location = '".getModuleURL($gridMode)."?key=".urlencode(encryption($gridState[$key+1], $_SESSION['salt'], true))."';\">";
					break;
				}
			}
		}
		return $template;
	}
	
	function getGridSaveNextButton($gridState, $current){
		$template = "";
		if(!empty($gridState)){
			$endPoint = count($gridState)-1;
			foreach($gridState AS $key => $value){
				if($current == $value && $key != $endPoint){
					$template .= "<input type=\"button\" value=\"Save & Next\" class=\"flat-button-default\" onclick=\"javascript: submitForm('next');\">";
					break;
				}
			}
		}
		return $template;
	}
	
	function getGridNextItem($gridState, $current){
		$output = "";
		if(!empty($gridState)){
			$endPoint = count($gridState)-1;
			foreach($gridState AS $key => $value){
				if($current == $value && $key != $endPoint){
					$output = encryption($gridState[$key+1], $_SESSION['salt'], true);
					break;
				}
			}
		}
		return $output;
	}
	
	function getFilterSQL($filter, $exception = array()){
		$condition = "";
		foreach($filter AS $key => $value){
			switch($value['data']['type']){
				case 'string':
					if(in_array($value['field'], $exception)){break;}
					$condition .= " AND LOWER(".$value['field'].") LIKE '%".strtolower($value['data']['value'])."%' ";
				break;
				case 'boolean' : 
					if(in_array($value['field'], $exception)){break;}
					if($value['data']['value'] == ""){
						$value['data']['value'] = 0;
					}
					$condition .= " AND ".$value['field']." = ".($value['data']['value']);
				break;
				case 'numeric':
					if(in_array($value['field'], $exception)){break;}
					switch ($value['data']['comparison']){
						case 'ne' : $condition .= " AND ".$value['field']." != ".$value['data']['value']; break;
						case 'eq' : $condition .= " AND ".$value['field']." = ".$value['data']['value']; break;
						case 'lt' : $condition .= " AND ".$value['field']." < ".$value['data']['value']; break;
						case 'gt' : $condition .= " AND ".$value['field']." > ".$value['data']['value']; break;
					}
				break;
				case 'date': 
					if(in_array($value['field'], $exception)){break;}
					switch($value['data']['comparison']){
						case 'ne' : $condition .= " AND ".$value['field']." != '".date('Y-m-d',strtotime($value['data']['value']))."'"; break;  
						case 'eq' : $condition .= " AND ".$value['field']." = '".date('Y-m-d',strtotime($value['data']['value']))."'"; break;
						case 'lt' : $condition .= " AND ".$value['field']." < '".date('Y-m-d',strtotime($value['data']['value']))."'"; break;
						case 'gt' : $condition .= " AND ".$value['field']." > '".date('Y-m-d',strtotime($value['data']['value']))."'"; break;
					}
				break;
				case 'list':
					if(in_array($value['field'], $exception)){break;}
					$tempVal = explode(',', $value['data']['value']);
					$tempStr = '';
					foreach($tempVal AS $key => $value){
						if($tempStr != ""){
							$tempStr .= ", ";
						}
						$tempStr .= "'".$value."'";
					}
					$condition .= " AND ".$value['field']." IN (".$tempStr.") ";
				break;
			}
		}
		return $condition;
	}
	/** Template - End **/
	
	/** User - Start **/
	function getUserGroupByUserId($id){
		global $myDB;
		$output = array();
		$sql = "SELECT `sys_usergroups`.* FROM `sys_usergroups`, `sys_users_groups` WHERE `sys_users_groups`.`user_id`='".$id."' AND `sys_users_groups`.`group_id`=`sys_usergroups`.`id`";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$output = $myDB->getRecord();
		}
		return $output;
	}
	
	function getDefaultPicture($uid){
		$output = "";
		$path = DIR_MEDIA.'/user-image/'.$uid.'/profile.png';
		if(file_exists($path)){
			$output = HTTP_MEDIA.'/user-image/'.$uid.'/profile.png';
		}else{
			$output = HTTP_MEDIA.'/user-image/default-avatar.png';
		}
		return $output;
	}
	
	function getUserCreateModify(&$input){
		if(isset($input['created_by'])){
			$data = getUserSpecificField($input['created_by'], "`first_name`, `last_name`, `username`");
			if(!empty($data)){
				$input['created_by_format'] = $data['first_name'].' '.$data['last_name'].'('.$data['username'].')';
			}
		}
		
		if(isset($input['modified_by'])){
			if($input['created_by'] != $input['modified_by']){
				$data = getUserSpecificField($input['modified_by'], "`first_name`, `last_name`, `username`");
			}
			if(!empty($data)){
				$input['modified_by_format'] = $data['first_name'].' '.$data['last_name'].'('.$data['username'].')';
			}
		}
		
		if(isset($input['created_date']) && $input['created_date'] != "0000-00-00 00:00:00"){
			$date = new DateTime($input['created_date']);
			$input['created_date'] = $date->format('d/m/Y H:i:s');
		}else{
			$input['created_date'] = "";
		}
		
		if(isset($input['modified_date']) && $input['modified_date'] != "0000-00-00 00:00:00"){
			$date = new DateTime($input['modified_date']);
			$input['modified_date'] = $date->format('d/m/Y H:i:s');
		}else{
			$input['modified_date'] = "";
		}
	}
        
        function getUserClosed(&$input){
		if(isset($input['closed_by'])){
			$data = getUserSpecificField($input['closed_by'], "`first_name`, `last_name`, `username`");
			if(!empty($data)){
				$input['closed_by_format'] = $data['first_name'].' '.$data['last_name'].'('.$data['username'].')';
				
			}
		}
		
		if(isset($input['closed_date']) && $input['closed_date'] != "0000-00-00 00:00:00"){
			$date = new DateTime($input['closed_date']);
			$input['closed_date'] = $date->format('d/m/Y H:i:s');
		}else{
			$input['closed_date'] = "";
		}
	}
	
	function getUserUID($id){
		global $myDB;
		$output = "";
		$sql = "SELECT `uid` FROM `sys_users` WHERE `id`='".$id."'";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$result = $myDB->getRecord();
			$output = $result['uid'];
		}
		return $output;
	}
	
	function getUserSpecificField($id, $field){
		global $myDB;
		$output = array();
		$sql = "SELECT $field FROM `sys_users` WHERE `id`='$id'";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$output = $myDB->getRecord();
		}
		return $output;
	}	
	/** User - End **/
	
	/** Global Function - Start **/	
	function getVariousIdsByConditions($arrfield, $table, $strConditions = '') {
		global $myDB;
		$output = array();			
		$sql = "SELECT ".$arrfield." FROM ".$table." WHERE 1=1";
		if($strConditions!="") {
			$sql .= $strConditions;
		}
		$myDB->query($sql);
		while($myDB->nextRecord()){
			$result = $myDB->getRecord();
			array_push($output, $result[$arrfield]);
		}
		return $output;
	}
	
	function getstrVariousIdByConditions($field, $table, $strConditions = '') {
		global $myDB;
		$output = "";		
		$sql = "SELECT ".$field." FROM ".$table." WHERE 1=1";
		if($strConditions!="") {
			$sql .= $strConditions;
		}
		$myDB->query($sql);
		while($myDB->nextRecord()){
			$result = $myDB->getRecord();
			$output = $result[$field];
		}
		return $output;
	}
	
	function updateVariousByConditions($table, $field1, $strConditions, $field2, $fieldConditions) {
		global $myDB;
		$output = "";		
		$sql = "UPDATE `".$table."` SET `".$field1."` = '".$strConditions."'  WHERE `".$field2."` = '".$fieldConditions."'";		
		if($myDB->query($sql)){
			return true;
		}else{
			return false;
		}
	}
	
	function checkGlobalDataExist($data, $field, $table, $extra = '', $caseSensitive = false){
		global $myDB;
		$sql = "SELECT * FROM `".$table."` WHERE 1=1 AND ";
		if($caseSensitive == true){
			$sql .= "`".$field."`='".$data."' ";
		}else{
			//$sql .= "LOWER(`".$field."`)='".strtolower($data)."' ";
			$sql .= "LOWER(`".$field."`)='".mb_strtolower($data, 'UTF-8')."' ";
		}	
		if($extra != ''){
			$sql .= $extra;
		}
		$sql .= " LIMIT 1";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			return true;
		}else{
			return false;
		}
	}
	
	function compareAndUpdate($table, $field, $value, $data, $extra = ""){
		global $myDB;
		
		$dataOri = array();
		$dataUpdate = array();
		$sql = "SELECT * FROM `".$table."` WHERE `".$field."`='".$value."' ".$extra." LIMIT 1";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$dataOri = $myDB->getRecord();
		}
		if(isset($dataOri['modified_by'])){unset($dataOri['modified_by']);}
		if(isset($dataOri['modified_date'])){unset($dataOri['modified_date']);}
		
		$myDB->beginTrans();
		if(!$myDB->update($table, $data, "`".$field."`='".$value."' ".$extra)){
			$myDB->rollbackTrans();
			return false;
		}
		
		$sql = "SELECT * FROM `".$table."` WHERE `".$field."`='".$value."' ".$extra." LIMIT 1";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$dataUpdate = $myDB->getRecord();
		}
		if(isset($dataUpdate['modified_by'])){unset($dataUpdate['modified_by']);}
		if(isset($dataUpdate['modified_date'])){unset($dataUpdate['modified_date']);}
		
		if($dataOri == $dataUpdate){
			$myDB->rollbackTrans();
			$commit = false;
		}else{
		//	print_r($data);
		//	print_r($dataOri);
		//	print_r($dataUpdate);
		//	print_r(array_diff($dataOri, $dataUpdate));
			$myDB->commitTrans();
		//	exit;
			$commit = true;
		}
		
		return $commit;
	}
	
	function convertIntToMonth($int, $mode = 's'){
		if($mode == 's'){
			return date('M', mktime(0, 0, 0, $int, 10)); 
		}else{
			return date('F', mktime(0, 0, 0, $int, 10));
		}
	}
	
	function populateMonth(){
		$array = array();
		for($m = 1; $m <= 12; $m++){
			$temp = array();
			$temp['value'] = $m;
			$temp['s_label'] = date('M', mktime(0, 0, 0, $m, 10)); 
			$temp['l_label'] = date('F', mktime(0, 0, 0, $m, 10));
			array_push($array, $temp);
		}
		return $array;
	}
	
	function populateYearRange($year = "", $range = 5, $doBegin = true, $doEnd = true){
		if($year == ""){$year = date("Y");}
		$array = array();
		if($doBegin){
			$begin = $year - 5;
		}else{
			$begin = $year;
		}
		if($doEnd){
			$end = $year + 5;
		}else{
			$end = $year;
		}
		for($i = $begin; $i <= $end; $i++){
			$temp = array();
			$temp['value'] = $i;
			array_push($array, $temp);
		}
		return $array;
	}

	function regionChecking(){
		define('REGION_IP', strtolower(getISOcodeFromIP()));

		if(isset($_SESSION['login'])){
			global $myDB;
			$sql = "SELECT `region` FROM sys_users WHERE id='".$_SESSION['user_id']."'";
			$myDB->query($sql);
			if($myDB->nextRecord()){
				$result = $myDB->getRecord();
				define('REGION', $result['region']);
			}
		}else{
			define('REGION', REGION_IP);
		}
		/*
		$url = $_SERVER['REQUEST_URI'];
		//Remove root
		if(HTTP_ROOT != ""){
			$url = substr($url, strlen(HTTP_ROOT));
		}

		//Remove GET
		$getURL = "";
		$dirty = strpos($url, '?');
		if($dirty !== false){
			$getURL = substr($url, $dirty);
			$url = substr_replace($url, '', $dirty);
		}

		//Remove .php
		$dirty = strripos($url, '.php');
		if($dirty !== false){
			$subDirty = strrpos($url, '/');
			$url = substr_replace($url, '', $subDirty);
		}
		if(substr($url, -1) == "/"){
			$url =  substr($url, 0, -1);
		}

		$arrURL = explode("/", $url);
		if(empty($arrURL)){
			define('REGION_URL', REGION_IP);
		}else{
			$endURL = trim(strtolower(end($arrURL)));
			if(in_array($endURL, array("uk", "us", "ca"))){
				define('REGION_URL', $endURL);
			}else{
				define('REGION_URL', '');
			}
		}

		$region = "";
		if(REGION_IP == "row" && REGION_URL != ""){
			$region = REGION_URL;
		}else if(in_array(REGION_IP, array("uk", "us", "ca"))){
			$region = REGION_IP;
		}
		define('REGION', $region);
		define('REGION_GET', $getURL);*/
	}
	/** Global Function - End **/
	
	/** System Alerts - Start **/
	function getSystemFormAlert($table, $id = '', &$winReady){
		global $myDB;
		$template = '';
		$alert = array();
		$sql = "SELECT * FROM `sys_alerts` WHERE `table`='".$table."' AND (CURDATE() BETWEEN start_date AND end_date)";
		if($id != ''){
			$sql .= " AND (target ='".$id."' OR target LIKE '%,".$id.",%' OR target LIKE '%,".$id."' OR target LIKE '".$id.",%' OR target = 'all')";
		}
		$myDB->query($sql);
		while($myDB->nextRecord()){
			$result = $myDB->getRecord();
			array_push($alert, $result);
		}
		if(!empty($alert)){
			$winReady .= '$("#system-form-alert").fadeIn(2500); ';
				$template .= '<div id="system-form-alert" class="system-form-alert">';
					$template .= '<table class="system-form-alert-table">';
					foreach($alert AS $data){
						$template .= '<tr><td class="system-form-alert-logo"><img src="'.HTTP_MEDIA.'/site-image/alerts/'.$data['type'].'-white.png"></td>';
						$content = $data['content'];
						$content = str_replace('<p>&nbsp;</p>', '<br>', $content);
						$content = str_replace('<p>', '', $content);
						$content = str_replace('</p>', '', $content);
						$template .= '<td>'.$content.'</td>';
						$template .= '</tr>';
					}
					$template .= '</table>';
				$template .= '</div>';
		}
		return $template;
	}
	/** System Alerts - End **/
	
	function mysqlPrepValues($value) {
		if(get_magic_quotes_gpc()){ 
			//stripslashes
			return stripslashesArray($value);
		} else { 
			//addslashes
			return addslashesArray($value);
		}
	}
	function stripslashesArray($arr) {
		//check if the variable is an array
		if (is_array($arr)) {
			//get the keys of the array
			$keys = array_keys($arr);
			for ($x=0;$x<count($keys);$x++) {
				//check if array item is not an object
				if (!is_object($arr[$keys[$x]])) {
					//strip the slashes of the item, even if it's an array
					$arr[$keys[$x]] = stripslashesArray($arr[$keys[$x]]);
				}
			}
			return $arr; //return the stripped array
		} else {
			//variable is just a string, treat as normal
			$arr = htmlspecialchars_decode($arr, ENT_QUOTES);
			$arr = htmlspecialchars($arr, ENT_QUOTES, 'UTF-8');
			return stripslashes($arr); //return the stripped string
		}
	} //end stripslashesArray function
	function addslashesArray($arr) {
		//check if the variable is an array
		if (is_array($arr)) {
			//get the keys of the array
			$keys = array_keys($arr);
			for ($x=0;$x<count($keys);$x++) {
				//check if array item is not an object
				if (!is_object($arr[$keys[$x]])) {
					//add the slashes to the item, even if it's an array
					$arr[$keys[$x]] = addslashesArray($arr[$keys[$x]]);
				}
			}
			return $arr; //return the stripped array
		} else {
			//variable is just a string, treat as normal
			$arr = htmlspecialchars_decode($arr, ENT_QUOTES);
			$arr = htmlspecialchars($arr, ENT_QUOTES, 'UTF-8');
			return addslashes($arr); //return the stripped string
		}
	} //end addslashesArray function
	
	//Function to Convert stdClass Objects to Multidimensional Arrays
	function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
	//Function to Convert Multidimensional Arrays to stdClass Objects
	function arrayToObject($d) {
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(__FUNCTION__, $d);
		}
		else {
			// Return object
			return $d;
		}
	}
        
        function readMyMoney($num){
		$text = "";
		$sepDecimal = explode(".", $num);
		$sepComma = explode(",", $sepDecimal[0]);
		
		$totalComma = count($sepComma);
		$position = $totalComma;
		$flag = 0;
		while($flag < $totalComma){
			if($sepComma[$flag] > 0){
				switch($position){
					case '1': $text .= convertNum2Words($sepComma[$flag]); break;
					case '2': $text .= convertNum2Words($sepComma[$flag]).' thousand '; break;
					case '3': $text .= convertNum2Words($sepComma[$flag]).' million '; break;
					case '4': $text .= convertNum2Words($sepComma[$flag]).' billion '; break;
					case '5': $text .= convertNum2Words($sepComma[$flag]).' trillion '; break;
					case '6': $text .= convertNum2Words($sepComma[$flag]).' quadrillion '; break;
					case '7': $text .= convertNum2Words($sepComma[$flag]).' quintrillion '; break;
					case '8': $text .= convertNum2Words($sepComma[$flag]).' sextrillion '; break;
					case '9': $text .= convertNum2Words($sepComma[$flag]).' septrillion '; break;
				}
			}
			$flag ++;
			$position --;
		}
		
		if(isset($sepDecimal[1])){
			$temp = convertNum2Words($sepDecimal[1]);
			if($temp != ""){
				$text .= ' and '.$temp. ' only';
			}
		}
		return $text;
	}
	
	function convertNum2Words($num){
		$num = (int)$num;
		$length = strlen($num);
		$text = "";
		if($length == 1){
			$text .= convertSingleNum2Words($num);
		}else if($length == 2){
			$text .= convertDoubleNum2Words($num);
		}else if($length == 3){
			$text .= convertTripleNum2Words($num);
		}
		return $text;
	}
	
	function convertTripleNum2Words($num){
		$num1 = substr($num, 0, 1);
		$num2 = substr($num, -2);
		return convertSingleNum2Words($num1). ' hundred '.convertDoubleNum2Words($num2);
	}
	
	function convertDoubleNum2Words($num){
		$num1 = substr($num, 0, 1);
		$num2 = substr($num, -1);
		switch($num1){
			case '1': 
				switch($num2){
					case '0': return 'ten';
					case '1': return 'eleven';
					case '2': return 'twelve';
					case '3': return 'thirteen';
					case '4': return 'fourteen';
					case '5': return 'fifteen';
					case '6': return 'sixteen';
					case '7': return 'seventeen';
					case '8': return 'eighteen';
					case '9': return 'nineteen';
				}
			break;
			case '2': return "twenty ".convertSingleNum2Words($num2);
			case '3': return "thirty ".convertSingleNum2Words($num2);
			case '4': return "forty ".convertSingleNum2Words($num2);
			case '5': return "fifty ".convertSingleNum2Words($num2);
			case '6': return "sixty ".convertSingleNum2Words($num2);
			case '7': return "seventy ".convertSingleNum2Words($num2);
			case '8': return "eighty ".convertSingleNum2Words($num2);
			case '9': return "ninety ".convertSingleNum2Words($num2);
			case '0': return convertSingleNum2Words($num2);
		}
	}
	
	function convertSingleNum2Words($num){
		switch($num){
			case '1': return 'one';
			case '2': return 'two';
			case '3': return 'three';
			case '4': return 'four';
			case '5': return 'five';
			case '6': return 'six';
			case '7': return 'seven';
			case '8': return 'eight';
			case '9': return 'nine';
			case '0': return '';
		}
	}
	
	function getAllPendingImportation(){
		global $myDB;
		$output = array();
		$sql = "SELECT identifier FROM cron_importation_log WHERE status = '0' ";
		$myDB->query($sql);
		while($myDB->nextRecord()){
			$result = $myDB->getRecord();
			array_push($output, $result['identifier']);
		}
		return $output;
	}
	
	function updatePendingImportation($identifier){
		global $myDB;
		$sql = "UPDATE cron_importation_log SET status = '1', modified_date = '".date("Y-m-d H:i:s")."' WHERE identifier = '$identifier' ";
		$myDB->query($sql);
	}
	
	function getAutomationData(){
		global $myDB;
		$output = array();
		$sql = "SELECT * FROM automation WHERE id = '1' ";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$output = $myDB->getRecord();
		}
		return $output;
	}
	
	function updateAutomationData($data){
		global $myDB;
		if($myDB->update("automation", $data, "id='1'")){
			return true;
		}else{
			return false;
		}
	}

	function updateSettingData($data){
		global $myDB;
		if($myDB->update("sys_settings", $data, "id='1'")){
			return true;
		}else{
			return false;
		}
	}
	
	function getISOcodeFromIP(){		
		$output = '';
		$caught = false;

		// This creates the Reader object, which should be reused across lookups.
		$reader = new Reader(DIR_PLUGINS.'/ip_geolocation/GeoLite2-Country.mmdb');

		// Replace "city" with the appropriate method for your database, e.g., "country".
		try{
			$record = $reader->country($_SERVER['REMOTE_ADDR']);
		}catch (Exception $e){
			//default to US if address not found in database
			$caught = true;
			$output = 'ROW';
		}
		
		if(!$caught) {
			$output = $record->country->isoCode;
		}
		return $output;
	}

	/*** Cloud - Start ***/
	function getAppURL(){
		global $myDB;
		if($GLOBALS["siteSetting"]["dataconnector_mode"] == "cloud"){
			$apiURL = "http://console.touchsales.net/api/public/dataconnector";
			$url = "";
			$diff = date_diff(new DateTime(date("Y-m-d H:i:s")), new DateTime($GLOBALS["siteSetting"]["cache_app_url_date"]), true);
			if($GLOBALS["siteSetting"]["cache_app_url"] == "" || $GLOBALS["siteSetting"]["cache_app_url_date"] == "0000-00-00 00:00:00" || $diff->format("%h") >= 6 || $diff->format("%a") > 0){
				$try = true;
				$tryFailed = 0;

				$connectionConsole = curl_init();
				curl_setopt($connectionConsole, CURLOPT_URL, $apiURL);
				curl_setopt($connectionConsole, CURLOPT_VERBOSE, 1);
				curl_setopt($connectionConsole, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($connectionConsole, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($connectionConsole, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($connectionConsole, CURLOPT_POST, 1);
				curl_setopt($connectionConsole, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($connectionConsole, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($connectionConsole, CURLOPT_POSTREDIR, 3);
				$postData = array(
					"opt" => "get_api_address",
					"identifier" => $GLOBALS["siteSetting"]["update_identifier"],
					"update_key" => $GLOBALS["siteSetting"]["update_key"]
				);
				curl_setopt($connectionConsole, CURLOPT_POSTFIELDS, $postData);

				while($try){
					$httpResponse = curl_exec($connectionConsole);
					if($httpResponse){
						$response = json_decode($httpResponse, true);
						if(isset($response) && $response['success'] == '1' && $response['url'] != ""){
							$myDB->update("sys_settings", array("cache_app_url_date" => date("Y-m-d H:i:s"), "cache_app_url" => $response['url']), "id='1'");
							$url = $response['url'];
							$try = false;
						}
					}
					if($tryFailed >= 3){
						$try = false;
					}
					$tryFailed++;
				}
			}else{
				$url = $GLOBALS["siteSetting"]["cache_app_url"];
			}
		}else{
			$url = $GLOBALS["siteSetting"]["cache_app_url"];
		}
		return $url;
	}
	
	// Function to archieve entire folder into zip file
	function zipFolder(&$zip, $path, $folder = ""){
		if(is_dir($path)){
			if($folder != ""){
				$zip->addEmptyDir($folder);
				$folder .= "/";
			}
			
			$scan = scandir($path);
			foreach($scan AS $result){
				if($result != "." && $result != ".."){
					$newPath = $path."/".$result;
					if(is_dir($newPath)){
						zipFolder($zip, $newPath, $folder.$result);
					}else{
						$zip->addFile($newPath, $folder.$result);
					}
				}
			}
		}
	}

	function onReadyMessageVJOB($message, $message2, $error, $warning){
		$template = "";
		$content = "";
		if(!empty($message) && isset($message['content']) && $message['content'] != ''){
			$template .= "var n = noty({
                            text: '".$message['content']."',
                            type: 'information'
                        });";
		}
		if(!empty($message2) && isset($message2['content']) && $message2['content'] != ''){
			$template .= "var n = noty({
                            text: '".$message2['content']."',
                            type: 'information'
                        });";
		}
		if(!empty($error) && isset($error['content']) && $error['content'] != ''){
			$template .= "var n = noty({
                            text: '".$error['content']."',
                            type: 'error'
                        });";
		}
		if(!empty($warning) && isset($warning['content']) && $warning['content'] != ''){
			$template .= "var n = noty({
                            text: '".$warning['content']."',
                            type: 'warning'
                        });";
		}   
		return $template;
	}
	/*** Cloud - End ***/

	/*** Updater - Start ***/
	function listUpdateLogHTML(){
		$output = "";
		$today = date('Y-m-d').".txt";
		if($handle = opendir(DIR_ROOT.'/_updater/logs')){
			while(false !== ($entry = readdir($handle))){
				if($entry != "." && $entry != ".."){
					$output .= "<option value='".$entry."' ";
					if($entry == $today){
						$output .= "selected";
					}
					$output .= ">".$entry."</option>";
				}
			}
		}
		return $output;
	}

	function readUpdateLogHTML($file){
		$path = DIR_ROOT.'/_updater/logs/'.$file;
		if($file != '' && file_exists($path)){
			return file_get_contents($path);
		}else{
			return '';
		}
	}

	function doUpdateBackupDB(){
		$output = false;
		$backupFile = DIR_ROOT."/_updater/backup_db/db.sql";
		if(file_exists($backupFile)){
			unlink($backupFile);
		}
		ob_start();
		$return = system("cmd /c ".DIR_ROOT."/_updater/backup_db.bat");
		ob_clean();
		if($return != "" && file_exists($backupFile)){
			$zip = new ZipArchive();
			$zipFilename = DIR_ROOT."/_updater/backup_db/".date("YmdHis").".zip";
			$zip->open($zipFilename, ZipArchive::CREATE);
			$zip->addFile($backupFile, "db.sql");
			$zip->close();
			unlink($backupFile);
			chmod($zipFilename, 0777);
			appendUpdateLog("Database backup file create at ".str_replace("/", "\\", $zipFilename).' '.date('Y-m-d H:i:s').PHP_EOL);
			$output = true;
		}
		return $output;
	}

	function doUpdateBackupScripts(){
		$output = false;
		$backupDir = DIR_ROOT."/_updater/backup/temp";
		if(file_exists($backupDir)){
			rrmdir($backupDir);
		}
		mkdir($backupDir, 0777);
		xcopy(DIR_ROOT."/theme", $backupDir.'/theme', 0777);
		mkdir($backupDir.'/core', 0777);
		xcopy(DIR_ROOT."/core/module", $backupDir.'/core/module', 0777);
		xcopy(DIR_ROOT."/core/cron", $backupDir.'/core/cron', 0777);
		xcopy(DIR_ROOT."/core/framework", $backupDir.'/core/framework', 0777);
		
		$zip = new ZipArchive();
		$zipFilename = DIR_ROOT."/_updater/backup/".date("YmdHis").".zip";
		$zip->open($zipFilename, ZipArchive::CREATE);
		zipFolder($zip, $backupDir, "");
		$zip->close();
		rrmdir($backupDir);
		chmod($zipFilename, 0777);
		appendUpdateLog("Scripts backup file create at ".str_replace("/", "\\", $zipFilename).' '.date('Y-m-d H:i:s').PHP_EOL);
		$output = true;
		return $output;
	}

	function appendUpdateLog($text){
		$path = DIR_ROOT.'/_updater/logs/'.date('Y-m-d').'.txt';
		if(!file_exists($path)){
			$logFile = fopen($path, 'w');
			fclose($logFile);
			chmod($path, 0777);
		}
		$logFile = fopen($path, 'a+');
		$insert = $text.PHP_EOL;
		fwrite($logFile, $insert);
		fclose($logFile);
	}

	function updateSysUpdater($data){
		global $myDB;
		if($myDB->update("sys_updater", $data, "`id`='1'")){
			return true;
		}
	}

	function getSysUpdater(){
		global $myDB;
		$output = array();
		$sql = "SELECT * FROM `sys_updater` WHERE `id`='1'";
		$myDB->query($sql);
		if($myDB->nextRecord()){
			$output = $myDB->getRecord();
		}
		return $output;
	}

	function getNextUpdateVersion($updaterData){
		$output = array();

		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, $updaterData['updater_server']);
		curl_setopt($connection, CURLOPT_VERBOSE, 1);
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($connection, CURLOPT_POST, 1);
		curl_setopt($connection, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($connection, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($connection, CURLOPT_POSTREDIR, 3);
		curl_setopt($connection, CURLOPT_CONNECTTIMEOUT, 900);
		curl_setopt($connection, CURLOPT_TIMEOUT, 900);
		$postData = array(
			'opt' => "get_update_version",
			'project' => $updaterData['updater_option'],
			'version' => $updaterData['version']
		);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $postData);
		$httpResponse = curl_exec($connection);
		if($httpResponse){
			$response = json_decode($httpResponse, true);
			if(isset($response['list'])){
				$output = $response['list'];
				asort($output);
			}
		}
		return $output;
	}
	/*** Updater - End ***/
?>