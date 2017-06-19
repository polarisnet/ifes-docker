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
		<p class="register-title">Sign up for your Donor Profile</p>
		<div class="row visible-xs-block"><br></div>
		<div class="row" style="float: right; margin-top: -20px;">
			<div class="col-xs-12 text-right"><span style="font-size: 12px; color:grey;">*REQUIRED<span></div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
				<label class="label-register" class="label-register" class="label-register" for="register-input-firstname">FIRST NAME(S)*</label class="label-register">
				<input type="text" id="register-input-firstname" name="register-input-firstname" 
				class="form-control" placeholder="First Name" value="<?php echo $formNameFirst; ?>">
			</div>
			<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
				<label class="label-register" class="label class="label-register"-register" for="register-input-lastname">LAST NAME*</label>
				<input type="text" id="register-input-lastname" name="register-input-lastname" 
				class="form-control" placeholder="Last Name" value="<?php echo $formNameLast; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 10px;">
			<label class="label-register" class="label-register" class="label-register" for="register-input-spouse">SPOUSE'S NAME (OPTIONAL)</label class="label-register">
				<input type="text" id="register-input-spouse" name="register-input-spouse" 
				class="form-control" placeholder="Spouse's Name" value="<?php echo $formNameSpouse; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 10px;">
				<label class="label-register" class="label-register" for="register-input-address1">ADDRESS 1*</label>
				<input type="text" id="register-input-address1" name="register-input-address1" 
				class="form-control" placeholder="Address 1" value="<?php echo $formAddress1; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 10px;">
				<label class="label-register" class="label-register" for="register-input-address2">ADDRESS 2*</label>
				<input type="text" id="register-input-address2" name="register-input-address2" 
				class="form-control" placeholder="Address 2" value="<?php echo $formAddress2; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
				<label class="label-register" class="label-register" for="register-input-city">TOWN/CITY*</label>
				<input type="text" id="register-input-city" name="register-input-city" 
				class="form-control" placeholder="City" value="<?php echo $formCity; ?>">
			</div>
			<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
				<label class="label-register" class="label-register" for="register-input-state">REGION/STATE/PROVINCE*</label>
				<input type="text" id="register-input-state" name="register-input-state" 
				class="form-control" placeholder="State/Province" value="<?php echo $formState; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
				<label class="label-register" class="label-register" for="register-input-zipcode">POSTCODE/ZIP CODE*</label>
				<input type="text" id="register-input-zipcode" name="register-input-zipcode" 
				class="form-control" placeholder="Zip/Postal Code" value="<?php echo $formZIP; ?>">
			</div>
			<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
				<label class="label-register" class="label-register" for="register-input-country">COUNTRY*</label>
				<select id="register-input-country" name="register-input-country" class="selectpicker" data-size="8" data-none-selected-text="Country">
					<?php foreach($listCountries AS $countryData){ ?>
						<option value="<?php echo $countryData['iso']; ?>" <?php if($countryData['iso'] == $formCountry){echo 'selected';}?>><?php echo $countryData['name']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 10px;">
				<label class="label-register" class="label-register" for="register-input-telephone">TELEPHONE*</label>
				<div class="row">
					<div class="col-xs-12 col-xs-3" style="padding-right: 0px;">
						<select id="register-input-telephone" name="register-input-telephone" class="selectpicker" 
						data-size="8" data-none-selected-text="Telephone" onchange="toggleTelephoneInput(this.value)">
								<option value="mobile" >Mobile</option>
								<option value="daytime" >Daytime</option>
								<option value="evening" >Evening</option>
						</select>
					</div>
					<div class="col-xs-12 col-xs-9" style="padding-left: 0px;">
						<input type="text" id="register-input-mobile" name="register-input-mobile" style="height: 34.4px;"
						class="form-control" placeholder="+(555) 555-5555" value="<?php echo $formTelephoneMobile; ?>">
						<input type="text" id="register-input-daytime" name="register-input-daytime" style="height: 34.4px;"
						class="form-control" placeholder="+(555) 123-5555" value="<?php echo $formTelephoneDaytime; ?>">
						<input type="text" id="register-input-evening" name="register-input-evening" style="height: 34.4px;"
						class="form-control" placeholder="+(555) 456-5555" value="<?php echo $formTelephoneEvening; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 10px;">
				<label class="label-register" class="label-register" for="register-input-email">EMAIL*</label>
				<input type="text" id="register-input-email" name="register-input-email" 
				class="form-control" placeholder="Email" value="<?php echo $formEmail; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 10px;">
				<label class="label-register" class="label-register" for="register-input-password-new">PASSWORD*</label>
				<input type="password" id="register-input-password-new" name="register-input-password-new" 
				class="form-control" placeholder="Password" value="<?php echo $formPassword; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 10px;">
				<label class="label-register" class="label-register" for="register-input-password-confirm">CONFIRM PASSWORD*</label>
				<input type="password" id="register-input-password-confirm" name="register-input-password-confirm" 
				class="form-control" placeholder="Re-type Password" value="<?php echo $formRetypePassword; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 text-left">
				<div class="g-recaptcha" data-sitekey="6LfxYyIUAAAAAMYHZ4MFYkqp5cJwN-IKTQ36DTRR"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 text-center">
				<button type="submit" class="btn btn-default btn-ifes" style="width: 150px;" name="submit_mode" value="new">SUBMIT</button>
				<br><br>
			</div>	
		</div>
		
	</form>
</div>
<script type="text/javascript">
	function toggleTelephoneInput(type){
		$('#register-input-mobile, #register-input-daytime, #register-input-evening').hide();
		$('#register-input-'+type).show();
	}
	
	function validateForm(){


	}
	
	$(document).ready(function(){
		toggleTelephoneInput('mobile');
	});
</script>