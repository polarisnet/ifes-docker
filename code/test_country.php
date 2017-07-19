<?php
	ini_set('max_execution_time', 900); //900 seconds = 15 minutes
	
	define('DIR_ROOT', dirname(__FILE__));
	define('DIR_CORE', DIR_ROOT.'/core');
	define('DIR_MEDIA', DIR_ROOT.'/media');
	define('DIR_THEME', DIR_ROOT.'/theme');
	define('DIR_MODULE', DIR_CORE.'/module');
	define('DIR_PLUGINS', DIR_CORE.'/plugins');
	define('DIR_FRAMEWORK', DIR_CORE.'/framework');
	require DIR_FRAMEWORK.'/config/site.config.php';
	require DIR_FRAMEWORK.'/config/core.config.php';
	require DIR_FRAMEWORK.'/config/date.config.php';
	require DIR_LOCALIZATION.'/en/shortcut.php';
	
	require DIR_COMMON.'/error_handler.php';
	require DIR_COMMON.'/db_open.php';
	require DIR_COMMON.'/site_setting.php';
	require DIR_COMMON.'/stdlib.php';
	
	require_once (DIR_PLUGINS.'/php/PHPExcel.php');
	
	$file = 'Country.xlsx';

	$countryList = readExcelFile($file);
	//$customerData = formatCustomerData($customerList);
	//$customerContact = getCustomerContact($customerList);
	
	/*
	foreach ($customerData as $key => $value){
		saveCustomer($value);
	}
	
	foreach ($customerContact as $key => $value){
		saveContacts($value);
	}
	*/
	
	echo "<pre>"; print_r($countryList); echo "</pre>"; exit;//debug
	
	function readExcelFile($path, $getHeader = false, $getEmptyValue = false){
		$output = array();
		
		if(is_readable($path)){
			$objPHPExcel = new PHPExcel();
			$valid = false;
			$types = array('Excel2007', 'Excel5');
			foreach ($types as $type) {
				$objReader = PHPExcel_IOFactory::createReader($type);
				if ($objReader->canRead($path)) {
					$valid = true;
					break;
				}
			}

			if($valid){
				$objReader->setReadDataOnly(true);

				$objPHPExcel = $objReader->load($path);
				$objWorksheet = $objPHPExcel->getActiveSheet();
				$objWorksheet->getProtection()->setSheet(true);
				$highestRow = $objWorksheet->getHighestRow();
				$highestColumn = $objWorksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				foreach($objWorksheet->getRowIterator() as $row){
					if(!$getHeader && $row->getRowIndex() == '1'){continue;}
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(true);
					foreach($cellIterator as $cell){
						if($cell->getValue() !== "" || $getEmptyValue){
							$output[$cell->getRow()][$cell->getColumn()] = trim($cell->getValue());
						}
					}
				}
				unset($objPHPExcel);
			} 
		}
		return $output;
	}
	/*
	function formatCustomerData($customerData){
		$output = array();
		
		foreach ($customerData as $key => $value){
			$phone1Assigned = false;
			$phone2Assigned = false;
			$temp = array();
			$temp['cust_no'] = $value['A'];
			$temp['name'] = $value['E'];
			$temp['name'] = $GLOBALS['myDB']->formatString($temp['name']);
			$temp['attention'] = $value['N'];
			$temp['attention'] = $GLOBALS['myDB']->formatString($temp['attention']);
			$temp['invoice_state'] = $value['D'];
			$temp['invoice_address'] = $value['F']."\n".$value['G']."\n".$value['H']."\n".$value['I'];
			$temp['invoice_address'] = $GLOBALS['myDB']->formatString($temp['invoice_address']);
			$temp['channel'] = $value['C'];
			$temp['team'] = "Support Team";
			$temp['area'] = $value['C'];
			if(!empty($value['J'])){
				$temp['phone1'] = $value['J'];
				$phone1Assigned = true;
			}
			if(!empty($value['K'])){
				if(!$phone1Assigned){
					$temp['phone1'] = $value['K'];
					$phone1Assigned = true;
				}else{
					$temp['phone2'] = $value['K'];
					$phone2Assigned = true;
				}
			}
			if(!empty($value['L'])){
				if(!$phone1Assigned){
					$temp['phone1'] = $value['L'];
				}else if(!$phone2Assigned){
					$temp['phone2'] = $value['L'];
				}
			}
			$temp['fax'] = $value['M'];
			$temp['business'] = $value['B'];
			$temp['created_by'] = "0";
			$temp['created_date'] = date("Y-m-d H:i:s");
			$output[] = $temp;
		}
		
		return $output;
	}
	
	function getCustomerContact($customerData){
		$output = array();
		
		foreach ($customerData as $key => $value){
			$temp = array();
			if(!empty($value['N'])){ //If Name is not empty
				$temp['first_name'] = $value['N'];
				$temp['first_name'] = $GLOBALS['myDB']->formatString($temp['first_name']);
				$temp['parent_type'] = 'Customer';
				$temp['parent_id'] = getCustIdByCode($value['A']);
				$temp['contact_state'] = $value['D'];
				$temp['contact_address'] = $value['F']."\n".$value['G']."\n".$value['H']."\n".$value['I'];
				$temp['contact_address'] = $GLOBALS['myDB']->formatString($temp['contact_address']);
				$temp['office_phone'] = $value['J'];
				$temp['mobile'] = $value['L']; 
				$temp['office_fax'] = $value['M'];
				$temp['created_by'] = "0";
				$temp['created_date'] = date("Y-m-d H:i:s");
				$output[] = $temp;
			}
		}
		
		return $output;
	}
	
	function getCustIdByCode($customerCode){
		$output = 0;
		$sql = "SELECT ID FROM `customers` WHERE `cust_no`='".$customerCode."'";
		$GLOBALS['myDB']->query($sql);
		if($GLOBALS['myDB']->nextRecord()){
			$result = $GLOBALS['myDB']->getRecord();
			$output = $result['ID'];
		}
		return $output;
	}
	
	function saveCustomer($customerData){
		if($GLOBALS['myDB']->insert("customers", $customerData)){
			return true;
		}else{
			return false;
		}
	}
	
	function saveContacts($contactsData){
		if($GLOBALS['myDB']->insert("contacts", $contactsData)){
			return true;
		}else{
			return false;
		}
	}
	*/
?>

