<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/complexify/jquery.complexify.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<table>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="Save" class="flat-button-default" onclick="javascript: submitForm('');">				
				<input type="button" value="Back" class="flat-button-default" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.user_management.users.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">Reset Password<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td class="form-spacer1">
				<table>					
					<tr>
                        <td class="lbl-field">Username</td>
                        <td class="lbl-gap"></td>
                        <td><?php echo $userData['username']; ?></td>
                    </tr>
                    <tr>
						<td class="lbl-field">New Password</td>
						<td><div class="lbl-compulsory">*</div></td>
						<td><input type="password" class="flat-input" id="password" name="password" onkeydown="javascript:calculateComplexity();"></td>
					</tr>
					<tr>
						<td class="lbl-field">Retype New Password</td>
						<td><div class="lbl-compulsory">*</div></td>
						<td><input type="password" class="flat-input" id="retype_password" name="retype_password" ></td>
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
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>		
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<input type="button" value="Save" class="flat-button-default" onclick="javascript: submitForm('');">				
				<input type="button" value="Back" class="flat-button-default" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.user_management.users.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">		
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		
		if(!validateEmpty($('#password'), 'password')){return;}
		if(!validateEmpty($('#retype_password'), 'retype password')){return;}
		if(!validateMatch($('#password'), $('#retype_password'), 'new password', 'retype new password')){return;}
		else{
			$('#form_1').submit();
		}
	}
</script>