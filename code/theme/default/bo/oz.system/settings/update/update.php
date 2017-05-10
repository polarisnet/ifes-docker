<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<table style="position: relative;">
		<tr>
			<td class="lbl-title" colspan="2">Update Logs</td>
		</tr>
		<tr>
			<td colspan="2">
				<br>
				<div style="text-align: center;">
					<select id="update-select" onchange="javascript: readLog();">
						<option value="">Please select any update logs...</option>
						<?php echo listUpdateLogHTML(); ?>
					</select>
					<br><br>
					<div id="current_version">
						Current version : <?php echo $updaterData['version'];?>
					</div>
					<br>
					<div id="update_version">
						Next update version : <?php echo $nextVersion;?>
						<?php if($hasUpdate && $showUpdateOption){ ?>
							<script type="text/javascript">
								function updateNow(){
									var request = $.ajax({
										url: HTTP_AJAX,
										type: 'POST',
										dataType: 'json',
										data:{
											opt: 'update_now'
										}
									}).done(function(msg){
										if(msg.success){
											$('#oz-noty').oznoty([{
												'type': 'message',
												'title': 'Update System',
												'content': 'Your update request is being process. Please refresh this page regularly.',
												'position': 'right',
												'autoclose': true
											}]);
											location.reload();
										}else{
											$('#oz-noty').oznoty([{
												'type': 'error',
												'title': 'Error',
												'content': 'Could not update system. Please try again.',
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
							</script>
							<input type="button" value="Update Now" class="flat-button-default" rel="tooltip" data-original-title="Update Now" data-placement="top" onclick="updateNow();">
						<?php } ?>
					</div>
					<br>
					<div id="last_backup_db">
						Last backup database : <?php echo $lastBackupDB;?>
					</div>
					<br>
					<div id="last_backup_script">
						Last backup scripts : <?php echo $lastBackupScript;?>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="lbl-title" colspan="2">Details</td>
		</tr>
		<tr>
			<td>
				<br>
				<textarea id="update-content" style="width: 99%; height: 300px;  resize: none;" readonly></textarea>
				<br><br>
				<div style="text-align: center;">
					<?php if($showUpdateOption){ ?>
					<input type="button" value="Backup Scripts" class="flat-button-default" rel="tooltip" data-original-title="Backup Scripts" data-placement="top" onclick="backupScripts();">
					<input type="button" value="Backup Database" class="flat-button-default" rel="tooltip" data-original-title="Backup Database" data-placement="top" onclick="backupDB();">
					<?php } ?>
				</div>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	function readLog(){
		var file = $('#update-select').val();
		if(file != ''){
			var request = $.ajax({
				url: HTTP_AJAX,
				type: 'POST',
				dataType: 'json',
				data:{
					opt: 'read_log',
					file: file
				}
			}).done(function(msg){
				document.getElementById('update-content').innerHTML = msg.log;
				$('#update-content').scrollTop($('#update-content')[0].scrollHeight);			
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
	}

	<?php if($showUpdateOption){ ?>
	function backupDB(){
		Ext.getBody().mask('Processing...');
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: 'backup_db'
			}
		}).done(function(msg){
			if(msg.success){
				$('#oz-noty').oznoty([{
					'type': 'message',
					'title': 'Backup Database',
					'content': 'Database has been successfully backup.',
					'position': 'right',
					'autoclose': true
				}]);
				document.getElementById("update-select").options.length = 0;
				$("#update-select").append(msg.list);
				$('#last_backup_db').html('Last backup database : '+msg.last_backup_db);
				$('#last_backup_script').html('Last backup scripts : '+msg.last_backup_script);
				readLog();
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': 'Could not backup database. Please try again.',
					'position': 'right',
					'autoclose': true
				}]);
			}
			Ext.getBody().unmask();
		}).fail(function(jqXHR, textStatus){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Could not connect with server. Please refresh browser and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			Ext.getBody().unmask();
		});
	}

	function backupScripts(){
		Ext.getBody().mask('Processing...');
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: 'backup_scripts'
			}
		}).done(function(msg){
			if(msg.success){
				$('#oz-noty').oznoty([{
					'type': 'message',
					'title': 'Backup Scripts',
					'content': 'Scripts have been successfully backup.',
					'position': 'right',
					'autoclose': true
				}]);
				document.getElementById("update-select").options.length = 0;
				$("#update-select").append(msg.list);
				$('#last_backup_db').html('Last backup database : '+msg.last_backup_db);
				$('#last_backup_script').html('Last backup scripts : '+msg.last_backup_script);
				readLog();
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': 'Could not backup scripts. Please try again.',
					'position': 'right',
					'autoclose': true
				}]);
			}
			Ext.getBody().unmask();
		}).fail(function(jqXHR, textStatus){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Could not connect with server. Please refresh browser and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			Ext.getBody().unmask();
		});
	}
	<?php } ?>

	$(document).ready(function(){
		readLog();
	});
</script>