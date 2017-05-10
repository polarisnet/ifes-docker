<?php
	class OZPDO{
		var $connection;
		var $resource;
		var $config;

		function OZPDO($config){
			$this->config = $config;
			switch($this->config['mode']){
				case "mssql":
					$dsn = "sqlsrv:Server=".$this->config['server'];
					if($this->config['port'] != "0"){
						$dsn .= ",".$this->config['port'];
					}
					$dsn .= ";Database=".$this->config['db'];
				break;
				case "mysql":
					$dsn = "mysql:host=".$this->config['server'].";port=".$this->config['port'].";dbname=".$this->config['db'];
				break;
				case "foxpro":
					$dsn = "odbc:Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=".$this->config['dir'].";Exclusive=No;";
				break;
				case "foxpro-oledb":
					//Use ActiveX Data Objects
					$this->connection = new COM("ADODB.Connection");
					$this->connection->open("Provider=vfpoledb;Mode=ReadWrite;Data Source=".$this->config['dir'].";");
					return;
				break;
			}
			if($this->config['mode'] == "foxpro"){
				$this->connection = new PDO($dsn);
			}else{
				$this->connection = new PDO($dsn, $this->config['user'], $this->config['password']);
			}
			$this->connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			if($this->config['mode'] == "mysql"){
				$this->connection->exec("set names utf8");
			}
		}

		function getConnection(){
			return $this->connection;
		}

		function getInsertedId(){
			return $this->connection->lastInsertId();
		}

		function close(){
			$this->connection = null;
		}

		function beginTrans(){
			$this->connection->beginTransaction();
		}
		
		function commitTrans(){
			$this->connection->commit();
		}
		
		function rollbackTrans(){
			$this->connection->rollBack();
		}

		function execute($sql, $param){
			if($this->config['mode'] == "foxpro-oledb"){
				foreach($param AS $key => $val){
					$sql = str_replace(":".$key, escapeVFPSquareBracket($val), $sql);
				}
				$this->resource = $this->connection->execute($sql);
				return true;
			}else{
				$this->prepare($sql, $param);
				return $this->resource->execute();
			}
		}

		function select($sql, $param){
			$this->execute($sql, $param);
			if($this->config['mode'] == "foxpro-oledb"){
				$structure = $this->getTableStructure($this->config['table']);
				$exist = array();
				$output = array();
				if(!$this->resource->EOF){
					if(empty($exist)){
						array_push($structure['column'], "total");
						foreach($structure['column'] AS $column){
							try{
								$this->resource->fields[$column];
								array_push($exist, $column);
							}catch(exception $e){
							}
						}
					}
					foreach($exist AS $column){
						$output[$column] = (string)$this->resource->fields[$column]->value;
					}
				}
				$this->resource->close();
				return $output;
			}else{
				return $this->resource->fetch();
			}
		}

		function selectAll($sql, $param){
			$this->execute($sql, $param);
			if($this->config['mode'] == "foxpro-oledb"){
				$structure = $this->getTableStructure($this->config['table']);
				$exist = array();
				$output = array();
				while(!$this->resource->EOF){
					if(empty($exist)){
						array_push($structure['column'], "total");
						foreach($structure['column'] AS $column){
							try{
								$this->resource->fields[$column];
								array_push($exist, $column);
							}catch(exception $e){
							}
						}
					}
					$temp = array();
					foreach($exist AS $column){
						$temp[$column] = (string)$this->resource->fields[$column]->value;
					}
					array_push($output, $temp);
					$this->resource->moveNext();
				}
				$this->resource->close();
				return $output;
			}else{
				return $this->resource->fetchAll();
			}
		}

		function constructFoxProRecNoQuery($table, $config){
			$output = "SELECT recno() recno ";
			$customOrder = "";
			if(isset($config['custom_order'])){
				foreach($config['custom_order'] AS $key => $custom){
					$output .= ",".$custom['sql']." ";
					$customOrder .= "co$key ".$custom['direction']." ,";
				}
			}
			$output .= " FROM $table WHERE 1=1 ".$config['condition']." ORDER BY $customOrder ".$config['order'];
			return $output;
		}

		function selectFoxProRecNoLimit($sql, $start, $limit){
			$output = array();
			$this->execute($sql, array());
			$i = 0;
			if($this->config['mode'] == "foxpro-oledb"){
				while(!$this->resource->EOF){
					if($i >= $start && $i < $limit){
						array_push($output, (string)$this->resource->fields["recno"]->value);
					}
					$this->resource->moveNext();
					$i++;
				}
				$this->resource->close();
			}else{
				while($row = $this->resource->fetch()){
					if($i >= $start && $i < $limit){
						array_push($output, $row["recno"]);
					}
					$i++;
				}
			}
			return $output;
		}

		function insert($table, $data){
			if($this->config['mode'] == "foxpro"){
				$this->formatDBFDefaultStructure($data, $table, true);
				$param = array();
				$column = "";
				$value = "";
				if($table == "ictran"){
					$isFMatrixq = isset($data["fmatrixq10"]);
					for($dIndex = 10; $dIndex <= 20; $dIndex++){
						if(isset($data['fmatrixq'.$dIndex])){unset($data['fmatrixq'.$dIndex]);}
						if($isFMatrixq){$data['fmatrixq_'.$dIndex] = 0;}
					}
				}
				foreach($data AS $label => $record){
					if($column != ''){$column .= ", ";}
					$column .= $label;
					if($value != ''){$value .= ", ";}
					$value .= $record;
				}
				$sql = "INSERT INTO ".$table." (".$column.") VALUES (".$value.") ";
				return $this->execute($sql, $param);
			}else{
				$this->formatDefaultStructure($data, $table);
				$param = array();
				$dataKey = array_keys($data);
				$field = "";
				$value = "";

				$function = array();
				foreach($data AS $key => $val){
					if(substr($key, 0, 3) == "[x]"){
						//remove from bind param function
						array_push($function, substr($key, 3));
					}else if(substr($key, 0, 2) == "[]"){
						//substitute sql statement
						$tmpField = substr($key, 2);
						if($field != ""){
							$field .= ",";
						}
						$field .= $tmpField;
						if($value != ""){
							$value .= ",";
						}
						$value .= $val;
						array_push($function, $tmpField);
					}else if(!in_array($key, $function)){
						//assign bind param
						if($field != ""){
							$field .= ",";
						}
						$field .= $key;
						if($value != ""){
							$value .= ",";
						}
						$value .= ":_".$key;
						$param["_$key"] = $val;
					}
				}
				$sql = "INSERT INTO $table ($field) VALUES ($value)";
				return $this->execute($sql, $param);
			}
		}

		function update($table, $data, $condition, $param){
			if($this->config['mode'] == "foxpro"){
				$this->formatDBFDefaultStructure($data, $table, false);
				$set = "";
				foreach($data AS $key => $val){
					if($set != ""){
						$set .= ",";
					}
					$set .= "$key=$val";
				}
				$sql = "UPDATE $table SET $set WHERE 1=1 $condition";
				return $this->execute($sql, $param);
			}else{
				$set = "";
				foreach($data AS $key => $val){
					if($set != ""){
						$set .= ",";
					}
					$set .= "$key=:_$key";
					$param["_$key"] = $val;
				}
				$sql = "UPDATE $table SET $set WHERE 1=1 $condition";
				return $this->execute($sql, $param);
			}
		}

		function delete($table, $condition, $param){
			$sql = "DELETE FROM $table WHERE 1=1 $condition";
			return $this->execute($sql, $param);
		}

		function prepare($sql, &$param){
			$this->resource = $this->connection->prepare($sql);
			foreach($param AS $col => $val){
				$this->resource->bindValue(":$col", $val);
			}
		}

		function getTableStructure($table){
			$output = array();
			switch($this->config['mode']){
				case "mssql":
					$output = $this->selectAll("SELECT * FROM ".$this->config['db'].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :tbl", array("tbl" => $table));
				break;
				case "foxpro":
				case "foxpro-oledb":
					require_once DIR_PLUGINS.'/adodb5/xbase.class.php';
					try{
						$xBase = new XBaseTable($this->config['dir']."\\".$table.".dbf");
						$xBase->open();
						$output['column'] = array_map('strtolower', $xBase->columnNames);
						$output['type'] = $xBase->columnTypes;
						$xBase->close();
						$xBase = null;
					}catch(exception $e){}
				break;
			}
			return $output;
		}

		function formatDBFDefaultStructure(&$data, $table, $insert){
			$structure = $this->getTableStructure($table);
			$output = array();
			foreach($structure['column'] AS $key => $column){
				if(!isset($data[$column])){
					if($insert){
						switch($structure['type'][$key]){
							case 'N':
							case 'L':
								$output[$column] = "0";
							break;
							case 'D':
							case 'T':
								$output[$column] = "{//}";
							break;
							default:
							case 'C':
								$output[$column] = "\"\"";
							break;
						}
					}
				}else{
					switch($structure['type'][$key]){
						case 'D':
						case 'T':
							$output[$column] = $this->formatFoxproDate($structure['type'][$key], $data[$column]);
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
			$data = $output;
			foreach($data AS $key => $val){
				$data[$key] = mb_convert_encoding($val, $this->config["encoding"], "UTF-8");
			}
		}

		function formatFoxproDate($dateType, $mySQLDate){
			if($mySQLDate == "0000-00-00 00:00:00" || $mySQLDate == ""){
				return "{//}";
			}else{
				if($dateType == "T"){
					return "{ts '".date("Y-m-d H:i:s", strtotime($mySQLDate))."'}";
				}else{
					return "{".date("m/d/Y", strtotime($mySQLDate))."}";
				}
			}
		}

		function formatDefaultStructure(&$data, $table){
			$structure = $this->getTableStructure($table);
			if($this->config['mode'] == 'mssql'){
				foreach($structure AS $structureData){
					$column = strtolower(trim($structureData['column_name']));
					if($structureData['is_nullable'] == "NO" && !isset($data[$column])){
						switch($structureData['data_type']){
							case "int":
							case "decimal":
								$data[$column] = 0;
							break;
							default:
							case "nvarchar":
								$data[$column] = "";
							break;
						}
					}
				}
			}
		}
	}
?>