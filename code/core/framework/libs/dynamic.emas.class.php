<?php
	class DynamicEMAS{
		var $vfp;
		var $error = '';
		var $status = false;
		var $isGST = false;
		var $connect = false;
		var $path = "";
		
		function DynamicEMAS(){}
		
		function connectEmas($path){
			if(!$this->connect){
				require_once DIR_PLUGINS.'/adodb5/adodb.inc.php';
				require_once DIR_PLUGINS.'/adodb5/adodb-exceptions.inc.php';
				require_once DIR_PLUGINS.'/adodb5/xbase.class.php';
				
				$this->vfp = ADONewConnection('vfp');
				
				$conn = "Driver={Microsoft Visual FoxPro Driver}; SourceType=DBF; SourceDB=".$path;
				$this->path = $path;
				try{
					$this->vfp->Connect($conn);
					$this->vfp->SetFetchMode(ADODB_FETCH_ASSOC);
					$this->status = true;
					$this->checkEMASGSTVersion();
				}catch(exception $e){
					$this->status = false;
					$this->error = $e;
				}
				$this->connect = true;
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
		
		function checkEMASGSTVersion(){
			require_once DIR_PLUGINS.'/adodb5/adodb.inc.php';
			require_once DIR_PLUGINS.'/adodb5/adodb-exceptions.inc.php';
			require_once DIR_PLUGINS.'/adodb5/xbase.class.php';
			
			try{
				$xBase = new XBaseTable($this->path."\\icso.dbf");
				$xBase->open();
				$column = array_map('strtolower', $xBase->columnNames);
				if(!empty($column) && in_array("taxcode", $column)){
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
		
		function emasRemoveIlegalUTF($value){
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
				$value .= mb_convert_encoding($record, $GLOBALS["siteSetting"]["emas_encoding"], "UTF-8");
			}
			$query = "INSERT INTO ".$table." (".$column.") VALUES (".$value.") ";
			try{
				$this->vfp->Execute($query);
				return true;
			}catch(exception $e){return false;}
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
			require_once DIR_PLUGINS.'/adodb5/adodb.inc.php';
			require_once DIR_PLUGINS.'/adodb5/adodb-exceptions.inc.php';
			require_once DIR_PLUGINS.'/adodb5/xbase.class.php';
			
			$output = array();
			try{
				$xBase = new XBaseTable($this->path."\\".$table.".dbf");
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

		function padString($value, $totalDigit){
			$padString = "";

			$padString = str_pad($value, 24, " ");
			$padString = substr($padString, 0, 24);

			return $padString;
		}
		
		function countProductQuantityEmas($itemCode, $unitMeasure, $stockDate, $wareHouseCode = array()){
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

				try{
					$query = "SELECT ictran.type, ictran.qty, ictran.billno, ictran.u_measure, icitem.u_measure AS 'item_measure', icitem.unit1, icitem.unit2, icitem.unit3, icitem.unit4, icitem.unit5, icitem.factor1, icitem.factor2, icitem.factor3, icitem.factor4, icitem.factor5, ictran.qty1, ictran.u_measure1, icmast.ref, icmast.retr, ictran.toloc, ictran.date, ictran.location ";
					$query .= "FROM ictran, icitem, icmast ";
					$query .= "WHERE ictran.type=icmast.type AND ictran.entry=icmast.entry AND ictran.item_no=icitem.item_no AND icmast.void <> 'Y' AND icmast.retr <> 'Y' AND ((ictran.item_no+DTOS(ictran.date)+ictran.sequence) LIKE '". $this->padString($itemCode, 24) ."%')";
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
								if(trim($value['retr']) == ""){
									$stockOut -= $tempQty;
								}
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
								// echo $value['date']." | ".$stockIn." || ";
							break;
							case 'ad':
								$stockAdd += $tempQty;
							break;
							case 'tr':
								if($wareHouse != ""){
									if(trim($value['toloc']) == trim($wareHouse) && $wareHouse != ""){
										$stockIn += $tempQty;
										if(trim($value['toloc']) == trim($value['location']) && $wareHouse != ""){
											$stockOut -= $tempQty;
										}
									}else{
										$stockOut -= $tempQty;
									}
								}else{
									$stockIn += $tempQty;
									$stockOut -= $tempQty;
								}	
								// echo $value['date']." | ".$stockIn." || ";
							break;
						}
					}
					$output += $stockIn + $stockOut + $stockAdd;
					// echo "Stock In : ".$stockIn."; Stock Add : ".$stockAdd."; Stock Out : ".$stockOut."; OUTPUT = ".$output;
					// exit;	
				}catch(exception $e){echo $e; exit;}
			}
			return $output;
		}

		function custom_search($array, $location, $bin){
		    $r = "";
		    foreach ($array as $key => $test) {
		        if (trim($test['location']) == trim($location) && trim($test['bin']) == trim($bin)) {
		            $r = $key;
		        }
		    }
		    return $r;
		} 

		function countProductLocBinQuantityEmas($itemCode, $unitMeasure, $stockDate, $wareHouseCode = array()){
			$output = array();
			$total = 0;
			foreach($wareHouseCode AS $wareHouse){
				try{
					$query = "SELECT qty, location, bin from icbin WHERE item_no = '".$itemCode."' UNION SELECT 0, location, bin from ictran WHERE item_no = '".$itemCode."' GROUP BY location, bin";
					$result = $this->vfp->Execute($query);
					$temp = $result->fields;
					if(!empty($temp)){
						$temp = $result->_array;
						if(!empty($temp)){
							foreach ($temp as $key => $value) {								
								if($value['location'] !=  '' || $value['bin'] != ''){
									$key = $this->custom_search($output, $value['location'], $value['bin']);
									$var = strval($key);
									if($var != ''){
										$output[$key]['qty'] += $value['qty'];
									}else{
										array_push($output, $value);
									}
								}
								$total += $value['qty'];
							}
						}
					}
				}catch(exception $e){

				}

				try{
					$query = "SELECT ictran.type, ictran.qty, ictran.billno, ictran.u_measure, icitem.u_measure AS 'item_measure', icitem.unit1, icitem.unit2, icitem.unit3, icitem.unit4, icitem.unit5, icitem.factor1, icitem.factor2, icitem.factor3, icitem.factor4, icitem.factor5, ictran.qty1, ictran.u_measure1, icmast.ref, icmast.retr, ictran.toloc, ictran.date, ictran.location, ictran.bin, ictran.tobin ";					
					$query .= "FROM ictran, icitem, icmast ";
					$query .= "WHERE ictran.type=icmast.type AND ictran.entry=icmast.entry AND ictran.item_no=icitem.item_no AND icmast.void <> 'Y' AND icmast.retr <> 'Y' AND ((ictran.item_no+DTOS(ictran.date)+ictran.sequence) LIKE '". $this->padString($itemCode, 24) ."%')";
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
					
					foreach($transData AS $key => $value){
						if($value['location'] !=  '' || $value['bin'] != ''){
							if(count($output) > 0){							
								$key = $this->custom_search($output, $value['location'], $value['bin']);
								$var = strval($key);
								if($var != ''){
									$_stockIn = 0;
									$_stockOut = 0;
									$_stockAdd = 0;
									$_tempQty = 0;
									
									if($value['u_measure'] == $value['item_measure']){
										$_tempQty = $value['qty'];
									}else{
										$match = false;
										foreach($altMeasure AS $altK => $altV){
											if($value['u_measure'] == $value[$altV] && trim($value['u_measure']) != ''){
												$_tempQty = $value['qty']*$value[$altFactor[$altK]];
												$match = true;
											}
										}
										if($match == false){
											$_tempQty = $value['qty'];
										}
									}
									
									$_tempFreeQty = 0;
									if($value['qty1'] > 0){
										if($value['u_measure1'] == $value['item_measure']){
											$_tempFreeQty = $value['qty1'];
										}else{
											$match = false;
											foreach($altMeasure AS $altK => $altV){
												if($value['u_measure1'] == $value[$altV]){
													$_tempFreeQty = $value['qty1']*$value[$altFactor[$altK]];
													$match = true;
												}
											}
											if($match == false){
												$_tempFreeQty = $value['qty1'];
											}
										}
									}
									
									$_tempQty += $_tempFreeQty;
									$billType = strtolower(trim($value['type']));
									switch($billType){
										case 'do':
											if(trim($value['retr']) == ""){
												$_stockOut -= $_tempQty;
											}
										break;
										case 'is':
										case 'pr':
										case 'ca':
										case 'dn':
											$_stockOut -= $_tempQty;
										break;
										case 'in':
											//if(trim($value['billno']) == ""){
												$_stockOut -= $_tempQty;
											//}
										break;
										case 're':
										case 'cn':
											$_stockIn += $_tempQty;
											// echo trim($output['location'])."+".$_tempQty."|";
										break;
										case 'ad':
											$_stockAdd += $_tempQty;
										break;
										case 'tr':
											$_stockOut -= $_tempQty;
											if(trim($value['location']) != trim($value['toloc'] && trim($value['bin']) != trim($value['tobin']))){
												$key2 = $this->custom_search($output, $value['toloc'], $value['tobin']);
												$var2 = strval($key2);
												if($var2 != ''){
													// echo trim($output[$key2]['location'])."+".$_tempQty."|";
													$output[$key2]['qty'] += $_tempQty;
												}
											}else{
												$_stockIn += $_tempQty;
											}
										break;
									}
									$output[$key]['qty'] += $_stockIn + $_stockOut + $_stockAdd;									
								}
							}
						}

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
								if(trim($value['retr']) == ""){
									$stockOut -= $tempQty;
								}
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
								if($wareHouse != ""){
									if(trim($value['toloc']) == trim($wareHouse) && $wareHouse != ""){
										$stockIn += $tempQty;
										if(trim($value['toloc']) == trim($value['location']) && $wareHouse != ""){
											$stockOut -= $tempQty;
										}
									}else{
										$stockOut -= $tempQty;
									}
								}else{
									$stockIn += $tempQty;
									$stockOut -= $tempQty;
								}	
							break;
						}
					}
					$total += $stockIn + $stockOut + $stockAdd;
					array_push($output, $total);
					// print_r($output);
					// exit;
				}catch(exception $e){echo $e; exit;}
			}
			return $output;
		}
		
		function checkPdepositExist(){
			return file_exists($this->path."\\pdeposit.dbf");
		}
		
		function getTotalVFPRow($table, $condition = ""){
			$query = "SELECT COUNT(*) AS  total FROM $table WHERE 1=1 $condition";
			$result = $this->selectQuery($query, -1, 1);	
			if(!empty($result)){
				return $result['total'];
			}else{
				return 0;
			}
		}

		function getRecordSyncDate($db, $pkField, $pkValue, $table){
			$sql = "SELECT sync_date FROM emas_$table WHERE `$pkField` = '$pkValue' ";
			$db->query($sql);
			if($db->nextRecord()){
				$result = $db->getRecord();
				return $result['sync_date'];
			}else{
				return '-';
			}
		}
		
		function checkTaxCodeExist($code){
			$query = "SELECT COUNT(*) AS total FROM stax WHERE trim(code) = '".trim($code)."'";
			$result = $this->selectQuery($query, -1, 1);
			if(!empty($result)){
				if($result['total'] == 0){
					return false;
				}else{
					return true;
				}
			}else{
				return false;
			}
		}
		
		function checkAccountNoExist($code){
			$query = "SELECT COUNT(*) AS total FROM gldata WHERE trim(accno) = '".trim($code)."'";
			$result = $this->selectQuery($query, -1, 1);
			if(!empty($result)){
				if($result['total'] == 0){
					return false;
				}else{
					return true;
				}
			}else{
				return false;
			}
		}

		function checkJournalNoExist($journal){
			$query = "SELECT COUNT(*) AS total FROM glpost WHERE trim(ref1) = '".trim($journal)."'";
			$result = $this->selectQuery($query, -1, 1);
			if(!empty($result)){
				if($result['total'] == 0){
					return false;
				}else{
					return true;
				}
			}else{
				return false;
			}
		}
	}
?>