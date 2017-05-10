<?php
	class AutoCount{
		var $sqlsrv;
		var $error = '';
		var $status = false;
		var $isGST = false;
		
		function AutoCount(){
			//$this->sqlsrv = ADONewConnection('sqlsrv');
			if($GLOBALS["siteSetting"]["autocount"] == "1"){
				try {
					$this->sqlsrv = new PDO("sqlsrv:Server=".$GLOBALS["siteSetting"]["autocount_server"].";Database=".$GLOBALS["siteSetting"]["autocount_database"], $GLOBALS["siteSetting"]["autocount_username"], $GLOBALS["siteSetting"]["autocount_password"]); 
					$this->sqlsrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$this->status = true;
				}catch(PDOException $e) {
					$this->status = false;
					$this->error = $e->getMessage();
				}
			}else{
				$this->status = false;
				$this->error = "AutoCount function is disabled.";
			}
		}
		
		function getError(){
			return $this->error;
		}
		
		function getStatus(){
			return $this->status;
		}
		
		function getGSTVersion(){
			return $this->isGST;
		}
		
		function getsqlsrvObject(){
			return $this->sqlsrv;
		}
		
		function executeQuery($query){
			try{
				$executeQuery = $this->sqlsrv->prepare($query);
				$executeQuery->Execute();
			}catch(PDOException $e){return false;}
		}
		
		function insertQuery($data, $table){
			$column = "";
			$value = "";

			foreach($data AS $label => $record){
				if($column != ''){$column .= ", ";}
				$column .= $label;
				if($value != ''){$value .= ", ";}
				$value .= $record;
			}
			$query = "INSERT INTO ".$table." (".$column.") VALUES (".$value.") ";
			try{
				$executeQuery = $this->sqlsrv->prepare($query);
				$executeQuery->execute();
				//return $executeQuery; //debug
				//return "QUERY EXECUTED!"; //debug
				return true; //testing
			}catch(PDOException $e){
				//return "YOU FAILED! ;; ".$e->getMessage();
				return false; //testing
			}
		}
		
		function selectQuery($query, $start = -1, $limit){
			$output = array();
			try{
				$executeQuery = $this->sqlsrv->prepare("SELECT * FROM ( ".$query." ) a WHERE a.row > :offset and a.row <= :limit ");
				$executeQuery->bindValue(':offset', $start ,PDO::PARAM_INT);
				$executeQuery->bindValue(':limit', $limit ,PDO::PARAM_INT);
				$executeQuery->execute();
				$output = $executeQuery->fetchAll();
			}catch(PDOException $e){return $e->getMessage();}
			return $output;
		}

		function getDBFStructure($table){
			$output = array();
			try{
				$select = $this->sqlsrv->query('SELECT COLUMN_NAME, DATA_TYPE FROM '.$GLOBALS["siteSetting"]["autocount_database"].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table."'");
				$output = $select->fetchAll(PDO::FETCH_ASSOC);
			}catch(PDOException $e){}
			return $output;
		}
		
		function formatDB0Structure($data, $structure){
			$output = array();
			foreach($structure AS $key => $column){
				foreach($column AS $cKey){
					if(isset($data[$column['COLUMN_NAME']])){
						switch($column['DATA_TYPE']){
							case 'nvarchar':
								$output[$column['COLUMN_NAME']] = "N'".$data[$column['COLUMN_NAME']]."'";
							break;
							case 'uniqueidentifier':
								$output[$column['COLUMN_NAME']] = $data[$column['COLUMN_NAME']];
							break;
							default:
								$output[$column['COLUMN_NAME']] = "'".$data[$column['COLUMN_NAME']]."'";
							break;
						}
					
					}
				}
			}
			return $output;
		}
		
		function getStrLength($data){
			$output = array();
			foreach($data AS $key => $value){
				$output[$key] = strlen($value);
			}
			return $output;
		}
		
		function getDocKeyfromID($soID){
			$output = "";
			$database = $GLOBALS["siteSetting"]["autocount_database"].".".$GLOBALS["siteSetting"]["autocount_schema"];
			$executeQuery = $this->sqlsrv->prepare("SELECT DocKey FROM ".$database.".SO WHERE DocNo = '".$soID."'");
			$executeQuery->execute();
			
			$output = $executeQuery->fetchColumn();
			return $output;
		}
		
		function formatTerms($terms){
			if($terms == "CASH"){
				$output = "C.O.D.";
			}
			$temp = preg_replace('/\D/', '', $terms);
			if(isset($temp) && !empty($temp)){
				$output = "Net ".$temp." Days";
			}
			return $output;
		}
		
		function formatTaxType($taxType){
			if($taxType == "STAX"){
				$output = "SR_S";
			}
			return $output;
		}
		
		function checkAutoCountSalesAgentExist($salesAgent){
			$output = array();
			try{
				$select = $this->sqlsrv->query('SELECT COUNT(*) FROM '.$GLOBALS["siteSetting"]["autocount_database"].".".$GLOBALS["siteSetting"]["autocount_schema"].".SalesAgent WHERE SalesAgent = '".$salesAgent."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			if($result == 0){
				return true;
			}else{
				return false;
			}
		}
		
		function checkAutoCountLocationExist($location){
			$output = array();
			try{
				$select = $this->sqlsrv->query('SELECT COUNT(*) FROM '.$GLOBALS["siteSetting"]["autocount_database"].".".$GLOBALS["siteSetting"]["autocount_schema"].".Location WHERE Location = '".$location."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			if($result == 0){
				return true;
			}else{
				return false;
			}
		}
		
		function formatEntry($value, $maxChar = 10, $padChar = "0"){
			$length = strlen($value);
			if($length > $maxChar){
				return str_pad(substr($value, 0, $maxChar), $maxChar, $padChar, STR_PAD_LEFT);
			}else{
				return str_pad(substr($value, 0, $length), $maxChar, $padChar, STR_PAD_LEFT);
			}
		}
		
		function getNextDockey(){
			$output = "";
			$table = $GLOBALS["siteSetting"]["autocount_database"].".".$GLOBALS["siteSetting"]["autocount_schema"];

			$select = $this->sqlsrv->query('SELECT COUNT(*) FROM '.$table.".SO WHERE Dockey = '100000000'");
			$result = $select->fetchColumn();
			if($result == 0){
				return 100000000;
			}else{
				$query1 = "SELECT max(DocKey) AS MaxKey FROM ".$table.".SO";
				$query2 = "SELECT max(DtlKey) AS MaxKey FROM ".$table.".SODTL";

				$select1 = $this->sqlsrv->query($query1);
				$maxKey1 = $select1->fetchColumn();
				
				$select2 = $this->sqlsrv->query($query2);
				$maxKey2 = $select2->fetchColumn();

				$output = max($maxKey1, $maxKey2);
				return $output+1; //testing
			}
		}
		

	}
?>