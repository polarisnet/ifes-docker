<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/placeholder-master/jquery.placeholder.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/skins/square/custom.css" />
<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/icheck-master/jquery.icheck.min.js"></script>
	<b>Answer the security question correctly to reset your password.</b><br><br>
<form id="form-recover" name="form-recover" enctype="multipart/form-data" method="post">
	<input type="hidden" name="mode" value="secquestion">
    <input type="hidden" name="encUserName" value="<?php echo $recoverusername; ?>">
    <input type="hidden" name="encSecQuestionID" value="<?php echo $userRecover['sec_id']; ?>">	
	<?php if($userRecover['sec_id'] == '0'){echo $userRecover['sec_question'];}else{echo $userSecQuestion['question'];} ?>    
    <p><input type="text" class="flat-input" id="secanswer" name="secanswer" placeholder="Enter the answer" value="<?php echo $secanswer; ?>"></p>
	<input type="submit" value="Continue" class="flat-button-default" onclick="javascript: submitForm('');"><br><br>	
</form>	
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
</script>
<style>
	.site-content-wrapper{height: 75%;}
	.site-content{position: relative; top: 25%;}
	.site-left{width: 60%;}
	.site-center{width: 40%;}
	.site-left{text-align: right;}
</style>