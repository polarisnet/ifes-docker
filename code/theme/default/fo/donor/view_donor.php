<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/edit-shortcut.js"></script>
<form id="form_1" class="std-form" enctype="multipart/form-data" method="post">
	<input type="hidden" id="submit_mode" name="submit_mode">
	<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
	<table style="position: relative;">
		<tr>
			<td colspan="2" style="text-align: center;">
				<?php echo getSystemFormAlert('customers', $customerData['id'], $winReady); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="formbuttons" style="text-align: center;">
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit" value="Edit" class="flat-button-default" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" id="btn-delete" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteCustomer('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<?php if($returnCust == '' && $returnType !='dashboard'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('customer.list')."?page=".checkParam('page'); ?>';">
				<?php } else if($returnCust != '' && $returnType=='customer'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('customer.view')."?key=".urlencode($returnCust); ?>';">
				<?php } else if($returnCust != '' && $returnType=='project'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('project_management.projects.view')."?key=".urlencode($returnCust); ?>';">
                <?php } else if($returnCust != '' && $returnType=='contract'){ ?>
                    <input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('project_management.contracts_customer.view')."?key=".urlencode($returnCust); ?>';">	
				<?php } else if($returnCust == '' && $returnType=='dashboard'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.dashboard'); ?>';">		
				<?php } else {?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('customer.list'); ?>';">
				<?php } ?>	
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="view-table" style="<?php if($mode == 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Customer Information</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Customer No</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['cust_no']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Name</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['name']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Business Reg. No</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['roc_no']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">GST No.</td>
									<td class="lbl-gap">:</td>
									<td><?php echo nl2br($customerData['gst_no']); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Sales Person Code</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['agent_code']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Sales Person Name</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $objSalesPerson->getSalesPersonNameById($customerData['agent_id']); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Email Address</td>
									<td class="lbl-gap">:</td>
									<td><ul class="insite-link"><li><a href="mailto:<?php echo $customerData['email']; ?>"><?php echo $customerData['email']; ?></a></li></ul></td>
								</tr> 
								<tr>
									<td class="lbl-field">Website</td>
									<td class="lbl-gap">:</td>
									<td><ul class="insite-link"><li><a href="http://<?php echo $customerData['website']; ?>" target="_blank" ><?php echo $customerData['website']; ?></a></li></ul></td>
								</tr>
                                <?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '14', 'channel', $encryptKey, 'view', $customerData['channel']); ?>
                                                                <?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '15', 'team', $encryptKey, 'view', $customerData['team']); ?>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '1', 'left', $encryptKey, 'view'); ?>                                
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>								
								<tr>
									<td class="lbl-field">Phone 1</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['phone1']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Phone 2</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['phone2']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Fax</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['fax']; ?></td>
								</tr>
                                
                                <?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '1', 'area', $encryptKey, 'view', $customerData['area']); ?>
								<?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '2', 'business', $encryptKey, 'view', $customerData['business']); ?>
								<?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '3', 'sales_potential', $encryptKey, 'view', $customerData['sales_potential']); ?>
								<tr>
									<td class="lbl-field">Status</td>
									<td class="lbl-gap">:</td>
									<td><?php if($customerData['status'] === '1'){echo "Active";}else{echo "Inactive";} ; ?></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '1', 'right', $encryptKey, 'view'); ?>
							</table>
                            </div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Billing Details</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Attention</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['attention']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Address</td>
									<td class="lbl-gap">:</td>
									<td><?php echo nl2br($customerData['invoice_address']); ?></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '2', 'left', $encryptKey, 'view'); ?>
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Postcode</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['invoice_postcode']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">City</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['invoice_city']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">State</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['invoice_state']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Country</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['invoice_country']; ?></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '2', 'right', $encryptKey, 'view'); ?>
							</table>
                            </div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Delivery Details</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Attention</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['attention1']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Address</td>
									<td class="lbl-gap">:</td>
									<td><?php echo nl2br($customerData['delivery_address']); ?></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '3', 'left', $encryptKey, 'view'); ?>
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Postcode</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['delivery_postcode']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">City</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['delivery_city']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">State</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['delivery_state']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Country</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['delivery_country']; ?></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '3', 'right', $encryptKey, 'view'); ?>
							</table>
                            </div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Credit Details</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<!-- <tr>
									<td class="lbl-field">Current Balance</td>
									<td class="lbl-gap">:</td>
									<td><?php echo numberWithCommas($customerData['credit_balance'], $GLOBALS['siteSetting']['def_pricedecimal']); ?></td>
								</tr> -->
								<tr>
									<td class="lbl-field">Credit Limit</td>
									<td class="lbl-gap">:</td>
									<td><?php echo numberWithCommas($customerData['credit_limit'], $GLOBALS['siteSetting']['def_pricedecimal']); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Credit Terms</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['credit_terms']; ?></td>
								</tr>
								<!-- <tr>
									<td class="lbl-field">Credit Date</td>
									<td class="lbl-gap">:</td>
									<td><?php if($customerData['credit_date'] != '0000-00-00 00:00:00' && $customerData['credit_date'] != ''){echo convertDate($customerData['credit_date'], 'Y-m-d H:i:s', 'd/m/Y');} ?></td>
								</tr> -->
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '4', 'left', $encryptKey, 'view'); ?>
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Currency Code</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['currency_code']; ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Currency Symbol</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $objCurrency->getCurrencySymbolById($customerData['currency_id']); ?></td>
								</tr>
								<tr>
									<td class="lbl-field">Tax Code</td>
									<td class="lbl-gap">:</td>
									<td><?php echo $customerData['tax_code']; ?></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '4', 'right', $encryptKey, 'view'); ?>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Additional Details</td>
					</tr>
					<tr>
                                            <td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

							<table>
								<tr>
									<td class="lbl-field">Pricing Group</td>
									<td class="lbl-gap">:</td>
									<td><?php if($customerData['price_group'] != 'A' && $customerData['price_group'] != 'B'){echo '';}else{echo $customerData['price_group'];}?></td>
								</tr>
								<tr>
                                    <td class="lbl-field">Password</td>
                                    <td class="lbl-gap">:</td>
                                    <td>
										<?php echo "******";//$formPassword; ?>
                                        <div id="email_customer_id"><a id="link_send_customer" href="javascript:void(0);">(Send Email to Customer)</a></div>
                                    </td>
                                </tr>
								<tr>
									<td class="lbl-field">Remarks</td>
									<td class="lbl-gap">:</td>
									<td><?php echo nl2br($customerData['remarks']); ?></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '5', 'left', $encryptKey, 'view'); ?>
							</table>
                            </div>
						</td>
					</tr>
				</table>
				<?php if($allowEdit){ ?>
				<table id="edit-table" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
					<tr>
						<td class="lbl-title" colspan="2">Customer Information<span class="compulsory-text"><span class="lbl-compulsory">*</span> Required Information</span></td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Customer No</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
										<input type="text" class="flat-input" id="custno" name="custno" onblur="javascript: checkFieldExist($('#custno'), '<?php echo $encryptKey; ?>', 'custno', $('#loader_custno'), 'check_duplicate_custno', true);" value="<?php echo $formCustNo;?>">
										<img id="loader_custno" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
									</td>
								</tr>
								<tr>
									<td class="lbl-field">Name</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
                                    	<input type="text" class="flat-input" id="name" name="name" onblur="javascript: checkFieldExist($('#name'), '<?php echo $encryptKey; ?>', 'name', $('#loader_name'), 'check_custname_similarity', true, 'warning');" value="<?php echo $formName; ?>">
                                    	<img id="loader_name" width="14px" height="14px" src="<?php echo HTTP_MEDIA; ?>/site-image/loader_small.gif" style="display: none; position: relative; top: 2px;">
	                                </td>
								</tr>
								<tr>
									<td class="lbl-field">Business Reg. No</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="roc" name="roc" value="<?php echo $formRocNo; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">GST No.</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="gst_no" name="gst_no" value="<?php echo nl2eol($formGSTno); ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Sales Person Code</td>
									<td class="lbl-gap"></td>
									<td><div id="input_agent"></div></td>
								</tr>
								<tr>
									<td class="lbl-field">Sales Person Name</td>
									<td class="lbl-gap"></td>
									<td><div id="disp_agent_name"><?php echo $objSalesPerson->getSalesPersonNameById($customerData['agent_id']); ?></div></td>
								</tr>
								<tr>
									<td class="lbl-field">Email Address</td>
									<td class="lbl-gap">&nbsp;</td>
									<td><input type="text" class="flat-input" id="email" name="email" value="<?php echo $formEmail; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Website</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="website" name="website" value="<?php echo $formWebsite; ?>"></td>
								</tr>
                                <?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '14', 'channel', $encryptKey, 'edit', $formChannel); ?>
                                <?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '15', 'team', $encryptKey, 'edit', $formTeam); ?>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '1', 'left', $encryptKey, 'edit'); ?>
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>								
								<tr>
									<td class="lbl-field">Phone 1</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="phone1" name="phone1" value="<?php echo $formPhone1; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Phone 2</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="phone2" name="phone2" value="<?php echo $formPhone2; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Fax</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="fax" name="fax" value="<?php echo $formFax; ?>"></td>
								</tr>								
								
                                <?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '1', 'area', $encryptKey, 'edit', $formArea); ?>  
								<?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '2', 'business', $encryptKey, 'edit', $formBusiness); ?>
								<?php echo $objSystemField->getSystemField(MODULE_PARENT_UID, '3', 'sales_potential', $encryptKey, 'edit', $formSalesPotential); ?>
								<tr>
									<td class="lbl-field">Status</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td>
										<select id="status" name="status" class="flat-selectbox">
											<option value="1" <?php if($formStatus == "1"){echo ("selected");}?> >Active</option>
											<option value="0" <?php if($formStatus == "0"){echo ("selected");}?> >Inactive</option>                                
										</select>
									</td>
								</tr>               			
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '1', 'right', $encryptKey, 'edit'); ?>
							</table>
                            </div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Billing Details</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Attention</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="attention" name="attention" value="<?php echo $formAttention; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Address</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><textarea id="invoice_address" name="invoice_address" class="flat-textarea" style="height: 70px;"><?php echo nl2eol($formInvoiceAddress); ?></textarea></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '2', 'left', $encryptKey, 'edit'); ?>
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Postcode</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="invoice_postcode" name="invoice_postcode" value="<?php echo $formInvoicePostcode; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">City</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="invoice_city" name="invoice_city" value="<?php echo $formInvoiceCity; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">State</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="invoice_state" name="invoice_state" value="<?php echo $formInvoiceState; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Country</td>
									<td class="lbl-gap"></td>
									<td><div id="input_invoice_country"></div></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '2', 'right', $encryptKey, 'edit'); ?>
							</table>
                            </div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Delivery Details</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Attention</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="attention1" name="attention1" value="<?php echo $formAttention1; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Address</td>
									<td class="lbl-gap"></td>
									<td><textarea id="delivery_address" name="delivery_address" class="flat-textarea" style="height: 70px;"><?php echo nl2eol($formDeliveryAddress); ?></textarea></td>
								</tr>
								<?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '3', 'left', $encryptKey, 'edit'); ?>								
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Postcode</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="delivery_postcode" name="delivery_postcode" value="<?php echo $formDeliveryPostcode; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">City</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="delivery_city" name="delivery_city" value="<?php echo $formDeliveryCity; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">State</td>
									<td class="lbl-gap"></td>
									<td><input type="text" class="flat-input" id="delivery_state" name="delivery_state" value="<?php echo $formDeliveryState; ?>"></td>
								</tr>
								<tr>
									<td class="lbl-field">Country</td>
									<td class="lbl-gap"></td>
									<td><div id="input_delivery_country"></div></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '3', 'right', $encryptKey, 'edit'); ?>
							</table>
                            </div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Credit Details</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<!-- <tr>
									<td class="lbl-field">Current Balance</td>
									<td class="lbl-gap"><div class="lbl-compulsory">*&nbsp;</div></td>
									<td><div id="input_balance"></div></td>
								</tr> -->
								<tr>
									<td class="lbl-field">Credit Limit</td>
									<td class="lbl-gap"></td>
									<td><div id="input_limit"></div></td>
								</tr>
								<tr>
									<td class="lbl-field">Credit Terms</td>
									<td class="lbl-gap"></td>
									<td><div id="input_terms"></div></td>
								</tr>
								<!-- <tr>
									<td class="lbl-field">Credit Date</td>
									<td class="lbl-gap"></td>
									<td><div id="input_credit_date"></div></td>
								</tr> -->
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '4', 'left', $encryptKey, 'edit'); ?>
							</table>
						</div>
			<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Currency Code</td>
									<td class="lbl-gap">&nbsp;</td>
									<td><div id="input_currency"></div></td>
								</tr>
								<tr>
									<td class="lbl-field">Currency Symbol</td>
									<td class="lbl-gap">&nbsp;</td>
									<td><div id="disp_currency_symbol"><?php echo $objCurrency->getCurrencySymbolById($customerData['currency_id']); ?></div></td>
								</tr>
								<tr>
									<td class="lbl-field">Tax Code</td>
									<td class="lbl-gap">&nbsp;</td>
									<td><div id="input_tax"></div></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '4', 'right', $encryptKey, 'edit'); ?>
							</table>
                            </div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="lbl-title" colspan="2">Additional Details</td>
					</tr>
					<tr>
						<td class="aa-bs form-spacer1" colspan="2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<table>
								<tr>
									<td class="lbl-field">Pricing Group</td>
									<td class="lbl-gap">&nbsp;</td>
									<td>
										<select id="pricing_group" name="pricing_group" class="flat-selectbox">
											<option value="A" <?php if($formPricingGroup == 'A'){echo 'selected';}?>>A</option>
											<option value="B" <?php if($formPricingGroup == 'B'){echo 'selected';}?>>B</option>
										</select>
									</td>
								</tr>
								<tr>
                                    <td class="lbl-field">Password</td>
                                    <td class="lbl-gap">&nbsp;</td>
                                    <td>
                                        <input type="password" class="flat-input" id="password" name="password" value="<?php echo $formPassword; ?>">
                                    </td>
                                </tr>  
								<tr>
									<td class="lbl-field">Remarks</td>
									<td class="lbl-gap">&nbsp;</td>
									<td><textarea id="remarks" name="remarks" class="flat-textarea"><?php echo nl2eol($formRemarks); ?></textarea></td>
								</tr>
                                <?php echo $objCustomField->getCustomField(MODULE_PARENT_UID, '5', 'left', $encryptKey, 'edit'); ?>
							</table>
                                </div>
						</td>
					</tr>
				</table>
				<?php } ?>
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container-ship-to', 'collapse-ship-to');">Additional Delivery Details (<span id="grid-count-info-ship-to">0</span>)</span><span id="collapse-ship-to" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container-ship-to', 'collapse-ship-to');">[+]</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<br><div id="ext-ship-to-grid"></div>
				<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/customer/ship_to.js"></script>
				<script type="text/javascript">
                    function hideContainer(obj) {
                        obj.hide();
                    }
					Ext.Loader.setPath('Ext.ux', '<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux');
					Ext.require([
						'Ext.ux.ProgressBarPager',
						'Ext.ux.grid.FiltersFeature'
					]);
					
					Ext.namespace('ShipTo');
					ShipTo.app = function(){
						return{
							init: function(){
								var mask = new Ext.LoadMask(document.getElementById('ext-ship-to-grid'),{ msg: 'Loading...'});
								var shipToPanel = new ShipToPanel({
									'start': '<?php echo $shipToStart; ?>',
									'itemsPerPage': '<?php echo $shipToPerPage; ?>',
									'listFields': [<?php echo implode(",", $shipToFields); ?>],
									'allowEdit': '<?php echo $allowEdit; ?>',
									'parent': '<?php echo $encryptKey; ?>'
							
								});
								Ext.create('Ext.container.Container',{
									id: 'ext-container-ship-to',
//									hidden: true,
									renderTo: 'ext-ship-to-grid',
									items:[shipToPanel],
                                    listeners: {
                                        afterlayout: function( obj, layout, eOpts )  {
                                                obj.hide();
                                                obj.clearListeners();
                                        }  
                                    }
								});
							}
						}
					}();
					Ext.onReady(ShipTo.app.init, ShipTo.app);
				</script>				
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container-relatedcustomers', 'collapse-customer');">Related Customers (<span id="grid-count-info-customer">0</span>)</span><span id="collapse-customer" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container-relatedcustomers', 'collapse-customer');">[+]</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<br><div id="ext-customers-grid"></div>
				<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/GridFilters.css" />
				<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/RangeMenu.css" />                
				<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/customer/list_related_customers.js"></script>
				<script type="text/javascript">
					Ext.namespace('RelatedCustomers');
					RelatedCustomers.app = function(){
						return{
							init: function(){
								var mask = new Ext.LoadMask(document.getElementById('ext-customers-grid'),{ msg: 'Loading...'});
								var listPanelOfRelatedCustomers = new ListPanelOfRelatedCustomers({
									'start': '<?php echo $relatedCustomersStart; ?>',
									'itemsPerPage': '<?php echo $relatedCustomersPerPage; ?>',
									'listFields': [<?php echo implode(",", $relatedCustomerFields); ?>],
									'allowEdit': '<?php echo $allowEdit; ?>',
									'parent': '<?php echo $encryptKey; ?>',
									'parentType': 'customer',
									'viewLink': '<?php echo getModuleURL('customer.view'); ?>'
								});
								Ext.create('Ext.container.Container',{
									id: 'ext-container-relatedcustomers',
									renderTo: 'ext-customers-grid',
//									hidden: true,
									items:[listPanelOfRelatedCustomers],
                                    listeners: {
                                        afterlayout: function( obj, layout, eOpts )  {
                                                obj.hide();
                                                obj.clearListeners();
                                        }  
                                    }
								});
							}
						}
					}();
					Ext.onReady(RelatedCustomers.app.init, RelatedCustomers.app);
				</script>				
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container-sales-person', 'collapse-salesperson');">Related Sales Persons (<span id="grid-count-info-salesperson">0</span>)</span><span id="collapse-salesperson" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container-sales-person', 'collapse-salesperson');">[+]</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<br><div id="ext-sales-person-grid"></div>
				<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/customer/related_salesperson.js"></script>
				<script type="text/javascript">
					Ext.namespace('SO');
					SO.app = function(){
						return{
							init: function(){
								var mask = new Ext.LoadMask(document.getElementById('ext-sales-person-grid'),{ msg: 'Loading...'});
								var salesPersonListPanel = new SalesPersonPanel({
									'start': '<?php echo $relatedSalesPersonStart; ?>',
									'itemsPerPage': '<?php echo $relatedSalesPersonPerPage; ?>',
									'listFields': [<?php echo implode(",", $relatedSalesPersonFields); ?>],
									'viewLink': '<?php echo getModuleURL('salesperson.view'); ?>',
									'allowEdit': '<?php echo $allowEdit; ?>',
									'parent': '<?php echo $encryptKey; ?>'
								});
								Ext.create('Ext.container.Container',{
									id: 'ext-container-sales-person',
									renderTo: 'ext-sales-person-grid',
//									hidden: true,
									items:[salesPersonListPanel],
                                    listeners: {
                                        afterlayout: function( obj, layout, eOpts )  {
                                            obj.hide();
                                            obj.clearListeners();
                                        }  
                                    }
								});
							}
						}
					}();
					Ext.onReady(SO.app.init, SO.app);
				</script>
			</td>
		</tr>		
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container-relatedcontacts', 'collapse-contact');">Related Contacts (<span id="grid-count-info-contact">0</span>)</span><span id="collapse-contact" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container-relatedcontacts', 'collapse-contact');">[+]</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<br><div id="ext-contacts-grid"></div>				       
				<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/contacts/list_related_contacts.js"></script>
				<script type="text/javascript">					
					Ext.namespace('RelatedContacts');
					RelatedContacts.app = function(){
						return{
							init: function(){
								var mask = new Ext.LoadMask(document.getElementById('ext-contacts-grid'),{ msg: 'Loading...'});
								var listPanelOfRelatedContacts = new ListPanelOfRelatedContacts({
									'start': '<?php echo $relatedContactsStart; ?>',
									'itemsPerPage': '<?php echo $relatedContactsPerPage; ?>',
									'listFields': [<?php echo implode(",", $relatedContactFields); ?>],
									'allowEdit': '<?php echo $allowEdit; ?>',
									'allowView': '<?php echo $allowView; ?>',									
									'parent': '<?php echo $encryptKey; ?>',
                                    'parentType': 'Customer',
									'dispparent': '<?php echo mysqli_real_escape_string($GLOBALS['myDB']->getConnection(), $customerData['name']); ?>',
									'contactowner': '<?php echo $formAgent; ?>',
									'dispcontactowner': '<?php echo mysqli_real_escape_string($GLOBALS['myDB']->getConnection(), $objSalesPerson->getSalesPersonNameById($customerData['agent_id'])); ?>',
									'viewLink': '<?php echo getModuleURL('contacts.view'); ?>'
								}); 
								Ext.create('Ext.container.Container',{
									id: 'ext-container-relatedcontacts',
									renderTo: 'ext-contacts-grid',
//									hidden: true,
									items:[listPanelOfRelatedContacts],
                                    listeners: {
                                        afterlayout: function( obj, layout, eOpts )  {
                                                obj.hide();
                                                obj.clearListeners();
                                        }  
                                    }
								});
							}
						}
					}();
					Ext.onReady(RelatedContacts.app.init, RelatedContacts.app);
				</script>				
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<?php if($moduleProjectStatus == "1"){ ?>
		<tr>
			<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container-relatedprojects', 'collapse-project');">Related Projects (<span id="grid-count-info-project">0</span>)</span><span id="collapse-project" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container-relatedprojects', 'collapse-project');">[+]</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<br><div id="ext-projects-grid"></div>				       
				<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/project_management/projects/list_related_projects.js"></script>
				<script type="text/javascript">
                    Ext.Loader.setPath('Ext.ux', '<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux');
					Ext.namespace('RelatedProjects');
					RelatedProjects.app = function(){
						return{
							init: function(){
								var mask = new Ext.LoadMask(document.getElementById('ext-projects-grid'),{ msg: 'Loading...'});
								var listPanelOfRelatedProjects = new ListPanelOfRelatedProjects({
									'start': '<?php echo $relatedProjectsStart; ?>',
									'itemsPerPage': '<?php echo $relatedProjectsPerPage; ?>',
									'listFields': [<?php echo implode(",", $relatedProjectFields); ?>],
									'allowEdit': '<?php echo $allowEdit; ?>',
									'allowView': '<?php echo $allowView; ?>',									
									'parent': '<?php echo $encryptKey; ?>',
									'parentType': 'customer',
									'dispparent': '<?php echo mysqli_real_escape_string($GLOBALS['myDB']->getConnection(), $customerData['name']); ?>',
									'pic': '<?php echo $formAgent; ?>',
									'disppic': '<?php echo mysqli_real_escape_string($GLOBALS['myDB']->getConnection(), $objSalesPerson->getSalesPersonNameById($customerData['agent_id'])); ?>',
									'viewLink': '<?php echo getModuleURL('project_management.projects.view'); ?>'
								});
								Ext.create('Ext.container.Container',{
									id: 'ext-container-relatedprojects',
									renderTo: 'ext-projects-grid',
//									hidden: true,
									items:[listPanelOfRelatedProjects],
                                    listeners: {
                                        afterlayout: function( obj, layout, eOpts )  {
                                                obj.hide();
                                                obj.clearListeners();
                                        }  
                                    }
								});
							}
						}
					}();
					Ext.onReady(RelatedProjects.app.init, RelatedProjects.app);
				</script>				
			</td>
		</tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <?php } ?>
        <!-- <tr>
			<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container-relateditems', 'collapse-item');">Products Owned (<span id="grid-count-info-item">0</span>)</span><span id="collapse-item" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container-relateditems', 'collapse-item');">[+]</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<br><div id="ext-items-grid"></div>
				<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/project_management/projects/list_related_items.js"></script>
				<script type="text/javascript">
					Ext.namespace('RelatedItems');
					RelatedItems.app = function(){
						return{
							init: function(){
								var mask = new Ext.LoadMask(document.getElementById('ext-items-grid'),{ msg: 'Loading...'});
								var listPanelOfRelatedItems = new ListPanelOfRelatedItems({
									'start': '<?php echo $relatedItemsStart; ?>',
									'itemsPerPage': '<?php echo $relatedItemsPerPage; ?>',
									'listFields': [<?php echo implode(",", $relatedItemFields); ?>],
									'allowEdit': '<?php echo $allowEdit; ?>',
									'parent': '<?php echo $encryptKey; ?>',
									'parentType': 'Customer',
									'totalAmount': '<?php //echo $formProjectTotalAmount; ?>'
								});
								Ext.create('Ext.container.Container',{
									id: 'ext-container-relateditems',
									renderTo: 'ext-items-grid',
									hidden: true,
									items:[listPanelOfRelatedItems],
									listeners:{
										'afterrender': function(obj, opts){
										   Ext.getCmp('panel_btn_relatedItem_new').setText('New Product Owned');
										   Ext.getCmp('panel_btn_relatedItem_edit').setText('Edit Product Owned');
										   Ext.getCmp('panel_btn_relatedItem_delete').setText('Delete Product Owned');
										},
                                        'afterlayout': function( obj, layout, eOpts )  {
                                                obj.hide();
                                                obj.clearListeners();
                                        }
									}
								});
							}
						}
					}();
					Ext.onReady(RelatedItems.app.init, RelatedItems.app);
				</script>				
			</td>
		</tr>
        <tr><td colspan="2">&nbsp;</td></tr> -->
		<tr>
			<td class="lbl-title" colspan="2"><span class="inner-grid-text" onclick="javsacript: innerGridToggle(this, 'ext-container', 'collapse-activity');">Activities (<span id="grid-count-info-activity">0</span>)</span><span id="collapse-activity" class="inner-grid-button" onclick="javsacript: innerGridToggle(this, 'ext-container', 'collapse-activity');">[+]</span></td>
		</tr>
		<tr>
			<td colspan="2">
				<br><div id="ext-details-grid"></div>
				<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/resources/css/BoxSelect.css">
				<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/src/boxselect/BoxSelect.js"></script>
				<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/oz.system/settings/activitity_management/activities/list_activities_bo.js"></script>
				<script type="text/javascript">					
					Ext.namespace('Activities');
					Activities.app = function(){
						return{
							init: function(){
								var mask = new Ext.LoadMask(document.getElementById('ext-details-grid'),{ msg: 'Loading...'});
								var listPanelOfActivities = new ListPanelOfActivities({
									'start': '<?php echo $detailsStart; ?>',
									'itemsPerPage': '<?php echo $detailsItemsPerPage; ?>',
									'listFields': [<?php echo implode(",", $activitiesFields); ?>],
									'allowEdit': '<?php echo $allowEdit; ?>',
									'sale': '<?php echo (isset($_SESSION['salesperson_id'])?$_SESSION['salesperson_id']:""); ?>',
									'dispsale': '<?php echo (isset($_SESSION['salesperson'])?$_SESSION['salesperson']:""); ?>',
									'parent': '<?php echo $encryptKey; ?>',
									'parentType': 'Customer',
									'datacustomer': '<?php echo $encryptKey; ?>',
									'viewProject': '<?php echo getModuleURL('project_management.projects.view'); ?>',
									'viewContact': '<?php echo getModuleURL('contacts.view'); ?>',
                                                                        'viewVendor': '<?php echo getModuleURL('vendor.view'); ?>'
							
								});
								Ext.create('Ext.container.Container',{
									id: 'ext-container',
									hidden: true,
									renderTo: 'ext-details-grid',
									items:[listPanelOfActivities]
								});
							}
						}
					}();
					Ext.onReady(Activities.app.init, Activities.app);
				</script>				
			</td>
		</tr>	
		<tr>
			<td colspan="2" style="text-align: center;">
				<br>
				<?php if($allowEdit){ ?>
					<input type="button" id="btn-edit1" value="Edit" class="flat-button-default" style="<?php if($mode == 'edit'){echo 'display: none;';}?>" rel="tooltip" data-original-title="<?php echo SHORTCUT_EDIT; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					<span id="cancel-toolbar1" style="<?php if($mode != 'edit'){echo 'display: none;';}?>">
						<input type="button" value="Save" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_SAVE; ?>" data-placement="top" onclick="javascript: submitForm('');">
						<input type="button" value="Cancel" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_CANCEL; ?>" data-placement="top" onclick="javascript: toggleEditForm();">
					</span>
				<?php } ?>
				<?php if($allowDelete){ ?><input type="button" value="Delete" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_DELETE; ?>" data-placement="top" onclick="javascript: deleteCustomer('<?php echo urlencode($encryptKey); ?>');"><?php } ?>
				<?php if($returnCust == '' && $returnType !='dashboard'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('customer.list')."?page=".checkParam('page'); ?>';">
				<?php } else if($returnCust != '' && $returnType=='customer'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('customer.view')."?key=".urlencode($returnCust); ?>';">
				<?php } else if($returnCust != '' && $returnType=='project'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('project_management.projects.view')."?key=".urlencode($returnCust); ?>';">	
                <?php } else if($returnCust != '' && $returnType=='contract'){ ?>
                    <input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('project_management.contracts_customer.view')."?key=".urlencode($returnCust); ?>';">
				<?php } else if($returnCust == '' && $returnType=='dashboard'){ ?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('oz.dashboard'); ?>';">		
				<?php } else {?>
					<input type="button" value="Back" class="flat-button-default" rel="tooltip" data-original-title="<?php echo SHORTCUT_BACK; ?>" data-placement="top" onclick="javascript: window.location='<?php echo getModuleURL('customer.list'); ?>';">
				<?php } ?>	
			</td>
		</tr>
	</table>
</form>
<?php if($allowEdit){ ?>
<script type="text/javascript">
	Ext.onReady(function(){
		var agentCodeStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_agent_code'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			//autoLoad: true,
			fields:['id', 'code', 'name']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_agent',
			id: 'agent',
			name: 'agent',
			hiddenName: 'ext-agent',
			store: agentCodeStore,
			root: 'combo',
			queryMode: 'remote',
			valueField: 'id',
			displayField: 'code',
			value: '<?php echo $objSalesPerson->getSalesPersonCodeById(encryption(rawurldecode($formAgent), $_SESSION['salt'], false)); ?>',
			pageSize: 15,
			emptyText: 'Please select code/name',
			listConfig:{
				loadingText: 'Searching...',
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Code: {code}<br>Name: {name}<br><hr>';
				}
			},
			listeners:{
				'select': function(obj, record){
					document.getElementById('disp_agent_name').innerHTML = record[0].get('name');
				},
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-agent")[0].value = '';
						document.getElementById('disp_agent_name').innerHTML = '';
					}else if(agentCodeStore.getCount() == 0){
						document.getElementById('disp_agent_name').innerHTML = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-agent")[0].value = '<?php echo $formAgent; ?>';
				}
			}
		});
		
		var countryCodeStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_country_code'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			//autoLoad: true,
			fields:['id', 'iso', 'name']
		});	
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_invoice_country',
			id: 'invoice_country',
			name: 'invoice_country',
			hiddenName: 'ext-invoice-country',
			store: countryCodeStore,
			root: 'combo',
			queryMode: 'remote',
			valueField: 'id',
			displayField: 'name',
			value: '<?php echo $objCountry->getCountryNameById(encryption(rawurldecode($formInvoiceCountry), $_SESSION['salt'], false)); ?>',
			pageSize: 15,
			emptyText: 'Please select country name',
			listConfig:{
				loadingText: 'Searching...',
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Name: {name}<br>ISO: {iso}<br><hr>';
				}
			},
			listeners:{
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-invoice-country")[0].value = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-invoice-country")[0].value = '<?php echo $formInvoiceCountry; ?>';
				}
			}
		});
		
		var countryCodeStore1 = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_country_code'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			//autoLoad: true,
			fields:['id', 'iso', 'name']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_delivery_country',
			id: 'delivery_country',
			name: 'delivery_country',
			hiddenName: 'ext-delivery-country',
			store: countryCodeStore1,
			root: 'combo',
			queryMode: 'remote',
			valueField: 'id',
			displayField: 'name',
			value: '<?php echo $objCountry->getCountryNameById(encryption(rawurldecode($formDeliveryCountry), $_SESSION['salt'], false)); ?>',
			pageSize: 15,
			emptyText: 'Please select country name',
			listConfig:{
				loadingText: 'Searching...',
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Name: {name}<br>ISO: {iso}<br><hr>';
				}
			},
			listeners:{
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-delivery-country")[0].value = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-delivery-country")[0].value = '<?php echo $formDeliveryCountry; ?>';
				}
			}
		});
	
		var currencyCodeStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_currency_code'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			//autoLoad: true,
			fields:['id', 'code', 'symbol']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_currency',
			id: 'currency',
			name: 'currency',
			hiddenName: 'ext-currency',
			store: currencyCodeStore,
			root: 'combo',
			queryMode: 'remote',
			valueField: 'id',
			displayField: 'code',
			allowBlank: false,
			value: '<?php echo $objCurrency->getCurrencyCodeById(encryption(rawurldecode($formCurrency), $_SESSION['salt'], false)); ?>',
			pageSize: 15,
			emptyText: 'Please select currency code/name',
			listConfig:{
				loadingText: 'Searching...',
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Code: {code}<br>Symbol: {symbol}<br><hr>';
				}
			},
			listeners:{
				'select': function(obj, record){
					document.getElementById('disp_currency_symbol').innerHTML = record[0].get('symbol');
				},
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-currency")[0].value = '';
						document.getElementById('disp_currency_symbol').innerHTML = '';
					}else if(agentCodeStore.getCount() == 0){
						document.getElementById('disp_currency_symbol').innerHTML = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-currency")[0].value = '<?php echo $formCurrency; ?>';
				}
			}
		});
		
		var taxCodeStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_tax_code'
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			//autoLoad: true,
			fields:['id', 'code', 'name']
		});
		
		Ext.create('Ext.form.field.ComboBox', {
			renderTo: 'input_tax',
			id: 'tax',
			name: 'tax',
			hiddenName: 'ext-tax',
			store: taxCodeStore,
			root: 'combo',
			queryMode: 'remote',
			value: '<?php echo $objMisc->getMiscCodeById(encryption(rawurldecode($formTax), $_SESSION['salt'], false)); ?>',
			valueField: 'id',
			displayField: 'code',
			allowBlank: false,
			pageSize: 15,
			minChars: 1,
			emptyText: 'Please select tax code/name',
			width: 208,
			matchFieldWidth: false,
			listConfig:{
				loadingText: 'Searching...',
				emptyText: '<div class="ext-empty-live-search">No match found...</div>',
				getInnerTpl: function(){
					return 'Code: {code}<br>Name: {name}<br><hr>';
				}
			},
			listeners:{
				'change': function(obj, newValue, oldValue, opts){
					if(newValue == ''){
						document.getElementsByName("ext-tax")[0].value = '';
					}
				},
				'afterrender': function(obj, opts){
					document.getElementsByName("ext-tax")[0].value = '<?php echo $formTax; ?>';
				}
			}
		});
		
		// Ext.create('Ext.form.field.Date', {
		// 	renderTo: 'input_credit_date',
		// 	id: 'date',
		// 	name: 'date',
		// 	width: 208,
		// 	format: 'd/m/Y',
		// 	value: '<?php echo $formCreditDate; ?>'
		// });
		
		// Ext.create('Ext.form.field.Number', {
		// 	renderTo: 'input_balance',
		// 	id: 'balance',
		// 	name: 'balance',
		// 	minValue: 0,
		// 	allowExponential: false,
		// 	width: 208,
		// 	decimalPrecision: <?php echo $GLOBALS['siteSetting']['def_pricedecimal']; ?>,
		// 	hideTrigger: true,
		// 	useThousandSeparator: true,
		// 	value: '<?php echo $formCreditBalance; ?>'
		// });
		
		Ext.create('Ext.form.field.Number', {
			renderTo: 'input_limit',
			id: 'limit',
			name: 'limit',
			minValue: 0,
			allowExponential: false,
			width: 208,
			decimalPrecision: <?php echo $GLOBALS['siteSetting']['def_pricedecimal']; ?>,
			hideTrigger: true,
			useThousandSeparator: true,
			value: '<?php echo $formCreditLimit; ?>'
		});
		
		Ext.create('Ext.form.field.Number', {
			renderTo: 'input_terms',
			id: 'terms',
			name: 'terms',
			minValue: 0,
			allowExponential: false,
			width: 208,
			decimalPrecision: 0,
			hideTrigger: true,
			useThousandSeparator: true,
			value: '<?php echo $formCreditTerms; ?>'
		});
	});
	
	var dynValidator = new Array();
	dynValidator['custno'] = true;
	
	function submitForm(mode){
		$('#submit_mode').val(mode);
		clearValidation('form_1');
		if(!validateEmpty($('#custno'), 'customer no')){return;}
		if($('#custno').val() != '' && !validateCode($('#custno'), 'customer no')){return;}
		if(!validateEmpty($('#name'), 'name')){return;}
		if($('#email').val() != '' && !validateEmail($('#email'), 'email')){return;}
		if($('#phone1').val() != '' && !validatePhone($('#phone1'), 'phone 1')){return;}
		if($('#phone2').val() != '' && !validatePhone($('#phone2'), 'phone 2')){return;}
		if($('#fax').val() != '' && !validatePhone($('#fax'), 'fax')){return;}
		if(!validateEmpty($('#invoice_address'), 'billing address')){return;}
		// if(!validateDecimal($('#balance'), 'current balance')){return;}
		// if(!validateDecimal($('#limit'), 'limit')){return;}
		// if(!validateEmpty($('#terms'), 'terms')){return;}
		// if(!validateExtDate('date', 'credit date')){return;}
		var getCF = JSON.parse('<?php echo json_encode($customfieldName); ?>');
		for (var i=0;i<getCF.length;i++){
			if(getCF[i]["cf_mandatory"] == "1"){
				if(getCF[i]["cf_type"] == "date"){	
					if(!validateExtEmpty(getCF[i]['cf_code'], getCF[i]['cf_label'])){return;}
					if(!validateExtDate(getCF[i]['cf_code'], getCF[i]['cf_label'])){return;}
				} else if(getCF[i]["cf_type"] == "checkbox"){
					var checkboxs = document.getElementsByName(getCF[i]['cf_code']+"[]");
					var cfChecked=false;
					for(var j=0,l=checkboxs.length;j<l;j++){
						if(checkboxs[j].checked){cfChecked=true;}
					}
					if(!cfChecked){
						$('#oz-noty').oznoty([{
							'type': 'error',
							'title': 'Error',
							'content': getCF[i]['cf_label']+' cannot be empty.',
							'position': 'right',
							'autoclose': true
						}]);						
						return;
					}
				} else if(getCF[i]["cf_type"] == "radio"){
					var checkboxs = document.getElementsByName(getCF[i]['cf_code']);
					var cfChecked=false;
					for(var j=0,l=checkboxs.length;j<l;j++){
						if(checkboxs[j].checked){cfChecked=true;}
					}
					if(!cfChecked){
						$('#oz-noty').oznoty([{
							'type': 'error',
							'title': 'Error',
							'content': getCF[i]['cf_label']+' cannot be empty.',
							'position': 'right',
							'autoclose': true
						}]);
						return;
					}	
				} else if(getCF[i]["cf_type"] == "textfield" || getCF[i]["cf_type"] == "textarea" || getCF[i]["cf_type"] == "dropbox" || getCF[i]["cf_type"] == "numeric"){
					if(!validateEmpty($('#'+getCF[i]['cf_code']), getCF[i]['cf_label'])){return;}
					if(getCF[i]["cf_type"] == "numeric"){
						if($('#'+getCF[i]['cf_code']).val() != '' && !validateDecimal($('#'+getCF[i]['cf_code']), getCF[i]['cf_label'])){return;}
					}
				}
			} else {
				if(getCF[i]["cf_type"] == "date"){	
					if(!validateExtDate(getCF[i]['cf_code'], getCF[i]['cf_label'])){return;}
				} else if(getCF[i]["cf_type"] == "numeric"){
					if($('#'+getCF[i]['cf_code']).val() != '' && !validateAllDecimal($('#'+getCF[i]['cf_code']), getCF[i]['cf_label'])){return;}
				}
			}
		}
		if(!dynValidator['custno']){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'System Validation',
				'content': 'System is still validating your information. Please wait for a few moment and try again.',
				'position': 'right',
				'autoclose': true
			}]);
			checkFieldExist($('#custno'), '<?php echo $encryptKey; ?>', 'custno', $('#loader_custno'), 'check_duplicate_custno', true);
		}else{
			$('#form_1').submit();
		}
	}
</script>
<?php } ?>
<?php if($allowDelete){ ?>
<script type="text/javascript">
	$('#link_send_customer').on('click', function() {
		$.ajax({
			url: HTTP_AJAX,
			type: 'post',
			dataType: 'json',
			data:{
				opt: 'email_change_password',
				id: '<?php echo $customerData['id'];?>'
			}
		}).done(function(msg){
			if(msg.success){
				$('#oz-noty').oznoty([{
					'type': 'message',
					'title': 'Message',
					'content': 'Email successfully sent to customer.',
					'position': 'right',
					'autoclose': true
				}]);
			}else{
				$('#oz-noty').oznoty([{
					'type': 'error',
					'title': 'Error',
					'content': msg.message,
					'position': 'right',
					'autoclose': true
				}]);
			}
		}).fail(function(jqXHR, textStatus){
			$('#oz-noty').oznoty([{
				'type': 'error',
				'title': 'Error',
				'content': 'Could not connect with server. Please refresh browser and try again.',
				'position': 'right',
				'autoclose': true
			}]);
		});
	});
	function deleteCustomer(key){
		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected customer?', function(btn){
			if(btn == 'yes'){
				var request = $.ajax({
					url: HTTP_AJAX,
					type: 'POST',
					dataType: 'json',
					data:{
						opt: 'delete_customers',
						id: key
					}
				}).done(function(msg){
					if(msg.success){
						$('#oz-noty').oznoty([{
							'type': 'message',
							'title': 'Message',
							'content': 'Customer successfully deleted.',
							'position': 'right',
							'autoclose': true
						}]);
						window.location = '<?php echo getModuleURL('customer.list'); ?>';
					}else{
						$('#oz-noty').oznoty([{
							'type': 'error',
							'title': 'Error',
							'content': msg.message,
							'position': 'right',
							'autoclose': true
						}]);
					}
				}).fail(function(jqXHR, textStatus){
					$('#oz-noty').oznoty([{
						'type': 'error',
						'title': 'Error',
						'content': 'Could not connect with server. Please refresh browser and try again.',
						'position': 'right',
						'autoclose': true
					}]);
				});
			}
		});
	}
</script>
<?php } ?>