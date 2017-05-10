<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/placeholder-master/jquery.placeholder.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/skins/square/custom.css" />
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/jquery.icheck.min.js"></script>
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
                        	<select id="combo-fieldtype" name="combo-fieldtype" class="flat-selectbox">								
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
                            	<option value="1" <?php if($formStatus == "yes"){echo ("selected");}?> >Enabled</option>
                                <option value="0" <?php if($formStatus == "no"){echo ("selected");}?> >Disabled</option>                                
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
							<input type="text" class="flat-input" id="fieldlabel" name="fieldlabel" onblur="javascript: checkFieldExistWithCombo($('#fieldlabel'), 'ext-moduleform', '', 'fieldlabel', $('#loader_fieldlabel'), 'check_duplicate_fieldlabel', true);" value="<?php echo $formFieldLabel;?>">
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
                            	<option value="0" <?php if($formMandatory == "no"){echo ("selected");}?> >No</option> 
                                <option value="1" <?php if($formMandatory == "yes"){echo ("selected");}?> >Yes</option>                                                               
							</select>  
                      	</td>
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
<?php
	$winReady .= "	$('input, textarea').placeholder();";
?>
<script type="text/javascript">		
	var dynValidator = new Array();
	dynValidator['fieldtitle'] = false;
	
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
					conditions: '<?php if($formModuleForm !=''){ echo $formModuleForm;} ?>'				
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
			editable: true,
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
						Ext.getCmp('position').setValue('');
						Ext.getCmp('position').setReadOnly(false);
					}
				},
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-section")[0].value = '';
					}
				},
				'afterrender': function(obj, opts, record){
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
			value: '<?php echo $objCustomField->getSectionNameById($formPosition); ?>',
			valueField: 'value',
			displayField: 'label',
			pageSize: 15,
			emptyText: 'Please select field position',
			editable: true,
			matchFieldWidth: true,
			listConfig:{
				'refresh': function () {
					var me = this,
					toolbar = me.pagingToolbar;
					toolbar.hide();
					Ext.view.View.prototype.refresh.call(me);
					if (me.rendered && toolbar && toolbar.rendered && !me.preserveScrollOnRefresh) {
						me.el.appendChild(toolbar.el);
						me.el.last().hide();						
					}
				}
			},
			listeners:{				
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
	
	function submitForm(mode){
		$('#submit_mode').val(mode);		
		clearValidation('form_1');
		if(!validateExtEmpty('moduleform', 'form')){return;}
		if(!validateExtEmpty('section', 'form section')){return;}
		if(!validateEmpty($('#fieldlabel'), 'field label')){return;}
		if(!validateExtEmpty('position', 'field position')){return;}
		if(!validateEmpty($('#combo-fieldtype'), 'field type')){return;}
		if(!validateEmpty($('#combo-mandatory'), 'mandatory field')){return;}
		
		if($('#fieldorder').val() != '' && !validateNumber($('#fieldorder'), 'field order')){return;}
		if(!dynValidator['fieldlabel']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkFieldExistWithCombo($('#fieldlabel'), 'ext-moduleform', '', 'fieldlabel', $('#loader_fieldlabel'), 'check_duplicate_fieldlabel', true);
		}else{			
			$('#form_1').submit();
		}
	}
</script>