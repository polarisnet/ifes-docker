<?php
	error_reporting(E_ALL);
	ini_set('display_errors','Off');
	
	define('DIR_ROOT', dirname(dirname(dirname(__FILE__))));
	define('DIR_CORE', DIR_ROOT.'/core');
	define('DIR_MEDIA', DIR_ROOT.'/media');
	define('DIR_THEME', DIR_ROOT.'/theme');
	define('DIR_MODULE', DIR_CORE.'/module');
	define('DIR_PLUGINS', DIR_CORE.'/plugins');
	define('DIR_FRAMEWORK', DIR_CORE.'/framework');
	require DIR_FRAMEWORK.'/config/site.config.php';
	require DIR_FRAMEWORK.'/config/core.config.php';
	require DIR_FRAMEWORK.'/config/date.config.php';
	require DIR_FRAMEWORK.'/config/websocket.config.php';
	
	//require DIR_COMMON.'/error_handler.php';
	require DIR_COMMON.'/db_open.php';
	require DIR_COMMON.'/site_setting.php';
	require DIR_COMMON.'/stdlib.php';
	
	require DIR_LIBS.'/user.class.php';
	$objUser = new User($GLOBALS['myDB']);
	
	require DIR_LIBS.'/websocket.class.php'; 
	$objWebsocket = new WebSocket($GLOBALS['myDB']);
	$objWebsocket->clearWebsocketPopulation('chat');
	
	$host = WS_SERVER;
	$port = WS_SERVER_CHAT_PORT;
	$null = NULL;

	//Create TCP/IP sream socket
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	//Reuseable port
	socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

	//Bind socket to specified host
	socket_bind($socket, 0, $port);

	//Listen to port
	socket_listen($socket);

	//Create & add listening socket to the list
	$clients = array($socket);

	//Start endless loop
	while(true){
		//Manage multipal connections
		$changed = $clients;
		//Returns the socket resources in $changed array
		socket_select($changed, $null, $null, 0, 10);
	
		//Check for new socket
		if(in_array($socket, $changed)){
			$newSocket = socket_accept($socket); //Accept new socket
			//echo 'New'.intval($newSocket)."\n";
			$clients[] = $newSocket; //Add socket to client array
			
			$header = socket_read($newSocket, 1024); //Read data sent by the socket
			performHandshaking($header, $newSocket, $host, $port); //Perform websocket handshake
			
			socket_getpeername($newSocket, $ip); //Get ip address of connected socket
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //Prepare json data
			sendSocketMessage($response); //Notify all users about new connection
			
			//Make room for new socket
			$foundSocket = array_search($socket, $changed);
			unset($changed[$foundSocket]);
		}
		
		//Loop through all connected sockets
		foreach($changed as $changedSocket){	
			//Check for any incomming data
			while(socket_recv($changedSocket, $buf, 1024, 0) >= 1){
				$receivedText = unmask($buf); //Unmask data
				$receivedData = json_decode($receivedText, true); //Json decode 
			
				if(!empty($receivedData)){
					socket_getpeername($changedSocket, $ip);
					$foundSocket = array_search($changedSocket, $clients);
					$resourceId = $foundSocket;
					$userId = encryption(rawurldecode($receivedData['id']), PUBLIC_SALT, false);
					$userData = $objUser->getUserData($userId);
					
					switch($receivedData['mode']){
						case 'register':
							$newData = array();
							$newData['user_id'] = $userId;			
							if(!empty($userData)){
								$newData['username'] = $userData['username'];
								$newData['display_name'] = trim($userData['first_name'].' '.$userData['last_name']);
							}else{
								$newData['username'] = '';
								$newData['display_name'] = '';
							}
							$newData['type'] = 'chat';
							$newData['ip'] = $ip;
							$newData['resource_id'] = $resourceId;
							$newData['created_time'] = date("Y-m-d H:i:s");
							$newData['uid'] = $objWebsocket->getWebSocketUID($newData['user_id'], $newData['type']);
							$objWebsocket->saveSocketPopulation($newData);
							
							unset($newData['created_time']);
							unset($newData['resource_id']);
							unset($newData['username']);
							unset($newData['ip']);
							$newData['user_id'] = $receivedData['id'];
							$newData['avatar'] = getDefaultPicture($userData['uid']);
							$newData['mode'] = 'insert';
							sendSocketMessage(mask(json_encode($newData)));
						break;
						case 'message':
							$replyData = array();
							$replyData['sender_id'] = $receivedData['id'];
							$replyData['sender_name'] = trim($userData['first_name'].' '.$userData['last_name']);
							$replyData['sender_uid'] = $objWebsocket->getWebSocketUID($userId, 'chat'); 
							$replyData['target_uid'] = $receivedData['uid'];
							if($replyData['target_uid'] != 'broadcast'){
								$targetId = $objWebsocket->getWebSocketUserByUID($replyData['target_uid'], 'chat');
								$targetUserData = $objUser->getUserData($targetId);
								$replyData['target_id'] =  rawurlencode(encryption($targetUserData['id'], PUBLIC_SALT, true));
								$replyData['target_name'] = trim($targetUserData['first_name'].' '.$targetUserData['last_name']);
							}else{
								$replyData['target_id'] = 'broadcast';
								$replyData['target_name'] = 'broadcast';
							}
							$replyData['date'] = date('h:i');
							$replyData['message'] = $objWebsocket->convertWebsocketChatMessage($receivedData['message']);
							$replyData['mode'] = 'message';
							sendSocketMessage(mask(json_encode($replyData)));
						break;
					}
				}
				break 2; //Exit this loop
			}
			
			
			$buf = @socket_read($changedSocket, 1024, PHP_NORMAL_READ);
			if ($buf === false) { //Check disconnected client
				//Remove client for $clients array
				$foundSocket = array_search($changedSocket, $clients);
				socket_getpeername($changedSocket, $ip);
				unset($clients[$foundSocket]);
				$resourceId = $foundSocket;
				$deletedSocket = $objWebsocket->getSocketPopulationData('chat', $resourceId);
				$objWebsocket->deleteSocketPopulation('chat', $resourceId);
				$totalSocketLeft = $objWebsocket->getTotalSocketPopulationLeft('chat', $deletedSocket['user_id']);
				
				if($totalSocketLeft <= 0){
					$deletedSocket['user_id'] = rawurlencode(encryption($deletedSocket['user_id'], PUBLIC_SALT, true));
					unset($deletedSocket['created_time']);
					unset($deletedSocket['resource_id']);
					unset($deletedSocket['username']);
					unset($deletedSocket['ip']);
					$deletedSocket['mode'] = 'delete';
					//Notify all users about disconnected connection
					sendSocketMessage(mask(json_encode($deletedSocket)));
				}
			}
		}
	}
	
	//Close the listening socket
	socket_close($socket);

	function sendSocketMessage($msg){
		global $clients;
		foreach($clients as $changedSocket){
			@socket_write($changedSocket,$msg,strlen($msg));
		}
		return true;
	}

	//Unmask incoming framed message
	function unmask($text) {
		$length = ord($text[1]) & 127;
		if($length == 126){
			$masks = substr($text, 4, 4);
			$data = substr($text, 8);
		}elseif($length == 127){
			$masks = substr($text, 10, 4);
			$data = substr($text, 14);
		}else{
			$masks = substr($text, 2, 4);
			$data = substr($text, 6);
		}
		$text = "";
		for($i = 0; $i < strlen($data); ++$i){
			$text .= $data[$i] ^ $masks[$i%4];
		}
		return $text;
	}

	//Encode message for transfer to client.
	function mask($text){
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($text);
		
		if($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$text;
	}

	//Handshake new client.
	function performHandshaking($receivedHeader, $clientConnection, $host, $port){
		$headers = array();
		$lines = preg_split("/\r\n/", $receivedHeader);
		foreach($lines as $line){
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches)){
				$headers[$matches[1]] = $matches[2];
			}
		}

		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		//Hand shaking header
		$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: $host\r\n" .
		"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
		socket_write($clientConnection, $upgrade, strlen($upgrade));
	}

	require DIR_COMMON.'/db_close.php';
?>
