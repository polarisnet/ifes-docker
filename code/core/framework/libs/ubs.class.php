<?php
	require_once (DIR_PLUGINS.'/adodb5/adodb.inc.php');
	require_once (DIR_PLUGINS.'/adodb5/adodb-exceptions.inc.php');
	require_once (DIR_PLUGINS.'/adodb5/xbase.class.php');
	
	class UBS{
		var $vfp;
		var $error = '';
		var $status = false;
		var $isGST = false;
		
		function UBS(){
			$this->vfp = ADONewConnection('vfp');
			if($GLOBALS["siteSetting"]["ubs"] == "1" && file_exists($GLOBALS["siteSetting"]["ubs_dir"])){
				$conn = "Driver={Microsoft Visual FoxPro Driver}; SourceType=DBF; SourceDB=".$GLOBALS["siteSetting"]["ubs_dir"];
				try{
					$this->vfp->Connect($conn);
					$this->vfp->SetFetchMode(ADODB_FETCH_ASSOC);
					$this->status = true;
					$this->checkUBSGSTVersion();
				}catch(exception $e){
					$this->status = false;
					$this->error = $e;
				}
			}else{
				$this->status = false;
				$this->error = "UBS function is disabled.";
			}
		}
		
		function UBS2(){ //testing
			$this->vfp = ADONewConnection('vfp');
			if($GLOBALS["siteSetting"]["ubs"] == "1" && file_exists($GLOBALS["siteSetting"]["ubs_dir"])){
				$conn = "Driver={Microsoft Visual FoxPro Driver}; SourceType=DBF; SourceDB=".$GLOBALS["siteSetting"]["ubs_dir"];
				try{
					$this->vfp->Connect($conn);
					$this->vfp->SetFetchMode(ADODB_FETCH_ASSOC);
					$this->status = true;
					$this->checkUBSGSTVersion();
				}catch(exception $e){
					$this->status = false;
					$this->error = $e;
				}
			}else{
				$this->status = false;
				$this->error = "UBS function is disabled.";
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
		
		function getVFPObject(){
			return $this->vfp;
		}
		
		function checkUBSGSTVersion(){
			try{
				$xBase = new XBaseTable($GLOBALS["siteSetting"]["ubs_dir"]."\\arpso.dbf");
				$xBase->open();
				$column = array_map('strtolower', $xBase->columnNames);
				if(!empty($column) && in_array("note", $column)){
					$this->isGST = true;
				}
			}catch(exception $e){}
		}
		
		function executeQuery($query){
			try{
				$this->vfp->Execute($query);
				return true;
			}catch(exception $e){return false;}
		}
		
		function executeQueryRs($query){
			try{
				return $this->vfp->Execute($query);
			}catch(exception $e){return false;}
		}
		
		function ubsRemoveIlegalUTF($value){
			$value = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
				 '|[\x00-\x7F][\x80-\xBF]+'.
				 '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
				 '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
				 '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
				 '', $value);
			return $value;
		}
		
		function insertQuery($data, $table){
			$column = "";
			$value = "";

			foreach($data AS $label => $record){
				if($column != ''){$column .= ", ";}
				$column .= $label;
				if($value != ''){$value .= ", ";}
				$value .= mb_convert_encoding($record, $GLOBALS["siteSetting"]["ubs_encoding"], "UTF-8");
			}
			$query = "INSERT INTO ".$table." (".$column.") VALUES (".$value.") ";
			try{
				$this->vfp->Execute($query);
				return true;
			}catch(exception $e){
				return false; 
			}
		}
		
		function selectQuery($query, $start = -1, $limit){
			$output = array();
			try{
				$result = $this->vfp->SelectLimit($query, $limit, $start, false);
				$output = $result->fields;
			}catch(exception $e){}
			return $output;
		}
		
		function selectQueryLimit($query, $start = -1, $limit){
			$output = array();
			try{
				$result = $this->vfp->SelectLimit($query, $limit, $start, false);
				while (!$result->EOF){
					array_push($output, $result->fields);
					$result->MoveNext();
				}
				//$output = $result->fields;
			}catch(exception $e){}
			return $output;
		}
		
		function formatEntry($value, $maxChar = 10, $padChar = "0"){
			$length = strlen($value);
			if($length > $maxChar){
				return str_pad(substr($value, 0, $maxChar), $maxChar, $padChar, STR_PAD_LEFT);
			}else{
				return str_pad(substr($value, 0, $length), $maxChar, $padChar, STR_PAD_LEFT);
			}
		}
		
		function getDBFStructure($table){
			$output = array();
			try{
				$xBase = new XBaseTable($GLOBALS["siteSetting"]["ubs_dir"]."\\".$table.".dbf");
				$xBase->open();
				$output['column'] = array_map('strtolower', $xBase->columnNames);
				$output['type'] = $xBase->columnTypes;
			}catch(exception $e){}
			return $output;
		}
		
		function formatDBFDefaultStructure($data, $structure){
			$output = array();
			foreach($structure['column'] AS $key => $column){
				if(!isset($data[$column])){
					switch($structure['type'][$key]){
						case 'N':
						case 'L':
							$output [$column] = "0";
						break;
						case 'D':
							$output [$column] = "{//}";
						break;
						case 'T':
							$output [$column] = "{//}";
						break;
						default:
						case 'C':
							$output [$column] = "\"\"";
						break;
					}
				}else{
					switch($structure['type'][$key]){
						case 'D':
							$output[$column] = $this->formatFoxproDate($data[$column]);
						break;
						case 'N':
						case 'L':
							$output[$column] = $data[$column];
						break;
						default:
						case 'C':
							$output[$column] = escapeVFPSquareBracket($data[$column]);
						break;
						case 'T':
							$output[$column] = $data[$column];
						break;
					}
				}
			}
			return $output;
		}
		
		function formatFoxproDate($mySQLDate){
			if($mySQLDate == "0000-00-00 00:00:00" || $mySQLDate == ""){
				return "{//}";
			}else{
				return "{".date("m/d/Y", strtotime($mySQLDate))."}";
			}
		}
		
		function countProductQuantityUBS($itemCode, $unitMeasure, $stockDate, $wareHouseCode = array()){
			$output = 0;
			foreach($wareHouseCode AS $wareHouse){
				try{
					$query = "SELECT SUM(qty) AS 'total' FROM icbin WHERE (item_no = '".$itemCode."') ";
					if($wareHouse != ""){
						$query .= " AND (location = '".$wareHouse."') ";
					}
					$result = $this->vfp->Execute($query);
					$temp = $result->fields;
					if(!empty($temp)){
						$output += $temp['total'];
					}
				}catch(exception $e){}
				
				//echo $output; exit;
				
				try{
					$query = "SELECT ictran.type, ictran.qty, ictran.billno, ictran.u_measure, icitem.u_measure AS 'item_measure', icitem.unit1, icitem.unit2, icitem.unit3, icitem.unit4, icitem.unit5, icitem.factor1, icitem.factor2, icitem.factor3, icitem.factor4, icitem.factor5, ictran.qty1, ictran.u_measure1, icmast.ref, icmast.retr, ictran.toloc ";
					$query .= "FROM ictran, icitem, icmast ";
					$query .= "WHERE ictran.type=icmast.type AND ictran.entry=icmast.entry AND ictran.item_no=icitem.item_no AND icmast.void <> 'Y' AND icmast.retr <> 'Y' AND ictran.item_no='".$itemCode."' ";
					if($stockDate != ""){
						$query .= "AND ictran.date <= {^".$stockDate."} ";
					}
					if($wareHouse != ""){
						$query .= " AND ((ictran.location = '".$wareHouse."') OR (ictran.toloc = '".$wareHouse."'))";
					}
					$query .= " ORDER BY ictran.date";
					
					$result = $this->vfp->Execute($query);
					$transData = array();
					while(!$result->EOF){
						$temp = $result->fields;
						array_push($transData, $temp);
						$result->MoveNext();
					}
					
					$altMeasure = array('unit1', 'unit2', 'unit3', 'unit4', 'unit5');
					$altFactor = array('factor1', 'factor2', 'factor3', 'factor4', 'factor5');
					
					$stockIn = 0;
					$stockOut = 0;
					$stockAdd = 0;
					
					//echo $query;
					//print_r($transData); exit;
					
					foreach($transData AS $key => $value){
						$tempQty = 0;
						if($value['u_measure'] == $value['item_measure']){
							$tempQty = $value['qty'];
						}else{
							$match = false;
							foreach($altMeasure AS $altK => $altV){
								if($value['u_measure'] == $value[$altV] && trim($value['u_measure']) != ''){
									$tempQty = $value['qty']*$value[$altFactor[$altK]];
									$match = true;
								}
							}
							if($match == false){
								$tempQty = $value['qty'];
							}
						}
						
						$tempFreeQty = 0;
						if($value['qty1'] > 0){
							if($value['u_measure1'] == $value['item_measure']){
								$tempFreeQty = $value['qty1'];
							}else{
								$match = false;
								foreach($altMeasure AS $altK => $altV){
									if($value['u_measure1'] == $value[$altV]){
										$tempFreeQty = $value['qty1']*$value[$altFactor[$altK]];
										$match = true;
									}
								}
								if($match == false){
									$tempFreeQty = $value['qty1'];
								}
							}
						}
						
						$tempQty += $tempFreeQty;
						$billType = strtolower(trim($value['type']));
						switch($billType){
							case 'do':
								if(trim($value['retr']) == ""){$stockOut -= $tempQty;}
							break;
							case 'is':
							case 'pr':
							case 'ca':
							case 'dn':
								$stockOut -= $tempQty;
							break;
							case 'in':
								//if(trim($value['billno']) == ""){
									$stockOut -= $tempQty;
								//}
							break;
							case 're':
							case 'cn':
								$stockIn += $tempQty;
							break;
							case 'ad':
								$stockAdd += $tempQty;
							break;
							case 'tr':
								if($value['toloc'] == $wareHouse && $wareHouse != ''){
									$stockIn += $tempQty;
								}else{
									$stockOut -= $tempQty;
								}
							break;
						}
					}
					$output += $stockIn + $stockOut + $stockAdd;
				}catch(exception $e){echo $e; exit;}
			}
			return $output;
		}
		
		function checkPdepositExist(){
			return file_exists($GLOBALS["siteSetting"]["ubs_dir"]."\\pdeposit.dbf");
		}
	}
?>