<?php
	class SEO{
		var $db;
		var $cleanURL;
		var $moduleUID;
		var $moduleURL;
		var $parentUID;
		var $parentURL;
		var $getURL;
		var $actionURL = array();
		//var $seoURL = array();
		//var $isAjax = false;
		
		function SEO($db){
			$this->db = $db;
		}
		
		//Clean URL request
		function cleanURL($input){
			//Remove root
			if(HTTP_ROOT != ""){
				$input = substr($input, strlen(HTTP_ROOT));
			}
			
			//Remove GET
			$dirty = strpos($input, '?');
			if($dirty !== false){
				$this->getURL = substr($input, $dirty);
				$input = substr_replace($input, '', $dirty);
			}
			
			//Remove .php
			$dirty = strripos($input, '.php');
			if($dirty !== false){
				$subDirty = strrpos($input, '/');
				$input = substr_replace($input, '', $subDirty);
			}
			if(substr($input, -1) == "/"){
				$input =  substr($input, 0, -1);
			}
			return $input;
		}
		
		//Get action URL
		function getActionURL(){
			return $this->actionURL;
		}
		
		//Set action URL
		function setActionURL(){
			$sql = "SELECT `sys_seo`.`seo_url`, `sys_module`.`module_display` FROM `sys_seo`, `sys_module` WHERE `sys_seo`.`module_uid`='".$this->parentUID."' AND `sys_seo`.`module_uid`=`sys_module`.`uid`";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$resultData = $this->db->getRecord();
				$this->parentURL = $resultData['seo_url'];
				define('MODULE_PARENT', $resultData['module_display']);
			}
			$actionCatcher = str_replace($this->parentURL, "", $this->cleanURL);
			$actionCatcher = str_replace($this->moduleURL, "", $this->cleanURL);
			$actionCatcher = str_replace("/", " ", $actionCatcher);
			$this->actionURL = explode(' ', trim($actionCatcher));
		}
		
		//Set constant
		function setSEOConstant($seoData){
			$themeDir = DIR_THEME;
			$themeHTTP = HTTP_SERVER.HTTP_ROOT."/theme";
			if($seoData['secure_mode'] == 'bo'){
				$themeDir .= "/".$GLOBALS["siteSetting"]["theme_bo"]."/bo";
				$themeHTTP .= "/".$GLOBALS["siteSetting"]["theme_bo"]."/bo";
			}else{
				$themeDir .= "/".$GLOBALS["siteSetting"]["theme_fo"]."/fo";
				$themeHTTP .= "/".$GLOBALS["siteSetting"]["theme_fo"]."/fo";
			}
			define('DIR_ACTIVE_THEME', $themeDir);
			define('DIR_ACTIVE_PUBLIC_THEME', DIR_THEME."/default/none");
			define('HTTP_ACTIVE_THEME', $themeHTTP);
			define('HTTP_ACTIVE_PUBLIC_THEME', HTTP_SERVER.HTTP_ROOT."/theme/default/none");
			define('HTTP_ACTIVE_MODULE', HTTP_SERVER.HTTP_ROOT.$this->moduleURL);
			define('HTTP_ACTIVE_PARENT', HTTP_SERVER.HTTP_ROOT.$this->parentURL);
			define('MODULE_NAME', $seoData['module_display']);
			define('MODULE_UID', $seoData['module_uid']);
			define('MODULE_PARENT_UID', $seoData['parent_uid']);
		}
		
		//Macth SEO URL
		function execute(){
			$this->cleanURL = $this->cleanURL($_SERVER['REQUEST_URI']);
			$tempCleanURL = $this->cleanURL;
			$sql = "SELECT * FROM `sys_seo`, `sys_module` WHERE `sys_seo`.`module_uid`=`sys_module`.`uid` AND `sys_module`.`uid`='oz.login.bo'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$resultData = $this->db->getRecord();
				define('SITE_BO_LOGIN', $resultData['seo_url']);
			}
			$sql = "SELECT * FROM `sys_seo`, `sys_module` WHERE `sys_seo`.`module_uid`=`sys_module`.`uid` AND `sys_module`.`uid`='oz.login.fo'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$resultData = $this->db->getRecord();
				define('SITE_FO_LOGIN', $resultData['seo_url']);
			}

			if($tempCleanURL == ""){
				header("Location: https://www.ifesworld.org");
				exit;
			}
			
			$tempSEO = explode("/", $tempCleanURL);
			$seoData = array();
			$totalSEO = count($tempSEO);
			
			for($i = $totalSEO; $i >0; $i--){
				$sql = "SELECT * FROM `sys_seo`, `sys_module` WHERE `sys_seo`.`module_uid`=`sys_module`.`uid` AND `sys_seo`.`seo_url`='".$tempCleanURL."'";
				$this->db->query($sql);
				if($this->db->numRow() > 0){
					if($this->db->nextRecord()){
						$seoData = $this->db->getRecord();
						break;
					}
				}else{
					$tempCleanURL= substr($tempCleanURL, 0, strlen('/'.$tempSEO[$i-1])*-1);
				}
			}
			if(!empty($seoData) && $seoData['status'] == '1'){
				$this->moduleUID = $seoData['module_uid'];
				$this->moduleURL = $seoData['seo_url'];
				$this->parentUID = $seoData['parent_uid'];
				$this->setActionURL();
				$this->setSEOConstant($seoData);
				checkLogin($seoData, $this->cleanURL, $this->getURL);
			}else{
				loadModule('404');
			}
		}
	}
?>