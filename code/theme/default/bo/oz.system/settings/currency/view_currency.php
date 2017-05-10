<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
	<table style="position: relative;">
		<tr>
			<td colspan="2" style="text-align: center;">
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit" value="Edit" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" onclick="javascript: deleteCurrency('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.currency.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Currency Information</td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Currency Code</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $currencyData['code']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Symbol</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $currencyData['symbol']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Text</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $currencyData['text']; ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Currency Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Currency Code</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
										<input type="text" class="flat-input" id="code" name="code" onblur="javascript: checkFieldExist($('#code'), '<?php echo $encryptKey; ?>', 'code', $('#loader_code'), 'check_duplicate_code', true);" value="<?php echo $formCurrencyCode;?>">
										<img id="loader_agentcode" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
									</td>
								</tr>
								<tr>
									<td class="lbl-field">Symbol</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><input type="text" class="flat-input" id="symbol" name="symbol" value="<?php echo $formCurrencySymbol; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Text</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><input type="text" class="flat-input" id="currency_text" name="currency_text" value="<?php echo $formCurrencyText; ?>"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit1" value="Edit" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar1" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" value="Delete" class="flat-button-default" onclick="javascript: deleteCurrency('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.currency.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<?php if($allowEdit){ ?>
<script type="text/javascript">	
	var dynValidator = new Array();
	dynValidator['code'] = true;
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#code'), 'currency code')){return;}
		if(!validateEmpty($('#symbol'), 'symbol')){return;}
		if(!validateEmpty($('#currency_text'), 'text')){return;}
		if(!dynValidator['code']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkFieldExist($('#code'), '<?php echo $encryptKey; ?>', 'code', $('#loader_code'), 'check_duplicate_code', true);
		}else{
			$('#form_1').submit();
		}
	}
</script>
<?php } ?>
<?php if($allowDelete){ ?>
<script type="text/javascript">	
	function deleteCurrency(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected currency?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_currency',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'Currency successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('oz.system.settings.currency.list'); ?>';
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
		});
	}
</script>
<?php } ?>