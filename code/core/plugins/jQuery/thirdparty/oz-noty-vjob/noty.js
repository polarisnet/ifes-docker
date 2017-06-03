(function($){
	$.fn.oznotyclose = function(container, mode, wrapperWidth){
		obj = $('#'+container);
		if(mode == 'right'){
			obj.animate({
				right: '-'+wrapperWidth+'px'
			}, 1000, function(){
				obj.remove();
			});
		}else{
			obj.animate({
				left: '-'+wrapperWidth+'px'
			}, 1000, function(){
				obj.remove();
			});
		}
	}

	function checkNotyELExist(mode){
		var output = 1;
		var search = "";
		var base = "";
		if(mode == 'right'){
			search = "oz-noty-right[]";
			base = "noty-right-";
		}else{
			search = "oz-noty-left[]";
			base = "noty-left-";
		}
		var group = $('div[name="'+search+'"]');
		for(var i=1; i<=group.length; i++){
			if($('#'+base+i).length == 1){
				output++;
			}else{
				break;
			}
		}
		return output;
	}
	
	function calculateTopPosition(mode, flag, padding){
		var output = 0;
		var search = "";
		var el = "";
		if(mode == 'right'){
			search = "noty-right-";
		}else{
			search = "noty-left-";
		}
		for(var i=0; i<flag; i++){
			el = $('#'+search+i);
			if(el.length != 0){
				output += el.height();
			}
			output += padding;
		}
		return output;
	}
	
	$.fn.oznoty = function(opt){
		var obj = this;
		
		var winHeight = $(window).height();
		var winWidth = $(window).height();
		var total = opt.length;
		
		var padding = 20+15+15;
		var defaultWidth = 300+15+15; //width+padding+padding
		var defaultHeight = 80;
		
		for(i=0; i<total; i++){
			var temp = opt[i];
			var tempId = "";
			var newEl = "";
			var newClass = "oz-noty-wrapper";
			var newStyle = "";
			var newName = "";
			var newCallBack = "";
			
			if(temp['type'] == 'error'){
				newClass += " oz-noty-error";
			}else if(temp['type'] == 'message'){
				newClass += " oz-noty-message";
			}else if(temp['type'] == 'warning'){
				newClass += " oz-noty-warning";
			}else{
				newClass += " oz-noty-standard";
			}
			
			if(temp['background'] !== undefined){
				newStyle += " background-color: "+temp['background']+";";
			}
			tempWidth = defaultWidth;
			if(temp['width'] !== undefined){
				tempWidth = temp['width']+15+15;
				newStyle += " width: "+temp['width']+"px;";
			}
			tempHeight = defaultHeight;
			if(temp['height'] !== undefined){
				tempHeight = temp['height'];
			}
			
			switch(temp['position']){
				case 'right':
					el = checkNotyELExist('right');
					newName = "oz-noty-right[]";
					tempId = "noty-right-"+el;
					topPosition = calculateTopPosition('right', el, padding);
					newStyle += " top: "+topPosition+"px; right: -"+tempWidth+"px;";
					if(temp['autoclose'] === undefined || temp['autoclose'] == false){
						newCallBack +="<div class=\"oz-noty-close\" style=\"float: right;\" onclick=\"javascript: $(this).oznotyclose('"+tempId+"', 'right', '"+tempWidth+"');\"></div>"
					} else if(temp['autoclose'] !== undefined && temp['autoclose'] == true){
						newCallBack +="<div class=\"oz-noty-close\" style=\"float: right;\" onclick=\"javascript: $('#"+tempId+"').dequeue(); $(this).oznotyclose('"+tempId+"', 'right', '"+tempWidth+"');\"></div>"
					}
				break;
				case 'left':
					el = checkNotyELExist('left');
					newName = "oz-noty-left[]";
					tempId = "noty-left-"+el;
					topPosition = calculateTopPosition('left', el, padding);
					newStyle += " top: "+topPosition+"px; left: -"+tempWidth+"px; text-align: right;";
					if(temp['autoclose'] === undefined || temp['autoclose'] == false){
						newCallBack +="<div class=\"oz-noty-close\" style=\"float: left;\" onclick=\"javascript: $(this).oznotyclose('"+tempId+"', 'left', '"+tempWidth+"');\"></div>"
					} else if(temp['autoclose'] !== undefined && temp['autoclose'] == true){
						newCallBack +="<div class=\"oz-noty-close\" style=\"float: left;\" onclick=\"javascript: $('#"+tempId+"').dequeue(); $(this).oznotyclose('"+tempId+"', 'left', '"+tempWidth+"');\"></div>"
					}
				break;
			}
			
			newEl += "<div id=\""+tempId+"\" name=\""+newName+"\" class=\""+newClass+"\" style=\""+newStyle+"\">";
			//if(temp['autoclose'] === undefined || temp['autoclose'] == false){
				newEl += newCallBack;
			//}
			newEl += "<div class=\"oz-noty-title\">"+temp['title']+"</div>";
			newEl += "<div class=\"oz-noty-content\">"+temp['content']+"</div>";
			newEl += "</div>";
			obj.append(newEl);
			$('#'+tempId).delay(1000*i);
			switch(temp['position']){
				case 'right':
					$('#'+tempId).animate({
						'right': '0',
					}, 1000);
					if(temp['autoclose'] !== undefined && temp['autoclose'] == true){
						$('#'+tempId).queue(function(){
							var wrapperWidth = $(this).width()+15+15;
							$(this).delay(5000).animate({
								right: '-'+wrapperWidth+'px'
							}, 1000, function(){
								$(this).remove();
							});
							$(this).dequeue();
						});
					}
				break;
				case 'left':
					$('#'+tempId).animate({
						'left': '0',
					}, 1000);
					if(temp['autoclose'] !== undefined && temp['autoclose'] == true){
						$('#'+tempId).queue(function(){
							var wrapperWidth = $(this).width()+15+15;
							$(this).delay(5000).animate({
								left: '-'+wrapperWidth+'px'
							}, 1000, function(){
								$(this).remove();
							});
							$(this).dequeue();
						});
					}
				break;
			}
		}
	}
})(jQuery);