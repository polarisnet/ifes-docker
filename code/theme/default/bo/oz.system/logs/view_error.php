<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<table style="position: relative;">
		<tr>
			<td class="lbl-title" colspan="2">Error Logs</td>
		</tr>
		<tr>
			<td colspan="2">
				<br>
				<div style="text-align: center;">
					<select id="error-select" onchange="javascript: readLog(this.value);">
						<option value="">Please select any error logs...</option>
						<?php echo listErrorLogHTML(); ?>
					</select>
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
				<textarea id="error-content" style="width: 99%; height: 300px;  resize: none;" readonly></textarea>
				<br><br>
				<div style="text-align: center;">
					<input type="button" id="btn-delete" style="display: none;" value="Delete Selected Logs" class="flat-button-default" rel="tooltip" data-original-title="Delete Selected Logs" data-placement="top" onclick="deleteLog();">
					<input type="button" value="Clear All Error Logs" class="flat-button-default" rel="tooltip" data-original-title="Clear All Error Logs" data-placement="top" onclick="deleteLog();">
				</div>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	function readLog(file){
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
				document.getElementById('error-content').innerHTML = msg.log;
				if(file != ''){
					$('#btn-delete').show();
				}else{
					$('#btn-delete').hide();
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
	}
	
	function deleteLog(){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete all error logs?', function(btn){
			if(btn == 'yes'){
			}
		});
	}
</script>