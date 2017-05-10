<?php
/**
* ----------------------------------------------------------------
*			XBase
*			XBaseColumn.class.php	
* --------------------------------------------------------------
*
* This class represents a DBF column
* Do not construct an instance yourself, it's useless that way.
*
**/
class XBaseColumn {

	var $name;
	var $rawname;
	var $type;
	var $memAddress;
	var $length;
	var $decimalCount;
	var $workAreaID;
	var $setFields;
	var $indexed;
	var $bytePos;
	var $colIndex;
	var $mysql_data_type_hash = array(
		1=>'tinyint',
		2=>'smallint',
		3=>'int',
		4=>'float',
		5=>'double',
		7=>'timestamp',
		8=>'bigint',
		9=>'mediumint',
		10=>'date',
		11=>'time',
		12=>'datetime',
		13=>'year',
		16=>'bit',
		//252 is currently mapped to all text and blob types (MySQL 5.0.51a)
		253=>'varchar',
		254=>'char',
		246=>'decimal'
	);

	function XBaseColumn(
		$name,
		$type,
		$memAddress,
		$length,
		$decimalCount,
		$reserved1,
		$workAreaID,
		$reserved2,
		$setFields,
		$reserved3,
		$indexed,
		$colIndex,
		$bytePos
	) {
		$this->rawname=$name;
		$this->name=strpos($name,0x00)!==false?substr($name,0,strpos($name,0x00)):$name;
		$this->type=$type;
		$this->memAddress=$memAddress;
		$this->length=$length;
		$this->decimalCount=$decimalCount;
		$this->workAreaID=$workAreaID;
		$this->setFields=$setFields;
		$this->indexed=$indexed;
		$this->bytePos=$bytePos;
		$this->colIndex=$colIndex;
	}
	function getDecimalCount() {
		return $this->decimalCount;
	}
	function isIndexed() {
		return $this->indexed;
	}
	function getLength() {
		return $this->length;
	}
	function getDataLength() {
		switch ($this->type) {
			case DBFFIELD_TYPE_DATE : return 8;
			case DBFFIELD_TYPE_DATETIME : return 8;
			case DBFFIELD_TYPE_LOGICAL : return 1;
			case DBFFIELD_TYPE_MEMO : return 10;
			default : return $this->length;
		}
	}
	function getMemAddress() {
		return $this->memAddress;
	}
	function getName() {
		return $this->name;
	}
	function isSetFields() {
		return $this->setFields;
	}
	function getType() {
		return $this->type;
	}
	function getWorkAreaID() {
		return $this->workAreaID;
	}
	function toString() {
		return $this->name;
	}
	function getBytePos() {
		return $this->bytePos;
	}
	function getRawname() {
		return $this->rawname;
	}
	function getColIndex() {
		return $this->colIndex;
	}
}

/**
* ----------------------------------------------------------------
*			XBase
*			api_conversion.php	
* --------------------------------------------------------------
*
* This file implements the default dBase functions as described in the PHP docs
*
**/


/**
* ----------------------------------------------------------------
*			XBase
*			Record.class.php	
* --------------------------------------------------------------
*
* This class defines the data access functions to a DBF record
* Do not construct an instance yourself, generate records through the nextRecord function of XBaseTable
*
**/

define ("DBFFIELD_TYPE_MEMO","M");		// Memo type field.
define ("DBFFIELD_TYPE_CHAR","C");		// Character field.
define ("DBFFIELD_TYPE_NUMERIC","N");	// Numeric
define ("DBFFIELD_TYPE_FLOATING","F");	// Floating point
define ("DBFFIELD_TYPE_DATE","D");		// Date
define ("DBFFIELD_TYPE_LOGICAL","L");	// Logical - ? Y y N n T t F f (? when not initialized).
define ("DBFFIELD_TYPE_DATETIME","T");	// DateTime

define ("DBFFIELD_TYPE_INDEX","I");    // Index 
define ("DBFFIELD_IGNORE_0","0");		// ignore this field


class XBaseRecord {

	var $zerodate = 0x253d8c;
	var $table;
	var $choppedData;
	var $deleted;
	var $inserted;
	var $recordIndex;
	
	function XBaseRecord($table, $recordIndex, $rawData=false) {
		$this->table = $table;
		$this->recordIndex=$recordIndex;
		$this->choppedData = array();
		if ($rawData && strlen($rawData)>0) {
			$this->inserted=false;
			$this->deleted=(ord($rawData[0])!="32");
			foreach ($table->getColumns() as $column) {
				$this->choppedData[]=substr($rawData,$column->getBytePos(),$column->getDataLength());
			}
		} else {
			$this->inserted=true;
			$this->deleted=false;
			foreach ($table->getColumns() as $column) {
				$this->choppedData[]=str_pad("", $column->getDataLength(),chr(0));
			}
		}
	}
	function isDeleted() {
		return $this->deleted;
	}
	function getColumns() {
		return $this->table->getColumns();
	}
	function getColumnByName($name) {
		return $this->table->getColumnByName($name);
	}
	function getColumn($index) {
		return $this->table->getColumn($index);
	}
	function getColumnIndex($name) {
		return $this->table->getColumnIndex($name);
	}
	function getRecordIndex() {
		return $this->recordIndex;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Get data functions
	 * -------------------------------------------------------------------------
	 */
	function getStringByName($columnName) {
		return $this->getString($this->table->getColumnByName($columnName));
	}
	function getStringByIndex($columnIndex) {
		return $this->getString($this->table->getColumn($columnIndex));
	}
	function getString($columnObj) {
		if ($columnObj->getType()==DBFFIELD_TYPE_CHAR) {
			return $this->forceGetString($columnObj);
		} else if($columnObj->getType()==DBFFIELD_TYPE_DATETIME || $columnObj->getType()==DBFFIELD_TYPE_DATE || $columnObj->getType()==DBFFIELD_TYPE_LOGICAL) {
			$result = $this->getObject($columnObj);
			if ($result && ($columnObj->getType()==DBFFIELD_TYPE_DATETIME || $columnObj->getType()==DBFFIELD_TYPE_DATE)) return @date("r",$result);
			if ($columnObj->getType()==DBFFIELD_TYPE_LOGICAL) return $result?"1":"0";
			return $result;
		}
	}
	function forceGetString($columnObj) {
		if (ord($this->choppedData[$columnObj->getColIndex()][0])=="0") return false;
		return trim($this->choppedData[$columnObj->getColIndex()]);
	}
	function getObjectByName($columnName) {
		return $this->getObject($this->table->getColumnByName($columnName));
	}
	function getObjectByIndex($columnIndex) {
		return $this->getObject($this->table->getColumn($columnIndex));
	}
	function getObject($columnObj) {
		switch ($columnObj->getType()) {
			case DBFFIELD_TYPE_CHAR : return $this->getString($columnObj);
			case DBFFIELD_TYPE_DATE : return $this->getDate($columnObj);
			case DBFFIELD_TYPE_DATETIME : return $this->getDateTime($columnObj);
			case DBFFIELD_TYPE_FLOATING : return $this->getFloat($columnObj);
			case DBFFIELD_TYPE_LOGICAL : return $this->getBoolean($columnObj);
			case DBFFIELD_TYPE_MEMO : return $this->getMemo($columnObj);
			case DBFFIELD_TYPE_NUMERIC : return $this->getInt($columnObj);
			case DBFFIELD_TYPE_INDEX : return $this->getIndex($columnObj); 
			case DBFFIELD_IGNORE_0 : return false;
		}
		trigger_error ("cannot handle datatype".$columnObj->getType(), E_USER_ERROR);
	}
	function getDate($columnObj) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_DATE) trigger_error ($columnObj->getName()." is not a Date column", E_USER_ERROR);
		$s = $this->forceGetString($columnObj);
		if (!$s) return false;
		return strtotime($s);
	}
	function getDateTime($columnObj) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_DATETIME) trigger_error ($columnObj->getName()." is not a DateTime column", E_USER_ERROR);
		$raw =  $this->choppedData[$columnObj->getColIndex()];
		$buf = unpack("i",substr($raw,0,4));
		$intdate = $buf[1];
		$buf = unpack("i",substr($raw,4,4));
		$inttime = $buf[1];

		if ($intdate==0 && $inttime==0) return false;

		$longdate = ($intdate-$this->zerodate)*86400;
		return $longdate+$inttime;
	}
	function getBoolean($columnObj) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_LOGICAL) trigger_error ($columnObj->getName()." is not a DateTime column", E_USER_ERROR);
		$s = $this->forceGetString($columnObj);
		if (!$s) return false;
		switch (strtoupper($s[0])) {
			case 'T':
			case 'Y':
			case 'J':
			case '1':
				return true;

			default: return false;
		}
	}
	function getMemo($columnObj) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_MEMO) trigger_error ($columnObj->getName()." is not a Memo column", E_USER_ERROR);
		return $this->forceGetString($columnObj);
	}
	function getFloat($columnObj) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_FLOATING) trigger_error ($columnObj->getName()." is not a Float column", E_USER_ERROR);
		$s = $this->forceGetString($columnObj);
		if (!$s) return false;
		$s = str_replace(",",".",$s);
		return floatval($s);
	}
	function getInt($columnObj) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_NUMERIC) trigger_error ($columnObj->getName()." is not a Number column", E_USER_ERROR);
		$s = $this->forceGetString($columnObj);
		if (!$s) return false;
		$s = str_replace(",",".",$s);
		return intval($s);
	}
	function getIndex($columnObj) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_INDEX) trigger_error ($columnObj->getName()." is not an Index column", E_USER_ERROR);
		$s = $this->choppedData[$columnObj->getColIndex()];
		if (!$s) return false;
		
		$ret = ord($s[0]);
		for ($i = 1; $i < $columnObj->length; $i++) {
			$ret += $i * 256 * ord($s[$i]);
		}
		return $ret;   
	} 

	/**
	 * -------------------------------------------------------------------------
	 * Set data functions
	 * -------------------------------------------------------------------------
	 **/
	function copyFrom($record) {
		$this->choppedData = $record->choppedData;
	}
	function setDeleted($b) {
		$this->deleted=$b;
	}
	function setStringByName($columnName,$value) {
		$this->setString($this->table->getColumnByName($columnName),$value);
	}
	function setStringByIndex($columnIndex,$value) {
		$this->setString($this->table->getColumn($columnIndex),$value);
	}
	function setString($columnObj,$value) {
		if ($columnObj->getType()==DBFFIELD_TYPE_CHAR) {
			$this->forceSetString($columnObj,$value);
		} else {
			if ($columnObj->getType()==DBFFIELD_TYPE_DATETIME || $columnObj->getType()==DBFFIELD_TYPE_DATE) $value = strtotime($value);
			$this->setObject($columnObj,$value);
		}
	}
	function forceSetString($columnObj,$value) {
		$this->choppedData[$columnObj->getColIndex()] = str_pad(substr($value,0,$columnObj->getDataLength()),$columnObj->getDataLength()," ");
	}
	function setObjectByName($columnName,$value) {
		return $this->setObject($this->table->getColumnByName($columnName),$value);
	}
	function setObjectByIndex($columnIndex,$value) {
		return $this->setObject($this->table->getColumn($columnIndex),$value);
	}
	function setObject($columnObj,$value) {
		switch ($columnObj->getType()) {
			case DBFFIELD_TYPE_CHAR : $this->setString($columnObj,$value); return;
			case DBFFIELD_TYPE_DATE : $this->setDate($columnObj,$value); return;
			case DBFFIELD_TYPE_DATETIME : $this->setDateTime($columnObj,$value); return;
			case DBFFIELD_TYPE_FLOATING : $this->setFloat($columnObj,$value); return;
			case DBFFIELD_TYPE_LOGICAL : $this->setBoolean($columnObj,$value); return;
			case DBFFIELD_TYPE_MEMO : $this->setMemo($columnObj,$value); return;
			case DBFFIELD_TYPE_NUMERIC : $this->setInt($columnObj,$value); return;
			case DBFFIELD_IGNORE_0 : return;
		}
//        trigger_error ("cannot handle datatype".$columnObj->getType(), E_USER_ERROR);
	}
	function setDate($columnObj,$value) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_DATE) trigger_error ($columnObj->getName()." is not a Date column", E_USER_ERROR);
		if (strlen($value)==0) {
			$this->forceSetString($columnObj,"");
			return;
		}
		$this->forceSetString($columnObj,date("Ymd",$value));
	}
	function setDateTime($columnObj,$value) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_DATETIME) trigger_error ($columnObj->getName()." is not a DateTime column", E_USER_ERROR);
		if (strlen($value)==0) {
			$this->forceSetString($columnObj,"");
			return;
		}
		$a = getdate($value);
		$d = $this->zerodate + (mktime(0,0,0,$a["mon"],$a["mday"],$a["year"]) / 86400);
		$d = pack("i",$d);
		$t = pack("i",mktime($a["hours"],$a["minutes"],$a["seconds"],0,0,0));
		$this->choppedData[$columnObj->getColIndex()] = $d.$t;
	}
	function setBoolean($columnObj,$value) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_LOGICAL) trigger_error ($columnObj->getName()." is not a DateTime column", E_USER_ERROR);
		switch (strtoupper($value)) {
			case 'T':
			case 'Y':
			case 'J':
			case '1':
			case 'F':
			case 'N':
			case '0':
				$this->forceSetString($columnObj,$value);
				return;
			
			case true:
				$this->forceSetString($columnObj,"T");
				return;

			default: $this->forceSetString($columnObj,"F");
		}
	}
	function setMemo($columnObj,$value) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_MEMO) trigger_error ($columnObj->getName()." is not a Memo column", E_USER_ERROR);
		return $this->forceSetString($columnObj,$value);
	}
	function setFloat($columnObj,$value) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_FLOATING) trigger_error ($columnObj->getName()." is not a Float column", E_USER_ERROR);
		if (strlen($value)==0) {
			$this->forceSetString($columnObj,"");
			return;
		}
		$value = str_replace(",",".",$value);
		$s = $this->forceSetString($columnObj,$value);
	}
	function setInt($columnObj,$value) {
		if ($columnObj->getType()!=DBFFIELD_TYPE_NUMERIC) trigger_error ($columnObj->getName()." is not a Number column", E_USER_ERROR);
		if (strlen($value)==0) {
			$this->forceSetString($columnObj,"");
			return;
		}
		$value = str_replace(",",".",$value);
		//$this->forceSetString($columnObj,intval($value));
		
		/**
		* suggestion from Sergiu Neamt: treat number values as decimals
		**/
		$this->forceSetString($columnObj,number_format($value, $columnObj->decimalCount));
	}
	/**
	 * -------------------------------------------------------------------------
	 * Protected
	 * -------------------------------------------------------------------------
	 **/

	 function serializeRawData() {
		 return ($this->deleted?"*":" ").implode("",$this->choppedData);
	 }
}


/**
* ----------------------------------------------------------------
*			XBase
*			Table.class.php	
*
* This class provides the main entry to a DBF table file.
* common usage:
* 1. construct an instance
* 	$table = new XBaseTable($name);
* where $name is the path to the the DBF file, or a stream like 'php://input'
*
* 2. open the file to read the header
*	$table->open();
*
* 3. iterate through the records
*	while ($record=$table->nextRecord()) { ... }
*
* 4. close the file
*	$table->close();
*
**/

class XBaseTable {

	var $name;
	var $fp;
	var $isStream;
	var $filePos=0;
	var $recordPos=-1;
	var $record;

	var $version;
	var $modifyDate;
	var $recordCount;
	var $recordByteLength;
	var $inTransaction;
	var $encrypted;
	var $mdxFlag;
	var $languageCode;
	var $columns;
	var $columnNames;
	var $columnTypes;
	var $headerLength;
	var $backlist;
	var $foxpro;
	var $deleteCount=0;

	var $db;
	
	function XBaseWritableTable($db){
		$this->db = $db;
	}
	
	function XBaseTable($name, $db='') {
		$this->name=$name;
		if($db!="") {
			$this->db=$db;
		}
	}
	
	function open() {
		
		$fn = $this->name;
		$this->isStream=strpos($this->name,"://")!==false;
		if (!$this->isStream) {
			if (!file_exists($fn)) $fn = $this->name.".DBF";
			if (!file_exists($fn)) $fn = $this->name.".dbf";
			if (!file_exists($fn)) $fn = $this->name.".Dbf";
			if (!file_exists($fn)) trigger_error ($this->name." cannot be found", E_USER_ERROR);
		}
		$this->name = $fn;
		$this->fp = fopen($fn,"rb");
		$this->readHeader();
		return $this->fp!=false;
		
	}
	
	function readHeader() {

		$this->version = $this->readChar();
		$this->foxpro = $this->version==48 || $this->version==49 || $this->version==245 || $this->version==251;
		$this->modifyDate = $this->read3ByteDate();
		$this->recordCount = $this->readInt();
		$this->headerLength = $this->readShort();
		$this->recordByteLength = $this->readShort();
		$this->readBytes(2); //reserved
		$this->inTransaction = $this->readByte()!=0;
		$this->encrypted = $this->readByte()!=0;
		$this->readBytes(4); //Free record thread
		$this->readBytes(8); //Reserved for multi-user dBASE
		$this->mdxFlag = $this->readByte();
		$this->languageCode = $this->readByte();
		$this->readBytes(2); //reserved

		$fieldCount = ($this->headerLength - ($this->foxpro?296:33) ) / 32;
		
		/* some checking */
		if (!$this->isStream && $this->headerLength>filesize($this->name)) trigger_error ($this->name." is not DBF", E_USER_ERROR);
		//disable for clean error message
		//if (!$this->isStream && $this->headerLength+($this->recordCount*$this->recordByteLength)-500>filesize($this->name)) trigger_error ($this->name." is not DBF", E_USER_ERROR);

		/* columns */
		$this->columnNames = array();
		$this->columnTypes = array();
		$this->columns = array();
		$bytepos = 1;
		for ($i=0;$i<$fieldCount;$i++) {
			$column = new XBaseColumn(
				$this->readString(11),	// name
				$this->readByte(),		// type
				$this->readInt(),		// memAddress
				$this->readChar(),		// length
				$this->readChar(),		// decimalCount
				$this->readBytes(2),	// reserved1
				$this->readChar(),		// workAreaID
				$this->readBytes(2),	// reserved2
				$this->readByte()!=0,	// setFields
				$this->readBytes(7),	// reserved3
				$this->readByte()!=0,	// indexed
				$i,						// colIndex
				$bytepos				// bytePos
			);
			$bytepos+=$column->getLength();
			$this->columnNames[$i] = $column->getName();
			$this->columnTypes[$i] = $column->getType();
			$this->columns[$i] = $column;
		}

		/**/
		if ($this->foxpro) {
			$this->backlist=$this->readBytes(263);
		}
		$b = $this->readByte();
		$this->recordPos=-1;
		$this->record=false;
		$this->deleteCount=0;
	}
	function isOpen() {
		return $this->fp?true:false;
	}
	function close() {
		fclose($this->fp);
	}
	function &nextRecord() {
		if (!$this->isOpen()) $this->open();
		$valid=false;
		do {
			if ($this->recordPos+1>=$this->recordCount) return false;
			$this->recordPos++;
			$this->record = new XBaseRecord($this,$this->recordPos,$this->readBytes($this->recordByteLength));
			if ($this->record->isDeleted()) {
				$this->deleteCount++;
			} else {
				$valid=true;
			}
		} while (!$valid);
		return $this->record;
	}
	function &moveTo($index) {
		$this->recordPos=$index;
		if ($index<0) return;
		fseek($this->fp,$this->headerLength+($index*$this->recordByteLength));
		$this->record = new XBaseRecord($this,$this->recordPos,$this->readBytes($this->recordByteLength));
		return $this->record;
	}
	function &getRecord() {
		return $this->record;
	}
	function getColumnNames() {
		return $this->columnNames;
	}
	function getColumns() {
		return $this->columns;
	}
	function &getColumn($index) {
		return $this->columns[$index];
	}
	function &getColumnByName($name) {
		foreach ($this->columnNames as $i=>$n) if (strtoupper($n) == strtoupper($name)) return $this->columns[$i];
		return false;
	}
	function getColumnIndex($name) {
		foreach ($this->columnNames as $i=>$n) if (strtoupper($n) == strtoupper($name)) return $i;
		return false;
	}
	function getColumnCount() {
		return sizeof($this->columns);
	}
	function getRecordCount() {
		return $this->recordCount;
	}
	function getRecordPos() {
		return $this->recordPos;
	}
	function getRecordByteLength() {
		return $this->recordByteLength;
	}
	function getName() {
		return $this->name;
	}
	function getType() {
		return $this->type;
	}
	function getDeleteCount() {
		return $this->deleteCount;
	}
	
	function toHTML($withHeader=true,$tableArgs="border='1'",$trArgs="",$tdArgs="",$thArgs="") {
		$result = "<table $tableArgs >\n";
		if ($withHeader) {
			$result .= "<tr>\n";
			foreach ($this->getColumns() as $i=>$c) {
				$result .= "<th $thArgs >".$c->getName()."</th>\n";
			}
			$result .= "</tr>\n";
		}
		$this->moveTo(-1);
		while ($r = $this->nextRecord()) {
			$result .= "<tr $trArgs >\n";
			foreach ($this->getColumns() as $i=>$c) {
				$result .= "<td $tdArgs >".$r->getString($c)."</td>\n";
			}
			$result .= "</tr>\n";
		}
		$result .= "</table>\n";
		return $result;
	}

	function toXML() {
		$result = "<table ";
		$result.= "name='".$this->name."' ";
		$result.= "version='".$this->version."' ";
		$result.= "modifyDate='".$this->modifyDate."' ";
		$result.= "recordCount='".$this->recordCount."' ";
		$result.= "recordByteLength='".$this->recordByteLength."' ";
		$result.= "inTransaction='".$this->inTransaction."' ";
		$result.= "encrypted='".$this->encrypted."' ";
		$result.= "mdxFlag='".ord($this->mdxFlag)."' ";
		$result.= "languageCode='".ord($this->languageCode)."' ";
		$result.= "backlist='".base64_encode($this->backlist)."' ";
		$result.= "foxpro='".$this->foxpro."' ";
		$result.= "deleteCount='".$this->deleteCount."' ";
		$result.= ">\n";
		$result .= "<columns>\n";
		foreach ($this->getColumns() as $i=>$c) {
			$result .= "<column ";
			$result .= "name='".$c->name."' ";
			$result .= "type='".$c->type."' ";
			$result .= "length='".$c->length."' ";
			$result .= "decimalCount='".$c->decimalCount."' ";
			$result .= "bytePos='".$c->bytePos."' ";
			$result .= "colIndex='".$c->colIndex."' ";
			$result .= "/>\n";
		}
		$result .= "</columns>\n";
		$result .= "<records>\n";
		$this->moveTo(-1);
		while ($r = $this->nextRecord()) {
			$result .= "<record>\n";
			foreach ($this->getColumns() as $i=>$c) {
				$result .= "<".$c->name.">".$r->getObject($c)."</".$c->name.">\n";
			}
			$result .= "</record>\n";
		}
		$result .= "</records>\n";
		$result .= "</table>\n";
		return $result;
	}
	
	/**
	 * -------------------------------------------------------------------------
	 * private functions
	 * -------------------------------------------------------------------------
	 */
	function readBytes($l) {
		$this->filePos+=$l;
		return fread($this->fp,$l);
	}
	function writeBytes($buf) {
		return fwrite($this->fp,$buf);
	}
	function readByte()  {
		$this->filePos++;
		return fread($this->fp,1);
	}
	function writeByte($b)  {
		return fwrite($this->fp,$b);
	}
	function readString($l) {
		return $this->readBytes($l);
	}
	function writeString($s) {
		return $this->writeBytes($s);
	}
	function readChar() {
		$buf = unpack("C",$this->readBytes(1));
		return $buf[1];
	}
	function writeChar($c) {
		$buf = pack("C",$c);
		return $this->writeBytes($buf);
	}
	function readShort() {
		$buf = unpack("S",$this->readBytes(2));
		return $buf[1];
	}
	function writeShort($s) {
		$buf = pack("S",$s);
		return $this->writeBytes($buf);
	}
	function readInt() {
		$buf = unpack("I",$this->readBytes(4));
		return $buf[1];
	}
	function writeInt($i) {
		$buf = pack("I",$i);
		return $this->writeBytes($buf);
	}
	function readLong() {
		$buf = unpack("L",$this->readBytes(8));
		return $buf[1];
	}
	function writeLong($l) {
		$buf = pack("L",$l);
		return $this->writeBytes($buf);
	}
	function read3ByteDate() {
		$y = unpack("c",$this->readByte());
		$m = unpack("c",$this->readByte());
		$d = unpack("c",$this->readByte());
		return mktime(0,0,0,$m[1],$d[1],$y[1]>70?1900+$y[1]:2000+$y[1]);
	}
	function write3ByteDate($d) {
		$t = getdate($d);
		return $this->writeChar($t["year"] % 1000) + $this->writeChar($t["mon"]) + $this->writeChar($t["mday"]);
	}
	function read4ByteDate() {
		$y = readShort();
		$m = unpack("c",$this->readByte());
		$d = unpack("c",$this->readByte());
		return mktime(0,0,0,$m[1],$d[1],$y);
	}
	function write4ByteDate($d) {
		$t = getdate($d);
		return $this->writeShort($t["year"]) + $this->writeChar($t["mon"]) + $this->writeChar($t["mday"]);
	}
}


/**
* ----------------------------------------------------------------
*			XBase
*			WritableTable.class.php	
* --------------------------------------------------------------
*
* This class extends the main entry to a DBF table file, with writing abilities

*
**/

class XBaseWritableTable extends XBaseTable {
	
	/* static */
	function cloneFrom($table) {
		$result = new XBaseWritableTable($table->name);
		$result->version=$table->version;
		$result->modifyDate=$table->modifyDate;
		$result->recordCount=0;
		$result->recordByteLength=$table->recordByteLength;
		$result->inTransaction=$table->inTransaction;
		$result->encrypted=$table->encrypted;
		$result->mdxFlag=$table->mdxFlag;
		$result->languageCode=$table->languageCode;
		$result->columns=$table->columns;
		$result->columnNames=$table->columnNames;
		$result->headerLength=$table->headerLength;
		$result->backlist=$table->backlist;
		$result->foxpro=$table->foxpro;
		return $result;
	}

	/* static */
	function create($filename,$fields) {
		if (!$fields || !is_array($fields)) trigger_error ("cannot create xbase with no fields", E_USER_ERROR);
		$recordByteLength=1;
		$columns=array();
		$columnNames=array();
		$i=0;
		foreach ($fields as $field) {
			if (!$field || !is_array($field) || sizeof($field)<2) trigger_error ("fields argument error, must be array of arrays", E_USER_ERROR);
			$column = new XBaseColumn($field[0],$field[1],0,@$field[2],@$field[3],0,0,0,0,0,0,$i,$recordByteLength);
			$recordByteLength += $column->getDataLength();
			$columnNames[$i]=$field[0];
			$columns[$i]=$column;
			$i++;
		}
		
		$result = new XBaseWritableTable($filename);
		$result->version=131;
		$result->modifyDate=time();
		$result->recordCount=0;
		$result->recordByteLength=$recordByteLength;
		$result->inTransaction=0;
		$result->encrypted=false;
		$result->mdxFlag=chr(0);
		$result->languageCode=chr(0);
		$result->columns=$columns;
		$result->columnNames=$columnNames;
		$result->backlist="";
		$result->foxpro=false;
		if ($result->openWrite($filename,true)) return $result;
		return false;
	}

	function openWrite($filename=false,$overwrite=false) {
		if (!$filename) $filename = $this->name;
		if (file_exists($filename) && !$overwrite) {
			if ($this->fp = fopen($filename,"r+")) $this->readHeader();
		} else {
			if ($this->fp = fopen($filename,"w+")) $this->writeHeader();
		}
		return $this->fp!=false;
	}
	
	function writeHeader() {
		$this->headerLength=($this->foxpro?296:33) + ($this->getColumnCount()*32);
		fseek($this->fp,0);
		$this->writeChar($this->version);
		$this->write3ByteDate(time());
		$this->writeInt($this->recordCount);
		$this->writeShort($this->headerLength);
		$this->writeShort($this->recordByteLength);
		$this->writeBytes(str_pad("", 2,chr(0)));
		$this->writeByte(chr($this->inTransaction?1:0));
		$this->writeByte(chr($this->encrypted?1:0));
		$this->writeBytes(str_pad("", 4,chr(0)));
		$this->writeBytes(str_pad("", 8,chr(0)));
		$this->writeByte($this->mdxFlag);
		$this->writeByte($this->languageCode);
		$this->writeBytes(str_pad("", 2,chr(0)));
		
		foreach ($this->columns as $column) {
			$this->writeString(str_pad(substr($column->rawname,0,11), 11,chr(0)));
			$this->writeByte($column->type);
			$this->writeInt($column->memAddress);
			$this->writeChar($column->getDataLength());
			$this->writeChar($column->decimalCount);
			$this->writeBytes(str_pad("", 2,chr(0)));
			$this->writeChar($column->workAreaID);
			$this->writeBytes(str_pad("", 2,chr(0)));
			$this->writeByte(chr($column->setFields?1:0));
			$this->writeBytes(str_pad("", 7,chr(0)));
			$this->writeByte(chr($column->indexed?1:0));
		}

		if ($this->foxpro) {
			$this->writeBytes(str_pad($this->backlist, 263," "));
		}
		$this->writeChar(0x0d);
	}
	function &appendRecord() {
		$this->record = new XBaseRecord($this,$this->recordCount);
		$this->recordCount+=1;
		return $this->record;
	}
	function writeRecord() {
		fseek($this->fp,$this->headerLength+($this->record->recordIndex*$this->recordByteLength));
		$data = $this->record->serializeRawData();
		fwrite($this->fp,$data);
		if ($this->record->inserted) $this->writeHeader();
		flush($this->fp);
	}
	function deleteRecord() {
		$this->record->deleted=true;
		fseek($this->fp,$this->headerLength+($this->record->recordIndex*$this->recordByteLength));
		fwrite($this->fp,"!");
		flush($this->fp);
	}
	function undeleteRecord() {
		$this->record->deleted=false;
		fseek($this->fp,$this->headerLength+($this->record->recordIndex*$this->recordByteLength));
		fwrite($this->fp," ");
		flush($this->fp);
	}
	function pack() {
		$newRecordCount = 0;
		$newFilepos = $this->headerLength;
		for ($i=0;$i<$this->getRecordCount();$i++) {
			$r = $this->moveTo($i);
			if ($r->isDeleted()) continue;
			$r->recordIndex = $newRecordCount++;
			$this->writeRecord();
		}
		$this->recordCount = $newRecordCount;
		$this->writeHeader();
		ftruncate($this->fp,$this->headerLength+($this->recordCount*$this->recordByteLength));
	}

	/**
	 * -------------------------------------------------------------------------
	 * private functions
	 * -------------------------------------------------------------------------
	 */
	 
	function writeBytes($buf) {
		return fwrite($this->fp,$buf);
	}
	function writeByte($b)  {
		return fwrite($this->fp,$b);
	}
	function writeString($s) {
		return $this->writeBytes($s);
	}
	function writeChar($c) {
		$buf = pack("C",$c);
		return $this->writeBytes($buf);
	}
	function writeShort($s) {
		$buf = pack("S",$s);
		return $this->writeBytes($buf);
	}
	function writeInt($i) {
		$buf = pack("I",$i);
		return $this->writeBytes($buf);
	}
	function writeLong($l) {
		$buf = pack("L",$l);
		return $this->writeBytes($buf);
	}
	function write3ByteDate($d) {
		$t = getdate($d);
		return $this->writeChar($t["year"] % 1000) + $this->writeChar($t["mon"]) + $this->writeChar($t["mday"]);
	}
	function write4ByteDate($d) {
		$t = getdate($d);
		return $this->writeShort($t["year"]) + $this->writeChar($t["mon"]) + $this->writeChar($t["mday"]);
	}
	
	// General functions
	function xbase_add_record($xbase_identifier=false,$record) { // - Add a record (array of values) to a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$r = $xbase->appendRecord();
		foreach ($record as $i=>$v) {
			if (is_object($i)) {
				$r->setString($i,$v);
			}
			else if (is_numeric($i))  {
				$r->setStringByIndex($i,$v);
			}
			else {
				$r->setStringByName($i,$v);
			}
		}
		$xbase->writeRecord();
		return $xbase->getRecordCount();
	}
	function xbase_close($xbase_identifier=false) { // - Close a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$xbase->close();
	}
	function xbase_create($filename,$fields) { // - Creates a dBase database
		if ($xbase = XBaseWritableTable::create($filename,$fields)) return $this->xbase_addInstance($xbase);
		return false;
	}
	function xbase_delete_record($xbase_identifier=false,$record,$filename="") { // - Deletes a record from a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$xbase->moveTo($record-1);
		$xbase->deleteRecord();
	}
	function xbase_undelete_record($xbase_identifier=false,$record,$filename="") { // - Deletes a record from a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$xbase->moveTo($record-1);
		$xbase->undeleteRecord();
	}
	function xbase_get_header_info($xbase_identifier=false) { // - Get the header info of a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$result = array();
		foreach ($xbase->columns as $column) {
			$result[] = array(
				"name"=>$column->name, 
				"type"=>$column->type, 
				"length"=>$column->length, 
				"precision"=>$column->decimalCount, 
				"format"=>"%s", 
				"offset"=>$column->bytePos,
			);
		}
		return $result;
	}
	function xbase_get_record_with_names($xbase_identifier=false,$record) { // - Gets a record from a dBase database as an associative array 
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$r = $xbase->moveTo($record-1);
		$result = array();
		foreach ($xbase->columns as $column) {
			$result[$column->name] = $r->getString($column);
		}
		$result["deleted"] = $r->isDeleted();
		return $result;
	}
	function xbase_get_record($xbase_identifier=false,$record) { // - Gets a record from a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$r = $xbase->moveTo($record-1);
		$result = array();
		foreach ($xbase->columns as $column) {
			$result[] = $r->getString($column);
		}
		$result["deleted"] = $r->isDeleted();
		return $result;
	}
	function xbase_numfields($xbase_identifier=false) { // - Find out how many fields are in a dBase database 
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		return $xbase->getColumnCount();
	}
	function xbase_numrecords($xbase_identifier=false) { // - Find out how many records are in a dBase database 
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		return $xbase->getRecordCount();
	}
	function xbase_open($filename,$flags=0) { // - Opens a dBase database - flags : Typically 0 means read-only, 1 means write-only, and 2 means read and write
		if ($flags==0) {
			$xbase = new XBaseTable($filename);
			if (!$xbase->open()) return false;
		} else {
			$xbase = new XBaseWritableTable($filename);
			if (!$xbase->openWrite()) return false;
		}
		return $this->xbase_addInstance($xbase);
	}
	function xbase_pack($xbase_identifier=false) { // - Packs a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$xbase->pack();
	}
	function xbase_replace_record($xbase_identifier=false,$record,$record_number) { // - Replace a record in a dBase database
		if (!($xbase=$this->xbase_getInstance($xbase_identifier))) return false;
		$r = $xbase->moveTo($record_number-1);
		foreach ($record as $i=>$v) {
			if (is_object($i))
				$r->setString($i,$v);
			else if (is_numeric($i)) 
				$r->setStringByIndex($i,$v);
			else 
				$r->setStringByName($i,$v);
		}
		$xbase->writeRecord();
	}
	
	/**
	*	private
	*/
	var $xbase_instances = array();
	function &xbase_getInstance($i=NULL) {
		global $xbase_instances;
		if (sizeof($xbase_instances)==0) trigger_error ("No xbases available", E_USER_ERROR);
		if (is_null($i)) {
			$result = current($xbase_instances);
		} else {
			if (!@$xbase_instances[$i]) trigger_error ($i." is an invalid xbase identifier", E_USER_ERROR);
			$result = $xbase_instances[$i];
		}
		return $result;
	}
	function xbase_addInstance(&$i) {
		global $xbase_instances;
		$result = sizeof($xbase_instances);
		$xbase_instances[$result]=$i;
		return $result;
	}
	
	function sysGetFields($strTable="", $arrAvoid = array(), $boolWithType = false, $boolWithComment = false) {
		if($strTable!="") {
			$sql = "SELECT * FROM `" . $strTable . "` LIMIT 1 ";
			$this->db->query($sql);
		}
		$arrFields = array();
		for($ifield = 0; $ifield < mysqli_num_fields($this->db->query_id()); $ifield++) {
			//$strFieldName = mysql_field_name($this->db->query_id(), $ifield); // for php < 5.5
			$strFieldName = mysqli_fetch_field_direct($this->db->query_id(), $ifield)->name;
			if(!in_array($strFieldName, $arrAvoid)) {
				$arrFields["field"][] = $strFieldName;
				if($boolWithType) {
					//$arrFields["type"][] = mysql_field_type($this->db->query_id(), $ifield); // for php < 5.5
					$strTempType =  mysqli_fetch_field_direct($this->db->query_id(), $ifield)->type;
					$arrFields["type"][] = (isset($this->mysql_data_type_hash[$strTempType])?$this->mysql_data_type_hash[$strTempType]:$strTempType);
				}if($boolWithComment) {
					$arrFields["comment"][] = $this->db->fieldComment(constant("MY_DB_DATABASE"), $strTable, $strFieldName);
				}
			}
		}
		return $arrFields;
	}
}
?>