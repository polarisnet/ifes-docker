var dropdownSelectors = $('.dropdown, .dropup');

// Custom function to read dropdown data
// =========================
function dropdownEffectData(target) {
  // @todo - page level global?
  var effectInDefault = null,
      effectOutDefault = null;
  var dropdown = $(target),
      dropdownMenu = $('.dropdown-menu:not(.dropdown-menu .sub)', target);
  var parentUl = dropdown.parents('ul.nav'); 

  // If parent is ul.nav allow global effect settings
  if (parentUl.size() > 0) {
    effectInDefault = parentUl.data('dropdown-in') || null;
    effectOutDefault = parentUl.data('dropdown-out') || null;
  }
  
  return {
    target:       target,
    dropdown:     dropdown,
    dropdownMenu: dropdownMenu,
    effectIn:     dropdownMenu.data('dropdown-in') || effectInDefault,
    effectOut:    dropdownMenu.data('dropdown-out') || effectOutDefault,  
  };
}

// Custom function to start effect (in or out)
// =========================
function dropdownEffectStart(data, effectToStart) {
  if (effectToStart) {
    data.dropdown.addClass('dropdown-animating');
    data.dropdownMenu.addClass('animated');
    data.dropdownMenu.addClass(effectToStart);    
  }
}

// Custom function to read when animation is over
// =========================
function dropdownEffectEnd(data, callbackFunc) {
  var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
  data.dropdown.one(animationEnd, function() {
    data.dropdown.removeClass('dropdown-animating');
    data.dropdownMenu.removeClass('animated');
    data.dropdownMenu.removeClass(data.effectIn);
    data.dropdownMenu.removeClass(data.effectOut);
    
    // Custom callback option, used to remove open class in out effect
    if(typeof callbackFunc == 'function'){
      callbackFunc();
    }
  });
}

// Bootstrap API hooks
// =========================
dropdownSelectors.on({
  "show.bs.dropdown": function () {
    // On show, start in effect
    var dropdown = dropdownEffectData(this);
    dropdownEffectStart(dropdown, dropdown.effectIn);
	if($(this).hasClass('yamm-aw')) {
       var yammLeft = 0;
       yammLeft = $(this).position().left -300;
       if(yammLeft < 0) {
           yammLeft = 0;
       }
       $(this).find('.yamm-aw').css({left: yammLeft});
	}
  },
  "shown.bs.dropdown": function () {
    // On shown, remove in effect once complete
    var dropdown = dropdownEffectData(this);
    if (dropdown.effectIn && dropdown.effectOut) {
      dropdownEffectEnd(dropdown, function() {}); 
    }
    
    if($(".navbar-toggle").css('display') == 'block' || $(".navbar-toggle").css('display') == 'inline-block') {
        if($('#navbar-collapse-1').hasScrollBar()){
            $("#haveDownbarIcon").stop(true, true);
            $("#haveDownbar").show();
            for(i=0;i<3;i++) {
              $("#haveDownbarIcon").fadeTo('slow', 0.5).fadeTo('slow', 1.0);
            }
        }else {
            $("#haveDownbar").hide();
        }
    }else {
        $("#haveDownbar").hide();
    }
  },  
  "hide.bs.dropdown":  function(e) {
    // On hide, start out effect
    var dropdown = dropdownEffectData(this);
    if (dropdown.effectOut) {
      e.preventDefault();   
      dropdownEffectStart(dropdown, dropdown.effectOut);   
      dropdownEffectEnd(dropdown, function() {
        dropdown.dropdown.removeClass('open');
      }); 
    }
  },
  "hidden.bs.dropdown":  function(e) {
    if($(".navbar-toggle").css('display') == 'block' || $(".navbar-toggle").css('display') == 'inline-block') {
        if($('#navbar-collapse-1').hasScrollBar()){
            $("#haveDownbarIcon").stop(true, true);
            $("#haveDownbar").show();
            for(i=0;i<3;i++) {
              $("#haveDownbarIcon").fadeTo('slow', 0.5).fadeTo('slow', 1.0);
            }
        }else {
            $("#haveDownbar").hide();
        }
    }else {
        $("#haveDownbar").hide();
    }
    
    
    
  },
});

$('#navbar-collapse-1').on('shown.bs.collapse', function () {
  $("#haveDownbar").hide();
});