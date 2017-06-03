<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/placeholder-master/jquery.placeholder.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/skins/square/custom.css" />
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/jquery.icheck.min.js"></script>
Enter your login details below:<br><br>
<form id="form-login" name="form-login" enctype="multipart/form-data" method="post">
	<input type="text" class="flat-input" id="username" name="username" placeholder="Username" value="<?php echo $username; ?>"><br><br>
	<input type="password" class="flat-input" id="password" name="password" placeholder="Password"><br><br>
	<input type="checkbox"id="remember" name="remember" <?php if($checkedRememberMe){echo 'checked';} ?>><label class="flat-checkbox" for="remember">Remember Me</label><br>
	<input type="submit" value="Login" class="flat-button-default"><br><br>	
</form>				
<a class="hyperlink1" href="<?php echo getModuleURL('oz.lostpass.bo'); ?>">Forgot your password?</a><br>
<a class="hyperlink1" href="http://www.polarisnet.com.my/contactus.html" style="position: relative; top: 5px;" target="_blank">Contact us / Request for a trial</a>
<?php
	$winReady .= "	$('input, textarea').placeholder();
					$('input').iCheck({
						checkboxClass: 'icheckbox_square-custom'
					});";
?>
<style>
	.site-content-wrapper{height: 75%;}
	.site-content{position: relative; top: 25%;}
	.site-left{width: 60%;}
	.site-center{width: 40%;}
	.site-left{text-align: right;}
</style>