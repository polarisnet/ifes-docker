<?php
	require_once DIR_LIBS.'/pdo.class.php';

	class DataConnectorPDO{
		var $objPDO;
		var $objMainPDO;
		var $totalRow;
		var $syncHeader = array();
		var $syncBatch = 200;
		var $syncTable = "";
		var $syncColumnKey = "";
		var $syncLastUpdateField = "";
		var $mainTable = "";
		var $mainColumnKey = "";
		var $hasLastUpdateField = false;
		var $isGST = false;

		function DataConnectorPDO($mode){
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
					$this->objPDO = new OZPDO(array(
						"mode" => "foxpro",
						"dir" => $GLOBALS["siteSetting"]["emas_directory"],
						"encoding" => $GLOBALS["siteSetting"]["emas_encoding"]
					));
					$this->checkEMASGSTVersion();
					$this->syncLastUpdateField = "lastupdt";
					switch($mode){
						case "items":
							$this->syncTable = "icitem";
							$this->syncColumnKey = "item_no";
							$this->syncHeader = array("item_no", "item_no2", "desc1", "desc2", "group", "cat", "dept", "u_measure", "mostcost", "weight", "minimum", "maximum", "remark", "suspend", "reorder", "s_price", "price_b", "c_price", "stock", "date");
							if($this->isGST){
								array_push($this->syncHeader, "staxcode");
							}
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
						case "categories":
							$this->syncTable = "iccat";
							$this->syncColumnKey = "code";
							$this->syncHeader = array("code", "desc");
						break;
						case "groups":
							$this->syncTable = "icgroup";
							$this->syncColumnKey = "group";
							$this->syncHeader = array("group", "desc");
						break;
						case "departments":
							$this->syncTable = "icdept";
							$this->syncColumnKey = "code";
							$this->syncHeader = array("code", "desc");
						break;
						case "currencies":
							$this->syncTable = "currency";
							$this->syncColumnKey = "currcode";
							$this->syncHeader = array("currcode", "desc", "currrate");
						break;
						case "salespersons":
							$this->syncTable = "glagent";
							$this->syncColumnKey = "agent";
							$this->syncHeader = array("agent", "name");
						break;
						case "customers":
							$this->syncTable = "arcust";
							$this->syncColumnKey = "custno";
							$this->syncHeader = array("custno", "attn", "contact", "name", "name2", "add1", "add2", "add3", "add4", "daddr1", "daddr2", "daddr3", "daddr4", "crlimit", "phone", "phonea", "fax", "business", "currency", "agent", "lastyrbal", "term", "email", "website", "gstno", "taxdesc");
						break;
						case "locations":
							$this->syncTable = "icterr";
							$this->syncColumnKey = "code";
							$this->syncHeader = array("code", "desc", "add1", "add2", "add3", "add4", "phone", "contact");
						break;
						case "addresses":
							$this->syncTable = "icship";
							$this->syncColumnKey = "code";
							$this->syncHeader = array("code", "custno", "add1", "add2", "add3", "add4");
						break;
						case "stocks":
							$this->syncTable = "icitem";
							$this->syncColumnKey = "item_no";
							$this->syncHeader = array("item_no");
						break;
						case "item_images":
							$this->syncTable = "icitem";
							$this->syncColumnKey = "item_no";
							$this->syncHeader = array("item_no", "pictpath", "pictpath2", "pictpath3", "pictpath4");
						break;
						case "history_prices":
							$this->syncTable = "iccust";
							$this->syncColumnKey = "ref";
							$this->syncHeader = array("item_no", "custno", "price", "date", "u_measure", "quantity");
						break;
					}
				break;
				case "ubs":
					$this->objPDO = new OZPDO(array(
						"mode" => "foxpro",
						"dir" => $GLOBALS["siteSetting"]["ubs_directory"],
						"encoding" => $GLOBALS["siteSetting"]["ubs_encoding"]
					));
					$this->syncLastUpdateField = "updated_on";
					$this->isGST = $GLOBALS["siteSetting"]["gst_version"];
					switch($mode){
						case "items":
						case "stocks":
						case "history_prices":
							$this->syncTable = "icitem";
							$this->syncColumnKey = "itemno";
							$this->syncHeader = array("itemno", "aitemno", "desp", "despa", "group", "category", "brand", "unit", "factor1", "weight", "price", "price2", "price3", "ucost", "minimum", "maximum", "reorder", "remark1", "date", "salestax");
						break;
						case "categories":
							$this->syncTable = "iccate";
							$this->syncColumnKey = "cate";
							$this->syncHeader = array("cate", "desp");
						break;
						case "groups":
							$this->syncTable = "icgroup";
							$this->syncColumnKey = "group";
							$this->syncHeader = array("group", "desp");
						break;
						case "departments":
							$this->syncTable = "brand";
							$this->syncColumnKey = "brand";
							$this->syncHeader = array("brand", "desp");
						break;
						case "salespersons":
							$this->syncTable = "icagent";
							$this->syncColumnKey = "agent";
							$this->syncHeader = array("agent", "desp");
						break;
						case "customers":
							$this->objPDO = new OZPDO(array(
								"mode" => "foxpro-oledb",
								"dir" => $GLOBALS["siteSetting"]["ubs_directory"],
								"encoding" => $GLOBALS["siteSetting"]["ubs_encoding"],
								"table" => "arcust"
							));
							$this->syncTable = "arcust";
							$this->syncColumnKey = "custno";
							$this->syncHeader = array("custno", "attn", "contact", "name", "name2", "add1", "add2", "add3", "add4", "daddr1", "daddr2", "daddr3", "daddr4", "crlimit", "phone", "phonea", "fax", "business", "currency", "agent", "term", "e_mail", "web_site", "taxcode");
						break;
						case "currencies":
							$this->syncTable = "currency";
							$this->syncColumnKey = "currcode";
							$this->syncHeader = array("currcode", "currency", "currrate");
						break;
						case "locations":
							$this->syncTable = "iclocate";
							$this->syncColumnKey = "location";
							$this->syncHeader = array("location", "desp", "addr1", "addr2", "addr3", "addr4");
						break;
						case "addresses":
							$this->objPDO = new OZPDO(array(
								"mode" => "foxpro-oledb",
								"dir" => $GLOBALS["siteSetting"]["ubs_directory"],
								"encoding" => $GLOBALS["siteSetting"]["ubs_encoding"],
								"table" => "address"
							));
							$this->syncTable = "address";
							$this->syncColumnKey = "code";
							$this->syncHeader = array("code", "custno", "add1", "add2", "add3", "add4", "attn");
						break;
						case "item_images":
							$this->syncTable = "icitem";
							$this->syncColumnKey = "itemno";
							$this->syncHeader = array("itemno", "photo");
						break;
					}
				break;
				case "qne":
					$this->objPDO = new OZPDO(array(
						"mode" => "mssql",
						"server" => $GLOBALS["siteSetting"]["qne_server"],
						"port" => $GLOBALS["siteSetting"]["qne_port"],
						"db" => $GLOBALS["siteSetting"]["qne_db"],
						"user" => $GLOBALS["siteSetting"]["qne_user"],
						"password" => $GLOBALS["siteSetting"]["qne_password"]
					));
					$this->isGST = $GLOBALS["siteSetting"]["gst_version"];
					switch($mode){
						case "items":
						case "stocks":
						case "history_prices":
							$this->syncTable = "stocks";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "stockcode", "stockname", "defaultinputtaxcodeid", "baseuom", "listprice", "purchaseprice", "weight", "isactive", "stockcontrol", "categoryid", "groupid");
						break;
						case "categories":
							$this->syncTable = "stockcategories";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "categorycode", "description");
						break;
						case "groups":
							$this->syncTable = "stockgroups";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "groupcode", "description");
						break;
						case "departments":
							$this->syncTable = "stockclasses";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "classcode", "description");
						break;
						case "salespersons":
							$this->syncTable = "salespersons";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "staffcode", "name", "email", "mobileno");
						break;
						case "customers":
							$this->syncTable = "debtors";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "companyname", "companycode", "contactperson", "address1", "address2", "address3", "address4", "homepage", "email", "salespersonid", "phoneno1", "phoneno2", "faxno1", "businessnature", "currencyid", "creditlimit", "termid", "defaulttaxcodeid");
						break;
						case "currencies":
							$this->syncTable = "currencies";
							$this->syncColumnKey = "currencycode";
							$this->syncHeader = array("currencycode", "description", "sign", "defaultpurchaserate");
						break;
						case "locations":
							$this->syncTable = "stocklocations";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "locationcode", "address1", "address2", "address3", "address4", "contact", "phoneno");
						break;
						case "addresses":
							$this->syncTable = "deliveryaddresses";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "debtorid", "deliveryaddresscode", "deliveryaddress1", "deliveryaddress2", "deliveryaddress3", "deliveryaddress4", "contactperson");
						break;
						case "item_images":
							$this->syncTable = "stocks";
							$this->syncColumnKey = "id";
							$this->syncHeader = array("id", "stockcode", "picture");
						break;
					}
				break;
				case "autocount":
					$this->objPDO = new OZPDO(array(
						"mode" => "mssql",
						"server" => $GLOBALS["siteSetting"]["autocount_server"],
						"port" => $GLOBALS["siteSetting"]["autocount_port"],
						"db" => $GLOBALS["siteSetting"]["autocount_db"],
						"user" => $GLOBALS["siteSetting"]["autocount_user"],
						"password" => $GLOBALS["siteSetting"]["autocount_password"]
					));
					$this->isGST = $GLOBALS["siteSetting"]["gst_version"];
					$this->syncLastUpdateField = "lastupdate";
					switch($mode){
						case "items":
						case "stocks":
						case "history_prices":
							$this->syncTable = "item";
							$this->syncColumnKey = "itemcode";
							$this->syncHeader = array("itemcode", "description", "itemgroup", "itemtype", "taxtype", "baseuom", "stockcontrol", "discontinued");
						break;
						case "categories":
							$this->syncTable = "itemtype";
							$this->syncColumnKey = "itemtype";
							$this->syncHeader = array("itemtype", "description");
						break;
						case "groups":
							$this->syncTable = "itemgroup";
							$this->syncColumnKey = "itemgroup";
							$this->syncHeader = array("itemgroup", "description");
						break;
						case "departments":
							$this->syncTable = "itembrand";
							$this->syncColumnKey = "itembrand";
							$this->syncHeader = array("itembrand", "description");
						break;
						case "salespersons":
							$this->syncTable = "salesagent";
							$this->syncColumnKey = "salesagent";
							$this->syncHeader = array("salesagent", "description");
						break;
						case "customers":
							$this->syncTable = "debtor";
							$this->syncColumnKey = "accno";
							$this->syncHeader = array("accno", "companyname", "attention", "address1", "address2", "address3", "address4", "deliveraddr1", "deliveraddr2", "deliveraddr3", "deliveraddr4", "weburl", "emailaddress", "salesagent", "phone1", "phone2", "fax1", "natureofbusiness", "currencycode", "creditlimit", "displayterm");
						break;
						case "currencies":
							$this->syncTable = "currency";
							$this->syncColumnKey = "currencycode";
							$this->syncHeader = array("currencycode", "currencysymbol", "currencyword", "bankbuyrate");
						break;
						case "locations":
							$this->syncTable = "location";
							$this->syncColumnKey = "location";
							$this->syncHeader = array("location", "description", "address1", "address2", "address3", "address4", "contact", "phone1");
						break;
						case "addresses":
							$this->syncTable = "branch";
							$this->syncColumnKey = "branchcode";
							$this->syncHeader = array("branchcode", "accno", "address1", "address2", "address3", "address4", "contact");
						break;
						case "item_images":
							$this->syncTable = "item";
							$this->syncColumnKey = "itemcode";
							$this->syncHeader = array("itemcode", "imagefilename");
						break;
					}
				break;
			}
			$this->mainTable = $GLOBALS["siteSetting"]["accounting_system"]."_".$this->syncTable;
			$this->mainColumnKey = $this->syncTable."_".$this->syncColumnKey;

			$this->objMainPDO = new OZPDO(array(
				"mode" => "mysql",
				"server" => MY_DB_SERVER,
				"port" => MY_DB_PORT,
				"db" => MY_DB_DATABASE,
				"user" => MY_DB_USER,
				"password" => MY_DB_PASS
			));
			$this->loadBatch();
			$this->hasLastUpdateField = $this->hasLastUpdateField();
		
		//	$this->objPDO->delete("brand", " AND brand=:brand", array("brand"=>"ubs"));
			//$this->objPDO->update("brand", array("desp" => "new"), " AND brand=:brand", array("brand" => "ubs"));
			
			/*for($i=150; $i<200; $i++){
				$this->objPDO->insert("currency", array("currcode" => "c$i", "currency" => "$ $i"));
			}*/
			/*for($i=10; $i<100; $i++){
				$this->objPDO->insert("itembrand", array("itembrand" => "AC $i", "description" => "description $i"));
			}*/
			//exit;
		}

		function loadBatch(){
			$result = $this->objMainPDO->select("SELECT dataconnector_batch FROM sys_settings WHERE id = '1'", array());
			if(!empty($result)){
				$this->syncBatch = $result['dataconnector_batch'];
			}
		}

		function getTotalRow(){
			return $this->totalRow;
		}

		function getInsertedId(){
			return $this->objMainPDO->getInsertedId();
		}

		function getHasLastUpdateField(){
			return $this->hasLastUpdateField;
		}

		function getSyncColumnKey(){
			return $this->syncColumnKey;
		}

		function listItemField(){
			$output = array();
			array_push($output, "'sync_date'");
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
				case "ubs":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					if(!empty($tableStructure)){
						foreach($tableStructure['column'] AS $column){
							array_push($output, "'$column'");
						}
					}
				break;
				case "qne":
				case "autocount":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					foreach($tableStructure AS $structure){
						array_push($output, "'".strtolower($structure['column_name'])."'");
					}
				break;
			}
			return $output;
		}

		function listItemColumn(){
			$output = array();
			$temp = array('xtype' => "rownumberer", 'resizable' => true, 'width' => 35);
			array_push($output, $temp);
			$temp = array('header' => "Last Updated", 'dataIndex' => "sync_date", 'sortable' => false);
			array_push($output, $temp);

			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
				case "ubs":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					if(!empty($tableStructure)){
						foreach($tableStructure['column'] AS $column){
							array_push($output, array("header" => $column, "dataIndex" => $column));
						}
					}
				break;
				case "qne":
				case "autocount":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					foreach($tableStructure AS $structure){
						array_push($output, array("header" => $structure['column_name'], "dataIndex" => strtolower($structure['column_name'])));
					}
				break;
			}
			return $output;
		}

		function listItemFilter(){
			$output = array();
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
				case "ubs":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					if(!empty($tableStructure)){
						foreach($tableStructure['column'] AS $column){
							array_push($output, array("type" => "string", "dataIndex" => $column));
						}
					}
				break;
				case "qne":
				case "autocount":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					foreach($tableStructure AS $structure){
						array_push($output, array("type" => "string", "dataIndex" => strtolower($structure['column_name'])));
					}
				break;
			}
			return $output;
		}

		function listItem($config){
			$output = array();
			if($config['order'] == ""){
				$config['order'] = $this->syncColumnKey." ASC";
			}
			$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM ".$this->syncTable." WHERE 1=1 ".$config['condition'], array());
			if(!empty($total)){
				$this->totalRow = $total['total'];
			}else{
				$this->totalRow = 0;
			}
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
				case "ubs":
					$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, $config), $config['start'], $config['limit']);
					foreach($recNo AS $filterRecNo){
						array_push($output, $this->objPDO->select("SELECT * FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array()));
					}
				break;
				case "qne":
				case "autocount":
					if(isset($config['custom_list_item_image_qne'])){
						$output = $this->objPDO->selectAll("SELECT * FROM (SELECT id, stockcode, SUBSTRING(picture, 0, 20) AS 'picture', ROW_NUMBER() OVER (ORDER BY ".$config['order'].") AS RowNumber FROM ".$this->syncTable." WHERE 1=1 ".$config['condition'].") AS A WHERE A.RowNumber > ".$config['start']." AND A.RowNumber <= ".($config['start']+$config['limit']), array());
					}else{
						$output = $this->objPDO->selectAll("SELECT * FROM (SELECT *, ROW_NUMBER() OVER (ORDER BY ".$config['order'].") AS RowNumber FROM ".$this->syncTable." WHERE 1=1 ".$config['condition'].") AS A WHERE A.RowNumber > ".$config['start']." AND A.RowNumber <= ".($config['start']+$config['limit']), array());
					}
				break;
			}
			foreach($output AS $key => $row){
				if($GLOBALS["siteSetting"]["accounting_system"] == "emas"){
					foreach($row AS $column => $data){
						$row[$column] = trim(iconv($GLOBALS["siteSetting"]["emas_encoding"], "UTF-8//IGNORE", $data));
					}
				}
				if($this->hasLastUpdateField){
					$row['sync_date'] = $this->getRecordSyncDate($row);
				}else{
					$row['sync_date'] = "-";
				}
				$output[$key] = $row;
			}
			return $output;
		}

		function deleteSyncRecord($id){
			return $this->objMainPDO->delete($this->mainTable, " AND id=:id", array("id"=>$id));
		}

		function saveSyncRecord($data){
			return $this->objMainPDO->insert($this->mainTable, $data);
		}

		function updateSyncRecord($data){
			return $this->objMainPDO->update($this->mainTable, $data, " AND ".$this->mainColumnKey."=:".$this->mainColumnKey, array($this->mainColumnKey=>$data[$this->mainColumnKey]));
		}

		function countPendingImportationLog(){
			$output = 0;
			$result = $this->objMainPDO->select("SELECT COUNT(id) AS total FROM cron_importation_log WHERE type = '".$GLOBALS["siteSetting"]["accounting_system"]."' AND status = '0' ", array());
			if(!empty($result)){
				$output = $result['total'];
			}
			return $output;
		}

		function hasLastUpdateField(){
			$output = false;
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
				case "ubs":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					if(isset($tableStructure['column']) && in_array($this->syncLastUpdateField, $tableStructure['column'])){
						$output = true;
					}
				break;
				case "autocount":
					$tableStructure = $this->objPDO->getTableStructure($this->syncTable);
					foreach($tableStructure AS $structure){
						if(strtolower($structure['column_name']) == $this->syncLastUpdateField){
							$output = true;
							break;
						}
					}
				break;
			}
			return $output;
		}

		function getRecordSyncDate($record){
			$result = $this->objMainPDO->select("SELECT sync_date FROM ".$this->mainTable." WHERE ".$this->mainColumnKey." = :".$this->mainColumnKey, array($this->mainColumnKey => $record[$this->syncColumnKey]));
			if(empty($result)){
				return "-";
			}else{
				return $result['sync_date'];
			}
		}

		function syncHistoryPrices($mode){
			$output = array('success' => false, 'message' => 'unknown error');
			$i = 0;
			$generatedFile = array();
			$condition = "";
			$totalRow = 0;
			$this->syncBatch = 10000;

			$purge = true;
			$historyTimeStamp = "";
			if($mode == "new"){
				$purge = false;
				$historyTimeStamp = $this->objMainPDO->select("SELECT history_prices_timestamp FROM sys_settings WHERE id = '1'", array());	
			}

			if($GLOBALS["siteSetting"]["accounting_system"] == "emas"){
				$condition .= "AND date < {".date('m/d/y')."}";
				if($mode == "new" && !empty($historyTimeStamp) && $historyTimeStamp['history_prices_timestamp'] != "0000-00-00"){
					$tempDate = new DateTime($historyTimeStamp['history_prices_timestamp']);
					$condition .= "AND date > {".$tempDate->format('m/d/y')."}";
				}
			}

			$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM ".$this->syncTable." WHERE 1=1 $condition", array());
			if(!empty($total)){
				$totalRow = $total['total'];
			}else{
				$totalRow = 0;
			}

			while($i <= $totalRow){
				$syncData = array();
				switch($GLOBALS["siteSetting"]["accounting_system"]){
					case "emas":
						$result = array();
						$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, array("condition" => $condition, "order" => "date ASC")), $i, ($i+$this->syncBatch));
						foreach($recNo AS $filterRecNo){
							$tempResult = $this->objPDO->select("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array());
							array_push($syncData, $this->formatSimplifiedJSONValue($tempResult));
						}
					break;
				}

				if(!empty($syncData)){
					$f = 0;
					$filename = date("YmdHis")."_".$f."_oz_historyprices.json";
					while(file_exists(DIR_MEDIA."/temporary/".$filename)){
						$f++;
						$filename = date("YmdHis")."_".$f."_oz_historyprices.json";
					}
					$file = fopen(DIR_MEDIA."/temporary/".$filename, 'w');
					fwrite($file, json_encode(array("header" => $this->syncHeader, "purge" => $purge, "data" => $syncData, "accounting_system" => $GLOBALS["siteSetting"]["accounting_system"])));
					fclose($file);
					array_push($generatedFile, $filename);
				}
				$i += $this->syncBatch;
			}
			if(empty($generatedFile)){
				$output['message'] = "There is no any sync file generated.";
			}else{
				$queueStatus = $this->sendQueueFiles($generatedFile);
				if($queueStatus['status'] == true){
					$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
					$output['success'] = true;
					$output['message'] = "Sync files have been prepared.";
				}else{
					$output['message'] = $queueStatus['message'];
				}
				$this->removeQueueFiles($generatedFile);
				$data = array("id" => "1", "history_prices_timestamp" => date('Y-m-d'));
				$this->objMainPDO->update("sys_settings", $data, " AND id=:id", array("id" => $data['id']));
			}
			return $output;
		}

		function syncNew(){
			if($GLOBALS["siteSetting"]["accounting_system"] == "emas" && $this->syncTable == "iccust"){
				return $this->syncHistoryPrices("new");
			}

			if($this->hasLastUpdateField){
				$output = array('success' => false, 'message' => 'unknown error');
				$i = 0;
				$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM ".$this->syncTable, array());
				if(!empty($total)){
					$totalRow = $total['total'];
				}else{
					$totalRow = 0;
				}
				$hasRecord = false;
				$generatedFile = array();
				while($i <= $totalRow){
					$syncData = array();
					switch($GLOBALS["siteSetting"]["accounting_system"]){
						case "emas":
						case "ubs":
							$result = array();
							$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, array("condition" => "", "order" => $this->syncColumnKey." ASC")), $i, ($i+$this->syncBatch));
							foreach($recNo AS $filterRecNo){
								array_push($result, $this->objPDO->select("SELECT ".$this->syncColumnKey.", ".$this->syncLastUpdateField." FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array()));
							}
						break;
						case "qne":
						case "autocount":
							$result = $this->objPDO->selectAll("SELECT * FROM (SELECT ".$this->syncColumnKey.", ".$this->syncLastUpdateField.", ROW_NUMBER() OVER (ORDER BY ".$this->syncColumnKey." ASC) AS RowNumber FROM ".$this->syncTable." WHERE 1=1) AS A WHERE A.RowNumber > $i AND A.RowNumber <= ".($i+$this->syncBatch), array());
						break;
					}
					foreach($result AS $resultData){
						$syncDate = $this->getRecordSyncDate($resultData);
						if($syncDate == "-" || $resultData[$this->syncLastUpdateField] != $syncDate){
							$newData = $this->objPDO->select("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE ".$this->syncColumnKey." = :".$this->syncColumnKey, array($this->syncColumnKey => $resultData[$this->syncColumnKey]));
							if($GLOBALS["siteSetting"]["accounting_system"] == "qne"){
								if($this->syncTable == "stocks"){
									$this->getQNEItemExtraFields($newData);
								}else if($this->syncTable == "debtors"){
									$this->getQNECustomerExtraFields($newData);
								}else if($this->syncTable == "deliveryaddresses"){
									$this->getQNEDeliveryAddressExtraFields($newData);
								}
								
							}
							array_push($syncData, $this->formatSimplifiedJSONValue($newData));

							$newSyncRecord = array();
							$newSyncRecord['sync_date'] = $resultData[$this->syncLastUpdateField];
							$newSyncRecord[$this->mainColumnKey] = cleanMYQuery($resultData[$this->syncColumnKey]);
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
			if($GLOBALS["siteSetting"]["accounting_system"] == "emas" && $this->syncTable == "iccust"){
				return $this->syncHistoryPrices("");
			}

			$output = array('success' => false, 'message' => 'unknown error');

			$i = 0;
			$generatedFile = array();
			$condition = "";
			foreach($filters AS $filter){
				if($filter['data']['type'] == "string"){
					$condition .= " AND lower(".$filter['field'].") LIKE '%".strtolower($filter['data']['value'])."%' ";
				}
			}

			if($rememberAll == "true"){
				$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM ".$this->syncTable." WHERE 1=1 $condition", array());
				if(!empty($total)){
					$totalRow = $total['total'];
				}else{
					$totalRow = 0;
				}
				while($i <= $totalRow){
					$syncData = array();
					if($this->hasLastUpdateField){
						switch($GLOBALS["siteSetting"]["accounting_system"]){
							case "emas":
							case "ubs":
								$result = array();
								$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, array("condition" => $condition, "order" => $this->syncColumnKey." ASC")), $i, ($i+$this->syncBatch));
								foreach($recNo AS $filterRecNo){
									array_push($result, $this->objPDO->select("SELECT ".$this->syncColumnKey.", ".$this->syncLastUpdateField." FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array()));
								}
							break;
							case "qne":
							case "autocount":
								$result = $this->objPDO->selectAll("SELECT * FROM (SELECT ".$this->syncColumnKey.", ".$this->syncLastUpdateField.", ROW_NUMBER() OVER (ORDER BY ".$this->syncColumnKey." ASC) AS RowNumber FROM ".$this->syncTable." WHERE 1=1 $condition) AS A WHERE A.RowNumber > $i AND A.RowNumber <= ".($i+$this->syncBatch), array());
							break;
						}
					}else{
						switch($GLOBALS["siteSetting"]["accounting_system"]){
							case "emas":
							case "ubs":
								$result = array();
								$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, array("condition" => $condition, "order" => $this->syncColumnKey." ASC")), $i, ($i+$this->syncBatch));
								foreach($recNo AS $filterRecNo){
									array_push($result, $this->objPDO->select("SELECT ".$this->syncColumnKey." FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array()));
								}
							break;
							case "qne":
							case "autocount":
								$result = $this->objPDO->selectAll("SELECT * FROM (SELECT ".$this->syncColumnKey.", ROW_NUMBER() OVER (ORDER BY ".$this->syncColumnKey." ASC) AS RowNumber FROM ".$this->syncTable." WHERE 1=1 $condition) AS A WHERE A.RowNumber > $i AND A.RowNumber <= ".($i+$this->syncBatch), array());
							break;
						}
					}
					foreach($result AS $resultData){
						if(!in_array(trim($resultData[$this->syncColumnKey]), $rememberSelection)){
							$syncDate = $this->getRecordSyncDate($resultData);
							$newData = $this->objPDO->select("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE ".$this->syncColumnKey." = :".$this->syncColumnKey, array($this->syncColumnKey => $resultData[$this->syncColumnKey]));
							if($GLOBALS["siteSetting"]["accounting_system"] == "qne"){
								if($this->syncTable == "stocks"){
									$this->getQNEItemExtraFields($newData);
								}else if($this->syncTable == "debtors"){
									$this->getQNECustomerExtraFields($newData);
								}else if($this->syncTable == "deliveryaddresses"){
									$this->getQNEDeliveryAddressExtraFields($newData);
								}
							}
							array_push($syncData, $this->formatSimplifiedJSONValue($newData));

							if($this->hasLastUpdateField){
								$newSyncRecord = array();
								$newSyncRecord['sync_date'] = $resultData[$this->syncLastUpdateField];
								$newSyncRecord[$this->mainColumnKey] = cleanMYQuery($resultData[$this->syncColumnKey]);
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
				$totalRow = count($rememberSelection);
				while($i <= $totalRow){
					$syncData = array();
					for($j=$i; $j<($i+$this->syncBatch); $j++){
						if(isset($rememberSelection[$j])){
							if($this->hasLastUpdateField){
								$newData = $this->objPDO->select("SELECT ".implode(",", $this->syncHeader).", ".$this->syncLastUpdateField." FROM ".$this->syncTable." WHERE ".$this->syncColumnKey." = :".$this->syncColumnKey, array($this->syncColumnKey => $rememberSelection[$j]));
							}else{
								$newData = $this->objPDO->select("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE ".$this->syncColumnKey." = :".$this->syncColumnKey, array($this->syncColumnKey => $rememberSelection[$j]));
							}
							if($GLOBALS["siteSetting"]["accounting_system"] == "qne"){
								if($this->syncTable == "stocks"){
									$this->getQNEItemExtraFields($newData);
								}else if($this->syncTable == "debtors"){
									$this->getQNECustomerExtraFields($newData);
								}else if($this->syncTable == "deliveryaddresses"){
									$this->getQNEDeliveryAddressExtraFields($newData);
								}
							}
							array_push($syncData, $this->formatSimplifiedJSONValue($newData));
							if($this->hasLastUpdateField){
								$syncDate = $this->getRecordSyncDate($newData);

								$newSyncRecord = array();
								$newSyncRecord['sync_date'] = $newData[$this->syncLastUpdateField];
								$newSyncRecord[$this->mainColumnKey] = cleanMYQuery($newData[$this->syncColumnKey]);
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
			if(empty($generatedFile)){
				$output['message'] = "There is no any sync file generated.";
			}else{
				$queueStatus = $this->sendQueueFiles($generatedFile);
				if($queueStatus['status'] == true){
					$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
					$output['success'] = true;
					$output['message'] = "Sync files have been prepared.";
				}else{
					$output['message'] = $queueStatus['message'];
				}
				$this->removeQueueFiles($generatedFile);
			}
			return $output;
		}

		function formatSimplifiedJSONValue($data){
			$temp = array();
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
				case "ubs":
					foreach($data AS $value){
						array_push($temp, mb_convert_encoding(trim($value), "UTF-8", $GLOBALS["siteSetting"]["emas_encoding"]));
					}	
				break;
				case "qne":
				case "autocount":
					foreach($data AS $value){
						array_push($temp, trim($value));
					}
				break;
			}
			return $temp;
		}

		function createSyncJSONData($syncData, $purge){
			$jsonFile = array("header" => $this->syncHeader, "purge" => $purge, "data" => $syncData);
			if($GLOBALS["siteSetting"]["accounting_system"] == "qne"){
				if($this->syncTable == "stocks"){
					array_push($jsonFile['header'], "category");
					array_push($jsonFile['header'], "group");
					array_push($jsonFile['header'], "item_taxcode");
					array_push($jsonFile['header'], "item_factor");
				}else if($this->syncTable == "debtors"){
					array_push($jsonFile['header'], "deliveryaddress1");
					array_push($jsonFile['header'], "deliveryaddress2");
					array_push($jsonFile['header'], "deliveryaddress3");
					array_push($jsonFile['header'], "deliveryaddress4");
					array_push($jsonFile['header'], "agent_code");
					array_push($jsonFile['header'], "currency_code");
					array_push($jsonFile['header'], "credit_terms");
					array_push($jsonFile['header'], "taxcode");
				}else if($this->syncTable == "deliveryaddresses"){
					array_push($jsonFile['header'], "debtor_no");
				}
			}

			$i = 0;
			$filename = date("YmdHis")."_".$i."_".$GLOBALS["siteSetting"]["accounting_system"]."_".$this->syncTable.".json";
			while(file_exists(DIR_MEDIA."/temporary/".$filename)){
				$i++;
				$filename = date("YmdHis")."_".$i."_".$GLOBALS["siteSetting"]["accounting_system"]."_".$this->syncTable.".json";
			}
			$file = fopen(DIR_MEDIA."/temporary/".$filename, 'w');
			fwrite($file, json_encode($jsonFile));
			fclose($file);
			return $filename;
		}

		function sendQueueFiles($listFiles){
			$output = array("status" => false, "message" => "General error on cURL. Please contact administrator.");
			$appURL = getAppURL();
			if($appURL == ""){
				$output['message'] = "Cannot get APP URL";
			}else{
				$connectionApp = curl_init();
				curl_setopt($connectionApp, CURLOPT_URL, $appURL);
				curl_setopt($connectionApp, CURLOPT_VERBOSE, 1);
				curl_setopt($connectionApp, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($connectionApp, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($connectionApp, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($connectionApp, CURLOPT_POST, 1);
				curl_setopt($connectionApp, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($connectionApp, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($connectionApp, CURLOPT_POSTREDIR, 3);
				
				$zip = new ZipArchive();
				$zipFilename = DIR_MEDIA."/temporary/".date("YmdHis").generateSalt("5", true, false, false).".zip";
				if($zip->open($zipFilename, ZipArchive::CREATE) !== TRUE){
					$output['status'] = false;
					$output['message'] = "Cannot create zip files";
					return $output;
				}
				foreach($listFiles AS $fileKey => $file){
					$zip->addFile(DIR_MEDIA."/temporary/$file", $file);
				}
				$zip->close();

				$postData = array(
					"api" => "dataconnector",
					"opt" => "queue_files",
					"data_connector" => $GLOBALS["siteSetting"]["accounting_system"]
				);
				if((version_compare(PHP_VERSION, '5.5') >= 0)){
					$postData['file'] = new CURLFile($zipFilename);
					curl_setopt($connectionApp, CURLOPT_SAFE_UPLOAD, true);
				}else{
					$postData['file'] = '@'.$zipFilename;
				}
				curl_setopt($connectionApp, CURLOPT_POSTFIELDS, $postData);
				$appResponse = curl_exec($connectionApp);
				if($appResponse){
					$response = json_decode($appResponse, true);
					if(isset($response) && $response['success'] == "1"){
						$output['status'] = true;
						$output['identifier'] = $response['identifier'];
					}else{
						$output['message'] = $response['message'];
					}
				}
				unlink($zipFilename);
			}
			return $output;
		}

		function removeQueueFiles($listFiles){
			foreach($listFiles AS $files){
				if(file_exists(DIR_MEDIA."/temporary/$files")){
					unlink(DIR_MEDIA."/temporary/$files");
				}
			}
		}

		function saveImportationLog($file, $identifier){
			$newData = array();
			$newData['type'] = $GLOBALS["siteSetting"]["accounting_system"];
			$newData['mode'] = $this->syncTable;
			$newData['status'] = 0;
			$newData['identifier'] = $identifier;
			$newData['file'] = implode(";", $file);
			if(isset($_SESSION['user_id'])){
				$newData['created_by'] = $_SESSION['user_id'];
			}
			$newData['created_date'] = date("Y-m-d H:i:s");
			return $this->objMainPDO->insert("cron_importation_log", $newData);
		}

		function getFilterSQL($filter, $exception = array()){
			$condition = "";
			foreach($filter AS $key => $value){
				if($GLOBALS["siteSetting"]["accounting_system"] == "emas" || $GLOBALS["siteSetting"]["accounting_system"] == "ubs"){
					$value['data']['value'] = mb_convert_encoding($value['data']['value'], $GLOBALS["siteSetting"]["emas_encoding"], "UTF-8");
				}
				switch($value['data']['type']){
					case 'string':
						if(in_array($value['field'], $exception)){break;}
						$condition .= " AND LOWER(".$value['field'].") LIKE '%".strtolower($value['data']['value'])."%' ";
					break;
					case 'boolean' : 
						if(in_array($value['field'], $exception)){break;}
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

		/*** EMAS Special Function - Start ***/
		function checkEMASGSTVersion(){
			$tableStructure = $this->objPDO->getTableStructure("icso");
			if(!empty($tableStructure) && in_array("taxcode", $tableStructure['column'])){
				$this->isGST = true;
			}
		}

		function customSyncAddress(){
			$output = array('success' => false, 'message' => 'unknown error');
			$i = 0;
			$generatedFile = array();
			
			$condition = "";
			$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM ".$this->syncTable." WHERE 1=1 $condition", array());
			if(!empty($total)){
				$totalRow = $total['total'];
			}else{
				$totalRow = 0;
			}
			$hasRecord = false;

			while($i <= $totalRow){
				$syncData = array();
				switch($GLOBALS["siteSetting"]["accounting_system"]){
					case "emas":
						$result = $this->objPDO->selectAll("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE 1=1 AND recno() > $i AND recno() <= ".($i+$this->syncBatch)." ORDER BY ".$this->syncColumnKey." ASC", array());
					break;
					case "autocount":
						$result = $this->objPDO->selectAll("SELECT * FROM (SELECT ".implode(",", $this->syncHeader).", ROW_NUMBER() OVER (ORDER BY ".$this->syncColumnKey." ASC) AS RowNumber FROM ".$this->syncTable." WHERE 1=1) AS A WHERE A.RowNumber > $i AND A.RowNumber <= ".($i+$this->syncBatch), array());
					break;
				}
				foreach($result AS $resultData){
					array_push($syncData, $this->formatSimplifiedJSONValue($resultData));
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

		function getHistoryPriceLiveAPI($itemNo, $customerNo){
			$output = array();
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
					$condition = " AND item_no = '$itemNo' ";
					if($customerNo != ""){
						$condition .= " AND custno = '$customerNo' ";
					}

					$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, array("condition" => $condition, "order" => "date DESC")), 0, 5);
					foreach($recNo AS $filterRecNo){
						$result = $this->objPDO->select("SELECT * FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array());
						$tmp = array();
						$tmp['date'] = $result['date'];
						$tmp['price'] = $result['price'];
						$tmp['quantity'] = $result['quantity'];
						$tmp['customer'] = $result['custno'];
						$tmp['unit'] = $result['u_measure'];
						array_push($output, $tmp);
					}
				break;
			}
			return $output;
		}
		/*** EMAS Special Function - End ***/

		/*** QNE Special Function - Start ***/
		function getQNEItemExtraFields(&$record){
			$category = $this->objPDO->select("SELECT categorycode FROM stockcategories WHERE id=:id", array("id" => $record['categoryid']));
			if(empty($category)){
				$record['category'] = "";
			}else{
				$record['category'] = $category['categorycode'];
			}

			$group = $this->objPDO->select("SELECT groupcode FROM stockgroups WHERE id=:id", array("id" => $record['groupid']));
			if(empty($group)){
				$record['group'] = "";
			}else{
				$record['group'] = $group['groupcode'];
			}

			$taxcode = $this->objPDO->select("SELECT taxcode FROM taxcodes WHERE id=:id", array("id" => $record['defaultinputtaxcodeid']));
			if(empty($taxcode)){
				$record['taxcode'] = "";
			}else{
				if($taxcode['taxcode'] == "SR"){
					$record['taxcode'] = "STAX";
				}else{
					$record['taxcode'] = $taxcode['taxcode'];
				}
			}

			$factor = $this->objPDO->select("SELECT TOP 1 rate FROM uoms WHERE stockid=:id", array("id" => $record['id']));
			if(empty($factor)){
				$record['factor'] = "";
			}else{
				$record['factor'] = $factor['rate'];
			}
		}

		function getQNECustomerExtraFields(&$record){
			$address = $this->objPDO->select("SELECT deliveryaddress1, deliveryaddress2, deliveryaddress3, deliveryaddress4 FROM deliveryaddresses WHERE debtorid=:id", array("id" => $record['id']));
			if(empty($address)){
				$record['deliveryaddress1'] = "";
				$record['deliveryaddress2'] = "";
				$record['deliveryaddress3'] = "";
				$record['deliveryaddress4'] = "";
			}else{
				$record['deliveryaddress1'] = $address['deliveryaddress1'];
				$record['deliveryaddress2'] = $address['deliveryaddress2'];
				$record['deliveryaddress3'] = $address['deliveryaddress3'];
				$record['deliveryaddress4'] = $address['deliveryaddress4'];
			}

			$salesperson = $this->objPDO->select("SELECT staffcode FROM salespersons WHERE id=:id", array("id" => $record['salespersonid']));
			if(empty($salesperson)){
				$record['agent_code'] = "";
			}else{
				$record['agent_code'] = $salesperson['staffcode'];
			}

			$currency = $this->objPDO->select("SELECT currencycode FROM currencies WHERE id=:id", array("id" => $record['currencyid']));
			if(empty($currency)){
				$record['currency_code'] = "";
			}else{
				$record['currency_code'] = $currency['currencycode'];
			}

			$credit = $this->objPDO->select("SELECT term FROM terms WHERE id=:id", array("id" => $record['termid']));
			if(empty($credit)){
				$record['credit_terms'] = "";
			}else{
				$record['credit_terms'] = $credit['term'];
			}

			$taxcode = $this->objPDO->select("SELECT taxcode FROM taxcodes WHERE id=:id", array("id" => $record['defaulttaxcodeid']));
			if(empty($taxcode)){
				$record['taxcode'] = "";
			}else{
				if($taxcode['taxcode'] == "SR"){
					$record['taxcode'] = "STAX";
				}else{
					$record['taxcode'] = $taxcode['taxcode'];
				}
			}
		}

		function getQNEDeliveryAddressExtraFields(&$record){
			$debtor = $this->objPDO->select("SELECT companycode FROM debtors WHERE id=:id", array("id" => $record['debtorid']));
			if(empty($debtor)){
				$record['debtor_no'] = "";
			}else{
				$record['debtor_no'] = $debtor['companycode'];
			}
		}
		/*** QNE Special Function - End ***/

		/*** AutoCount Special Function - Start ***/

		/*** AutoCount Special Function - End ***/

		/*** UBS Special Function - Start ***/

		/*** UBS Special Function - End ***/

		/*** Stock Function - Start ***/
		function listStocksField(){
			$output = array();
			$result = $this->objMainPDO->selectAll("SHOW COLUMNS FROM `stocks`", array());
			foreach($result AS $row){
				array_push($output, "'".$row['field']."'");
			}
			return $output;
		}

		function listStocks($condition = '', $start = 0, $limit = 0){
			$output = array();
			$total = $this->objMainPDO->select("SELECT COUNT(*) AS total FROM stocks WHERE 1=1 $condition", array());
			if(!empty($total)){
				$this->totalRow = $total['total'];
			}else{
				$this->totalRow = 0;
			}

			$output = $this->objMainPDO->selectAll("SELECT * FROM stocks WHERE 1=1 $condition LIMIT $start, $limit", array());
			return $output;
		}

		function listStocksLocBin($condition = '', $start = 0, $limit = 0){
			$output = array();
			$total = $this->objMainPDO->select("SELECT COUNT(*) AS total FROM stocks_locbin WHERE 1=1 $condition", array());
			if(!empty($total)){
				$this->totalRow = $total['total'];
			}else{
				$this->totalRow = 0;
			}

			$output = $this->objMainPDO->selectAll("SELECT * FROM stocks_locbin WHERE 1=1 $condition LIMIT $start, $limit", array());
			return $output;
		}

		function padEMASString($value, $totalDigit){
			$padString = "";
			$padString = str_pad($value, 24, " ");
			$padString = substr($padString, 0, 24);
			return $padString;
		}

		function calculateEMASStocks(){
			$i = 0;
			$stockBatch = 200;
			$this->objMainPDO->execute("TRUNCATE stocks", array());
			$this->objMainPDO->execute("TRUNCATE stocks_locbin", array());
			$totalItem = 0;
			$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM icitem", array());
			if(!empty($total)){
				$totalItem = $total['total'];
			}
			while($i <= $totalItem){
				$itemsData = $this->objPDO->selectAll("SELECT item_no, desc1, u_measure FROM icitem WHERE recno() > $i AND recno() <= ".($i+$stockBatch), array());
				foreach($itemsData AS $itemData){
					$stockList = $this->countEMASProductLocBinQuantity($itemData['item_no'], $itemData['u_measure'], date('Y-m-d'));
					foreach($stockList AS $stockData){
						if(is_array($stockData)){
							$newLocBinData = array();
							$newLocBinData['item_no'] = trim($itemData['item_no']);
							$newLocBinData['description'] = trim($itemData['desc1']);
							$newLocBinData['stock_date'] = date("Y-m-d H:i:s");
							$newLocBinData['quantity'] = trim($stockData['qty']);
							$newLocBinData['location'] = trim($stockData['location']);
							$newLocBinData['bin'] = $stockData['bin'];
							$this->objMainPDO->insert("stocks_locbin", $newLocBinData);
						}else{
							$newData = array();
							$newData['item_no'] = trim($itemData['item_no']);
							$newData['description'] = trim($itemData['desc1']);
							$newData['stock_date'] = date("Y-m-d H:i:s");
							$newData['quantity'] = $stockData;
							$this->objMainPDO->insert("stocks", $newData);
						}
					}
				}
				$i += $stockBatch;
			}
		}

		function countEMASProductLocBinQuantity($itemCode, $unitMeasure, $stockDate){
			$output = array();
			$total = 0;
			$result = $this->objPDO->selectAll("SELECT qty, location, bin FROM icbin WHERE item_no = '$itemCode' UNION SELECT 0, location, bin FROM ictran WHERE item_no = '$itemCode' UNION SELECT 0, toloc AS location, tobin AS bin FROM ictran WHERE item_no = '$itemCode'", array());
			if(!empty($result)){
				foreach($result AS $row => $data){
					if($data['location'] != "" || $data['bin'] != ""){
						$key = $this->getProductLocBinOutputKey($output, $data['location'], $data['bin']);
						if($key == ""){
							array_push($output, $data);
						}else{
							$output[$key]['qty'] += $data['qty'];
						}
					}
					$total += $data['qty'];
				}

				$altMeasure = array('unit1', 'unit2', 'unit3', 'unit4', 'unit5');
				$altFactor = array('factor1', 'factor2', 'factor3', 'factor4', 'factor5');

				$stockIn = 0;
				$stockOut = 0;
				$stockAdd = 0;
				$query = "SELECT ictran.type, ictran.qty, ictran.billno, ictran.u_measure, icitem.u_measure AS item_measure, icitem.unit1, icitem.unit2, icitem.unit3, icitem.unit4, icitem.unit5, icitem.factor1, icitem.factor2, icitem.factor3, icitem.factor4, icitem.factor5, ictran.qty1, ictran.u_measure1, icmast.ref, icmast.retr, ictran.toloc, ictran.date, ictran.location, ictran.bin, ictran.tobin ";
				$query .= "FROM ictran, icitem, icmast ";
				$query .= "WHERE ictran.type=icmast.type AND ictran.entry=icmast.entry AND ictran.item_no=icitem.item_no AND icmast.void <> 'Y' AND icmast.retr <> 'Y' AND ((ictran.item_no+DTOS(ictran.date)+ictran.sequence) LIKE '". $this->padEMASString($itemCode, 24)."%')";
				if($stockDate != ""){
					$query .= "AND ictran.date <= {^".$stockDate."} ";
				}
				$query .= " ORDER BY ictran.date";
				$result = $this->objPDO->selectAll($query, array());
				foreach($result AS $row => $data){
					if(($data['location'] !=  "" || $data['bin'] != "") && count($output) > 0){
						$key = $this->getProductLocBinOutputKey($output, $data['location'], $data['bin']);
						if($key != ""){
							$_stockIn = 0;
							$_stockOut = 0;
							$_stockAdd = 0;
							$_tempQty = 0;

							if($data['u_measure'] == $data['item_measure']){
								$_tempQty = $data['qty'];
							}else{
								$match = false;
								foreach($altMeasure AS $altK => $altV){
									if($data['u_measure'] == $data[$altV] && trim($data['u_measure']) != ''){
										$_tempQty = $data['qty']*$data[$altFactor[$altK]];
										$match = true;
									}
								}
								if($match == false){
									$_tempQty = $data['qty'];
								}
							}

							$_tempFreeQty = 0;
							if($data['qty1'] > 0){
								if($data['u_measure1'] == $data['item_measure']){
									$_tempFreeQty = $data['qty1'];
								}else{
									$match = false;
									foreach($altMeasure AS $altK => $altV){
										if($data['u_measure1'] == $data[$altV]){
											$_tempFreeQty = $data['qty1']*$data[$altFactor[$altK]];
											$match = true;
										}
									}
									if($match == false){
										$_tempFreeQty = $data['qty1'];
									}
								}
							}

							$_tempQty += $_tempFreeQty;
							$billType = strtolower(trim($data['type']));
							switch($billType){
								case 'do':
									if(trim($data['retr']) == ""){
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
									$_stockOut -= $_tempQty;
								break;
								case 're':
								case 'cn':
									$_stockIn += $_tempQty;
								break;
								case 'ad':
									$_stockAdd += $_tempQty;
								break;
								case 'tr':
									$_stockOut -= $_tempQty;
									if(trim($data['location']) != trim($data['toloc'] && trim($data['bin']) != trim($data['tobin']))){
										$key2 = $this->getProductLocBinOutputKey($output, $data['toloc'], $data['tobin']);
										if($key2 != ''){
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

					$tempQty = 0;
					if($data['u_measure'] == $data['item_measure']){
						$tempQty = $data['qty'];
					}else{
						$match = false;
						foreach($altMeasure AS $altK => $altV){
							if($data['u_measure'] == $data[$altV] && trim($data['u_measure']) != ''){
								$tempQty = $data['qty']*$data[$altFactor[$altK]];
								$match = true;
							}
						}
						if($match == false){
							$tempQty = $data['qty'];
						}
					}

					$tempFreeQty = 0;
					if($data['qty1'] > 0){
						if($data['u_measure1'] == $data['item_measure']){
							$tempFreeQty = $data['qty1'];
						}else{
							$match = false;
							foreach($altMeasure AS $altK => $altV){
								if($data['u_measure1'] == $data[$altV]){
									$tempFreeQty = $data['qty1']*$data[$altFactor[$altK]];
									$match = true;
								}
							}
							if($match == false){
								$tempFreeQty = $data['qty1'];
							}
						}
					}

					$tempQty += $tempFreeQty;
					$billType = strtolower(trim($data['type']));
					switch($billType){
						case 'do':
							if(trim($data['retr']) == ""){
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
							$stockOut -= $tempQty;
						break;
						case 're':
						case 'cn':
							$stockIn += $tempQty;
						break;
						case 'ad':
							$stockAdd += $tempQty;
						break;
						case 'tr':
							$stockIn += $tempQty;
							$stockOut -= $tempQty;
						break;
					}
				}
				$total += $stockIn + $stockOut + $stockAdd;
			}
			array_push($output, $total);
			return $output;
		}

		function getProductLocBinOutputKey($output, $location, $bin){
			$key = "";
			foreach($output AS $row => $data){
				if($data['location'] == $location && $data['bin'] == $bin){
					$key = $row;
				}
			}
			return $key;
		}

		function syncItemStocks(){
			$headerStocks = array();
			$result = $this->objMainPDO->selectAll("SHOW COLUMNS FROM stocks", array());
			foreach($result AS $row){
				array_push($headerStocks, $row['field']);
			}

			$totalStocks = 0;
			$total = $this->objMainPDO->select("SELECT COUNT(*) AS total FROM stocks", array());
			if(!empty($total)){
				$totalStocks = $total['total'];
			}

			$generatedFile = array();
			$i = 0;
			while($i <= $totalStocks){
				$syncData = array();
				$result = $this->objMainPDO->selectAll("SELECT * FROM stocks ORDER BY item_no ASC LIMIT $i, ".$this->syncBatch, array());
				foreach($result AS $resultData){
					array_push($syncData, $this->formatSimplifiedJSONValue($resultData));
				}
				$f = 0;
				$filename = date("YmdHis")."_".$f."_oz_stockscount.json";
				while(file_exists(DIR_MEDIA."/temporary/".$filename)){
					$f++;
					$filename = date("YmdHis")."_".$f."_oz_stockscount.json";
				}
				$file = fopen(DIR_MEDIA."/temporary/".$filename, 'w');
				fwrite($file, json_encode(array("header" => $headerStocks, "purge" => 0, "data" => $syncData)));
				fclose($file);
				array_push($generatedFile, $filename);
				$i += $this->syncBatch;
			}
			if(!empty($generatedFile)){
				$this->syncTable = "stockscount";
				$queueStatus = $this->sendQueueFiles($generatedFile);
				if($queueStatus['status'] == true){
					$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
				}
				$this->removeQueueFiles($generatedFile);
			}

			$totalStocksLocBin = 0;
			$total = $this->objMainPDO->select("SELECT COUNT(*) AS total FROM stocks_locbin", array());
			if(!empty($total)){
				$totalStocksLocBin = $total['total'];
			}
			if($totalStocksLocBin > 0){
				$headerStocksLocBin = array();
				$result = $this->objMainPDO->selectAll("SHOW COLUMNS FROM stocks_locbin", array());
				foreach($result AS $row){
					array_push($headerStocksLocBin, $row['field']);
				}

				$generatedFile = array();
				$i = 0;
				while($i <= $totalStocksLocBin){
					$syncData = array();
					$result = $this->objMainPDO->selectAll("SELECT * FROM stocks_locbin ORDER BY item_no ASC LIMIT $i, ".$this->syncBatch, array());
					foreach($result AS $resultData){
						array_push($syncData, $this->formatSimplifiedJSONValue($resultData));
					}
					$f = 0;
					$filename = date("YmdHis")."_".$f."_oz_stockslocbincount.json";
					while(file_exists(DIR_MEDIA."/temporary/".$filename)){
						$f++;
						$filename = date("YmdHis")."_".$f."_oz_stockslocbincount.json";
					}
					$file = fopen(DIR_MEDIA."/temporary/".$filename, 'w');
					fwrite($file, json_encode(array("header" => $headerStocksLocBin, "purge" => 0, "data" => $syncData)));
					fclose($file);
					array_push($generatedFile, $filename);
					$i += $this->syncBatch;
				}
				if(!empty($generatedFile)){
					$this->syncTable = "stockslocbincount";
					$queueStatus = $this->sendQueueFiles($generatedFile);
					if($queueStatus['status'] == true){
						$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
					}
					$this->removeQueueFiles($generatedFile);
				}
			}
		}

		function processStocksUploadFile($file){
			ini_set("memory_limit","-1");
			ini_set('max_execution_time', 0);
			set_time_limit(0);
			$output = array("success" => false, "message" => "Cannot process uploaded file.");
			$tempArray = explode(".", $file["name"]);
			$extension = strtolower(end($tempArray));
			if($extension != "xlsx"){
				$output['message'] = "Invalid file extension. Please only upload excel file (.xlsx)";
			}else{
				require_once DIR_PLUGINS.'/php/PHPExcel.php';
				$objReader = PHPExcel_IOFactory::createReader("Excel2007");
				if($objReader->canRead($file["tmp_name"])){
					$this->objMainPDO->execute("TRUNCATE stocks", array());
					$this->objMainPDO->execute("TRUNCATE stocks_locbin", array());

					$objReader->setReadDataOnly(true);
					$objPHPExcel = $objReader->load($file["tmp_name"]);
					$objWorksheet = $objPHPExcel->getActiveSheet();
					$objWorksheet->getProtection()->setSheet(true);

					$highestRow = $objWorksheet->getHighestRow();
					$highestColumn = $objWorksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$itemCol = "";
					$descCol = "";
					$qtyCol = "";
					if($GLOBALS["siteSetting"]["accounting_system"] == "ubs"){
						for($i=0; $i<$highestColumnIndex; $i++){
							$col = PHPExcel_Cell::stringFromColumnIndex($i);
							$cellValue = strtolower(trim($objPHPExcel->getActiveSheet()->getCell($col."4")->getValue()));
							if($cellValue == "item no."){
								$itemCol = $col;
							}else if($cellValue == "item description"){
								$descCol = $col;
							}else if($cellValue == "qty"){
								$qtyCol = $col;
							}
						}

						if($itemCol == "" || $qtyCol == "" || $descCol == ""){
							$output['message'] = "Invalid UBS stock report format.";
							return $output;
						}

						$foundLocation = false;
						$locLabel = "";
						for($row=5; $row<$highestRow; $row++){
							if($foundLocation){
								$itemValue = trim($objPHPExcel->getActiveSheet()->getCell($itemCol.$row)->getValue());
								if($itemValue == ""){
									$foundLocation = false;
								}else{
									$qtyValue = trim($objPHPExcel->getActiveSheet()->getCell($qtyCol.$row)->getValue());
									$descValue = trim($objPHPExcel->getActiveSheet()->getCell($descCol.$row)->getValue());
								
									$newData = array();
									$newData['item_no'] = $itemValue;
									$newData['description'] = $descValue;
									$newData['quantity'] = $qtyValue;
									$newData['location'] = $locLabel;
									$newData['stock_date'] = date("Y-m-d H:i:s");
									if($this->objMainPDO->insert("stocks_locbin", $newData)){
										unset($newData['location']);
										if(!$this->objMainPDO->insert("stocks", $newData)){
											$output['message'] = "Cannot insert into stocks for excel $row";
											return $output;
										}
									}else{
										$output['message'] = "Cannot insert into stocks by location bin for excel $row";
										return $output;
									}
								}
							}else{
								$cellValue = trim($objPHPExcel->getActiveSheet()->getCell("A".$row)->getValue());
								if(substr(strtolower($cellValue), 0, 8) == "location"){
									$foundLocation = true;
									$tmpLoc = explode(":", $cellValue);
									$locLabel = $tmpLoc[1];
								}
							}
						}
						$this->objMainPDO->execute("INSERT INTO stocks (quantity, item_no, description, stock_date) SELECT SUM(quantity) AS 'quantity', item_no, description, stock_date FROM stocks GROUP BY item_no", array());
						$this->objMainPDO->execute("DELETE a FROM stocks a, stocks b WHERE a.item_no = b.item_no AND a.id < b.id", array());
					}else if($GLOBALS["siteSetting"]["accounting_system"] == "qne"){
						for($i=0; $i<$highestColumnIndex; $i++){
							$col = PHPExcel_Cell::stringFromColumnIndex($i);
							$cellValue = strtolower(trim($objPHPExcel->getActiveSheet()->getCell($col."4")->getValue()));
							if($cellValue == "code"){
								$itemCol = $col;
							}else if($cellValue == "stock name"){
								$descCol = $col;
							}else if($cellValue == "available"){
								$qtyCol = $col;
							}
						}

						if($itemCol == "" || $qtyCol == "" || $descCol == ""){
							$output['message'] = "Invalid QNE stock report format.";
							return $output;
						}

						for($row=6; $row<$highestRow; $row++){
							$colAValue = trim($objPHPExcel->getActiveSheet()->getCell("A".$row)->getValue());
							if($colAValue == ""){
								break;
							}else{
								$itemValue = trim($objPHPExcel->getActiveSheet()->getCell($itemCol.$row)->getValue());
								$qtyValue = trim($objPHPExcel->getActiveSheet()->getCell($qtyCol.$row)->getValue());
								$descValue = trim($objPHPExcel->getActiveSheet()->getCell($descCol.$row)->getValue());

								$newData = array();
								$newData['item_no'] = $itemValue;
								$newData['description'] = $descValue;
								$newData['quantity'] = $qtyValue;
								$newData['stock_date'] = date("Y-m-d H:i:s");
								if(!$this->objMainPDO->insert("stocks", $newData)){
									$output['message'] = "Cannot insert into stocks for excel $row";
									return $output;
								}
							}
						}
					}else if($GLOBALS["siteSetting"]["accounting_system"] == "autocount"){
						$locCol = "";
						for($i=0; $i<$highestColumnIndex; $i++){
							$col = PHPExcel_Cell::stringFromColumnIndex($i);
							$cellValue = strtolower(trim($objPHPExcel->getActiveSheet()->getCell($col."15")->getValue()));
							if($cellValue == "item code"){
								$itemCol = $col;
							}else if($cellValue == "description"){
								$col = PHPExcel_Cell::stringFromColumnIndex($i-1);
								$descCol = $col;
							}else if($cellValue == "quantity"){
								$qtyCol = $col;
							}else if($cellValue == "location"){
								$col = PHPExcel_Cell::stringFromColumnIndex($i+1);
								$locCol = $col;
							}
						}

						if($itemCol == "" || $qtyCol == "" || $descCol == "" || $locCol == ""){
							$output['message'] = "Invalid AutoCount stock report format.";
							return $output;
						}

						for($row=21; $row<$highestRow; $row+=3){
							$colAValue = trim($objPHPExcel->getActiveSheet()->getCell("A".$row)->getValue());
							if($colAValue == ""){
								break;
							}else{
								$itemValue = trim($objPHPExcel->getActiveSheet()->getCell($itemCol.$row)->getValue());
								$qtyValue = trim($objPHPExcel->getActiveSheet()->getCell($qtyCol.$row)->getValue());
								$descValue = trim($objPHPExcel->getActiveSheet()->getCell($descCol.$row)->getValue());
								$locValue = trim($objPHPExcel->getActiveSheet()->getCell($locCol.$row)->getValue());

								$newData = array();
								$newData['item_no'] = $itemValue;
								$newData['description'] = $descValue;
								$newData['quantity'] = $qtyValue;
								$newData['location'] = $locValue;
								$newData['stock_date'] = date("Y-m-d H:i:s");
								if($this->objMainPDO->insert("stocks_locbin", $newData)){
									unset($newData['location']);
									if(!$this->objMainPDO->insert("stocks", $newData)){
										$output['message'] = "Cannot insert into stocks for excel $row";
										return $output;
									}
								}else{
									$output['message'] = "Cannot insert into stocks by location bin for excel $row";
									return $output;
								}
							}
						}
						$this->objMainPDO->execute("INSERT INTO stocks (quantity, item_no, description, stock_date) SELECT SUM(quantity) AS 'quantity', item_no, description, stock_date FROM stocks GROUP BY item_no", array());
						$this->objMainPDO->execute("DELETE a FROM stocks a, stocks b WHERE a.item_no = b.item_no AND a.id < b.id", array());
					}
					$output['success'] = true;
				}else{
					$output['message'] = "File cannot be read. Please try again.";
				}
			}
			return $output;
		}
		/*** Stock Function - End ***/
		
		/*** Item Images - Start ***/
		function syncItemImageNew(){
			$this->syncBatch = 15;
			$output = array('success' => false, 'message' => 'unknown error');
			
			$i = 0;
			$condition = "";
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
					$condition = "AND (pictpath != '' OR pictpath2 != '' OR pictpath3 != '' OR pictpath4 != '')";
				break;
				case "ubs":
					$condition = "AND photo != ''";
				break;
				case "qne":
					$condition = "AND picture != ''";
				break;
				case "autocount":
					$condition .= " AND imagefilename != '' ";
				break;
			}
			$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM ".$this->syncTable." WHERE 1=1 $condition", array());
			if(!empty($total)){
				$totalRow = $total['total'];
			}else{
				$totalRow = 0;
			}
			$hasRecord = false;
			$generatedFile = array();
			while($i <= $totalRow){
				$syncData = array();
				switch($GLOBALS["siteSetting"]["accounting_system"]){
					case "emas":
					case "ubs":
						$result = array();
						$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, array("condition" => $condition, "order" => $this->syncColumnKey." ASC")), $i, ($i+$this->syncBatch));
						foreach($recNo AS $filterRecNo){
							array_push($result, $this->objPDO->select("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array()));
						}
					break;
					case "qne":
					case "autocount":
						$result = $this->objPDO->selectAll("SELECT * FROM (SELECT ".implode(",", $this->syncHeader).", ROW_NUMBER() OVER (ORDER BY ".$this->syncColumnKey." ASC) AS RowNumber FROM ".$this->syncTable." WHERE 1=1 $condition) AS A WHERE A.RowNumber > $i AND A.RowNumber <= ".($i+$this->syncBatch), array());
					break;
				}
				foreach($result AS $resultData){
					$newData = $this->formatItemImageQueryResult($resultData);
					foreach($newData AS $imgData){
						$syncRecord = $this->getItemImageSyncRecord($imgData);
						$newSyncRecord = $imgData;
						if(empty($syncRecord)){
							$this->saveItemImageSyncRecord($newSyncRecord);
							array_push($syncData, $this->formatSimplifiedJSONValue($imgData));
						}else if(trim($syncRecord['path']) != trim($imgData['path']) || $syncRecord['size'] != $imgData['size'] || ($syncRecord['modified_date'] != $imgData['modified_date'] && $GLOBALS["siteSetting"]["accounting_system"] != "qne")){
							$newSyncRecord['id'] = $syncRecord['id'];
							$this->updateItemImageSyncRecord($newSyncRecord);
							array_push($syncData, $this->formatSimplifiedJSONValue($imgData));
						}else{
							if($GLOBALS["siteSetting"]["accounting_system"] == "qne"){
								unlink($imgData['path']);
							}
						}
					}
				}
				if(!empty($syncData)){
					$hasRecord = true;
					array_push($generatedFile, $this->createSyncItemImageJSONData($syncData, false));
				}
				$i += $this->syncBatch;
			}
			$output['success'] = true;
			if($hasRecord){
				$queueStatus = $this->sendItemImageQueueFiles($generatedFile);
				if($queueStatus['status'] == true){
					$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
					$output['success'] = true;
					$output['message'] = "Sync files have been prepared.";
				}else{
					$output['message'] = $queueStatus['message'];
				}
				$this->removeItemImageQueueFiles($generatedFile);
			}else{
				$output['message'] = "There is no any new records.";
			}
			return $output;
		}
		
		function syncItemImageSelection($rememberAll, $rememberSelection, $filters){
			$this->syncBatch = 15;
			$output = array('success' => false, 'message' => 'unknown error');
			
			$i = 0;
			$generatedFile = array();
			$condition = "";
			foreach($filters AS $filter){
				if($filter['data']['type'] == "string"){
					$condition .= " AND lower(".$filter['field'].") LIKE '%".strtolower($filter['data']['value'])."%' ";
				}
			}
			
			if($rememberAll == "true"){
				$totalQuery = "";
				switch($GLOBALS["siteSetting"]["accounting_system"]){
					case "emas":
						$condition = "AND (pictpath != '' OR pictpath2 != '' OR pictpath3 != '' OR pictpath4 != '') $condition";
					break;
					case "ubs":
						$condition = "AND photo != ''";
					break;
					case "qne":
						$condition = "AND picture != ''";
					break;
					case "autocount":
						$condition .= " AND imagefilename != '' ";
					break;
				}
				
				$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM ".$this->syncTable." WHERE 1=1 $condition", array());
				if(!empty($total)){
					$totalRow = $total['total'];
				}else{
					$totalRow = 0;
				}
				while($i <= $totalRow){
					$syncData = array();
					switch($GLOBALS["siteSetting"]["accounting_system"]){
						case "emas":
						case "ubs":
							$result = array();
							$recNo = $this->objPDO->selectFoxProRecNoLimit($this->objPDO->constructFoxProRecNoQuery($this->syncTable, array("condition" => $condition, "order" => $this->syncColumnKey." ASC")), $i, ($i+$this->syncBatch));
							foreach($recNo AS $filterRecNo){
								array_push($result, $this->objPDO->select("SELECT ".$this->syncColumnKey." FROM ".$this->syncTable." WHERE recno() = $filterRecNo", array()));
							}
						break;
						case "qne":
						case "autocount":
							$result = $this->objPDO->selectAll("SELECT * FROM (SELECT ".$this->syncColumnKey.", ROW_NUMBER() OVER (ORDER BY ".$this->syncColumnKey." ASC) AS RowNumber FROM ".$this->syncTable." WHERE 1=1 $condition) AS A WHERE A.RowNumber > $i AND A.RowNumber <= ".($i+$this->syncBatch), array());
						break;
					}
					foreach($result AS $resultData){
						if(!in_array(trim($resultData[$this->syncColumnKey]), $rememberSelection)){
							$newData = $this->objPDO->select("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE ".$this->syncColumnKey." = :".$this->syncColumnKey, array($this->syncColumnKey => $resultData[$this->syncColumnKey]));
							$newData = $this->formatItemImageQueryResult($newData);
							foreach($newData AS $imgData){
								array_push($syncData, $this->formatSimplifiedJSONValue($imgData));
								$syncRecord = $this->getItemImageSyncRecord($imgData);
								$newSyncRecord = $imgData;
								if(empty($syncRecord)){
									$this->saveItemImageSyncRecord($newSyncRecord);
								}else{
									$newSyncRecord['id'] = $syncRecord['id'];
									$this->updateItemImageSyncRecord($newSyncRecord);
								}
							}
						}
					}
					if(!empty($syncData)){
						array_push($generatedFile, $this->createSyncItemImageJSONData($syncData));
					}
					$i += $this->syncBatch;
				}
			}else{
				$totalRow = count($rememberSelection);
				while($i <= $totalRow){
					$syncData = array();
					for($j=$i; $j<($i+$this->syncBatch); $j++){
						if(isset($rememberSelection[$j])){
							$newData = $this->objPDO->select("SELECT ".implode(",", $this->syncHeader)." FROM ".$this->syncTable." WHERE ".$this->syncColumnKey." = :".$this->syncColumnKey, array($this->syncColumnKey => $rememberSelection[$j]));
							$newData = $this->formatItemImageQueryResult($newData);
							foreach($newData AS $imgData){
								array_push($syncData, $this->formatSimplifiedJSONValue($imgData));
								$syncRecord = $this->getItemImageSyncRecord($imgData);
								$newSyncRecord = $imgData;
								if(empty($syncRecord)){
									$this->saveItemImageSyncRecord($newSyncRecord);
								}else{
									$newSyncRecord['id'] = $syncRecord['id'];
									$this->updateItemImageSyncRecord($newSyncRecord);
								}
							}
						}else{
							break;
						}
					}
					if(!empty($syncData)){
						array_push($generatedFile, $this->createSyncItemImageJSONData($syncData));
					}
					$i += $this->syncBatch;
				}
			}
			if(empty($generatedFile)){
				$output['message'] = "There is no any sync file generated.";
			}else{
				$queueStatus = $this->sendItemImageQueueFiles($generatedFile);
				if($queueStatus['status'] == true){
					$this->saveImportationLog($generatedFile, $queueStatus['identifier']);
					$output['success'] = true;
					$output['message'] = "Sync files have been prepared.";
				}else{
					$output['message'] = $queueStatus['message'];
				}
				$this->removeItemImageQueueFiles($generatedFile);
			}
			return $output;
		}
		
		function sendItemImageQueueFiles($listFiles){
			$output = array("status" => false, "message" => "General error on cURL. Please contact administrator.");
			$appURL = getAppURL();
			if($appURL == ""){
				$output['message'] = "Cannot get APP URL";
			}else{
				$connectionApp = curl_init();
				curl_setopt($connectionApp, CURLOPT_URL, $appURL);
				curl_setopt($connectionApp, CURLOPT_VERBOSE, 1);
				curl_setopt($connectionApp, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($connectionApp, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($connectionApp, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($connectionApp, CURLOPT_POST, 1);
				curl_setopt($connectionApp, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($connectionApp, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($connectionApp, CURLOPT_POSTREDIR, 3);
				
				$zip = new ZipArchive();
				$zipFilename = DIR_MEDIA."/temporary/".date("YmdHis").generateSalt("5", true, false, false).".zip";
				if($zip->open($zipFilename, ZipArchive::CREATE) !== TRUE){
					$output['status'] = false;
					$output['message'] = "Cannot create zip files";
					return $output;
				}
				foreach($listFiles AS $fileKey => $file){
					$zip->addFile(DIR_MEDIA."/temporary/$file", $file);
					$folderName = str_replace(".json", "", $file);
					$folderPath = DIR_MEDIA."/temporary/".$folderName;
					zipFolder($zip, $folderPath, $folderName);
				}
				$zip->close();
				
				$postData = array(
					"api" => "dataconnector",
					"opt" => "queue_files",
					"data_connector" => $GLOBALS["siteSetting"]["accounting_system"]
				);
				if((version_compare(PHP_VERSION, '5.5') >= 0)){
					$postData['file'] = new CURLFile($zipFilename);
					curl_setopt($connectionApp, CURLOPT_SAFE_UPLOAD, true);
				}else{
					$postData['file'] = '@'.$zipFilename;
				}
				curl_setopt($connectionApp, CURLOPT_POSTFIELDS, $postData);
				$appResponse = curl_exec($connectionApp);
				if($appResponse){
					$response = json_decode($appResponse, true);
					if(isset($response) && $response['success'] == "1"){
						$output['status'] = true;
						$output['identifier'] = $response['identifier'];
					}else{
						$output['message'] = $response['message'];
					}
				}
				unlink($zipFilename);
			}
			return $output;
		}
		
		function removeItemImageQueueFiles($listFiles){
			foreach($listFiles AS $files){
				if(file_exists(DIR_MEDIA."/temporary/$files")){
					unlink(DIR_MEDIA."/temporary/$files");
				}
				$folderName = str_replace(".json", "", $files);
				$folderPath = DIR_MEDIA."/temporary/".$folderName;
				if(file_exists($folderPath)){
					rrmdir($folderPath);
				}
			}
		}
		
		function createSyncItemImageJSONData($syncData){
			$allowedExts = array("jpg", "jpeg", "png");
			
			$f = 0;
			$fileName = date("YmdHis")."_".$f."_oz_itemimages.json";
			while(file_exists(DIR_MEDIA."/temporary/".$fileName)){
				$f++;
				$fileName = date("YmdHis")."_".$f."_oz_itemimages.json";
			}
			$folderName = str_replace(".json", "", $fileName);
			$folderPath = DIR_MEDIA."/temporary/".$folderName;
			if(!file_exists($folderPath)){
				mkdir($folderPath, 0777);
			}

			foreach($syncData AS $key => $data){
				mkdir($folderPath."/".$key, 0777);
				if($data[1] != ""){
					$tempArray = explode(".", $data[1]);	
					$extension = strtolower(end($tempArray));
					if(in_array($extension, $allowedExts) && file_exists($data[1])){
						$tmp = explode("\\", $data[1]);
						$imgName = end($tmp);
						copy($data[1], $folderPath."/".$key."/".$imgName);
						if($GLOBALS["siteSetting"]["accounting_system"] == "qne"){
							unlink($data[1]);
						}
						$data[1] = $imgName;
					}else{
						$data[1] = "";
					}
				}
				$syncData[$key] = $data;
			}
			
			$itemImageSyncHeader = array("item_no", "img", "size", "extra");
			$jsonFile = array("header" => $itemImageSyncHeader, "purge" => false, "data" => $syncData);
			$file = fopen(DIR_MEDIA."/temporary/".$fileName, 'w');
			fwrite($file, json_encode($jsonFile));
			fclose($file);
			return $fileName;
		}
		
		function formatItemImageQueryResult($data){
			$output = array();
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
					for($i=1; $i<=4; $i++){
						$tmpName = 'pictpath';
						if($i != 1){
							$tmpName .= $i;
						}
						if($data[$tmpName] != "" && file_exists($data[$tmpName])){
							$tmp = array("item_no" => $data['item_no'], "path" => trim($data[$tmpName]), "size" => filesize($data[$tmpName]), "extra" => $i, "modified_date" => date("Y-m-d H:i:s", filemtime($data[$tmpName])));
							array_push($output, $tmp);
						}
					}
				break;
				case "ubs":
					if($data['photo'] != "" && file_exists($data['photo'])){
						$tmp = array("item_no" => $data['itemno'], "path" => trim($data['photo']), "size" => filesize($data['photo']), "extra" => 0, "modified_date" => date("Y-m-d H:i:s", filemtime($data['photo'])));
						array_push($output, $tmp);
					}
				break;
				case "qne":
					if($data['picture'] != ""){
						$tmpName = str_replace('/', "", $data['stockcode']);
						if(substr($data['picture'], 0, 6) == "FFD8FF"){
							$tmpPath = DIR_MEDIA."/temporary/".$tmpName.".jpg";
						}else if(substr($data['picture'], 0, 16) == "89504E470D0A1A0A"){
							$tmpPath = DIR_MEDIA."/temporary/".$tmpName.".png";
						}else{
							break;
						}
						$tmpPath = str_replace('/', "\\", $tmpPath);
						file_put_contents($tmpPath, pack("H".strlen($data['picture']), $data['picture']));
						$tmp = array("item_no" => $data['stockcode'], "path" => trim($tmpPath), "size" => filesize($tmpPath), "extra" => 0, "modified_date" => date("Y-m-d H:i:s", filemtime($tmpPath)));
						array_push($output, $tmp);
					}
				break;
				case "autocount":
					if($data['imagefilename'] != "" && file_exists($data['imagefilename'])){
						$tmp = array("item_no" => $data['itemcode'], "path" => trim($data['imagefilename']), "size" => filesize($data['imagefilename']), "extra" => 0, "modified_date" => date("Y-m-d H:i:s", filemtime($data['imagefilename'])));
						array_push($output, $tmp);
					}
				break;
			}
			return $output;
		}
		
		function getItemImageSyncRecord($data){
			if($GLOBALS["siteSetting"]["accounting_system"] == "emas"){
				$output = $this->objMainPDO->select("SELECT * FROM item_images WHERE item_no =:item_no AND extra=:extra ", array("item_no" => $data['item_no'], "extra" => $data['extra']));
			}else{
				$output = $this->objMainPDO->select("SELECT * FROM item_images WHERE item_no =:item_no AND size=:size AND path = :path", array("path" => $data['path'], "item_no" => $data['item_no'], "size" => $data['size']));
			}
			return $output;
		}
		
		function saveItemImageSyncRecord($data){
			return $this->objMainPDO->insert("item_images", $data);
		}

		function updateItemImageSyncRecord($data){
			return $this->objMainPDO->update("item_images", $data, " AND id=:id", array("id" => $data['id']));
		}
		
		function truncateItemImageSyncRecord(){
			return $this->objMainPDO->execute("TRUNCATE item_images", array());
		}
		/*** Item Images - End ***/
	}
?>