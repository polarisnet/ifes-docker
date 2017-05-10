<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<table>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_NEW; ?>" data-placement="top" onclick="javascript: submitForm('');">
				<input type="button" value="Save & New" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVENEW; ?>" data-placement="top" onclick="javascript: submitForm('new');">
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo HTTP_ACTIVE_PARENT; ?>';">
			</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">Banner Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="form-spacer-solo">
					<tr>
						<td class="lbl-field">Image</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td><div id="input_image"></div></td>						
					</tr>
					<tr>
						<td class="lbl-field">Caption</td>
						<td class="lbl-gap"></td>
						<td><input type="text" class="flat-input" id="caption" name="caption" value="<?php echo $formCaption; ?>"></td>
					</tr>
<!--					<tr>
						<td class="lbl-field">Image Effect</td>
						<td class="lbl-gap"></td>
						<td><div id="input_imageEffect"></div></td>	
					</tr>-->
					<tr>
						<td class="lbl-field">Order</td>
						<td class="lbl-gap"></td>
						<td><div id="input_order"></div></td>						
					</tr>
					<tr>
						<td class="lbl-field">Type</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td>
							<select id="type" name="type" class="flat-selectbox">
                            	<option value="Login Screen" <?php if($formType == "Login Screen"){echo ("selected");}?> >Login Screen</option>                             
							</select>
						</td>
					</tr>
					<tr>
						<td class="lbl-field">Status</td>
						<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
						<td>
							<select id="status" name="status" class="flat-selectbox">
                            	<option value="1" <?php if($formStatus == "1"){echo ("selected");}?> >Active</option>
                                <option value="0" <?php if($formStatus == "0"){echo ("selected");}?> >Inactive</option>                                
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">Additional Information</td>
		</tr>
		<tr>
			<td class="form-spacer1">
				<table>
					<tr>
						<td class="lbl-field">Remarks</td>
						<td class="lbl-gap">&nbsp;</td>
						<td><textarea id="remarks" name="remarks" class="flat-textarea"><?php echo nl2eol($formRemarks); ?></textarea></td>
					</tr>                    
				</table>
			</td>
			<td class="form-spacer1"></td>
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
	dynValidator['type'] = false;
	
	Ext.onReady(function(){
		Ext.create('Ext.form.field.File', {
			renderTo: 'input_image',
			id: 'image',
			name: 'image',
			width: 208,	
			allowBlank: false,			
			buttonText: 'Select Image'
		});
		
//		var imageEffectStore = Ext.create('Ext.data.ArrayStore', {
//			fields: ['effect'],
//			data : [
//				['boxRain'],
//				['boxRainReverse'],
//				['boxRainGrow'],
//				['boxRainGrowReverse'],
//				['boxRandom'],
//				['fade'],
//				['fold'],
//				['random'],
//				['sliceDown'],
//				['sliceDownLeft'],
//				['sliceDownRight'],
//				['slideInLeft'],
//				['slideInRight'],
//				['sliceUp'],
//				['sliceUpDown'],
//				['sliceUpDownLeft'],
//				['sliceUpLeft'],
//				['sliceUpRight']
//			]
//		});
//		
//		Ext.create('Ext.form.field.ComboBox', {
//            renderTo        : 'input_imageEffect',
//            id              : 'imageEffect',
//            name            : 'imageEffect',
//            hiddenName      : 'ext-imageEffect',
//            store           : imageEffectStore,           
//            emptyText       : 'Please select image effect',				
//            root            : 'combo',
//            queryMode       : 'local',		
//            valueField      : 'effect',
//            displayField    : 'effect',
//            value           : '<?php echo isset($formEffect)?$formEffect:""; ?>',
//            editable		: false,
//            triggerAction	: 'all',
//            selectOnFocus	: true,
//			forceSelection	: true,
//            matchFieldWidth : true,
//			listeners:{
//				'change': function(obj, newValue, oldValue, opts){
//					if(newValue == ''){
//						document.getElementsByName("imageEffect")[0].value = '';
//					}
//				},
//               'afterrender': function(obj, opts){
//					<?php if(isset($formEffect) && $formEffect !=""){ ?>
//						document.getElementsByName("imageEffect")[0].value = '<?php echo $formEffect; ?>';
//					<?php } ?>
//               }
//			}
//        });
		
		Ext.create('Ext.form.field.Number', {
			renderTo: 'input_order',
			id: 'order',
			name: 'order',
			minValue: 0,			
			width: 208,
			allowExponential: false,
			allowDecimals: false,
			hideTrigger: true,
			value: '<?php echo $formOrder; ?>'
		});
	});
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateExtEmpty('image', 'image')){return;}
		$('#form_1').submit();
	}
</script>