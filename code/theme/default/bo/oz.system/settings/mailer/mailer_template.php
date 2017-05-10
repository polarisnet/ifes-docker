<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/ckeditor/ckeditor.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<table>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="Save" class="flat-button-default" onclick="javascript: submitForm('');">
				<input type="button" value="Back" class="flat-button-default" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">Mailer Templates<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="form-spacer-solo">
					<tr>
						<td class="lbl-field">Template</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td>
							<select id="template" name="template" class="flat-selectbox" onchange="javascript: changeTemplate(this.value);">
								<?php foreach($listTemplate AS $key => $value){ ?>
								<option value="<?php echo $value['id']; ?>" <?php if($value['id'] == $formTemplate){echo 'selected';}?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="lbl-field">Code</td>
						<td class="lbl-gap"></td>
						<td><div id="code"><?php echo $formCode; ?></div></td>
					</tr>
					<tr>
						<td class="lbl-field">Sender</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="sender" name="sender" value="<?php echo $formSender; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Sender Mail</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="sender_mail" name="sender_mail" value="<?php echo $formSenderMail; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Reply</td>
						<td class="lbl-gap"></td>
						<td><input type="text" class="flat-input" id="reply" name="reply" value="<?php echo $formReply; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Reply Mail</td>
						<td class="lbl-gap"></td>
						<td><input type="text" class="flat-input" id="reply_mail" name="reply_mail" value="<?php echo $formReplyMail; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Subject</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="subject" name="subject" value="<?php echo $formSubject; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Bcc</td>
						<td class="lbl-gap"></td>
						<td><input type="text" class="flat-input" id="bcc" name="bcc" value="<?php echo $formBcc; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Content</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><textarea id="content" name="content"><?php echo nl2eol($formContent); ?></textarea></td>
					</tr>
					<tr>
						<td class="lbl-field">Note</td>
						<td class="lbl-gap"></td>
						<td><div id="note"><?php echo $formNote; ?></div></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<input type="button" value="Save" class="flat-button-default" onclick="javascript: submitForm('');">
				<input type="button" value="Back" class="flat-button-default" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	var editor = CKEDITOR.replace("content",{
		allowedContent: true
	});
		
	function changeTemplate(val){
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: 'change_template',
				template: val
			}
		}).done(function(msg){
			if(msg.success){
				$('#bcc').val(msg.bcc);
				$('#subject').val(msg.subject);
				$('#content').val(msg.content);
				$('#sender').val(msg.sender);
				$('#sender_mail').val(msg.sender_mail);
				$('#reply_mail').val(msg.reply_mail);
				$('#content').val(msg.content);
				$('#code').html(msg.code);
				$('#note').html(msg.note);
				editor.setData(msg.content);
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
		if(!validateEmpty($('#subject'), 'subject')){return;}
		if(!validateEmpty($('#sender'), 'sender')){return;}
		if($('#sender_mail').val() != '[DEFAULT]' && !validateEmail($('#sender_mail'), 'sender email')){return;}
		if($('#reply_mail').val() != '' && $('#reply_mail').val() != '[DEFAULT]' && !validateEmail($('#reply_mail'), 'reply email')){return;}
		$('#form_1').submit();
	}
</script>