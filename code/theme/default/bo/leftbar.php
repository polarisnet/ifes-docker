<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/placeholder-master/jquery.placeholder.min.js"></script>
<div id="leftbar-toggle-container" style="text-align: right; padding: 8px 11px; position: relative;">
	<img id="leftbar-toggle-img" src="<?php echo HTTP_MEDIA;?>/site-image/back.png" width="26px;" height="26px;" onclick="javascript: toggleLeftBar();" onmouseover="javascript: toggleLeftBarImg(true);" onmouseout="javascript: toggleLeftBarImg(false);">
</div>
<div id="leftbar-content" style="width: <?php echo $setting['left_width']; ?>px; margin-top: -10px;">
	<?php echo loadSideBarNavigation($setting['left_uid'], 'sidebar-navigation');?>
	<?php echo getTrackerTemplate('sidebar-navigation'); ?>
	<?php if($GLOBALS['siteSetting']['enable_chat'] == '1'){require DIR_ACTIVE_THEME."/leftbar_chat.php"; } ?>
</div>
<style>
	.left-scroll-pane{
		background: red; 
		min-height: 50px; 
		min-width: 50px;
		overflow: auto;
	}
</style>