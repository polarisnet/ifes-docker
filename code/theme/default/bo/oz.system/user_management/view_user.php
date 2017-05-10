<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
	<table style="position: relative;">
		<tr>
			<td colspan="2" style="text-align: center;">
				<?php //echo getGridPreviousButton($gridState, $userData['id'], MODULE_UID); ?>
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit" value="Edit" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<?php //echo getGridSaveNextButton($gridState, $userData['id']); ?>
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteUser('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<?php //echo getGridNextButton($gridState, $userData['id'], MODULE_UID); ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.user_management.users.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">User Information</td>
					</tr>
					<tr>
						<td class="form-spacer1" style="position: relative; height: 110px;">
							<table>
								<tr>
									<td class="lbl-field">Username</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $userData['username']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">User Group</td>
									<td class="lbl-gap">:</td>
									<td><?php if(isset($userGroupData['group_name'])){echo $userGroupData['group_name'];}else{echo 'Group does not exist';} ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Access Type</td>
									<td class="lbl-gap">:</td>
									<td><?php if($userData['access'] == 'fo'){echo 'Front Office (FO)';}else if($userData['access'] == 'bo'){echo 'Back Office (BO)';}else{echo 'Both';} ?></td>
								</tr>
							</table>
							<div class="userform-profilepics">
								<img src="<?php echo getDefaultPicture($userData['uid']); ?>" style="width: 100px; height: 100px;">
							</div>
						</td>
						<td class="form-spacer1">
							<table>
								<tr>
									<td class="lbl-field">Email Address</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $userData['email']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">First Name</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $userData['first_name']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Last Name</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $userData['last_name']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Status</td>
									<td class="lbl-gap">:</td>
									<td><?php if($userData['status']){echo 'Active'; }else{echo 'Blocked';} ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">User Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td class="form-spacer1" style="position: relative; height: 110px;">
							<table>
								<tr>
									<td class="lbl-field">Username</td>
									<td class="lbl-gap"></td>
									<td><?php echo $userData['username']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">User Group</td>
									<td class="lbl-gap"></td>
									<td><?php if(isset($userGroupData['group_name'])){echo $userGroupData['group_name'];}else{echo 'Group does not exist';} ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Access Type</td>
									<td class="lbl-gap"></td>
									<td><?php if($userData['access'] == 'fo'){echo 'Front Office (FO)';}else if($userData['access'] == 'bo'){echo 'Back Office (BO)';}else{echo 'Both';} ?></td>
								</tr>
							</table>
							<div class="userform-profilepics">
								<img src="<?php echo getDefaultPicture($userData['uid']); ?>" style="width: 100px; height: 100px;">
								<br><br>
								<input type="button" value="Change Picture" class="flat-button-default" onclick="">
							</div>
							<br><br><br><br>
						</td>
						<td class="form-spacer1">
							<table>
								<tr>
									<td class="lbl-field">Email Address</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
										<input type="text" class="flat-input" id="email" name="email" value="<?php echo $formEmail; ?>" onblur="javascript: checkEmailExist($('#email'), 'email address', '<?php echo $encryptKey; ?>', 'email'); ">
										<img id="loader_email" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
									</td>
								</tr>
								<tr>
									<td class="lbl-field">First Name</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*</div></td>
									<td><input type="text" class="flat-input" id="first_name" name="first_name" value="<?php echo $formFName; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Last Name</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*</div></td>
									<td><input type="text" class="flat-input" id="last_name" name="last_name" value="<?php echo $formLName; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Status</td>
									<td><div class="lbl-compulsory">*</div></td>
									<td>
										<select id="status" name="status" class="flat-selectbox">
											<option value="1" <?php if($formStatus == '1'){echo 'selected';}?>>Active</option>
											<option value="0" <?php if($formStatus == '0'){echo 'selected';}?>>Blocked</option>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
				</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<?php //echo getGridPreviousButton($gridState, $userData['id'], MODULE_UID); ?>
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit1" value="Edit" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar1" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<?php //echo getGridSaveNextButton($gridState, $userData['id']); ?>
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteUser('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<?php //echo getGridNextButton($gridState, $userData['id'], MODULE_UID); ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.user_management.users.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<?php if($allowEdit){ ?>
<script type="text/javascript">	
	var dynValidator = new Array();
	dynValidator['email'] = true;
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#email'), 'email')){return;}
		if(!validateEmail($('#email'), 'email')){return;}
		if(!validateEmpty($('#first_name'), 'first name')){return;}
		if(!validateEmpty($('#last_name'), 'last name')){return;}
		if($('#telephone').val() != '' && !validatePhone($('#telephone'), 'telephone number')){return;}
		var cur = <?php echo $userData['id']; ?>;
		if(cur == <?php echo $_SESSION['user_id']; ?>){
			if($('#sec_question1').val() == '0' && !validateEmpty($('#sec_question2'), 'security question')){return;}
			if(!validateEmpty($('#sec_answer'), 'security answer')){return;}
		}
		if(!dynValidator['email']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkEmailExist($('#email'), 'email address', '<?php echo $encryptKey; ?>', 'email');
		}else{
			$('#form_1').submit();
		}
	}
</script>
<?php } ?>
<?php if($allowDelete){ ?>
<script type="text/javascript">	
	function deleteUser(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected user?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_users',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'User successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('oz.system.user_management.users.list'); ?>';
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