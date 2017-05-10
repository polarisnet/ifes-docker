<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/placeholder-master/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/complexify/jquery.complexify.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/skins/square/custom.css" />
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/jquery.icheck.min.js"></script>
<b>Reset Password</b><br><br>
<form id="form-reset" name="form-reset" enctype="multipart/form-data" method="post">
		<input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    	<input type="password" class="flat-input" id="password" name="password" onkeydown="javascript:calculateComplexity();"  placeholder="New Password"><br><br>
        <input type="password" class="flat-input" id="retype_password" name="retype_password" onblur="javascript: validateMatch($('#password'), $('#retype_password'), 'new password', 'retype password');" placeholder="Retype New Password"><br>
        <div class="password-calc"><div id="password-inner" class="password-inner"><div id="password-bar" class="password-bar"></div></div></div><br>
        <input type="submit" value="Continue" class="flat-button-default"><br><br>
</form>	    			
<a class="hyperlink1" href="<?php echo getModuleURL('oz.login.bo'); ?>">Back to Login Page</a>
<?php
	$winReady .= "$('input, textarea').placeholder();";
?>
<style>
	.site-content-wrapper{height: 75%;}
	.site-content{position: relative; top: 25%;}
	.site-left{width: 60%;}
	.site-center{width: 40%;}
	.site-left{text-align: right;}
</style>