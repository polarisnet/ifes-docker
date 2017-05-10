<?php
	class Mailer{
		var $db;
		var $totalRow;
		
		function Mailer($db){
			$this->db = $db;
		}
		
		function getTemplateNameById($id){
			$output = "";
			$sql = "SELECT `name` FROM `sys_mailer_templates` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['name'];
			}
			return $output;
		}
		
		function getSettingsData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_mailer_settings` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function getTemplateData($id){
			$output = array();
			$sql = "SELECT * FROM `sys_mailer_templates` WHERE `id`='".$id."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function getTemplateDataByCode($code){
			$output = array();
			$sql = "SELECT * FROM `sys_mailer_templates` WHERE `code`='".$code."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function updateTemplate($data){
			if($this->db->update("sys_mailer_templates", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function updateSettings($data){
			if($this->db->update("sys_mailer_settings", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function listTemplate($condition = '', $start = 0, $limit = 0, $encrypt = true, $format = true){
			$output = array();
			if(isset($_SESSION['salt'])){$salt = $_SESSION['salt'];}else{$salt = PUBLIC_SALT;}
			$this->totalRow = $this->db->countRow('id', 'sys_mailer_templates', $condition);
			$sql = "SELECT * FROM `sys_mailer_templates` WHERE 1=1 ";
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
		
		function testMailer(){
			require_once (DIR_PLUGINS.'/php/PHPMailer/class.phpmailer.php');
			$status = false;
			
			$settingData = $this->getSettingsData('1');
			if(!empty($settingData)){
				$mail = new PHPMailer(true);
				$mail->CharSet = 'UTF-8';
				$mail->Mailer = 'smtp';
				$mail->IsSMTP();
				$mail->Host = $settingData['host'];
				$mail->Port = $settingData['port'];
				$mail->SMTPAuth = $settingData['auth'];
				$mail->SMTPSecure = "ssl";
				$mail->Username = $settingData['user'];
				$mail->Password = $settingData['pass'];
				$mail->From = $settingData['default_sender_mail'];
				$mail->FromName = $settingData['default_sender'];
				$mail->Subject = 'Test SMTP Configuration Result';
				$mail->AddAddress($_SESSION['email']);
				$mail->IsHTML(true);
				$mail->Body = "Hi ".$_SESSION['user_fullname']."<br>This message is to notify that you have correctly configure ".SITE_NAME." mailer settings.";
				try{
					$mail->Send();
					$status = true;
				}catch (phpmailerException $e){
					errorHandler('MAILER', $e->getMessage(), 'mailer.class.php', '-', '-');
				}catch (Exception $e) {
					errorHandler('MAILER', $e->getMessage(), 'mailer.class.php', '-', '-');
				}
			}else{
				errorHandler('MAILER', 'Could not load settings data', 'mailer.class.php', '-', '-');
			}
			return $status;
		}
		
		function constructTemplateHeader($settingData, $templateData){
			require_once (DIR_PLUGINS.'/php/PHPMailer/class.phpmailer.php');
			$mail = new PHPMailer(true);
			$mail->CharSet = 'UTF-8';
			$mail->Mailer = 'smtp';
			$mail->IsSMTP();
			$mail->Host = $settingData['host'];
			$mail->Port = $settingData['port'];
			$mail->SMTPAuth = $settingData['auth'];
			$mail->SMTPSecure = "ssl";
			$mail->Username = $settingData['user'];
			$mail->Password = $settingData['pass'];
			if($templateData['sender'] == '[DEFAULT]'){
				$mail->FromName = $settingData['default_sender'];
			}else{
				$mail->FromName = $templateData['sender'];
			}
			if($templateData['sender_mail'] == '[DEFAULT]'){
				$mail->From = $settingData['default_sender_mail'];
			}else{
				$mail->From = $templateData['sender_mail'];
			}
			if($templateData['reply'] == '[DEFAULT]'){
				if($settingData['default_reply_mail'] != ''){
					$mail->AddReplyTo($settingData['default_reply_mail'], $settingData['default_reply']);
				}
			}else{
				if($templateData['reply_mail'] != ''){
					$mail->AddReplyTo($templateData['reply_mail'], $templateData['reply']);
				}
			}
			$bcc = explode(";", $templateData['bcc']);
			foreach($bcc AS $key => $value){
				if($value != ""){
					$mail->AddBCC($value);
				}
			}
			return $mail;
		}
	}
?>