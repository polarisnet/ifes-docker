<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<table style="position: relative;">
		<tr>
			<td colspan="2" style="text-align: center;">
				<?php echo getGridPreviousButton($gridState, $trailsData['id'], MODULE_UID); ?>
				<input type="button" id="btn-delete" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteTrails('<?php echo urlencode($encryptKey); ?>');">
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.logs.audit'); ?>';">
				<?php echo getGridNextButton($gridState, $trailsData['id'], MODULE_UID); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table">
					<tr>
						<td class="lbl-title" colspan="2">Audit Trails Details</td>
					</tr>
					<tr>
						<td colspan="2">
							<table class="form-spacer-solo">
								<tr>
									<td class="lbl-field">Type</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $trailsData['type']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Module</td>
									<td>:</td>
									<td><?php echo $trailsData['module']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Created By</td>
									<td>:</td>
									<td><?php echo $trailsData['created_by_format']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Created Date</td>
									<td>:</td>
									<td><?php echo $trailsData['created_date']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">JSON Before</td>
									<td>:</td>
									<td><?php echo wordwrap($trailsData['json_before'], 160, "<br>", true); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">JSON After</td>
									<td>:</td>
									<td><?php echo wordwrap($trailsData['json_after'], 160, "<br>", true);  ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Extra</td>
									<td>:</td>
									<td><?php echo wordwrap($trailsData['extra'], 160, "<br>", true);  ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<?php echo getGridPreviousButton($gridState, $trailsData['id'], MODULE_UID); ?>
				<input type="button" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteTrails('<?php echo urlencode($encryptKey); ?>');">
				<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.system.logs.audit'); ?>';">
				<?php echo getGridNextButton($gridState, $trailsData['id'], MODULE_UID); ?>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">	
	function deleteTrails(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected audit trail?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_trails',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'Audit Trails successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('oz.system.logs.audit'); ?>';
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