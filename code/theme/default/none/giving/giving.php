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
				<span class="input-group-addon gift-catalog-currency-symbol"><?php echo $formCurrencySymbol; ?></span>
				<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a onclick="toggleCurrency('EUR', '&euro;');">EUR</a></li>
						<li><a onclick="toggleCurrency('GBP', '&pound;');">GBP</a></li>
						<li><a onclick="toggleCurrency('USD', '&dollar;');">USD</a></li>
					</ul>
					<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;" onclick="addGift(this, 'staff', 'search', 'AFRICA STAFF TRAINING INSTITUTES', '');">ADD GIFT</button>
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
					<br><br>
					<div id="gift-catalog-ministry-search-form" style="display: none;">
						Ministry text
						<div class="input-group" style="margin-top: 10px;">
							<input type="text" id="gift-catalog-ministry-search-query" class="form-control" placeholder="Search for IFES ministry">
							<span class="input-group-btn">
								<button class="btn btn-default btn-search" type="button" onclick="searchGiftCatalog('ministry');">SEARCH</button>
							</span>
						</div>
						<div id="gift-catalog-ministry-search-container" class="gift-catalog-default-search-container">
							<p id="gift-catalog-ministry-search-label" class="gift-catalog-default-search-label" ></p>
							<div id="gift-catalog-ministry-search-result" class="gift-catalog-default-search-result"></div>
						</div>
					</div>
					<div id="gift-catalog-ministry-manual-form" style="display: none;">
						IFES ministry in sensitive locations may not appear in list. If the ministry is not listed please enter their name in the form below.
						<div style="margin-top: 10px;">
							<div class="col-xs-8" style="padding-left: 0;"><input type="text" id="gift-catalog-search-ministry-manual-input" class="form-control" placeholder="Enter ministry name"></div>
							<div class="col-xs-4" style="padding-right: 0;">
								<div class="input-group currency-box">
									<span class="input-group-addon gift-catalog-currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="toggleCurrency('EUR', '&euro;');">EUR</a></li>
											<li><a onclick="toggleCurrency('GBP', '&pound;');">GBP</a></li>
											<li><a onclick="toggleCurrency('USD', '&dollar;');">USD</a></li>
										</ul>
										<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;" onclick="addGift(this, 'ministry', 'manual', $('#gift-catalog-search-ministry-manual-input').val(), '');">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="gift-catalog-staff" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-staff-search" name="radio-gift-catalog-staff" checked onclick="toggleGiftCatalog('staff', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-staff-manual" name="radio-gift-catalog-staff" onclick="toggleGiftCatalog('staff', 'manual');">Enter Staff Name</label>
					<br><br>
					<div id="gift-catalog-staff-search-form" style="display: none;">
						Your commitment to staff allows them to focus on their ministry and serving students.
						<div class="input-group" style="margin-top: 10px;">
							<input type="text" id="gift-catalog-staff-search-query" class="form-control" placeholder="Search for staff by name and countyr or ministry">
							<span class="input-group-btn">
								<button class="btn btn-default btn-search" type="button" onclick="searchGiftCatalog('staff');">SEARCH</button>
							</span>
						</div>
						<div id="gift-catalog-staff-search-container" class="gift-catalog-default-search-container">
							<p id="gift-catalog-staff-search-label" class="gift-catalog-default-search-label" ></p>
							<div id="gift-catalog-staff-search-result" class="gift-catalog-default-search-result"></div>
						</div>
					</div>
					<div id="gift-catalog-staff-manual-form" style="display: none;">
						Staff in sensitive locations may not appear in list. If the staff is not listed please enter their name and country of service in the form below.
						<div style="margin-top: 10px;">
							<div class="col-xs-8" style="padding-left: 0;"><input type="text" id="gift-catalog-search-manual-input" class="form-control" placeholder="Enter staff name"></div>
							<div class="col-xs-4" style="padding-right: 0;">
								<div class="input-group currency-box">
									<span class="input-group-addon gift-catalog-currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="toggleCurrency('EUR', '&euro;');">EUR</a></li>
											<li><a onclick="toggleCurrency('GBP', '&pound;');">GBP</a></li>
											<li><a onclick="toggleCurrency('USD', '&dollar;');">USD</a></li>
										</ul>
										<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;" onclick="addGift(this, 'staff', 'manual', $('#gift-catalog-search-manual-input').val(), '');">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="gift-catalog-movement" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-movement-search" name="radio-gift-catalog-movement" checked onclick="toggleGiftCatalog('movement', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-movement-manual" name="radio-gift-catalog-movement" onclick="toggleGiftCatalog('movement', 'manual');">Enter a National Movement</label>
					<br><br>
					<div id="gift-catalog-movement-search-form" style="display: none;">
						National movement text
						<div class="input-group" style="margin-top: 10px;">
							<input type="text" id="gift-catalog-movement-search-query" class="form-control" placeholder="Search for a national movement">
							<span class="input-group-btn">
								<button class="btn btn-default btn-search" type="button" onclick="searchGiftCatalog('movement');">SEARCH</button>
							</span>
						</div>
						<div id="gift-catalog-movement-search-container" class="gift-catalog-default-search-container">
							<p id="gift-catalog-movement-search-label" class="gift-catalog-default-search-label" ></p>
							<div id="gift-catalog-movement-search-result" class="gift-catalog-default-search-result"></div>
						</div>
					</div>
					<div id="gift-catalog-movement-manual-form" style="display: none;">
						Some national movement may not appear in list. If the national movement is not listed please enter in the form below.
						<div style="margin-top: 10px;">
							<div class="col-xs-8" style="padding-left: 0;"><input type="text" id="gift-catalog-search-movement-manual-input" class="form-control" placeholder="Enter national movement"></div>
							<div class="col-xs-4" style="padding-right: 0;">
								<div class="input-group currency-box">
									<span class="input-group-addon gift-catalog-currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="toggleCurrency('EUR', '&euro;');">EUR</a></li>
											<li><a onclick="toggleCurrency('GBP', '&pound;');">GBP</a></li>
											<li><a onclick="toggleCurrency('USD', '&dollar;');">USD</a></li>
										</ul>
										<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;" onclick="addGift(this, 'movement', 'manual', $('#gift-catalog-search-movement-manual-input').val(), '');">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="gift-catalog-offering" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-offering-search" name="radio-gift-catalog-offering" checked onclick="toggleGiftCatalog('offering', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-offering-manual" name="radio-gift-catalog-offering" onclick="toggleGiftCatalog('offering', 'manual');">Enter an Offering</label>
					<br><br>
					<div id="gift-catalog-offering-search-form" style="display: none;">
						Select the event you’re attending from the dropdown menu
						<div style="margin-top: 10px;">
							<div class="col-xs-8" style="padding-left: 0;">
								<select id="gift-catalog-offering-select" class="selectpicker" data-live-search="true" data-size="8">
									<?php foreach($listOfferingEvents AS $eventData){ ?>
									<option data-subtext="<?php echo $eventData['sourcecode']; ?>" value="<?php echo $eventData['sourcecode']; ?>"><?php echo $eventData['sourcedescription']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xs-4" style="padding-right: 0;">
								<div class="input-group currency-box">
									<span class="input-group-addon gift-catalog-currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="toggleCurrency('EUR', '&euro;');">EUR</a></li>
											<li><a onclick="toggleCurrency('GBP', '&pound;');">GBP</a></li>
											<li><a onclick="toggleCurrency('USD', '&dollar;');">USD</a></li>
										</ul>
										<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;" onclick="addGift(this, 'offering', 'search', $('#gift-catalog-offering-select option:selected').text(), $('#gift-catalog-offering-select').val());">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="gift-catalog-offering-manual-form" style="display: none;">
						Can’t find your event? Please enter the name of the event you’re attending or the gift designation in the field below.
						<div style="margin-top: 10px;">
							<div class="col-xs-8" style="padding-left: 0;"><input id="gift-catalog-offering-manual-input" type="text" class="form-control" placeholder="Enter event or designation name"></div>
							<div class="col-xs-4" style="padding-right: 0;">
								<div class="input-group currency-box">
									<span class="input-group-addon gift-catalog-currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle gift-catalog-currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="toggleCurrency('EUR', '&euro;');">EUR</a></li>
											<li><a onclick="toggleCurrency('GBP', '&pound;');">GBP</a></li>
											<li><a onclick="toggleCurrency('USD', '&dollar;');">USD</a></li>
										</ul>
										<button type="button" class="btn btn-default btn-add-gift" style="margin-left: 10px;" onclick="addGift(this, 'offering', 'manual', $('#gift-catalog-offering-manual-input').val(), '');">ADD GIFT</button>
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
			<td style="padding-top: 20px; font-family: 'Muli-Bold', Helvetica; text-align: center; font-size: 22px; font-weight: bold;">Your Gift List</td>
		</tr>
		<tr>
			<td>
				<div class="gift-list-master">There is no any gift in your list yet.</div>
			</td>
		</tr>
		<tr>
			<td class="gift-cc-total-style">Total monthly gift:&nbsp;<span class="gift-list-currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="gift-list-total-recurring">0.00</span></td>
		</tr>
		<tr>
			<td class="gift-cc-total-style">Total one-time gift:&nbsp;<span class="gift-list-currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="gift-list-total-onetime">0.00</span></td>
		</tr>
		<tr>
			<td style="padding: 5px 24px; text-align: right; font-family: 'Muli', Helvetica;">
				<div style="max-width: 450px; width: 450px; padding-top: 10px; float: right;">
					<div class="col-xs-6" style="padding-left: 0; padding-right: 10px;">
						<input type="text" class="form-control" placeholder="User Name">
					</div>
					<div class="col-xs-6" style="padding-left: 10px; padding-right: 0;">
						<input type="password" class="form-control" placeholder="Password">
					</div>
					<div class="col-xs-12" style="padding: 10px; padding-right: 0;">
						<a style="font-size: 14px;">Forget your password?</a>
					</div>
					<div class="col-xs-12" style="padding-left: 0; padding-right: 0;">
						<button type="button" class="btn btn-default btn-ifes" style="margin-right: 15px;">LOG IN</button>
						<button type="button" class="btn btn-default btn-ifes">GIVE AS GUEST</button>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td style="padding-bottom: 20px;"></td>
		</tr>
	</table>
</div>
<div style="height: auto; background-color: #ebebeb; margin-top: 50px; padding-bottom: 20px;">
	<div class="container">
		<table style="width: 600px; max-width: 600px; margin: auto;">
			<tr>
				<td style="padding-top: 20px; font-family: 'Muli-Bold', Helvetica; text-align: center; font-size: 22px; font-weight: bold;">Secure Payment Information</td>
			</tr>
			<?php if(!empty($listCreditCards)){ ?>
			<tr id="gift-cc-form-select">
				<td style="text-align: center; padding-top: 20px;">
					<div class="col-xs-12">
						<select id="gift-cc-select" class="selectpicker" data-size="8" data-none-selected-text="Please select a credit card"></select>
					</div>
					<div class="col-xs-12" style="text-align: right; padding-top: 10px;">
						<button type="button" class="btn btn-default btn-ifes" onclick="toggleCardPayment();">NEW CARD</button>
					</div>
				</td>
			</tr>
			<?php } ?>
			<tr id="gift-cc-form-new" style="<?php if(!empty($listCreditCards)){echo "display: none;";} ?>">
				<td style="text-align: center; padding-top: 20px; font-family: 'Muli', Helvetica;">
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-cc-input-number" class="form-control" placeholder="Card Number">
					</div>
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-cc-input-name" class="form-control" placeholder="Name on Card">
					</div>
					<div class="col-xs-6" style="padding-right: 5px;">
						<input type="text" id="gift-cc-input-expiration" class="form-control" placeholder="Expiration MM/YY">
					</div>
					<div class="col-xs-6" style="padding-left: 5px;">
						<input type="text" id="gift-cc-input-cvv" class="form-control" placeholder="CVV">
					</div>
					<?php if(!empty($listCreditCards)){ ?>
					<div class="col-xs-12" style="text-align: right; padding-top: 10px;">
						<button type="button" class="btn btn-default btn-ifes" onclick="toggleCardPayment();">CANCEL</button>
					</div>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td style="padding: 12px 15px;">
					<label class="checkbox-inline" style="font-size: 14px; font-family: 'Muli', Helvetica;"><input type="checkbox" id="gift-cc-process-fee" class="gift-cc-process-fee" style="margin-top: 4px;" onclick="calcGiftList();">I’d like to increase my donation by <span class='gift-cc-process-currency'><?php echo $formCurrencySymbol; ?></span> 5.00 to help towards the cost of online transactions.</label>
				</td>
			</tr>
			<tr>
				<td class="gift-cc-total-style">Total monthly gift:&nbsp;<span class="gift-list-currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="gift-list-total-recurring">0.00</span></td>
			</tr>
			<tr>
				<td class="gift-cc-total-style">Total one-time gift:&nbsp;<span class="gift-list-currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="gift-list-total-onetime">0.00</span></td>
			</tr>
			<tr>
				<td style="padding-top: 40px; font-family: 'Muli-Bold', Helvetica; text-align: center; font-size: 22px; font-weight: bold;">Billing Information</td>
			</tr>
			<tr id="gift-cc-form-billing">
				<td style="text-align: center; padding-top: 20px; font-family: 'Muli', Helvetica;">
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-billing-input-name" class="form-control" placeholder="Full Name">
					</div>
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-billing-input-address1" class="form-control" placeholder="Address 1">
					</div>
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-billing-input-address2" class="form-control" placeholder="Address 2">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
						<input type="text" id="gift-billing-input-city" class="form-control" placeholder="City">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
						<input type="text" id="gift-billing-input-city" class="form-control" placeholder="State/Provice">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
						<input type="text" id="gift-billing-input-zipcode" class="form-control" placeholder="Zip/Postal Code">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
						<select id="gift-billing-input-country" class="selectpicker" data-size="8" data-none-selected-text="Country">
							<?php foreach($listCountries AS $countryData){ ?>
							<option value="<?php echo $countryData['iso']; ?>"><?php echo $countryData['name']; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
						<input type="text" id="gift-billing-input-email" class="form-control" placeholder="Email">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
						<input type="text" id="gift-billing-input-phone" class="form-control" placeholder="Phone">
					</div>
				</td>
			</tr>
			<tr>
				<td style="padding: 12px 15px;">
					<label class="checkbox-inline" style="font-size: 14px; font-family: 'Muli', Helvetica;"><input type="checkbox" id="gift-add-mailing" class="gift-cc-process-fee" style="margin-top: 4px;" onclick="$('#gift-cc-form-mailing').toggle();">Add a preferred mailing address</label>
				</td>
			</tr>
			<tr id="gift-cc-form-mailing" style="display: none;">
				<td style="text-align: center; padding-top: 20px; font-family: 'Muli', Helvetica;">
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-mailing-input-name" class="form-control" placeholder="Full Name">
					</div>
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-mailing-input-address1" class="form-control" placeholder="Address 1">
					</div>
					<div class="col-xs-12" style="padding-bottom: 10px;">
						<input type="text" id="gift-mailing-input-address2" class="form-control" placeholder="Address 2">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
						<input type="text" id="gift-mailing-input-city" class="form-control" placeholder="City">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
						<input type="text" id="gift-mailing-input-city" class="form-control" placeholder="State/Provice">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
						<input type="text" id="gift-mailing-input-zipcode" class="form-control" placeholder="Zip/Postal Code">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
						<select id="gift-mailing-input-country" class="selectpicker" data-size="8" data-none-selected-text="Country">
							<?php foreach($listCountries AS $countryData){ ?>
							<option value="<?php echo $countryData['iso']; ?>"><?php echo $countryData['name']; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
						<input type="text" id="gift-mailing-input-email" class="form-control" placeholder="Email">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
						<input type="text" id="gift-mailing-input-phone" class="form-control" placeholder="Phone">
					</div>
				</td>
			</tr>
			<tr>
				<td style="padding: 12px 15px; font-size: 14px; font-family: 'Muli', Helvetica;">
					<a style="text-decoration: underline;" onclick="$('.gift-create-account-form').show();">Create an account</a> for quick and easy giving, access to your giving history, and to edit and customize your settings.
				</td>
			</tr>
			<tr class="gift-create-account-form" style="display: none;">
				<td style="text-align: center; padding-top: 20px; font-family: 'Muli', Helvetica;">
					<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
						<input type="password" id="gift-save-password" class="form-control" placeholder="Password">
					</div>
					<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
						<input type="password" id="gift-save-confirm-password" class="form-control" placeholder="Confirm Password">
					</div>
				</td>
			</tr>
			<tr>
				<td style="padding: 12px 15px;">
					<label class="checkbox-inline" style="font-size: 14px; font-family: 'Muli', Helvetica;"><input type="checkbox" id="gift-save-payment" class="gift-cc-process-fee" style="margin-top: 4px;">Save payment method information on my account</label>
				</td>
			</tr>
		</table>
	</div>
</div>
<div class="gift-section-newsletter">
	<div class="container">
		<table>
			<tr>
				<td>
					I want to stay connected with IFES updates<br>
					<label class="checkbox-inline"><input type="checkbox" class="gift-cc-process-fee" style="margin-top: 4px;" checked="">Prayerline emails (weekly)</label><br>
					<label class="checkbox-inline"><input type="checkbox" class="gift-cc-process-fee" style="margin-top: 4px;" checked="">News and prayer updates by email (6-8 per year)</label><br><br>
					<span>I am happy to be contacted by </span><label class="checkbox-inline"><input type="checkbox" class="gift-cc-process-fee" style="margin-top: 4px;" checked="">Email</label> <label class="checkbox-inline"><input type="checkbox" class="gift-cc-process-fee" style="margin-top: 4px;" checked="">Post</label> <label class="checkbox-inline"><input type="checkbox" class="gift-cc-process-fee" style="margin-top: 4px;" checked="">Phone</label>
				</td>
			</tr>
			<tr>
				<td class="gift-cc-total-style">Total monthly gift:&nbsp;<span class="gift-list-currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="gift-list-total-recurring">0.00</span></td>
			</tr>
			<tr>
				<td class="gift-cc-total-style">Total one-time gift:&nbsp;<span class="gift-list-currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="gift-list-total-onetime">0.00</span></td>
			</tr>
			<tr>
				<td>
					<div class="col-xs-6" style="padding-left: 0; font-family: 'Muli-Bold', Helvetica; font-size: 18px;">
						Thank you for your gift!
					</div>
					<div class="col-xs-6" style="text-align: right; padding-right: 0;">
						<button type="button" class="btn btn-default btn-ifes" onclick="">GIVE NOW</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<br><br>
<script type="text/javascript">
	var giftLists = [];
	var giftCurrencySymbol = '<?php echo $formCurrencySymbol; ?>';
	var giftCurrencyCode = '<?php echo $formCurrencyCode; ?>';

	function toggleCurrency(code, symbol){
		$('.gift-catalog-currency-code').html(code+' <span class="caret"></span>');
		$('.gift-catalog-currency-symbol').html(symbol);
		$('.gift-list-currency-symbol').html(symbol);
		$('.gift-cc-process-currency').html(symbol);
		giftCurrencySymbol = symbol;
		giftCurrencyCode = code;
	}

	function toggleGiftCatalogHeader(type){
		$('#toggle-gift-header-ministry, #toggle-gift-header-staff, #toggle-gift-header-movement, #toggle-gift-header-offering').removeClass('active');
		$('#toggle-gift-header-'+type).addClass('active');
		$('#gift-catalog-ministry, #gift-catalog-staff, #gift-catalog-movement, #gift-catalog-offering').hide();
		$('#gift-catalog-'+type).show();
		toggleGiftCatalog(type, 'search');
		$("#gift-catalog-"+type+"-search").prop("checked", true);
	}

	function toggleGiftCatalog(type, mode){
		$('#gift-catalog-'+type+'-search-form').hide();
		$('#gift-catalog-'+type+'-manual-form').hide();
		$('#gift-catalog-'+type+'-'+mode+'-form').show();
	}

	function searchGiftCatalog(type){
		var searchQuery = $('#gift-catalog-'+type+'-search-query').val();
		if(searchQuery.length >= 3){
			$('#gift-catalog-'+type+'-search-container').ploading({action: 'show'});
			$.ajax({
				url: HTTP_AJAX,
				type: 'POST',
				dataType: 'json',
				data:{
					opt: 'search_gift_catalog',
					type: type,
					query: searchQuery,
					currency_symbol: giftCurrencySymbol,
					currency_code: giftCurrencyCode
				}
			}).done(function(msg){
				if(msg.success){
					$('#gift-catalog-'+type+'-search-label').html('Showing most relevant results for '+searchQuery+' (Total '+msg.total+' results)');
					$('#gift-catalog-'+type+'-search-result').html(msg.template);
					rebind();
				}else{
					noty({text: "Could not get data from server. Please try again.", type: 'error'});
				}
				$('#gift-catalog-'+type+'-search-container').ploading({action: 'hide'});
			}).fail(function(jqXHR, textStatus){
				$('#gift-catalog-'+type+'-search-container').ploading({action: 'hide'});
				noty({text: "Could not connect with server. Please refresh your browser and try again.", type: 'error'});
			});
		}else{
			$('#gift-catalog-'+type+'-search-label').html('');
			$('#gift-catalog-'+type+'-search-result').html('');
			noty({text: "Please search with at least 3 characters.", type: 'error'});
		}
	}

	function addGift(obj, type, mode, desc, code){
		var giftObj = $(obj).parent().parent().find('.gift-catalog-currency-value');
		var giftValue = giftObj.val();
		if(giftValue == "" || giftValue <= 0){
			noty({text: "Please set amount for selected gift.", type: 'error'});
			giftObj.focus();
		}else{
			if(desc == ""){
				noty({text: "Please set a description for selected gift.", type: 'error'});
			}else{
				var exist = false;
				for(var i=0; i<giftLists.length; i++){
					if(giftLists[i].mode == mode && giftLists[i].type == type && giftLists[i].code == code && giftLists[i].description == desc && giftLists[i].recurring == ''){
						exist = true;
						giftLists[i].amount += Number(giftValue);
						$('#gift-list-container-'+giftLists[i].id).find('.gift-list-currency-value').html(number_format(giftLists[i].amount, 2, ".", ","));
						$('#gift-list-container-'+giftLists[i].id).find('.gift-list-input-currency').val(giftLists[i].amount);
						noty({text: "Your gift list has been updated.", type: 'information'});
						rebind();
						calcGiftList();
						break;
					}
				}

				if(!exist){
					var giftList = new Object();
					giftList.id = Math.round((new Date()).getTime() / 1000);
					giftList.type = type;
					giftList.mode = mode;
					giftList.code = code;
					giftList.description = desc;
					giftList.comment = '';
					giftList.amount = Number(giftValue);
					giftList.anonymous = false;
					giftList.recurring = '';
					giftLists.push(giftList);

					$.ajax({
						url: HTTP_AJAX,
						type: 'POST',
						dataType: 'json',
						data:{
							opt: 'generate_gift_list_item',
							type: giftList.type,
							mode: giftList.mode,
							description: giftList.description,
							amount: giftList.amount,
							id: giftList.id,
							currency_symbol: giftCurrencySymbol,
							currency_code: giftCurrencyCode
						}
					}).done(function(msg){
						if(msg.success){
							if(giftLists.length == 1){
								$('.gift-list-master').html(msg.template);
							}else{
								$('.gift-list-master').append(msg.template);
							}
							
							noty({text: "Your gift list has been updated.", type: 'information'});
							rebind();
							calcGiftList();
						}else{
							noty({text: "Could not get data from server. Please refresh your browser.", type: 'error'});
						}
					}).fail(function(jqXHR, textStatus){
						noty({text: "Could not connect with server. Please refresh your browser and try again.", type: 'error'});
					});
				}
			}
		}
	}

	function removeGiftList(index){
		for(var i=0; i<giftLists.length; i++){
			if(giftLists[i].id == index){
				giftLists.splice(i, 1);
				break;
			}
		}
		$('#gift-list-container-'+index).remove();
		if(giftLists.length == 0){
			$('.gift-list-master').html('There is no any gift in your list yet.');
		}
		calcGiftList();
	}

	function modifiyGiftList(index){
		for(var i=0; i<giftLists.length; i++){
			if(giftLists[i].id == index){
				$('#gift-list-container-'+index).find('.gift-list-input-currency').val(giftLists[i].amount);
				$('#gift-list-container-'+index).find('.gift-list-input-anonymous').prop('checked', giftLists[i].anonymous);
				$('#gift-list-container-'+index).find('.gift-list-input-comment').val(giftLists[i].comment);
				$('#gift-list-container-'+index).find('.gift-list-input-recurring').val(giftLists[i].recurring);
				break;
			}
		}
		$('#gift-list-container-'+index).children('.gift-list-container-view').hide();
		$('#gift-list-container-'+index).children('.gift-list-container-edit').show();
	}

	function cancelGiftList(index){
		$('#gift-list-container-'+index).children('.gift-list-container-view').show();
		$('#gift-list-container-'+index).children('.gift-list-container-edit').hide();
	}

	function saveGiftList(index){
		var inputCurrency = $('#gift-list-container-'+index).find('.gift-list-input-currency').val();
		if(inputCurrency == "" || inputCurrency <= 0){
			noty({text: "Please set amount for selected gift.", type: 'error'});
			return;
		}else{
			$('#gift-list-container-'+index).find('.gift-list-currency-value').html(number_format(inputCurrency, 2, ".", ","));
		}

		var inputAnonymous = $('#gift-list-container-'+index).find('.gift-list-input-anonymous');
		if(inputAnonymous.prop('checked')){
			$('#gift-list-container-'+index).find('.gift-list-view-anonymous').show();
		}else{
			$('#gift-list-container-'+index).find('.gift-list-view-anonymous').hide();
		}

		var inputComment = $('#gift-list-container-'+index).find('.gift-list-input-comment').val();
		if(inputComment == ""){
			$('#gift-list-container-'+index).find('.gift-list-view-comment').html('&nbsp;');
		}else{
			$('#gift-list-container-'+index).find('.gift-list-view-comment').html(inputComment);
		}

		var inputRecurring = $('#gift-list-container-'+index).find('.gift-list-input-recurring').val();
		if(inputRecurring == ""){
			$('#gift-list-container-'+index).find('.gift-list-view-recurring').html('One-time gift');
		}else{
			$('#gift-list-container-'+index).find('.gift-list-view-recurring').html('Monthly Gift on the '+inputRecurring);
		}

		$('#gift-list-container-'+index).children('.gift-list-container-view').show();
		$('#gift-list-container-'+index).children('.gift-list-container-edit').hide();

		for(var i=0; i<giftLists.length; i++){
			if(giftLists[i].id == index){
				giftLists[i].amount = Number(inputCurrency);
				giftLists[i].recurring = inputRecurring;
				giftLists[i].comment = inputComment;
				giftLists[i].anonymous = inputAnonymous.prop('checked');
				break;
			}
		}
		calcGiftList();
	}

	function calcGiftList(){
		var tmpRecurring = 0;
		var tmpOnetime = 0;
		for(var i=0; i<giftLists.length; i++){
			if(giftLists[i].recurring == ''){
				tmpOnetime += giftLists[i].amount;
			}else{
				tmpRecurring += giftLists[i].amount;
			}
		}

		if($('#gift-cc-process-fee').prop('checked')){
			if(tmpOnetime > 0){
				tmpOnetime += 5;
			}
			if(tmpRecurring > 0){
				tmpRecurring += 5;
			}
		}

		$('.gift-list-total-recurring').html(number_format(tmpRecurring, 2, ".", ","));
		$('.gift-list-total-onetime').html(number_format(tmpOnetime, 2, ".", ","));
	}

	$(document).ready(function(){
		toggleGiftCatalogHeader('staff');
		rebind();
	});

	$("#gift-catalog-staff-search-query").on('keyup', function (e){
		if(e.keyCode === 13){
			searchGiftCatalog('staff');
		}
	});

	$("#gift-catalog-ministry-search-query").on('keyup', function (e){
		if(e.keyCode === 13){
			searchGiftCatalog('ministry');
		}
	});

	$("#gift-catalog-movement-search-query").on('keyup', function (e){
		if(e.keyCode === 13){
			searchGiftCatalog('movement');
		}
	});

	$('.gift-catalog-currency-value').on('keyup', function (e){
		if(e.keyCode === 13){
			$(e.target).next('div').children('.btn-add-gift').click();
		}
	});

	function rebind(){
		$('.datetimepicker').datetimepicker({
			format: 'Do',
			allowInputToggle: true
		});

		$('.gift-list-input-currency').on('keyup', function (e){
			if(e.keyCode === 13){
				$(e.target).parent().parent().parent().find('.gift-list-input-save').click();
			}
		});

		$('.gift-list-input-comment').on('keyup', function (e){
			if(e.keyCode === 13){
				$(e.target).parent().parent().parent().find('.gift-list-input-save').click();
			}
		});
	}

	function toggleCardPayment(){
		if($('#gift-cc-form-new').is(':visible')){
			$('#gift-cc-input-number').val('');
			$('#gift-cc-input-name').val('');
			$('#gift-cc-input-expiration').val('');
			$('#gift-cc-input-cvv').val('');
		}
		$('#gift-cc-form-select').toggle();
		$('#gift-cc-form-new').toggle();
	}
</script>