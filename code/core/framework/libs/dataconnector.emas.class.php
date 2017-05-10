<?php
	require_once DIR_LIBS.'/emas.class.php';
	
	class DataConnectorEMAS extends EMAS{
		var $db;
		var $totalRow;
		var $emasTable = "emas_table";
		var $emasColumnKey = "emas_unique_column";
		var $syncHeader = array();
		var $syncBatch = 200;
		
		function DataConnectorEMAS($db, $rule){
			$this->db = $db;
			$this->loadBatch();
			$this->loadEMASRule($rule);
			$this->EMAS();
		}

		function loadEMASRule($type){
			switch($type){
				case "iccat":
					$this->emasTable = "iccat";
					$this->emasColumnKey = "code";
					$this->syncHeader = array("code", "desc");
				break;
				case "currency":
					$this->emasTable = "currency";
					$this->emasColumnKey = "currcode";
					$this->syncHeader = array("currcode", "desc", "currrate");
				break;
				case "arcust":
					$this->emasTable = "arcust";
					$this->emasColumnKey = "custno";
					$this->syncHeader = array("custno", "attn", "contact", "name", "name2", "add1", "add2", "add3", "add4", "daddr1", "daddr2", "daddr3", "daddr4", "crlimit", "phone", "phonea", "fax", "business", "currency", "agent", "lastyrbal", "term", "email", "website", "gstno", "taxdesc");
				break;
				case "icship":
					$this->emasTable = "icship";
					$this->emasColumnKey = "code";
					$this->syncHeader = array("code", "custno", "add1", "add2", "add3", "add4");
				break;
				case "icdept":
					$this->emasTable = "icdept";
					$this->emasColumnKey = "code";
					$this->syncHeader = array("code", "desc");
				break;
				case "icgroup":
					$this->emasTable = "icgroup";
					$this->emasColumnKey = "group";
					$this->syncHeader = array("group", "desc");
				break;
				case "icitem":
					$this->emasTable = "icitem";
					$this->emasColumnKey = "item_no";
					$this->syncHeader = array("item_no", "item_no2", "desc1", "desc2", "group", "cat", "dept", "u_measure", "mostcost", "weight", "minimum", "maximum", "remark", "suspend", "reorder", "staxcode", "s_price", "price_b", "c_price", "stock", "date");
					for($i=1; $i<=5; $i++){
						array_push($this->syncHeader, "unit$i");
						array_push($this->syncHeader, "item$i");
						array_push($this->syncHeader, "measure$i");
						array_push($this->syncHeader, "factor$i");
						array_push($this->syncHeader, "price$i");
						array_push($this->syncHeader, "s_price$i");
						array_push($this->syncHeader, "price_b$i");
					}
				break;
				case "icterr":
					$this->emasTable = "icterr";
					$this->emasColumnKey = "code";
					$this->syncHeader = array("code", "desc", "add1", "add2", "add3", "add4", "phone", "contact");
				break;
				case "glagent":
					$this->emasTable = "glagent";
					$this->emasColumnKey = "agent";
					$this->syncHeader = array("agent", "name");
				break;
			}
		}
		
		function loadBatch(){
			$sql = "SELECT dataconnector_batch FROM sys_settings WHERE id='1'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$this->syncBatch = $result['dataconnector_batch'];
			}
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}

		function listItemField(){
			$output = array();
			array_push($output, "'sync_date'");
			$emasStructure = $this->getDBFStructure($this->emasTable);
			if(isset($emasStructure['column'])){
				foreach($emasStructure['column'] AS $column){
					array_push($output, "'$column'");
				}
			}
			return $output;
		}

		function listItemColumn(){
			$output = array();
			$temp = array('xtype' => "rownumberer", 'resizable' => true, 'width' => 35);
			array_push($output, $temp);
			$temp = array('header' => "Sync Date", 'dataIndex' => "sync_date");
			array_push($output, $temp);
			$emasStructure = $this->getDBFStructure($this->emasTable);
			if(isset($emasStructure['column'])){
				foreach($emasStructure['column'] AS $column){
					$temp = array('header' => "$column", 'dataIndex' => "$column");
					array_push($output, $temp);
				}
			}
			return $output;
		}

		function listItemFilter(){
			$output = array();
			$emasStructure = $this->getDBFStructure($this->emasTable);
			if(isset($emasStructure['column'])){
				foreach($emasStructure['column'] AS $column){
					$temp = array('type' => "string", 'dataIndex' => "$column");
					array_push($output, $temp);
				}
			}
			return $output;
		}

		function listItem($condition, $start, $limit){
			$output = array();
			$condition = " AND $this->emasColumnKey != '' $condition ";
			$this->totalRow = $this->getTotalVFPRow($this->emasTable, $condition);
			$sql = "SELECT * FROM $this->emasTable WHERE 1=1 $condition ";
			$output = $this->selectQueryLimit($sql, $start, $limit);
			foreach($output AS $key => $row){
				foreach($row AS $column => $data){
					$row[$column] = trim(iconv($GLOBALS["siteSetting"]["emas_encoding"], "UTF-8//IGNORE", $data));
				}
				$row['sync_date'] = $this->getRecordSyncDate($this->db, $this->emasColumnKey, $row[$this->emasColumnKey], $this->emasTable);
				$output[$key] = $row;
			}
			return $output;
		}
		
		function deleteSyncRecord($id){
			if($this->db->delete("emas_".$this->emasTable, "`id`='$id'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveSyncRecord($data){
			if($this->db->insert("emas_".$this->emasTable, $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateSyncRecord($data){
			if($this->db->update("emas_".$this->emasTable, $data, "`".$this->emasColumnKey."`='".$data[$this->emasColumnKey]."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function syncNew(){
			if($this->hasLastUpdateField()){
				$output = array('success' => false, 'message' => 'unknown error');
				$i = 0;
				$a = 0;
				$totalEMASRow = $this->getTotalVFPRow($this->emasTable, "");
				$hasRecord = false;
				$generatedFile = array();
				while($i <= $totalEMASRow){
					$a++;
					$syncData = array();
					
					$query = "SELECT ".$this->emasColumnKey.", lastupdt FROM ".$this->emasTable;
					$result = $this->selectQueryLimit($query, $i, $this->syncBatch);	
					foreach($result AS $emasData){
						$syncDate = $this->getRecordSyncDate($this->db, $this->emasColumnKey, trim($emasData[$this->emasColumnKey]), $this->emasTable);
						$processSyncDate = str_replace(array(" ", "-", ":"), "", $syncDate);
						
						if($syncDate == "-" || $emasData['lastupdt'] > $processSyncDate){
							$query = "SELECT ".implode(", ", $this->syncHeader)." FROM ".$this->emasTable." WHERE ".$this->emasColumnKey." = [".$emasData[$this->emasColumnKey]."]";
							$newData = $this->selectQuery($query, -1, 1);
							array_push($syncData, $this->formatSimplifiedJSONValue($newData));
							
							$newSyncRecord = array();
							$newSyncRecord['sync_date'] = $emasData['lastupdt'];
							$newSyncRecord[$this->emasColumnKey] = cleanMYQuery($emasData[$this->emasColumnKey]);
							if($syncDate == "-"){
								$this->saveSyncRecord($newSyncRecord);
							}else{
								$this->updateSyncRecord($newSyncRecord);
							}
						}
					}
					
					if(!empty($syncData)){
						$hasRecord = true;
						array_push($generatedFile, $this->createSyncJSONData($syncData, false));
					}
					$i += $this->syncBatch;
				}
				
				$output['success'] = true;
				if($hasRecord){
					$queueStatus = $this->sendQueueFiles($generatedFile);
					if($queueStatus['status'] == true){
						$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
						$output['success'] = true;
						$output['message'] = "Sync files have been prepared.";
					}else{
						$output['success'] = false;
						$output['message'] = $queueStatus['message'];
					}
					$this->removeQueueFiles($generatedFile);
				}else{
					$output['message'] = "There is no any new records.";
				}
				return $output;
			}else{
				return $this->syncSelection(true, array(), array(), false);
			}
		}

		function syncSelection($rememberAll, $rememberSelection, $filters, $purge){
			$output = array('success' => false, 'message' => 'unknown error');
			$hasLastUpdateField = $this->hasLastUpdateField();
			$i = 0;
			$generatedFile = array();
			$condition = "";
			foreach($filters AS $filter){
				if($filter['data']['type'] == "string"){
					$condition .= " AND lower(".$filter['field'].") LIKE '%".strtolower($filter['data']['value'])."%' ";
				}
			}

			if($rememberAll == "true"){
				$totalEMASRow = $this->getTotalVFPRow($this->emasTable, $condition);
				while($i <= $totalEMASRow){
					$syncData = array();
					if($hasLastUpdateField){
						$query = "SELECT ".$this->emasColumnKey.", lastupdt FROM ".$this->emasTable." WHERE 1=1 $condition";
					}else{
						$query = "SELECT ".$this->emasColumnKey." FROM ".$this->emasTable." WHERE 1=1 $condition";
					}
					$result = $this->selectQueryLimit($query, $i, $this->syncBatch);
					foreach($result AS $emasData){
						if(!in_array(trim($emasData[$this->emasColumnKey]), $rememberSelection)){
							$syncDate = $this->getRecordSyncDate($this->db, $this->emasColumnKey, trim($emasData[$this->emasColumnKey]), $this->emasTable);

							$query = "SELECT ".implode(", ", $this->syncHeader)." FROM ".$this->emasTable." WHERE ".$this->emasColumnKey." = [".$emasData[$this->emasColumnKey]."]";
							$newData = $this->selectQuery($query, -1, 1);
							array_push($syncData, $this->formatSimplifiedJSONValue($newData));

							if($hasLastUpdateField){
								$newSyncRecord = array();
								$newSyncRecord['sync_date'] = $emasData['lastupdt'];
								$newSyncRecord[$this->emasColumnKey] = cleanMYQuery($emasData[$this->emasColumnKey]);
								if($syncDate == "-"){
									$this->saveSyncRecord($newSyncRecord);
								}else{
									$this->updateSyncRecord($newSyncRecord);
								}
							}
						}
					}

					if(!empty($syncData)){
						array_push($generatedFile, $this->createSyncJSONData($syncData, $purge));
					}
					$i += $this->syncBatch;
				}
			}else{
				$totalEMASRow = count($rememberSelection);
				while($i <= $totalEMASRow){
					$syncData = array();
					for($j=$i; $j<($i+$this->syncBatch); $j++){
						if(isset($rememberSelection[$j])){
							$syncDate = $this->getRecordSyncDate($this->db, $this->emasColumnKey, trim($rememberSelection[$j]), $this->emasTable);
							if($hasLastUpdateField){
								$query = "SELECT ".implode(", ", $this->syncHeader).", lastupdt FROM ".$this->emasTable." WHERE ".$this->emasColumnKey." = [".$rememberSelection[$j]."]";
							}else{
								$query = "SELECT ".implode(", ", $this->syncHeader)." FROM ".$this->emasTable." WHERE ".$this->emasColumnKey." = [".$rememberSelection[$j]."]";
							}
							$newData = $this->selectQuery($query, -1, 1);
							array_push($syncData, $this->formatSimplifiedJSONValue($newData));
							
							if($hasLastUpdateField){
								$newSyncRecord = array();
								$newSyncRecord['sync_date'] = $newData['lastupdt'];
								$newSyncRecord[$this->emasColumnKey] = cleanMYQuery($rememberSelection[$j]);
								if($syncDate == "-"){
									$this->saveSyncRecord($newSyncRecord);
								}else{
									$this->updateSyncRecord($newSyncRecord);
								}
							}
						}else{
							break;
						}
					}

					if(!empty($syncData)){
						array_push($generatedFile, $this->createSyncJSONData($syncData, $purge));
					}
					$i += $this->syncBatch;
				}
			}

			$queueStatus = $this->sendQueueFiles($generatedFile);
			if($queueStatus['status'] == true){
				$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
				$output['success'] = true;
				$output['message'] = "Sync files have been prepared.";
			}else{
				$output['message'] = $queueStatus['message'];
			}
			$this->removeQueueFiles($generatedFile);
			return $output;
		}
		
		function customSyncIcship(){
			$output = array('success' => false, 'message' => 'unknown error');
			$i = 0;
			$generatedFile = array();
			
			$condition = "";
			$totalEMASRow = $this->getTotalVFPRow($this->emasTable, $condition);
			$hasRecord = false;
			while($i <= $totalEMASRow){
				$syncData = array();
				
				$query = "SELECT ".implode(", ", $this->syncHeader)." FROM ".$this->emasTable;
				$result = $this->selectQueryLimit($query, $i, $this->syncBatch);	
				foreach($result AS $emasData){
					array_push($syncData, $this->formatSimplifiedJSONValue($emasData));
				}
				
				if(!empty($syncData)){
					$hasRecord = true;
					array_push($generatedFile, $this->createSyncJSONData($syncData, true));
				}
				$i += $this->syncBatch;
			}
			
			$output['success'] = true;
			if($hasRecord){
				$queueStatus = $this->sendQueueFiles($generatedFile);
				if($queueStatus['status'] == true){
					$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
					$output['success'] = true;
					$output['message'] = "Sync files have been prepared.";
				}else{
					$output['success'] = false;
					$output['message'] = $queueStatus['message'];
				}
				$this->removeQueueFiles($generatedFile);
			}else{
				$output['message'] = "There is no any new records.";
			}
			return $output;
		}

		function createSyncJSONData($syncData, $purge){
			$jsonFile = array("header" => $this->syncHeader, "purge" => $purge, "data" => $syncData);
			$i = 0;
			$filename = date("YmdHis")."_".$i."_emas_".$this->emasTable.".json";
			while(file_exists(DIR_MEDIA."/temporary/".$filename)){
				$i++;
				$filename = date("YmdHis")."_".$i."_emas_".$this->emasTable.".json";
			}
			$file = fopen(DIR_MEDIA."/temporary/".$filename, 'w');
			fwrite($file, json_encode($jsonFile));
			fclose($file);
			return $filename;
		}
		
		function saveImportationLog($file, $identifier){
			$newData = array();
			$newData['type'] = "emas";
			$newData['mode'] = $this->emasTable;
			$newData['status'] = 0;
			$newData['identifier'] = $identifier;
			$newData['file'] = implode(";", $file);
			if(isset($_SESSION['user_id'])){
				$newData['created_by'] = $_SESSION['user_id'];
			}
			$newData['created_date'] = date("Y-m-d H:i:s");
			if($this->db->insert("cron_importation_log", $newData)){
				return true;
			}else{
				return false;
			}
		}
		
		function countPendingImportationLog(){
			$output = 0;
			$sql = "SELECT COUNT(id) AS 'total' FROM cron_importation_log WHERE type = 'emas' AND status = '0' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['total'];
			}
			return $output;
		}
		
		function hasLastUpdateField(){
			$output = false;
			$emasStructure = $this->getDBFStructure($this->emasTable);
			if(isset($emasStructure['column']) && in_array("lastupdt", $emasStructure['column'])){
				$output = true;
			}
			return $output;
		}
	}
?>