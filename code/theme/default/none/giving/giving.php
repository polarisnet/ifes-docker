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
<div class="container paddingless">
	<div class="col-xs-12 col-md-6">
		<img src="<?php echo HTTP_MEDIA.'/site-image/ifes-logo.png';?>" width="131" style="margin-top: -1px; margin-bottom: 28px;">
		<p class="page-title">GIVING PAGE</p>
		<p class="page-title-content">Donate towards the various ministries and work of IFES worldwide.<br>To make a non-donation payment, please visit our Payment Page.</p>
	</div>
	<div class="col-xs-12 col-md-6" style="position: relative;">
		<div class="gift-upper-box">
			<p class="page-title-content">AFRICA STAFF TRAINING INSTITUTES</p>
			<p class="page-title-content"><br><a>Learn more</a> about encouraging<br>and equiping staff in Africa.<br><br></p>
			<div class="input-group currency-box">
				<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
				<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
					<?php echo $formCurrencyToogle; ?>
					<button type="button" class="btn btn-default btn-ifes" onclick="addGift(this, 'staff', 'search', 'AFRICA STAFF TRAINING INSTITUTES', '');">ADD GIFT</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container gift-center-link">
	<a>LEGACY GIVING</a> | <a>NON-CASH GIFTS</a> | <a>GIVE BY PHONE OR MAIL</a> | <a>FAQ</a> | <a>PAYMENT PAGE</a> | <a>HELP</a>
</div>
<div class="container">
	<br>
	<p class="gift-title">IFES Gift Catalog</p>
	<br>
	<table class="gift-catalog-table">
		<tr class="header">
			<td id="toggle-gift-header-ministry" onclick="toggleGiftCatalogHeader('ministry');">IFES Ministry</td>
			<td id="toggle-gift-header-staff" onclick="toggleGiftCatalogHeader('staff');">Staff Worker</td>
			<td id="toggle-gift-header-movement" onclick="toggleGiftCatalogHeader('movement');">National Movement</td>
			<?php if(REGION == 'us'){ ?>
			<td id="toggle-gift-header-offering" onclick="toggleGiftCatalogHeader('offering');">Offerings</td>
			<?php } ?>
		</tr>
		<tr>
			<td colspan="<?php if(REGION == 'us'){echo '4';}else{echo '3';} ?>" style="padding: 25px; height: 500px; vertical-align: top;">
				<div id="gift-catalog-ministry" style="display: none;">
					<label class="radio-inline"><input type="radio" id="gift-catalog-ministry-search" name="radio-gift-catalog-ministry" checked onclick="toggleGiftCatalog('ministry', 'search');">Search</label>
					<label class="radio-inline"><input type="radio" id="gift-catalog-ministry-manual" name="radio-gift-catalog-ministry" onclick="toggleGiftCatalog('ministry', 'manual');">Enter an IFES Ministry</label>
					<br><br>
					<div id="gift-catalog-ministry-search-form" style="display: none;">
						Your support will strengthen a specific ministry to students and national movements worldwide.
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
									<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<?php echo $formCurrencyToogle; ?>
										<button type="button" class="btn btn-default btn-ifes" onclick="addGift(this, 'ministry', 'manual', $('#gift-catalog-search-ministry-manual-input').val(), '');">ADD GIFT</button>
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
									<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<?php echo $formCurrencyToogle; ?>
										<button type="button" class="btn btn-default btn-ifes" onclick="addGift(this, 'staff', 'manual', $('#gift-catalog-search-manual-input').val(), '');">ADD GIFT</button>
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
						Your gift will equip students to share and live out the good news of Jesus Christ in their own culture and context.
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
									<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<?php echo $formCurrencyToogle; ?>
										<button type="button" class="btn btn-default btn-ifes" onclick="addGift(this, 'movement', 'manual', $('#gift-catalog-search-movement-manual-input').val(), '');">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if(REGION == 'us'){ ?>
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
									<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<?php echo $formCurrencyToogle; ?>
										<button type="button" class="btn btn-default btn-ifes" onclick="addGift(this, 'offering', 'search', $('#gift-catalog-offering-select option:selected').text(), $('#gift-catalog-offering-select').val());">ADD GIFT</button>
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
									<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<?php echo $formCurrencyToogle; ?>
										<button type="button" class="btn btn-default btn-ifes" onclick="addGift(this, 'offering', 'manual', $('#gift-catalog-offering-manual-input').val(), '');">ADD GIFT</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</td>
		</tr>
	</table>
	<div class="gift-catalog-template" style="display: none;">
		<div class="result-container">
			<div class="col-xs-8 result-label">%templateDescription%</div>
			<div class="col-xs-4 result-form">
				<div class="input-group currency-box">
					<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
					<input type="number" min="0" class="form-control gift-catalog-currency-value" aria-label="..." placeholder="0.00">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
						<?php echo $formCurrencyToogle; ?>
						<button type="button" class="btn btn-default btn-ifes" style="margin-left: 10px;" onclick="addGift(this, '%templateSearchType%', 'search', '%templateDescription%', '%templateCode%')">ADD GIFT</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr class="gift-catalog-linebreak" style="<?php if(empty($_POST)){echo "display: none;";} ?>">
<div class="gift-list-outer-container container" style="<?php if(empty($_POST)){echo "display: none;";} ?>">
	<table class="gift-list-table">
		<tr>
			<td class="gift-title">Your Gift List</td>
		</tr>
		<tr>
			<td>
				<div class="gift-list-master">There is no any gift in your list yet.</div>
				<div class="gift-list-template" style="display: none;">
					<div id="gift-list-container-%templateId%" class="gift-list-container">
						<div class="gift-list-container-view" style="display: block;">
							<div class="col-xs-8" style="padding-left: 0;">
								<div style="padding: 6px 0;">%templateDescription%</div>
								<div class="gift-list-view-comment">%templateCommentFormat%</div>
								<div class="gift-list-view-anonymous" style="%templateAnonymousStyle%">Anonymous Gift</div>
							</div>
							<div class="col-xs-4" style="padding-right: 0; text-align: right;">
								<div style="padding: 6px 0;"><span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span> <span class="currency-value">%templateAmountFormat%</span></div>
								<div class="gift-list-view-recurring">%templateRecurringFormat%</div>
								<div style="padding: 6px 0;">
									<div><a onclick="modifiyGiftList('%templateId%');">Modify</a> | <a onclick="removeGiftList('%templateId%');">Remove</a></div>
								</div>
							</div>
						</div>
						<div class="gift-list-container-edit" style="display: none;">
							<div class="col-xs-8" style="padding-left: 0;">
								<div style="padding: 6px 0;">%templateDescription%</div>
								<div style="padding: 5px 0;"><input type="text" class="form-control gift-list-input-comment" placeholder="Add comment or instructions for the finance office." value="%templateComment%"></div>
								<div><label class="checkbox-inline" style="font-size: 16px;"><input type="checkbox" class="gift-list-input-anonymous" %templateAnonymous%>Anonymous Gift</label></div>	
							</div>
							<div class="col-xs-4" style="padding-right: 0; text-align: right;">
								<div class="input-group currency-box" style="float:right; width: 260px;">
									<span class="input-group-addon currency-symbol"><?php echo $formCurrencySymbol; ?></span>
									<input type="number" min="0" class="form-control gift-list-currency-value" aria-label="..." placeholder="0.00" value='%templateAmount%'>
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle currency-code" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $formCurrencyCode; ?> <span class="caret"></span></button>
										<?php echo $formCurrencyToogle; ?>
									</div>
								</div>
								<div style="padding: 5px 0; clear: both;">
									<div class="input-group date datetimepicker gift-list-datepicker">
										<input type="text" class="form-control gift-list-input-recurring" value="%templateRecurring%" />
										<span class="input-group-addon" style="padding-bottom: 7px;">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="gift-list-label-recurring">Monthly Gift on the</div>
								</div>
								<div class="gift-list-save-container">
									<div><a class="gift-list-input-save" onclick="saveGiftList('%templateId%');">Save</a> | <a onclick="cancelGiftList('%templateId%');">Cancel</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="gift-total">Total monthly gift:&nbsp;<span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="total-recurring">0.00</span></td>
		</tr>
		<tr>
			<td class="gift-total">Total one-time gift:&nbsp;<span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="total-onetime">0.00</span></td>
		</tr>
		<?php if(!matchCookieSession()){ ?>
		<tr>
			<td class="gift-list-login">
				<div class="login-container">
					<div class="col-xs-6" style="padding-left: 0; padding-right: 10px;">
						<input type="text" class="form-control" placeholder="User Name" id="login-username">
					</div>
					<div class="col-xs-6" style="padding-left: 10px; padding-right: 0;">
						<input type="password" class="form-control" placeholder="Password" id="login-password">
					</div>
					<div class="col-xs-12" style="padding: 10px; padding-right: 0;">
						<a style="font-size: 14px;">Forget your password?</a>
					</div>
					<div class="col-xs-12 paddingless">
						<button type="button" class="btn btn-default btn-ifes" style="margin-right: 15px;" onclick="login();">LOG IN</button>
						<button type="button" class="btn btn-default btn-ifes" onclick="revealPayment();">GIVE AS GUEST</button>
					</div>
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr><td style="padding-bottom: 20px;"></td></tr>
	</table>
</div>
<form id="gift-submit-form" role="form" method="post" onsubmit="return validateForm();">
	<div class="gift-payment" style="<?php if(empty($_POST)){echo "display: none;";} ?>">
		<div class="container">
			<table class="gift-payment-table">
				<tr>
					<td class="gift-title">Secure Payment Information</td>
				</tr>
				<?php if(REGION == 'us'){ ?>
				<tr>
					<td style="text-align: center; padding-top: 10px;">
						<button type="button" class="btn btn-default btn-ifes" style="margin-right: 15px;" onclick="toggleUSPayment('cc');">GIVE BY CREDIT OR DEBIT CARD</button>
						<button type="button" class="btn btn-default btn-ifes" onclick="toggleUSPayment('check');">GIVE BY eCHECK</button>
						<input type="hidden" id="payment-us-paymode" name="payment-us-paymode" value="cc">
					</td>
				</tr>
				<?php }else if(REGION == 'uk'){ ?>
				<tr>
					<td style="padding: 12px 15px;">
						<label class="checkbox-inline"><input type="checkbox" id="gift-uk-extra-aid" name="payment-uk-extra-aid" onclick="toggleUKExtraAid();" <?php if($formPaymentUKExtraAid == "on"){echo 'checked';} ?>>Gift Aid my donation to add an extra <span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span> 15.00</label>
						<div id="gift-uk-extra-aid-form" style="<?php if($formPaymentUKExtraAid == "on"){echo 'display: block;';}?>">
							I want to Gift Aid my donation to IFES, any donations that I have made in the last four years, or from 
							<div style="display: inline-block; max-width: 140px;">
								<div class="input-group date datetimepicker1 gift-extra-aid-datepicker" style="top: 10px;">
									<input type="text" id="payment-uk-extra-aid-date" class="form-control" name="payment-uk-extra-aid-date" value="<?php echo $formPaymentUKExtraAidDate; ?>" />
									<span class="input-group-addon" style="padding-bottom: 7px;">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div> and all donations I make in the future.<br><br>
							I am a UK taxpayer and understand that if I pay less Income Tax and/or Capital Gains Tax than the amount of Gift Aid claimed on all my donations in that tax year it is my responsibility to pay any difference. IFES will claim 25p for each &pound; 1 I’ve given. I will notify IFES if I want to cancel this declaration, change my name or home address or no longer pay sufficient Income and/or Capital Gains Tax.
						</div>
					</td>
				</tr>
				<?php } ?>
				<tr id="gift-echeck-form" style="display: none;">
					<td>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-echeck-input-acc-no" name="payment-echeck-acc-no" class="form-control" placeholder="Account Number">
						</div>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-echeck-input-route-no" name="payment-echeck-router-no" class="form-control" placeholder="Route Number">
						</div>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-echeck-input-bank-name" name="payment-echeck-bank-name" class="form-control" placeholder="Bank Name">
						</div>
						<div class="col-xs-6" style="padding-right: 5px; padding-bottom: 10px;">
							<input type="text" id="gift-echeck-input-name" name="payment-echeck-name" class="form-control" placeholder="Name on Account">
						</div>
						<div class="col-xs-6" style="padding-left: 5px; padding-bottom: 10px;">
							<select id="gift-echeck-input-type" name="payment-echeck-type" class="selectpicker" data-size="8" data-none-selected-text="Checking">
								<option value="checking">Checking</option>
								<option value="savings">Savings</option>
							</select>
						</div>
					</td>
				</tr>
				<?php if(!empty($listCreditCards)){ ?>
				<tr id="gift-cc-form-select">
					<td>
						<div class="col-xs-12">
							<select id="gift-cc-select" class="selectpicker" data-size="8" name="payment-cc-select" data-none-selected-text="Please select a credit card">
								<?php foreach($listCreditCards AS $creditCardData){ ?>
								<option value="<?php echo $creditCardData['id']; ?>"><?php echo ccMasking($creditCardData['number']); ?></option>
								<?php } ?>	
							</select>
						</div>
						<div class="col-xs-12" style="text-align: right; padding-top: 10px;">
							<?php if(!empty($listCreditCards)){ ?><button type="button" class="btn btn-default btn-ifes" onclick="toggleCardPayment('edit');" style="margin-right: 15px;">EDIT CARD</button><?php } ?>
							<button type="button" class="btn btn-default btn-ifes" onclick="toggleCardPayment('new');">NEW CARD</button>
						</div>
					</td>
				</tr>
				<?php } ?>
				<tr id="gift-cc-form-new" style="<?php if(!empty($listCreditCards)){echo "display: none;";} ?>">
					<td>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="hidden" id="gift-cc-input-mode" name="payment-cc-mode" value="<?php if(empty($listCreditCards)){echo "new";}else{echo "select";} ?>">
							<input type="text" id="gift-cc-input-number" name="payment-cc-number" class="form-control" placeholder="Card Number" value="<?php echo $formPaymentCCNumber; ?>">
						</div>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-cc-input-name" name="payment-cc-name" class="form-control" placeholder="Name on Card" value="<?php echo $formPaymentCCName; ?>">
						</div>
						<div class="col-xs-6" style="padding-right: 5px;">
							<input type="text" id="gift-cc-input-expiration" name="payment-cc-expiration" class="form-control" placeholder="Expiration MM/YY" value="<?php echo $formPaymentCCExpiration; ?>">
						</div>
						<div class="col-xs-6" style="padding-left: 5px;">
							<input type="text" id="gift-cc-input-cvv" name="payment-cc-cvv" class="form-control" placeholder="CVV">
						</div>
						<?php if(!empty($listCreditCards)){ ?>
						<div class="col-xs-12" style="text-align: right; padding-top: 10px;">
							<button type="button" class="btn btn-default btn-ifes" onclick="toggleCardPayment('select');">CANCEL</button>
						</div>
						<?php } ?>
					</td>
				</tr>
				<tr id="gift-cc-form-process-fee">
					<td>
						<label class="checkbox-inline"><input type="checkbox" id="gift-cc-input-process-fee" name="payment-cc-process-fee" onclick="calcGiftList();" <?php if($formPaymentCCProcessFee != ""){echo 'checked';} ?>>I’d like to increase my donation by <span class='currency-symbol'><?php echo $formCurrencySymbol; ?></span> 5.00 to help towards the cost of online transactions.</label>
					</td>
				</tr>
				<tr>
					<td class="gift-total">Total monthly gift:&nbsp;<span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="total-recurring">0.00</span></td>
				</tr>
				<tr>
					<td class="gift-total">Total one-time gift:&nbsp;<span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="total-onetime">0.00</span></td>
				</tr>
				<tr>
					<td class="gift-title" style="padding-top: 40px;">Billing Information</td>
				</tr>
				<tr id="gift-cc-form-billing">
					<td style="text-align: center; padding-top: 20px;">
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-billing-input-name" name="payment-billing-name" class="form-control" placeholder="Full Name" value="<?php echo $formPaymentBillingName; ?>">
						</div>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-billing-input-address1" name="payment-billing-address1" class="form-control" placeholder="Address 1" value="<?php echo $formPaymentBillingAddress1; ?>">
						</div>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-billing-input-address2" name="payment-billing-address2" class="form-control" placeholder="Address 2" value="<?php echo $formPaymentBillingAddress2; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
							<input type="text" id="gift-billing-input-city" name="payment-billing-city" class="form-control" placeholder="City" value="<?php echo $formPaymentBillingCity; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
							<input type="text" id="gift-billing-input-state" name="payment-billing-state" class="form-control" placeholder="State/Provice" value="<?php echo $formPaymentBillingState; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
							<input type="text" id="gift-billing-input-zipcode" name="payment-billing-zipcode" class="form-control" placeholder="Zip/Postal Code" value="<?php echo $formPaymentBillingZipcode; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
							<select id="gift-billing-input-country" name="payment-billing-country" class="selectpicker" data-size="8" data-none-selected-text="Country">
								<?php foreach($listCountries AS $countryData){ ?>
								<option value="<?php echo $countryData['iso']; ?>" <?php if($countryData['iso'] == $formPaymentBillingCountry){echo 'selected';}?>><?php echo $countryData['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
							<input type="text" id="gift-billing-input-email" name="payment-billing-email" class="form-control" placeholder="Email" value="<?php echo $formPaymentBillingEmail; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
							<input type="text" id="gift-billing-input-phone" name="payment-billing-phone" class="form-control" placeholder="Phone" value="<?php echo $formPaymentBillingPhone; ?>">
						</div>
					</td>
				</tr>
				<tr>
					<td style="padding: 12px 15px;">
						<label class="checkbox-inline"><input type="checkbox" id="gift-add-mailing" name="payment-add-mailing-address" onclick="$('#gift-cc-form-mailing').toggle();" <?php if($formPaymentAddMailing != ''){echo 'checked';} ?>>Add a preferred mailing address</label>
					</td>
				</tr>
				<tr id="gift-cc-form-mailing" style="<?php if($formPaymentAddMailing == ''){echo 'display: none;';} ?>">
					<td style="text-align: center; padding-top: 20px; font-family: 'Muli', Helvetica;">
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-mailing-input-name" name="payment-mailing-name" class="form-control" placeholder="Full Name" value="<?php echo $formPaymentMailingName; ?>">
						</div>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-mailing-input-address1" name="payment-mailing-address1" class="form-control" placeholder="Address 1" value="<?php echo $formPaymentMailingAddress1; ?>">
						</div>
						<div class="col-xs-12" style="padding-bottom: 10px;">
							<input type="text" id="gift-mailing-input-address2" name="payment-mailing-address2" class="form-control" placeholder="Address 2" value="<?php echo $formPaymentMailingAddress2; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
							<input type="text" id="gift-mailing-input-city" name="payment-mailing-city" class="form-control" placeholder="City" value="<?php echo $formPaymentMailingCity; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
							<input type="text" id="gift-mailing-input-state" name="payment-mailing-state" class="form-control" placeholder="State/Provice" value="<?php echo $formPaymentMailingState; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
							<input type="text" id="gift-mailing-input-zipcode" name="payment-mailing-zipcode" class="form-control" placeholder="Zip/Postal Code" value="<?php echo $formPaymentMailingZipcode; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
							<select id="gift-mailing-input-country" name="payment-mailing-country" class="selectpicker" data-size="8" data-none-selected-text="Country">
								<?php foreach($listCountries AS $countryData){ ?>
								<option value="<?php echo $countryData['iso']; ?>" <?php if($countryData['iso'] == $formPaymentMailingCountry){echo 'selected';}?>><?php echo $countryData['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
							<input type="text" id="gift-mailing-input-email" name="payment-mailing-email" class="form-control" placeholder="Email" value="<?php echo $formPaymentMailingEmail; ?>">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
							<input type="text" id="gift-mailing-input-phone" name="payment-mailing-phone" class="form-control" placeholder="Phone" value="<?php echo $formPaymentMailingPhone; ?>">
						</div>
					</td>
				</tr>
				<?php if(!$isLogin){ ?>
				<tr id="gift-create-account-form-1">
					<td>
						<label class="checkbox-inline"><input type="checkbox" id="gift-create-account" name="payment-create-account" onclick="toggleCreateAccount();" <?php if($formPaymentCreateAccount != ""){echo 'checked';} ?>><a style="text-decoration: underline;">Create an account</a> for quick and easy giving, access to your giving history, and to edit and customize your settings.</label>
					</td>
				</tr>
				<tr id="gift-create-account-form-2" style="display: none;">
					<td style="text-align: center;">
						<div class="col-xs-6" style="padding-bottom: 10px; padding-right: 5px;">
							<input type="password" id="gift-save-password" name="payment-account-password" class="form-control" placeholder="Password">
						</div>
						<div class="col-xs-6" style="padding-bottom: 10px; padding-left: 5px;">
							<input type="password" id="gift-save-confirm-password" name="payment-account-confirm-password" class="form-control" placeholder="Confirm Password">
						</div>
					</td>
				</tr>
				<?php } ?>
				<tr id="gift-payment-save-details" style="<?php if(!$isLogin){ ?>display: none;<?php } ?>">
					<td>
						<label class="checkbox-inline"><input type="checkbox" id="gift-save-payment" name="payment-save-information" <?php if($formPaymentSaveInformation != ""){echo 'checked';} ?>>Save payment method information on my account</label>
					</td>
				</tr>
				<?php if(REGION == 'us'){ ?>
				<tr id="gift-payment-us-receipt">
					<td><label class="radio-inline"><input type="radio" name="payment-preferred-receipt" value="email" <?php if($formPaymentPreferredReceipt == 'email'){echo 'checked';} ?>>Email Receipt</label> <label class="radio-inline"><input type="radio" name="payment-preferred-receipt" value="paper" <?php if($formPaymentPreferredReceipt == 'paper'){echo 'checked';} ?>>Paper Receipt</label></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	<div class="gift-newsletter" style="<?php if(empty($_POST)){echo "display: none;";} ?>">
		<div class="container">
			<table>
				<tr>
					<td>
						<?php if(REGION == "us"){ ?>
						I want to stay connected with IFES updates<br>
						<label class="checkbox-inline"><input type="checkbox" name="newsletter-us-weekly" <?php if($formNewsletterUSWeekly == "on"){echo 'checked';}?>>Prayerline emails (weekly)</label><br>
						<label class="checkbox-inline"><input type="checkbox" name="newsletter-us-bimonthly" <?php if($formNewsletterUSBimonthly == "on"){echo 'checked';}?>>Praise &amp; Prayer (bimonthly)</label> <label class="radio-inline"><input type="radio" name="newsletter-us-bimonthly-mode" value="email" <?php if($formNewsletterUSBimonthlyMode == "email"){echo 'checked';} ?>>By Email</label><label class="radio-inline"><input type="radio" name="newsletter-us-bimonthly-mode" value="mail" <?php if($formNewsletterUSBimonthlyMode == "mail"){echo 'checked';} ?>>By Mail</label><br>
						<?php }else if(REGION == "uk"){ ?>
							IFES would love to keep you up to date with student ministry news and prayer information (6-8 per year) by post.<br>
							<label class="checkbox-inline"><input type="checkbox" name="newsletter-uk-email" <?php if($formNewsletterUKEmail == "on"){echo 'checked';} ?>>Please send me these updates by EMAIL</label><br>
							<label class="checkbox-inline"><input type="checkbox" name="newsletter-uk-not" <?php if($formNewsletterUKNot == "on"){echo 'checked';} ?>>Please do NOT send me these updates</label><br>
							<label class="checkbox-inline"><input type="checkbox" name="newsletter-uk-email-weekly" <?php if($formNewsletterUKEmailWeekly == "on"){echo 'checked';} ?>>Please also send me Prayerline emails (weekly)</label><br>
							<span>I am happy to be contacted by </span><label class="checkbox-inline" name="newsletter-uk-contact"><input type="checkbox" name="newsletter-uk-contact-email" <?php if($formNewsletterUKContactEmail == 'on'){echo 'checked';} ?>>Email</label> <label class="checkbox-inline"><input type="checkbox" name="newsletter-uk-contact-post" <?php if($formNewsletterUKContactPost == 'on'){echo 'checked';} ?>>Post </label><label class="checkbox-inline"><input type="checkbox" name="newsletter-uk-contact-phone" <?php if($formNewsletterUKContactPhone == 'on'){echo 'checked';} ?>>Phone</label>
						<?php }else{ ?>
						I want to stay connected with IFES updates<br>
						<label class="checkbox-inline"><input type="checkbox" name="newsletter-row-weekly" <?php if($formNewsletterROWWeekly == "on"){echo 'checked';} ?>>Prayerline emails (weekly)</label><br>
						<label class="checkbox-inline"><input type="checkbox" name="newsletter-row-yearly" <?php if($formNewsletterROWYearly == "on"){echo 'checked';} ?>>News and prayer updates by email (6-8 per year)</label><br><br>
						<span>I am happy to be contacted by </span><label class="checkbox-inline"><input type="checkbox" name="newsletter-row-email" <?php if($formNewsletterROWEmail == "on"){echo 'checked';} ?>>Email</label> <label class="checkbox-inline"><input type="checkbox" name="newsletter-row-post" <?php if($formNewsletterROWPost == 'on'){echo 'checked';}?>>Post</label> <label class="checkbox-inline"><input type="checkbox" name="newsletter-row-phone" <?php if($formNewsletterROWPhone == 'on'){echo 'checked';}?>>Phone</label>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td class="gift-total">Total monthly gift:&nbsp;<span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="total-recurring">0.00</span></td>
				</tr>
				<tr>
					<td class="gift-total">Total one-time gift:&nbsp;<span class="currency-symbol"><?php echo $formCurrencySymbol; ?></span>&nbsp;<span class="total-onetime">0.00</span></td>
				</tr>
				<tr>
					<td>
						<div class="col-xs-6" style="padding-left: 0; padding-top: 4px; font-family: 'Muli-Bold', Helvetica; font-size: 18px;">
							Thank you for your gift!
						</div>
						<div class="col-xs-6" style="text-align: right; padding-right: 0;">
							<button type="button" class="btn btn-default btn-ifes" onclick="$('#gift-submit-form').submit();">GIVE NOW</button>
						</div>
					</td>
				</tr>
				<?php if(REGION == 'us'){ ?>
				<tr>
					<td>
						<table class="gift-newsletter-us-footer" style="width: 100%;">
							<tr>
								<td style="width: 60px;"><img src="<?php echo HTTP_MEDIA;?>/site-image/ecfa_logo.jpg"></td>
								<td style="width: 60px;"><img src="<?php echo HTTP_MEDIA;?>/site-image/guidestar.jpg"></td>
								<td>This contribution is made with the understanding that IFES/USA has complete discretion and control over the use of the donated funds. If IFES cannot honor your preference, your gift will be used where most needed.</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	<div id="submit-variable" style="display: none;"></div>
</form>
<br><br>
<script type="text/javascript">
	var giftLists = [];
	var giftCurrencySymbol = '<?php echo $formCurrencySymbol; ?>';
	var giftCurrencyCode = '<?php echo $formCurrencyCode; ?>';

	<?php if(!$isLogin){ ?>
	function login(){
		$('.gift-list-outer-container').ploading({action: 'show'});
		$.ajax({
			url: HTTP_AJAX,
			type: 'POST',
			dataType: 'json',
			data:{
				opt: 'login',
				username: $('#login-username'),
				password: $('#login-password')
			}
		}).done(function(msg){
			if(msg.success){
			/*	$('#gift-catalog-'+type+'-search-label').html('Showing most relevant results for '+searchQuery+' (Total '+msg.total+' results)');
				var tmpTemplates = "";
				var catalogTemplate = $('.gift-catalog-template').html();
				for(var i=0; i<msg.total; i++){
					var tmpTemplate = catalogTemplate;
					tmpTemplate = tmpTemplate.replace(/%templateDescription%/g, msg.result[i].destinationdescription);
					tmpTemplate = tmpTemplate.replace(/%templateCode%/g, msg.result[i].destinationcode);
					tmpTemplate = tmpTemplate.replace(/%templateSearchType%/g, type);
					tmpTemplates += tmpTemplate;
				}
				$('#gift-catalog-'+type+'-search-result').html(tmpTemplates);
				rebind();*/
			}else{
				noty({text: "Could not get data from server. Please try again.", type: 'error'});
			}
			$('.gift-list-outer-container').ploading({action: 'hide'});
		}).fail(function(jqXHR, textStatus){
			$('.gift-list-outer-container').ploading({action: 'hide'});
			noty({text: "Could not connect with server. Please refresh your browser and try again.", type: 'error'});
		});
	}
	<?php } ?>

	function toggleCurrency(code, symbol){
		$('.currency-code').html(code+' <span class="caret"></span>');
		$('.currency-symbol').html(symbol);
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
					query: searchQuery
				}
			}).done(function(msg){
				if(msg.success){
					$('#gift-catalog-'+type+'-search-label').html('Showing most relevant results for '+searchQuery+' (Total '+msg.total+' results)');
					var tmpTemplates = "";
					var catalogTemplate = $('.gift-catalog-template').html();
					for(var i=0; i<msg.total; i++){
						var tmpTemplate = catalogTemplate;
						tmpTemplate = tmpTemplate.replace(/%templateDescription%/g, msg.result[i].destinationdescription);
						tmpTemplate = tmpTemplate.replace(/%templateCode%/g, msg.result[i].destinationcode);
						tmpTemplate = tmpTemplate.replace(/%templateSearchType%/g, type);
						tmpTemplates += tmpTemplate;
					}
					$('#gift-catalog-'+type+'-search-result').html(tmpTemplates);
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
		$('.gift-catalog-linebreak, .gift-list-outer-container').show();
		<?php if($isLogin){ ?>revealPayment();<?php } ?>
		var objCurrency = $(obj).parent().parent();
		var giftValue = objCurrency.find('.gift-catalog-currency-value').val();
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
						$('#gift-list-container-'+giftLists[i].id).find('.currency-value').html(number_format(giftLists[i].amount, 2, ".", ","));
						$('#gift-list-container-'+giftLists[i].id).find('.gift-list-currency-value').val(giftLists[i].amount);
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

					renderGiftLists(giftLists.length-1);
					noty({text: "Your gift list has been updated.", type: 'information'});
					rebind();
					calcGiftList();
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

	function renderGiftLists(index){
		var tmpTemplate = $('.gift-list-template').html();
		tmpTemplate = tmpTemplate.replace(/%templateId%/g, giftLists[index].id);
		tmpTemplate = tmpTemplate.replace(/%templateDescription%/g, giftLists[index].description);
		tmpTemplate = tmpTemplate.replace(/%templateComment%/g, giftLists[index].comment);
		if(giftLists[index].comment != ""){
			tmpTemplate = tmpTemplate.replace(/%templateCommentFormat%/g, giftLists[index].comment);
		}else{
			tmpTemplate = tmpTemplate.replace(/%templateCommentFormat%/g, '&nbsp;');
		}
		tmpTemplate = tmpTemplate.replace(/%templateAmount%/g, giftLists[index].amount);
		tmpTemplate = tmpTemplate.replace(/%templateAmountFormat%/g, number_format(giftLists[index].amount, 2, ".", ","));
		tmpTemplate = tmpTemplate.replace(/%templateRecurring%/g, giftLists[index].recurring);
		if(giftLists[index].recurring == ""){
			tmpTemplate = tmpTemplate.replace(/%templateRecurringFormat%/g, 'One-time gift');
		}else{
			tmpTemplate = tmpTemplate.replace(/%templateRecurringFormat%/g, 'Monthly Gift on the '+giftLists[index].recurring);
		}

		if(giftLists[index].anonymous){
			tmpTemplate = tmpTemplate.replace(/%templateAnonymous%/g, 'checked');
			tmpTemplate = tmpTemplate.replace(/%templateAnonymousStyle%/g, 'display: block;');
		}else{
			tmpTemplate = tmpTemplate.replace(/%templateAnonymous%/g, '');
			tmpTemplate = tmpTemplate.replace(/%templateAnonymousStyle%/g, '');
		}

		if(giftLists.length == 1){
			$('.gift-list-master').html(tmpTemplate);
		}else{
			$('.gift-list-master').append(tmpTemplate);
		}
	}

	function modifiyGiftList(index){
		for(var i=0; i<giftLists.length; i++){
			if(giftLists[i].id == index){
				$('#gift-list-container-'+index).find('.gift-list-currency-value').val(giftLists[i].amount);
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
		var inputCurrency = $('#gift-list-container-'+index).find('.gift-list-currency-value').val();
		if(inputCurrency == "" || inputCurrency <= 0){
			noty({text: "Please set amount for selected gift.", type: 'error'});
			return;
		}else{
			$('#gift-list-container-'+index).find('.currency-value').html(number_format(inputCurrency, 2, ".", ","));
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
		noty({text: "Your gift list has been updated.", type: 'information'});
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

		if($('#payment-us-paymode').val() != 'check'){
			if($('#gift-cc-input-process-fee').prop('checked')){
				if(tmpOnetime > 0){
					tmpOnetime += 5;
				}
				if(tmpRecurring > 0){
					tmpRecurring += 5;
				}
			}
		}

		if($('#gift-uk-extra-aid').prop('checked')){
			if(tmpOnetime > 0){
				tmpOnetime += 15;
			}
			if(tmpRecurring > 0){
				tmpRecurring += 15;
			}
		}

		$('.total-recurring').html(number_format(tmpRecurring, 2, ".", ","));
		$('.total-onetime').html(number_format(tmpOnetime, 2, ".", ","));
	}

	$(document).ready(function(){
		toggleGiftCatalogHeader('staff');
		rebind();

		$('.datetimepicker1').datetimepicker({
			format: 'DD/MM/YYYY',
			allowInputToggle: true
		});

		$("#gift-cc-input-expiration").inputmask("99/99", {placeholder: 'MM/YY', "clearIncomplete": true});
		$("#gift-billing-input-email, #gift-payment-input-email").inputmask("email");

		<?php if(!empty($formGiftLists)){
				foreach($formGiftLists AS $listKey => $listData){ ?>
		var giftList = new Object();
		giftList.id = <?php echo (time()+$listKey); ?>;
		giftList.type = '<?php echo $listData["type"]; ?>';
		giftList.mode = '<?php echo $listData["mode"]; ?>';
		giftList.code = '<?php echo $listData["code"]; ?>';
		giftList.description = '<?php echo $listData["description"]; ?>';
		giftList.comment = '<?php echo $listData["comment"]; ?>';
		giftList.amount = <?php echo $listData["amount"]; ?>;
		giftList.anonymous = <?php echo $listData["anonymous"]; ?>;
		giftList.recurring = '<?php echo $listData["recurring"]; ?>';
		giftLists.push(giftList);
		<?php	} ?>
		for(var i=0; i<giftLists.length; i++){
			renderGiftLists(i);
		}
		rebind();
		calcGiftList();
		<?php	} ?>
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

	function validateCC(){
		if($('#gift-cc-input-mode').val() != 'select'){
			if(!bootstrapValidateEmpty("gift-cc-input-number", "")){
				noty({text: "Please fill in card number.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-cc-input-name", "")){
				noty({text: "Please fill in name on card.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-cc-input-expiration", "")){
				noty({text: "Please fill in card expiration.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-cc-input-cvv", "")){
				noty({text: "Please fill in card cvv.", type: 'error'});
				return false;
			}
		}
		return true;
	}

	function validateForm(){
		if(giftLists.length == 0){
			noty({text: "Please add at least 1 gift before submit this donation form.", type: 'error'});
			return false;
		}

		<?php if(REGION == 'uk'){ ?>
			if($('#gift-uk-extra-aid').prop('checked')){
				if(!bootstrapValidateEmpty("payment-uk-extra-aid-date", "")){
					noty({text: "Please fill in your aid date.", type: 'error'});
					return false;
				}
			}
		<?php }else if(REGION == 'us'){ ?>
			if($('#payment-us-paymode').val() == "check"){
				if(!bootstrapValidateEmpty("gift-echeck-input-acc-no", "")){
					noty({text: "Please fill in account number.", type: 'error'});
					return false;
				}
				if(!bootstrapValidateEmpty("gift-echeck-input-route-no", "")){
					noty({text: "Please fill in route number.", type: 'error'});
					return false;
				}
				if(!bootstrapValidateEmpty("gift-echeck-input-bank-name", "")){
					noty({text: "Please fill in bank name.", type: 'error'});
					return false;
				}
				if(!bootstrapValidateEmpty("gift-echeck-input-name", "")){
					noty({text: "Please fill in name on account.", type: 'error'});
					return false;
				}
			}else if($('#payment-us-paymode').val() == "cc" && !validateCC()){
				return false;
			}
		<?php }else{ ?>
			if(!validateCC()){
				return false;
			}
		<?php } ?>

		if(!bootstrapValidateEmpty("gift-billing-input-name", "")){
			noty({text: "Please fill in full name.", type: 'error'});
			return false;
		}
		if(!bootstrapValidateEmpty("gift-billing-input-address1", "")){
			noty({text: "Please fill in address 1.", type: 'error'});
			return false;
		}
		if(!bootstrapValidateEmpty("gift-billing-input-address2", "")){
			noty({text: "Please fill in address 2.", type: 'error'});
			return false;
		}
		if(!bootstrapValidateEmpty("gift-billing-input-city", "")){
			noty({text: "Please fill in city.", type: 'error'});
			return false;
		}
		if(!bootstrapValidateEmpty("gift-billing-input-state", "")){
			noty({text: "Please fill in state.", type: 'error'});
			return false;
		}
		if(!bootstrapValidateEmpty("gift-billing-input-zipcode", "")){
			noty({text: "Please fill in zipcode.", type: 'error'});
			return false;
		}
		if(!bootstrapValidateEmpty("gift-billing-input-email", "")){
			noty({text: "Please fill in email.", type: 'error'});
			return false;
		}
		if(!bootstrapValidateEmpty("gift-billing-input-phone", "")){
			noty({text: "Please fill in phone.", type: 'error'});
			return false;
		}

		if($('#gift-add-mailing').prop('checked')){
			if(!bootstrapValidateEmpty("gift-mailing-input-name", "")){
				noty({text: "Please fill in full name.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-mailing-input-address1", "")){
				noty({text: "Please fill in address 1.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-mailing-input-address2", "")){
				noty({text: "Please fill in address 2.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-mailing-input-city", "")){
				noty({text: "Please fill in city.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-mailing-input-state", "")){
				noty({text: "Please fill in state.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-mailing-input-zipcode", "")){
				noty({text: "Please fill in zipcode.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-mailing-input-email", "")){
				noty({text: "Please fill in email.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-mailing-input-phone", "")){
				noty({text: "Please fill in phone.", type: 'error'});
				return false;
			}
		}

		if($('#gift-create-account').prop('checked')){
			if(!bootstrapValidateEmpty("gift-save-password", "")){
				noty({text: "Please fill in password.", type: 'error'});
				return false;
			}
			if(!bootstrapValidateEmpty("gift-save-confirm-password", "")){
				noty({text: "Please fill in Confirm password.", type: 'error'});
				return false;
			}
			if($('#gift-save-confirm-password').val() != $('#gift-save-password').val()){
				noty({text: "Password and confirm password does not match.", type: 'error'});
				return false;
			}
		}

		for(var i=0; i<giftLists.length; i++){
			$('<input>').attr({name: 'catalog-value-type[]', value: giftLists[i].type}).appendTo('#submit-variable');
			$('<input>').attr({name: 'catalog-value-mode[]', value: giftLists[i].mode}).appendTo('#submit-variable');
			$('<input>').attr({name: 'catalog-value-code[]', value: giftLists[i].code}).appendTo('#submit-variable');
			$('<input>').attr({name: 'catalog-value-description[]', value: giftLists[i].description}).appendTo('#submit-variable');
			$('<input>').attr({name: 'catalog-value-comment[]', value: giftLists[i].comment}).appendTo('#submit-variable');
			$('<input>').attr({name: 'catalog-value-anonymous[]', value: giftLists[i].anonymous}).appendTo('#submit-variable');
			$('<input>').attr({name: 'catalog-value-amount[]', value: giftLists[i].amount}).appendTo('#submit-variable');
			$('<input>').attr({name: 'catalog-value-recurring[]', value: giftLists[i].recurring}).appendTo('#submit-variable');
		}

		$('<input>').attr({name: 'submit-currency-code', value: $('.currency-code').html().substr(0, 3)}).appendTo('#submit-variable');
		$('<input>').attr({name: 'submit-currency-symbol', value: $('.currency-symbol').html()}).appendTo('#submit-variable');
	}

	function revealPayment(){
		$('.gift-payment, .gift-newsletter').show();
	}

	function rebind(){
		$('.gift-catalog-currency-value').off();
		$('.gift-catalog-currency-value').on('keyup', function (e){
			if(e.keyCode === 13){
				$(e.target).next('div').children('.btn-ifes').click();
			}
		});

		$('.datetimepicker').datetimepicker({
			format: 'Do',
			allowInputToggle: true
		});

		$('.gift-list-currency-value').off();
		$('.gift-list-currency-value').on('keyup', function (e){
			if(e.keyCode === 13){
				$(e.target).parent().parent().parent().find('.gift-list-input-save').click();
			}
		});

		$('.gift-list-input-comment').off();
		$('.gift-list-input-comment').on('keyup', function (e){
			if(e.keyCode === 13){
				$(e.target).parent().parent().parent().find('.gift-list-input-save').click();
			}
		});
	}

	function toggleCardPayment(mode){
		if(mode == "edit"){
			$('.gift-payment').ploading({action: 'show'});
			$.ajax({
				url: HTTP_AJAX,
				type: 'POST',
				dataType: 'json',
				data:{
					opt: 'get_cc_details',
					id: $('#gift-cc-select').val()
				}
			}).done(function(msg){
				if(msg.success){
					if(msg.valid){
						$('#gift-cc-input-number').val(msg.number);
						$('#gift-cc-input-name').val(msg.name);
						$('#gift-cc-input-expiration').val(msg.expiration);
						$('#gift-cc-input-cvv').val('');
						$('#gift-cc-input-mode').val(mode);
						$('#gift-cc-form-select').toggle();
						$('#gift-cc-form-new').toggle();
					}else{
						noty({text: "Requested card information does not available.", type: 'error'});
					}
					$('.gift-payment').ploading({action: 'hide'});
				}else{
					noty({text: "Could not get data from server. Please try again.", type: 'error'});
					$('.gift-payment').ploading({action: 'hide'});
				}
			}).fail(function(jqXHR, textStatus){
				noty({text: "Could not connect with server. Please refresh your browser and try again.", type: 'error'});
				$('body').ploading({action: 'hide'});
			});
		}else{
			if($('#gift-cc-form-new').is(':visible')){
				$('#gift-cc-input-number').val('');
				$('#gift-cc-input-name').val('');
				$('#gift-cc-input-expiration').val('');
				$('#gift-cc-input-cvv').val('');
			}
			$('#gift-cc-input-mode').val(mode);
			$('#gift-cc-form-select').toggle();
			$('#gift-cc-form-new').toggle();
		}
	}

	function toggleUSPayment(mode){
		if(mode == 'cc'){
			if($('#gift-echeck-form').is(':visible')){
				$('#gift-cc-form-process-fee').show();
				$('#gift-cc-form-new').show();
				$('#gift-cc-form-select').hide();
				$('#gift-echeck-form').hide();
			}
		}else{
			$('#gift-cc-form-process-fee').hide();
			$('#gift-cc-form-new').hide();
			$('#gift-cc-form-select').hide();
			$('#gift-echeck-form').show();
		}
		$('#payment-us-paymode').val(mode);
		calcGiftList();
	}

	function toggleUKExtraAid(){
		$('#gift-uk-extra-aid-form').toggle();
		calcGiftList();
	}

	function toggleCreateAccount(){
		if($('#gift-create-account').prop('checked')){
			$('#gift-create-account-form-2').show();
			$('#gift-payment-save-details').show();
		}else{
			$('#gift-create-account-form-2').hide();
			$('#gift-payment-save-details').hide();
		}
	}
</script>