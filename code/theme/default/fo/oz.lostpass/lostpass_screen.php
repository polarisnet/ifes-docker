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
<div class="row" id="login-content" style="height:95vh;padding-bottom: 10px;">
<div class="col-sm-6 hidden-xs" style="height:100%;padding-right:0;">
   <?php 
            $loadBanner = checkBannerExist('Login Screen');
            if(isset($loadBanner) && is_array($loadBanner) && count($loadBanner)>1){ ?>

            <div id="login-pic" class="carousel slide" data-ride="carousel" style="height:100%">
             <?php echo loadBanner('Login Screen'); ?>
            </div>

    <?php }else if(isset($loadBanner) && is_array($loadBanner) && count($loadBanner) == 1 ){ ?>
            <div style="position:relative;top:50%;transform: translateY(-50%);">
                <img id="login-pic" class="img-responsive" style="max-height:100%;margin: 0 auto;" src="<?php echo HTTP_MEDIA.'/site-image/banner/'.$loadBanner[0]['path']; ?>">
            </div>
    <?php }else{ ?>
            <div id="login-pic" style="position:relative;top:50%;transform: translateY(-50%);">
                <img id="login-pic" class="img-responsive" style="max-height:100%;margin: 0 auto;" src="<?php echo HTTP_MEDIA;?>/site-image/banner3.jpg">     
            </div>
    <?php } ?>
</div>
<div id="login-form" class="col-sm-6 col-xs-12" style="height:95vh;">
    <div class="row" style="height:33%;"><div class="col-xs-12 v-align" ><img id="login-logo" class="img-responsive logo" src="<?php echo HTTP_MEDIA;?>/site-image/polarisnet.jpg"></div></div>
    <div class="row" style="height:67%">
        <div class="col-xs-12" style="top:16%;transform:translateY(-33%)" >
        <form id="form-lostpass" name="form-lostpass" enctype="multipart/form-data" method="post" class="form-signin">
            <input type="hidden" name="mode" value="lostpass"> 
            <input type="hidden" name="service" value="mail">
            <div class="errorbox-good"></div>
            <input type="hidden" name="jsEnabled" id="jsEnabledField" value="False">
            <script type="text/javascript">
              var jsEnabledElement = document.getElementById('jsEnabledField');
              jsEnabledElement.value = "True";
            </script>
            <h5 class="form-signin-heading">Having trouble signing in?</h5>
            <div class="recovery" style="margin-bottom: 10px;">
            <div class="radio-option">
                <input type="radio" name="preoption" value="1" id="1" onclick="recoveryOptionSelected()">
                <label class="radio-label" for="1" style="cursor: pointer">I would like to use my registered email address to reset my password.</label>
                    <div class="hideable-wrapper" id="hideable-box1"><div class="hideable-box">
                            <div class="secondary">To reset your password, please enter the email address that is associated with your account.</div>               
                    <input type="text" class="form-control" id="recoveremail" name="recoveremail" class="english-text" size="30" placeholder="Email Adress"></p></div></div>
            </div>   

            <div class="radio-option">
                <input type="radio" name="preoption" value="3" id="3" onclick="recoveryOptionSelected()">
                <label class="radio-label" for="3" style="cursor: pointer">I would like to use my security question to reset my password.</label>
                    <div class="hideable-wrapper" id="hideable-box3"><div class="hideable-box"><div class="secondary">Enter the username that you use to sign in.</div>               
                    <input type="text" class="form-control" id="recoverusername" name="recoverusername" size="30" placeholder="Account Username"></div></div>
            </div>
            </div>
            <button type="submit" class="btn btn-md btn-primary btn-block">Continue</button>
            <br/>
            <a  href="<?php echo getModuleURL('oz.login.bo'); ?>">Back to Login Page</a><br>
            <a  href="http://www.polarisnet.com.my/contactus.html" style="position: relative; top: 5px;">Sign up for a new account</a>
          </form>
    <script type="text/javascript">

        recoveryOptionSelected();
    </script>
    </div>
    </div>

</div>
</div>
                    
                    