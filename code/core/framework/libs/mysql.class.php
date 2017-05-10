<?php
	class MySQL{
		var $connection;
		var $queryResult;
		var $lastQuery;
		var $record = array();
		
		//Constructor
		function MySQL($host, $user, $pass, $port){
			$this->connection = @mysqli_connect($host, $user, $pass, "", $port);
		}
		
		//Connect with database
		function connect($db){
			mysqli_query($this->connection, "SET NAMES 'utf8'");
			if(mysqli_select_db($this->connection, $db)){
				return true;
			}else{
				$this->errorHandler(1, '');
				return false;
			}
		}
		
		//Return connection
		function getConnection(){
			return $this->connection;
		}
		
		//Return last query
		function getLastQuery(){
			return $this->lastQuery;
		}
		
		//Terminate connection
		function terminate(){
			if($this->connection){
				mysqli_close($this->connection);
			}
		}
		
		//MySQL error handler
		function errorHandler($trace, $query = ""){
			//$backtrace = debug_backtrace()[$trace];
			$debug = debug_backtrace();
			if(isset($trace) && $trace != ''){
				$backtrace = $debug[$trace];
			} else {
				$backtrace = $debug[0];
			}
			$text = '['.date('Y-m-d H:i:s').'][ERROR][MYSQL]['.mysqli_error($this->connection).($query == "" ? '' : '. Query: '.$query).']['.$backtrace['file'].'][on line:'.$backtrace['line'].']';
			appendErrorLog($text);
		}
		
		//Execute query
		function query($query){
			$query = trim($query);
			$this->lastQuery = $query;
			$this->queryResult = mysqli_query($this->connection, $query);
			if(!$this->queryResult){
				$this->errorHandler(1, $query);
				return false;
			}else{
				return true;
			}
		}
		
		function query_id() {
			return $this->queryResult;
		}
		
		//Get record
		function getRecord(){
			$temp = $this->record;
			/*foreach($temp AS $tK => $tV){
				$temp[$tK] = stripcslashes($tV);
			}*/
			return $temp;
		}
		
		//Get Inserted Id
		function getInsertedId(){
			return mysqli_insert_id($this->connection);
		}
		
		//Iterate to next record
		function nextRecord($mode = 'array'){
			if($this->queryResult == ""){
				return false;
			}
			
			switch($mode){
				case 'row':
					$this->record = mysqli_fetch_row($this->queryResult);
				break;
				case 'array':
				default:
					$this->record = mysqli_fetch_array($this->queryResult, MYSQLI_ASSOC);
				break;
			}
			
			if(is_array($this->record)){
				return $this->record;
			}else{
				mysqli_free_result($this->queryResult);
				return false;
			}
		}
		
		//Insert function
		function insert($table, $data){
			$field = "";
			$value = "";
			foreach($data AS $key => $val){
				if($field == ""){
					$field = "`".$key."`";
				}else{
					$field .= ", `".$key."`";
				}
				if($value == ""){
					$value = "'".$val."'";
				}else{
					$value .= ", '".$val."'";
				}
			}
			
			$query = "INSERT INTO `".$table."` (".$field.") VALUES (".$value.")";
			$this->queryResult = mysqli_query($this->connection, $query);
			if(!$this->queryResult){
				$this->errorHandler(1, $query);
				return false;
			}else{
				return true;
			}
		}
		
		//Alert function
		function alert($table, $field, $type){			
			$query = "ALTER TABLE `".$table."`  ADD ".$field." ".$type ;
			$this->queryResult = mysqli_query($this->connection, $query);
			if(!$this->queryResult){
				$this->errorHandler(1, $query);
				return false;
			}else{
				return true;
			}
		}
		
		//Update function
		function update($table, $data, $condition){
			$query = "UPDATE `".$table."` SET ";
			$value = "";
			foreach($data AS $key => $val){
				if($value != ""){
					$value .= ", ";
				}
				$value .= "`".$key."`='".$val."'";
			}
			$query .= $value." WHERE 1=1 AND ".$condition;
			$this->queryResult = mysqli_query($this->connection, $query);
			if(!$this->queryResult){
				$this->errorHandler(1, $query);
				return false;
			}else{
				return true;
			}
		}
		
		//Delete function
		function delete($table, $condition){
			$query = "DELETE FROM `".$table."` WHERE 1=1 AND ".$condition;
			$this->queryResult = mysqli_query($this->connection, $query);
			if(!$this->queryResult){
				$this->errorHandler(1, $query);
				return false;
			}else{
				return true;
			}
		}
		
		//Get total row (specific)
		function countRow($field = 'id', $table, $condition = ''){
			$total = 0;
			$query = "SELECT COUNT(`".$field."`) AS `total` FROM `".$table."` WHERE 1=1 ".$condition;
			$this->query($query);
			if($this->nextRecord()){
				$result = $this->getRecord();
				$total = $result['total'];
			}
			return $total;
		}
		
		//Get total row (with JOIN)
		function countJoinRow($field = 'id', $table, $join, $condition = ''){
			$total = 0;
			$query = "SELECT COUNT(".$field.") AS `total` FROM `".$table."` ".$join." WHERE 1=1 ".$condition;
			$this->query($query);
			if($this->nextRecord()){
				$result = $this->getRecord();
				$total = $result['total'];
			}
			return $total;
		}
		
		//Get total row (blind)
		function numRow(){
			return mysqli_num_rows($this->queryResult);
		}
		
		function Get($field_name) {
			if(isset($this->data[$field_name])) {
				return $this->data[$field_name];
			} else {
				return "";
			}		
		}
		
		function Set($field_name, $field_value) {
			$this->data[$field_name] = $field_value;
		}
		
		// find single field type
		function fieldComment($db, $table, $column) {
			$sql = "SELECT `column_comment` AS `comment` FROM information_schema.columns WHERE `table_schema` = '".$db."' AND `table_name` = '".$table."' AND `column_name` = '".$column."'";
			$h = @mysqli_query($sql, $this->Link_ID);		
			$info = @mysqli_fetch_row($h);
			$comment = $info[0];
			@mysqli_free_result($h);
			return $comment;
		}
		
		function beginTrans(){
			mysqli_query($this->connection, "BEGIN");
		}
		
		function commitTrans(){
			mysqli_query($this->connection, "COMMIT");
		}
		
		function rollbackTrans(){
			mysqli_query($this->connection, "ROLLBACK");
		}
	}
?>