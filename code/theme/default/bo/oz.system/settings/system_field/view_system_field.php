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
				<?php /*if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" onclick="javascript: deleteContact('<?php echo urlencode($encryptKey); ?>');"><?php }*/ ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.system_field.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">System Field Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td class="form-spacer1">
                        	<table>
								<tr>
									<td class="lbl-field">Form</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $objSystemField->getFormNameById($systemfieldData['sys_module_id'],$systemfieldData['module_uid']);  ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Field Label</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $systemfieldData['cf_label'];  ?></td>
								</tr>
							</table>
						</td>
						<td class="form-spacer1">
							<table>
								<tr>
									<td class="lbl-field">Mandatory Field</td>
									<td class="lbl-gap">:</td>
									<td><?php if($systemfieldData['cf_mandatory'] == 1) {echo "Yes";} else { echo "No";} ?></td>
								</tr>
							</table>
                        </td>
					</tr>					
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">System Field Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
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
                                    <td class="lbl-field">Field Label</td>
                                    <td class="lbl-gap">:</td>
                                    <td>
										<?php echo $formFieldLabel;  ?>
                                        <input type="hidden" class="flat-input" id="fieldlabel" name="fieldlabel" value="<?php echo $formFieldLabel; ?>">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="form-spacer1">
                        	<table>
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
		<tr id="trInternalStuffId1"><td colspan="2">&nbsp;</td></tr>
		<tr id="trInternalStuffId2">
			<td class="lbl-title" colspan="2">System Field Selectable Values</td>
		</tr>
		<tr id="trInternalStuffId3">
			<td colspan="2">
				<br><div id="ext-details-grid"></div>				
				<script type="text/javascript">
					Ext.onReady(function(){
					<?php if($formFieldType!="") { ?>
						showHideSystemFieldSelectableValues('<?php echo $formFieldType; ?>');
					<?php } else { ?>
						showHideSystemFieldSelectableValues('');
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
				<?php /*if($allowDelete){ ?><input type="button" value="Delete" class="flat-button-default" onclick="javascript: deleteContact('<?php echo urlencode($encryptKey); ?>');"><?php }*/ ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.system_field.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/GridFilters.css" />
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/RangeMenu.css" />                
<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/oz.system/settings/system_field/option_content.js"></script>
<script type="text/javascript">
	function showHideSystemFieldSelectableValues(value) {
		if(value=="dropbox" || value =="checkbox" || value =="radio") {			
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
			value: '<?php echo $objSystemField->getFormNameById($formModuleForm,$formUID);?>',
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
					/*sectionStore.getProxy().extraParams = {
						opt: 'combo_section',
							conditions: record[0].get('id')	
					};
					sectionStore.load();*/				
				},
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-moduleform")[0].value = '';
						/*sectionStore.getProxy().extraParams = {
							opt: 'combo_section',
							conditions: ''	
						};
						sectionStore.load();*/
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-moduleform")[0].value = '<?php echo $formModuleForm; ?>';
				}
			}
		});
	});
	
	var dynValidator = new Array();

	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateExtEmpty('moduleform', 'form')){return;}
		if(!validateEmpty($('#fieldlabel'), 'field label')){return;}
		$('#form_1').submit();
	}
</script>
<?php } ?>