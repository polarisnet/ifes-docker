Ext.namespace('OptionContent', 'DetailsListPanel');
Ext.define('DetailsListPanel', {
	extend: 'Ext.grid.Panel',
	constructor: function(config){
		var encId = "";
		var encDelId = new Array();
		var dataParent = config.parent;
		var dataParentType = config.parentType;
		var dataOptionLabel = "";
		var dataOptionValue = "";
		var dataOptionOrder = "";
		var dataOperation = "";
		
		var listStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'list_options',
					parent: config.parent
				},
				reader:{
					type: 'json',
					root: 'table',
					idProperty: 'id',
					totalProperty: 'total'
				}
			},
			pageSize: config.itemsPerPage,
			autoLoad: false/*,
			autoLoad:{
				params:{
					start: config.start,
					limit: config.itemsPerPage
				}
			}*/,
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
				dataIndex: 'cf_content_label'
			},{
				type: 'string',
				dataIndex: 'cf_content_data'
			},{
				type: 'string',
				dataIndex: 'cf_content_order'
			}]
		}
		
		var detailsForm = {
			id: 'details_details_form',
			xtype: 'container',
			layout: 'form',
			style: 'padding: 5px 20px',
			items:[{				
				id: 'option_label',
				xtype: 'textfield',
				fieldLabel: 'Option Label',
				allowBlank: false
			},{				
				id: 'option_value',
				xtype: 'textfield',
				allowBlank: false,
				fieldLabel: 'Option Value'			
			},{				
				id: 'option_order',
				xtype: 'numberfield',
				allowNegative: false,
				minValue: 0,
				allowDecimal: false,
				fieldLabel: 'Option Order'		
			}]
		}
		
		var detailsWindow = new Ext.Window({
			title: 'New Item',
			width: 400,
			height: 250,
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
					if(dataOperation == 'edit'){
						obj.setTitle('Edit Option');
						Ext.getCmp('option_label').setValue(dataOptionLabel);
						Ext.getCmp('option_value').setValue(dataOptionValue);	
						Ext.getCmp('option_order').setValue(dataOptionOrder);
					}else{
						obj.setTitle('New Item');
						Ext.getCmp('option_label').setValue('');
						Ext.getCmp('option_value').setValue('');
						Ext.getCmp('option_order').setValue('0');
					}
				}
			},
			buttons:[{
				text: 'Save',
				handler: function(){
					dataOptionLabel = Ext.getCmp('option_label').getValue();
					dataOptionValue = Ext.getCmp('option_value').getValue();
					dataOptionOrder = Ext.getCmp('option_order').getValue();
					if(dataOperation == 'new'){encId = '';}
					if(dataOptionLabel == ''){Ext.Msg.alert('Options List', 'Option Label field cannot be empty. Please input a value for this field.'); return;}
					if(dataOptionValue == ''){Ext.Msg.alert('Options List', 'Option value field cannot be empty. Please input a value for this field.'); return;}					
					
					Ext.define('submitRequest', {
						extend: 'Ext.data.Connection',
						singleton: true,
						constructor : function(config){
							this.callParent([config]);
							this.on("beforerequest", function(){Ext.getBody().mask('Loading...');});
							this.on("requestcomplete", function(){Ext.getBody().unmask();});
						}
					});
					
					submitRequest.request({
						url: HTTP_AJAX,
						params:{
							opt: 'add_option',
							id: encId,							
							label: dataOptionLabel,
							value: dataOptionValue,
							order: dataOptionOrder,
							parent: dataParent,							
							operation: dataOperation
						},
						success: function(response){
							var feedback = Ext.decode(response.responseText);
							Ext.Msg.alert('Message', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
							if(feedback.success){
								detailsWindow.hide();
								listStore.reload();
							}
						},
						failure: function(response){
							Ext.Msg.alert('Error', 'Oops.. something wrong with the connection to server. Please try again.');
						}
					});
				}
			},{
				text: 'Close',
				handler: function(){detailsWindow.hide();}
			}]
		});

		Ext.define('actionRequest', {
			extend: 'Ext.data.Connection',
			singleton: true,
			constructor : function(config){
				this.callParent([config]);
				this.on("beforerequest", function(){Ext.getBody().mask('Loading...');});
				this.on("requestcomplete", function(){Ext.getBody().unmask();});
			}
		});
		
		DetailsListPanel.superclass.constructor.call(this, {
			id: 'grid_system_option_content_listing',
			height: 400,
			dockedItems:[{
				xtype: 'toolbar',
enableOverflow: true,
				dock: 'top',
				hidden: !config.allowEdit,
				items:[{
					id: 'panel_btn_new',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/plus-16-gray.png',
					text: 'New Option',
					handler: function(){
						dataOperation = 'new';
						detailsWindow.show();
					}
				},{
					id: 'panel_btn_edit',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/pencil-16-gray.png',
					disabled: true,
					text: 'Edit Option',
					handler: function(){
						dataOperation = 'edit';
						detailsWindow.show();
					}
				},{
					id: 'panel_btn_delete',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/minus-16-gray.png',
					disabled: true,
					text: 'Delete Option',
					handler: function(){
						Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected option?', function(btn){
							if(btn == 'yes'){
								actionRequest.request({
									url: HTTP_AJAX,
									params:{
										opt: 'delete_options',
										id: encDelId.join(';')
									},
									success: function(response){
										var feedback = Ext.decode(response.responseText);
										Ext.Msg.alert('Options List', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
										listStore.reload();
									},
									failure: function(response){
										Ext.Msg.alert('Options List', 'Oops.. something wrong with the connection to server. Please try again.');
									}
								});
							}
						});
					}
				},{
					id: "btnResetGridViewSystemDetails",
					icon: HTTP_MEDIA+"/site-image/table_refresh.png",
					cls: "x-btn-text-icon",
					text: "Reset Grid to Default",
					//tooltip: "Reset Grid to Default",
					disabled: false,
					enableToggle: false,
					pressed: false,
					handler: function(){
						gridDeleteState('system_option_content_listing', JS_USERID, this, listStore);
					},
					scope: this
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
					header: 'Option Label',
					dataIndex: 'cf_content_label'
				},{
					header: 'Option Value',
					dataIndex: 'cf_content_value'				
				},{
					header: 'Option Order',
					dataIndex: 'cf_content_order'		
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
			stateful: true,
			stateId: 'sm_system_option_content_listing',
			stateEvents: ['columnmove', 'columnresize', 'sortchange', 'groupchange'],
			listeners:{
				'selectionchange': function(sm, sel){					
					Ext.getCmp('panel_btn_edit').disable();
					Ext.getCmp('panel_btn_delete').disable();
					if(sm.getCount() >= 1){
						if(sm.getCount() == 1){		
							Ext.getCmp('panel_btn_edit').enable();
							encId 			= sel[0].get('enc_id');
							dataParentType	= sel[0].get('type');
							//dataParent		= sel[0].get('enc_parent_id');
							dataOptionLabel = sel[0].get('cf_content_label');
							dataOptionValue = sel[0].get('cf_content_value');
							dataOptionOrder = sel[0].get('cf_content_order');
						}
						encDelId = new Array();
						for(i=0; i<sm.getCount(); i++){
							encDelId.push(sel[i].get('enc_id'));
						}
						Ext.getCmp('panel_btn_delete').enable();
					}
				},
				'celldblclick': function(obj){
					if(config.allowEdit == '1'){
						dataOperation = 'edit';
						detailsWindow.show();
					}
				},
				statesave: function (objInit, state, eOpts) {
					if(!objInit.isStateRestoring) {
						gridSaveState('system_option_content_listing', JS_USERID, this, listStore);
					}
					if(Object.keys(state.filters).length==0) {
						listStore.reload();
					}
					Ext.getCmp(objInit.id).isStateRestoring = false;
				},
				render: function (objInit) {
					Ext.getCmp(objInit.id).isStateRestoring = true;
					gridRestoreState('system_option_content_listing', JS_USERID, this, listStore);
				},
				columnmove: function (objInit) {
					Ext.getCmp(objInit.grid.id).isStateRestoring = false;
				},
				columnresize: function (objInit) {
					Ext.getCmp(objInit.grid.id).isStateRestoring = false;
				},
				sortchange: function (objInit) {
					Ext.getCmp(objInit.grid.id).isStateRestoring = false;
				},
				groupchange: function (objInit) {
					Ext.getCmp(objInit.grid.id).isStateRestoring = false;
				},
				scope: this
			}
		});
		
		Ext.EventManager.onWindowResize(function () {
			Ext.getCmp('ext-container').doLayout();
		});
	}
});