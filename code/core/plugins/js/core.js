function getDocHeight(){
	var D = document;
	return Math.max(
		Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
		Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
		Math.max(D.body.clientHeight, D.documentElement.clientHeight)
	);
}

function setCookie(name, value, days){
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + days);
	var c_value = escape(value) + ((days == null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie = SITE_NAME+'_SESSION['+name+"]="+value+"; path=/";
}

function adjustLeftBar(optimalHeight){
	$('#leftbar-content').height(optimalHeight - $('#leftbar-toggle-container').height() - 16);
}

function adjustContent(optimalHeightCenter){
	if($('#site-left').length != 0){
		$('#center-content').width($(window).width() - $('#site-left').width());
	}
	$('#center-content').height(optimalHeightCenter);
}

function autoAdjustLogin(mode){
	if(mode == 'login'){
                optimalHeight = $(window).height() - footerHeight-10;
		//optimalHeight -= 1;
//                if(optimalHeight > 682) {
//                    optimalHeight = 682;
//                }
                $('#login-content').height(optimalHeight);
                
//                if($(window).width() > 1100) {
//                    $('#login-form').width($('#login-pic').width()-15);
//                }else {
//                    $('#login-form').width($('#login-logo'));
//                }
		
	}
}

function autoAdjust(mode){
	if(mode == 'bo'){
                if(_visible) {
                    optimalHeight = $(window).height() - headerHeight;
                }else {
                    optimalHeight = $(window).height();
                }
		optimalHeight -= 1;
                if($('#site-content-wrapper').width() <= 480 ) {
                    if(maximizeLeftBar && (initialLoadBar == '1')) {
                        toggleLeftBar();
                    }
                }
                  
		optimalHeightCenter = optimalHeight - footerHeight - 6;
		$('#site-center-content').height(optimalHeightCenter);
		adjustContent(optimalHeightCenter);
		if($('#site-left').length != 0){
			adjustLeftBar(optimalHeight);
		}
		if("wsChat" in window){
			if($('#ws-chat-box').is(':visible')){
				var target = document.getElementById('ws-chat-uid').value;
				if(target == 'broadcast'){
					var objPosition = $('#ws-whois-online').position();
					$('#ws-chat-box').css('top', objPosition.top-15);
				}else{
					objPosition = $('#ws-tmp-'+target).position();
					$('#ws-chat-box').css('top', objPosition.top-15);
				}
			}
		}
	}else{
		optimalHeight = $(window).height() - headerHeight - footerHeight;
		$('#site-content-wrapper').height(optimalHeight);
	}
}

function toggleLeftBar(){
    if(initialLoadBar != '0') {
        setCookie('toggle_leftbar_initial', '0', 5);
        initialLoadBar = '0';
    }
    
    
	if(maximizeLeftBar){
		//$('#site-left').width(12);
                //$('#fullbar').hide();
                    //
                $('#fullbar').animate({
                    left: '-'+oriLeftBarWidth+'px',
                }, 500, function() {
                    $('#fullbar').hide();
                    $('#site-left').animate({
                    width: '12px',
                }, 250, function() {
                    $(window).trigger('resize');
                    $('#lessbar').show();
                    $('#lessbar').animate({
                        left: '0px'
                    }, 500, function() {
                        resizeGrid();
                    });
                });
                });
                  
                
//		$('#leftbar-content').hide();
//		$('#input_left_search').hide();
//		$('#sidebar-quick-search-icon').hide();
                
		setCookie('toggle_leftbar', '0', 5);
		if("wsChat" in window){
			$('#ws-chat-box').css('left', 58);
		}
	}else{
//		$('#leftbar-content').show();
//		$('#input_left_search').show();

                
                
		//$('#site-left').width(oriLeftBarWidth);
                $('#fullbar').show();
                $('#lessbar').animate({
                        left: '-12px'
                    }, 300, function() {
                        $('#lessbar').hide();
                $('#site-left').animate({
                    width: oriLeftBarWidth+'px'
                }, 150, function() {
                    
                  });
                $('#fullbar').animate({
                    left: '0px',
                }, 300, function() {
                    resizeGrid();
                  });
                });
                
                
		setCookie('toggle_leftbar', '1', 5);
		if("wsChat" in window){
			$('#ws-chat-box').css('left', oriLeftBarWidth+15);
		}
	}
	$('#center-content').width($(window).width() - $('#site-left').width());
	maximizeLeftBar = !maximizeLeftBar;
	toggleLeftBarImg(false);
	
}

function resizeGrid() {
    if($('#ext-container').length != 0){
            Ext.getCmp('ext-container').doLayout();
    }
    if($('#ext-container-1').length != 0){
            Ext.getCmp('ext-container-1').doLayout();
    }
    if($('#ext-container-2').length != 0){
            Ext.getCmp('ext-container-2').doLayout();
    }
    if($('#ext-container-3').length != 0){
            Ext.getCmp('ext-container-3').doLayout();
    }
    if($('#ext-DashboardActivities-container').length != 0){
            Ext.getCmp('ext-DashboardActivities-container').doLayout();
    }
    if($('#ext-container-ship-to').length != 0){
            Ext.getCmp('ext-container-ship-to').doLayout();
    }
    if($('#ext-container-relatedcustomers').length != 0){
            Ext.getCmp('ext-container-relatedcustomers').doLayout();
    }
    if($('#ext-container-sales-person').length != 0){
            Ext.getCmp('ext-container-sales-person').doLayout();
    }
    if($('#ext-container-relatedcontacts').length != 0){
            Ext.getCmp('ext-container-relatedcontacts').doLayout();
    }
    if($('#ext-container-relatedprojects').length != 0){
            Ext.getCmp('ext-container-relatedprojects').doLayout();
    }
    if($('#ext-container-relateditems').length != 0){
            Ext.getCmp('ext-container-relateditems').doLayout();
    }
}

function toggleLeftBarImg(hover){
	var imgURL = HTTP_MEDIA+'/site-image';
	if(maximizeLeftBar){
		if(hover){
			$('#leftbar-toggle-img').attr("src", imgURL + '/back_white.png');
                        $('#leftbar-toggle-img-hover').attr("src", imgURL + '/back_white.png');
                        $('#leftbar-toggle-img-navbar').attr("src", imgURL + '/back.png');
		}else{
			$('#leftbar-toggle-img').attr("src", imgURL + '/back.png');
                        $('#leftbar-toggle-img-hover').attr("src", imgURL + '/back.png');
                        $('#leftbar-toggle-img-navbar').attr("src", imgURL + '/back_white.png');
		}
	}else{
		if(hover){
			$('#leftbar-toggle-img').attr("src", imgURL + '/next_white.png');
                        $('#leftbar-toggle-img-hover').attr("src", imgURL + '/next_white.png');
                        $('#leftbar-toggle-img-navbar').attr("src", imgURL + '/next.png');
		}else{
			$('#leftbar-toggle-img').attr("src", imgURL + '/next.png');
                        $('#leftbar-toggle-img-hover').attr("src", imgURL + '/next.png');
                        $('#leftbar-toggle-img-navbar').attr("src", imgURL + '/next_white.png');
		}
	}
}

(function($) {
    $.fn.hasScrollBar = function() {
        return this.get(0).scrollHeight > this.height();
    }
})(jQuery);

function flipMe(obj, target){
	$(obj).flippy({
		color_target: "#0160ab",
		duration: "350",
		direction: "TOP",
		verso: "<div class='tilepic'><img class='mainimg' src='"+HTTP_MEDIA+"/site-image/link.png'><div class='tile-title'>Redirecting...<div></div>",
		noCSS: true,
		onFinish: function(){
			setTimeout(function(){window.location = target;}, 300);
		}
	});
}

function onMarkError(group){
	for(var i=0; i<group.length; i++){
		$('#'+group[i]).css({
			'border': '1px solid red'
		});
		if(i == 0){
			$('#'+group[i]).focus();
		}
	}
}

function fullPageScroll(){
	var win = $(window);
	var isResizing = false;
	// Full body scroll
	win.bind(
		'resize',
		function(){
			if (!isResizing){
				isResizing = true;
				var container = $('#full-page-container');
				// Temporarily make the container tiny so it doesn't influence the
				// calculation of the size of the document
				container.css({
					'width': 1,
					'height': 1
				});
				// Now make it the size of the window...
				container.css({
					'width': win.width(),
					'height': win.height()
				});
				$('#full-page-container .jspPane').css('left', '0');
				isResizing = false;
				container.jScrollPane({
					'showArrows': true
				});
			}
		}
	).trigger('resize');

	// Workaround for known Opera issue which breaks demo (see
	// http://jscrollpane.kelvinluck.com/known_issues.html#opera-scrollbar )
	$('body').css('overflow', 'hidden');

	// IE calculates the width incorrectly first time round (it
	// doesn't count the space used by the native scrollbar) so
	// we re-trigger if necessary.
	if ($('#full-page-container').width() != win.width()){
		win.trigger('resize');
	}
}

function calculateComplexity(obj){
	$('#password').complexify({
			minimumChars: 5,
			strengthScaleFactor: 0.85
		},function(valid, complexity){
			if(complexity < 30){
				$('#password-bar').css('background','#eb330f');
			}else if(complexity >= 30 && complexity < 80){
				$('#password-bar').css('background','#ffd800');
			}else{
				$('#password-bar').css('background','#72d706');
			}
			var containerWidth = $('#password-inner').width();
			$('#password-bar').width(complexity*containerWidth/100);
		}
	);
}

function toggleSecQuestion(value){
	if(value == '0'){
		$('#sec_question2').width($('#sec_question1').width());
		$('#sec_question2').show();
		$('#sec_question2').focus();
	}else{
		$('#sec_question2').hide();
	}
}

function ucFirst(string){
	string = $.trim(string);
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function toggleEditForm(){
	var mode = document.getElementById('mode').value;
	if(mode == 'view'){
		$('#view-table, #btn-edit, #btn-edit1').hide();
		$('#edit-table, #cancel-toolbar, #cancel-toolbar1').show();
		document.getElementById('mode').value = 'edit';
	}else{
		$('#view-table, #btn-edit, #btn-edit1').show();
		$('#edit-table, #cancel-toolbar, #cancel-toolbar1').hide();
		document.getElementById('mode').value = 'view';
	}
}

//function animateLabel(id) {
//    $("#"+id).addClass("animated");
//    $("#"+id).addClass("fadeIn");
//
//    var animationEnd = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
//    $("#"+id).one(animationEnd, function() {
//        $("#"+id).removeClass("animated");
//        $("#"+id).removeClass("fadeIn");
//        if(DISCOUNTS_AVAILABLE) {
//            animateLabel(id);
//        }
//    });
//}

function blinkElement(id) {
    var x = 0;
    var intervalID = setInterval(function () {

       $( "#"+id ).fadeToggle( 600, function() {
    $( "#"+id ).fadeToggle();
  });

       if (++x === 6) {
           window.clearInterval(intervalID);
           
       }
    }, 400);
}


function toggleDiscount(show) {
    if(show) {
        Ext.getCmp('panel_btn_details_discounts').show();
        Ext.getCmp('discounts_label').show();
        blinkElement('discount_alert');
//        DISCOUNTS_AVAILABLE = true;
//        animateLabel('discounts_label-inputEl');
        
    }else {
        Ext.getCmp('panel_btn_details_discounts').hide();
        Ext.getCmp('discounts_label').hide();
//        DISCOUNTS_AVAILABLE = false;
    }
}

function toggleItemBody(){
	if(WAWA == false){
                WAWA = true;
                Ext.getCmp('details_grid').getPlugin('bodyroweditor').cancelEdit();
                Ext.getCmp('panel_btn_details_add').disable();
                Ext.getCmp('panel_btn_details_add_row').disable();
                Ext.getCmp('panel_btn_details_delete').disable();
                Ext.getCmp('panel_btn_details_discounts').disable();
                
                Ext.getCmp('footer_grid').getPlugin('footerroweditor').cancelEdit();
                Ext.getCmp('panel_btn_footer_add').disable();
                Ext.getCmp('panel_btn_footer_add_row').disable();
                Ext.getCmp('panel_btn_footer_delete').disable();
                
	}else{
		WAWA = false;
                Ext.getCmp('panel_btn_details_add').enable();
                Ext.getCmp('panel_btn_details_add_row').enable();
                var sm = Ext.getCmp('details_grid').getSelectionModel();
                if(sm.getCount() >= 1){
                    Ext.getCmp('panel_btn_details_delete').enable();
                }
                Ext.getCmp('panel_btn_details_discounts').enable();
                
                Ext.getCmp('panel_btn_footer_add').enable();
                Ext.getCmp('panel_btn_footer_add_row').enable();
                var sm = Ext.getCmp('footer_grid').getSelectionModel();
                if(sm.getCount() >= 1){
                    Ext.getCmp('panel_btn_footer_delete').enable();
                }
	}
}

function toggleItemBody2(){
	if(WAWA == false){
                WAWA = true;
                Ext.getCmp('details_grid').getPlugin('bodyroweditor').cancelEdit();
                Ext.getCmp('panel_btn_details_add').disable();
                Ext.getCmp('panel_btn_details_add_row').disable();
                Ext.getCmp('panel_btn_details_delete').disable();
	}else{
		WAWA = false;
                Ext.getCmp('panel_btn_details_add').enable();
                Ext.getCmp('panel_btn_details_add_row').enable();
                var sm = Ext.getCmp('details_grid').getSelectionModel();
                if(sm.getCount() >= 1){
                    Ext.getCmp('panel_btn_details_delete').enable();
                }
	}
}

function itemBodyEdited(){
    if(BODYEDITED == false){
        $('#body_edited').html('You have edited the body of the SO, please save the form for the changes to take effect.');
	}
    BODYEDITED = true;
}

function itemBodyEdited2(){
    if(BODYEDITED == false){
        $('#body_edited').html('You have edited the product bundle items, please save the form for the changes to take effect.');
	}
    BODYEDITED = true;
}

function itemBodyEdited3(){
    if(BODYEDITED == false){
        $('#body_edited').html('You have edited the catalogue items, please save the form for the changes to take effect.');
	}
    BODYEDITED = true;
}

function itemFooterEdited(){
        if(FOOTEREDITED == false){
                $('#footer_edited').html('You have edited the footer of the SO, please save the form for the changes to take effect.');
	}
        FOOTEREDITED = true;
}

/** Validation - Start **/
function clearValidation(form){
	$("#"+form+" input:not([id$=-inputEl]):not(:button):not(:file) , #"+form+" textarea").css({'border': '1px solid #bfbfbf'});
}

function validateEmpty(obj, field){
	var value = $.trim(obj.val());
	if(value == ''){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': ucFirst(field)+' cannot be empty.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return false;
	}else{
		return true;
	}
}

function validateExtDate(cmp, field){
	 var verify = $.trim(Ext.getCmp(cmp).getValue());
	 var value = $.trim(Ext.getCmp(cmp).getSubmitValue());
	 var regex = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
	 if(verify != '' || verify == null){
		 if(!regex.test(value)){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should in date format. Example: "dd/mm/yyyy".',
				'position': 'right',
				'autoclose': true
			}]);
			$('#'+cmp).css({'border': '1px solid red'});
			$('#'+cmp).focus();
			return false;
		}else{
			return true;
		}
	 } else{
		return true;
	}
}

function validateEmail(obj, field){ 
	var value = $.trim(obj.val());
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!regex.test(value)){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid email address. Please input correct email address.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return false;
	}else{
		return true;
	}
}

function validatePhone(obj, field){
	var value = $.trim(obj.val());	
	var regex = /^\(?\+?([0-9]{1,5})\)?[-. ]?([0-9]{1,26})[-. ]?([0-9,/]{1,26})$/;
	if(!regex.test(value) && field == 'mobile'){ //only validate mobile phone numbers.
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. Please input correct '+ucFirst(field)+'. Example: "(xxx)xxxxxxx or xxxxxxxxxx" ',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return;
	}else{
		return true;
	}
}

function validateCode(obj, field){
	return true;
	var value = $.trim(obj.val());
	var regex = /^[-A-Z0-9\.:,_ ]*$/i;
	if(!regex.test(value)){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should not contain special characters.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return false;
	}else{
		return true;
	}
}

function validateExtJsPhone(obj){
	var value = obj;
	var regex = /^\(?\+?([0-9]{1,5})\)?[-. ]?([0-9]{1,26})[-. ]?([0-9]{1,26})$/;
	if(!regex.test(value)){		
		return;
	}else{
		return true;
	}
}

function validateNumber(obj, field){ //only integer
	var value = $.trim(obj.val());
	var regex = /^[0-9]*$/;
	if(!regex.test(value)){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should be a numeric value.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return;
	}
	return true;
}

function validateAllDecimal(obj, field){ //include positive decimal
	var value = $.trim(obj.val());
	var regex = /^[-+]?[0-9]*\.?[0-9]+$/;
	if(!regex.test(value)){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should be a numeric value.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return;
	}
	return true;
}

function validateDecimal(obj, field){ //not include positive number
	var value = $.trim(obj.val());
	var regex = /^-?\d+(?:\.\d+)?$/;
	if(!regex.test(value)){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should be a numeric value.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return;
	}
	return true;
}

function validatePercent(obj, field){
	var value = $.trim(obj.val());
	if(value > 100 || value > 100.00){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should not be more than 100%.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return;
	}
	return true;
}

function validateZero(obj, field){
	var value = $.trim(obj.val());
	if(value == 0 || value == 0.00){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should not be 0.',
			'position': 'right',
			'autoclose': true
		}]);
		obj.css({'border': '1px solid red'});
		obj.focus();
		return;
	}
	return true;
}

function validateMatch(obj1, obj2, field1, field2){
	if(obj1.val() != obj2.val()){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': ucFirst(field1)+' does not match with '+field2.toLowerCase()+'. Please input the same values for both fields.',
			'position': 'right',
			'autoclose': true
		}]);
		obj1.css({'border': '1px solid red'});
		obj2.css({'border': '1px solid red'});
		return false;
	}else{
		obj1.css({'border': '1px solid #bfbfbf'});
		obj2.css({'border': '1px solid #bfbfbf'});
		return true;
	}
}

function validateExtEmpty(cmp, field){
	var value = $.trim(Ext.getCmp(cmp).getValue());
	if(value == ''){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': ucFirst(field)+' cannot be empty.',
			'position': 'right',
			'autoclose': true
		}]);
		$('#'+cmp).css({'border': '1px solid red'});
		$('#'+cmp).focus();
		return false;
	}else{
		return true;
	}
}

function validateExtDecimal(cmp, field){
	var value = $.trim(Ext.getCmp(cmp).getValue());
	var regex = /^\d+(?:\.\d+)?$/;
	if(!regex.test(value)){
		$('#oz-noty').oznoty([{
			'type': 'error',
			'title': 'Error',
			'content': 'Invalid '+ucFirst(field)+'. '+ucFirst(field)+' should not be decimal.',
			'position': 'right',
			'autoclose': true
		}]);
		$('#'+cmp).css({'border': '1px solid red'});
		$('#'+cmp).focus();
		return;
	}
	return true;
}

function checkUsernameExist(obj, field, validator){
	if(obj.val() != ''){
		$('#loader_username').show();
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: 'check_duplicate_user',
				username: obj.val()
			}
		}).done(function(msg){
			if(msg.success){
				$('#loader_username').hide();
				obj.css({'border': '1px solid #bfbfbf'});
				dynValidator[validator] = true;
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': msg.message,
					'position': 'right',
					'autoclose': true
				}]);
				obj.css({'border': '1px solid red'});
				$('#loader_username').hide();
				dynValidator[validator] = false;
			}
		}).fail(function(jqXHR, textStatus){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Could not connect with server. Please refresh browser and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			obj.css({'border': '1px solid red'});
			$('#loader_username').hide();
			dynValidator[validator] = false;
		});
	}
}

function checkFieldExist(obj, id, validator, loader, opt, hideError, ErrorOrWarning){
	if(obj.val() != ''){
		loader.show();
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: opt,
				val: obj.val(),
				id: id
			}
		}).done(function(msg){
			if(msg.success){
				loader.hide();
				obj.css({'border': '1px solid #bfbfbf'});
				dynValidator[validator] = true;
			}else{
				$('#oz-noty').oznoty([{
					'type': (ErrorOrWarning=='warning'?'warning':'error'),
					'title': (ErrorOrWarning=='warning'?'Warning':'Error'),
					'content': msg.message,
					'position': 'right',
					'autoclose': true
				}]);
				if(ErrorOrWarning=='warning') {
					obj.css({'border': '1px solid #808080'});
				} else {
					obj.css({'border': '1px solid red'});
				}
				loader.hide();
				dynValidator[validator] = false;
			}
		}).fail(function(jqXHR, textStatus){
			if(!hideError){
				$('#oz-noty').oznoty([{
					'type': (ErrorOrWarning=='warning'?'warning':'error'),
					'title': (ErrorOrWarning=='warning'?'Warning':'Error'),
					'content': 'Could not connect with server. Please refresh browser and try again.',
					'position': 'right',
					'autoclose': true
				}]);
				if(ErrorOrWarning=='warning') {
					obj.css({'border': '1px solid #808080'});
				} else {
					obj.css({'border': '1px solid red'});
				}
			}
			loader.hide();
			dynValidator[validator] = false;
		});
	}
}

function checkFieldExistWithCombo(obj, obj2, id, validator, loader, opt, hideError){
	var value = $.trim(document.getElementsByName(obj2)[0].value);
	if(obj.val() != ''){
		loader.show();
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: opt,
				val: obj.val(),
				val2: value,
				id: id
			}
		}).done(function(msg){
			if(msg.success){
				loader.hide();
				obj.css({'border': '1px solid #bfbfbf'});
				dynValidator[validator] = true;
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': msg.message,
					'position': 'right',
					'autoclose': true
				}]);
				obj.css({'border': '1px solid red'});
				loader.hide();
				dynValidator[validator] = false;
			}
		}).fail(function(jqXHR, textStatus){
			if(!hideError){
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': 'Could not connect with server. Please refresh browser and try again.',
					'position': 'right',
					'autoclose': true
				}]);
				obj.css({'border': '1px solid red'});
			}
			loader.hide();
			dynValidator[validator] = false;
		});
	}
}

function checkFieldExistMulti(obj, multi, id, validator, loader, opt, hideError){
	if(obj.val() != ''){
		loader.show();
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: opt,
				val: obj.val(),
				multi: multi,
				id: id
			}
		}).done(function(msg){
			if(msg.success){
				loader.hide();
				obj.css({'border': '1px solid #bfbfbf'});
				dynValidator[validator] = true;
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': msg.message,
					'position': 'right',
					'autoclose': true
				}]);
				obj.css({'border': '1px solid red'});
				loader.hide();
				dynValidator[validator] = false;
			}
		}).fail(function(jqXHR, textStatus){
			if(!hideError){
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': 'Could not connect with server. Please refresh browser and try again.',
					'position': 'right',
					'autoclose': true
				}]);
				obj.css({'border': '1px solid red'});
			}
			loader.hide();
			dynValidator[validator] = false;
		});
	}
}

function checkEmailExist(obj, field, id, validator){
	if(obj.val() != ''){
		$('#loader_email').show();
		var request = $.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: 'check_duplicate_email',
				email: obj.val(),
				user_id: id
			}
		}).done(function(msg){
			if(msg.success){
				$('#loader_email').hide();
				obj.css({'border': '1px solid #bfbfbf'});
				dynValidator[validator] = true;
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': msg.message,
					'position': 'right',
					'autoclose': true
				}]);
				obj.css({'border': '1px solid red'});
				$('#loader_email').hide();
				dynValidator[validator] = false;
			}
		}).fail(function(jqXHR, textStatus){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Could not connect with server. Please refresh browser and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			obj.css({'border': '1px solid red'});
			$('#loader_email').hide();
			dynValidator[validator] = false;
		});
	}
}
/** Validation - End **/
function inArray(value, arrayVal) {
    var length = arrayVal.length;
    for (var i = 0; i < length; i++) {
        if (arrayVal[i] == value) return true;
    }
    return false;
}

function innerGridToggle(me, container, collapse){
	if(Ext.getCmp(container).isHidden()){
		document.getElementById(collapse).innerHTML= "[-]";
		Ext.getCmp(container).show();
	}else{
		document.getElementById(collapse).innerHTML= "[+]";
		Ext.getCmp(container).hide();
	}
}

function innerViewToggle(me, container, collapse){
	if($("#" + container).is(":hidden")){
		document.getElementById(collapse).innerHTML= "[-]";
		$("#" + container).show();
	}else{
		document.getElementById(collapse).innerHTML= "[+]";
		$("#" + container).hide();
	}
}

function changeShareOption(val) {
	document.getElementById("option1_id").style.display = "none";
	
	document.getElementById("option2_id").style.display = "none";
	document.getElementById("option2table_id").style.display = "none";
	
	document.getElementById("option3_id").style.display = "none";
	document.getElementById("option3table_id").style.display = "none";
	
	//document.getElementById("option4_id").style.display = "none";
	document.getElementById("option4table_id").style.display = "none";
	if(val=="1") {
		document.getElementById("option1_id").style.display = "";
	} else if(val=="2") {
		document.getElementById("option2_id").style.display = "";
		document.getElementById("option2table_id").style.display = "";
	} else if(val=="3") {
		document.getElementById("option3_id").style.display = "";
		document.getElementById("option3table_id").style.display = "";
	} else if(val=="4") {
		//document.getElementById("option4_id").style.display = "";
		document.getElementById("option4table_id").style.display = "";
	} 
}
function makeUniqueLink(num, to) {
	var text = "";
	var possible = "abcdefghijklmnopqrstuvwxyz0123456789";
	for( var i=0; i < num; i++ )
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	document.getElementById(to).value = text;
	if(document.getElementById("uniqueurl_realtime")) {
		document.getElementById("uniqueurl_realtime").innerHTML = text;
	}
	//return text;
}

function readMyMoney(num){
		var text = "";
		var sepDecimal = num.split(".");
		var sepComma = sepDecimal[0].split(",");
		var totalComma = sepComma.length;
		var position = totalComma;
		var flag = 0;
		while(flag < totalComma){
			if(sepComma[flag] > 0){
				switch(position){
					case 1: text += convertNum2Words(sepComma[flag]); break;
					case 2: text += convertNum2Words(sepComma[flag])+' thousand '; break;
					case 3: text += convertNum2Words(sepComma[flag])+' million '; break;
					case 4: text += convertNum2Words(sepComma[flag])+' billion '; break;
					case 5: text += convertNum2Words(sepComma[flag])+' trillion '; break;
					case 6: text += convertNum2Words(sepComma[flag])+' quadrillion '; break;
					case 7: text += convertNum2Words(sepComma[flag])+' quintrillion '; break;
					case 8: text += convertNum2Words(sepComma[flag])+' sextrillion '; break;
					case 9: text += convertNum2Words(sepComma[flag])+' septrillion '; break;
				}
			}
			flag ++;
			position --;
		}
		
		if(typeof sepDecimal[1] != 'undefined'){
			var temp = convertNum2Words(sepDecimal[1]);
			if(temp != ""){
				text += ' and '+temp+ ' only';
			}
		}
		return text;
	}
	
	function convertNum2Words(num){
                var length = num.length;
		var text = "";
		if(length == 1){
			text += convertSingleNum2Words(num);
		}else if(length == 2){
			text += convertDoubleNum2Words(num);
		}else if(length == 3){
			text += convertTripleNum2Words(num);
		}
		return text;
	}
	
	function convertTripleNum2Words(num){
		var num1 = num.substr(0,1);
		var num2 = num.substr(-2);
		return convertSingleNum2Words(num1)+ ' hundred '+convertDoubleNum2Words(num2);
	}
	
	function convertDoubleNum2Words(num){
		var num1 = num.substr(0,1);
		var num2 = num.substr(-1);
		switch(num1){
			case '1': 
				switch(num2){
					case '0': return 'ten';
					case '1': return 'eleven';
					case '2': return 'twelve';
					case '3': return 'thirteen';
					case '4': return 'fourteen';
					case '5': return 'fifteen';
					case '6': return 'sixteen';
					case '7': return 'seventeen';
					case '8': return 'eighteen';
					case '9': return 'nineteen';
				}
			break;
			case '2': return "twenty "+convertSingleNum2Words(num2);
			case '3': return "thirty "+convertSingleNum2Words(num2);
			case '4': return "forty "+convertSingleNum2Words(num2);
			case '5': return "fifty "+convertSingleNum2Words(num2);
			case '6': return "sixty "+convertSingleNum2Words(num2);
			case '7': return "seventy "+convertSingleNum2Words(num2);
			case '8': return "eighty "+convertSingleNum2Words(num2);
			case '9': return "ninety "+convertSingleNum2Words(num2);
			case '0': return convertSingleNum2Words(num2);
		}
	}
	
	function convertSingleNum2Words(num){
		switch(num){
			case '1': return 'one';
			case '2': return 'two';
			case '3': return 'three';
			case '4': return 'four';
			case '5': return 'five';
			case '6': return 'six';
			case '7': return 'seven';
			case '8': return 'eight';
			case '9': return 'nine';
			case '0': return '';
		}
	}
        
        function addCommas(nStr)
        {
                nStr += '';
                x = nStr.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2;
        }
		
		
function jsCheckFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}

function number_format(number, decimals, dec_point, thousands_sep){
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function(n, prec){
		var k = Math.pow(10, prec);
		return '' + (Math.round(n * k) / k).toFixed(prec);
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if(s[0].length > 3){
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if((s[1] || '').length < prec){
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}