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
        <script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/submenu/bootstrap-submenu.min.js"></script>
		<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/core.js"></script>	
		<script type='text/javascript' src='<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/oz-noty/noty.js'></script>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/oz-noty/noty.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_THEME; ?>/style.css" />
        <link href="<?php echo HTTP_PLUGIN; ?>/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="<?php echo HTTP_PLUGIN; ?>/bootstrap/bootstrap-theme.min.css" rel="stylesheet">
        <link href="<?php echo HTTP_PLUGIN; ?>/bootstrap/demo.css" rel="stylesheet">
        <link href="<?php echo HTTP_PLUGIN; ?>/submenu/bootstrap-submenu.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_THEME; ?>/yamm.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_THEME; ?>/font.css" />  
	</head>
	<?php $winReady = ""; ?>
	<body class="x-body" style="padding-bottom: 0;">
		<div id="oz-noty" style="position: relative;"></div>
		<!-- <div class="aa-bs"> -->
			<div class="container" style="position: relative;">
				<?php require $setting['center_dir']; ?>
				<?php if($setting['footer_dir'] != ''){ require $setting['footer_dir']; }?>
			</div>
		<!-- </div> -->
		<script type="text/javascript">
			var headerHeight = 0;
			var headerWidth = 0;
			if($('#site-header').length != 0){
				headerHeight = $('#site-header').height();
				headerWidth = $('#site-header').width();
			}
			var footerHeight = 0;
			var footerWidth = 0;
			if($('#login-footer').length != 0){
				footerHeight = $('#login-footer').height();
				footerWidth = $('#login-footer').width();
			}
			
			var maximizeLeftBar = '<?php echo !$setting["left_maximize"];?>';
			var oriLeftBarWidth = $('#site-left').width();
    
                        var SITE_NAME = <?php echo json_encode(SITE_NAME); ?>;
			var HTTP_MEDIA = <?php echo json_encode(HTTP_MEDIA); ?>;
			var HTTP_AJAX = <?php echo json_encode($HTTP_AJAX) ?>;
			var JS_SERVER = <?php echo json_encode(HTTP_SERVER); ?>;
			var JS_ROOT = <?php echo json_encode(HTTP_ROOT); ?>;
			var JS_USERID = '<?php echo (isset($_SESSION['user_id'])?$_SESSION['user_id']:"0"); ?>';
                        
                        $(document).ready(function(){
				//toggleLeftBar();
				<?php echo $winReady; ?>
				<?php echo onReadyMessage($message, $error, $warning); ?>
				onMarkError([<?php echo onReadyMarkError($markError); ?>]);
			});
		</script>
	</body>
</html>