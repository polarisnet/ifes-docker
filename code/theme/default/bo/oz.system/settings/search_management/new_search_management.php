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
			<td class="lbl-title" colspan="2">Search Management Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="form-spacer-solo">
					<tr>
						<td class="lbl-field">Module Name</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><div id="input_module"></div></td>
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
	Ext.onReady(function(){		
		var modulesearchStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_modulesearch'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			fields:['id', 'module_name']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_module',
			id: 'modulesearch',
			name: 'modulesearch',
			hiddenName: 'ext-modulesearch',
			store: modulesearchStore,
			root: 'combo',
			queryMode: 'remote',
			value: '<?php echo $objSearchManagement->getModuleNameById(encryption(rawurldecode($formModule), $_SESSION['salt'], false)); ?>',
			valueField: 'id',
			displayField: 'module_name',
			pageSize: 15,
			emptyText: 'Please select module',
			listConfig:{
				loadingText: 'Searching...',
				//minWidth: 305,
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Name: {module_name}<br><hr>';
				}
			},
			listeners:{
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-modulesearch")[0].value = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-modulesearch")[0].value = '<?php echo $formModule; ?>';
				}	
			}			
		});
	});	
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateExtEmpty('modulesearch', 'module name')){return;}
		$('#form_1').submit();
	}
</script>