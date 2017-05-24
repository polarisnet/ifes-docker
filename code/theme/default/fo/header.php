<div class="flat-header aa-bs">
	<div class="navbar yamm navbar-default navbar-fixed-top" >
      <div>
        <div class="navbar-header" style="height:50px;">
          <img class="visible-xs" style="max-height: 50px;float:right" src="<?php echo getDefaultPicture($_SESSION['uid']); ?>"><button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="<?php echo getModuleURL('oz.dashboard'); ?>"><img src="<?php echo HTTP_MEDIA;?>/site-image/polarisnet2.jpg"></a>
          
        </div>
        <div id="navbar-collapse-1" class="navbar-collapse collapse yamm-aw">
          <ul class="nav navbar-nav yamm-aw" data-dropdown-in="fadeIn">
            <?php echo loadHeaderTemplate('mega-menu-1', ''); ?>
              <?php echo loadSpecificHeaderTemplate('...'); ?>
          </ul>
            <ul class="nav navbar-nav navbar-right hidden-xs" style="margin-right: -15px;">
                <li>
            <img style="max-height: 50px;" src="<?php echo getDefaultPicture($_SESSION['uid']); ?>">
                </li>
            </ul>
          <ul class="nav navbar-nav navbar-right"  data-dropdown-in="fadeIn">
				<li class="dropdown">
                  <a class="mainMod dropdown-toggle" href="#" data-toggle="dropdown">
                      <?php
                      $siteNavDispName = "";
                      if(strlen($_SESSION['user_fname']) > 10) {
                          $siteNavDispName = substr($_SESSION['user_fname'],0,8)."..";
                      }else {
                          $siteNavDispName = $_SESSION['user_fname'];
                      }
                      ?>
                      <?php echo $siteNavDispName; ?>
                      
                  </a>
                <ul class="dropdown-menu pus">
                    <?php if(CLOUD_MODE){ ?>
                    <li><a href="<?php echo HTTP_CONSOLE."/app/account"; ?>" tabindex="0" target="_blank">View Profile</a></li>
                    <li><a href="<?php echo HTTP_CONSOLE."/app/account/change_password"; ?>" tabindex="0" target="_blank">Change Password</a></li>
                    <li><a href="<?php echo HTTP_CONSOLE; ?>" tabindex="0">Go to Console</a></li>
                    <li><a href="<?php echo HTTP_CONSOLE."/login?action=logout"; ?>" tabindex="0">Log Out</a></li>
                    <?php }else{ ?>
                    <li><a href="<?php echo getModuleURL('oz.message'); ?>" tabindex="0">Message</a></li>
                    <li><a href="<?php echo getModuleURL('oz.system.settings.profile.edit'); ?>" tabindex="0">Edit Profile</a></li>
                    <li><a href="<?php echo getModuleURL('oz.system.settings.profile.changepassword'); ?>" tabindex="0">Change Password</a></li>
                    <li><a href="<?php echo HTTP_SERVER.HTTP_ROOT.SITE_BO_LOGIN; ?>?action=logout" tabindex="0">Log Out</a></li>
                    <?php } ?>
                </ul>
              </li>
              <li class="visible-xs" style="line-height: 10px;padding:5px 15px">
                  <img id="leftbar-toggle-img-navbar" src="<?php if($setting["left_maximize"] == 0){echo HTTP_MEDIA."/site-image/next_white.png";}else {echo HTTP_MEDIA."/site-image/back_white.png";} ?>" width="26px;" height="26px;" onclick="javascript:$('.navbar-collapse').collapse('hide'); toggleLeftBar();" onmouseover="javascript: toggleLeftBarImg(true);" onmouseout="javascript: toggleLeftBarImg(false);">
              </li>
            </ul>
<!--            <div id="haveDownbar" class="visible-xs" style="display:none;bottom:0px;position:absolute;background-color:white;border-top:1px solid #f3f3f3 ; margin-left: -15px;width: 100%;height:20px;text-align: center">
                <span id="haveDownbarIcon" class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
            </div>-->
        </div>
        </div>
            <div id="haveDownbar" class="hidden-lg hidden-md hidden-sm" style="position:fixed;margin-top:-20px;display:none;background-color:white;border-top:1px solid #f3f3f3 ; width: 100%;height:20px;text-align: center">
                <span id="haveDownbarIcon" class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
            </div>  
      </div>
            
    </div>
</div>
<div style="clear:both;"></div>