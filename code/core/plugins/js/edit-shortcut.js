$(document).keydown(function(e){
	if(e.ctrlKey && e.keyCode == 46){
		if($('#btn-delete').length != 0){
			$('#btn-delete').trigger('click');
			return;
		}
	}
	if(e.ctrlKey && e.keyCode == 83){
		if($('#cancel-toolbar').length != 0 && $('#cancel-toolbar').is(':visible')){
			submitForm('');
			e.preventDefault(e);
			return;
		}
	}
	if(e.ctrlKey && e.keyCode == 69){
		if($('#cancel-toolbar').length != 0){
			toggleEditForm();
			e.preventDefault(e);
			return;
		}
	}
	if(e.ctrlKey && e.keyCode == 8){		
		e.preventDefault(e);
		return;
	}
});