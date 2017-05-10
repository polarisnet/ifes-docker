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
  </form>
    </div>
    </div>
</div>
</div>