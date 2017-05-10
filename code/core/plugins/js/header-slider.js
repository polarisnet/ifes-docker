var quickSlider = false;
$(document).ready(function(){
	$('#bar-settings, #bar-settings-toggle').click(function(e){
		var id = $(this).attr('id');
		if(!quickSlider){
			$('#bar-settings-toggle').fadeIn('fast', function(){
				quickSlider = true;
			}); 
		}
		if(id == 'bar-settings-toggle'){
			e.stopPropagation();
		}
	});
	$(document).click(function(){
		if(quickSlider){
			$('#bar-settings-toggle').fadeOut('fast', function(){
				quickSlider = false;
			}); 
		}
	});
});