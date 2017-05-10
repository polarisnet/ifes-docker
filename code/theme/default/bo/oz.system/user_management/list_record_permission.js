Ext.namespace('RecordPermission', 'ListPanelOfRecordPermission');
Ext.define('ListPanelOfRecordPermission', {
	extend: 'Ext.grid.Panel',
	constructor: function(config){
		var encId = "";
		var encDelId = new Array();
		var dataParent = config.parent;	
		var dataModule = "";
		var dispModule = "";
		var dataField = "";		
		var dispField = "";	
		var dataViewMode = "";	
		var dataOperation = "";
		
		var listStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'list_record_permission',
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
			autoLoad:{
				params:{
					start: config.start,
					limit: config.itemsPerPage
				}
			},
			fields: config.listFields,
			remoteSort: true,
			listeners:{
				'load': function(obj, records, successful){
					if($('#grid-count-info-RecordPermission').length != 0){
						if(successful){
							document.getElementById('grid-count-info-RecordPermission').innerHTML = records.length;
						}else{
							document.getElementById('grid-count-info-RecordPermission').innerHTML = '0';
						}
					}
				}
			}
		});
		
		var moduleStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_module_record_permission'	
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			autoLoad: false,
			fields:['id', 'uid', 'module_display']
		});		
		
		var fieldStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_module_field',
					conditions: ''
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				}
			},
			pageSize: 15,
			autoLoad: false,
			fields:['id', 'field']
		});		
		
		var viewSOStore = Ext.create('Ext.data.ArrayStore', {
			fields: ['value'],
			data: [
				['View Own Record']
			]
		});	
		
		var gridFilter = {
			id: 'filters',
			ftype: 'filters',
			autoReload: true,
			local: false,
			filters:[{
				type: 'string',
				dataIndex: 'cust_no'
			},{
				type: 'string',
				dataIndex: 'name'
			},{
				type: 'string',
				dataIndex: 'business'
			},{
				type: 'string',
				dataIndex: 'cust_relationship'	
			}]
		}
				
		var detailsRecordPermissionForm = Ext.create('Ext.form.Panel', {
			id: 'recordpermission_details_form',
			xtype: 'container',
			layout: 'form',
			border 	: false,
			style: 'padding: 5px 20px',
			items:[{
				id: 'details_module',
				xtype: 'combo',
				store: moduleStore,
				anchor: '100%',
				fieldLabel: 'Module',
				emptyText: 'Please select module',
				root: 'combo',
				queryMode: 'remote',
				allowBlank: false,
				typeAhead: true,
				selectOnFocus: true,
				valueField: 'uid',
				displayField: 'module_display',
				pageSize: 15,
				listConfig:{
					loadingText: 'Searching...',
					//minWidth: 305,
					emptyText: '<div class="ext-empty-live-search">No match found...</div>',
					getInnerTpl: function(){
						return 'Module: {module_display}<hr>';
					}
				},
				listeners:{
					'select': function(obj, record){
						verifySelect = true;
						// dataModule = record[0].get('uid');
						Ext.getCmp('module_field').setValue('');
						fieldStore.getProxy().extraParams = {
							opt: 'combo_module_field',
							conditions: record[0].get('uid')
						};
						fieldStore.load();		
					},			
					'change': function(obj, newValue, oldValue, opts){
						verifySelect = false;
						if(newValue == ''){
							Ext.getCmp('module_field').setValue('');
							fieldStore.getProxy().extraParams = {
								opt: 'combo_module_field',
								conditions: ''
							};
							fieldStore.load();
						}
					},
					'blur': function(field, eOpts) {
						var currentValue = field.getRawValue();
						var recordIndex = field.findRecord(field.displayField, currentValue);	
						// if(recordIndex && !verifySelect){
							// this.store.findBy(function(r){
								// if(r.get('module_display') === currentValue){
									// dataModule = r.get('uid');
								// }
							// });
						// }else 
						if(!recordIndex && !verifySelect){
							field.getStore().clearFilter();
							field.setValue("");
							dataModule = "";
						}						
					}
				}
			},{
				id: 'module_field',
				xtype: 'combo',
				store: fieldStore,
				anchor: '100%',
				fieldLabel: 'Filter By',
				emptyText: 'Please select filter',
				root: 'combo',
				queryMode: 'remote',
				valueField: 'id',
				displayField: 'field',
				allowBlank: false,
				pageSize: 15,
				listConfig:{
					loadingText: 'Searching...',
					//minWidth: 305,
					emptyText: '<div class="ext-empty-live-search">No match found...</div>',
					getInnerTpl: function(){
						return 'Filter By: {field}<hr>';
					}
				},
				listeners:{
					'select': function(obj, record, opts){
						verifySelect = true;
						dataField = record[0].get('id');
					},			
					'change': function(obj, newValue, oldValue, opts){
						verifySelect = false;
						if(newValue == ''){
							Ext.getCmp('module_field').setValue('');
							fieldStore.getProxy().extraParams = {
								opt: 'combo_module_field',
								conditions: ''
							};
							fieldStore.load();
						}
					},
					'blur': function(field, eOpts) {
						var currentValue = field.getRawValue();
						var recordIndex = field.findRecord(field.displayField, currentValue);	
						if(recordIndex && !verifySelect){
							this.store.findBy(function(r){
								if(r.get('field') === currentValue){
									dataField = r.get('id');
								}
							});
						}else if(!recordIndex && !verifySelect){
							field.getStore().clearFilter();
							field.setValue("");
							// Ext.getCmp('module_field').markInvalid("Oops.. the value is not correct. Please try again.");
							dataModule = "";
						}						
					}
				}
			},{	
				id					: 'viewSO_mode',
				xtype				: 'combo',
				store				: viewSOStore,
				anchor				: '100%',
				fieldLabel			: 'View Mode',
				labelWidth			: 130,
				value				: 'View Own Record',
				root				: 'combo',
				queryMode			: 'local',		
				valueField			: 'value',
				displayField		: 'value',
				editable			: false,
				triggerAction		: 'all',
				selectOnFocus		: true,
				matchFieldWidth		: true
			}]
		});
		
		var detailsRecordPermissionWindow = new Ext.Window({
			title: 'New Records Permission',
			width: 400,			
			autoHeight: true,
			layout: 'form',
			resizable: false,
			closable: true,
			closeAction : 'hide',
			modal: true,
			constrain: true,
			shadow: true,
			items:[detailsRecordPermissionForm],
			listeners:{
				'show': function(obj){
					Ext.getBody().unmask();	
					verifySelect = false;
					if(dataOperation == 'edit'){						
						obj.setTitle('Edit Records Permission');	
						Ext.getCmp('details_module').setValue(dispModule);
						Ext.getCmp('module_field').setValue(dispField);
						Ext.getCmp('viewSO_mode').setValue(dataViewMode);
					}else{						
						obj.setTitle('New Records Permission');
						Ext.getCmp('details_module').setValue('');
						Ext.getCmp('module_field').setValue('');
						// Ext.getCmp('viewSO_mode').setValue('');
					}
				}
			},
			buttons:[{
				text: 'Save',
				handler: function(){					
					var temp_dataModule 	= Ext.getCmp('details_module').getValue();
					var temp_dispModule 	= Ext.getCmp('details_module').getRawValue();
					var temp_dispField 		= Ext.getCmp('module_field').getRawValue();
					var temp_dataViewMode 	= Ext.getCmp('viewSO_mode').getValue();
					
					if(dataOperation == 'new'){encId = '';}
					Ext.define('submitRequest', {
						extend: 'Ext.data.Connection',
						singleton: true,
						constructor : function(config){
							this.callParent([config]);
							this.on("beforerequest", function(){Ext.getBody().mask('Loading...');});
							this.on("requestcomplete", function(){Ext.getBody().unmask();});
						}
					});
					if (detailsRecordPermissionForm.getForm().isValid()) {
						dataModule		= temp_dataModule;
						dispModule 		= temp_dispModule;
						dispField 		= temp_dispField;
						dataViewMode 	= temp_dataViewMode;
						
						submitRequest.request({
							url: HTTP_AJAX,
							params:{
								opt: 'add_record_permission',
								id: encId,
								group_id: config.parent,
								module_uid: dataModule,													
								module_display: dispModule,
								field_id: dataField,													
								field: dispField,
								view: dataViewMode,
								operation: dataOperation
							},
							success: function(response){
								var feedback = Ext.decode(response.responseText);
								Ext.Msg.alert('Records Permissions', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
								if(feedback.success){
									detailsRecordPermissionWindow.hide();																				
									dispModule = Ext.getCmp('details_module').getValue();		
									dispField = Ext.getCmp('module_field').getValue();		
									listStore.reload();										
								}
							},
							failure: function(response){
								Ext.Msg.alert('Records Permission Details', 'Oops.. something wrong with the connection to server. Please try again.');
							}
						});
					} else {
						Ext.Msg.alert('Records Permission Details', 'Please check your input data.');
					}
				}
			},{
				text: 'Close',
				handler: function(){detailsRecordPermissionWindow.hide();}
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
		
		ListPanelOfRecordPermission.superclass.constructor.call(this, {
			height: 400,
			id: 'related_customer_grid',
			dockedItems:[{
				xtype: 'toolbar',
enableOverflow: true,
				dock: 'top',
				hidden: !config.allowEdit,
				items:[{
					id: 'panel_btn_recordPermission_new',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/plus-16-gray.png',
					text: 'New Records Permission',
					handler: function(){
						dataOperation = 'new';
						detailsRecordPermissionWindow.show();
					}
				},{
					id: 'panel_btn_recordPermission_edit',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/pencil-16-gray.png',
					disabled: true,
					text: 'Edit Records Permission',
					handler: function(){
						dataOperation = 'edit';
						detailsRecordPermissionWindow.show();
					}
				},{
					id: 'panel_btn_recordPermission_delete',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/minus-16-gray.png',
					disabled: true,
					text: 'Delete Records Permission',
					handler: function(){
						Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected record permission?', function(btn){
							if(btn == 'yes'){
								actionRequest.request({
									url: HTTP_AJAX,
									params:{
										opt: 'delete_record_permission',
										id: encDelId.join(';')
									},
									success: function(response){
										var feedback = Ext.decode(response.responseText);
										Ext.Msg.alert('Records Permission Details', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
										listStore.reload();
									},
									failure: function(response){
										Ext.Msg.alert('Records Permission Details', 'Oops.. something wrong with the connection to server. Please try again.');
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
					header: 'Module',
					dataIndex: 'module_display'
				},{
					header: 'Field',
					dataIndex: 'field'
				},{
					header: 'View Mode',
					dataIndex: 'view'
				// },{
					// header: 'Remarks',
					// dataIndex: 'type'				
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
				deferEmptyText: false,
				emptyText: 'No records found.'
			},
			selModel:{
				selType: 'checkboxmodel',
				mode: 'MULTI'
			},
			listeners:{
				'selectionchange': function(sm, sel){
					Ext.getCmp('panel_btn_recordPermission_edit').disable();
					Ext.getCmp('panel_btn_recordPermission_delete').disable();
					if(sm.getCount() >= 1){
						if(sm.getCount() == 1){
							Ext.getCmp('panel_btn_recordPermission_edit').enable();	
							
							encId 			= sel[0].get('enc_id');
							dataModule 		= sel[0].get('module_uid');
							dispModule 		= sel[0].get('module_display');
							dataField 		= sel[0].get('field_id');
							dispField 		= sel[0].get('field');
							dataViewMode 	= sel[0].get('view');							
						}
						encDelId = new Array();
						for(i=0; i<sm.getCount(); i++){
							encDelId.push(sel[i].get('enc_id'));
						}
						Ext.getCmp('panel_btn_recordPermission_delete').enable();
					}
				},
				'celldblclick': function(obj){
					if(config.allowEdit == '1'){
						dataOperation = 'edit';
						detailsRecordPermissionWindow.show();
					}
				}
			}
		});
		
		Ext.EventManager.onWindowResize(function () {
			Ext.getCmp('ext-container-recordpermission').doLayout();
		});
	}
});