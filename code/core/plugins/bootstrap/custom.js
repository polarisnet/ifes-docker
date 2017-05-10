// JavaScript Document


$(function() {
    FastClick.attach(document.body);
});


// Only enable if the document has a long scroll bar
// Note the window height + offset
if ( ($(window).height() + 100) < $(document).height() ) {
    $('#top-link-block').removeClass('hidden').affix({
        // how far to scroll down before link "slides" into view
        offset: {top:100}
    });
}


$(document).on('click', '.panel-heading span.clickable', function (e) {
    var $this = $(this);
    if (!$this.hasClass('panel-collapsed')) {
        $this.parents('.panel').find('.panel-body').slideUp();
        $this.addClass('panel-collapsed');
        $this.find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
    } else {
        $this.parents('.panel').find('.panel-body').slideDown();
        $this.removeClass('panel-collapsed');
        $this.find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
    }
});
$(document).on('click', '.panel div.clickable', function (e) {
    var $this = $(this);
    if (!$this.hasClass('panel-collapsed')) {
        $this.parents('.panel').find('.panel-body').slideUp();
        $this.addClass('panel-collapsed');
        $this.find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
    } else {
        $this.parents('.panel').find('.panel-body').slideDown();
        $this.removeClass('panel-collapsed');
        $this.find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
    }
});
$(document).ready(function () {
    $('.panel-heading span.clickable').click();
    $('.panel div.clickable').click();
});


	$(document).ready(function(){
    //Handles menu drop down
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
    });
});

// ADD SLIDEDOWN ANIMATION TO DROPDOWN //
        $('.dropdown').on('show.bs.dropdown', function (e) {
            $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
        });

        // ADD SLIDEUP ANIMATION TO DROPDOWN //
        $('.dropdown').on('hide.bs.dropdown', function (e) {
            $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
        });

/** li check box **/
$(function () {
    $('.list-group.checked-list-box .list-group-item').each(function () {
        
        // Settings
        var $widget = $(this),
            $checkbox = $('<input type="checkbox" class="hidden" />'),
            color = ($widget.data('color') ? $widget.data('color') : "primary"),
            style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
            settings = {
                on: {
                    icon: 'fa fa-check-square-o'
                },
                off: {
                    icon: 'fa fa-square-o'
                }
            };
            
        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });
          

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }

        // Initialization
        function init() {
            
            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }
            
            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }
        init();
    });
    
    $('#get-checked-data').on('click', function(event) {
        event.preventDefault(); 
        var checkedItems = {}, counter = 0;
        $("#check-list-box li.active").each(function(idx, li) {
            checkedItems[counter] = $(li).text();
            counter++;
        });
        $('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
    });
});



/** li check box  search bar scroll (769)**/
if ($(window).width() > 752) {
	$(function() {
		var offset = $("#search-bar").offset();
		var topPadding = 15;
		$(window).scroll(function() {
			if (offset && $(window).scrollTop() > offset.top) {
				$("#search-bar").stop().animate({
					marginTop: $(window).scrollTop() - offset.top + topPadding
				});
			} else {
				$("#search-bar").stop().animate({
					marginTop: 0
				});
			};
		});
	});
}



// triger tool tip

$(document).ready(function () {
  $("a").tooltip({
    'selector': '',
    'container':'body'
  });
});

// date picker
$('#datepicker-container input').datepicker({
    format: "dd-M-yy",
    weekStart: 1,
    autoclose: true,
    todayHighlight: true
});



// form validation

// $('#myaccount').bootstrapValidator({
//        live: 'disabled',
//        message: 'This value is not valid',
  //      feedbackIcons: {
  //          valid: 'glyphicon glyphicon-ok',
  //          invalid: 'glyphicon glyphicon-remove',
  //          validating: 'glyphicon glyphicon-refresh'
  //      },
//        fields: {
//            displayname: {
//                validators: {
//                    notEmpty: {
//                        message: 'The Display name is required'
//                    }
//                }
//            },
//			
//            email: {
//                validators: {
//                    notEmpty: {
//                        message: 'The email is required'
//                    },
//                    emailAddress: {
//                        message: 'The input is not a valid email address'
//                    }
//                }
//            },	
//				
//            mobile: {
//                validators: {
//					digits: {
//					    message: 'Enter only digit'
//					},
//                    notEmpty: {
//                        message: 'The mobile is required'
//                    }
//                }
//            },
//
//
//            availability: {
//                validators: {
//                    notEmpty: {
//                        message: 'The availability is required'
//                    }
//                }
//            }			
//        }
//    });








