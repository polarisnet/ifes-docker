<?php
	function listErrorLogHTML(){
		$output = "";
		if($handle = opendir(DIR_LOGS)){
			while(false !== ($entry = readdir($handle))){
				if($entry != "." && $entry != ".."){
					$output .= "<option value='".$entry."'>".$entry."</option>";
				}
			}
		}
		return $output;
	}

	function readErrorLogHTML($file){
		$path = DIR_LOGS.'/'.$file;
		if($file != '' && file_exists($path)){
			return file_get_contents($path);
		}else{
			return '';
		}
	}

	function errorHandler($errorNo, $errorString, $errorFile, $errorLine, $errorContext){
		$output = "";

		switch($errorNo){
			case 'E_USER_ERROR':
				$output .= '['.date('Y-m-d H:i:s').'][ERROR]['.$errorNo.']['.$errorString.']['.$errorFile.'][on line:'.$errorLine.']';
			break;
			case '2':
			case 'E_USER_WARNING':
				$output .= '['.date('Y-m-d H:i:s').'][WARNING]['.$errorNo.']['.$errorString.']['.$errorFile.'][on line:'.$errorLine.']';
			break;
			case '8':
			case 'E_USER_NOTICE':
				$output .= '['.date('Y-m-d H:i:s').'][NOTICE]['.$errorNo.']['.$errorString.']['.$errorFile.'][on line:'.$errorLine.']';
			break;
			default:
				$output .= '['.date('Y-m-d H:i:s').'][UNKNOWN]['.$errorNo.']['.$errorString.']['.$errorFile.'][on line:'.$errorLine.']';
			break;
		}
		appendErrorLog($output);
	}
	
	function appendErrorLog($text){
		$path = DIR_LOGS.'/'.date('Y-m-d').'.txt';
		if(!file_exists($path)){
			$logFile = fopen($path, 'w');
			fclose($logFile);
			chmod($path, 0777);
		}
		$logFile = fopen($path, 'a+');
		$insert = $text.PHP_EOL;
		fwrite($logFile, $insert);
		fclose($logFile);
		if(ERROR_DISPLAY){
			echo $text.'<br>';
		}
	}
	
	if(ERROR_HANDLER){
		set_error_handler("errorHandler");
	}
?>