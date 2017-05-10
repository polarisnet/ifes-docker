$(document).keydown(function(e){
	if(e.ctrlKey && e.keyCode == 83){
		if(e.shiftKey){
			submitForm('new');
		}else{
			submitForm('');
		}
		e.preventDefault(e);
		return;
	}
	if(e.ctrlKey && e.keyCode == 8){		
		e.preventDefault(e);
		return;
	}
});