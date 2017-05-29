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
<div class="container no-padding donor-header">
		<div class="col-xs-12 col-md-12">
			<div class="social-header">
			<a class="social-link fb" href="http://www.facebook.com/ifesworld" title="Find us on Facebook"></a>
			<a class="social-link tw" href="http://www.twitter.com/ifes" title="Follow us on Twitter"></a>
			<a class="social-link ig" href="https://instagram.com/ifesworld" title="Check out our Instagram Photos"></a>
		</div>
		<img src="<?php echo HTTP_MEDIA.'/site-image/ifes-logo.png';?>" width="131" style="margin-top: -1px; margin-bottom: 28px;">
		<p class="pg-title">MY ACCOUNT</p>
		<p class="pg-title-content"><?php echo strtoupper($donorName) ?></p>
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
						<div class="col-xs-12 col-md-8 no-padding">
							<div style="padding-right:15px">
								<div class="content-white">
									<p class="donor-title-content text-center">Your Activity</p>
									<hr>
									<p class="donor-regular-content">You've given $<?php echo $total." since ".$firstDate?></p> <!-- TODO:Currency(?) -->
									<table class="table table-hover">
										<thead>
											<tr>
												<th colspan="4">Recent Donations</th>
											</tr>
										</thead>
										<tr>
											<td>Africa Staff Training Institutes</td>
											<td>$200.00</td>
											<td>20 Jan 2017</td>
											<td>Give Again</td>
										</tr>
										<tr>
											<td>Student Ministry in Ghana</td>
											<td>$25.00(monthly)</td>
											<td>10 Jan 2017</td>
											<td>Give Again</td>
										</tr>
									</table>
									<p style="float:right">View your <span onclick="alert('hey there')" style="color: blue; cursor: pointer">full giving history</span></p>
									<div style="padding:15px"></div>
								</div>
								<div style="padding:15px"></div>
								<div class="content-white">
									<p class="donor-title-content text-center">Your News Feed</p>
									<hr>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-4 no-padding">
							<div style="padding-left:15px">
								<div class="content-white">
									<p class="donor-title-content text-center">Your Profile</p>
									<hr>
									<div style="height:250px;"></div>
								</div>
								<div style="padding:15px"></div>
								<div class="content-white">
									<p class="donor-title-content text-center">Quick Links</p>
									<hr>
									<a href=<?php echo HTTP_SERVER.HTTP_ROOT."/giving"?> >Give Now</a><br>
									<a href="#" >Help</a><br>
									<a href="#" >FAQs</a><br>
									<a href="#" >Contacts</a>
								</div>
								
							</div>
						</div>
				</div>
				<div id="donor-profile-settings" style="display: none;">
					
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
		toggleDonorProfileHeader('dashboard');
	});
</script>