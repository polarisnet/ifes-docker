<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/placeholder-master/jquery.placeholder.min.js"></script>
<script type="text/javascript">      
  function recoveryOptionSelected() {
	var radioOptions =
		document.getElementsByName("preoption");
	for (var i = 0; i < radioOptions.length; ++i) {
	  var confirmBox = document.getElementById(
		  "hideable-box" + radioOptions[i].id);
	  if (confirmBox) {
		if (radioOptions[i].checked) {
		  confirmBox.style.height = confirmBox.scrollHeight + 'px';
		} else {
		  confirmBox.style.height = '0px';
		}
	  }
	}
  }
</script>
<div class="recovery main content clearfix">
   <b>Having trouble signing in?</b>
    <form id="form-lostpass" name="form-lostpass" enctype="multipart/form-data" method="post">
        <input type="hidden" name="mode" value="lostpass"> 
        <input type="hidden" name="service" value="mail"> 
        <div class="errorbox-good"></div>
        <input type="hidden" name="jsEnabled" id="jsEnabledField" value="False">
        <script type="text/javascript">
          var jsEnabledElement = document.getElementById('jsEnabledField');
          jsEnabledElement.value = "True";
        </script>
        <p><div class="radio-option">
            <input type="radio" name="preoption" value="1" id="1" onclick="recoveryOptionSelected()">
            <label class="radio-label" for="1" style="cursor: pointer">I would like to use my registered email address to reset my password.</label>
                <div class="hideable-wrapper" id="hideable-box1"><div class="hideable-box">
                	<div class="secondary">To reset your password, please enter the email address that is associated with your account.</div>               
                <input type="text" id="recoveremail" name="recoveremail" class="english-text" size="30" placeholder="Email Adress"></p></div></div>
        </div></p>    
        <p><div class="radio-option">
            <input type="radio" name="preoption" value="3" id="3" onclick="recoveryOptionSelected()">
            <label class="radio-label" for="3" style="cursor: pointer">I would like to use my security question to reset my password.</label>
                <div class="hideable-wrapper" id="hideable-box3"><div class="hideable-box"><div class="secondary">Enter the username that you use to sign in.</div>               
                <input type="text" class="flat-input" id="recoverusername" name="recoverusername" size="30" placeholder="Account Username"></p></div></div>
      	</div></p>
        <p class="recovery-submit"><input type="submit" value="Continue" class="flat-button-default"></p>
    </form>
    <script type="text/javascript">
        
		recoveryOptionSelected();
    </script>
</div>
<a class="hyperlink1" href="<?php echo getModuleURL('oz.login.bo'); ?>">Back to Login Page</a>
<?php
	$winReady .= "	$('input, textarea').placeholder();"					
?>
<style>
	.site-content-wrapper{height: 75%;}
	.site-content{position: relative; top: 25%;}
	.site-left{width: 60%;}
	.site-center{width: 40%;}
	.site-left{text-align: right;}
</style>