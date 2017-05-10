<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<table>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="Save" class="flat-button-default" onclick="javascript: submitForm('');">
				<input type="button" value="Test" class="flat-button-default" onclick="javascript: testMailer();">
				<input type="button" value="Back" class="flat-button-default" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">Mailer Settings<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="form-spacer-solo">
					<tr>
						<td class="lbl-field">SMTP Host</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="host" name="host" value="<?php echo $formHost; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">SMTP Port</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="port" name="port" value="<?php echo $formPort; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">SMTP Authentication</td>
						<td class="lbl-gap"></td>
						<td><input type="checkbox" id="auth" name="auth" value="1" <?php if($formAuth == 1){echo 'checked';}?>></td>
					</tr>
					<tr>
						<td class="lbl-field">SMTP User</td>
						<td class="lbl-gap"></td>
						<td><input type="text" class="flat-input" id="user" name="user" value="<?php echo $formUser; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">SMTP Password</td>
						<td class="lbl-gap"></td>
						<td><input type="password" class="flat-input" id="password" name="password" value="<?php echo $formPassword; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Default Sender</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="sender" name="sender" value="<?php echo $formSender; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Default Sender Mail</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="sender_mail" name="sender_mail" value="<?php echo $formSenderMail; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Default Reply</td>
						<td class="lbl-gap"></td>
						<td><input type="text" class="flat-input" id="reply" name="reply" value="<?php echo $formReply; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Default Reply Mail</td>
						<td class="lbl-gap"></td>
						<td><input type="text" class="flat-input" id="reply_mail" name="reply_mail" value="<?php echo $formReplyMail; ?>"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<input type="button" value="Save" class="flat-button-default" onclick="javascript: submitForm('');">
				<input type="button" value="Test" class="flat-button-default" onclick="javascript: testMailer();">
				<input type="button" value="Back" class="flat-button-default" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	function testMailer(){
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: 'test_smtp'
			}
		}).done(function(msg){
			if(msg.success){
				$('#oz-noty').oznoty([{
					'type': 'message',
					'title': 'Message',
					'content': 'Message successfully send to your email address.',
					'position': 'right',
					'autoclose': true
				}]);
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': msg.message,
					'position': 'right',
					'autoclose': true
				}]);
			}
		}).fail(function(jqXHR, textStatus){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Could not connect with server. Please refresh browser and try again.',
				'position': 'right',
				'autoclose': true
			}]);
		});
	}
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#host'), 'SMTP host')){return;}
		if(!validateEmpty($('#port'), 'SMTP port')){return;}
		if($("#auth").is(':checked')){
			if(!validateEmpty($('#user'), 'SMTP user')){return;}
			if(!validateEmpty($('#password'), 'SMTP password')){return;}
		}
		if(!validateEmpty($('#sender'), 'default sender')){return;}
		if(!validateEmail($('#sender_mail'), 'default sender email')){return;}
		if($('#reply_mail').val() != '' && !validateEmail($('#reply_mail'), 'default reply email')){return;}
		$('#form_1').submit();
	}
</script>