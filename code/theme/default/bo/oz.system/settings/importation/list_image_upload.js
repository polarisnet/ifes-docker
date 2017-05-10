Ext.namespace('Item', 'ImgListPanel');
Ext.define('ImgListPanel', {
	extend: 'Ext.grid.Panel',
	constructor: function(config){
		var encId = "";
		var encDelId = new Array();
		var dataCaption = "";
		var dataItemOrder = "";
		var dataOperation = "";
			
		var listStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'list_image_upload'
				},
				reader:{
					type: 'json',
					root: 'table',
					idProperty: 'id',
					totalProperty: 'total'
				}
			},
			pageSize: config.itemsPerPage,
			autoLoad: true,
			fields: config.listFields,
			remoteSort: true
		});
		
		var gridFilter = {
			id: 'filters',
			ftype: 'filters',
			autoReload: true,
			local: false,
			filters:[{
				type: 'string',
				dataIndex: 'path'
			},{
				type: 'string',
				dataIndex: 'caption'
			}]
		}
		
		Ext.define('actionRequest', {
			extend: 'Ext.data.Connection',
			singleton: true,
			constructor : function(config){
				this.callParent([config]);
				this.on("beforerequest", function(){Ext.getBody().mask('Loading...');});
				this.on("requestcomplete", function(){Ext.getBody().unmask();});
			}
		});
		
		var detailsForm = {
			id: 'details_img_form',
			xtype: 'container',
			layout: 'form',
			style: 'padding: 5px 20px',
			items:[{
				xtype: 'textfield',
				id: 'details_img_caption',
				fieldLabel: 'Caption',
				allowBlank: true,
				value: ''
			},{
				id: 'details_item_order',
				xtype: 'numberfield',
				fieldLabel: 'Item Order',
				minValue: 0,
				decimalPrecision: 0,
				allowBlank: false,
				allowExponential: false
			}]
		}
		
		var detailsWindow = new Ext.Window({
			title: 'Edit Image',
			width: 400,
			layout: 'form',
			resizable: false,
			closable: true,
			closeAction : 'hide',
			modal: true,
			constrain: true,
			shadow: true,
			items:[detailsForm],
			listeners:{
				'show': function(obj){
					Ext.getCmp('details_img_caption').setValue(dataCaption);
					Ext.getCmp('details_item_order').setValue(dataItemOrder);
				}
			},
			buttons:[{
				text: 'Save',
				handler: function(){
					dataItemOrder = Ext.getCmp('details_item_order').getValue();
					dataCaption = Ext.getCmp('details_img_caption').getValue();
					
					Ext.define('submitRequest', {
						extend: 'Ext.data.Connection',
						singleton: true,
						constructor : function(config){
							this.callParent([config]);
							this.on("beforerequest", function(){Ext.getBody().mask('Loading...');});
							this.on("requestcomplete", function(){Ext.getBody().unmask();});
						}
					});
					
					actionRequest.request({
						url: HTTP_AJAX,
						params:{
							opt: 'submit_item_img',
							id: encId,
							caption: dataCaption,
							item_order: dataItemOrder,
							operation: dataOperation
						},
						success: function(response){
							var feedback = Ext.decode(response.responseText);
							Ext.Msg.alert('Image List', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
							if(feedback.success){
								detailsWindow.hide();
								listStore.reload();
							}
						},
						failure: function(response){
							Ext.Msg.alert('Image List', 'Oops.. something wrong with the connection to server. Please try again.');
						}
					});
				}
			},{
				text: 'Close',
				handler: function(){detailsWindow.hide();}
			}]
		});
		
		var uploadForm = {
			id: 'upload_form',
			xtype: 'form',
			layout: 'form',
			style: 'padding: 5px 20px;',
			baseCls: 'x-container',
			items:[{
				xtype: 'hidden',
				name: 'opt',
				value: 'upload_img'
			},{
				xtype: 'filefield',
				name: 'img_image',
				fieldLabel: 'Image',
				allowBlank: false,
				buttonText: 'Select Image...'
			},{
				xtype: 'textfield',
				id: 'img_caption',
				name: 'img_caption',
				fieldLabel: 'Caption',
				allowBlank: true,
				value: ''
			},{
				xtype: 'numberfield',
				id: 'img_order',
				name: 'img_order',
				fieldLabel: 'Item Order',
				allowBlank: false,
				allowDecimals: false,
				minValue: 0,
				value: 0
			}]
		}
		
		var uploadImg = new Ext.Window({
			title: 'Upload New Image',
			width: 400,
			height: 200,
			layout: 'form',
			resizable: false,
			closable: true,
			closeAction : 'hide',
			modal: true,
			constrain: true,
			shadow: true,
			items:[uploadForm],
			listeners:{
				show: function(obj){
					Ext.getCmp('img_caption').setValue('');
					Ext.getCmp('img_order').setValue('0');
				}
			},
			buttons:[{
				text: 'Save',
				handler: function(){
					var form = Ext.getCmp('upload_form').getForm();
					if (form.isValid()) {
						form.submit({
							url: HTTP_AJAX+"?opt=upload_img",
							waitMsg: 'Uploading image...',
							success: function(form, action){
							   Ext.Msg.alert('Success', action.result.message);
							   uploadImg.hide();
							   listStore.reload();
							},
							failure: function(form, action){
								if(action.failureType === Ext.form.action.Action.SERVER_INVALID){
									Ext.Msg.alert('Image', action.result.message);
								}else{
									Ext.Msg.alert('Image List', 'Could not connect with server. Upload operation terminated. Please try again.');
								}
							}
						});
					}
				}
			},{
				text: 'Close',
				handler: function(){uploadImg.hide();}
			}]
		});

		ImgListPanel.superclass.constructor.call(this, {
			id: 'grid_item_img_listing',
			height: 400,
			dockedItems:[{
				xtype: 'toolbar',
enableOverflow: true,
				dock: 'top',
				items:[{
					id: 'panel_btn_new_img',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/plus-16-gray.png',
					text: 'Upload New Image',
					hidden: !config.allowNew,
					handler: function(){
						uploadImg.show();
					}
				},{
					id: 'panel_btn_edit_img',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/pencil-16-gray.png',
					hidden: !config.allowEdit,
					disabled: true,
					text: 'Edit Image',
					handler: function(){
						dataOperation = 'edit';
						detailsWindow.show();
					}
				},{
					id: 'panel_btn_delete_img',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/minus-16-gray.png',
					hidden: !config.allowDelete,
					disabled: true,
					text: 'Delete Image',
					handler: function(){
						Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected image?', function(btn){
							if(btn == 'yes'){
								actionRequest.request({
									url: HTTP_AJAX,
									params:{
										opt: 'delete_item_img',
										id: encDelId.join(';')
									},
									success: function(response){
										var feedback = Ext.decode(response.responseText);
										Ext.Msg.alert('Image List', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
										listStore.reload();
									},
									failure: function(response){
										Ext.Msg.alert('Image List', 'Oops.. something wrong with the connection to server. Please try again.');
									}
								});
							}
						});
					}				
				}]
			},{
				xtype: 'pagingtoolbar',
enableOverflow: true,
				dock: 'bottom',
				store: listStore,
				pageSize: config.itemsPerPage,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				plugins: new Ext.ux.ProgressBarPager()
			}],
			columns:{
				defaults:{
					width: 150
				},
				items:[{
					xtype: 'rownumberer',
					resizable: true,
					width: 35
				},{
					header: 'Thumbnails',
					dataIndex: 'path',
					renderer: function(value){
						return '<img src="'+config.link+value.replace('/', '/thw85_')+'">';
					}
				},{
					header: 'Caption',
					dataIndex: 'caption'
				},{
					header: 'Item Order',
					dataIndex: 'item_order'
				},{
					header: 'Created By',
					dataIndex: 'created_by_format',
					sortable: false
				},{
					header : 'Created Date',
					dataIndex: 'created_date'
				},{
					header: 'Modified By',
					dataIndex: 'modified_by_format',
					sortable: false
				},{
					header: 'Modified Date',
					dataIndex: 'modified_date'
				}]
			},
			features:[gridFilter],
			store: listStore,
			viewConfig:{
				emptyText: 'No records found.'
			},
			selModel:{
				selType: 'checkboxmodel',
				mode: 'MULTI'
			},
			listeners:{
				'selectionchange': function(sm, sel){
					Ext.getCmp('panel_btn_edit_img').disable();
					Ext.getCmp('panel_btn_delete_img').disable();

					if(sm.getCount() >= 1){
						if(sm.getCount() == 1){
							Ext.getCmp('panel_btn_edit_img').enable();
							encId = sel[0].get('enc_id');
							dataItemOrder = sel[0].get('item_order');
							dataCaption = sel[0].get('caption');
						}
						encDelId = new Array();
						for(i=0; i<sm.getCount(); i++){
							encDelId.push(sel[i].get('enc_id'));
						}
						Ext.getCmp('panel_btn_delete_img').enable();
					}
				},
				'celldblclick': function(obj){
					if(config.allowEdit == '1'){
						dataOperation = 'edit';
						detailsWindow.show();
					}
				}
			}
		});
		
		Ext.EventManager.onWindowResize(function () {
			Ext.getCmp('ext-container').doLayout();
		});
	}
});