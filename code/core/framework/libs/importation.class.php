<?php

	class Importation{
		var $db;
		var $baseTable;
		var $ruleTable;
		var $selTable;
		
		var $mode = array(
			"navi_csv" => "NAVITAS CSV",
			"oz_excel" => "Oz Excel"
		);
		
		function Importation($db, $baseTable){
			$this->db = $db;
			$this->baseTable = $baseTable;
			$this->ruleTable = $this->baseTable.'_rule';
			$this->selTable = $this->baseTable.'_selection';
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getMode(){
			return $this->mode;
		}
		
		function removeMode($array){
			foreach($array AS $value){
				if(isset($this->mode[$value])){unset($this->mode[$value]);}
			}
		}
		
		function listBaseField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `".$this->baseTable."` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'mode_format'");
			array_push($output, "'init_by_format'");
			array_push($output, "'step1_by_format'");
			array_push($output, "'step2_by_format'");
			array_push($output, "'step3_by_format'");
			array_push($output, "'enc_id'");
			return $output;
		}
		
		function listRuleField(){
			$output = array();
			$sql = "SHOW COLUMNS FROM `".$this->ruleTable."` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			return $output;
		}
		
		function listTempField($table){
			$output = array();
			$sql = "SHOW COLUMNS FROM `".$table."` WHERE `Field` != 'id'";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}
			return $output;
		}
		
		function getComboSource($table){
			$output = array();
			$sql = "SHOW COLUMNS FROM `".$table."` ";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if(strtolower($result['Field']) != 'id'){
					$temp = array();
					$temp['display'] = ucfirst($result['Field']);
					$temp['display'] = str_replace("_", " ", $temp['display']);
					$temp['value'] = strtolower($result['Field']);
					array_push($output, $temp);
				}
			}
			return $output;
		}
		
		function listImportationBase($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', $this->baseTable, $condition);
			$sql = "SELECT * FROM `".$this->baseTable."` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); $result['raw'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					$this->formatBaseImportationData($output[$key]);
				}
			}
			return $output;
		}
		
		function listImportationRule($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', $this->ruleTable, $condition);
			$sql = "SELECT * FROM `".$this->ruleTable."` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); $result['raw'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					getUserCreateModify($output[$key], $value['id']);
				}
			}
			return $output;
		}
		
		function listImportationSource($table, $condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', $table, $condition);
			$sql = "SELECT * FROM `".$table."` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); $result['raw'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			return $output;
		}
		
		function listImportationSelection($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', $this->selTable, $condition);
			$sql = "SELECT * FROM `".$this->selTable."` WHERE 1=1 ";
			if($condition != ""){
				$sql .= $condition;
			}
			if($start != '' && $limit != ''){
				$sql .= " LIMIT ".$start.", ".$limit;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				if($encrypt){$result['enc_id'] = rawurlencode(encryption($result['id'], $salt, true)); $result['raw'] = encryption($result['id'], $salt, true);}else{$result['enc_id'] = '';}
				array_push($output, $result);
			}
			if($format){
				foreach($output AS $key => $value){
					$rawData = json_decode($value['raw_data'], true);
					foreach($rawData AS $tKey => $tValue){
						if($tKey != 'id'){
							$output[$key][$tKey] = $tValue;
						}
					}
				}
			}
			return $output;
		}
		
		function formatBaseImportationData(&$input){
			$iteration = array('init', 'step1', 'step2', 'step3');
			$input['mode_format'] = $this->mode[$input['mode']];
			foreach($iteration AS $value){
				if(isset($input[$value.'_by'])){
					$data = getUserSpecificField($input[$value.'_by'], "`first_name`, `last_name`, `username`");
					if(!empty($data)){
						$input[$value.'_by_format'] = $data['first_name'].' '.$data['last_name'].'('.$data['username'].')';
					}
			
					if(isset($input[$value.'_date']) && $input[$value.'_date'] != "0000-00-00 00:00:00"){
						$date = new DateTime($input[$value.'_date']);
						$input[$value.'_date'] = $date->format('d/m/Y H:i:s');
					}else{
						$input[$value.'_date'] = "";
					}
				}
			}
		}
		
		function formatMode($value){
			return $this->mode[$value];
		}
		
		function getImportationModeById($id){
			$output = "";
			$sql = "SELECT `mode` FROM `".$this->baseTable."` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['mode'];
			}
			return $output;
		}
		
		function getImportationRuleIdByBaseId($id){
			$output = "";
			$sql = "SELECT `id` FROM `".$this->baseTable."` WHERE `importation_id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['id'];
			}
			return $output;
		}
		
		function getBaseData($id){
			$output = array();
			$sql = "SELECT * FROM `".$this->baseTable."` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			if(!empty($output)){
				$this->formatBaseImportationData($output);
			}
			return $output;
		}
		
		function getRuleData($id){
			$output = array();
			$sql = "SELECT * FROM `".$this->ruleTable."` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function getSelectionData($id){
			$output = array();
			$sql = "SELECT * FROM `".$this->selTable."` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function getTempData($id, $table){
			$output = array();
			$sql = "SELECT * FROM `".$table."` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function getRuleLabel($combo, $value){
			$output = "";
			foreach($combo AS $val){
				if($val['value'] == $value){
					$output = $val['display'];
				}
			}
			return $output;
		}
		
		function checkDuplicateRuleExist($id, $parent, $target){
			$sql = "SELECT * FROM `".$this->ruleTable."` WHERE `target`='".$target."' AND `importation_id`='".$parent."'";
			if($id != ''){
				$sql .= " AND `id` != '".$id."'";
			}
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkDuplicateSelectionExist($id, $parent){
			$sql = "SELECT * FROM `".$this->selTable."` WHERE `importation_id`='".$parent."' AND `selection_id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkImportationExist($type, $id){
			if($type == 'rule'){
				$table = $this->ruleTable;
			}else if($type == 'selection'){
				$table = $this->selTable;
			}else{
				$table = $this->baseTable;
			}
			$sql = "SELECT * FROM `".$table."` WHERE `id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function checkImportationSelectionEmpty($id){
			$sql = "SELECT * FROM `".$this->selTable."` WHERE `importation_id`='".$id."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return false;
			}else{
				return true;
			}
		}
		
		function countExludeList($id){
			$output = 0;
			$output = $this->db->countRow('id', $this->selTable, " AND `importation_id`='".$id."' ");
			return $output;
		}
		
		function deleteImportation($type, $id){
			if($type == 'rule'){
				$table = $this->ruleTable;
			}else if($type == 'selection'){
				$table = $this->selTable;
			}else{
				$table = $this->baseTable;
			}
			if($this->db->delete($table, "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function cleanUpImportationFile($repo, $ori, $source, $log){
			$dir = DIR_MEDIA."/importation/".$repo;
			$delOri = $dir."/".$ori;
			$delSource = $dir."/".$source;
			$delLog = $dir."/".$log;
			if($ori != '' && file_exists($delOri)){
				unlink($delOri);
			}
			if($source != '' && file_exists($delSource)){
				unlink($delSource);
			}
			if($log != '' && file_exists($delLog)){
				unlink($delLog);
			}
		}
		
		function dropTempTable($table){
			if($table != ""){
				$sql = "DROP TABLE IF EXISTS `".MY_DB_DATABASE."`.`".$table."`";
				$this->db->query($sql);
			}
		}
		
		function deleteImportationRuleByBaseId($id){
			if($this->db->delete($this->ruleTable, "`importation_id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function deleteImportationSelectionByBaseId($id){
			if($this->db->delete($this->selTable, "`importation_id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveImportation($type, $data){
			if($type == 'rule'){
				$table = $this->ruleTable;
			}else if($type == 'selection'){
				$table = $this->selTable;
			}else{
				$table = $this->baseTable;
			}
			if($this->db->insert($table, $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateImportation($type, $data){
			if($type == 'rule'){
				$table = $this->ruleTable;
			}else if($type == 'selection'){
				$table = $this->selTable;
			}else{
				$table = $this->baseTable;
			}
			if($this->db->update($table, $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function listRepository($repo, $ext){
			$output = array();
			$dir = DIR_MEDIA."/importation/".$repo."/repository";
			if($handle = opendir($dir)){
				while(false !== ($entry = readdir($handle))){
					if ($entry != "." && $entry != ".."){
						foreach($ext AS $value){
							if(strpos($entry, $value,1)){
								array_push($output, $entry);
								break;
							}
						}
					}
				}
				closedir($handle);
			}
			return $output;
		}
		
		function moveRepositoryFile($repo, $file, $ext){
			$output = array('status' => false, 'message' => 'Unable to read repository file.', 'filename' => '');
			$dir = DIR_MEDIA."/importation/".$repo."/repository";
			$target = $dir."/".$file;
		
			if(file_exists($target)){
				$targetExt = strtolower(".".pathinfo($target, PATHINFO_EXTENSION));
				if(in_array($targetExt, $ext)){
					$path =  DIR_MEDIA."/importation/".$repo."/";
					$filename = $file;
					while(file_exists($path.$filename)){
						$filename = strtolower(generateSalt('10')).$targetExt;
					}
					if(copy($target, $path.$filename)) {
						$output['filename'] = $filename;
						$output['filetype'] = $targetExt;
						$output['status'] = true;
					}else{
						$output['message'] = 'Failed to copy repository file.';
					}
				}else{
					$output['message'] = 'Invalid file extension.';
				}
			}else{
				$output['message'] = 'Unable to read repository file. Missing repository file.';
			}
			return $output;
		}
		
		function moveUploadFile($repo, $file, $ext, $size = 2000000){
			$output = array('status' => false, 'message' => 'Unable to read file.', 'filename' => '');
			$target = $file['tmp_name'];
			$tempArray = explode(".", $file["name"]);	
			$targetExt = ".".strtolower(end($tempArray));
			if(in_array($targetExt, $ext)){
				if($file["size"] < $size){
					if($file["error"] > 0){
						$output['message'] = "File Corrupted. Return Code: ".$file["error"];
					}else{
						$path = DIR_MEDIA."/importation/".$repo."/";
						$filename = $file['name'];
						while(file_exists($path.$filename)){
							$filename = strtolower(generateSalt('10')).$targetExt;
						}
						move_uploaded_file($file["tmp_name"], $path.$filename);
						$output['filename'] = $filename;
						$output['filetype'] = $targetExt;
						$output['status'] = true;						
					}
				}else{
					$output['message'] = 'File size too large.';
				}
			}else{
				$output['message'] = 'Invalid file extension.';
			}
			return $output;
		}
		
		function readCSVFile($config){
			$output = array('status' => true, 'message' => 'Unable to read file.', 'temp_table' => '');
			if(is_array($config)){
				$dir = DIR_MEDIA."/importation/".$config['repo']."/".$config['file'];
				if(($handle = fopen($dir, "r")) !== FALSE){
					$row = 1;
					$structure = array();
					if(PHP_VERSION_ID >= 50300){
						$data = fgetcsv($handle, $config['length'], $config['limiter'], $config['enclose'], $config['escape']);
					}else{
						$data = fgetcsv($handle, $config['length'], $config['limiter'], $config['enclose']);
					}
					while($data !== FALSE){
						if($row == 1){
							if(empty($data)){
								$output['status'] = false;
								$output['message'] = 'Missing CSV header. First row of CSV data should not be empty.';
								break;
							}else{
								$structure = $this->cleanTempStructure($data);
								$output['temp_table'] = $this->createTempTable($structure);
								if($output['temp_table'] == ''){
									$output['status'] = false;
									$output['message'] = 'Unable to create temporary table.';
									break;
								}
							}
						}else{
							if(!empty($data)){
								$this->insertTempTable($output['temp_table'], $structure, $data);
							}
						}
						$row++;
						if(PHP_VERSION_ID >= 50300){
							$data = fgetcsv($handle, $config['length'], $config['limiter'], $config['enclose'], $config['escape']);
						}else{
							$data = fgetcsv($handle, $config['length'], $config['limiter'], $config['enclose']);
						}
					}
					fclose($handle);
				}else{
					$output['status'] = false;
					$output['message'] = 'Unable to read file.';
				}
			}else{
				$output['status'] = false;
				$output['message'] = 'Invalid CSV reader config.';
			}
			return $output;
		}
		
		function readExcelFile($config){
			require_once DIR_PLUGINS.'/php/PHPExcel.php';
			require_once DIR_PLUGINS.'/php/PHPExcel/IOFactory.php';	
			
			$output = array('status' => true, 'message' => 'Unable to read file.', 'temp_table' => '');
			$header = array();
			$data = array();
			$tempExcel = array();
			$tempExcel['total_row'] = 0;
			$tempExcel['last_row'] = 0;
			if(is_array($config)){
				$dir = DIR_MEDIA."/importation/".$config['repo']."/".$config['file'];
				if(file_exists($dir)){
					$objPHPExcel = new PHPExcel();
					if($config['type'] == '.xlsx'){
						$objReader = new PHPExcel_Reader_Excel2007();
					}else if($config['type'] == '.xls'){
						$objReader = new PHPExcel_Reader_Excel5();
					}
					$objReader->setReadDataOnly(true);
					$objPHPExcel = $objReader->load($dir);
					$objWorksheet = $objPHPExcel->getActiveSheet();
					$objWorksheet->getProtection()->setSheet(true);
					$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
					$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
					$nrColumns = ord($highestColumn) - 64; 
					foreach($objWorksheet->getRowIterator() as $row){						
						if($row->getRowIndex() == '1'){
							//continue;
							$cellIterator = $row->getCellIterator();
							$cellIterator->setIterateOnlyExistingCells(true);
							foreach($cellIterator as $cell){								
								array_push($header, $cell->getValue());
							}
							$structure = $this->cleanTempStructure($header);
							$output['temp_table'] = $this->createTempTable($structure);
							if($output['temp_table'] == ''){
								$output['status'] = false;
								$output['message'] = 'Unable to create temporary table.';						
							}
						} 
						$tempExcel['last_row'] = $row->getRowIndex();
						$tempExcel['total_row']++;
					}
					for ($datarow = 2; $datarow <= $highestRow; ++ $datarow) {
						$val=array();
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							$cell = $objWorksheet->getCellByColumnAndRow($col, $datarow);
							$val[] = $cell->getValue();
						 }
						 $this->insertTempTable($output['temp_table'], $structure, $val);
						 
					}	
					$objPHPExcel->disconnectWorksheets();
					unset($objPHPExcel);	
					
				}else{
					$output['status'] = false;
					$output['message'] = 'Unable to read file.';
				}
			}else{
				$output['status'] = false;
				$output['message'] = 'Invalid Excel reader config.';
			}
			return $output;
		}
		
		function createTempTable($structure){
			$tableName = "temp_".strtolower(generateSalt('10'));
			$sql = "SHOW TABLES LIKE '".$tableName."' ";
			$this->db->query($sql);
			while($this->db->numRow() > 0){
				$sql = "SHOW TABLES LIKE '".$tableName."' ";
				$this->db->query($sql);
			}
			$create = "CREATE TABLE ".$tableName." (id INT(10) NOT NULL AUTO_INCREMENT, PRIMARY KEY(id)";
			foreach($structure AS $value){
				$create .= ", `".$value."` VARCHAR(800)";
			}
			$create .= ")";
			if($this->db->query($create) === FALSE){$tableName = "";}
			return $tableName;
		}
		
		function cleanTempStructure($data){
			foreach($data AS $key => $value){
				$value = trim($value);
				$value = str_replace(" ", "_", $value);
				$value = preg_replace ('/[^a-zA-Z0-9]/', '_', $value);
				$data[$key] = strtolower($value);				
			}
			return $data;
		}
		
		function insertTempTable($table, $structure, $data){
			$insert = array();
			foreach($structure AS $key => $value){
				if(isset($data[$key])){
					$insert[$value] = cleanMYQuery($data[$key]);
				}else{
					$insert[$value] = "";
				}
			}
			if($this->db->insert($table, $insert)){
				return true;
			}else{
				return false;
			}
		}
		
		function countTempTable($table){
			$output = 0;
			$output = $this->db->countRow('id', $table, "");
			return $output;
		}
		
		function generateExtColumn($data){
			$output = "";
			foreach($data AS $value){
				$output .= ",{header: ".$value.", dataIndex: ".$value."}";
			}
			$output = "{xtype: 'rownumberer', resizable: true, width: 35}".$output;
			return $output;
		}
		
		function generateExtFilter($data){
			$output = "";
			foreach($data AS $value){
				if($output != ''){$output .= ",";}
				$output .= "{type: 'string', dataIndex: ".$value."}";
			}
			return $output;
		}
		
		function validatePredefinedRule($importationId, $rule, $mode, $table, $comboTarget){
			$output = array('status'=>true, 'message'=>'');
			if($mode == 'navi_csv' || $mode == 'oz_excel'){
				if($table == ''){
					$output['status'] = false;
					$output['message'] = 'Temporary table cannot found. Please try again';
				}else{
					$comboSource = $this->getComboSource($table);
					$source = array();
					foreach($comboSource AS $data){
						array_push($source, $data['value']);
					}
					foreach($rule AS $data){
						if(!in_array($data['source'], $source)){
							$output['status'] = false;
							$output['message'] = 'Source data does not match with predefined rule.';
							return $output;
						}
						if($this->checkDuplicateRuleExist('', $importationId, $data['target'])){
							$output['status'] = false;
							$output['message'] = "Cannot load predefined rule because duplicate rule detected.";
							break;
						}else{
							$data['source_label'] = $this->getRuleLabel($comboSource, $data['source']);
							$data['target_label'] = $this->getRuleLabel($comboTarget, $data['target']);
							$data['importation_id'] = $importationId;
							$data['created_by'] = $_SESSION['user_id'];
							$data['created_date'] = date("Y-m-d H:i:s");
							if($this->saveImportation('rule', $data)){
								insertAuditTrails('item_management.category.import.view', 'insert', "rule", $data);
							}
						}
					}
				}
			}
			return $output;
		}
		
		function getSourceDataCSV($start, $limit, $baseId, $tempTable, $exclude, $ruleSql){
			$output = array();
			$excludeId = array();
			$sql = "SELECT `selection_id` FROM `".$this->selTable."` WHERE `importation_id`='".$baseId."' LIMIT ".$start.", ".$limit;
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($excludeId, $result['selection_id']);
			}
			
			$sql = "SELECT ".$ruleSql." FROM `".$tempTable."` WHERE 1=1 ";
			if($exclude == '0'){
				$sql .= " AND `id` IN ('".implode("', '", $excludeId)."')";
			}else{
				$sql .= " AND `id` NOT IN ('".implode("', '", $excludeId)."')";
			}
			$sql .= " LIMIT ".$start.", ".$limit;
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				foreach($result AS $rK => $rV){
					$result[$rK] = cleanMYQuery($rV);
				}
				array_push($output, $result);
			}
			return $output;
		}
		
		function getFinalRuleData($importationId){
			$output = array();
			$output['table'] = array();
			$output['select'] = "";
			$ruleData = array();
			$sql = "SELECT * FROM `".$this->ruleTable."` WHERE `importation_id`='".$importationId."'";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				
				$target = explode(".", $result['target']);
				$table = $target[0];
				$temp = array();
				$temp['source'] = $result['source'];
				$temp['target'] = $target[1];
				$temp['function'] = $result['function'];
				
				if(!isset($output['table'][$table])){
					$output['table'][$table] = array();
				}
				array_push($output['table'][$table], $temp);
				
				if($output['select'] != ""){
					$output['select'] .= ", ";
				}
				$output['select'] .= "`".$result['source']."`";
			}
			return $output;
		}
		
		function createLogFile($repo){
			$dir = DIR_MEDIA."/importation/".$repo;
			$output = "log_".generateSalt('10').".txt";
			$path = $dir."/".$output;
			while(file_exists($path)){
				$output = "log_".generateSalt('10').".txt";
				$path = $dir."/".$output;
			}
			$logFile = fopen($path, 'w+');
			$insert = "[".date("Y-m-d H:i:s")."]			[initialize]			Importation start...".PHP_EOL;
			fwrite($logFile, $insert);
			fclose($logFile);
			return $output;
		}
		
		function appendLogFile($repo, $log, $content){
			$dir = DIR_MEDIA."/importation/".$repo."/".$log;
			if(!file_exists($dir)){
				$this->createLogFile($repo);
			}else{
				$logFile = fopen($dir, 'a+');
				$insert = $content;
				fwrite($logFile, $insert);
				fclose($logFile);
			}
		}
		
		function getFilterRawDataSQL($filter, $exception = array()){
			$condition = "";
			for($i=0; $i<count($filter); $i++){
				if(in_array($filter[$i]['field'], $exception)){
					continue;
				}else{
					$condition .= " AND `raw_data` REGEXP '\"".$filter[$i]['field']."\":\"([^\"]*)".$filter[$i]['data']['value']."([^\"]*)\"'";
				}
			}
			return $condition;
		}
		
		function validateImportationFunction($function, $value){
			$output = array('status' => true, 'message' => '');
			
			$fx = explode(';', $function);
			foreach($fx AS $k => $v){
				$v = trim($v);
				if($v == '[NOT NULL]' || $v == '[NOT ZERO]'){
					if($value == '' || $value == '0'){
						$output['status'] = false;
						$output['message'] = "Value is null";
						return $output;
					}
				}else if($v == '[NUMERIC]'){
					if(!is_numeric($value)){
						$output['status'] = false;
						$output['message'] = "Value is not numeric";
						return $output;
					}
				}else if($v == '[POSITIVE]'){
					if(!is_numeric($value)){
						$output['status'] = false;
						$output['message'] = "Value is not numeric";
						return $output;
					}else{
						if($data < 0){
							$output['status'] = false;
							$output['message'] = "Value is not positive";
							return $output;
						}
					}
				}else if($v == '[NEGATIVE]'){
					if(!is_numeric($value)){
						$output['status'] = false;
						$output['message'] = "Value is not numeric";
						return $output;
					}else{
						if($data > 0){
							$output['status'] = false;
							$output['message'] = "Value is not negative";
							return $output;
						}
					}
				}else if($v == '[NOT DECIMAL]' || $v == '[DECIMAL]'){
					if(!is_numeric($value)){
						$output['status'] = false;
						$output['message'] = "Value is not numeric";
						return $output;
					}else{
						if(floor($data) != $data && $v == '[NOT DECIMAL]'){
							$output['status'] = false;
							$output['message'] = "Value should not be in decimal";
							return $output;
						}else if(floor($data) == $data && $v == '[DECIMAL]'){
							$output['status'] = false;
							$output['message'] = "Value should be in decimal";
							return $output;
						}
					}
				}
			}
			return $output;
		}
	}	
?>