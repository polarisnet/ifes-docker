<style type="text/css">
	.social-header a.social-link.fb {
		background-image: url(<?php echo HTTP_MEDIA.'/site-image/fb-icon.png'; ?>);
	}
	.social-header a.social-link.tw {
		background-image: url(<?php echo HTTP_MEDIA.'/site-image/tw-icon.png'; ?>);
	}
	.social-header a.social-link.ig {
		background-image: url(<?php echo HTTP_MEDIA.'/site-image/ig-icon.png'; ?>);
	}
	.container.donor-header{
		background-image: url(<?php echo HTTP_MEDIA.'/site-image/ifes-center-ring.png'; ?>);
		background-repeat: no-repeat;
		background-position: center top;
		padding-top: 20px;
	}
</style>
<div class="container no-gutters donor-header">
		<div class="col-xs-12 col-md-12">
			<div class="social-header">
			<a class="social-link fb" href="http://www.facebook.com/ifesworld" title="Find us on Facebook"></a>
			<a class="social-link tw" href="http://www.twitter.com/ifes" title="Follow us on Twitter"></a>
			<a class="social-link ig" href="https://instagram.com/ifesworld" title="Check out our Instagram Photos"></a>
		</div>
		<img src="<?php echo HTTP_MEDIA.'/site-image/ifes-logo.png';?>" width="131" style="margin-top: -1px; margin-bottom: 28px;">
		<p class="pg-title">MY ACCOUNT</p>
		<p class="donor-content-title blackcurrant" style="display:inline-block"><?php echo strtoupper($donorName) ?></p>
		<button type="button" class="btn btn-default btn-ifes" style="float:right" onclick="location.href='<?php echo HTTP_SERVER.HTTP_ROOT.SITE_FO_LOGIN.'?action=logout';?>';">LOG OUT</button>
		<p class="donor-content-title blackcurrant">Account Number: <?php echo $donorAccountNumbers ?></p>
		
	</div>
</div>
<div class="container ifes-content">
	<table class="donor-table">
		<tr class="header">
			<td id="toggle-profile-header-dashboard" onclick="toggleDonorProfileHeader('dashboard');">Dashboard</td>
			<td id="toggle-profile-header-settings" onclick="toggleDonorProfileHeader('settings');">Settings</td>
			<td id="toggle-profile-header-giving" onclick="toggleDonorProfileHeader('giving');">My Giving</td>
		</tr>
		<tr>
			<td colspan="4" style="padding: 15px; font-size: 16px; font-family: 'Muli', Helvetica; height: 500px; vertical-align: top;">
				<div id="donor-profile-dashboard" style="display: none;">
					<div class="row no-gutters">
						<p class="donor-content-title blackcurrant" style="padding-left: 15px">Welcome back, <?php echo ucfirst($formNameFirst)."!"; ?></p>
						<div class="col-xs-12 col-md-8 no-gutters">
							<div>
								<div class="row no-gutters">
									<div class="col-xs-12 col-md-12" style="margin-bottom:30px">
										<div class="content-white">
											<p class="donor-content-title text-center">Your Activity</p>
											<hr>
											<p class="donor-content-regular-bold" style="font-size: 16px;">You've given <?php echo $total." since ".$firstDate?></p> <!-- TODO:Currency(?) -->
											<table class="table table-hover donor-table-content">
												<thead> <!-- todo: get from database-->
													<tr>
														<th colspan="4">Recent Donations</th>
													</tr>
												</thead>
												<?php echo $recentGivingTemplate; ?>
											</table>
											<p style="float:right">View your <span class= "span-link" onclick="toggleDonorProfileHeader('giving')" >giving history</span></p>
											<div style="padding:15px"></div>
										</div>
									</div>
									<div class="col-xs-12 col-md-12" style="margin-bottom:30px">
										<div class="content-white">
											<p class="donor-content-title text-center">Your News Feed</p>
											<hr>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-4 no-gutters">
							<div>
								<div class="row no-gutters">
									<div class="col-xs-12 col-md-12" style="margin-bottom:30px">
										<div class="content-white">
											<p class="donor-content-title text-center">Your Profile</p>
											<hr>
											<div style="padding: 10px;"> </div>
											<p class="donor-content-title"><?php echo $donorName ?></p>
											<p class="donor-content-regular"><?php echo $fullAddress ?></p>
											<p class="donor-content-regular"><?php echo $fullContact ?></p>
											<p class="donor-content-regular"><?php echo $fullEmail ?></p>
											<p style="text-align:right"><span class= "span-link" style = "font-size: 12px" onclick="toggleDonorProfileHeader('settings');" >MODIFY</span></p>
											<div style="padding: 10px;"> </div>
										</div>
									</div>
									<div class="col-xs-12 col-md-12" style="margin-bottom:30px">
										<div class="content-white quick-links" style="height: 100%">
											<p class="donor-content-title text-center">Quick Links</p>
											<div style="padding: 5px;"></div>
											<a href=<?php echo HTTP_SERVER.HTTP_ROOT."/giving"?> >Give Now</a><br>
											<div style="padding: 5px;"></div>
											<a href="#" >Help</a><br>
											<div style="padding: 5px;"></div>
											<a href="#" >FAQs</a><br>
											<div style="padding: 5px;"></div>
											<a href="#" >Contact</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="donor-profile-settings" style="display: none;">
					<div class="row no-gutters" style="overflow: hidden;margin-bottom: 30px;">
						<div class="col-xs-12 col-md-8" style="margin-top: 30px;">
								<div>
									<div class="content-white">
										<form id="donor-profile-update" class="form-vertical" role="form" method="post" onsubmit="return validateForm();">
											<p class="donor-content-title text-center">Update Profile</p>
											<hr>
											<div class="row">
												<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
													<label for="donor-profile-input-firstname">FIRST NAME(S)*</label>
													<input type="text" id="donor-profile-input-firstname" name="donor-profile-input-firstname" 
													class="form-control" placeholder="First Name" value="<?php echo $formNameFirst; ?>">
												</div>
												<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
													<label for="donor-profile-input-lastname">LAST NAME*</label>
													<input type="text" id="donor-profile-input-lastname" name="donor-profile-input-lastname" 
													class="form-control" placeholder="Last Name" value="<?php echo $formNameLast; ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12" style="padding-bottom: 10px;">
												<label for="donor-profile-input-spouse">SPOUSE'S NAME (OPTIONAL)</label>
													<input type="text" id="donor-profile-input-spouse" name="donor-profile-input-spouse" 
													class="form-control" placeholder="Spouse's Name" value="<?php echo $formNameSpouse; ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12" style="padding-bottom: 10px;">
													<label for="donor-profile-input-address1">ADDRESS 1*</label>
													<input type="text" id="donor-profile-input-address1" name="donor-profile-input-address1" 
													class="form-control" placeholder="Address 1" value="<?php echo $formAddress1; ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12" style="padding-bottom: 10px;">
													<label for="donor-profile-input-address2">ADDRESS 2*</label>
													<input type="text" id="donor-profile-input-address2" name="donor-profile-input-address2" 
													class="form-control" placeholder="Address 2" value="<?php echo $formAddress2; ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
													<label for="donor-profile-input-city">TOWN/CITY*</label>
													<input type="text" id="donor-profile-input-city" name="donor-profile-input-city" 
													class="form-control" placeholder="City" value="<?php echo $formCity; ?>">
												</div>
												<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
													<label for="donor-profile-input-state">REGION/STATE/PROVINCE*</label>
													<input type="text" id="donor-profile-input-state" name="donor-profile-input-state" 
													class="form-control" placeholder="State/Province" value="<?php echo $formState; ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
													<label for="donor-profile-input-zipcode">POSTCODE/ZIP CODE*</label>
													<input type="text" id="donor-profile-input-zipcode" name="donor-profile-input-zipcode" 
													class="form-control" placeholder="Zip/Postal Code" value="<?php echo $formZIP; ?>">
												</div>
												<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
													<label for="donor-profile-input-country">COUNTRY*</label>
													<select id="donor-profile-input-country" name="donor-profile-input-country" class="selectpicker" data-size="8" data-none-selected-text="Country">
														<?php foreach($listCountries AS $countryData){ ?>
															<option value="<?php echo $countryData['iso']; ?>" <?php if($countryData['iso'] == $formCountryISO){echo 'selected';}?>><?php echo $countryData['name']; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12" style="padding-bottom: 10px;">
													<label for="donor-profile-input-telephone">TELEPHONE*</label>
													<div class="row">
														<div class="col-xs-12 col-xs-3" style="padding-right: 0px;">
															<select id="donor-profile-input-telephone" name="donor-profile-input-telephone" class="selectpicker" 
															data-size="8" data-none-selected-text="Telephone" onchange="toggleTelephoneInput(this.value)">
																	<option value="mobile" >Mobile</option>
																	<option value="daytime" >Daytime</option>
																	<option value="evening" >Evening</option>
															</select>
														</div>
														<div class="col-xs-12 col-xs-9" style="padding-left: 0px;">
															<input type="text" id="donor-profile-input-mobile" name="donor-profile-input-mobile" style="height: 34.4px;"
															class="form-control" placeholder="+(555) 555-5555" value="<?php echo $formTelephoneMobile; ?>">
															<input type="text" id="donor-profile-input-daytime" name="donor-profile-input-daytime" style="height: 34.4px;"
															class="form-control" placeholder="+(555) 123-5555" value="<?php echo $formTelephoneDaytime; ?>">
															<input type="text" id="donor-profile-input-evening" name="donor-profile-input-evening" style="height: 34.4px;"
															class="form-control" placeholder="+(555) 456-5555" value="<?php echo $formTelephoneEvening; ?>">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12" style="padding-bottom: 10px;">
													<label for="donor-profile-input-email">EMAIL*</label>
													<input type="text" id="donor-profile-input-email" name="donor-profile-input-email" 
													class="form-control" placeholder="Email" value="<?php echo $formEmail; ?>">
												</div>
											</div>
											<div style="text-align: right">
												<span style="font-size: 12px; color:grey;">*REQUIRED<span>
											</div>
											<div>
												<input type="hidden" name="add-billing" value="0" />
												<label class="checkbox-inline bill-checkbox"><input type="checkbox" name="add-billing" id="gift-add-billing" value="1" style="margin-top: 4px;" onclick="$('#donor-cc-form-billing').toggle();">Add a billing address</label>
											</div>
											<div id="donor-cc-form-billing" style="display: none;">
												<div style="text-align: center; padding-top: 20px; font-family: 'Muli', Helvetica;">
													<div class="col-xs-12 no-gutters" style="padding-bottom: 10px;">
														<input type="text" id="donor-billing-input-address1" name="donor-billing-input-address1" 
														class="form-control" placeholder="Address 1" value="<?php echo $formBillAddress1; ?>">
													</div>
													<div class="col-xs-12 no-gutters" style="padding-bottom: 10px;">
														<input type="text" id="donor-billing-input-address2" name="donor-billing-input-address2" 
														class="form-control" placeholder="Address 2" value="<?php echo $formBillAddress2; ?>">
													</div>
													<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-right: 5px;">
														<input type="text" id="donor-billing-input-city" name="donor-billing-input-city" 
														class="form-control" placeholder="City" value="<?php echo $formBillCity; ?>">
													</div>
													<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-left: 5px;">
														<input type="text" id="donor-billing-input-state" name="donor-billing-input-state" 
														class="form-control" placeholder="State/Province" value="<?php echo $formBillState; ?>">
													</div>
													<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-right: 5px;">
														<input type="text" id="donor-billing-input-zipcode" name="donor-billing-input-zipcode" 
														class="form-control" placeholder="Zip/Postal Code" value="<?php echo $formBillZIP; ?>">
													</div>
													<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-left: 5px;">
														<select id="donor-billing-input-country" name="donor-billing-input-country" 
														class="selectpicker" data-size="8" data-none-selected-text="Country">
															<?php foreach($listCountries AS $countryData){ ?>
																<option value="<?php echo $countryData['iso']; ?>" <?php if($countryData['iso'] == $formBillCountry){echo 'selected';}?>><?php echo $countryData['name']; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>
											<div style="text-align:right">
												<button type="submit" class="btn btn-default btn-ifes" name="submit_mode" value="profile_update" >SAVE</button>
											</div>
										</form>
									</div>
								</div>
						</div>
						<div class="col-xs-12 col-md-4 row-eq-height" style="margin-top: 30px;">
							<div>
								<div class="content-white">
									<form class="form-vertical" role="form" method="post">
										<p class="donor-content-title text-center">Change Password</p>
										<p style="font-size: 14px; color: grey;">Passwords are case-sensitive and must be at least 6 characters. <br><br>
										A good passsword should contain a mix of capital and lower-case letters, numbers and symbols</p>
										<input type="password" id="donor-password-input-current" name="donor-password-input-current" class="form-control" placeholder="Current Password">
										<div style="padding:5px"></div>
										<input type="password" id="donor-password-input-new" name="donor-password-input-new" class="form-control" placeholder="New Password">
										<div style="padding:5px"></div>
										<input type="password" id="donor-password-input-confirm" name="donor-password-input-confirm" class="form-control" placeholder="Confirm New Password">
										<div style="padding:5px"></div>
										
										<div style="text-align:right">
											<button type="submit" class="btn btn-default btn-ifes" name="submit_mode" value="reset_password">SAVE</button>
										</div>
									</form>
								</div>
								<div style="padding:15px"></div>
								<div class="content-white quick-links" style="height: 100%">
									<p class="donor-content-title text-center">Quick Links</p>
									<div style="padding: 5px;"></div>
									<a href=<?php echo HTTP_SERVER.HTTP_ROOT."/giving"?> >Give Now</a><br>
									<div style="padding: 5px;"></div>
									<a href="#" >Help</a><br>
									<div style="padding: 5px;"></div>
									<a href="#" >FAQs</a><br>
									<div style="padding: 5px;"></div>
									<a href="#" >Contact</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row no-gutters"> 
						<div class="col-xs-12 col-md-12">
							<div class="content-white">
								<form class="form-vertical" role="form" method="post">
									<p class="donor-content-title text-center">Communication Preference</p>
									<hr>
									<div class="row no-gutters">
										<div class="col-xs-12 col-md-9 no-gutters" >
											<p class="donor-content-subtitle">My Subscriptions</p>
											<div class="row no-gutters">
												<div>
													<label class="donor-checkbox">
													<input type="checkbox" name="subscriptions[]" id="donor-subscriptions-1" value="praise_prayer" 
													<?php if(isset($praise_prayer)){echo 'checked';}?> style="margin-top: 4px;">
													Praise & Prayer: a bimonthly email with Daily Prayer Guide <span style="font-size:10px">(English only)</span></label>
													<div style="margin-left:30px;margin-bottom:10px;">
														<label class="donor-checkbox-inline">
														  <input type="checkbox" style="margin-top: 4px;" name="donor-prayer-email" id="donor-support-email" value="1"> email
														</label>
														<label class="donor-checkbox-inline">
														  <input type="checkbox" style="margin-top: 4px;" name="donor-prayer-post" id="donor-support-post" value="1"> post
														</label>
													</div>
													<label class="donor-checkbox">
													<input type="checkbox" name="subscriptions[]" id="donor-subscriptions-2" value="prayerline" 
													<?php if(isset($prayerline)){echo 'checked';}?> style="margin-top: 4px;">
													Prayerline: a weekly email snapshot of the IFES world to inspire your prayers</label>
													<label class="donor-checkbox">
													<input type="checkbox" name="subscriptions[]" id="donor-subscriptions-3" value="conexion" 
													<?php if(isset($conexion)){echo 'checked';}?> style="margin-top: 4px;">
													Conexión: a monthly online magazine connecting the IFES World</label>
													<label class="donor-checkbox">
													<input type="checkbox" name="subscriptions[]" id="donor-subscriptions-4" value="voix" 
													<?php if(isset($voix)){echo 'checked';}?> style="margin-top: 4px;">
													Voix: a weekly blog, by students and for students</label>
												</div>
											</div>
											<?php  if(REGION == 'uk'){ // START REGION == 'uk' ?>
												<div class="row no-gutters">
													<div style="padding:2.5px;"></div>
													<p class="donor-paragraph-support">I would like to received:</p>
													<div style="padding:5px;"></div>
													<label class="donor-checkbox">
													<input type="checkbox" name="donor-support-prime" id="donor-support-prime" value="1" 
													<?php if(isset($supportPrime)){echo 'checked';}?> style="margin-top: 4px;">
													Updates and oppurtunities to support IFES ministries</label>
													<div style="margin-left:30px;">
														<label class="donor-checkbox-inline">
														  <input type="checkbox" style="margin-top: 4px;" name="donor-support-email" id="donor-support-email" value="1"> email
														</label>
														<label class="donor-checkbox-inline">
														  <input type="checkbox" style="margin-top: 4px;" name="donor-support-post" id="donor-support-post" value="1"> post
														</label>
														<label class="donor-checkbox-inline">
														  <input type="checkbox" style="margin-top: 4px;" name="donor-support-phone" id="donor-support-phone" value="1"> phone
														</label>
													</div>
												</div>
											<?php } // END REGION == 'uk'?>
										</div>
										<div class="col-xs-12 col-md-3 no-gutters" >
											<p class="donor-content-subtitle">Preferred Language</p>
											<div>
												<div class="radio">
													<label class="donor-radio-bold" ><input type="radio" name="radio-language" id="donor-language-english" value="english" 
													<?php if($formLanguage == 'english'){echo 'checked';}?> style="margin-top: 4px;">English</label>
												</div>
												<div class="radio">
													<label class="donor-radio-bold" ><input type="radio" name="radio-language" id="donor-language-france" value="france" 
													<?php if($formLanguage == 'france'){echo 'checked';}?> style="margin-top: 4px;">Français</label>
												</div>
												<div class="radio">
													<label class="donor-radio-bold" ><input type="radio" name="radio-language" id="donor-language-spanish" value="spanish" 
													<?php if($formLanguage == 'spanish'){echo 'checked';}?> style="margin-top: 4px;">Español</label>
												</div>
											</div>
										</div>
									</div>
									<div style="padding: 15px;"></div>
									<div style="text-align:right">
										<button type="submit" class="btn btn-default btn-ifes" name="submit_mode" value="comm_preference">SAVE</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div id="donor-profile-giving" style="display: none;">
					<div class="row no-gutters">
						<p class="donor-content-title blackcurrant" style="padding: 15px 15px;display: inline-block;margin-bottom: 0px;">Thank you for giving!</p>
						<p class="donor-content-regular" style="float: right;padding: 20px 15px 0px 0px;">Contact <span class="span-link">donor services</span> for additional information or assitance.</p>
					</div>
					<div class="row no-gutters">
						<div class="col-xs-12 col-md-12">
							<div class="content-white">
								<p class="donor-content-title text-center">Giving History</p>
								<p class="donor-content-regular">Access your most recent giving history here. Giving details prior to 2013 are available by <span class="span-link">request.</span></p>
								<div class="row no-gutters vertical-align">
									<div class="col-xs-4 col-md-2 no-gutters" >
										<p class="donor-content-regular">Select date range</p>
									</div>
									<div class="col-xs-8 col-md-3 no-gutters">
										<div class="form-group">
											<div class='input-group date' id='giving_date_start'>
												<input type='text' class="form-control" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									<div class="col-xs-4 col-md-1 no-gutters">
										<p class="donor-content-regular text-center">to</p>
									</div>
									<div class="col-xs-8 col-md-3 no-gutters">
										<div class="form-group">
											<div class='input-group date' id='giving_date_end'>
												<input type='text' class="form-control" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table id="giving-grid" class="table table-hover dt-responsive" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>DESIGNATION</th>
												<th>AMOUNT</th>
												<th>DONATION DATE</th>
												<th>PAYMENT TYPE</th>
												<th></th>
												<th></th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-12" style="margin-top: 30px;">
							<div class="content-white">
								<p class="donor-content-title text-center">Monthly Giving</p>
								<div style="padding: 15px;"></div>
								<div class="table-responsive">
									<table id="subscription-grid" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>DESIGNATION</th>
												<th>AMOUNT</th>
												<th>DONATION DATE</th>
												<th>PAYMENT TYPE</th>
												<th></th>
												<th></th>
											</tr>
										</thead>
									</table>
								</div>
								<div style="text-align: right"><a href=<?php echo HTTP_SERVER.HTTP_ROOT."/giving"?> ><span class="span-link">ADD NEW</span></a></div>
							</div>	
						</div>
						<div class="col-xs-12 col-md-12" style="margin-top: 30px;">
							<div class="content-white">
								<p class="donor-content-title text-center">Payment Methods</p>
								<div style="padding: 15px;"></div>
								<div class="table-responsive">
									<table id="payment-grid" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>CUSTOM NAME</th>
												<th>TYPE</th>
												<th>CARD NUMBER</th>
												<th>EXPIRATION</th>
												<th></th>
												<th></th>
											</tr>
										</thead>
									</table>
								</div>
								<div style="text-align: right"><span class="span-link" data-toggle="modal" data-target="#payment-modal" onclick="toggleModalPayment('new', '')">ADD NEW</span></div>
							</div>
						</div>
						<?php  if(REGION == 'us'){ // START REGION == 'us' ?>
						<div class="col-xs-12 col-md-12" style="margin-top: 30px;">
							<div class="content-white">
								<p class="donor-content-title text-center">Annual Giving Statements</p>
								<hr>
								<p class="text-center">Print your tax-deductible annual giving statements from the past four years.</p>
								<div class="form-group" style="width:300px;margin: auto;">
									<select id="giving-input-statement" name="giving-input-statement" class="selectpicker" 
									data-size="8" data-none-selected-text="Annual Giving">
										<?php
										$year = date("Y");
										
										for($i=1; $i <= 4; ++$i){
											--$year;
											echo "<option>".$year." Annual Giving</option>";
										}
										?>
										
									</select>
								</div>
								<p class="text-center">The current year's receipt is available after the end of the year. Annual receipts prior to 2013 are available by <span class= "span-link">request</span>.</p>
							</div>
						</div>
						<?php }// END REGION == 'us'?>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
<br><br>

<!-- Modal -->
<div class="modal fade" id="payment-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content donor-custom">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding-right: 5px;">
				<span aria-hidden="true">&times;</span>
			</button>
			<div style="padding:30px 30px">
				<div class="modal-header donor-custom">
					<h5 class="modal-title donor-custom" name="modal-payment-new">Add Payment Method</h5>
					<h5 class="modal-title donor-custom" name="modal-payment-update">Modify Payment Method</h5>
					<div style="padding:10px;"></div>
				</div>
				<div style="padding-bottom:10px;"></div>
				<?php if(REGION == 'us'){ ?>
					<div class="row" name="modal-payment-new" style="padding:10px;">
						<div class="col-xs-6">
							<button type="button" id="btn-us-payment-cc" class="btn btn-default btn-ifes <?php if($formPaymentUSPaymode == "cc"){echo 'btn-ifes-active';} ?>" style="margin-right: 15px;width: 100%;" onclick="toggleUSPayment('cc', this);">ADD CREDIT OR DEBIT CARD</button>
						</div>
						<div class="col-xs-6">
							<button type="button" id="btn-us-payment-check" class="btn btn-default btn-ifes <?php if($formPaymentUSPaymode == "check"){echo 'btn-ifes-active';} ?>" style="width: 100%;" onclick="toggleUSPayment('check', this);">ADD eCHECK</button>
						</div>
						<input type="hidden" id="payment-us-paymode" name="payment-us-paymode" value="<?php echo $formPaymentUSPaymode; ?>">
					</div>
				<?php } ?>
				<div class="modal-subtitle" name="modal-payment-new">
					<p style="display: inline-block;">We accept major cards:</p>
					<img src="<?php echo HTTP_MEDIA.'/site-image/cards.png';?>" style="margin-left: 5px;">
				</div>
				
				<form id="donor-payment-modal" class="form-vertical" role="form" method="post">
					<input type="hidden" name="payment-cc-id" value=""> 
					<div class="modal-body donor-custom">
						<div class="container-fluid no-gutters payment-form">
							<div class="row" id="payment-form-cc">
								<div class="col-xs-12" style="padding-bottom: 10px;">
									<label for="payment-cc-customname">CUSTOM NAME</label>
									<input type="text" name="payment-cc-customname" id="payment-cc-customname" class="form-control" placeholder="Custom Name">
								</div>
								<div class="col-xs-12" style="padding-bottom: 10px;">
									<label for="payment-cc-number">CARD NUMBER</label>
									<input type="text" name="payment-cc-number" id="payment-cc-number" class="form-control" placeholder="Card Number">
								</div>
								<div class="col-xs-12" style="padding-bottom: 10px;">
									<label for="payment-cc-name">NAME ON CARD</label>
									<input type="text" name="payment-cc-name" id="payment-cc-name" class="form-control" placeholder="Name on Card">
								</div>
								<div class="col-xs-6" style="padding-right: 5px;">
									<label for="payment-cc-expiratio">EXPIRATION DATE MM/YY</label>
									<input type="text" name="payment-cc-expiration" id="payment-cc-expiration" class="form-control" placeholder="Expiration MM/YY">
								</div>
								<div class="col-xs-6" style="padding-left: 5px;">
									<label for="payment-cc-cvv">CVV</label>
									<input type="text" name="payment-cc-cvv" id="payment-cc-cvv" class="form-control" placeholder="CVV">
								</div>
							</div>
							<div class="row" id="payment-form-echeck" style="display:none;">
								<div class="col-xs-12" style="padding-bottom: 10px;">
									<label for="payment-cc-customname">CUSTOM NAME</label>
									<input type="text" name="payment-echeck-customname" id="payment-echeck-customname" class="form-control" placeholder="Custom Name">
								</div>
								<div class="col-xs-12" style="padding-bottom: 10px;">
									<label for="payment-cc-number">ACCOUNT NUMBER</label>
									<input type="text" name="payment-echeck-acc_number" id="payment-echeck-acc_number" class="form-control" placeholder="Account Number">
								</div>
								<div class="col-xs-12" style="padding-bottom: 10px;">
									<label for="payment-cc-number">ROUTING NUMBER</label>
									<input type="text" name="payment-echeck-route_number" id="payment-echeck-route_number" class="form-control" placeholder="Routing Number">
								</div>
								<div class="col-xs-12" style="padding-bottom: 10px;">
									<label for="payment-cc-name">BANK NAME</label>
									<input type="text" name="payment-echeck-bank" id="payment-echeck-bank" class="form-control" placeholder="Bank Name">
								</div>
								<div class="col-xs-6" style="padding-right: 5px;">
									<label for="payment-cc-expiratio">NAME ON ACCOUNT</label>
									<input type="text" name="payment-echeck-name" id="payment-echeck-name" class="form-control" placeholder="Name On Account">
								</div>
								<div class="col-xs-6" style="padding-left: 5px;">
									<label for="payment-cc-cvv">ACCOUNT TYPE</label>
									<input type="text" name="payment-echeck-type" id="payment-echeck-type" class="form-control" placeholder="CVV">
								</div>
							</div>
							<div>
								<label class="checkbox-inline" style="font-size: 14px; font-family: 'Muli', Helvetica;color: black;"><input type="checkbox" id="giving-add-billing" style="margin-top: 4px;" onclick="$('#giving-cc-form-billing').toggle();">Add a billing address</label>
							</div>
							<div class="row no-gutters">
								<div id="giving-cc-form-billing" style="display: none;">
									<div style="padding-top: 10px">
										<div class="col-xs-12 no-gutters" style="padding-bottom: 10px;">
											<label for="giving-billing-input-address1">ADDRESS 1*</label>
											<input type="text" name="payment-cc-address1" id="payment-cc-address1" class="form-control" placeholder="Address 1">
										</div>
										<div class="col-xs-12 no-gutters" style="padding-bottom: 10px;">
											<label for="giving-billing-input-address2">ADDRESS 2*</label>
											<input type="text" name="payment-cc-address2" id="payment-cc-address2" class="form-control" placeholder="Address 2">
										</div>
										<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-right: 5px;">
											<label for="giving-billing-input-city">TOWN/CITY*</label>
											<input type="text" name="payment-cc-city" id="payment-cc-city" class="form-control" placeholder="City">
										</div>
										<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-left: 5px;">
											<label for="giving-billing-input-state">REGION/STATE/PROVINCE*</label>
											<input type="text" name="payment-cc-state" id="payment-cc-state" class="form-control" placeholder="State/PROVINCE">
										</div>
										<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-right: 5px;">
											<label for="giving-billing-input-zipcode">POSTCODE/ZIP CODE</label>
											<input type="text" name="payment-cc-zipcode" id="payment-cc-zipcode" class="form-control" placeholder="Zip/Postal Code">
										</div>
										<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-left: 5px;">
											<label for="giving-billing-input-country">COUNTRY*</label>
											<select name="giving-billing-input-country" id="giving-billing-input-country" class="selectpicker" data-size="8" data-none-selected-text="Country">
												<?php foreach($listCountries AS $countryData){ ?>
												<option value="<?php echo $countryData['iso']; ?>"><?php echo $countryData['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer donor-custom">
						<button type="button" class="btn btn-default btn-ifes" data-dismiss="modal">CANCEL</button>
						<button type="submit" class="btn btn-default btn-ifes" style = "display:none;" id="modal-payment-new" name="submit_mode" value="payment_new">SAVE</button>
						<button type="submit" class="btn btn-default btn-ifes" style = "display:none;" id="modal-payment-update" name="submit_mode" value="payment_update">UPDATE</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="subscription-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content donor-custom">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding-right: 5px;">
				<span aria-hidden="true">&times;</span>
			</button>
			<div style="padding:30px 30px">
				<div class="modal-header donor-custom">
					<h5 class="modal-title donor-custom">Modify Monthly Gift</h5>
					<div style="padding:7.5px;"></div>
				</div>
				<form id="donor-subscription-modal" class="form-vertical" role="form" method="post">
					<input type="hidden" name="subscription-input-id" value=""> 
					<div class="modal-body donor-custom">
						<div class="container-fluid no-gutters">
							<div style="padding:7.5px;"></div>
							<div class="row vertical-align">
								<div class="col-xs-8" style="padding-right: 5px;">
									<p name="subscription-display-designation" class="subscription-modal">welcome to die</p>
								</div>
								<div class="col-xs-4" style="padding-left: 5px;">
									<div class="input-group currency-box">
										<span class="input-group-addon currency-symbol" name="subscription-display-symbol"><?php echo $formCurrencySymbol; ?></span>
										<input type="number" min="0" class="form-control gift-catalog-currency-value" name="subscription-input-amount" aria-label="..." placeholder="0.00">
										<div class="input-group-btn">
											<button type="button" class="btn btn-default dropdown-toggle currency-code" name="subscription-display-currency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
											<?php echo $formCurrencyToogle; ?>
										</div>
									</div>
								</div>
							</div>
							<div style="padding:5px;"></div>
							<div>
								<label class="checkbox-inline subscription-form"><input type="checkbox" id="subscription-add-comment" style="margin-top: 3px;" onclick="$('#subscription-comment-group').toggle();">Add comment or intructions for the finance office.</label>
							</div>
							<div class="form-group" id="subscription-comment-group" style="display:none">
								<div style="padding:5px;"></div>
								<label for="subscription-input-comment" style="font-size: 12px;color: grey;">Comment:</label>
								<textarea class="form-control" rows="5" name="subscription-input-comment" id="subscription-input-comment"></textarea>
							</div>
							<div class="subscription-form">
								<div style="padding:5px;"></div>
								<span class="glyphicon glyphicon-calendar" aria-hidden="true" style="color:#1c244e"></span> Monthly Gift on the <span name="subscription-display-date"></span>
							</div>
						</div>
					</div>
					<div class="modal-footer donor-custom">
						<button type="button" class="btn btn-default btn-ifes" data-dismiss="modal">CANCEL</button>
						<button type="submit" class="btn btn-default btn-ifes" id="modal-subscription-update" name="submit_mode" value="subscription_update" onclick="appendInput();">SAVE</button>
					</div>
					<div id="submit-variable" style="display: none;"></div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function toggleDonorProfileHeader(type){
		$('#toggle-profile-header-dashboard, #toggle-profile-header-settings, #toggle-profile-header-giving').removeClass('active');
		$('#toggle-profile-header-'+type).addClass('active');
		$('#donor-profile-dashboard, #donor-profile-settings, #donor-profile-giving').hide();
		$('#donor-profile-'+type).show();
		
		//recalculate table width for reponsiveness 
		if($('#giving-grid').css('display') == 'table'){
			$('#giving-grid').DataTable().responsive.recalc();
		}
	}
	
	function toggleTelephoneInput(type){
		$('#donor-profile-input-mobile, #donor-profile-input-daytime, #donor-profile-input-evening').hide();
		$('#donor-profile-input-'+type).show();
	}
	
	function toggleCurrency(code, symbol){
		$('.currency-code').html(code+' <span class="caret"></span>');
		$('.currency-symbol').html(symbol);
		giftCurrencySymbol = symbol;
		giftCurrencyCode = code;
	}
	
	function toggleUSPayment(mode, obj){
		if(mode == 'cc'){
			$('#payment-form-cc').show();
			$('#payment-form-echeck').hide();
		}else{
			$('#payment-form-cc').hide();
			$('#payment-form-echeck').show();
		}
		$('#payment-us-paymode').val(mode);
		$('#btn-us-payment-check, #btn-us-payment-cc').removeClass('btn-ifes-active');
		$(obj).addClass('btn-ifes-active');
	}
	
	function toggleModalSubscription(id){
		$.ajax({
			url: HTTP_AJAX+"?opt=get_subscription_data", 
			dataType: 'json',
			data: {
				"enc_id": id
			}
		}).success(function(response){
			// Populate the form fields with the data returned from server
			$('#donor-subscription-modal')
				.find('[name="subscription-input-id"]').val(id).end()
				.find('[name="subscription-display-designation"]').html(response.data['description']).end()
				.find('[name="subscription-display-symbol"]').html(response.data['symbol']).end()
				.find('[name="subscription-input-amount"]').val(response.data['amount']).end()
				.find('[name="subscription-display-currency"]').html(response.data['currency_code']+' <span class="caret"></span>').end()
				.find('[name="subscription-display-date"]').html(response.data['billing_date']).end();
			$("#subscription-modal").modal();
		});
	}
	
	function toggleModalPayment(type, id){
		$('#modal-payment-new, #modal-payment-update, [name="modal-payment-new"], [name="modal-payment-update"]').hide();
		
		switch(type){
			case 'new':
				$('#donor-payment-modal')
						.find('[name="payment-cc-customname"]').val('').end()
						.find('[name="payment-cc-number"]').val('').end()
						.find('[name="payment-cc-name"]').val('').end()
						.find('[name="payment-cc-expiration"]').val('').end()
						.find('[name="payment-cc-cvv"]').val('').end();
				$('[name="modal-payment-new"]').show();
			break;	
			case 'update':
				$.ajax({
					url: HTTP_AJAX+"?opt=get_payment_data", 
					dataType: 'json',
					data: {
						"enc_id": id
					}
				}).success(function(response){
					// Populate the form fields with the data returned from server
					
					$('#donor-payment-modal')
						.find('[name="payment-cc-id"]').val(id).end()
						.find('[name="payment-cc-customname"]').val(response.data['custom_name']).end()
						.find('[name="payment-cc-number"]').val(response.data['number']).end()
						.find('[name="payment-cc-name"]').val(response.data['name']).end()
						.find('[name="payment-cc-expiration"]').val(response.data['name_1']).end()
						.find('[name="payment-cc-cvv"]').val(response.data['number_1']).end();
					$('[name="modal-payment-update"]').show();
					$("#payment-modal").modal();				
				});
			break;
		}
		
		
		$('#modal-payment-'+type).show();
	}
	
	function validateForm(){
		if(!bootstrapValidateEmpty("donor-profile-input-firstname", "")){
			noty({text: "Please fill in first name.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-lastname", "")){
			noty({text: "Please fill in last name.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-address1", "")){
			noty({text: "Please fill in address 1.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-address2", "")){
			noty({text: "Please fill in address 2.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-city", "")){
			noty({text: "Please fill in City name.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-state", "")){
			noty({text: "Please fill in State name.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-zipcode", "")){
			noty({text: "Please fill in Zipcode.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-country", "")){
			noty({text: "Please fill in Country.", type: 'error'});
			return false;
		}
		
		if(!bootstrapValidateEmpty("donor-profile-input-email", "")){
			noty({text: "Please fill in Email.", type: 'error'});
			return false;
		}
		
	}
	
	function appendInput(){
		$('<input>').attr({name: 'subscription-input-currency', value: $('.currency-code').html().substr(0, 3)}).appendTo('#submit-variable');
	}
	
	function deleteRow(type, id){
		switch(type){
			case 'subscription':
				var result = confirm("Are you sure want to delete this subscription?");
				if (result) {
					$.ajax({
						url: HTTP_AJAX+"?opt=delete_subscription", 
						data: {
							"enc_id": id
						}
					}).success(function(response){
						noty({text: "Subscription deleted successfully!", type: 'success'});
						$('#subscription-grid').DataTable().ajax.reload();
					});
				}
			break;
			case 'payment':
				var result = confirm("Are you sure want to delete this payment method?");
				if (result) {
					$.ajax({
						url: HTTP_AJAX+"?opt=delete_payment", 
						data: {
							"enc_id": id
						}
					}).success(function(response){
						noty({text: "Payment method deleted successfully!", type: 'success'});
						$('#payment-grid').DataTable().ajax.reload();
					});
				}
			break;
			
		}
		
	}
	
	$(function(){
		$('#giving_date_start').datetimepicker({
			format: 'D MMM YYYY',
			defaultDate: '<?php echo date('d M Y',strtotime(date('Y-01-01')));?>'
		});
		$('#giving_date_end').datetimepicker({
			format: 'D MMM YYYY',
			defaultDate: '<?php echo date('d M Y',strtotime(date('Y-12-31')));?>'
		});
	});
	
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#donor-profile-input-email").inputmask("email");
		
		$.fn.dataTable.ext.errMode = 'none';
		var givingGridAjaxURL = HTTP_AJAX+"?opt=list_giving";
		var givingGrid = $('#giving-grid').DataTable({
			//serverSide: true,
			dom: "<'row'<'col-xs-12 col-sm-6'B><'col-xs-12 col-sm-6 text-right'l>>rt<'row'<'col-xs-12 col-sm-6'i><'col-xs-12 col-sm-6 text-right'p>>",
			responsive: true,
			info: false,
			searching: false,
			ordering: false,
			lengthChange: false,
			columnDefs: [{
				width: "300px",
				targets: 0
			}],
			pagingType: "simple_numbers",
			order: [[ 3, 'desc' ]],
			language:{
				zeroRecords: "No giving history found",
				paginate: {
					"previous": '<span class="arrow-left"></span><span>BACK</span>',
					"next": '<span style="vertical-align: top;">NEXT</span><span class="arrow-right"></span>'
				}
			},
			ajax: {
				url: givingGridAjaxURL,
				data: function(d){
					d.search_date_start = $('#giving_date_start').data('DateTimePicker').date().format('YYYY-MM-DD');
					d.search_date_end = $('#giving_date_end').data('DateTimePicker').date().format('YYYY-MM-DD');
				}
			}
			
		});
		
		var subscriptionGridAjaxURL = HTTP_AJAX+"?opt=list_subscription";
		var subscriptionGrid = $('#subscription-grid').DataTable({
			//serverSide: true,
			dom: "<'row'<'col-xs-12 col-sm-6'B><'col-xs-12 col-sm-6 text-right'l>>rt<'row'<'col-xs-12 col-sm-6'i><'col-xs-12 col-sm-6 text-right'p>>",
			responsive: true,
			info: false,
			searching: false,
			ordering: false,
			lengthChange: false,
			pagingType: "simple_numbers",
			order: [[ 3, 'desc' ]],
			language:{
				zeroRecords: "No monthly giving found",
				paginate: {
					"previous": '<span class="arrow-left"></span><span>BACK</span>',
					"next": '<span style="vertical-align: top;">NEXT</span><span class="arrow-right"></span>'
				}
			},
			ajax: {
				url: subscriptionGridAjaxURL
			}
			
		});

		var paymentGridAjaxURL = HTTP_AJAX+"?opt=list_payment";
		var paymentGrid = $('#payment-grid').DataTable({
			//serverSide: true,
			dom: "<'row'<'col-xs-12 col-sm-6'B><'col-xs-12 col-sm-6 text-right'l>>rt<'row'<'col-xs-12 col-sm-6'i><'col-xs-12 col-sm-6 text-right'p>>",
			responsive: true,
			info: false,
			searching: false,
			ordering: false,
			lengthChange: false,
			columnDefs: [{
				width: "300px",
				targets: 0
			}],
			pagingType: "simple_numbers",
			order: [[ 3, 'desc' ]],
			language:{
				zeroRecords: "No payment method found",
				paginate: {
					"previous": '<span class="arrow-left"></span><span>BACK</span>',
					"next": '<span style="vertical-align: top;">NEXT</span><span class="arrow-right"></span>'
				}
			},
			ajax: {
				url: paymentGridAjaxURL
			}
			
		});
		
		/*
		.on( 'error.dt', function ( e, settings, techNote, message ) { //debug
			console.log( 'An error has been reported by DataTables: ', message ); //debug
		});	 //debug
		*/
		
		$("#giving_date_start").on("dp.change", function(e) {
			givingGrid.ajax.reload();
		});
		
		$("#giving_date_end").on("dp.change", function(e) {
			givingGrid.ajax.reload();
		});
		
		toggleDonorProfileHeader('settings'); //dashboard, settings, giving
		toggleTelephoneInput('mobile');
	});
	
	
</script>