<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/complexify/jquery.complexify.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<table>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
				<input type="button" value="Save & New" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVENEW; ?>" data-placement="top" onclick="javascript: submitForm('new');">
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">User Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td class="form-spacer1">
				<table>
					<tr>
						<td class="lbl-field">Username</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td>
							<input type="text" class="flat-input" id="username" name="username" onblur="javascript: checkUsernameExist($('#username'), 'username', 'username');" value="<?php echo $formUsername;?>">
							<img id="loader_username" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
						</td>
					</tr>
					<tr>
						<td class="lbl-field">Password</td>
						<td><div class="lbl-compulsory">*</div></td>
						<td><input type="password" class="flat-input" id="password" name="password" onkeydown="javascript:calculateComplexity();"></td>
					</tr>
					<tr>
						<td class="lbl-field">Retype Password</td>
						<td><div class="lbl-compulsory">*</div></td>
						<td><input type="password" class="flat-input" id="retype_password" name="retype_password" onblur="javascript: validateMatch($('#password'), $('#retype_password'), 'password', 'retype password');"></td>
					</tr>
					<tr>
						<td class="lbl-field">Password Strength</td>
						<td></td>
						<td>
							<div class="password-calc"><div id="password-inner" class="password-inner"><div id="password-bar" class="password-bar"></div></div></div>
						</td>
					</tr>
				</table>
			</td>
			<td class="form-spacer1">
				<table>
					<tr>
						<td class="lbl-field">Email Address</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td>
							<input type="text" class="flat-input" id="email" name="email" value="<?php echo $formEmail; ?>" onblur="javascript: checkEmailExist($('#email'), 'email address', '', 'email'); ">
							<img id="loader_email" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
						</td>
					</tr>
					<tr>
						<td class="lbl-field">First Name</td>
						<td><div class="lbl-compulsory">*</div></td>
						<td><input type="text" class="flat-input" id="first_name" name="first_name" value="<?php echo $formFName; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Last Name</td>
						<td><div class="lbl-compulsory">*</div></td>
						<td><input type="text" class="flat-input" id="last_name" name="last_name" value="<?php echo $formLName; ?>"></td>
					</tr>
					<tr>
						<td class="lbl-field">Status</td>
						<td><div class="lbl-compulsory">*</div></td>
						<td>
							<select id="status" name="status" class="flat-selectbox">
								<option value="1" <?php if($formStatus == '1'){echo 'selected';}?>>Active</option>
								<option value="0" <?php if($formStatus == '0'){echo 'selected';}?>>Blocked</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
				<input type="button" value="Save & New" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVENEW; ?>" data-placement="top" onclick="javascript: submitForm('new');">
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">	
	var dynValidator = new Array();
	dynValidator['username'] = false;
	dynValidator['email'] = false;

	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#username'), 'username')){return;}
		if(!validateEmpty($('#password'), 'password')){return;}
		if(!validateEmpty($('#retype_password'), 'retype password')){return;}
		if(!validateMatch($('#password'), $('#retype_password'), 'password', 'retype password')){return;}
		if(!validateEmpty($('#email'), 'email')){return;}
		if(!validateEmail($('#email'), 'email')){return;}
		if(!validateEmpty($('#first_name'), 'first name')){return;}
		if(!validateEmpty($('#last_name'), 'last name')){return;}
		if(!dynValidator['username'] || !dynValidator['email']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkEmailExist($('#email'), 'email address', '', 'email');
			checkUsernameExist($('#username'), 'username', 'username');
		}else{
			$('#form_1').submit();
		}
	}
</script>