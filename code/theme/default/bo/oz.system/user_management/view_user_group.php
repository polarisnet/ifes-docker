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
				<?php if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteUserGroup('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.user_management.usergroups.list'); ?>';">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">User Group Information</td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Group Name</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $groupData['group_name']; ?></td>
								</tr>
							</table>
						</td>
					</tr>					
					<?php if($decryptKey != '1'){?>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container-recordpermission', 'collapse-recordpermission');">User Group Records Permission (<span id="grid-count-info-RecordPermission">0</span>)</span><span id="collapse-recordpermission" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container-recordpermission', 'collapse-recordpermission');">[+]</span></td>
					</tr>
					<tr>
						<td colspan="2">
							<br><div id="ext-recordpermission-grid"></div>
							<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/GridFilters.css" />
							<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/RangeMenu.css" />                
							<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/oz.system/user_management/list_record_permission.js"></script>
							<script type="text/javascript">
								Ext.Loader.setPath('Ext.ux', '<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux');
								Ext.require([
									'Ext.ux.ProgressBarPager',
									'Ext.ux.grid.FiltersFeature'
								]);
								Ext.namespace('RecordPermission');
								RecordPermission.app = function(){
									return{
										init: function(){
											var mask = new Ext.LoadMask(document.getElementById('ext-recordpermission-grid'),{ msg: 'Loading...'});
											var listPanelOfRecordPermission = new ListPanelOfRecordPermission({
												'start': '<?php echo $recordPermissionStart; ?>',
												'itemsPerPage': '<?php echo $recordPermissionPerPage; ?>',
												'listFields': [<?php echo implode(",", $recordPermissionFields); ?>],
												'allowEdit': '<?php echo $allowEdit; ?>',
												'parent': '<?php echo $encryptKey; ?>'
											});
											Ext.create('Ext.container.Container',{
												id: 'ext-container-recordpermission',
												renderTo: 'ext-recordpermission-grid',
												hidden: true,
												items:[listPanelOfRecordPermission]
											});
										}
									}
								}();
								Ext.onReady(RecordPermission.app.init, RecordPermission.app);
							</script>				
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td class="lbl-title" colspan="2">User Group Privileges</td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="default-table">								
							<?php foreach($getMainModule AS $key => $mainModule){ ?>
								<tr class="default-header">
									<th class="default-table-td td1" style="width: 15%; "><?php echo ucfirst($mainModule['module_display']) ?></th>
									<th class="default-table-td td2" style="width: 14%; ">Add</th>
									<th class="default-table-td td3" style="width: 14%; ">Edit</th>
									<th class="default-table-td td4" style="width: 14%; ">Delete</th>
									<th class="default-table-td td5" style="width: 14%; ">View</th>
									<th class="default-table-td td6" style="width: 14%; ">List</th>
								</tr>
								<?php if(!empty($mainModule['child'])){ ?>								
									<?php foreach($mainModule['child'] AS $subkey => $submainModule){ ?>
										<?php if($submainModule['uid'] == 'reports.activities' || $submainModule['uid'] == 'reports.customers' || $submainModule['uid'] == 'reports.projects' || $submainModule['uid'] == 'oz.system.logs' ||  $submainModule['uid'] == 'reports.transactions'){ ?>	
											<?php if($submainModule['uid'] == 'oz.system.logs'){ 
												$submainModule['uid'] = 'oz.system.logs.audit';} ?>
											<tr class="default-odd">
												<td class="default-table-td td1"><?php echo ucfirst($submainModule['module_display']); ?></td>
												<td class="default-table-td td2">-</td>
												<td class="default-table-td td3">-</td>
												<td class="default-table-td td4">-</td>
												<td class="default-table-td td5"><?php if(getAccess($submainModule['uid'], $groupModule)){?><div class="state icheckbox_square-blue checked"  style="cursor: default;"></div><?php }else{ ?><div class="state icheckbox_square-blue" style="cursor: default;"></div> <?php } ?></td>
												<td class="default-table-td td6">-</td>
											</tr>										
										<?php }else{ ?>
											<?php	if($submainModule['uid'] == 'transactions.settings'){ 
														$submainModule['uid'] = 'transactions.settings.locations'; 
													}else if($submainModule['uid'] == 'oz.system.settings'){
														$submainModule['uid'] = 'oz.system.settings.custom_field';
													}else if($submainModule['uid'] == 'oz.system.user_management'){
														$submainModule['uid'] = 'oz.system.user_management.usergroups';
													}
											?>
											<tr class="default-odd">
												<td class="default-table-td td1"><?php echo ucfirst($submainModule['module_display']); ?></td>
												<td class="default-table-td td2"><?php if(getAccess($submainModule['uid'].".new", $groupModule)){?><div class="state icheckbox_square-blue checked"  style="cursor: default;"></div><?php }else{ ?><div class="state icheckbox_square-blue" style="cursor: default;"></div> <?php } ?></td>
												<td class="default-table-td td3"><?php if(getAccess($submainModule['uid'].".edit", $groupModule)){?><div class="state icheckbox_square-blue checked"  style="cursor: default;"></div><?php }else{ ?><div class="state icheckbox_square-blue" style="cursor: default;"></div> <?php } ?></td>
												<td class="default-table-td td4"><?php if(getAccess($submainModule['uid'].".delete", $groupModule)){?><div class="state icheckbox_square-blue checked"  style="cursor: default;"></div><?php }else{ ?><div class="state icheckbox_square-blue" style="cursor: default;"></div> <?php } ?></td>
												<td class="default-table-td td5"><?php if(getAccess($submainModule['uid'].".view", $groupModule)){?><div class="state icheckbox_square-blue checked"  style="cursor: default;"></div><?php }else{ ?><div class="state icheckbox_square-blue" style="cursor: default;"></div> <?php } ?></td>
												<td class="default-table-td td6"><?php if(getAccess($submainModule['uid'].".list", $groupModule)){?><div class="state icheckbox_square-blue checked"  style="cursor: default;"></div><?php }else{ ?><div class="state icheckbox_square-blue" style="cursor: default;"></div> <?php } ?></td>
											</tr>								
										<?php } ?>
									<?php } ?>
								<?php } ?>
								<tr class="default-odd">
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
								</tr>
							<?php } ?>							
							</table>
						</td>	
					</tr>	
					<?php }else{ ?>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td class="lbl-title" colspan="2">Group Records Permission</td>
					</tr> 
					<tr><td colspan="2">Administrator account has full permission of view recors </td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td class="lbl-title" colspan="2">User Group Privileges</td>
					</tr>
					<tr><td colspan="2">Administrator account has full privileges.</td></tr>
					<?php } ?>	
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">User Group Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Group Name</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
										<input type="text" class="flat-input" id="groupname" name="groupname" onblur="javascript: checkFieldExist($('#groupname'), '<?php echo $encryptKey; ?>', 'groupname', $('#loader_usergroup'), 'check_duplicate_usergroup', true);" value="<?php echo $formGroupName;?>">
										<img id="loader_usergroup" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
									</td>
								</tr>
							</table>
						</td>
					</tr>					
					<?php if($decryptKey != '1'){?>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container', 'collapse-activity');">User Group Records Permission</span><span id="collapse-activity" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container', 'collapse-activity');">[+]</span></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td class="lbl-title" colspan="2">User Group Privileges</td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="default-table">								
							<?php foreach($getMainModule AS $key => $mainModule){ ?>
								<tr class="default-header">
									<th class="default-table-td td1" style="width: 15%; "><?php echo ucfirst($mainModule['module_display']) ?></th>
									<th class="default-table-td td2" style="width: 14%; ">Add</th>
									<th class="default-table-td td3" style="width: 14%; ">Edit</th>
									<th class="default-table-td td4" style="width: 14%; ">Delete</th>
									<th class="default-table-td td5" style="width: 14%; ">View</th>
									<th class="default-table-td td6" style="width: 14%; ">List</th>
									<th class="default-table-td td7" style="width: 10%; ">Check All</th>
								</tr>
								<?php if(!empty($mainModule['child'])){ ?>								
									<?php foreach($mainModule['child'] AS $subkey => $submainModule){ ?>
										<?php if($submainModule['uid'] == 'reports.activities' || $submainModule['uid'] == 'reports.customers' || $submainModule['uid'] == 'reports.projects' || $submainModule['uid'] == 'oz.system.logs' || $submainModule['uid'] == 'reports.transactions'){ ?>	
											<?php if($submainModule['uid'] == 'oz.system.logs'){ 
												$submainModule['uid'] = 'oz.system.logs.audit';} ?>
											<tr class="default-odd">
												<td class="default-table-td td1"><?php echo ucfirst($submainModule['module_display']); ?></td>
												<td class="default-table-td td2">-</td>
												<td class="default-table-td td3">-</td>
												<td class="default-table-td td4">-</td>
												<td class="default-table-td td5"><input type="checkbox" id="<?php echo $submainModule['name']; ?>" name="<?php echo $submainModule['name']; ?>" value="1" <?php if(getAccess($submainModule['uid'], $groupModule)){echo 'checked';} ?>></td>
												<td class="default-table-td td6">-</td>
												<td class="default-table-td td7">-</td>
											</tr>
										<?php }else{ ?>
											<?php	if($submainModule['uid'] == 'transactions.settings'){ 
														$submainModule['uid'] = 'transactions.settings.locations'; 
													}else if($submainModule['uid'] == 'oz.system.settings'){
														$submainModule['uid'] = 'oz.system.settings.custom_field';
													}else if($submainModule['uid'] == 'oz.system.user_management'){
														$submainModule['uid'] = 'oz.system.user_management.usergroups';
													}
													
											?>
											<tr class="default-odd">
												<td class="default-table-td td1"><?php echo ucfirst($submainModule['module_display']); ?></td>
												<td class="default-table-td td2"><input type="checkbox" id="<?php echo $submainModule['name']."_new"; ?>" name="<?php echo $submainModule['name']."_new"; ?>" value="1" <?php if(getAccess($submainModule['uid'].".new", $groupModule)){echo 'checked';} ?> /></td>
												<td class="default-table-td td3"><input type="checkbox" id="<?php echo $submainModule['name']."_edit"; ?>" name="<?php echo $submainModule['name']."_edit"; ?>" value="1" <?php if(getAccess($submainModule['uid'].".edit", $groupModule)){echo 'checked';} ?> /></td>
												<td class="default-table-td td4"><input type="checkbox" id="<?php echo $submainModule['name']."_delete"; ?>" name="<?php echo $submainModule['name']."_delete"; ?>" value="1" <?php if(getAccess($submainModule['uid'].".delete", $groupModule)){echo 'checked';} ?> /></td>
												<td class="default-table-td td5"><input type="checkbox" id="<?php echo $submainModule['name']."_view"; ?>" name="<?php echo $submainModule['name']."_view"; ?>" value="1" <?php if(getAccess($submainModule['uid'].".view", $groupModule)){echo 'checked';} ?> /></td>
												<td class="default-table-td td6"><input type="checkbox" id="<?php echo $submainModule['name']."_list"; ?>" name="<?php echo $submainModule['name']."_list"; ?>" value="1" <?php if(getAccess($submainModule['uid'].".list", $groupModule)){echo 'checked';} ?> /></td>
												<td class="default-table-td td7"><input type="checkbox" id="<?php echo $submainModule['name']; ?>" name="<?php echo $submainModule['name']; ?>" value="1" <?php if(getAccess($submainModule['uid'].".new", $groupModule) && getAccess($submainModule['uid'].".edit", $groupModule) && getAccess($submainModule['uid'].".delete", $groupModule) && getAccess($submainModule['uid'].".view", $groupModule) && getAccess($submainModule['uid'].".list", $groupModule)){echo 'checked';} ?> onchange="checkALl()"/></td>
											</tr>
										<?php } ?>										
									<?php } ?>	
								<?php } ?>
								<tr class="default-odd">
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
									<td >&nbsp;</td>
								</tr>	
							<?php } ?>
							</table>
						</td>	
					</tr>	
					<?php } ?>
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
				<?php if($allowDelete){ ?>
				<input type="button" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteUserGroup('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.user_management.usergroups.list'); ?>';">
			</td>
		</tr>
	</table>
</form>
<?php if($allowEdit){ ?>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/skins/square/blue.css" />
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/jquery.icheck.min.js"></script>
<?php	$winReady .= "$('input').iCheck({checkboxClass: 'icheckbox_square-blue'});"; ?>
<script type="text/javascript">	
	var dynValidator = new Array();
	dynValidator['groupname'] = true;
	
	$(document).ready(function() {
		var tempArray = <?php echo json_encode($arrSubModule); ?>;
		var arrayLength = tempArray.length;
		for(i=0; i < arrayLength; i++){		
			$('#'+tempArray[i]).on('ifClicked', function(event){
				if(event.currentTarget.checked === false){
					$('#'+this.id+', #'+this.id+'_new, #'+this.id+'_edit, #'+this.id+'_delete, #'+this.id+'_view, #'+this.id+'_list').iCheck('check');
				}else{
					$('#'+this.id+', #'+this.id+'_new, #'+this.id+'_edit, #'+this.id+'_delete, #'+this.id+'_view, #'+this.id+'_list').iCheck('uncheck');
				}
			});
			
			$('#'+tempArray[i]+'_list').on('ifUnchecked', function(event){
				$('#'+this.id+', #'+this.id.replace('_list','')+'_new, #'+this.id.replace('_list','')+'_edit, #'+this.id.replace('_list','')+'_delete, #'+this.id.replace('_list','')+'_view').iCheck('uncheck');
			});	
						
			$('#'+tempArray[i]+'_view').on('ifUnchecked', function(event){
				$('#'+this.id+', #'+this.id.replace('_view','')+'_edit').iCheck('uncheck');
			});	
			
			$('#'+tempArray[i]+'_view').on('ifChecked', function(event){
				$('#'+this.id.replace('_view','')+'_list').iCheck('check');
			});	
			
			$('#'+tempArray[i]+'_edit').on('ifChecked', function(event){
				$('#'+this.id.replace('_edit','')+'_list, #'+this.id.replace('_edit','')+'_view').iCheck('check');
			});	
			
			$('#'+tempArray[i]+'_delete').on('ifChecked', function(event){
				$('#'+this.id.replace('_delete','')+'_list').iCheck('check');
			});	
			
			$('#'+tempArray[i]+'_new').on('ifChecked', function(event){
				$('#'+this.id.replace('_new','')+'_view').iCheck('check');
			});	
		}
	});
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#groupname'), 'group name')){return;}
		if(!dynValidator['groupname']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkFieldExist($('#groupname'), '<?php echo $encryptKey; ?>', 'groupname', $('#loader_usergroup'), 'check_duplicate_usergroup', true);
		}else{
			$('#form_1').submit();
		}
	}
</script>
<?php } ?>
<?php if($allowDelete){ ?>
<script type="text/javascript">	
	function deleteUserGroup(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected user group?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_usergroups',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'User group successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('oz.system.user_management.usergroups.list'); ?>';
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