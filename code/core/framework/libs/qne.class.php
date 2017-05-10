<?php
	class QNE{
		var $sqlsrv;
		var $error = '';
		var $status = false;
		var $isGST = false;
		
		function QNE(){
			//$this->sqlsrv = ADONewConnection('sqlsrv');
			if($GLOBALS["siteSetting"]["qne"] == "1"){
				try {
					$this->sqlsrv = new PDO("sqlsrv:Server=".$GLOBALS["siteSetting"]["qne_server"].";Database=".$GLOBALS["siteSetting"]["qne_database"], $GLOBALS["siteSetting"]["qne_username"], $GLOBALS["siteSetting"]["qne_password"]); 
					$this->sqlsrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$this->status = true;
				}catch(PDOException $e) {
					$this->status = false;
					$this->error = $e->getMessage();
				}
			}else{
				$this->status = false;
				$this->error = "QNE function is disabled.";
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
				return true;
			}catch(PDOException $e){return false;} //$e->getMessage();			
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
				return true; //testing
			}catch(PDOException $e){
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
				$select = $this->sqlsrv->query('SELECT COLUMN_NAME, DATA_TYPE FROM '.$GLOBALS["siteSetting"]["qne_database"].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table."'");
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
							//case 'uniqueidentifier': //disabled due to the need for consistent GUID
							//	$output[$column['COLUMN_NAME']] = $data[$column['COLUMN_NAME']];
							//break;
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
		
		function getSalesOrderIdByCode($soID){
			$output = "";
			$database = $GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"];
			$executeQuery = $this->sqlsrv->prepare("SELECT Id FROM ".$database.".SalesOrders WHERE SalesOrderCode = '".$soID."'");
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
				$output = $temp." DAYS";
			}
			return $output;
		}
		
		function formatTaxType($taxType){
			if($taxType == "STAX"){
				$output = "SR";
			}
			return $output;
		}
		
		function checkQNESalesAgentExist($salesAgent){
			$output = array();
			try{
				$select = $this->sqlsrv->query('SELECT COUNT(*) FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".SalesPersons WHERE StaffCode = '".$salesAgent."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			if($result == 0){
				return true;
			}else{
				return false;
			}
		}
		
		function checkQNELocationExist($location){
			$output = array();
			try{
				$select = $this->sqlsrv->query('SELECT COUNT(*) FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".Location WHERE Location = '".$location."'");
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
		
		function getDebtorIdByCode($debtorCode){
			$output = "";
			try{
				$select = $this->sqlsrv->query('SELECT Id FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".Debtors WHERE CompanyCode = '".$debtorCode."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			
			$output = $result;
			Return $output;
		}
		
		function getTermIdByCode($term){
			$output = "";
			try{
				$select = $this->sqlsrv->query('SELECT Id FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".Terms WHERE Term = '".$term."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			
			$output = $result;
			Return $output;
		}
		
		function getSalesPersonIdByCode($salesPersonId){
			$output = "";
			try{
				$select = $this->sqlsrv->query('SELECT Id FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".SalesPersons WHERE StaffCode = '".$salesPersonId."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			
			$output = $result;
			Return $output;
		}
		
		function getCurrencyIdByCode($currencyCode){
			$output = "";
			try{
				$select = $this->sqlsrv->query('SELECT Id FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".Currencies WHERE CurrencyCode = '".$currencyCode."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			
			$output = $result;
			Return $output;
		}
		
		function getUOMIdByCode($uomCode, $stockId){
			$output = "";
			try{
				$select = $this->sqlsrv->query('SELECT Id FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".UOMs WHERE UOMCode = '".$uomCode."' AND StockId = '".$stockId."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			
			$output = $result;
			Return $output;
		}
		
		function getStockIdByCode($itemCode){
			$output = "";
			try{
				$select = $this->sqlsrv->query('SELECT Id FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".Stocks WHERE StockCode = '".$itemCode."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			
			$output = $result;
			Return $output;
		}
		
		function getTaxIdByCode($taxCode){
			$output = "";
			try{
				$select = $this->sqlsrv->query('SELECT Id FROM '.$GLOBALS["siteSetting"]["qne_database"].".".$GLOBALS["siteSetting"]["qne_schema"].".TaxCodes WHERE TaxCode = '".$taxCode."'");
				$result = $select->fetchColumn();
			}catch(PDOException $e){}
			
			$output = $result;
			Return $output;
		}
		
	}
?>