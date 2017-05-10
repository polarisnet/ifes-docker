Ext.namespace('Search', 'ListPanelOfSearchFields');
Ext.define('ListPanelOfSearchFields', {
	extend: 'Ext.grid.Panel',
	constructor: function(config){
		var encId = "";
		var encDelId = new Array();
		var dataParent = config.parent;
		var dataSearch = config.search;
		var dataField = "";
		var dispField = "";
		var defaultField = "";
		var dataOperation = "";
		var verifySelect = false;
		
		var listStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'list_search_fields',
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
					if($('#grid-count-info-searchfields').length != 0){
						if(successful){
							document.getElementById('grid-count-info-searchfields').innerHTML = records.length;
						}else{
							document.getElementById('grid-count-info-searchfields').innerHTML = '0';
						}
					}
				}
			}
		});
		
		var fieldStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'combo_searchfield',
					search: config.search,
					parent: config.parent					
				},
				reader:{
					type: 'json',
					root: 'combo',
					totalProperty: 'total_row'
				},
				limitParam: undefined, 
				startParam: undefined, 
				pageParam: undefined
			},
			autoLoad: true,
			fields:['Field', 'Comment']
		});		
				
		var gridFilter = {
			id: 'filters',
			ftype: 'filters',
			autoReload: true,
			local: false,
			filters:[{
				type: 'string',
				dataIndex: 'field_name'
			}]
		}
				
		var detailsSearchFieldForm = Ext.create('Ext.form.Panel', {
			id: 'searchfields_details_form',
			xtype: 'container',
			layout: 'form',
			border 	: false,
			style: 'padding: 5px 20px',
			items:[{
				id: 'details_field',
				xtype: 'combo',
				store: fieldStore,
				anchor: '100%',
				fieldLabel: 'Field Name',
				emptyText: 'Please select field',
				root: 'combo',
				queryMode: 'local',
				allowBlank: false,
				valueField: 'Field',
				displayField: 'Comment',
				selectOnFocus: true,
				matchFieldWidth: true,
				typeAhead: true,
				listeners:{
					'afterrender': function() {
						defaultField = dataField;
					},
					'select': function(obj, record, opts){
						dataField = record[0].get('Field');	
						verifySelect = true;
					},
					'change': function(obj, newValue, oldValue, opts){
						verifySelect = false;
					},
					'blur': function(field, eOpts) {
						var currentValue = field.getRawValue();
						var recordIndex = field.findRecord(field.displayField, currentValue);	
						if(recordIndex && !verifySelect){
							this.store.findBy(function(r){
								if(r.get('Comment') === currentValue){
									dataField = r.get('Field');	
								}
							});
						}else if(!recordIndex && !verifySelect){							
							field.getStore().clearFilter();
							field.setValue("");
							dataField = "";
						}						
					}
				}	
			}]
		});
		
		var detailsSearchFieldWindow = new Ext.Window({
			title: 'New Search Field',
			width: 400,			
			autoHeight: true,
			layout: 'form',
			resizable: false,
			closable: true,
			closeAction : 'hide',
			modal: true,
			constrain: true,
			shadow: true,
			items:[detailsSearchFieldForm],
			listeners:{
				'show': function(obj){
					Ext.getBody().unmask();	
					verifySelect = false;
					if(dataOperation == 'edit'){						
						obj.setTitle('Edit Search Field');								
						Ext.getCmp('details_field').setValue(dispField);
					}else{						
						obj.setTitle('New Search Field');
						detailsSearchFieldForm.getForm().reset();
					}
				}
			},
			buttons:[{
				text: 'Save',
				handler: function(){				
					var temp_dispField = Ext.getCmp('details_field').getRawValue();
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
										
					if (detailsSearchFieldForm.getForm().isValid()) {
						submitRequest.request({
							url: HTTP_AJAX,
							params:{
								opt: 'add_search_field',
								id: encId,
								field: dataField,													
								field_name: temp_dispField,
								parent: config.parent,
								operation: dataOperation
							},
							success: function(response){
								var feedback = Ext.decode(response.responseText);
								Ext.Msg.alert('Search Fields', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
								if(feedback.success){
									detailsSearchFieldWindow.hide();																				
									dispField = Ext.getCmp('details_field').getValue();		
									listStore.reload();										
								}
							},
							failure: function(response){
								Ext.Msg.alert('Search Field Details', 'Oops.. something wrong with the connection to server. Please try again.');
							}
						});
					}else if(Ext.getCmp('details_field').isValid() == false){	
						Ext.Msg.alert('Search Fields', 'Field cannot be empty. Please select field.', function(){
							Ext.getCmp('details_field').focus();
						});
					}
				}
			// },{
				// text: 'Reset',
				// handler: function(){
					// if(dataOperation == 'new'){
						// detailsSearchFieldForm.getForm().reset();
					// }else{console.log(defaultField);
						// Ext.getCmp('details_field').setValue(dispField);
					// }
				// }
			// },{
				// text: 'Delete',
				// handler: function(){
					// actionRequest.request({
						// url: HTTP_AJAX,
						// params:{
							// opt: 'delete_search_field',
							// id: encId
						// },
						// success: function(response){
							// var feedback = Ext.decode(response.responseText);
							// Ext.Msg.alert('Search Field Details', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
							// detailsSearchFieldWindow.hide();
							// listStore.reload();
						// },
						// failure: function(response){
							// Ext.Msg.alert('Search Field Details', 'Oops.. something wrong with the connection to server. Please try again.');
						// }
					// });
				// }	
			},{
				text: 'Close',
				handler: function(){detailsSearchFieldWindow.hide();}
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
		
		ListPanelOfSearchFields.superclass.constructor.call(this, {
			height: 400,
			id: 'search_field_grid',
			dockedItems:[{
				xtype: 'toolbar',
enableOverflow: true,
				dock: 'top',
				hidden: !config.allowEdit,
				items:[{
					id: 'panel_btn_searchField_new',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/plus-16-gray.png',
					text: 'New Search Field',
					handler: function(){
						dataOperation = 'new';
						detailsSearchFieldWindow.show();
					}
				},{
					id: 'panel_btn_searchField_edit',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/pencil-16-gray.png',
					disabled: true,
					text: 'Edit Search Field',
					handler: function(){
						dataOperation = 'edit';
						detailsSearchFieldWindow.show();
					}
				},{
					id: 'panel_btn_searchField_delete',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/minus-16-gray.png',
					disabled: true,
					text: 'Delete Search Field',
					handler: function(){
						Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected field?', function(btn){
							if(btn == 'yes'){
								actionRequest.request({
									url: HTTP_AJAX,
									params:{
										opt: 'delete_search_field',
										id: encDelId.join(';')
									},
									success: function(response){
										var feedback = Ext.decode(response.responseText);
										Ext.Msg.alert('Search Field Details', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
										listStore.reload();
									},
									failure: function(response){
										Ext.Msg.alert('Search Field Details', 'Oops.. something wrong with the connection to server. Please try again.');
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
					header: 'Field',
					dataIndex: 'field_name'			
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
					dataIndex: 'modified_date',
					flex: 1
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
					Ext.getCmp('panel_btn_searchField_edit').disable();
					Ext.getCmp('panel_btn_searchField_delete').disable();
					if(sm.getCount() >= 1){
						if(sm.getCount() == 1){
							Ext.getCmp('panel_btn_searchField_edit').enable();
							encId = sel[0].get('enc_id');
							dataField = sel[0].get('field');
							defaultField = sel[0].get('field');
							dispField = sel[0].get('field_name');
						}
						encDelId = new Array();
						for(i=0; i<sm.getCount(); i++){
							encDelId.push(sel[i].get('enc_id'));
						}
						Ext.getCmp('panel_btn_searchField_delete').enable();
					}
				},
				'celldblclick': function(obj){
					if(config.allowEdit == '1'){
						dataOperation = 'edit';
						detailsSearchFieldWindow.show();
					}
				}
			}
		});
		
		Ext.EventManager.onWindowResize(function () {
			Ext.getCmp('ext-container-searchfields').doLayout();
		});
	}
});