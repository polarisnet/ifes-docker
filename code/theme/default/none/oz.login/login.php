<body>
		<div class="container" style="position: relative;">
			<form class="form-vertical form-login" role="form" method="post" style="background: #ffffff; margin: 10% auto; width: 400px; text-align: center; border: 1px solid #dddddd;">
				<br>
				<img src="<?php echo HTTP_MEDIA.'/site-image/ifes-logo.png'; ?>" width=80>
				<p style="position: relative; top: 8px;">Login and manage your donor account</p>
				<div class="row" style="margin-left: 0; margin-right: 0;">
					<div class="form-group form-hr" style="width: 360px; margin: auto; padding-bottom: 10px;"><hr></div>
					<div class="form-group"><input type="text" class="form-control" id="username" name="username" placeholder="Email Address" value=""></div>
					<div class="form-group"><input type="password" class="form-control" id="password" name="password" placeholder="Password"></div>
				</div>
				<div class="row" style="margin-left: 0; margin-right: 0; text-align: center; padding-top: 12px; padding-bottom: 12px;">
					<div class="col-xs-12 col-login"><button type="submit" class="btn btn-default">LOGIN</button></div>
				</div>
				<div class="row" style="margin-left: 0; margin-right: 0; text-align: left;">
					<div class="col-xs-6 col-signup"><input type="checkbox" name="remember" <?php if($rememberOn){echo "checked";} ?>> Remember Me</div>
					<div class="col-xs-6 col-forget"><a>Forgot your password?</a></div>
				</div>
				<br>
			</form>
		</div>
	</body>
		<style>
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
