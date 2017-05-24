<?php
	class Donor{
		var $db;
		var $totalRow;
		
		function Donor($db){
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
		
		function checkUIDExist($uid){
			$sql = "SELECT * FROM `donors` WHERE `uid`='".$uid."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function getDonorNameById($id){
			$output = "";
			$sql = "SELECT `username` FROM `donors` WHERE `id`='".$id."' ";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$output = $result['username'];
			}
			return $output;
		}
		
		function deleteDonor($id){
			if($this->db->delete("donors", "`id`='".$id."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function saveDonor($data){
			if($this->db->insert("donors", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateDonor($data){
			if($this->db->update("donors", $data, "`id`='".$data['id']."'")){
				return true;
			}else{
				return false;
			}
		}
		
		/** Login - Start **/
		/** Login - Start **/
		function getLoginCredential($username){
			$output = array();
			$sql = "SELECT `password`, `salt` FROM `donors` WHERE LOWER(`username`)='".strtolower($username)."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
			}
			return $output;
		}
		
		function createLoginSession($username){
			// Create session
			$sql = "SELECT * FROM `donors` WHERE `username` = '".$username."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$output = $this->db->getRecord();
				$_SESSION['user_id'] = $output['id'];				
				$_SESSION['salt'] = $output['salt'];
				$_SESSION['username'] = $output['username'];
				$_SESSION['email'] = $output['email'];
				$_SESSION['uid'] = $output['uid'];
				$_SESSION['login']['token'] = generateSalt(12);
				$_SESSION['dynamic_salt'] = generateSalt(12);
				$_SESSION['enc_user_id'] =  encryption($output['id'], $_SESSION['salt'], true);
			}
			
			// Save session details to db
			$this->db->delete("sys_session", "`user_id`='".$_SESSION['user_id']."'");
			
			
			$data['session'] = session_id();
			$data['user_id'] = $_SESSION['user_id'];
			$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
			$data['created_date'] = date("Y-m-d H:i:s");
			
			if($this->db->insert("sys_session", $data)){
				$this->db->delete("sys_event_tracker", "`session`='".session_id()."'");
				return true;
			}else{
				return false;
			}
		}
		
		function createLoginCookies($remember, $username){
			$siteName = str_replace(" ", "", COOKIE_NAME);
			if($remember == "on" || $remember == "1"){
				$set_time = time() + (7*24*3600);
				setcookie($siteName."_REMEMBER_ME", $username, 0, "/", "", false, true);
			}else{
				$set_time = 0;
				setcookie($siteName."_REMEMBER_ME", "", time()-9600, "/", "", false, true);
			}
			setcookie($siteName."_SESSION[id]", session_id(), $set_time, "/", "", false, true);
			setcookie($siteName."_SESSION[token]", $_SESSION['login']['token'], $set_time, "/", "", false, true);
			setcookie($siteName."_SESSION[toggle_leftbar]", 1, $set_time, "/", "", false, false);
			setcookie($siteName."_SESSION[toggle_leftbar_initial]", 1, $set_time, "/", "", false, false);
		}
		
		function logout(){
			if(matchCookieSession()){
				$trails = array();
				$trails['session'] = session_id();
				insertAuditTrails('', 'logout', json_encode($trails));
				
				$sessionID = session_id();			
				$siteName = str_replace(" ", "", COOKIE_NAME);
				deleteAllCookies();
				session_destroy(); // delete session
				$this->db->delete("sys_session", "`session`='".$sessionID ."'");
				$this->db->delete("sys_event_tracker", "`session`='".$sessionID ."'");
				return true;
			}else{
				return false;
			}
		}
		/** Login - End **/
	}
?>