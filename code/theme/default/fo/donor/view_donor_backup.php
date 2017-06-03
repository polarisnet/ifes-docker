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
		<p class="pg-title-content" style="display:inline-block"><?php echo strtoupper($donorName) ?></p>
		<button type="button" class="btn btn-default btn-ifes" style="float:right">LOG OUT</button>
		<p class="pg-title-content">Account Number: <?php echo $donorAccountNumbers ?></p>
		
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
			<td colspan="4" style="padding: 25px; font-size: 16px; font-family: 'Muli', Helvetica; height: 500px; vertical-align: top;">
				<div id="donor-profile-dashboard" style="display: none;">
						<p class="pg-title-content">Welcome back, <?php echo ucfirst($firstName)."!"; ?></p>
						<div class="col-xs-12 col-md-8 no-gutters">
							<div style="padding-right:15px">
								<div class="content-white">
									<p class="donor-content-title text-center">Your Activity</p>
									<hr>
									<p class="donor-content-regular-bold" style="font-size: 16px;">You've given $<?php echo $total." since ".$firstDate?></p> <!-- TODO:Currency(?) -->
									<table class="table table-hover donor-table-content">
										<thead>
											<tr>
												<th colspan="4">Recent Donations</th>
											</tr>
										</thead>
										<tr>
											<td>Africa Staff Training Institutes</td>
											<td>$200.00</td>
											<td>20 Jan 2017</td>
											<td><span class= "span-link" onclick="alert('hey there')" >Give Again</span></td>
										</tr>
										<tr>
											<td>Student Ministry in Ghana</td>
											<td>$25.00(monthly)</td>
											<td>10 Jan 2017</td>
											<td><span class= "span-link" onclick="alert('hey there')" >Give Again</span></td>
										</tr>
									</table>
									<p style="float:right">View your <span class= "span-link" onclick="toggleDonorProfileHeader('giving')" >five-year giving history</span></p>
									<div style="padding:15px"></div>
								</div>
								<div style="padding:15px"></div>
								<div class="content-white">
									<p class="donor-content-title text-center">Your News Feed</p>
									<hr>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-4 no-gutters">
							<div style="padding-left:15px">
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
				<div id="donor-profile-settings" style="display: none;">
					<div class="row no-gutters equal">
						<div class="col-xs-12 col-md-8 no-gutters">
								<div style="padding-right:15px">
									<div class="content-white">
										<p class="donor-content-title text-center">Update Profile</p>
										<hr>
										<div class="row">
											<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
												<label for="donor-profile-input-firstname">FIRST NAME(S)*</label>
												<input type="text" id="donor-profile-input-firstname" class="form-control" placeholder="First Name">
											</div>
											<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
												<label for="donor-profile-input-lastname">LAST NAME*</label>
												<input type="text" id="donor-profile-input-lastname" class="form-control" placeholder="Last Name">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12" style="padding-bottom: 10px;">
											<label for="donor-profile-input-spouse">SPOUSE'S NAME (OPTIONAL)</label>
												<input type="text" id="donor-profile-input-spouse" class="form-control" placeholder="Spouse's Name">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12" style="padding-bottom: 10px;">
												<label for="donor-profile-input-address1">ADDRESS 1*</label>
												<input type="text" id="donor-profile-input-address1" class="form-control" placeholder="Address 1">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12" style="padding-bottom: 10px;">
												<label for="donor-profile-input-address2">ADDRESS 2*</label>
												<input type="text" id="donor-profile-input-address2" class="form-control" placeholder="Address 2">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
												<label for="donor-profile-input-city">TOWN/CITY*</label>
												<input type="text" id="donor-profile-input-city" class="form-control" placeholder="City">
											</div>
											<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
												<label for="donor-profile-input-state">REGION/STATE/PROVINCE*</label>
												<input type="text" id="donor-profile-input-state" class="form-control" placeholder="State/Provice">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
												<label for="donor-profile-input-zipcode">POSTCODE/ZIP CODE*</label>
												<input type="text" id="donor-profile-input-zipcode" class="form-control" placeholder="Zip/Postal Code">
											</div>
											<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
												<label for="donor-profile-input-country">COUNTRY*</label>
												<input type="text" id="donor-profile-input-country" class="form-control" placeholder="Country">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12" style="padding-bottom: 10px;">
												<label for="donor-profile-input-telephone">TELEPHONE*</label>
												<input type="text" id="donor-profile-input-telephone" class="form-control" placeholder="Telephone">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12" style="padding-bottom: 10px;">
												<label for="donor-profile-input-email">EMAIL*</label>
												<input type="text" id="donor-profile-input-email" class="form-control" placeholder="Email">
											</div>
										</div>
										<div style="text-align: right">
											<span style="font-size: 12px; color:grey;">*REQUIRED<span>
										</div>
										<div>
											<label class="checkbox-inline" style="font-size: 14px; font-family: 'Muli', Helvetica;color: black;"><input type="checkbox" id="gift-add-billing" style="margin-top: 4px;" onclick="$('#donor-cc-form-billing').toggle();">Add a billing address</label>
										</div>
										<div id="donor-cc-form-billing" style="display: none;">
											<div style="text-align: center; padding-top: 20px; font-family: 'Muli', Helvetica;">
												<div class="col-xs-12 no-gutters" style="padding-bottom: 10px;">
													<input type="text" id="gift-mailing-input-name" class="form-control" placeholder="Full Name">
												</div>
												<div class="col-xs-12 no-gutters" style="padding-bottom: 10px;">
													<input type="text" id="gift-mailing-input-address1" class="form-control" placeholder="Address 1">
												</div>
												<div class="col-xs-12 no-gutters" style="padding-bottom: 10px;">
													<input type="text" id="gift-mailing-input-address2" class="form-control" placeholder="Address 2">
												</div>
												<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-right: 5px;">
													<input type="text" id="gift-mailing-input-city" class="form-control" placeholder="City">
												</div>
												<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-left: 5px;">
													<input type="text" id="gift-mailing-input-city" class="form-control" placeholder="State/Provice">
												</div>
												<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-right: 5px;">
													<input type="text" id="gift-mailing-input-zipcode" class="form-control" placeholder="Zip/Postal Code">
												</div>
												<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-left: 5px;">
													<input type="text" id="gift-mailing-input-zipcode" class="form-control" placeholder="Zip/Postal Code">
												</div>
												<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-right: 5px;">
													<input type="text" id="gift-mailing-input-email" class="form-control" placeholder="Email">
												</div>
												<div class="col-xs-6 no-gutters" style="padding-bottom: 10px; padding-left: 5px;">
													<input type="text" id="gift-mailing-input-phone" class="form-control" placeholder="Phone">
												</div>
											</div>
										</div>
										<div style="text-align:right">
											<button type="button" class="btn btn-default btn-ifes">SAVE</button>
										</div>
									</div>
								</div>
						</div>
						<div class="col-xs-12 col-md-4 no-gutters">
							<div style="padding-left:15px">
								<div class="content-white">
									<p class="donor-content-title text-center">Change Password</p>
									<p style="font-size: 14px; color: grey;">Passwords are case-sensitive and must be at least 6 characters. <br><br>
									A good passsword should contain a mix of capital and lower-case letters, numbers and symbols</p>
									<input type="text" id="donor-password-input-current" class="form-control" placeholder="Current Password">
									<div style="padding:5px"></div>
									<input type="text" id="donor-password-input-new" class="form-control" placeholder="New Password">
									<div style="padding:5px"></div>
									<input type="text" id="donor-password-input-confirm" class="form-control" placeholder="Confirm New Password">
									<div style="padding:5px"></div>
									
									<div style="text-align:right">
										<button type="button" class="btn btn-default btn-ifes">SAVE</button>
									</div>
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
					<div style="padding:15px"></div>
					<div class="row no-gutters"> 
						<div class="col-xs-12 col-md-12 no-gutters">
							<div class="content-white">
								<p class="donor-content-title text-center">Communication Preference</p>
								<hr>
								<div class="row no-gutters">
									<div class="col-xs-12 col-md-9 no-gutters" >
										<p class="donor-content-subtitle">My Subscriptions</p>
										<div>
											<label class="donor-checkbox-subscriptions">
											<input type="checkbox" id="donor-subscriptions-1" style="margin-top: 4px;">
											Praise & Prayer: a bimonthly email with Daily Prayer Guide <span style="font-size:10px">(English only)</span></label>
											<label class="donor-checkbox-subscriptions">
											<input type="checkbox" id="donor-subscriptions-2" style="margin-top: 4px;">
											Prayerline: a weekly email snapshot of the IFES world to inspire your prayers</label>
											<label class="donor-checkbox-subscriptions">
											<input type="checkbox" id="donor-subscriptions-3" style="margin-top: 4px;">
											Conexión: a monthly online magazine connecting the IFES World</label>
											<label class="donor-checkbox-subscriptions">
											<input type="checkbox" id="donor-subscriptions-4" style="margin-top: 4px;">
											Voix: a weekly blog, by students and for students</label>
										</div>
									</div>
									<div class="col-xs-12 col-md-3 no-gutters" >
										<p class="donor-content-subtitle">Preferred Language</p>
										<div>
											<label class="donor-checkbox-language" ><input type="checkbox" id="donor-language-english" style="margin-top: 4px;">English</label>
											<label class="donor-checkbox-language" ><input type="checkbox" id="donor-language-france" style="margin-top: 4px;">Français</label>
											<label class="donor-checkbox-language" ><input type="checkbox" id="donor-language-spanish" style="margin-top: 4px;">Español</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="donor-profile-giving" style="display: none;">
					
				</div>
			</td>
		</tr>
	</table>
</div>

<br><br>
<script type="text/javascript">
	function toggleDonorProfileHeader(type){
		$('#toggle-profile-header-dashboard, #toggle-profile-header-settings, #toggle-profile-header-giving').removeClass('active');
		$('#toggle-profile-header-'+type).addClass('active');
		$('#donor-profile-dashboard, #donor-profile-settings, #donor-profile-giving').hide();
		$('#donor-profile-'+type).show();
	}

	$(document).ready(function(){
		toggleDonorProfileHeader('settings'); //dashboard
	});
</script>