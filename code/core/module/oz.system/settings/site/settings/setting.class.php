<?php
	class Setting{
		var $db;
		var $totalRow;
		
		function Setting($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
	
		function getSettingData(){
			$output = array();
			$sql = "SELECT * FROM `sys_settings` WHERE `id`='1'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function updateSetting($data){
			if($this->db->update("sys_settings", $data, "`id`='1'")){
				return true;
			}else{
				return false;
			}
		}
		
		function checkEMASStatus($dir){
			if(file_exists($GLOBALS["siteSetting"]["emas_dir"])){
				return '<div style="color:green;">*EMAS Directory detected</div>';
			}else{
				return '<div style="color:red;">*EMAS Directory does not exist</div>';
			}
		}
		
		function checkUBSStatus($dir){
			if(file_exists($GLOBALS["siteSetting"]["ubs_dir"])){
				return '<div style="color:green;">*UBS Directory detected</div>';
			}else{
				return '<div style="color:red;">*UBS Directory does not exist</div>';
			}
		}
	}
?>