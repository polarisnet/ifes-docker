<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
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
			<td class="lbl-title" colspan="2">Country Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="form-spacer-solo">
					<tr>
						<td class="lbl-field">Country Name</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td>
							<input type="text" class="flat-input" id="name" name="name" onblur="javascript: checkFieldExist($('#name'), '', 'name', $('#loader_name'), 'check_duplicate_name', true);" value="<?php echo $formCountryName;?>">
							<img id="loader_name" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
						</td>
					</tr>
					<tr>
						<td class="lbl-field">ISO Code</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><input type="text" class="flat-input" id="iso" name="iso" value="<?php echo $formCountryISO; ?>"></td>
					</tr>
				</table>
			</td>
		</tr>
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
	dynValidator['name'] = false;

	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#name'), 'country name')){return;}
		if(!validateEmpty($('#iso'), 'iso code')){return;}
		if(!dynValidator['name']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkFieldExist($('#name'), '', 'name', $('#loader_name'), 'check_duplicate_name', true);
		}else{
			$('#form_1').submit();
		}
	}
</script>