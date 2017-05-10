<?php
	class WebSocket{
		var $db;
		var $totalRow;
		//var $type;
		
		function WebSocket($db){
			$this->db = $db;
			//$this->type = $type;
		}
		
		function getTotalRow(){
			return $this->totalRow;
		}
		
		function getInsertedId(){
			return $this->db->getInsertedId();
		}
		
		function getSocketPopulationData($type, $resourceId){
			$data = array();
			$sql = "SELECT * FROM `websocket_population` WHERE `type`='".$type."' AND `resource_id`='".$resourceId."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$data = $this->db->getRecord();
			}
			return $data;
		}
		
		function saveSocketPopulation($data){
			if($this->db->insert("websocket_population", $data)){
				return true;
			}else{
				return false;
			}
		}
		
		function deleteSocketPopulation($type, $resourceId){
			if($this->db->delete("websocket_population", "`type`='".$type."' AND `resource_id`='".$resourceId."'")){
				return true;
			}else{
				return false;
			}
		}
		
		function getWebSocketUID($userId, $type){
			$sql = "SELECT `uid` FROM `websocket_population` WHERE `user_id`='".$userId."' AND `type`='".$type."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				return $result['uid'];
			}else{
				$uid = strtolower(generateSalt('10'));
				while($this->checkSocketUIDExist($uid)){
					$uid = strtolower(generateSalt('10'));
				}
				return $uid;
			}
		}
		
		function getWebSocketUserByUID($uid, $type){
			$sql = "SELECT `user_id` FROM `websocket_population` WHERE `uid`='".$uid."' AND `type`='".$type."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				return $result['user_id'];
			}
		}
		
		function checkSocketUIDExist($uid){
			$sql = "SELECT * FROM `websocket_population` WHERE `uid`='".$uid."' LIMIT 1";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				return true;
			}else{
				return false;
			}
		}
		
		function clearWebsocketPopulation($type){
			if($this->db->delete("websocket_population", "`type`='".$type."'")){
				$sql = "OPTIMIZE TABLE  `websocket_population`";
				$this->db->query($sql);
				return true;
			}else{
				return false;
			}
		}
		
		function getTotalSocketPopulationLeft($type, $userId){
			$count = 0;
			$sql = "SELECT COUNT(`id`) AS 'total' FROM `websocket_population` WHERE `type`='".$type."' AND `user_id`='".$userId."'";
			$this->db->query($sql);
			if($this->db->nextRecord()){
				$result = $this->db->getRecord();
				$count = $result['total'];
			}
			return $count;
		}
		
		function convertWebsocketChatMessage($input){
			
			
			//Convert hyperlinks
			$regex = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            preg_match_all($regex, $input, $matches);
            $usedPatterns = array();
            foreach($matches[0] as $pattern){
                if(!array_key_exists($pattern, $usedPatterns)){
                    $usedPatterns[$pattern] = true;
                    $input = str_replace($pattern, "<a href=".$pattern." target='_blank'>".$pattern."</a>", $input);   
                }
            }
			
			//Convert youtube
			$regex = '#<a(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*<\/a>#x';
			$replace = '<iframe width="230" height="auto" src="//www.youtube.com/embed/$2" frameborder="0" allowfullscreen></iframe>';
			$input = preg_replace($regex, $replace, $input);
			return $input;
		}
	}
?>