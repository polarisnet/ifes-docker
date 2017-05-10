<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/ckeditor/ckeditor.js"></script>
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
				<?php if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" onclick="javascript: deleteAlert('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.alerts.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Alert Information</td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Header</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $alertData['header']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Form</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $objAlert->getTableLabel($alertData['table']); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Target</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $objAlert->getTargetLabel($alertData['table'], $alertData['target']); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Type</td>
									<td class="lbl-gap">:</td>
									<td><?php echo ucfirst($alertData['type']); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Start Date</td>
									<td class="lbl-gap">:</td>
									<td><?php if($alertData['start_date'] != '0000-00-00 00:00:00' && $alertData['start_date'] != ''){echo convertDate($alertData['start_date'], 'Y-m-d H:i:s', 'd/m/Y');} ?></td>
								</tr>
								<tr>
									<td class="lbl-field">End Date</td>
									<td class="lbl-gap">:</td>
									<td><?php if($alertData['end_date'] != '0000-00-00 00:00:00' && $alertData['end_date'] != ''){echo convertDate($alertData['end_date'], 'Y-m-d H:i:s', 'd/m/Y');} ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Content</td>
									<td class="lbl-gap">:</td>
									<td><div style="margin-top: -12px;"><?php echo $alertData['content']; ?></div></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Alert Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Header</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><input type="text" class="flat-input" id="header" name="header" value="<?php echo $formHeader; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Form</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><div id="input_table"></div></td>
								</tr>
								<tr>
									<td class="lbl-field">Target</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
										<input type="radio" name="target_mode" value="0" <?php if($formTableId[0] == 'all'){echo 'checked';} ?> onclick="javascript: toggleTarget(false);">All<br>
										<input type="radio" name="target_mode" value="1" <?php if($formTableId[0] != 'all'){echo 'checked';} ?> onclick="javascript: toggleTarget(true);">Specific Record<br><div id="input_related_target" style="position: relative; left: 19px; width: 90%;"></div>
										<input type="hidden" id="hide_related_target" name="hide_related_target">
									</td>
								</tr>
								<tr>
									<td class="lbl-field">Type</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
										<select id="type" name="type" class="flat-selectbox">
											<option value="pin" <?php if($formType == "pin"){echo ("selected");}?> >Pin</option>
											<option value="reminder" <?php if($formType == "reminder"){echo ("selected");}?> >Reminder</option>
											<option value="question" <?php if($formType == "question"){echo ("selected");}?> >Question</option>
											<option value="warning" <?php if($formType == "warning"){echo ("selected");}?> >Warning</option>                                
										</select>
									</td>
								</tr>    
								<tr>
									<td class="lbl-field">Start Date</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><div id="input_start_date"></div></td>
								</tr>
								<tr>
									<td class="lbl-field">End Date</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><div id="input_end_date"></div></td>
								</tr>
								<tr>
									<td class="lbl-field">Content</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><textarea id="content" name="content"><?php echo nl2eol($formContent); ?></textarea></td>
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
				<?php if($allowDelete){ ?><input type="button" value="Delete" class="flat-button-default" onclick="javascript: deleteAlert('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.alerts.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<?php if($allowEdit){ ?>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/resources/css/BoxSelect.css">
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/src/boxselect/BoxSelect.js"></script>
<script type="text/javascript">	
	function toggleTarget(mode){
		var tbl = Ext.getCmp('table').getValue();
		if(mode){
			Ext.getCmp('ext-related-target-container').enable();
		}else{
			Ext.getCmp('ext-related-target-container').disable();
		}
	}
	
	var editor = CKEDITOR.replace("content",{
		allowedContent: true
	});
	<?php 
		$tempTableId = $formTableId;
		foreach($tempTableId AS $tKey => $val){
			$tempTableId[$tKey] = "'".$val."'";
		}
	?>
	var relatedTarget = [<?php echo implode(",", $tempTableId); ?>];
	
	Ext.require(['Ext.ux.form.field.BoxSelect']);
	Ext.USE_NATIVE_JSON = false;
	Ext.onReady(function(){
		var tableStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_table'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			autoLoad: true,
			fields:['table', 'label']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_table',
			id: 'table',
			name: 'table',
			hiddenName: 'ext-table',
			store: tableStore,
			root: 'combo',
			queryMode: 'local',
			value: '<?php echo $formTable; ?>',
			valueField: 'table',
			displayField: 'label',
			emptyText: 'Please select form',
			listeners:{
				'select': function(obj, record, opts){
					Ext.getStore('target_store').getProxy().extraParams.table = record[0].get('table');
					Ext.getStore('target_store').load();
				},
				'change': function(obj, newValue, oldValue, opts){
					Ext.getCmp('related_target').setValue('');
				}
			}
		});
		
		var relatedTargetStore = new Ext.data.JsonStore({
			id: 'target_store',
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_related_target',
					table: '<?php echo $formTable; ?>',
					intact: "<?php echo implode(",", $tempTableId); ?>",
					intact_done: 0
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			autoLoad: true,
			fields:['id', 'label'],
			listeners:{
				'load': function(obj, records, successful, opts){
					if(successful){
						if(obj.getProxy().extraParams.intact_done == 0){
							obj.getProxy().extraParams.intact_done = 1;
							obj.getProxy().extraParams.intact = '';
						}
					}
				}
			}
		});
		
		Ext.create('Ext.container.Container',{
			id: 'ext-related-target-container',
			renderTo: 'input_related_target',
			disabled: true,
			items:[{
				id: 'related_target',
				name: 'related_target',
				hiddenName: 'ext-related_target',
				xtype: 'boxselect',
				store: relatedTargetStore,
				emptyText: 'Please select related target',
				root: 'combo',
				queryMode: 'remote',
				valueField: 'id',
				displayField: 'label',
				delimiter: ',',
				multiSelect: true,
				forceSelection: false,
				pageSize: 15,
				listConfig: {
					loadingText: 'Searching...',
					//minWidth: 305,
					emptyText: '<div class="ext-empty-live-search">No match found...</div>',
					getInnerTpl: function(){
						return '{label}<hr>';
					}
				}
			}]
		});
	
		Ext.create('Ext.form.field.Date', {
			renderTo: 'input_start_date',
			id: 'start_date',
			name: 'start_date',
			width: 208,
			format: 'd/m/Y',
			value: '<?php echo $formStartDate; ?>'
		});
		
		Ext.create('Ext.form.field.Date', {
			renderTo: 'input_end_date',
			id: 'end_date',
			name: 'end_date',
			width: 208,
			format: 'd/m/Y',
			value: '<?php echo $formEndDate; ?>'
		});
		
		<?php if($formTableId[0] != 'all'){ ?>
			toggleTarget(true);
			Ext.getCmp('related_target').setValue(relatedTarget);
		<?php } ?>
	});
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#header'), 'header')){return;}
		document.getElementById('hide_related_target').value = Ext.getCmp('related_target').getValue().join(',');
		$('#form_1').submit();
	}
</script>
<?php } ?>
<?php if($allowDelete){ ?>
<script type="text/javascript">	
	function deleteAlert(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected alerts?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_alert',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'Alert successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('oz.system.settings.alerts.list'); ?>';
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
<style>
	.x-mask{background: none repeat scroll 0 0 #FFFFFF !important;}
</style>