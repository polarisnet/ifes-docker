<body>
    <div class="container" style="position:relative;">
        <form id="form-login" name="form-login" enctype="multipart/form-data" method="post" class="form-signin" 
        style="background: #EBEBEB;margin: 10% auto;width: 400px; text-align: center; border: 1px solid #dddddd; padding:40px;">
            <strong style="font-size:16px;">Sign In</strong><hr />
            
            <div class="row" style="text-align:left;">
                <div class="col-xs-12" style="">
                    <label for="donor-profile-input-firstname">EMAIL</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Email Address" value="">
                </div>
            </div>
            <br />
            <div class="row" style="margin-left:0;margin-right:0;text-align:left;">
                <label for="donor-profile-input-firstname">PASSWORD</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <br /><strong style="font-size:14px;">Forgot your password? <a href="#"><u>Recover it here</u></a>.</strong><br />
            <div class="row" style="margin-left:0; margin-right:0; text-align:center; padding-top:12px; padding-bottom:12px;">
                <div class="col-xs-12"><button type="submit" class="btn" style="padding:10px 40px 10px 40px;background-color:#646464;color:#FFF;">Sign in</button></div>
            </div>
            
            <br /><strong style="font-size:14px;">Don't have an account?</strong><br />
            <div class="row" style="margin-left:0; margin-right:0; text-align:center; padding-top:12px; padding-bottom:12px;">
                <div class="col-xs-12"><button type="button" class="btn" style="padding:10px 40px 10px 40px;background-color:#EBEBEB;color:#666;border:1px solid #AFAFAF;" onclick="javascript:window.location.href='<?php echo getModuleURL('register');?>'">Sign up</button></div>
            </div>
            
        </form>
    </div>
</body>
<style type="text/css">
.form-control{
	width: auto;
	margin: auto;
	min-width: 320px;
}
body{
	background-image: url('<?php echo HTTP_MEDIA; ?>/site-image/world-map-color-lg.png');
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-position: center;
}
.col-signup, .col-remember{
	padding-left: 39px;
}
.col-forget{
	text-align: right; padding-right: 39px;
}
.col-remember{
	padding-top: 8px;
}
.col-login{
}
@media(max-width: 414px){
	.form-horizontal{
		width: 330px !important;
	}
	.form-hr{
		width: 300px !important;
	}
	.form-login .form-control{
		width: 280px !important;
		min-width: 280px !important;
		max-width: 280px !important;
	}
	.col-signup, .col-remember{
		padding-left: 44px !important;
	}
	.col-forget{
		padding-right: 44px !important;
	}
	.col-login{
		padding-right: 17px !important;
	}
}
</style>


<?php /*

<div class="row" id="login-content" style="height:95vh;padding-bottom: 10px;">
<br />
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
    <div class="row" style="height:33%;">
        <div class="col-xs-12 v-align">
            <img id="login-logo" class="img-responsive logo" src="<?php echo HTTP_MEDIA;?>/site-image/ifes.jpg">
        </div>
    </div>
    <div class="row" style="height:67%">
        <div class="col-xs-11" style="top:16%;transform:translateY(-33%)" >
            <form id="form-login" name="form-login" enctype="multipart/form-data" method="post" class="form-signin">
                <h5 class="form-signin-heading">Please sign in</h5>
                <label class="sr-only" for="inputEmail">Email address</label>
                <input style="margin-bottom:5px;" type="text"  placeholder="Email address" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
                <label class="sr-only" for="inputPassword">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" class="form-control" >
                <div class="checkbox">
                <label><input type="checkbox" id="remember" name="remember" <?php if($checkedRememberMe){echo 'checked';} ?>>Remember me</label>
                </div>
                <button type="submit" class="btn btn-md btn-primary btn-block">Sign in</button>
                <br/>
                <a href="<?php echo getModuleURL('oz.lostpass.bo'); ?>">Forgot your password?</a><br>
                <a href="http://www.polarisnet.com.my/contactus.html" style="position: relative; top: 5px;">Sign up for a new account</a>
            </form>
        </div>
    </div>
</div>
</div>
*/ ?>              
                    