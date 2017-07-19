<?php
	class Donor{
		var $db;
		var $totalRow;
		
		function __construct($db){
			$this->db = $db;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function generateUID(){
			$uid = strtolower(generateSalt('30'));
			while($this->checkUIDExist($uid)){
				$uid = strtolower(generateSalt('30'));
			}
			return $uid;
		}
		
		function getFirstDateByUserID($id){
			$output = array();
			$sql = "SELECT `created_date` FROM `donations` WHERE `user_id`='".$id."' ORDER BY `created_date` ASC LIMIT 1 ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			$output = date("d M Y", strtotime($output['created_date']));
			return $output;
		}
		
		function getGivingSumByUserID($id){
			$output = array();
			$sql = "SELECT `currency_code`, SUM(`total_onetime`+`total_recurring`) as 'total' FROM `donations` WHERE `user_id`='".$id."' GROUP BY `currency_code`";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				switch($result['currency_code']){
					case 'EUR':
					$result['symbol'] = '&euro;';
					break;
					case 'USD':
					$result['symbol'] = '&dollar;';
					break;
					case 'GBP':
					$result['symbol'] = '&pound;';
					break;
				}
				$total = $result['symbol'].$result['total'];
				array_push($output, $total);
			}
			$output = implode(" and ", $output);
			return $output;
		}
		
		
		
		function listGivingHistoryField(){
			$output = array();
			/*$sql = "SHOW COLUMNS FROM `payments`, `donations`, `donations_details`";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				array_push($output, "'".$result['Field']."'");
			}*/			
			array_push($output, "'id'");
			array_push($output, "'type'");
			array_push($output, "'description'");
			array_push($output, "'currency_code'");
			array_push($output, "'amount'");
			array_push($output, "'amount_only'");
			array_push($output, "'recurring'");
			array_push($output, "'created_date'");
			array_push($output, "'created_by_format'");
			array_push($output, "'modified_date'");
			array_push($output, "'modified_by_format'");
			array_push($output, "'enc_id'");
			return $output;
		}
		
		function listGivingHistoryTotal($condition, $recent = false, $convert_array = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT CONCAT(p.`id`,'-',d.`id`,'-',dd.`id`) as `id`, p.`id` as `pid`, d.`id` as `did`, p.`user_id`, dd.`id` as `ddid`, 
					p.`type`, dd.`description`, d.`currency_code`, dd.`amount`, dd.`recurring`, d.`created_by`, d.`created_date` ";
			$sql .= "FROM `payments` p INNER JOIN `donations` d ON p.`id`=d.`payment_id` ";
			$sql .= "INNER JOIN `donations_details` dd ON d.`id`=dd.`header_id` ";
			$sql .= "WHERE 1=1";
			if($condition != ""){
				$sql .= $condition;
			}
			
			$this->totalRow = $this->db->countRow('id', 'sys_users', $condition);
			
		}
		function listGivingHistory($condition, $recent = false, $convert_array = true, $count_totalrow = false){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT CONCAT(p.`id`,'-',d.`id`,'-',dd.`id`) as `id`, p.`id` as `pid`, d.`id` as `did`, p.`user_id`, dd.`id` as `ddid`, 
					p.`type`, dd.`description`, d.`currency_code`, dd.`amount`, dd.`recurring`, d.`created_by`, d.`created_date` ";
			$sql .= "FROM `payments` p INNER JOIN `donations` d ON p.`id`=d.`payment_id` ";
			$sql .= "INNER JOIN `donations_details` dd ON d.`id`=dd.`header_id` ";
			$sql .= "WHERE 1=1";
			if($condition != ""){
				$sql .= $condition;
			}
			if($count_totalrow) {
				$this->db->query($sql);
				return $this->db->numRow();
			}
			if($recent){
				$sql .= " LIMIT 5";
			}
			//echo $sql;exit;
			
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$temp = array();
				//$temp = $result;
				$currencySymbol = "";
				$temp['designation'] = $result['description'];
				switch($result['currency_code']){
					case 'EUR':
					$currencySymbol = '&euro;';
					break;
					case 'USD':
					$currencySymbol = '&dollar;';
					break;
					case 'GBP':
					$currencySymbol = '&pound;';
					break;
				}
				$temp['amount'] = $currencySymbol.$result['amount'];
				if($result['recurring'] !== ""){
					$temp['amount'] .= " (monthly)";
				}
				$temp['date'] = date("d M Y", strtotime($result['created_date']));
				if(!$recent){
					$temp['type'] = "";
					if($result['type'] == "card"){
						$temp['type'] = "Credit Card";
					}
					$temp['download'] = '<span class="span-link">DOWNLOAD</span>';
				}
				$temp['link'] = "<a href=".HTTP_SERVER.HTTP_ROOT."/giving"." ><span class='span-link'>GIVE AGAIN</span></a>"; 
				
				if(!$recent && $convert_array){
					//convert the associative array to index base
					$temp = array_values($temp);
				}
				
				$temp['user_id'] = $result['user_id'];
				$temp['id'] = $result['id'];
				$temp['enc_id'] = rawurlencode(encryption($result['id'], $salt, true));
				$temp['raw'] = encryption($result['id'], $salt, true);
				$temp['recurring'] = $result['recurring'];
				$temp['description'] = $result['description'];
				$temp['currency_code'] = $result['currency_code'];
				$temp['amount_only'] = $result['amount'];
				$temp['created_by'] = $result['created_by'];
				$temp['created_date'] = $result['created_date'];
				array_push($output, $temp);
			}
			foreach($output AS $key => $value){
				getUserCreateModify($output[$key], $value['user_id']);
			}
			
			return $output;
		}
		function getGivingHistoryData($condition, $encrypt = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT CONCAT(p.`id`,'-',d.`id`,'-',dd.`id`) as `id`, p.`id` as `pid`, d.`id` as `did`, p.`user_id`, dd.`id` as `ddid`, 
					p.`stripe_source_id`, d.`payment_status`, d.`transaction_no`, d.`transaction_date`, d.`billing_fullname`, d.`billing_address1`, d.`billing_address2`,
					d.`billing_city`, d.`billing_state`, d.`billing_zipcode`, d.`billing_country`, d.`billing_email`, d.`billing_phone`, dd.`mode`, dd.`code`,
					p.`type`, dd.`description`, d.`currency_code`, dd.`amount`, dd.`recurring`, d.`created_by`, d.`created_date` ";
			$sql .= "FROM `payments` p INNER JOIN `donations` d ON p.`id`=d.`payment_id` ";
			$sql .= "INNER JOIN `donations_details` dd ON d.`id`=dd.`header_id` ";
			$sql .= "WHERE 1=1";
			if($condition != ""){
				$sql .= $condition;
			}
			$sql .= " LIMIT 1";
			
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$currencySymbol = "";
				$output['payment_status'] = ($result['payment_status']=="1"?"Completed":"Failed");
				$output['transaction_no'] = $result['transaction_no'];
				$output['transaction_date'] = $result['transaction_date'];
				$output['billing_fullname'] = $result['billing_fullname'];
				$output['billing_address1'] = $result['billing_address1'];
				$output['billing_address2'] = $result['billing_address2'];
				$output['billing_city'] = $result['billing_city'];
				$output['billing_state'] = $result['billing_state'];
				$output['billing_zipcode'] = $result['billing_zipcode'];
				$output['billing_country'] = $result['billing_country'];
				$output['billing_email'] = $result['billing_email'];
				$output['billing_phone'] = $result['billing_phone'];
				$output['mode'] = $result['mode'];
				$output['code'] = $result['code'];
				$output['stripe_source_id'] = $result['stripe_source_id'];
				
				$output['designation'] = $result['description'];
				switch($result['currency_code']){
					case 'EUR':
					$currencySymbol = '&euro;';
					break;
					case 'USD':
					$currencySymbol = '&dollar;';
					break;
					case 'GBP':
					$currencySymbol = '&pound;';
					break;
				}
				$output['amount'] = $currencySymbol.$result['amount'];
				if($result['recurring'] !== ""){
					$output['amount'] .= " (monthly)";
				}
				$output['date'] = date("d M Y", strtotime($result['created_date']));
				if(!$recent){
					$output['type'] = "";
					if($result['type'] == "card"){
						$output['type'] = "Credit Card";
					}
					$output['download'] = '<span class="span-link">DOWNLOAD</span>';
				}
				$output['link'] = "<a href=".HTTP_SERVER.HTTP_ROOT."/giving"." ><span class='span-link'>GIVE AGAIN</span></a>"; 
				$output['user_id'] = $result['user_id'];
				$output['id'] = $result['id'];
				$output['enc_id'] = rawurlencode(encryption($result['id'], $salt, true));
				$output['raw'] = encryption($result['id'], $salt, true);
				$output['recurring'] = $result['recurring'];
				$output['description'] = $result['description'];
				$output['currency_code'] = $result['currency_code'];
				$output['amount_only'] = $result['amount'];
				$output['created_by'] = $result['created_by'];
				$output['created_date'] = $result['created_date'];
				getUserCreateModify($output, $result['user_id']);
			}
			return $output;
		}
		
		function getSubscriptionData($id, $encrypt = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT * FROM `subscriptions` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
				switch($output['currency_code']){
					case 'EUR':
					$output['symbol'] = '&euro;';
					break;
					case 'USD':
					$output['symbol'] = '&dollar;';
					break;
					case 'GBP':
					$output['symbol'] = '&pound;';
					break;
				}
				$output['billing_date'] = ordinal($output['billing_date']);
			}
			if($encrypt){
				$output['id'] = encryption($output['id'], $salt, true);
			}
			return $output;
		}
		
		function listSubscription($condition){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT * FROM `subscriptions` WHERE 1=1";
			if($condition != ""){
				$sql .= $condition;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$temp = array();
				$encryptID = encryption($result['id'], $_SESSION['salt'], true);
				$currencySymbol = "";
				$temp['designation'] = $result['description'];
				switch($result['currency_code']){
					case 'EUR':
					$currencySymbol = '&euro;';
					break;
					case 'USD':
					$currencySymbol = '&dollar;';
					break;
					case 'GBP':
					$currencySymbol = '&pound;';
					break;
				}
				$temp['amount'] = $currencySymbol.$result['amount'];
				$temp['date'] = "day ".$result['billing_date'];
				$temp['type'] = "";
				if($result['type'] == "card"){
					$temp['type'] = "Credit Card";
				}
				$temp['modify'] = '<span class="span-link" onclick="toggleModalSubscription(\''.$encryptID.'\'); return false">MODIFY</span>';
				$temp['delete'] = '<span class="span-link" onclick="deleteRow(\'subscription\', \''.$encryptID.'\'); return false">REMOVE</span>'; 
				//convert the associative array to index base
				$temp = array_values($temp);
				array_push($output, $temp);
			}
			
			return $output;
		}
		
		function updateSubscription($data){
			if($this->db->update("subscriptions", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function deleteSubscription($id){
			if($this->db->delete("subscriptions", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function getPaymentData($id, $encrypt = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT * FROM `payments` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			if($encrypt){
				$output['id'] = encryption($output['id'], $salt, true);
			}
			return $output;
		}
		
		function listPaymentMethods($condition){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT * FROM `payments` WHERE 1=1";
			if($condition != ""){
				$sql .= $condition;
			}
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$encryptID = encryption($result['id'], $_SESSION['salt'], true);
				$temp = array();
				$temp['custom_name'] = $result['custom_name'];
				$temp['type'] = "";
				if($result['type'] == "card"){
					$temp['type'] = "Credit Card";
				}
				$temp['card_number'] = str_repeat('*', strlen($result['number']) - 4) . substr($result['number'], -4);;
				$temp['expiration'] = $result['name_1'];
				$temp['modify'] = '<span class="span-link" onclick="toggleModalPayment(\'update\', \''.$encryptID.'\'); return false">MODIFY</span>';
				$temp['delete'] = '<span class="span-link" onclick="deleteRow(\'payment\', \''.$encryptID.'\'); return false">REMOVE</span>'; 
				//convert the associative array to index base
				$temp = array_values($temp);
				array_push($output, $temp);
			}
			
			return $output;
		}
		
		function savePaymentMethod($data){
			if($this->db->insert("payments", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updatePaymentMethod($data){
			if($this->db->update("payments", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function deletePaymentMethod($id){
			if($this->db->delete("payments", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function listCountries(){
			$output = array();
			$sql = "SELECT * FROM `sys_country` ORDER BY name ASC";
			$this->db->query($sql);
			while($this->db->nextRecord()){
				array_push($output, $this->db->getRecord());
			}
			return $output;
		}
	}
?>