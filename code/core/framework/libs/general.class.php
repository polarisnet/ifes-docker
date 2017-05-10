<?php
	class General{
		var $db;
		var $totalRow;
		
		function General($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		/** General - Start **/
		function getTotalRecordsWithConditions($strID = 'id', $table, $strConditions = '') {
			$intTotal = 0;
			$sql = "SELECT COUNT(".$strID.") as total FROM ".$table." WHERE 1=1 ";
			if($strConditions!="") {
				$sql .= $strConditions;
			}
			if ($this->query_id = $this->db->query($sql)){
				if($this->db->nextRecord()) {
					$this->db->data = $this->db->getRecord();
					$intTotal = $this->db->Get("total");
				}
			}
			return $intTotal;
		}
		
		function getDetailsByTableConditions($table = "users", $strConditions = "") {
			$sql = "SELECT * FROM ".$table." ";
			$sql .= "WHERE 1=1 ";
			if($strConditions!="") {
				$sql .= $strConditions;
			}
			if ($this->query_id = $this->db->query($sql)){
				return true;
			} else {
				return false;
			}
		}
		
		function getSpecialByTableConditions($table = "users", $field, $strConditions = "") {
			$sql = "SELECT ".$field." FROM ".$table." ";
			$sql .= "WHERE 1=1 ";
			if($strConditions!="") {
				$sql .= $strConditions;
			}
			if ($this->query_id = $this->db->query($sql)){
				return true;
			} else {
				return false;
			}
		}
		
		function saveRecord($arrFields = array(), $tablename = '', $enablePrepData = false) {
			// Pre checking for mysql data.
			if(isset($enablePrepData) && $enablePrepData) {
				$arrFields = mysqlPrepValues($arrFields);
			}
			
			$total_fields = count($arrFields);
			$count = 0;
			$field_list = ""; $field_values = "";
			foreach($arrFields as $key => $item) {
				$field_list .= '`' . $key . '`';
				$field_values .= '"' . $item . '"';
				$count++;
				if ($count != $total_fields) {
					$field_list .= ', ';
					$field_values .= ', ';
				}
			}
			$sql = 'INSERT INTO ' . $tablename . '(' . $field_list . ') VALUES (' . $field_values . ')';
			//echo $sql;exit;
			if ($this->db->query($sql)) {
				return true;
			} else {
				return false;
			}
		}

		function updateRecord($arrFields = array(), $tablename = 'users', $intId = 0, $enablePrepData = false) {
			$total_fields = count($arrFields);
			if ($total_fields > 0) {
				// Pre checking for mysql data.
				if(isset($enablePrepData) && $enablePrepData) {
					$arrFields = mysqlPrepValues($arrFields);
				}
				
				$count = 0;
				$strValue = "";
				foreach($arrFields as $key => $item) {
					$strValue .= '`' . $key . '` = ' . '"' . $item . '"';
					$count++;
					if ($count != $total_fields) {
						$strValue .= ', ';
					}
				}
				$sql = 'UPDATE ' . $tablename . ' SET ' . $strValue . ' WHERE id = "' . $intId . '"';
				if ($this->query_id = $this->db->query($sql)) {
					return true;
				} else {
					return false;
				}
			}
		}

		function updateRecordByConditions($arrFields = array(), $tablename = 'users', $strConditions = '', $enablePrepData = false) {
			$total_fields = count($arrFields);
			if ($total_fields > 0 && $strConditions!="") {
				// Pre checking for mysql data.
				if(isset($enablePrepData) && $enablePrepData) {
					$arrFields = mysqlPrepValues($arrFields);
				}
				
				$count = 0;
				$strValue = "";
				foreach($arrFields as $key => $item) {
					$strValue .= '`' . $key . '` = ' . '"' . $item . '"';
					$count++;
					if ($count != $total_fields) {
						$strValue .= ', ';
					}
				}
				$sql = 'UPDATE ' . $tablename . ' SET ' . $strValue . ' WHERE 1=1 ' . $strConditions . '';
				//echo $sql;exit;
				if ($this->query_id = $this->db->query($sql)) {
					return true;
				} else {
					return false;
				}
			}
		}

		function deleteRecord($tablename = '', $strCondition = "") {
			if($tablename != "") {
				$sql = "DELETE FROM " . $tablename . " WHERE 1=1 " . $strCondition;
				//echo $sql;exit;
				if ($this->query_id = $this->db->query($sql)){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		/** General - End **/
		
	}
?>