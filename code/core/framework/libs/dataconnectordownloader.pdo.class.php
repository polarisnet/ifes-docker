<?php
	require_once DIR_LIBS.'/pdo.class.php';

	class DataConnectorDownloader{
		var $objPDO;
		var $totalRow;
		var $isGST = false;

		function DataConnectorDownloader(){
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
					$this->objPDO = new OZPDO(array(
						"mode" => "foxpro",
						"dir" => $GLOBALS["siteSetting"]["emas_directory"],
						"encoding" => $GLOBALS["siteSetting"]["emas_encoding"]
					));
					$this->checkEMASGSTVersion();
				break;
				case "ubs":
					$this->objPDO = new OZPDO(array(
						"mode" => "foxpro",
						"dir" => $GLOBALS["siteSetting"]["ubs_directory"],
						"encoding" => $GLOBALS["siteSetting"]["ubs_encoding"]
					));
					$this->isGST = $GLOBALS["siteSetting"]["gst_version"];
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
				break;
			}
		}

		function getTotalRow(){
			return $this->totalRow;
		}

		function getCloudItemField($table){
			$output = array();
			$appURL = getAppURL();
			if($appURL != ""){
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
				$postData = array(
					"api" => "dataconnector",
					"opt" => "get_item_field",
					"table" => $table
				);
				curl_setopt($connectionApp, CURLOPT_POSTFIELDS, $postData);
				$appResponse = curl_exec($connectionApp);
				if($appResponse){
					$response = json_decode($appResponse, true);
					if(isset($response) && $response['success'] == "1"){
						$output = $response['field'];
					}
				}
			}
			return $output;
		}

		function listCloudSalesOrders($condition, $start, $limit, $select){
			$output = array();
			$appURL = getAppURL();
			$this->totalRow = 0;
			if($appURL != ""){
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
				$postData = array(
					"api" => "dataconnector",
					"opt" => "get_list_sales_orders",
					"condition" => $condition,
					"start" => $start,
					"limit" => $limit,
					"select" => $select
				);
				curl_setopt($connectionApp, CURLOPT_POSTFIELDS, $postData);
				$appResponse = curl_exec($connectionApp);
				if($appResponse){
					$response = json_decode($appResponse, true);
					if(isset($response) && $response['success'] == "1"){
						$output = $response['table'];
						$this->totalRow = $response['total'];
					}
				}
			}
			return $output;
		}

		function getSalesOrder($id){
			$output = array();
			$appURL = getAppURL();
			if($appURL != ""){
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
				$postData = array(
					"api" => "dataconnector",
					"opt" => "get_sales_order",
					"id" => $id
				);
				curl_setopt($connectionApp, CURLOPT_POSTFIELDS, $postData);
				$appResponse = curl_exec($connectionApp);
				if($appResponse){
					$response = json_decode($appResponse, true);
					if(isset($response) && $response['success'] == "1"){
						$output = $response['data'];
					}
				}
			}
			return $output;
		}

		function setSalesOrderStatus($id, $identifier, $mode){
			$output = array("status" => false, "message" => "Could not set sales order status.");
			$appURL = getAppURL();
			if($appURL != ""){
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
				$postData = array(
					"api" => "dataconnector",
					"opt" => "set_sales_order",
					"set_mode" => $mode
				);
				if(is_array($id)){
					$postData['id'] = implode(";", $id);
				}else{
					$postData['id'] = $id;
				}
				if(is_array($identifier)){
					$postData['update_id'] = implode(";", $identifier);
				}else{
					$postData['update_id'] = $identifier;
				}
				curl_setopt($connectionApp, CURLOPT_POSTFIELDS, $postData);
				$appResponse = curl_exec($connectionApp);
				if($appResponse){
					$response = json_decode($appResponse, true);
					if(isset($response)){
						if($response['success'] == "1"){
							$output['status'] = true;
						}else{
							$output['message'] = $response['message'];
						}
					}
				}
			}
			return $output;
		}

		function downloadSalesOrder($soData){
			$output = array("status" => false, "message" => "General error detected when downloading sales order ".$soData['header']['so_no'].".");
			$revert = false;
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
					$entry = $this->formatEmasEntry($soData['header']['so_no'], 10, "0");
					$this->objPDO->delete("icso", " AND entry='$entry'", array());
					$this->objPDO->delete("icsotr", " AND entry='$entry'", array());
					$this->objPDO->delete("icsotr1", " AND entry='$entry'", array());
				
					$insertData = array();
					$insertData['entry'] = $entry;
					$insertData['so_no'] = $soData['header']['so_no'];
					$insertData['custno'] = $soData['header']['cust_no'];
					$insertData['name'] = $soData['header']['cust_name'];
					$insertData['date'] = $soData['header']['date'];
					$insertData['agent'] = $soData['header']['agent_code'];
					$soData['header']['terms'] = str_replace("\r\n", " ", $soData['header']['terms']);
					$soData['header']['terms'] = str_replace("\n", " ", $soData['header']['terms']);
					$soData['header']['terms'] = str_replace("\r", " ", $soData['header']['terms']);
					$insertData['term'] = $soData['header']['terms'];
					if(strtolower($GLOBALS["siteSetting"]["emas_main_currency"]) == strtolower($soData['header']['currency_code'])){
						$insertData['currcode'] = "";
					}else{
						$insertData['currcode'] = $soData['header']['currency_code'];
					}
					$tmpAddress = explode("\n", $soData['header']['invoice_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['add'.($tmpKey+1)] = trim($tmpVal);
						}
					}
					$tmpAddress = explode("\n", $soData['header']['delivery_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['daddr'.($tmpKey+1)] = trim($tmpVal);
						}
					}
					if(preg_match("/\p{Han}+/u", $soData['header']['remarks'])){
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = $this->mb_str_split($tmpRemarks);

						if(!empty($tmpRemarks)){							
							$reVal = 1;
							foreach($tmpRemarks AS $key => $value) {
								if(($key+1)/22 == 1) {
									$reVal += 1;
								}else if(($key+1)/22 == 2) {
									$reVal += 1;
								}
								if ($reVal == 3) {
									break;
								}
								if(!isset($insertData['remark'.$reVal])) {
									$insertData['remark'.$reVal] = "";
								}
								$insertData['remark'.$reVal] .= trim($value); 
							}
						}
					}else{
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = str_split($tmpRemarks, 40);
						if(!empty($tmpRemarks)){
							foreach($tmpRemarks AS $tmpKey => $tmpVal){
								if (($tmpKey+1) == 3) {
									break;
								}
								$insertData['remark'.($tmpKey+1)] = trim($tmpVal);
							}
						}
					}
					$customerData = $this->objPDO->select("SELECT * FROM arcust WHERE custno = '".$soData['header']['cust_no']."'", array());
					if(!empty($customerData)){
						$insertData['business'] = $this->emasRemoveIlegalUTF($customerData['business']);
						$insertData['area'] = $this->emasRemoveIlegalUTF($customerData['area']);
						$insertData['name'] = $this->emasRemoveIlegalUTF($customerData['name']);
						$insertData['name2'] = $this->emasRemoveIlegalUTF($customerData['name2']);
						$insertData['add1'] = $this->emasRemoveIlegalUTF($customerData['add1']);
						$insertData['add2'] = $this->emasRemoveIlegalUTF($customerData['add2']);
						$insertData['add3'] = $this->emasRemoveIlegalUTF($customerData['add3']);
						$insertData['add4'] = $this->emasRemoveIlegalUTF($customerData['add4']);
						$insertData['daddr1'] = $this->emasRemoveIlegalUTF($customerData['daddr1']);
						$insertData['daddr2'] = $this->emasRemoveIlegalUTF($customerData['daddr2']);
						$insertData['daddr3'] = $this->emasRemoveIlegalUTF($customerData['daddr3']);
						$insertData['daddr4'] = $this->emasRemoveIlegalUTF($customerData['daddr4']);
					}
					$insertData['n_amt'] = $soData['header']['total_amount'];
					$insertData['t_amt'] = $insertData['n_amt'];
					if($this->isGST){
						$insertData['taxcode'] = $soData['header']['tax'];
						$insertData['t_line'] = count($soData['body']) + count($soData['footer']);
						if($GLOBALS["siteSetting"]["gst_inclusive"] == "1"){
							$insertData['taxamt'] = $soData['header']['tax_amount'];
						}else{
							$insertData['t_amt'] -= $soData['header']['tax_amount'];
							$insertData['taxamt'] = "0";
						}
					}else{
						$insertData['t_line'] = count($soData['body']);
						foreach($soData['footer'] AS $fK => $fV){
							$insertData['t_amt'] -= $fV['amount_1'];
							if($fK < 8){ //not GST
								$insertData['miscdesc'.($fK+1)] = $fV['name'];
								$insertData['miscamt'.($fK+1)] = $fV['amount_1'];
							}
						}
					}
					if($this->objPDO->insert("icso", $insertData)){
						foreach($soData['body'] AS $bodyLine => $bodyData){
							$tmpBody = array();
							$tmpBody['n3type'] = strtoupper($bodyData['type']);
							if($tmpBody['n3type'] == 'B'){
								$tmpBody['n3type'] = 'C';
							}
							$tmpBody['so_no'] = $soData['header']['so_no'];
							$tmpBody['entry'] = $entry;
							$tmpBody['entry2'] = str_pad($bodyData['row'], 10, "0", STR_PAD_LEFT);
							$tmpBody['line_no'] = $bodyData['row'];
							$tmpBody['location'] = $bodyData['location'];
							$tmpBody['desc1'] = $bodyData['item_name'];
							if($bodyData['delivery_date'] != "0000-00-00 00:00:00"){
								$tmpBody['expected'] = $bodyData['delivery_date'];
							}else{
								$tmpBody['expected'] = $soData['header']['date'];
							}
							$tmpBody['amount'] = $bodyData['amount'];
							if($tmpBody['n3type'] == 'M'){
								$tmpBody['misccode'] = $bodyData['item_no'];
							}else{
								$tmpBody['item_no'] = $bodyData['item_no'];
								$tmpBody['qty'] = $bodyData['qty'];
								$tmpBody['u_measure'] = $bodyData['qty_uom'];
								$tmpBody['qty1'] = $bodyData['free_qty'];
								$tmpBody['u_measure1'] = $bodyData['free_qty_uom'];
								$tmpBody['price'] = $bodyData['price'];	
								if($bodyData['disc_mode'] == 'per'){
									$tmpBody['itemdisc'] = $bodyData['disc_amount'];
								}else{
									if($tmpBody['price'] != '0'){
										$tmpBody['itemdisc'] = $this->convertDiscountValToPer(number_format($bodyData['price']*$bodyData['qty'], 2, '.', ''), $this->convertTierDiscount("val", $bodyData['disc_amount']));
									}else{
										$tmpBody['itemdisc'] = 0;
									}
								}
								$tmpBody['netprice'] = number_format($tmpBody['price']- number_format(($tmpBody['price']*$this->convertTierDiscount('per', $tmpBody['itemdisc'])/100), 2, '.', ''), 2, '.', '');
								
							}
							if($this->isGST){
								if($GLOBALS["siteSetting"]["gst_inclusive"] == "1"){
									$tmpBody['taxamt'] = $bodyData['tax_final_amount'];
								}else{
									$tmpBody['taxamt'] = "0";
								}
							}
							if($this->isGST && $tmpBody['n3type'] != 'C'){
								$tmpBody['taxcode'] = $bodyData['tax'];
								if($soData['tax']['tax_lockdown'] == "1"){
									$tmpBody['taxcode'] = $soData['header']['tax'];
								}
							}
							if(!$this->objPDO->insert("icsotr", $tmpBody)){
								$revert = true;
								$output['message'] = "Cannot save sales order details ".$soData['header']['so_no']." line ".($bodyLine+1).".";
								break;
							}
						}

						if(!$revert){
							$updateHeader = array();
							if($this->isGST){
								$line = count($soData['body'])+1;
								foreach($soData['footer'] AS $footerLine => $footerData){
									$tmpBody = array();	
									$tmpBody['n3type'] = 'M';
									$tmpBody['so_no'] = $soData['header']['so_no'];
									$tmpBody['entry'] = $entry;
									$tmpBody['entry2'] = str_pad($line, 10, "0", STR_PAD_LEFT);
									$tmpBody['line_no'] = $line;
									$tmpBody['desc1'] = $footerData['name'];
									$tmpBody['misccode'] = $footerData['code'];
									$tmpBody['amount'] = $footerData['amount_1'];
									$tmpBody['taxcode'] = $footerData['tax'];
									if($soData['tax']['tax_lockdown'] == "1" && $tmpBody['taxcode'] == ''){
										$tmpBody['taxcode'] = $soData['header']['tax'];
									}
									if(!$this->objPDO->insert("icsotr", $tmpBody)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line ".($footerLine+1).".";
										break;
									}
									$line++;
								}
								if($soData['header']['tax_amount'] != '0' && $GLOBALS["siteSetting"]["gst_inclusive"] == "0"){
									$tmpFooter = array();
									$tmpFooter['entry'] = $entry;
									$tmpFooter['entry2'] = str_pad('1', 10, "0", STR_PAD_LEFT);
									$tmpFooter['code'] = $soData['header']['tax'];
									$tmpFooter['desc'] = 'GST @ '.number_format($soData['header']['tax_ori_amount'], 0, '.', '').'%';
									$tmpFooter['percent'] = $soData['header']['tax_ori_amount'];
									$tmpFooter['amount'] = $soData['header']['tax_amount'];
									$tmpFooter['value'] = $soData['header']['total_amount']-$soData['header']['tax_amount'];
									if(!$this->objPDO->insert("icsotr1", $tmpFooter)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line GST footer.";
										break;
									}
									$updateHeader['miscdesc1'] = $tmpFooter['desc'];
									$updateHeader['miscamt1'] = $tmpFooter['amount'];
								}
							}else{
								foreach($soData['footer'] AS $footerLine => $footerData){
									$tmpFooter = array();
									$tmpFooter['entry'] = $entry;
									$tmpFooter['entry2'] = str_pad($footerData['row'], 10, "0", STR_PAD_LEFT);
									$tmpFooter['code'] = $footerData['code'];
									$tmpFooter['desc'] = $footerData['name'];
									$tmpFooter['line_no'] = $footerData['row'];
									$tmpFooter['amount'] = $footerData['amount_1'];
									if($footerData['type'] == 'discount' && $footerData['mode'] == 'per'){
										$tmpFooter['percent'] = $footerData['amount'];
										$tmpFooter['desc'] .= ' '.$footerData['amount'].'%';
									}
									if(!$this->objPDO->insert("icsotr1", $tmpFooter)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line ".($footerLine+1).".";
										break;
									}
									$updateHeader['miscdesc'.$tmpFooter['line_no']] = $tmpFooter['desc'];
									$updateHeader['miscamt'.$tmpFooter['line_no']] = $tmpFooter['amount'];
								}
								if($soData['header']['tax_amount'] != '0' && $GLOBALS["siteSetting"]["gst_inclusive"] == "0"){
									$taxLine = count($soData['footer']);
									if($taxLine == 0){
										$taxLine = 1;
									}
									$tmpFooter = array();
									$tmpFooter['entry'] = $entry;
									$tmpFooter['line_no'] = $taxLine;
									$tmpFooter['entry2'] = str_pad($tmpFooter['line_no'], 10, "0", STR_PAD_LEFT);
									$tmpFooter['code'] = $soData['header']['tax'];
									$tmpFooter['desc'] = 'GST @ '.number_format($soData['header']['tax_ori_amount'], 0, '.', '').'%';
									$tmpFooter['percent'] = $soData['header']['tax_ori_amount'];
									$tmpFooter['amount'] = $soData['header']['tax_amount'];
									$tmpFooter['value'] = $soData['header']['total_amount']-$soData['header']['tax_amount'];
									if(!$this->objPDO->insert("icsotr1", $tmpFooter)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line GST footer.";
										break;
									}
									$updateHeader['miscdesc'.$tmpFooter['line_no']] = $tmpFooter['desc'];
									$updateHeader['miscamt'.$tmpFooter['line_no']] = $tmpFooter['amount'];
									$updateHeader['t_amt'] = $insertData['t_amt'] - $soData['header']['tax_amount'];
								}
							}
							if(!empty($updateHeader) && !$revert){
								if(!$this->objPDO->update("icso", $updateHeader, "AND entry='$entry'", array())){
									$revert = true;
									$output['message'] = "Cannot update sales order for miscellaneous item.";
									break;
								}
							}
						}
					}
				break;
				case "ubs":
					$objOLEPDO = new OZPDO(array(
						"mode" => "foxpro-oledb",
						"dir" => $GLOBALS["siteSetting"]["ubs_directory"],
						"encoding" => $GLOBALS["siteSetting"]["ubs_encoding"],
						"table" => "arcust"
					));

					$entry = $this->formatEmasEntry($soData['header']['so_no'], 10, "0");
					$this->objPDO->delete("arpso", " AND refno='$entry'", array());
					$this->objPDO->delete("icpso", " AND refno='$entry'", array());
					$this->objPDO->delete("ic_memo", " AND refno='$entry'", array());
					if($soData['currency']['conversion'] == 0){
						$soData['currency']['conversion'] = 1;
					}

					$insertData = array();
					$insertData['type'] = "SO";
					$insertData['refno'] = $entry;
					$insertData['trancode'] = str_pad("1", 4, "0", STR_PAD_LEFT);
					$insertData['custno'] = $soData['header']['cust_no'];
					if($this->checkUBSFinancePeriodExpired()){
						$output['message'] = 'Financial period have expired. Please set a new period before exporting';
						break;
					}
					$insertData['fperiod'] = $this->getUBSFinancePeriod($soData['header']['date']);
					$insertData['name'] = $soData['header']['cust_name'];
					$insertData['date'] = $soData['header']['date'];
					$insertData['currrate'] = $soData['currency']['conversion'];
					$insertData['currrate2'] = $soData['currency']['conversion'];
					$insertData['pla_dodate'] = $soData['header']['date'];
					$insertData['trdatetime'] = date("Y-m-d H:i:s"); 
					$insertData['note'] = $soData['header']['tax'];
					$insertData['agenno'] = $soData['header']['agent_code'];
					$insertData['term'] = $soData['header']['terms'];
					$insertData['gross_bil'] = $soData['header']['total_amount'];
					$insertData['grand'] = $soData['header']['total_amount'];
					$insertData['grand_bil'] = $soData['header']['total_amount'];
					$insertData['debitamt'] = $soData['header']['total_amount'];
					$insertData['debit_bil'] = $soData['header']['total_amount'];
					$insertData['net'] = $soData['header']['total_amount'];
					$insertData['net_bil'] = $soData['header']['total_amount'];
					$insertData['invgross'] = $soData['header']['total_amount'];
					$insertData['urgency'] = ".F.";
					$insertData['taxincl'] = ".F."; 
					$insertData['created_by'] = substr($GLOBALS['siteSetting']['download_data_as'], 0, 8);
					$insertData['created_on'] = date('Y-m-d H:i:s');
					if(preg_match("/\p{Han}+/u", $soData['header']['remarks'])) {
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = $this->mb_str_split($tmpRemarks);
						if(!empty($tmpRemarks)){
							$reVal = 0;
							foreach($tmpRemarks AS $key => $value){
                                if(($key+1)/22 == 1){
                                    $reVal += 1;
                                }else if(($key+1)/22 == 2){
                                    $reVal += 1;
                                }
								if($reVal == 3){
									break;
								}
								if(!isset($insertData['rem'.$reVal])){
									$insertData['rem'.$reVal] = "";
								}
								$insertData['rem'.$reVal] .= trim($value);
							}
						}
					}else{
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = str_split($tmpRemarks, 40);
						if(!empty($tmpRemarks)){
							foreach($tmpRemarks AS $tmpKey => $tmpVal){
								if(($tmpKey+1) == 3){
									break;
								}
								$insertData['rem'.($tmpKey+1)] = trim($tmpVal);
							}
						}
					}
					$customerData = $objOLEPDO->select("SELECT * FROM arcust WHERE custno = '".$soData['header']['cust_no']."'", array());
					if(!empty($customerData)){
						$insertData['business'] = $this->emasRemoveIlegalUTF($customerData['business']);
						$insertData['area'] = $this->emasRemoveIlegalUTF($customerData['area']);
					}
					if($this->isGST == "1"){ 
						if($soData['header']['tax'] == "" || $soData['header']['tax'] == "STAX"){
							$insertData['note'] = 'SR';
						}else{
							$insertData['note'] = $soData['header']['tax'];
						}							
						$insertData['net'] -= $soData['header']['tax_amount']; 
						$insertData['net_bil'] -= $soData['header']['tax_amount'];
						$insertData['gross_bil'] -= $soData['header']['tax_amount'];
						$insertData['invgross'] -= $soData['header']['tax_amount'];							
						if($GLOBALS["siteSetting"]["gst_inclusive"] == "1"){
							$insertData['tax'] = $soData['header']['tax_amount'];
							$insertData['tax_bil'] = $soData['header']['tax_amount'];
							$insertData['wgst'] = ".T.";
						}else{
							$insertData['tax'] = "0";
							$insertData['tax_bil'] = "0";
							$insertData['wgst'] = ".F.";
						}
						if($soData['header']['tax_amount'] != '0' && $GLOBALS["siteSetting"]["gst_inclusive"] == 0){
							$insertData['frem0'] = 'GST @ '.number_format($soData['header']['tax_ori_amount'], 0, '.', '').'%';
							$insertData['taxp1'] = $soData['header']['tax_ori_amount'];
							$insertData['tax'] = $soData['header']['tax_amount'];
						}
					}else{
						if($soData['header']['tax_amount'] != '0' && $GLOBALS["siteSetting"]["gst_inclusive"] == 0){
							$insertData['frem0'] = 'GST @ '.number_format($soData['header']['tax_ori_amount'], 0, '.', '').'%';
							$insertData['taxp1'] = $soData['header']['tax_ori_amount'];
							$insertData['tax'] = $soData['header']['tax_amount'];
						}
					}
					if($this->objPDO->insert("arpso", $insertData)){
						$commentTrancode = 1; str_pad(1, 4, "0", STR_PAD_LEFT);
						$productTrancode = 1; str_pad(0, 4, "0", STR_PAD_LEFT);
						foreach($soData['body'] AS $bodyLine => $bodyData){
							$tmpBody = array();
							$tmpBody['type'] = "SO";
							$tmpBody['refno'] = $entry;
							if($bodyData['type'] == "C"){
								$tmpBody['trancode'] = str_pad($commentTrancode, 4, "0", STR_PAD_LEFT);
								$tmpBody['line'] = $bodyData['item_name'];
								$tmpBody['created_by'] = substr($GLOBALS['siteSetting']['download_data_as'], 0, 8);
								$tmpBody['created_on'] = date("Y-m-d H:i:s");
								$commentTrancode++;
								if(!$this->objPDO->insert("ic_memo", $tmpBody)){
									$revert = true;
									$output['message'] = "Cannot save sales order comment ".$soData['header']['so_no']." line ".($bodyLine+1).".";
									break;
								}
							}else{
								$tmpBody['trancode'] = str_pad($productTrancode, 4, "0", STR_PAD_LEFT);
								$tmpBody['custno'] = $soData['header']['cust_no'];
								$tmpBody['fperiod'] = $this->getUBSFinancePeriod($soData['header']['date']); 
								$tmpBody['date'] = $soData['header']['date'];
								$tmpBody['currrate'] = $soData['currency']['conversion']; 
								$tmpBody['name'] = $soData['header']['cust_name'];
								$tmpBody['itemcount'] = $productTrancode; 
								$tmpBody['location'] = $bodyData['location'];
								$tmpBody['desp'] = $bodyData['item_name'];
								$tmpBody['sign'] = '-1';
								$tmpBody['amt'] = $bodyData['amount'];
								$tmpBody['amt1'] = $bodyData['amount'];
								$tmpBody['amt_bil'] = $bodyData['amount'];
								$tmpBody['amt1_bil'] = $bodyData['amount'];
								if($bodyData['type'] == "S"){
									$tmpBody['line_code'] = "SV"; 
								}
								if($bodyData['type'] == "M"){
									$tmpBody['misccode'] = $bodyData['item_no']; 
								}else{
									$tmpBody['itemno'] = $bodyData['item_no'];
									$tmpBody['qty'] = $bodyData['qty'];
									$tmpBody['qty_bil'] = $bodyData['qty'];
									$tmpBody['unit'] = $bodyData['qty_uom'];
									$tmpBody['unit_bil'] = $bodyData['qty_uom'];
									$tmpBody['price'] = $bodyData['price'];
									$tmpBody['price_bil'] = $bodyData['price'];
									if($bodyData['disc_mode'] == 'per'){
										$tmpBody['disamt'] = $bodyData['disc_amount']; 
									}else{
										if($tmpBody['price'] != '0'){
											$tmpBody['disamt'] = $this->convertDiscountValToPer(number_format($bodyData['price']*$bodyData['qty'], 2, '.', ''), $this->convertTierDiscount("val", $bodyData['disc_amount']));
											$tmpBody['disamt_bil'] = $tmpBody['disamt'] ;
										}else{
											$tmpBody['disamt'] = 0;
											$tmpBody['disamt_bil'] = 0;
										}
									}
									$tmpBody['amt'] = number_format($tmpBody['price']- number_format(($tmpBody['price']*$this->convertTierDiscount('per', $tmpBody['disamt'])/100), 2, '.', ''), 2, '.', ''); // not confirm
								}
								if($this->isGST == "1"){
									if($bodyData['tax'] == "STAX" || $bodyData['tax'] == ""){
										$tmpBody['taxcode'] = "SR"; 
									}else{
										$tmpBody['taxcode'] = $bodyData['tax']; 
									}
									$tmpBody['taxamt'] = $bodyData['amount']*$bodyData['tax_amount']*0.01; 
									$tmpBody['taxamt_bil'] = $bodyData['amount']*$bodyData['tax_amount']*0.01;
									$tmpBody['taxpec1'] = $bodyData['tax_amount']; 
									if($soData['tax']['tax_lockdown'] == "1"){
										$tmpBody['taxcode'] = $soData['header']['tax']; 
									}
								}else{
									$tmpBody['taxamt'] = "0"; 
									$tmpBody['taxamt_bil'] = "0"; 
								}
								$tmpBody['gst_item'] = "N";
								$tmpBody['totalup'] = "N"; 
								$tmpBody['factor1'] = 1;
								$tmpBody['factor2'] = 1;
								$tmpBody['qty1'] = 1; 
								$tmpBody['qty7'] = 1;
								$tmpBody['ud_qty'] = "Y";
								$tmpBody['wgst'] = ".F.";
								$tmpBody['userid'] = substr($GLOBALS['siteSetting']['download_data_as'], 0, 8);
								$tmpBody['created_by'] = substr($GLOBALS['siteSetting']['download_data_as'], 0, 8);
								$tmpBody['created_on'] = date("Y-m-d H:i:s");
								$tmpBody['trdatetime'] = date("Y-m-d H:i:s");
								$tmpBody['time'] = date("H:i:s");
								$productTrancode++;
								if(!$this->objPDO->insert("icpso", $tmpBody)){
									$revert = true;
									$output['message'] = "Cannot save sales order details ".$soData['header']['so_no']." line ".($bodyLine+1).".";
									break;
								}
							}
						}
					}
				break;
				case "qne":
					$currentIdKey = $this->objPDO->select("SELECT id FROM salesorders WHERE salesordercode = '".$soData['header']['so_no']."'", array());
					if(!empty($currentIdKey) && $currentIdKey['id'] != ""){
						$this->objPDO->delete("salesorderdetails", " AND salesorderid=:id", array("id"=>$currentIdKey['id']));
						$this->objPDO->delete("salesorders", " AND id=:id", array("id"=>$currentIdKey['id']));
					}

					if($soData['currency']['conversion'] == 0){
						$soData['currency']['conversion'] = 1;
					}

					$insertData = array();
					$insertData['[]id'] = "NEWID()";
					$insertData['salesordercode'] = $soData['header']['so_no'];
					$insertData['salesorderdate'] = $soData['header']['date'];
					$insertData['title'] = "SALES ORDER";
					$debtorData = $this->objPDO->select("SELECT * FROM debtors WHERE companycode = '".$soData['header']['cust_no']."'", array());
					if(!empty($debtorData)){
						$insertData['debtorid'] = $debtorData['id'];
					}
					$insertData['debtorname'] = $soData['header']['cust_name'];
					$termData = $this->objPDO->select("SELECT * FROM terms WHERE term = '".$soData['header']['terms']."'", array());
					if(!empty($termData)){
						$insertData['termid'] = $termData['id'];
					}
					$agentData = $this->objPDO->select("SELECT * FROM salespersons WHERE staffcode = '".$soData['header']['agent_code']."'", array());
					if(empty($agentData)){
						$output['message'] = "Missing sales agent ".$soData['header']['agent_code']." in sales order ".$soData['header']['so_no'].".";
						break;
					}
					$insertData['salespersonid'] = $agentData['id'];
					$tmpAddress = explode("\n", $soData['header']['invoice_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['address'.($tmpKey+1)] = substr(trim($tmpVal), 0, 40);
						}
					}
					$tmpAddress = explode("\n", $soData['header']['delivery_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['doaddress'.($tmpKey+1)] = substr(trim($tmpVal), 0, 40);
						}
					}
					$currencyData = $this->objPDO->select("SELECT * FROM currencies WHERE currencycode = '".$soData['header']['currency_code']."'", array());
					if(!empty($currencyData)){
						$insertData['currencyid'] = $currencyData['id'];
					}
					$insertData['currencyrate'] = $soData['currency']['conversion'];
					$insertData['totalamount'] = $soData['header']['total_amount']-$soData['header']['tax_amount'];
					$insertData['nettotalamountlocal'] = $soData['header']['total_amount'];
					$insertData['nettotalamount'] = $soData['header']['total_amount'];
					if(preg_match("/\p{Han}+/u", $soData['header']['remarks'])){
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = $this->mb_str_split($tmpRemarks);
						if(!empty($tmpRemarks)){
							$reVal = 1;
							foreach($tmpRemarks AS $key => $value){
								if(($key+1)/22 == 1){
									$reVal += 1;
								}else if(($key+1)/22 == 2){
									$reVal += 1;
								}
								if($reVal == 4){
									break;
								}
								if(!isset($insertData['remark'.$reVal])){
									$insertData['remark'.$reVal] = "";
								}
								$insertData['remark'.$reVal] = trim($value);
							}
						}
					}else{
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = str_split($tmpRemarks, 40);
						if(!empty($tmpRemarks)){
							foreach($tmpRemarks AS $tmpKey => $tmpVal){
								if(($tmpKey+1) == 4){
									break;
								}
								$insertData['remark'.($tmpKey+1)] = trim($tmpVal); 
							}
						}
					}
					$insertData['iscancelled'] = 0;
					$insertData['isclosed'] = 0; 
					$insertData['taxtotalamount'] = $soData['header']['tax_amount'];
					$insertData['taxtotalamountlocal'] = $soData['header']['tax_amount'];
					$insertData['subtotalamount'] = $soData['header']['total_amount'];
					$insertData['subtotalamountlocal'] = $soData['header']['total_amount'];
					$insertData['istaxinclusive'] = 0;
					$insertData['optimisticlockfield'] = 0;
					if($this->objPDO->insert("salesorders", $insertData)){
						$currentIdKey = $this->objPDO->select("SELECT id FROM salesorders WHERE salesordercode = '".$soData['header']['so_no']."'", array());
						if(empty($currentIdKey)){
							$output['message'] = "Cannot get inserted GUID for sales order ".$soData['header']['so_no'].".";
							break;
						}
						foreach($soData['body'] AS $bodyLine => $bodyData){
							$tax = $bodyData['amount']*$bodyData['tax_amount']*0.01;
							$tmpBody = array();
							$tmpBody['[]id'] = "NEWID()";
							$tmpBody['salesorderid'] = $currentIdKey['id'];
							$tmpBody['pos'] = ($bodyLine+1);
							$tmpBody['description'] = $bodyData['item_name'];
							if($bodyData['type'] != "C"){
								$tmpBody['qty'] = $bodyData['qty'];
								$tmpBody['unitprice'] = $bodyData['price'];
								$tmpBody['fromdocid'] = "00000000-0000-0000-0000-000000000000";
								$tmpBody['fromdtlid'] = "00000000-0000-0000-0000-000000000000";
								$tmpBody['bundledtransactionid'] = "00000000-0000-0000-0000-000000000000";
								$tmpBody['isbundled'] = 0;
								$tmpBody['issubitem'] = 0;
								$tmpBody['amount'] = $bodyData['amount'];
								$tmpBody['amountlocal'] = $bodyData['amount'];
								$tmpBody['netamount'] = $bodyData['amount']+$tax;
								$tmpBody['netamountlocal'] = $tmpBody['netamount'];
								$tmpBody['istaxinclusive'] = 0;
								$tmpBody['taxrate'] = $bodyData['tax_amount']*0.01;
								$tmpBody['taxclass'] = 0;
								$tmpBody['taxamount'] = number_format($tax, 2);
								$tmpBody['taxamountlocal'] = $tmpBody['taxamount'];
								$tmpBody['subamount'] = $tmpBody['amount'];
								$tmpBody['subamountlocal'] = $tmpBody['amount'];
								$tmpBody['taxexclusiveamount'] = $tmpBody['amount'];
								$tmpBody['taxexclusiveamountlocal'] = $tmpBody['amount'];
								$stockData = $this->objPDO->select("SELECT * FROM stocks WHERE stockcode = '".$bodyData['item_no']."'", array());
								if(!empty($stockData)){
									$tmpBody['stockid'] = $stockData['id'];
								}else{
									$tmpBody['stockid'] = "";
								}
								$uomData = $this->objPDO->select("SELECT * FROM uoms WHERE uomcode = '".$bodyData['qty_uom']."' AND stockid = '".$tmpBody['stockid']."'", array());
								if(!empty($uomData)){
									$tmpBody['uomid'] = $uomData['id'];
								}
								$taxData = $this->objPDO->select("SELECT * FROM taxcodes WHERE taxcode = '".$this->formatQNETaxType($bodyData['tax'])."'", array());
								if(!empty($taxData)){
									$tmpBody['taxcodeid'] = $taxData['id'];
								}
							}
							if(!$this->objPDO->insert("salesorderdetails", $tmpBody)){
								$revert = true;
								$output['message'] = "Cannot save sales order details ".$soData['header']['so_no']." line ".($bodyLine+1).".";
								break;
							}
						}
					}
				break;
				case "autocount":
					//remove current DocKey
					$currentDocKey = $this->objPDO->select("SELECT dockey FROM so WHERE docno = '".$soData['header']['so_no']."'", array());
					if(!empty($currentDocKey) && $currentDocKey['dockey'] != ""){
						$this->objPDO->delete("sodtl", " AND dockey=:dockey", array("dockey"=>$currentDocKey['dockey']));
						$this->objPDO->delete("so", " AND dockey=:dockey", array("dockey"=>$currentDocKey['dockey']));
					}

					if($soData['currency']['conversion'] == 0){
						$soData['currency']['conversion'] = 1;
					}

					$insertData = array();
					$insertData['dockey'] = $this->getAutoCountNextSalesOrderDocKey();
					$insertData['docno'] = $soData['header']['so_no'];
					$insertData['docdate'] = $soData['header']['date'];
					$insertData['debtorcode'] = $soData['header']['cust_no'];
					$insertData['debtorname'] = $soData['header']['cust_name'];
					$insertData['description'] = 'SALES ORDER';
					$insertData['displayterm'] = $this->formatAutoCountTerms($soData['header']['terms']);
					$agentData = $this->objPDO->select("SELECT * FROM salesagent WHERE salesagent = '".$soData['header']['agent_code']."'", array());
					if(empty($agentData)){
						$output['message'] = "Missing sales agent ".$soData['header']['agent_code']." in sales order ".$soData['header']['so_no'].".";
						break;
					}
					$insertData['salesagent'] = $soData['header']['agent_code'];
					$tmpAddress = explode("\n", $soData['header']['invoice_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['invaddr'.($tmpKey+1)] = substr(trim($tmpVal), 0, 40);
						}
					}
					$tmpAddress = explode("\n", $soData['header']['delivery_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['deliveraddr'.($tmpKey+1)] = substr(trim($tmpVal), 0, 40);
						}
					}
					$insertData['total'] = $soData['header']['total_amount']-$soData['header']['tax_amount'];
					$insertData['currencycode'] = $soData['header']['currency_code'];
					$insertData['currencyrate'] = $soData['header']['currency_id'];
					$insertData['nettotal'] = $soData['header']['total_amount'];
					$insertData['localnettotal'] = $soData['header']['total_amount'];
					$insertData['analysisnettotal'] = $insertData['total'];
					$insertData['localanalysisnettotal'] = $insertData['total'];
					$insertData['localtax'] = $soData['header']['tax_amount'];
					$insertData['transferable'] = 'T';
					$insertData['printcount'] = "0";
					$insertData['cancelled'] = "F";
					$insertData['lastmodified'] = date('Y-m-d H:i:s');
					$insertData['lastmodifieduserid'] = substr($GLOBALS["siteSetting"]["download_data_as"], 0, 8);
					$insertData['createdtimestamp'] = date('Y-m-d H:i:s');
					$insertData['createduserid'] = substr($GLOBALS["siteSetting"]["download_data_as"], 0, 8);
					if(preg_match("/\p{Han}+/u", $soData['header']['remarks'])){
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = $this->mb_str_split($tmpRemarks);
						if(!empty($tmpRemarks)){
							$reVal = 1;
							foreach($tmpRemarks AS $key => $value) {
								if(($key+1)/22 == 1) {
									$reVal += 1;
								}else if(($key+1)/22 == 2) {
									$reVal += 1;
								}
								if ($reVal == 4) {
									break;
								}
								if(!isset($insertData['remark'.$reVal])) {
									$insertData['remark'.$reVal] = "";
								}
								$insertData['remark'.$reVal] .= trim($value);
							}
						}
					}else{
						$tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
						$tmpRemarks = str_replace("\n", " ", $tmpRemarks);
						$tmpRemarks = str_replace("\r", " ", $tmpRemarks);
						$tmpRemarks = str_split($tmpRemarks, 40);
						if(!empty($tmpRemarks)){
							foreach($tmpRemarks AS $tmpKey => $tmpVal){
								if(($tmpKey+1) == 4){
									break;
								}
								$insertData['remark'.($tmpKey+1)] = trim($tmpVal); 
							}
						}
					}
					$insertData['cansync'] = "F";
					$insertData['lastupdate'] = "0";
					$insertData['extax'] = $soData['header']['tax_amount'];
					$insertData['localextax'] = $soData['header']['tax_amount'];
					$insertData['[]guid'] = "NEWID()"; 
					$insertData['totaxcurrencyrate'] = "1";
					$insertData['totalextax'] = $insertData['total'];
					$insertData['taxableamt'] = $insertData['total'];
					$insertData['inclusivetax'] = "F";
					$insertData['isroundadj'] = "F";
					$insertData['roundingmethod'] = 4;
					$insertData['finaltotal'] = $soData['header']['total_amount'];
					$insertData['localtaxableamt'] = $insertData['total'];
					$insertData['taxcurrencytax'] = $soData['header']['tax_amount'];
					$insertData['taxcurrencytaxableamt'] = $insertData['total'];

					if($this->objPDO->insert("so", $insertData)){
						$sequence = 0;
						foreach($soData['body'] AS $bodyLine => $bodyData){
							$sequence += 16;
							$tax = $bodyData['amount']*$bodyData['tax_amount']*0.01;
							$tmpBody = array();
							$tmpBody['dockey'] = $insertData['dockey'];
							$tmpBody['seq'] = $sequence;
							$tmpBody['mainitem'] = "T";
							$tmpBody['transferable'] = 'T';
							$tmpBody['printout'] = 'T';
							$tmpBody['dtltype'] = "N";
							$tmpBody['addtosubtotal'] = "T";
							$tmpBody['stockreceived'] = "F";
							$tmpBody['rate'] = $soData['currency']['conversion'];
							if($bodyData['type'] == "C"){
								$tmpBody['description'] = $bodyData['item_name'];
							}else{
								$tmpBody['itemcode'] = $bodyData['item_no'];
								$tmpBody['description'] = $bodyData['item_name'];
								$tmpBody['uom'] = $bodyData['qty_uom'];
								$tmpBody['useruOM'] = $bodyData['qty_uom'];
								$tmpBody['qty'] = $bodyData['qty']; 
								$tmpBody['smallestqty'] = 0; 
								$tmpBody['transferedqty'] = 0; 
								$tmpBody['focqty'] = 0;
								$tmpBody['smallestunitprice'] = $bodyData['price'];
								$tmpBody['unitprice'] = $bodyData['price'];
								$tmpBody['taxtype'] = $this->formatAutoCountTaxType($bodyData['tax']);
								$tmpBody['tax'] = $tax;
								$tmpBody['subtotal'] = $bodyData['amount'];
								$tmpBody['localsubtotal'] = $bodyData['amount'];
								$tmpBody['subtotalextax'] = $bodyData['amount']; 
								$tmpBody['localtax'] = $tax;
								$tmpBody['taxableamt'] = $bodyData['amount'];
								$tmpBody['localsubtotalextax'] = $bodyData['amount'];
								$tmpBody['taxrate'] = $bodyData['tax_amount'];
								$tmpBody['localtaxableamt'] = $bodyData['amount'];
								$tmpBody['taxcurrencytax'] = $tax;
								$tmpBody['taxcurrencytaxableamt'] = $bodyData['amount'];
								if($bodyData['disc_mode'] == 'per'){
									$tmpBody['discountamt'] = $bodyData['disc_amount']; 
								}else{
									if($tmpBody['price'] != '0'){
										$tmpBody['discountamt'] = $this->convertDiscountValToPer(number_format($bodyData['price']*$bodyData['qty'], 2, '.', ''), $this->convertTierDiscount("val", $bodyData['disc_amount']));
									}else{
										$tmpBody['discountamt'] = 0;
									}
								}
								$locationData = $this->objPDO->select("SELECT * FROM location WHERE location = '".$bodyData['location']."'", array());
								if(empty($locationData)){
									$revert = true;
									$output['message'] = "Missing location ".$bodyData['location']." in sales order details ".$soData['header']['so_no']." line ".($bodyLine+1).".";
									break;
								}else{
									$tmpBody['location'] = $bodyData['location'];
								}
							}
							if($bodyData['delivery_date'] == "0000-00-00 00:00:00"){
								$tmpBody['deliverydate'] = $soData['header']['date'];
							}else{
								$tmpBody['deliverydate'] = $bodyData['delivery_date']; 
							}
							$tmpBody['[]guid'] = "NEWID()";
							$tmpBody['dtlkey'] = $this->getAutoCountNextSalesOrderDtlKey();
							if(!$this->objPDO->insert("sodtl", $tmpBody)){
								$revert = true;
								$output['message'] = "Cannot save sales order details ".$soData['header']['so_no']." line ".($bodyLine+1).".";
								break;
							}
						}
					}
				break;
			}
			if(!$revert){
				$setStatus = $this->setSalesOrderStatus($soData['header']['id'], $soData['header']['update_id'], "download");
				if(!$setStatus['status']){
					$revert = true;
					$output['message'] = $setStatus['message'];
				}
			}
			if($revert){
				$output['status'] = false;
				switch($GLOBALS["siteSetting"]["accounting_system"]){
					case "emas":
						$this->objPDO->delete("icso", " AND entry='$entry'", array());
						$this->objPDO->delete("icsotr", " AND entry='$entry'", array());
						$this->objPDO->delete("icsotr1", " AND entry='$entry'", array());
					break;
					case "ubs":
						$this->objPDO->delete("arpso", " AND refno='$entry'", array());
						$this->objPDO->delete("icpso", " AND refno='$entry'", array());
						$this->objPDO->delete("ic_memo", " AND refno='$entry'", array());
					break;
					case "qne":
						$currentIdKey = $this->objPDO->select("SELECT id FROM salesorders WHERE salesordercode = '".$soData['header']['so_no']."'", array());
						if(!empty($currentIdKey) && $currentIdKey['id'] != ""){
							$this->objPDO->delete("salesorderdetails", " AND salesorderid=:id", array("id"=>$currentIdKey['id']));
							$this->objPDO->delete("salesorders", " AND id=:id", array("id"=>$currentIdKey['id']));
						}
					break;
					case "autocount":
						$this->objPDO->delete("sodtl", " AND dockey=:dockey", array("dockey"=>$insertData['dockey']));
						$this->objPDO->delete("so", " AND dockey=:dockey", array("dockey"=>$insertData['dockey']));
					break;
				}
			}else{
				$output['status'] = true;
			}
			return $output;
		}

		function downloadSalesOrderToInvoice($soData){
			$output = array("status" => false, "message" => "General error detected when downloading sales order ".$soData['header']['so_no'].".");
			$revert = false;
			switch($GLOBALS["siteSetting"]["accounting_system"]){
				case "emas":
					$entry = $this->formatEmasEntry($soData['header']['so_no'], 10, "0");
					$this->objPDO->delete("icmast", " AND entry='$entry' AND type='IN' ", array());
					$this->objPDO->delete("ictran", " AND entry='$entry' AND type='IN' ", array());
					$this->objPDO->delete("ictran1", " AND entry='$entry' AND type='IN' ", array());
				
					$insertData = array();
					$insertData['entry'] = $entry;
					$insertData['type'] = "IN";
					$insertData['accno'] = $GLOBALS["siteSetting"]["emas_in_icmast_accno"];
					$insertData['postaccno'] = $GLOBALS["siteSetting"]["emas_in_icmast_postaccno"];
					$insertData['ref'] = $soData['header']['so_no'];
					$insertData['code'] = $soData['header']['cust_no'];
					$insertData['name'] = $soData['header']['cust_name'];
					$insertData['date'] = $soData['header']['date'];
					$insertData['agent'] = $soData['header']['agent_code'];
					$soData['header']['terms'] = str_replace("\r\n", " ", $soData['header']['terms']);
					$soData['header']['terms'] = str_replace("\n", " ", $soData['header']['terms']);
					$soData['header']['terms'] = str_replace("\r", " ", $soData['header']['terms']);
					$insertData['term'] = $soData['header']['terms'];
					if(strtolower($GLOBALS["siteSetting"]["emas_main_currency"]) == strtolower($soData['header']['currency_code'])){
						$insertData['currcode'] = "";
					}else{
						$insertData['currcode'] = $soData['header']['currency_code'];
					}
					
					$tmpAddress = explode("\n", $soData['header']['invoice_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['add'.($tmpKey+1)] = trim($tmpVal);
						}
					}
					$tmpAddress = explode("\n", $soData['header']['delivery_address']);
					if(!empty($tmpAddress)){
						foreach($tmpAddress AS $tmpKey => $tmpVal){
							$insertData['daddr'.($tmpKey+1)] = trim($tmpVal);
						}
					}
					if(preg_match("/\p{Han}+/u", $soData['header']['remarks'])) {
                        $tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
                        $tmpRemarks = $this->mb_str_split($tmpRemarks);
                        if(!empty($tmpRemarks)){
							$reVal = 1;
                            foreach($tmpRemarks AS $key => $value){
                                if(($key+1)/22 == 1){
                                    $reVal += 1;
                                }
								if(!isset($insertData['remark'.$reVal])){
									$insertData['remark'.$reVal] = "";
								}
								$insertData['remark'.$reVal] .= trim($value);
								if($reVal == 2){
									break;
								}
                            }
                        }
                    }else{
                        $tmpRemarks = str_replace("\r\n", " ", $soData['header']['remarks']);
                        $tmpRemarks = str_split($tmpRemarks, 40);
                        if(!empty($tmpRemarks)){
                            foreach($tmpRemarks AS $tmpKey => $tmpVal){
                                $insertData['remark'.($tmpKey+1)] = trim($tmpVal);
                            }
                        }
                    }
                    $customerData = $this->objPDO->select("SELECT * FROM arcust WHERE custno = '".$soData['header']['cust_no']."'", array());
					if(!empty($customerData)){
						$insertData['business'] = $this->emasRemoveIlegalUTF($customerData['business']);
						$insertData['area'] = $this->emasRemoveIlegalUTF($customerData['area']);
						$insertData['name'] = $this->emasRemoveIlegalUTF($customerData['name']);
						$insertData['name2'] = $this->emasRemoveIlegalUTF($customerData['name2']);
						$insertData['add1'] = $this->emasRemoveIlegalUTF($customerData['add1']);
						$insertData['add2'] = $this->emasRemoveIlegalUTF($customerData['add2']);
						$insertData['add3'] = $this->emasRemoveIlegalUTF($customerData['add3']);
						$insertData['add4'] = $this->emasRemoveIlegalUTF($customerData['add4']);
						$insertData['daddr1'] = $this->emasRemoveIlegalUTF($customerData['daddr1']);
						$insertData['daddr2'] = $this->emasRemoveIlegalUTF($customerData['daddr2']);
						$insertData['daddr3'] = $this->emasRemoveIlegalUTF($customerData['daddr3']);
						$insertData['daddr4'] = $this->emasRemoveIlegalUTF($customerData['daddr4']);
					}
					$insertData['n_amt'] = $soData['header']['total_amount'];
					$insertData['t_amt'] = $insertData['n_amt'];
					if($this->isGST){
						$insertData['taxcode'] = $soData['header']['tax'];
						$insertData['t_line'] = count($soData['body']) + count($soData['footer']);
						if($GLOBALS["siteSetting"]["gst_inclusive"] == "1"){
							$insertData['taxamt'] = $soData['header']['tax_amount'];
						}else{
							$insertData['t_amt'] -= $soData['header']['tax_amount'];
							$insertData['taxamt'] = "0";
						}
					}else{
						$insertData['t_line'] = count($soData['body']);
						foreach($soData['footer'] AS $fK => $fV){
							$insertData['t_amt'] -= $fV['amount_1'];
							if($fK < 8){ //not GST
								$insertData['miscdesc'.($fK+1)] = $fV['name'];
								$insertData['miscamt'.($fK+1)] = $fV['amount_1'];
							}
						}
					}
					if($this->objPDO->insert("icmast", $insertData)){
						foreach($soData['body'] AS $bodyLine => $bodyData){
							$tmpBody = array();
							$tmpBody['n3type'] = strtoupper($bodyData['type']);
							if($tmpBody['n3type'] == 'B'){
								$tmpBody['n3type'] = 'C';
							}
							$tmpBody['type'] = "IN";
							$tmpBody['sequence'] = 2;
							$tmpBody['ref'] = $soData['header']['so_no'];
							$tmpBody['entry'] = $entry;
							$tmpBody['entry2'] = str_pad($bodyData['row'], 10, "0", STR_PAD_LEFT);
							$tmpBody['line_no'] = $bodyData['row'];
							$tmpBody['location'] = $bodyData['location'];
							$tmpBody['desc1'] = $bodyData['item_name'];
							$tmpBody['amount'] = $bodyData['amount'];
							if($tmpBody['n3type'] == 'M'){
								$tmpBody['misccode'] = $bodyData['item_no'];
							}else{
								$tmpBody['item_no'] = $bodyData['item_no'];
								$tmpBody['qty'] = $bodyData['qty'];
								$tmpBody['u_measure'] = $bodyData['qty_uom'];
								$tmpBody['qty1'] = $bodyData['free_qty'];
								$tmpBody['u_measure1'] = $bodyData['free_qty_uom'];
								$tmpBody['price'] = $bodyData['price'];	
								if($bodyData['disc_mode'] == 'per'){
									$tmpBody['itemdisc'] = $bodyData['disc_amount'];
								}else{
									if($tmpBody['price'] != '0'){
										$tmpBody['itemdisc'] = $this->convertDiscountValToPer(number_format($bodyData['price']*$bodyData['qty'], 2, '.', ''), $this->convertTierDiscount("val", $bodyData['disc_amount']));
									}else{
										$tmpBody['itemdisc'] = 0;
									}
								}
								$tmpBody['netprice'] = number_format($tmpBody['price']- number_format(($tmpBody['price']*$this->convertTierDiscount('per', $tmpBody['itemdisc'])/100), 2, '.', ''), 2, '.', '');
								
							}
							if($this->isGST && $tmpBody['n3type'] != 'C'){
								$tmpBody['taxcode'] = $bodyData['tax'];
								if($soData['tax']['tax_lockdown'] == "1"){
									$tmpBody['taxcode'] = $soData['header']['tax'];
								}
							}
							if($this->isGST){
								if($GLOBALS["siteSetting"]["gst_inclusive"] == "1"){
									$tmpBody['taxamt'] = $bodyData['tax_final_amount'];
								}else{
									$tmpBody['taxamt'] = "0";
								}
							}
							if(!$this->objPDO->insert("ictran", $tmpBody)){
								$revert = true;
								$output['message'] = "Cannot save sales order details ".$soData['header']['so_no']." line ".($bodyLine+1)." (invoice).";
								break;
							}
						}

						if(!$revert){
							$updateHeader = array();
							if($this->isGST){
								$line = count($soData['body'])+1;
								foreach($soData['footer'] AS $footerLine => $footerData){
									$tmpBody = array();	
									$tmpBody['type'] = "IN";
									$tmpBody['n3type'] = 'M';
									$tmpBody['ref'] = $soData['header']['so_no'];
									$tmpBody['entry'] = $entry;
									$tmpBody['entry2'] = str_pad($line, 10, "0", STR_PAD_LEFT);
									$tmpBody['line_no'] = $line;
									$tmpBody['desc1'] = $footerData['name'];
									$tmpBody['misccode'] = $footerData['code'];
									$tmpBody['amount'] = $footerData['amount_1'];
									$tmpBody['taxcode'] = $footerData['tax'];
									if($soData['tax']['tax_lockdown'] == "1" && $tmpBody['taxcode'] == ''){
										$tmpBody['taxcode'] = $soData['header']['tax'];
									}
									if(!$this->objPDO->insert("ictran", $tmpBody)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line ".($footerLine+1)." (invoice).";
										break;
									}
									$line++;
								}
								if($soData['header']['tax_amount'] != '0' && $GLOBALS["siteSetting"]["gst_inclusive"] == "0"){
									$tmpFooter = array();
									$tmpFooter['type'] = "IN";
									$tmpFooter['entry'] = $entry;
									$tmpFooter['entry2'] = str_pad('1', 10, "0", STR_PAD_LEFT);
									$tmpFooter['code'] = $soData['header']['tax'];
									$tmpFooter['desc'] = 'GST @ '.number_format($soData['header']['tax_ori_amount'], 0, '.', '').'%';
									$tmpFooter['percent'] = $soData['header']['tax_ori_amount'];
									$tmpFooter['amount'] = $soData['header']['tax_amount'];
									$tmpFooter['value'] = $soData['header']['total_amount']-$soData['header']['tax_amount'];
									if(!$this->objPDO->insert("ictran1", $tmpFooter)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line GST footer (invoice).";
										break;
									}
									$updateHeader['miscdesc1'] = $tmpFooter['desc'];
									$updateHeader['miscamt1'] = $tmpFooter['amount'];
								}
							}else{
								foreach($soData['footer'] AS $footerLine => $footerData){
									$tmpFooter = array();
									$tmpFooter['type'] = "IN";
									$tmpFooter['entry'] = $entry;
									$tmpFooter['entry2'] = str_pad($footerData['row'], 10, "0", STR_PAD_LEFT);
									$tmpFooter['code'] = $footerData['code'];
									$tmpFooter['desc'] = $footerData['name'];
									$tmpFooter['line_no'] = $footerData['row'];
									$tmpFooter['amount'] = $footerData['amount_1'];
									if($footerData['type'] == 'discount' && $footerData['mode'] == 'per'){
										$tmpFooter['percent'] = $footerData['amount'];
										$tmpFooter['desc'] .= ' '.$footerData['amount'].'%';
									}
									if(!$this->objPDO->insert("ictran1", $tmpFooter)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line ".($footerLine+1)." (invoice).";
										break;
									}
									$updateHeader['miscdesc'.$tmpFooter['line_no']] = $tmpFooter['desc'];
									$updateHeader['miscamt'.$tmpFooter['line_no']] = $tmpFooter['amount'];
								}
								if($soData['header']['tax_amount'] != '0' && $GLOBALS["siteSetting"]["gst_inclusive"] == "0"){
									$taxLine = count($soData['footer']);
									if($taxLine == 0){
										$taxLine = 1;
									}
									$tmpFooter = array();
									$tmpFooter['type'] = "IN";
									$tmpFooter['entry'] = $entry;
									$tmpFooter['line_no'] = $taxLine;
									$tmpFooter['entry2'] = str_pad($tmpFooter['line_no'], 10, "0", STR_PAD_LEFT);
									$tmpFooter['code'] = $soData['header']['tax'];
									$tmpFooter['desc'] = 'GST @ '.number_format($soData['header']['tax_ori_amount'], 0, '.', '').'%';
									$tmpFooter['percent'] = $soData['header']['tax_ori_amount'];
									$tmpFooter['amount'] = $soData['header']['tax_amount'];
									$tmpFooter['value'] = $soData['header']['total_amount']-$soData['header']['tax_amount'];
									if(!$this->objPDO->insert("ictran1", $tmpFooter)){
										$revert = true;
										$output['message'] = "Cannot save sales order footer details ".$soData['header']['so_no']." line GST footer (invoice).";
										break;
									}
									$updateHeader['miscdesc'.$tmpFooter['line_no']] = $tmpFooter['desc'];
									$updateHeader['miscamt'.$tmpFooter['line_no']] = $tmpFooter['amount'];
									$updateHeader['t_amt'] = $insertData['t_amt'] - $soData['header']['tax_amount'];
								}
							}
							if(!empty($updateHeader) && !$revert){
								if(!$this->objPDO->update("icmast", $updateHeader, "AND entry='$entry' AND type='IN' ", array())){
									$revert = true;
									$output['message'] = "Cannot update sales order for miscellaneous item.";
									break;
								}
							}
						}
					}
				break;
			}
			if(!$revert){
				$setStatus = $this->setSalesOrderStatus($soData['header']['id'], $soData['header']['update_id'], "download_to_invoice");
				if(!$setStatus['status']){
					$revert = true;
					$output['message'] = $setStatus['message'];
				}
			}
			if($revert){
				$output['status'] = false;
				switch($GLOBALS["siteSetting"]["accounting_system"]){
					case "emas":
						$this->objPDO->delete("icmast", " AND entry='$entry' AND type='IN' ", array());
						$this->objPDO->delete("ictran", " AND entry='$entry' AND type='IN' ", array());
						$this->objPDO->delete("ictran1", " AND entry='$entry' AND type='IN' ", array());
					break;
				}
			}else{
				$output['status'] = true;
			}
			return $output;
		}

		function convertDiscountValToPer($ori, $disc){
			return $disc/$ori*100;
		}
		
		function convertTierDiscount($mode, $value){
			$array = explode("+", $value);
			$output = 0;
			if($mode == 'per'){
				$output = 1;
			}
			foreach($array AS $disc){
				if($mode == 'per'){
					$output *= (1- ($disc/100));
				}else{
					$output += number_format($disc, 2, '.', '');
				}
			}
			if($mode == 'per'){
				$output = number_format((1-$output)*100, 2, '.', '');
			}
			return $output;
		}

		/*** EMAS Special Function - Start ***/
		function checkEMASGSTVersion(){
			$tableStructure = $this->objPDO->getTableStructure("icso");
			if(!empty($tableStructure) && in_array("taxcode", $tableStructure['column'])){
				$this->isGST = true;
			}
		}

		function formatEmasEntry($value, $maxChar = 10, $padChar = "0"){
			$length = strlen($value);
			if($length > $maxChar){
				return str_pad(substr($value, 0, $maxChar), $maxChar, $padChar, STR_PAD_LEFT);
			}else{
				return str_pad(substr($value, 0, $length), $maxChar, $padChar, STR_PAD_LEFT);
			}
		}
		
		function emasRemoveIlegalUTF($value){
			$value = iconv($GLOBALS["siteSetting"]["emas_encoding"], "UTF-8", $value);
			$value = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
				 '|[\x00-\x7F][\x80-\xBF]+'.
				 '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
				 '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
				 '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
				 '', $value);
			return $value;
		}
		/*** EMAS Special Function - End ***/

		/*** UBS Special Function - Start ***/
		function checkUBSFinancePeriodExpired(){ //Check if the financial period have expired
			$date = date("Y-m-d");
			if($GLOBALS["siteSetting"]["ubs_finance_period_end"] <= $date){
				return true; //return true if expired
			}else
			{
				return false; //else return false
			}
		}

		function getUBSFinancePeriod($checkOutDate){ //get financial period, e.g. 1,2,3
			$dateStart = new DateTime($GLOBALS["siteSetting"]["ubs_finance_period_start"]);
			$dateEnd = new DateTime($GLOBALS["siteSetting"]["ubs_finance_period_end"]);
			$totalDays = date_diff($dateStart,$dateEnd);
			$totalDays = $totalDays->days; // days throughout the financial period
			
			$checkOutDate = new DateTime($checkOutDate);
			$checkOutDay = date_diff($dateStart,$checkOutDate);
			$checkOutDay = $checkOutDay->days; // the day fallen between the financial period
			
			$fPeriod = $GLOBALS["siteSetting"]["ubs_finance_period"];
			$fperiodDays = ($totalDays/$fPeriod); //numbers of days a period will have
			$rangeStart = 0;
			$rangeEnd = $fperiodDays;
			
			$output = "";
			for ($x = 1; $x <= $fPeriod; $x++){
				switch(true) {
					case (($checkOutDay >= $rangeStart) && (number_format($checkOutDay,0,"","") <= number_format($rangeEnd,0,"",""))): //the range for a period
					$output = $x;
					break;
				}
				$rangeStart += $fperiodDays;
				$rangeEnd += $fperiodDays;
			}
			return $output;
		}
		/*** UBS Special Function - End ***/

		/*** AutoCount Special Function - Start ***/
		// To find latest DocKey
		function getAutoCountNextSalesOrderDocKey(){
			$output = 100000000;
			$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM so WHERE dockey = '100000000'", array());
			if(!empty($total) && $total['total'] != 0){
				$this->totalRow = $total['total'];

				$key1 = $this->objPDO->select("SELECT MAX(dockey) AS maxkey FROM so", array());
				if(!empty($key1)){
					$key1 = $key1['maxkey'];
				}else{
					$key1 = 0;
				}

				$key2 = $this->objPDO->select("SELECT MAX(dockey) AS maxkey FROM sodtl", array());
				if(!empty($key2)){
					$key2 = $key2['maxkey'];
				}else{
					$key2 = 0;
				}
				$output = (int)max($key1, $key2)+1;
			}
			return $output;
		}

		// To find latest DtlKey
		function getAutoCountNextSalesOrderDtlKey(){
			$output = 100000000;
			$total = $this->objPDO->select("SELECT COUNT(*) AS total FROM sodtl WHERE dtlkey = '100000000'", array());
			if(!empty($total) && $total['total'] != 0){
				$this->totalRow = $total['total'];

				$key1 = $this->objPDO->select("SELECT MAX(dtlkey) AS maxkey FROM sodtl", array());
				if(!empty($key1)){
					$key1 = $key1['maxkey'];
				}else{
					$key1 = 0;
				}
				$output = (int)$key1+1;
			}
			return $output;
		}

		function formatAutoCountTerms($terms){
			$output = $terms;
			if(strtolower($terms) == "cash"){
				$output = "C.O.D.";
			}
			$temp = preg_replace('/\D/', '', $terms);
			if(isset($temp) && !empty($temp)){
				$output = "Net $temp Days";
			}
			return $output;
		}

		function formatAutoCountTaxType($taxType){
			$output = $taxType;
			if($taxType == "STAX"){
				$output = "SR_S";
			}
			return $output;
		}
		/*** AutoCount Special Function - End ***/

		/*** QNE Special Function - Start ***/
		function formatQNETaxType($taxType){
			if($taxType == "STAX"){
				$output = "SR";
			}
			return $output;
		}
		/*** QNE Special Function - End ***/
	}
?>