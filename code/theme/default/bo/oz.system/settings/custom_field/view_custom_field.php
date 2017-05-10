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
				<?php if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" onclick="javascript: deleteContact('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.custom_field.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Custom Field Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td class="form-spacer1">
							<table>
								<tr>
									<td class="lbl-field">Form</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $objCustomField->getFormNameById($customfieldData['sys_module_id'],$customfieldData['module_uid']);  ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Form Section</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $objCustomField->getSectionNameById($customfieldData['cf_section_id']);  ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Field Position</td>
									<td class="lbl-gap">:</td>									
									<td><?php if($customfieldData['cf_position'] == "left") {echo "Left Side";}
											  else if($customfieldData['cf_position'] == "right"){echo "Right Side";}
										?></td>
								</tr>
                                <tr>
									<td class="lbl-field">Field Type</td>
									<td class="lbl-gap">:</td>
									<td><?php if($customfieldData['cf_type'] == "textfield") {echo "Text Input";}
											  else if($customfieldData['cf_type'] == "textarea"){echo "Text Area";}
											  else if($customfieldData['cf_type'] == "numeric"){echo "Numeric Input";}
											  else if($customfieldData['cf_type'] == "date"){echo "Date";}
											  else if($customfieldData['cf_type'] == "dropdown"){echo "Drop Down";}
											  else if($customfieldData['cf_type'] == "checkbox"){echo "Check Box";}
											  else if($customfieldData['cf_type'] == "radio"){echo "Radio Button";}
										?></td>
								</tr>
                                <tr>
									<td class="lbl-field">Status</td>
									<td class="lbl-gap">:</td>
									<td><?php if($customfieldData['cf_status'] == 1) {echo "Enabled";} else { echo "Disabled";} ?></td>
								</tr>
							</table>
						</td>
						<td class="form-spacer1">
							<table>
								<tr>
									<td class="lbl-field">Field Label</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customfieldData['cf_label']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Field Tooltip</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customfieldData['cf_tooltip']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Field Order</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customfieldData['cf_order']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Mandatory Field</td>
									<td class="lbl-gap">:</td>
									<td><?php if($customfieldData['cf_mandatory'] == 1) {echo "Yes";} else { echo "No";} ?></td>
								</tr>
							</table>
						</td>
					</tr>					
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Custom Field Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
                        <td class="form-spacer1">
                            <table>
                                <tr>
                                    <td class="lbl-field">Form</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
                                    <td><div id="input_moduleform"></div><input type="hidden" class="flat-input" id="form_uid" name="form_uid" value="<?php echo $formUID; ?>"></td>
                                </tr>
                                <tr>
                                    <td class="lbl-field">Form Section</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>						
                                    <td><div id="input_section"></div></td>
                                </tr>
                                <tr>
                                    <td class="lbl-field">Field Position</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>						
                                    <td><div id="input_position"></td>
                                </tr>					
                                <tr>
                                    <td class="lbl-field">Field Type</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
                                    <td>
                                        <select id="combo-fieldtype" name="combo-fieldtype" onchange="javascript: showHideCustomFieldSelectableValues(this.value); " class="flat-selectbox">								
                                            <option value="">Please select field type</option>
                                            <option value="textfield" <?php if($formFieldType == "textfield"){echo ("selected");}?> >Text Input</option>
                                            <option value="textarea" <?php if($formFieldType == "textarea"){echo ("selected");}?> >Text Area</option>
                                            <option value="numeric" <?php if($formFieldType == "numeric"){echo ("selected");}?> >Numeric Input</option>
                                            <option value="date" <?php if($formFieldType == "date"){echo ("selected");}?> >Date</option>
                                            <option value="dropdown" <?php if($formFieldType == "dropdown"){echo ("selected");}?> >Drop Down</option>
                                            <option value="checkbox" <?php if($formFieldType == "checkbox"){echo ("selected");}?> >Check Box</option>
                                            <option value="radio" <?php if($formFieldType == "radio"){echo ("selected");}?> >Radio Button</option>
                                        </select>  
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lbl-field">Status</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>						
                                    <td>
                                        <select id="combo-status" name="combo-status" class="flat-selectbox">
                                            <option value="1" <?php if($formStatus == "1"){echo ("selected");}?> >Enabled</option>
                                            <option value="0" <?php if($formStatus == "0"){echo ("selected");}?> >Disabled</option>                                
                                        </select>  
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="form-spacer1">
                            <table>
                                <tr>
                                    <td class="lbl-field">Field Label</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
                                    <td>
                                        <input type="text" class="flat-input" id="fieldlabel" name="fieldlabel" onblur="javascript: checkFieldExistWithCombo($('#fieldlabel'), 'ext-moduleform', '<?php echo $encryptKey; ?>', 'fieldlabel', $('#loader_fieldlabel'), 'check_duplicate_fieldlabel', true);" value="<?php echo $formFieldLabel;?>">
                                        <img id="loader_fieldlabel" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lbl-field">Field Tooltip</td>
                                    <td class="lbl-gap"></td>
                                    <td><input type="text" class="flat-input" id="tooltip" name="tooltip" value="<?php echo $formTooltip; ?>"></td>
                                </tr>                    
                                <tr>
                                    <td class="lbl-field">Field Order</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
                                    <td><div id="input_field_order"></div></td>
                                </tr>
                                <tr>
                                    <td class="lbl-field">Mandatory Field</td>
                                    <td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>						
                                    <td>
                                        <select id="combo-mandatory" name="combo-mandatory" class="flat-selectbox">
                                            <option value="0" <?php if($formMandatory == "0"){echo ("selected");}?> >No</option>
                                            <option value="1" <?php if($formMandatory == "1"){echo ("selected");}?> >Yes</option>                                                                            
                                        </select>  
                                    </td>
                                </tr>                                
                            </table>
                        </td>
                    </tr>					
				</table>
				<?php } ?>
			</td>
		</tr>
		<tr id="trInternalStuffId1"  ><td colspan="2">&nbsp;</td></tr>
		<tr id="trInternalStuffId2" >
			<td class="lbl-title" colspan="2">Custom Field Selectable Values</td>
		</tr>
		<tr id="trInternalStuffId3" >
			<td colspan="2">
				<br><div id="ext-details-grid"></div>				
				<script type="text/javascript">
					Ext.onReady(function(){
					<?php if($formFieldType!="") { ?>
						showHideCustomFieldSelectableValues('<?php echo $formFieldType; ?>');
					<?php } else { ?>
						showHideCustomFieldSelectableValues('');
					<?php } ?>
						});
				</script>
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
				<?php if($allowDelete){ ?><input type="button" value="Delete" class="flat-button-default" onclick="javascript: deleteContact('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.custom_field.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/GridFilters.css" />
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/RangeMenu.css" />                
<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/oz.system/settings/custom_field/option_content.js"></script>
<script type="text/javascript">
	function showHideCustomFieldSelectableValues(value) {
		if(value=="dropdown" || value =="checkbox" || value =="radio") {			
			document.getElementById("trInternalStuffId1").style.display = '';
			document.getElementById("trInternalStuffId2").style.display = '';
			document.getElementById("trInternalStuffId3").style.display = '';
			var chk = Ext.getCmp('ext-container');
			if (!chk){
				Ext.Loader.setPath('Ext.ux', '<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux');
				Ext.require([
					'Ext.ux.ProgressBarPager',
					'Ext.ux.grid.FiltersFeature'
				]);
				Ext.namespace('OptionContent');
				OptionContent.app = function(){
					return{
						init: function(){
							var mask = new Ext.LoadMask(document.getElementById('ext-details-grid'),{ msg: 'Loading...'});
							var detailsListPanel = new DetailsListPanel({
								'start': '<?php echo $detailsStart; ?>',
								'itemsPerPage': '<?php echo $detailsItemsPerPage; ?>',
								'listFields': [<?php echo implode(",", $optionFields); ?>],
								'allowEdit': '<?php echo $allowEdit; ?>',
								'parent': '<?php echo $encryptKey; ?>'
							});
							Ext.create('Ext.container.Container',{
								id: 'ext-container',
								renderTo: 'ext-details-grid',
								items:[detailsListPanel]
							});
						}
					}
				}();
				Ext.onReady(OptionContent.app.init, OptionContent.app);
			}					
		} else {			
			document.getElementById("trInternalStuffId1").style.display = 'none';
			document.getElementById("trInternalStuffId2").style.display = 'none';
			document.getElementById("trInternalStuffId3").style.display = 'none';
		}
	}
</script>
<?php if($allowEdit){ ?>
<script type="text/javascript">
		
	Ext.onReady(function(){
		var moduleformStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_moduleform'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			//autoLoad: true,
			fields:['id', 'uid', 'module_display']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_moduleform',
			id: 'moduleform',
			name: 'moduleform',
			hiddenName: 'ext-moduleform',
			store: moduleformStore,
			root: 'combo',
			queryMode: 'remote',
			value: '<?php echo $objCustomField->getFormNameById($formModuleForm,$formUID);?>',
			valueField: 'id',			
			displayField: 'module_display',
			pageSize: 15,
			emptyText: 'Please select form',
			listConfig:{
				loadingText: 'Searching...',
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Form: {module_display}<br><hr>';
				}
			},
			listeners:{
				'select': function(obj, record){
					document.getElementById("form_uid").value = record[0].get('uid');
					sectionStore.getProxy().extraParams = {
						opt: 'combo_section',
							conditions: record[0].get('id')	
					};
					sectionStore.load();					
				},
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-moduleform")[0].value = '';
						sectionStore.getProxy().extraParams = {
						opt: 'combo_section',
						conditions: ''	
					};
					sectionStore.load();
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-moduleform")[0].value = '<?php echo $formModuleForm; ?>';
				}
			}
		});
				
		var sectionStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_section',
					conditions: '<?php echo $formModuleForm; ?>'					
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			//autoLoad: true,
			fields:['id', 'section_name', 'module_id', 'section_column']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_section',
			id: 'section',
			name: 'section',			
			hiddenName: 'ext-section',
			store: sectionStore,
			root: 'combo',
			queryMode: 'remote',
			value: '<?php echo $objCustomField->getSectionNameById($formSection); ?>',	
			valueField: 'id',
			displayField: 'section_name',			
			pageSize: 15,
			emptyText: 'Please select form first',
			listConfig:{
				loadingText: 'Searching...',
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Section: {section_name}<br><hr>';
				}
			},
			listeners:{
				'select': function(obj, record){
					var column = record[0].get('section_column');
					if(column == '1'){
						Ext.getCmp('position').setValue('left');
						Ext.getCmp('position').setReadOnly(true);
					} else {					
						Ext.getCmp('position').setReadOnly(false);
					}
				},
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-section")[0].value = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-section")[0].value = '<?php echo $formSection; ?>';					
				}
			}
		});
		
		var fullpositionStore = Ext.create('Ext.data.ArrayStore', {
			fields: [ 'value', 'label' ],
			data: [
				['left','Left Side'],
				['right','Right Side']
			]
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_position',
			id: 'position',
			name: 'position',			
			hiddenName: 'ext-position',
			store: fullpositionStore,
			root: 'combo',
			queryMode: 'local',
			value: '<?php echo "$formPosition"; ?>',
			valueField: 'value',
			displayField: 'label',
			emptyText: 'Please select field position',
			editable: true,
			matchFieldWidth: true,
			listeners:{				
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-position")[0].value = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-position")[0].value = '<?php echo $formPosition; ?>';
					var tempcolumn = '<?php echo $formColumn; ?>';
					if(tempcolumn == '1'){						
						Ext.getCmp('position').setReadOnly(true);
					} else {						
						Ext.getCmp('position').setReadOnly(false);
					}
				}
			}
		});
		
		Ext.create('Ext.form.field.Number', {
			renderTo: 'input_field_order',
			id: 'fieldorder',
			name: 'fieldorder',
			minValue: 0,
			allowBlank: false,
			allowExponential: false,
			width: 208,
			allowDecimals: false,
			hideTrigger: true,
			value: '<?php echo $formOrder; ?>'
		});
		
	});
	
	var dynValidator = new Array();
	dynValidator['fieldlabel'] = true;

	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateExtEmpty('moduleform', 'form')){return;}
		if(!validateExtEmpty('section', 'form section')){return;}
		if(!validateEmpty($('#fieldlabel'), 'field label')){return;}
		if(!validateEmpty($('#combo-fieldtype'), 'field type')){return;}
		if(!validateEmpty($('#combo-mandatory'), 'mandatory field')){return;}
		if(!validateExtEmpty('position', 'field position')){return;}
		if($('#fieldorder').val() != '' && !validateDecimal($('#fieldorder'), 'field order')){return;}		
		if(!dynValidator['fieldlabel']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkFieldExistWithCombo($('#fieldlabel'), 'ext-moduleform', '<?php echo $encryptKey; ?>', 'fieldlabel', $('#loader_fieldlabel'), 'check_duplicate_fieldlabel', true);
		}else{
			$('#form_1').submit();
		}
	}
</script>
<?php } ?>
<?php if($allowDelete){ ?>
<script type="text/javascript">	
	function deleteContact(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected custom field?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_custom_field',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'Custom field successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('oz.system.settings.custom_field.list'); ?>';
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