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
    <div class="row" style="height:33%;"><div class="col-xs-12 v-align" ><img id="login-logo" class="img-responsive logo" src="<?php echo HTTP_MEDIA;?>/site-image/ifes.jpg"></div></div>
    <div class="row" style="height:67%">
        <div class="col-xs-12" style="top:16%;transform:translateY(-33%)" >
        <form id="form-recover" name="form-recover" enctype="multipart/form-data" method="post" class="form-signin">
            <input type="hidden" name="mode" value="secquestion">
            <input type="hidden" name="encUserName" value="<?php echo $recoverusername; ?>">
            <input type="hidden" name="encSecQuestionID" value="<?php echo $userRecover['sec_id']; ?>">	
            <h5 class="form-signin-heading">Answer the security question correctly to reset your password.</h5>
            <div class="recovery" style="margin-bottom: 10px;">
            <label>Question: <?php if($userRecover['sec_id'] == '0'){echo $userRecover['sec_question'];}else{echo $userSecQuestion['question'];} ?></label>    
            <input type="text" class="form-control" id="secanswer" name="secanswer" placeholder="Enter the answer" value="<?php echo $secanswer; ?>">
            </div>
            <button type="submit" class="btn btn-md btn-primary btn-block"  onclick="javascript: submitForm('');">Continue</button>
            <br/>
            <a  href="<?php echo getModuleURL('oz.login.bo'); ?>">Back to Login Page</a><br>
            <a  href="http://www.polarisnet.com.my/contactus.html" style="position: relative; top: 5px;">Sign up for a new account</a>
          </form>
    </div>
    </div>

</div>
</div>
<script type="text/javascript">	
	function submitForm(mode){		
		clearValidation('form-recover');
		if(!validateEmpty($('#secanswer'), 'secret answer')){return;}
		else{
			$('#form-recover').submit();
		}	
	}	
</script>