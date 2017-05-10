<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/placeholder-master/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/new-shortcut.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/complexify/jquery.complexify.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/skins/square/custom.css" />
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/jquery.icheck.min.js"></script>
<?php if(!($allowRecover)){ ?>
	<b>Answer question correctly to reset your account password</b><br><br>
<?php } ?>	
<?php if($allowRecover){ ?>
	<b>Reset Password</b><br><br>
<?php } ?>	
<?php if(!($allowRecover)){ ?>
<form id="form-recover" name="form-recover" enctype="multipart/form-data" method="post">
	<input type="hidden" name="mode" value="secquestion">
    <input type="hidden" name="encUserName" value="<?php echo $recoverusername; ?>">
    <input type="hidden" name="encSecQuestionID" value="<?php echo $userRecover['sec_id']; ?>">	
	<?php echo $userSecQuestion['question']; ?>    
    <p><input type="text" class="flat-input" id="secanswer" name="secanswer" placeholder="Enter the answer" value="<?php echo $secanswer; ?>"></p>
	<input type="submit" value="Continue" class="flat-button-default" onclick="javascript: submitForm('');"><br><br>	
</form>	
<?php } ?>
<?php if($allowRecover){ ?>
<form id="form-reset" name="form-reset" enctype="multipart/form-data" method="post">	
        <input type="hidden" name="mode" value="secquestion">
        <input type="hidden" name="type" value="reset">
        <input type="hidden" name="encUserName" value="<?php echo $recoverusername; ?>">
        <input type="hidden" name="encSecQuestionID" value="<?php echo $userRecover['sec_id']; ?>">
    	<input type="password" class="flat-input" id="password" name="password" onkeydown="javascript:calculateComplexity();"  placeholder="Password"><br><br>
        <input type="password" class="flat-input" id="retype_password" name="retype_password" onblur="javascript: validateMatch($('#password'), $('#retype_password'), 'password', 'retype password');" placeholder="Retype Password"><br><br>
        <div class="password-calc"><div id="password-inner" class="password-inner"><div id="password-bar" class="password-bar"></div></div></div><br><br>
        <input type="submit" value="Continue" class="flat-button-default" onclick="javascript: submitFormRecover('');"><br><br>
</form>	
<?php } ?>		
    			
<a class="hyperlink1" href="<?php echo getModuleURL('oz.login.bo'); ?>">Back to Login Page</a>
<?php
	$winReady .= "$('input, textarea').placeholder();";
?>
<script type="text/javascript">	
	function submitForm(mode){		
		clearValidation('form-recover');
		if(!validateEmpty($('#secanswer'), 'secret answer')){return;}
		else{
			$('#form-recover').submit();
		}	
	}
	function submitFormRecover(mode){		
		clearValidation('form-reset');
		if(!validateEmpty($('#password'), 'password')){return;}
		if(!validateEmpty($('#retype_password'), 'retype password')){return;}
		if(!validateMatch($('#password'), $('#retype_password'), 'password', 'retype password')){return;}
		else{
			$('#form-reset').submit();
		}	
	}
</script>
<style>
	.site-content-wrapper{height: 75%;}
	.site-content{position: relative; top: 25%;}
	.site-left{width: 60%;}
	.site-center{width: 40%;}
	.site-left{text-align: right;}
</style>