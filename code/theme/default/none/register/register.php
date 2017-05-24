<script src='https://www.google.com/recaptcha/api.js'></script>
<style>
	.aa-bs {
	   height: 100%;
	}
	
	.progress{
		position: relative;
		top: 5px;
	}

	body{
		background-image: url('<?php echo HTTP_ACTIVE_PUBLIC_THEME; ?>/world-map-color-lg.png');
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-position: center;
		background-color: #f7f7f5;
	}
</style>

<div class="container" style="padding-top: 10px; padding-bottom: 10px; width:75%; background-color: #ffffff;">
	<form class="form-horizontal" role="form" method="post" style="" onsubmit="javascript: return validateForm();">
		<h4>Sign up for your Donor Profile</h4>
		<div class="row visible-xs-block"><br></div>
		<div class="row" style="float: right; margin-top: -20px;">
			<div class="col-xs-12 text-right header-required">Required Information</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-12 col-md-8">
				<div class="form-group required">
					<label class="col-xs-4 control-label">Username</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" value="<?php //echo stripcslashes($formCompanyName); ?>" >
					</div>
				</div>
				
				<div class="form-group required">
					<label class="col-xs-4 control-label">Email Address</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="email_address" name="email_address" placeholder="Enter your email address" value="<?php //echo $formFirstName; ?>">
					</div>
				</div>

				<div class="form-group required">
					<label class="col-xs-4 control-label">Password</label>
					<div class="col-xs-8">
						<input type="password" class="form-control" id="password" name="password"  value="<?php //echo $formEmail; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-4 text-center">
				<button type="submit" class="btn btn-default">SUBMIT</button>
				<br><br>
			</div>
			<div class="col-xs-12 col-md-8 text-center">
				<div class="g-recaptcha" data-sitekey="6LfxYyIUAAAAAMYHZ4MFYkqp5cJwN-IKTQ36DTRR"></div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	function changeHomeDirectory(val){
		val = val.toLowerCase();
		val = val.replace(/ /g, '_');
		val = val.replace(/[^\w\s]/gi, '');
		$('#sample-url').html(val);
		$('#url').val(val);
	}

	function validateForm(){
		if(bootstrapValidateEmpty("username", "Username")){return false;}
		if(bootstrapValidateEmpty("email_address", "Email Address")){return false;}
		if(bootstrapValidateEmpty("password", "Password")){return false;}

		if(!$("#agree_tnc").is(":checked")){
			$("#agree_tnc").focus();
			noty({text: "Please read and agree to the Terms and Conditions of Service before submitting this registration form.", type: 'error'});
			return false;
		}
	}
</script>