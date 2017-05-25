<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $setting['title']; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="<?php echo $setting['meta_keyword']; ?>">
		<meta name="description" content="<?php echo $setting['meta_description']; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="<?php echo HTTP_MEDIA;?>/site-image/favicon.ico" type="image/x-icon">
		<?php if($GLOBALS['siteSetting']['debug_mode'] == '0'){ ?>
			<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/jquery-1.10.1-min.js"></script>
		<?php }else{ ?>
			<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/jquery-1.10.1.js"></script>
		<?php } ?>
        <script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/printing/print.js"></script>
        <script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/submenu/bootstrap-submenu.min.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/core.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/jscrollpane-master/style/jquery.jscrollpane.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/jscrollpane-master/themes/lozenge/style/jquery.jscrollpane.oz.css" media="all"/>
		<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/jscrollpane-master/script/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/jscrollpane-master/script/mwheelIntent.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/jscrollpane-master/script/jquery.jscrollpane.min.js"></script>	
		<script type='text/javascript' src='<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/oz-noty/noty.js'></script>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/oz-noty/noty.css" />
		<script type='text/javascript' defer src='<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/tooltip/bootstrap-tooltip.js'></script>
		<script type='text/javascript' defer src='<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/tooltip/script.js'></script>
                <script type='text/javascript' defer src='<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/caret/dist/jquery.caret.min.js'></script>
		<?php if($setting['extjs'] == '1'){ ?>			
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/resources/css/ext-all-neptune.css">
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/GridFilters.css">
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/RangeMenu.css">
			<?php if($GLOBALS['siteSetting']['debug_mode'] == '0'){ ?>
				<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/ext-all.js"></script>
				<!--<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/extendpagingtoolbar.js"></script>-->
			<?php }else{ ?>
				<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/ext-all-debug.js"></script>
			<?php } ?>
			<?php /*<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/packages/ext-theme-neptune/build/ext-theme-neptune.js"></script>	*/	?>	
			<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/extjs_core.js"></script>	
			<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/customnumberfield/ThousandSeparatorNumberField.js"></script>
		<?php } ?>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_THEME; ?>/style.css" />
	    <link href="<?php echo HTTP_PLUGIN; ?>/bootstrap/bootstrap.css" rel="stylesheet">
	    <link href="<?php echo HTTP_PLUGIN; ?>/bootstrap/bootstrap-theme.min.css" rel="stylesheet">
	    <link href="<?php echo HTTP_PLUGIN; ?>/bootstrap/animate.css" rel="stylesheet">
	    <link href="<?php echo HTTP_PLUGIN; ?>/bootstrap/demo.css" rel="stylesheet">
	    <link href="<?php echo HTTP_PLUGIN; ?>/submenu/bootstrap-submenu.min.css" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_THEME; ?>/yamm.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_THEME; ?>/font.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PLUGIN; ?>/css/breadcrumbs/crumbs.css">
	</head>
	<?php $winReady = ""; ?>
	<body>
		<div id="oz-noty" style="position: relative;"></div>
		<?php if($setting['header'] == '1'){ ?>
		<!--<div id="" class="">-->
			<?php if($setting['header_dir'] != ''){ require $setting['header_dir']; }?>
		<!--</div>-->
		<?php } ?>
		<div id="site-content-wrapper" class="site-content-wrapper">
			<table id="site-content" class="site-content">
				<tr>
					<?php if($setting['left'] == '1'){ ?>
                        <!--<td id="site-left" style="background-color: #ccc; width:10px;"></td>-->
                        <td id="site-left" class="site-left" style="<?php if($setting["left_maximize"] == 0){echo "width:12px;";}else {echo "width:195px;";} ?>"><div id="lessbar" style="background-color:#ccc;height: 100vh;width: 12px; position: absolute;<?php if($setting["left_maximize"] == 0){echo "left:0px;display:inline";}else {echo "left:-12px;display:none";} ?>"><div id="hoverHide" class="" style="height:26px;width:38px;text-align: right;padding:5px;background-color:#ccc;border-left:0;z-index:9999;position:absolute;top:180px;left: -101px"><img id="leftbar-toggle-img-hover" src="<?php echo HTTP_MEDIA;?>/site-image/next.png" width="26px;" height="26px;" onClick="javascript: toggleLeftBar();" onMouseOver="javascript: toggleLeftBarImg(true);" onMouseOut="javascript: toggleLeftBarImg(false);"></div></div><div id="fullbar" style="position:relative;height: 100vh;<?php if($setting["left_maximize"] == 0){echo "left:-195px;display:none";}?>"><?php if($setting['left_dir'] != ''){require $setting['left_dir']; }?></div></td>
					<?php } ?>
					<td id="site-center" class="site-center">
						<div id="site-center-content">
							<?php if($GLOBALS['siteSetting']['enable_chat'] == '1'){ ?>
								<div id="ws-chat-box" class="ws-chat-box" style="">
									<img src="<?php echo HTTP_MEDIA;?>/site-image/minimize_window-16.png" class="ws-chat-cont-cls-btn" onClick="javascript: $('#ws-chat-box').hide();">
									<div id="ws-chat-group">
										<div id="ws-chat-cont-broadcast">
											<img src="<?php echo HTTP_MEDIA;?>/site-image/online-128.png" class="ws-chat-avatar-img">
											<div class="ws-chat-cont-title">
												Broadcast<br>
												<img src="<?php echo HTTP_MEDIA;?>/site-image/ws_chat_online_sq.png"> Online
											</div>
											<hr>
											<div id="ws-chat-log-broadcast" class="ws-chat-log"></div>
										</div>
										<?php echo $wsBroadCastTemplate; ?>
									</div>
									<div class="ws-chat-control">
										<input type="hidden" id="ws-chat-uid" value="broadcast">
										<textarea id="ws-chat-text" class="ws-chat-text"></textarea>
										<img src="<?php echo HTTP_MEDIA;?>/site-image/reply-26.png" style="margin-left: 3px; cursor: pointer;" onMouseOver="javascript: hoverWSSendChatIcon(this);" onMouseOut="javascript: hoverOutWSSendChatIcon(this);" onClick="javascript: sendWSChat();">
									</div>
								</div>
							<?php } ?>
							<div id="center-content">
								<div class="inner-padding">
									<?php if($access){ ?>
										<div class="flat-content-header" style="padding-bottom: 8px; cursor: default;"><?php echo strtoupper(MODULE_NAME); ?></div><?php
										if($setting['load_breadcrumb'] == '1'){echo getBreadCrumbTemplate($breadCrumbData); }
										if($setting['load_tile'] == '1'){if(MODULE_UID != 'oz.dashboard'){echo loadTileTemplate(MODULE_UID);}}
										if(file_exists($setting['center_dir'])){require $setting['center_dir']; }else{ ?><div style="text-align: center; color: red; padding: 10px;">Template file not found!</div><?php }
										if(MODULE_UID == 'oz.dashboard'){echo loadTileTemplate(''); }
                                                                                if($setting['load_tile'] == '1'){
                                                                                    ?>
                                                                                <script type="text/javascript">
                                                                                    $(".tooltipbutton").click(function( event ) {
                                                                                    event.stopPropagation();
                                                                                    $(event.target).parent(".tilepic").css("top", "-110%");$(event.target).parent(".tilepic").siblings(".tiletooltip").css("bottom", "0%");
                                                                                  });

                                                                                    $(".tooltipclose").click(function( event ) {
                                                                                    event.stopPropagation();
                                                                                    $(event.target).parent(".tiletooltip").css("bottom", "-100%");$(event.target).parent(".tiletooltip").siblings(".tilepic").css("top", "0%");
                                                                                  });

                                                                                    $(".tooltipbutton").mouseover(function( event) {


                                                                                    $(event.target).addClass("animated");
                                                                                    $(event.target).addClass("rotateIn");

                                                                                    var animationEnd = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
                                                                                    $(event.target).one(animationEnd, function() {
                                                                                        $(event.target).removeClass("animated");
                                                                                        $(event.target).removeClass("rotateIn");
                                                                                    });

                                                                                  });

                                                                                    $(".tile-container").mouseover(function( event) {
                                                                                    event.stopPropagation();

                                                                                    $(event.target).find(".mainimg").addClass("animated");
                                                                                    $(event.target).find(".mainimg").addClass("pulse");

                                                                                    var animationEnd = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
                                                                                    $(event.target).one(animationEnd, function() {
                                                                                        $(event.target).find(".mainimg").removeClass("animated");
                                                                                        $(event.target).find(".mainimg").removeClass("pulse");
                                                                                    });
                                                                                    });
                                                                                  </script>
                                                                                    <?php
                                                                                }
									}else{ ?>
										<div style="text-align: center; color: red; padding: 10px;">You do not have enough privileges to access this page!</div>		
									<?php } ?>
								</div>
							</div>
						</div>
						<img id="go-to-top" src="<?php echo HTTP_MEDIA; ?>/site-image/go-to-top.png">
						<?php if($setting['footer'] == '1'){ ?>
						<div id="site-footer" class="site-footer">
							<?php if($setting['footer_dir'] != ''){ require $setting['footer_dir']; }?>
						</div>
						<?php } ?>
					</td>
					<?php if($setting['right'] == '1'){ ?>
					<td id="site-right" class="site-right">Right</td>
					<?php } ?>
				</tr>
			</table>
		</div>
		<script type="text/javascript">
			var headerHeight = 50;
			var headerWidth = 0;
			if($('#site-header').length != 0){
				headerHeight = $('#site-header').height();
				headerWidth = $('#site-header').width();
			}
			var footerHeight = 0;
			var footerWidth = 0;
			if($('#site-footer').length != 0){
				footerHeight = $('#site-footer').height();
				footerWidth = $('#site-footer').width();
			}

			var maximizeLeftBar = <?php if($setting["left_maximize"]){echo "true";}else{echo "false";}?>;
			var oriLeftBarWidth = 195;
			var initialLoadBar = '<?php echo getCookieValue('toggle_leftbar_initial'); ?>';
			
			var SITE_NAME = <?php echo json_encode(SITE_NAME); ?>;
			var HTTP_MEDIA = <?php echo json_encode(HTTP_MEDIA); ?>;
			var HTTP_AJAX = <?php echo json_encode($HTTP_AJAX) ?>;
			var JS_SERVER = <?php echo json_encode(HTTP_SERVER); ?>;
			var JS_ROOT = <?php echo json_encode(HTTP_ROOT); ?>;
			var JS_GRIDSTATES = <?php echo json_encode(HTTP_SERVER.HTTP_ROOT.'/system/settings'); ?>;
			var JS_USERID = '<?php echo (isset($_SESSION['user_id'])?$_SESSION['user_id']:"0"); ?>';
            var _visible = true;
            var _previousScrollTop = null;
            var _animatingNav = false;
            var _animatingNavC = false;
			
			$(document).ready(function(){
				<?php echo $winReady; ?>
				$(window).bind(
					'resize', function(){
						autoAdjust('bo');
					}
				).trigger('resize');
				<?php echo onReadyMessage($message, $error, $warning); ?>
				onMarkError([<?php echo onReadyMarkError($markError); ?>]);
				$('#center-content').scroll(function (event){
                                        event.stopPropagation();
					var scrollVal = $('#center-content').scrollTop(),
                                            scrollDelta = scrollVal - _previousScrollTop;
					if(scrollVal > 80){
						$('#go-to-top').fadeIn();
					}else{
						$('#go-to-top').fadeOut();
					}
                                        _previousScrollTop = scrollVal;
                                        if (scrollDelta < 0) {
                                          if (_visible) {
                                            return;
                                          }
                                          if (true) {
                                            if(!_animatingNav && !_animatingNavC || $('#center-content').scrollTop() == 0) {
                                              _animatingNav = true;
                                              _animatingNavC = true;
                                              $(".navbar-fixed-top").animate({
                                                  top: 0
                                                }, {
                                                  queue: false,
                                                  duration: 300,
                                                  complete: function() {
                                                      _animatingNav = false;
                                                      var heightCenter = $(window).height() - headerHeight - footerHeight - 6;
                                                    $('#site-center-content').height(heightCenter);
                                                    $('#center-content').height(heightCenter);
                                                    $('#site-left').height(heightCenter);
                                                  }
                                                });
                                                $(".flat-header").animate({
                                                  height: 50
                                                }, {
                                                  queue: false,
                                                  duration: 300,
                                                  complete: function() {
                                                      _animatingNavC = false;
                                                  }
                                                });
                                              _visible = true;
                                              
                                            }
                                          }
                                        }
                                        else if (scrollDelta > 0) {
                                            if (!_visible) {
                                            return;
                                          }
                                        if(!_animatingNav && !_animatingNavC) {
                                            if (scrollVal > 60) {
                                                var heightCenter = $(window).height() - footerHeight - 6;
                                                  $('#site-center-content').height(heightCenter);
                                                  $('#center-content').height(heightCenter);
                                                  $('#site-left').height(heightCenter);
                                                _animatingNav = true;
                                                _animatingNavC = true;
                                                $(".navbar-fixed-top").animate({
                                                    top: -$(".navbar-fixed-top").height()
                                                  }, {
                                                    queue: false,
                                                    duration: 500,
                                                    complete: function() {
                                                        _animatingNav = false;
                                                    }
                                                  });
                                                  $(".flat-header").animate({
                                                    height: -$(".flat-header").height()
                                                  }, {
                                                    queue: false,
                                                    duration: 500,
                                                    complete: function() {
                                                        _animatingNavC = false;
                                                    }
                                                  });
                                                  _visible = false;
                                              }
                                          }
                                      }
				});
				$('#go-to-top').click(function(){
					$('#center-content').animate({scrollTop: 0}, 800);
					return false;
				});
				$('#btn-edit').click(function(){
					$('#center-content').scrollTop(0);
					
				});
				$('#btn-edit1').click(function(){
					$('#center-content').scrollTop(99999999);
				});
                                $(document).on('click', '.yamm .dropdown-menu', function(e) {
                                    e.stopPropagation()
                                  });
                                $('[data-submenu]').submenupicker();
                                var hoversnip = false;
                                $( "#lessbar" ).mouseenter(function(event) {
                                    if(hoversnip === false){
                                        hoversnip = true;
                                var minusHeight = 0;
                                if($('.navbar').position().top < 0) {
                                    minusHeight = -50;
                                }else {
                                    minusHeight = 50;
                                }
                                        $('#hoverHide').css('top', (event.pageY+$('.navbar').position().top-minusHeight-13) + 'px');
                                        if($('#hoverHide').position().top < 0){
                                            $('#hoverHide').css('top', 0);
                                        }
                                        if(minusHeight < 0) {
                                            minusHeight = 0;
                                        }else {
                                            minusHeight = 50;
                                        }
                                        if($('#hoverHide').position().top+minusHeight+50 >$(window).height()){
                                            $('#hoverHide').css('top', $(window).height()-minusHeight-36);
                                        }
                                        $('#hoverHide').animate({
                                            left: '0px'
                                        });
                                  }
                                })
                                .mouseleave(function() {
                                  if(hoversnip === true){
                                        $('#hoverHide').animate({
                                            left: '-101px'
                                        }, 100, function() {
                                            hoversnip = false;
                                          });
                                        
                                    }
                                });
                                var hideDownBar = false;
                                $('#navbar-collapse-1').scroll(function (){
                                    var scrollVal = $('#navbar-collapse-1').scrollTop();
                                        if($(".navbar-toggle").css('display') == 'block' || $(".navbar-toggle").css('display') == 'inline-block') {
                                        if(scrollVal+340 >= $('#navbar-collapse-1')[0].scrollHeight){
                                            if(!hideDownBar) {
                                                hideDownBar = true;
                                                $("#haveDownbar").removeClass('animated');
                                                $("#haveDownbar").removeClass('fadeIn');

                                                $("#haveDownbar").addClass('animated');
                                                $("#haveDownbar").addClass('fadeOut');
                                            }
                                        }else{
                                            if(hideDownBar){
                                             hideDownBar = false;
                                             $("#haveDownbar").show();
                                             if($('#navbar-collapse-1').hasScrollBar()){
                                                 $("#haveDownbar").removeClass('animated');
                                                 $("#haveDownbar").removeClass('fadeOut');

                                                 $("#haveDownbar").addClass('animated');
                                                 $("#haveDownbar").addClass('fadeIn');
                                                }
                                            }
                                        }
                                    }
                                });
			});
		</script>
                <script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/bootstrap/dropdownanimation.js"></script>
	</body>
</html>