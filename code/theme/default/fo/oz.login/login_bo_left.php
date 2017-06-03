<?php /* <img src="<?php echo HTTP_MEDIA;?>/site-image/login-banner.png"> */?>
<link rel="stylesheet" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/nivo-slider/themes/default/default.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/nivo-slider/themes/bar/bar.css" type="text/css" media="screen" />
<link rel="stylesheet" media="screen" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/nivo-slider/nivo-slider.css" /> 
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/nivo-slider/jquery.nivo.slider.pack.js"></script>
<?php 
	$loadBanner = checkBannerExist('Login Screen');
	if(isset($loadBanner) && is_array($loadBanner) && count($loadBanner)>1){ ?>
		<div style="width: 613px; height: 240px; overflow: hidden; float: right; padding-right: 5px;">	
			<div class="slider-wrapper theme-default">
				<?php echo loadBanner('Login Screen'); ?>	
			</div>
		</div>
		<script type="text/javascript">
			$(window).ready(function() {
				$('#slider').nivoSlider({pauseTime: 8000});		
				$(".nivo-prevNav").text("");
				$(".nivo-nextNav").text("");
			});
		</script>
<?php }else if(isset($loadBanner) && is_array($loadBanner) && count($loadBanner) == 1 ){ ?>
	<div style="width: 613px; height: 240px; overflow: hidden; float: right; padding-right: 5px;">	
		<div id="slider" style="height: 210px; width: 613px; overflow: hidden;">
			<img style="width: 613px;" src="<?php echo HTTP_MEDIA.'/site-image/banner/'.$loadBanner[0]['path']; ?>">
		</div>
	</div>	
<?php }else{ ?>
	<div style="width: 613px; height: 240px; overflow: hidden; float: right; padding-right: 5px;">	
		<div id="slider" style="height: 210px; width: 613px; overflow: hidden;">
			<img style="width: 613px;" src="<?php echo HTTP_MEDIA;?>/site-image/login-banner.png">
		</div>
	</div>
<?php } ?>