<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
	<table style="position: relative;">
		<tr>
			<td colspan="2" style="text-align: center;">
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit" value="Edit" class="flat-button-default" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteActivityType('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.site.banner.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Banner Information</td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Preview</td>
									<td class="lbl-gap">:</td>
									<td>
										<a href='<?php echo $bannerPath.$bannerData['path']; ?>' target="_blank">
											<img style="width: 150px; height: auto;" src='<?php echo $bannerPath.$bannerData['path']; ?>'></br>
										</a>
										<?php echo $bannerData['path']; ?>
									</td>
								</tr>
								<tr>
									<td class="lbl-field">Caption</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $bannerData['caption']; ?></td>
								</tr>
<!--								<tr>
									<td class="lbl-field">Image Effect</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $bannerData['effect']; ?></td>
								</tr>-->
								<tr>
									<td class="lbl-field">Order</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $bannerData['order']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Type</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $bannerData['type']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Status</td>
									<td class="lbl-gap">:</td>
									<td><?php if($bannerData['status'] === '1'){echo "Active";}else{echo "Inactive";} ; ?></td>
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
									<td class="lbl-gap">:</td>
									<td><?php echo $bannerData['remarks']; ?></td>
								</tr>                    
							</table>
						</td>
						<td class="form-spacer1"></td>
					</tr>
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Banner Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr id="preview">
									<td class="lbl-field">Preview</td>
									<td class="lbl-gap"></td>
									<td>
										<img style="width: 150px; height: auto;" src='<?php echo $bannerPath.$bannerData['path']; ?>'></br>
										<?php echo $bannerData['path']; ?></br>
										<input type="button" value="Remove" class="flat-button-default" onclick="javascript: display();">
									</td>						
								</tr>
								<tr id="upload" style="display:none">
									<td class="lbl-field">Image</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><div id="input_image"></div></td>						
								</tr>
								<tr>
									<td class="lbl-field">Caption</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="caption" name="caption" value="<?php echo $formCaption; ?>"></td>
								</tr>
<!--								<tr>
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
				</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit1" value="Edit" class="flat-button-default" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar1" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteActivityType('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.settings.site.banner.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<?php if($allowEdit){ ?>
<script type="text/javascript">	
	var dynValidator = new Array();
	dynValidator['type'] = true;
	
	Ext.onReady(function(){
		var imageFile = Ext.create('Ext.form.field.File', {
			renderTo: 'input_image',
			id: 'image',
			name: 'image',
			width: 116,	
			msgTarget: 'side',
			buttonText: 'Select Image',
			emptyText: "Please select image"
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
	function display() {
			$('#submit_mode').val('reupload');
			document.getElementById("preview").style.display = "none";
			document.getElementById("upload").style.display = "";		
	}
	function submitForm(mode){
		clearValidation('form_1');
		var checkRemove = document.getElementById("preview");
		if(checkRemove.style.display == "none"){
			$('#submit_mode').val('reupload');			
			if(!validateExtEmpty('image', 'image')){return;}
		} else {
			$('#submit_mode').val(mode);
		}	
		$('#form_1').submit();
		
	}
</script>
<?php } ?>
<?php if($allowDelete){ ?>
<script type="text/javascript">	
	function deleteActivityType(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected banner?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_banner',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'Banner successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('oz.system.settings.site.banner.list'); ?>';
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