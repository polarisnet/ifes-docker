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
			$output = "";
			$sql = "SELECT `created_date` FROM `donations` WHERE `user_id`='".$id."' ORDER BY `created_date` ASC LIMIT 1 ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = date("d M Y", strtotime($result['created_date']));
			}
			
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
		
		function listGivingHistory($condition, $recent = false){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$sql = "SELECT d.`type`, dd.`description`, d.`currency_code`, dd.`amount`, dd.`recurring`, d.`created_date` ";
			$sql .= "FROM `donations` d INNER JOIN `donations_details` dd ON d.`id`=dd.`header_id` ";
			$sql .= "WHERE 1=1";
			if($condition != ""){
				$sql .= $condition;
			}
			if($recent){
				$sql .= " LIMIT 5";
			}
			
			$this->db->query($sql);
			while($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$temp = array();
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
				
				if(!$recent){
					//convert the associative array to index base
					$temp = array_values($temp);
				}
				
				array_push($output, $temp);
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
			$sql = "SELECT * FROM `payments` WHERE 1=1 AND `display_info` =1";
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