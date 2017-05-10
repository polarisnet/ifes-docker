<?php 
	require_once DIR_FRAMEWORK.'/config/websocket.config.php';	
	function getWhoIsOnline(){
		$list = array();
		global $myDB;
		$sql = "SELECT * FROM `websocket_population` WHERE `type`='chat' GROUP BY `user_id`";
		$myDB->query($sql);
		while($myDB->nextRecord()){
			array_push($list, $myDB->getRecord());
		}
		return $list;
	}
?>
<div id="ws-whois-online" class='flat-content-header' style='margin-left: -4px; position: relative;'>WHO'S ONLINE?<img id="ws-broadcast" src="<?php echo HTTP_MEDIA;?>/site-image/megafone-26-black.png" class="ws-broadcast" onclick="javascript: displayWSBroadcast();" onmouseover="javascript: hoverWSBroadcastIcon(this);" onmouseout="javascript: hoverOutWSBroadcastIcon(this);"></div>
<div id="ws-chat-offline" class="ws-chat-offline">Chat server is offline</div>
<div id="ws-chat-list" class="ws-chat-list">
<?php 
	$onlineList = getWhoIsOnline();
	$wsBroadCastTemplate = "";
	foreach($onlineList AS $data){
		if($data['user_id'] != $_SESSION['user_id']){
			$wsBroadCastTemplate .= '<div id="ws-chat-cont-'.$data['uid'].'" style="display: none;">';
				$wsBroadCastTemplate .= '<img src="'.getDefaultPicture(getUserUID($data['user_id'])).'" class="ws-chat-avatar-img">';
				$wsBroadCastTemplate .= '<div class="ws-chat-cont-title">';
					$wsBroadCastTemplate .= $data['display_name'].'<br><img src="'.HTTP_MEDIA.'/site-image/ws_chat_online_sq.png"> Online';
				$wsBroadCastTemplate .= '</div><hr>';
				$wsBroadCastTemplate .= '<div id="ws-chat-log-'.$data['uid'].'" class="ws-chat-log"></div>';
			$wsBroadCastTemplate .= '</div>'; ?>
	<div id="ws-tmp-<?php echo $data['uid']; ?>" class="ws-chat-list-item" <?php if($data['user_id'] != $_SESSION['user_id']){ ?>onclick="javascript: displayWSChat(this, '<?php echo $data['uid']; ?>');" <?php }else{ ?>style="cursor: default;" <?php } ?>><img id="ws-chat-icon-<?php echo $data['uid']; ?>" src="<?php echo HTTP_MEDIA;?>/site-image/ws_chat_online_sq.png"><?php echo $data['display_name']; ?></div>
		<?php 	
		}
	} 
?>
</div>
<style>
	.ws-broadcast{
		height: 22px; 
		width: 22px; 
		position: absolute; 
		right:12px; 
		cursor: pointer;
		display: none;
	}
	.ws-chat-box{
		position: absolute;
		left: 210px;
		width: 280px;
		height: 250px;
		padding: 10px;
		background: #d3d3d3;
		/*-webkit-border-radius: 10px;*/
		/*-moz-border-radius: 10px;*/
		/*border-radius: 10px;*/
		z-index: 1000;
		display: none;
		-webkit-box-shadow: 3px 3px 5px 0px rgba(50, 50, 50, 0.75);
		-moz-box-shadow:    3px 3px 5px 0px rgba(50, 50, 50, 0.75);
		box-shadow:         3px 3px 5px 0px rgba(50, 50, 50, 0.75);
	}

	.ws-chat-box:after{
		content: '';
		position: absolute;
		border-style: solid;
		border-width: 15px 15px 15px 0;
		border-color: transparent #d3d3d3;
		display: block;
		width: 0;
		z-index: 1;
		left: -15px;
		top: 12px;
	}
	
	.ws-chat-text{
		border: 1px solid #BFBFBF;
		font-family: Droid Sans,Arial;
		font-size: 12px;
		height: 25px;
		width: 240px;
		padding: 3px;
		resize: none;
	}
	
	.ws-chat-offline{
		color: #ff0000;
		display: none;
		padding: 5px 0 0 10px;
	}
	.ws-chat-list{
		padding: 5px 0 0 10px;
		display: none;
	}
	.ws-chat-list-item{
		position: relative;
		left: -15px;
		cursor: pointer;
		padding-bottom: 6px;
		color: #004883;
	}
	.ws-chat-list-item:hover{
		color: #0160AB;
		text-decoration: underline;
	}
	.ws-chat-list-item img{
		margin-right: 5px;
		position: relative;
		top: 1px;
	}
	.ws-chat-cont-cls-btn{
		position: absolute; 
		right: 10px; 
		cursor: pointer; 
		z-index: 5;
	}
	.ws-chat-cont-title{
		position: relative; 
		top: -34px;
		left: 40px; 
		margin-bottom: -32px;
	}
	.ws-chat-avatar-img{
		width: 32px; height: 32px; border: 1px solid #4c4c4c;
	}
	.ws-chat-log{
		overflow-y: scroll; 
		height: 160px;
	}
	.ws-chat-log-sender{
		margin-bottom: -14px;
	}
	.ws-chat-log-timestamp{
		text-align: right; 
		padding-right: 2px;
	}
	.ws-chat-control{
		position: absolute; 
		bottom: 0; 
		padding-bottom: 10px;
	}
</style>
<script>
	var oriBroadcastIcon = "<?php echo HTTP_MEDIA;?>/site-image/megafone-26-black.png";
	function hoverWSBroadcastIcon(obj) {
		oriBroadcastIcon = obj.src;
		obj.src = "<?php echo HTTP_MEDIA;?>/site-image/megafone-26-white.png";
	}
	function hoverOutWSBroadcastIcon(obj) {
		obj.src = oriBroadcastIcon;
	}
	function hoverWSSendChatIcon(obj) {
		obj.src = "<?php echo HTTP_MEDIA;?>/site-image/reply-26-white.png";
	}
	function hoverOutWSSendChatIcon(obj) {
		obj.src = "<?php echo HTTP_MEDIA;?>/site-image/reply-26.png";
	}
	function displayWSBroadcast(){
		hideAllWSChatCont();
		$('#ws-chat-cont-broadcast').show();
		$('#ws-chat-text').val('');
		oriBroadcastIcon =  "<?php echo HTTP_MEDIA;?>/site-image/megafone-26-black.png";
		objPosition = $('#ws-whois-online').position();
		$('#ws-chat-box').css('top', objPosition.top-15);
		$('#ws-chat-box').show();
		$('#ws-chat-uid').val('broadcast');
	}
	function displayWSChat(obj, uid){
		hideAllWSChatCont();
		$('#ws-chat-cont-'+uid).show();
		$('#ws-chat-text').val('');
		$('#ws-chat-icon-'+uid).attr("src", "<?php echo HTTP_MEDIA;?>/site-image/ws_chat_online_sq.png");
		objPosition = $(obj).position();
		$('#ws-chat-box').css('top', objPosition.top-15);
		$('#ws-chat-box').show();
		$('#ws-chat-uid').val(uid);
	}
	
	function hideAllWSChatCont(){
		var obj = $( "[id^='ws-chat-cont-']");
		obj.each(function(index, el){
			$(el).hide();
		});
	}
	
	function sendWSChat(){
		var uid = $('#ws-chat-uid').val();
		var msg = $('#ws-chat-text').val();
		if(uid == ''){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Chat id is missing. Please refresh your browser.',
				'position': 'right',
				'autoclose': true
			}]);	
		}else{
			$('#ws-chat-text').val('');
			var message = {
				id: wsPublicId,
				message: msg,
				uid: uid,
				mode: 'message'
			}
			wsChat.send(JSON.stringify(message));
		}
	}
	
	function displayWSChatMessage(targetUID, template){
		$('#ws-chat-log-'+targetUID).append(template);
		$('#ws-chat-log-'+targetUID).animate({ scrollTop: $('#ws-chat-log-'+targetUID)[0].scrollHeight}, 1000);
		if($('#ws-chat-box').is(':hidden') && maximizeLeftBar == true){
			if(targetUID == 'broadcast'){
				displayWSBroadcast();
			}else{
				displayWSChat(document.getElementById('ws-tmp-'+targetUID), targetUID);
			}
		}else{
			if($('#ws-chat-log-'+targetUID).is(':hidden')){
				if(targetUID == 'broadcast'){
					$('#ws-broadcast').attr("src", "<?php echo HTTP_MEDIA;?>/site-image/broadcast_in.gif");
				}else{
					$('#ws-chat-icon-'+targetUID).attr("src", "<?php echo HTTP_MEDIA;?>/site-image/ws_chat_online_in.gif");
				}
			}
		}
	}
	
	var wsPublicId = '<?php echo rawurlencode(encryption($_SESSION['user_id'], PUBLIC_SALT, true)); ?>';
	var wsChat = {};	
	$(document).ready(function(){
		var wsChatURI = "<?php echo 'ws://'.WS_SERVER.':'.WS_SERVER_CHAT_PORT.HTTP_ROOT.'/core/websocket/server_chat.php';?>";
		try{
			wsChat = new WebSocket(wsChatURI);
				
			wsChat.onopen = function(e){
				var message = {id: wsPublicId, mode: 'register'};
				wsChat.send(JSON.stringify(message));
				$('#ws-chat-list').show();
				$('#ws-broadcast').show();
				hideAllWSChatCont();
				$('<audio id="ws-chat-audio"><source src="<?php echo HTTP_MEDIA;?>/sound-file/ffxiv-tell-sample-only.mp3" type="audio/mpeg"></audio>').appendTo('body');
			}
			
			wsChat.onmessage = function(e){
				var message = JSON.parse(e.data);
				switch(message.mode){
					case 'insert':
						if($('#ws-tmp-'+message.uid).length == 0 && wsPublicId != message.user_id){
							var template = '';
							template = '<div id="ws-tmp-'+message.uid+'" class="ws-chat-list-item" onclick="javascript: displayWSChat(this, '+"'"+message.uid+"'"+');"><img id="ws-chat-icon-'+message.uid+'" src="<?php echo HTTP_MEDIA;?>/site-image/ws_chat_online_sq.png">'+message.display_name+'</div>';
							$('#ws-chat-list').append(template);
							
							template = '<div id="ws-chat-cont-'+message.uid+'" style="display: none;">';
								template += '<img src="'+message.avatar+'" class="ws-chat-avatar-img" onclick="javascript:'+"$('#ws-chat-box').hide();"+'">';
								template += '<div class="ws-chat-cont-title">'+message.display_name+'<br><img src="<?php echo HTTP_MEDIA;?>/site-image/ws_chat_online_sq.png"> Online</div><hr>';
								template += '<div id="ws-chat-log-'+message.uid+'" class="ws-chat-log">';
								template += '</div>';
							template += '</div>';
							$('#ws-chat-group').append(template);
						}
					break;
					case 'message':
						var template = "";
						if(wsPublicId == message.sender_id){
							template += '<div class="ws-chat-log-sender">You:</div><div class="ws-chat-log-timestamp">'+message.date+'</div>';
							template += message.message+'<br><br><hr>';
							displayWSChatMessage(message.target_uid, template);
						}else if(message.target_id == 'broadcast' || wsPublicId == message.target_id){
							template += '<div class="ws-chat-log-sender">'+message.sender_name+':</div><div class="ws-chat-log-timestamp">'+message.date+'</div>';
							template += message.message+'<br><br><hr>';
							if(message.target_uid == 'broadcast'){
								displayWSChatMessage(message.target_uid, template);
							}else{
								displayWSChatMessage(message.sender_uid, template);
							}
							$('#ws-chat-audio')[0].play();
						}
					break;
					case 'delete':
						if($('#ws-tmp-'+message.uid).length != 0){
							$('#ws-tmp-'+message.uid).remove();
							if($('#ws-chat-cont-'+message.uid).is(':visible')){
								$('#ws-chat-box').hide();
							}
							$('#ws-chat-cont-'+message.uid).remove();
						}
					break;
				}
			}
			
			wsChat.onerror = function(e){}
			
			wsChat.onclose = function(e){
				$('#ws-chat-offline').show();
				$('#ws-broadcast').hide();
				$('#ws-chat-box').hide();
				$('#ws-chat-list').hide();
				$('#ws-chat-list').empty();
			}
		}catch(ex){
			//console.log(ex);
		}
		
		$('#ws-chat-text').keyup(function(event){
			if (event.keyCode == 10 || event.keyCode == 13){
				sendWSChat();
			}
		});
		$('#ws-chat-text').keypress(function(event){
			if (event.keyCode == 10 || event.keyCode == 13){
				event.preventDefault();
			}
		});
		
	});
	window.onbeforeunload = function(){
		wsChat.close();
	}
</script>