<div id="myCarousel" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
		<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		<li data-target="#myCarousel" data-slide-to="1"></li>
		<li data-target="#myCarousel" data-slide-to="2"></li>
	</ol>
	<div class="carousel-inner" role="listbox">
		<div class="item active">
			<img src="<?php echo HTTP_MEDIA;?>/site-image/banner/ifes-banner-1.jpg">
		</div>
		<div class="item">
			<img src="<?php echo HTTP_MEDIA;?>/site-image/banner/ifes-banner-2.jpg">
		</div>
		<div class="item">
			<img src="<?php echo HTTP_MEDIA;?>/site-image/banner/ifes-banner-3.jpeg">
		</div>
	</div>
</div>
<div class="container" style="padding-left: 0; padding-right: 0;">
	<div class="col-xs-12 col-md-6">
		<img src="<?php echo HTTP_MEDIA.'/site-image/ifes-logo.png';?>" width="131" style="margin-top: -1px; margin-bottom: 28px;">
		<p class="pg-title">GIVING PAGE</p>
		<p class="pg-title-content">Donate towards the various ministries and work of IFES worldwide.<br>To make a non-donation payment, please visit our Payment Page.</p>
	</div>
	<div class="col-xs-12 col-md-6" style="position: relative;">
		<div style="width: 344px; padding: 20px; background-color: rgba(255, 255, 255, 0.8); position: absolute; right: 16px;">
			<p class="pg-title-content">AFRICA STAFF TRAINING INSTITUTES</p>
			<p class="pg-title-content"><br><a>Learn more</a> about encouraging<br>and equiping staff in Africa.<br><br></p>
			<div class="input-group currency-box">
				<span class="input-group-addon"><?php echo $formCurrencySymbol; ?></span>
				<input type="number" min="0" class="form-control" aria-label="..." placeholder="0.00">
				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a href="#">EUR</a></li>
						<li><a href="#">GBP</a></li>
						<li><a href="#">USD</a></li>
					</ul>
					<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;">ADD GIFT</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container giving-center-link">
	<a>LEGACY GIVING</a> | <a>NON-CASH GIFTS</a> | <a>GIVE BY PHONE OR MAIL</a> | <a>FAQ</a> | <a>PAYMENT PAGE</a> | <a>HELP</a>
</div>
<div class="container">
	<br>
	<p style="font-family: 'Muli-Bold', Helvetica; text-align: center; font-size: 22px; font-weight: bold;">IFES Gift Catalog</p>
	<br>
	<table class="giving-table">
		<tr class="header">
			<td id="toggle-gift-header-ministry" onclick="toggleGiftCatalogHeader('ministry');">IFES Ministry</td>
			<td id="toggle-gift-header-staff" onclick="toggleGiftCatalogHeader('staff');">Staff Worker</td>
			<td id="toggle-gift-header-movement" onclick="toggleGiftCatalogHeader('movement');">National Movement</td>
			<td id="toggle-gift-header-offering" onclick="toggleGiftCatalogHeader('offering');">Offerings</td>
		</tr>
		<tr>
			<td colspan="4" style="padding: 25px; font-size: 16px; font-family: 'Muli', Helvetica; height: 500px; vertical-align: top;">
				<div id="gift-catalog-ministry" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-ministry-search" name="radio-gift-catalog-ministry" checked onclick="toggleGiftCatalog('ministry', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-ministry-manual" name="radio-gift-catalog-ministry" onclick="toggleGiftCatalog('ministry', 'manual');">Enter an IFES Ministry</label>
				</div>
				<div id="gift-catalog-staff" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-staff-search" name="radio-gift-catalog-staff" checked onclick="toggleGiftCatalog('staff', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-staff-manual" name="radio-gift-catalog-staff" onclick="toggleGiftCatalog('staff', 'manual');">Enter Staff Name</label>
					<br><br>
					<div>
						Your commitment to staff allows them to focus on their ministry and serving students.
						<div class="input-group" style="margin-top: 10px;">
							<input type="text" class="form-control" placeholder="Search for staff by name and countyr or ministry">
							<span class="input-group-btn">
								<button class="btn btn-default btn-search" type="button">SEARCH</button>
							</span>
						</div>
						<div style="margin-top: 20px; height: 350px; background-color: #ffffff; border: 1px solid #cccccc;"></div>
					</div>
				</div>
				<div id="gift-catalog-movement" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-movement-search" name="radio-gift-catalog-movement" checked onclick="toggleGiftCatalog('movement', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-movement-manual" name="radio-gift-catalog-movement" onclick="toggleGiftCatalog('movement', 'manual');">Enter a National Movement</label>
				</div>
				<div id="gift-catalog-offering" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-offering-search" name="radio-gift-catalog-offering" checked onclick="toggleGiftCatalog('offering', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-offering-manual" name="radio-gift-catalog-offering" onclick="toggleGiftCatalog('offering', 'manual');">Enter an Offering</label>
					<br><br>
					<div id="gift-catalog-offering-search-form" style="display: none;">
						Select the event you’re attending from the dropdown menu
						<div style="margin-top: 10px;">
							<div class="col-xs-8" style="padding-left: 0;"></div>
							<div class="col-xs-4" style="padding-right: 0;">
								<div class="input-group currency-box">
									<span class="input-group-addon"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="#">EUR</a></li>
											<li><a href="#">GBP</a></li>
											<li><a href="#">USD</a></li>
										</ul>
										<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="gift-catalog-offering-manual-form" style="display: none;">
						Can’t find your event? Please enter the name of the event you’re attending or the gift designation in the field below.
						<div style="margin-top: 10px;">
							<div class="col-xs-8" style="padding-left: 0;"><input type="text" class="form-control" placeholder="Enter event or designation name"></div>
							<div class="col-xs-4" style="padding-right: 0;">
								<div class="input-group currency-box">
									<span class="input-group-addon"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="#">EUR</a></li>
											<li><a href="#">GBP</a></li>
											<li><a href="#">USD</a></li>
										</ul>
										<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
<hr style="margin-top: 25px; margin-bottom: 25px; background-color: #fff; border-top: 2px dashed #8c8b8b;">
<div class="container">
	<table style="background-color: #ebebeb; width: 100%;">
		<tr>
			<td style="font-family: 'Muli-Bold', Helvetica; text-align: center; font-size: 22px; font-weight: bold;">Your Gift List</td>
		</tr>
	</table>
</div>
<br><br>
<script type="text/javascript">
	function toggleGiftCatalogHeader(type){
		$('#toggle-gift-header-ministry, #toggle-gift-header-staff, #toggle-gift-header-movement, #toggle-gift-header-offering').removeClass('active');
		$('#toggle-gift-header-'+type).addClass('active');
		$('#gift-catalog-ministry, #gift-catalog-staff, #gift-catalog-movement, #gift-catalog-offering').hide();
		$('#gift-catalog-'+type).show();
		toggleGiftCatalog(type, 'search');
	}

	function toggleGiftCatalog(type, mode){
		$('#gift-catalog-'+type+'-search-form').hide();
		$('#gift-catalog-'+type+'-manual-form').hide();
		$('#gift-catalog-'+type+'-'+mode+'-form').show();
	}

	$(document).ready(function(){
		toggleGiftCatalogHeader('staff');
	});
</script>