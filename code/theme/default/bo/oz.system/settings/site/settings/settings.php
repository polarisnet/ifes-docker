<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<table style="position: relative;">
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">Global Settings<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td class="aa-bs form-spacer1" colspan="2">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<table>
						<tr>
							<td class="lbl-field">Max Login Attempt</td>
							<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
							<td><input type="text" class="flat-input" id="login_attempt" name="login_attempt" value="<?php echo $formLoginAttempt; ?>"></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<table>
						<tr>
							<td class="lbl-field">Max Login Lockdown Duration (minute)</td>
							<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
							<td><input type="text" class="flat-input" id="login_lock" name="login_lock" value="<?php echo $formLoginLock; ?>"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		$('#form_1').submit();
	}
	
	$(document).keydown(function(e){
		if(e.ctrlKey && e.keyCode == 83){
			if(e.shiftKey){
				submitForm('new');
			}else{
				submitForm('');
			}
			e.preventDefault(e);
			return;
		}
		if(e.ctrlKey && e.keyCode == 8){		
			e.preventDefault(e);
			return;
		}
	});
</script>