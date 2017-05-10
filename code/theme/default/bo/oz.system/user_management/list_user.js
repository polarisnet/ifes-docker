Ext.namespace('User', 'ListPanel');
Ext.define('ListPanel', {
	extend: 'Ext.grid.Panel',
	constructor: function(config){
		var encId = "";
		var encDelId = new Array();
		var listStore = new Ext.data.JsonStore({
			proxy:{
				type: 'ajax',
				url: HTTP_AJAX,
				extraParams:{
					opt: 'list_user'
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
				dataIndex: 'username'
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

		ListPanel.superclass.constructor.call(this, {
			id: 'grid_user_listing',
			height: 400,
			dockedItems:[{
				xtype: 'toolbar',
enableOverflow: true,
				dock: 'top',
				items:[{
					id: 'panel_btn_new',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/plus-16-gray.png',
					text: 'New User',
					hidden: !config.allowNew,
					handler: function(){
						window.location = config.newLink;
					}
				},{
					id: 'panel_btn_view',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/file-16-gray.png',
					hidden: !config.allowView,
					disabled: true,
					text: 'View User',
					handler: function(){
						window.location = config.viewLink+'?key='+encId;
					}
				},{
					id: 'panel_btn_edit',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/pencil-16-gray.png',
					hidden: !config.allowEdit,
					disabled: true,
					text: 'Edit User',
					handler: function(){
						window.location = config.editLink+'?key='+encId;
					}
				},{
					id: 'panel_btn_resetpassword',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/pencil-16-gray.png',
					hidden: !config.allowResetPassword,
					disabled: true,
					text: 'Reset Password',
					handler: function(){
						window.location = config.resetpasswordLink+'?key='+encId;
					}	
				},{
					id: 'panel_btn_delete',
					xtype: 'button',
					cls: 'x-btn-text-icon',
					icon: HTTP_MEDIA+'/site-image/minus-16-gray.png',
					hidden: !config.allowDelete,
					disabled: true,
					text: 'Delete User',
					handler: function(){
						Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete selected users?', function(btn){
							if(btn == 'yes'){
								actionRequest.request({
									url: HTTP_AJAX,
									params:{
										opt: 'delete_users',
										id: encDelId.join(';')
									},
									success: function(response){
										var feedback = Ext.decode(response.responseText);
										Ext.Msg.alert('Users List', '<p class="fix-x-multiline-msg">'+feedback.message+'</p>');
										listStore.reload();
									},
									failure: function(response){
										Ext.Msg.alert('Users List', 'Oops.. something wrong with the connection to server. Please try again.');
									}
								});
							}
						});
					}
				},{
					id: "btnResetGridView",
					icon: HTTP_MEDIA+"/site-image/table_refresh.png",
					cls: "x-btn-text-icon",
					text: "Reset Grid to Default",
					//tooltip: "Reset Grid to Default",
					disabled: false,
					enableToggle: false,
					pressed: false,
					handler: function(){
						gridDeleteState('user_listing', JS_USERID, this, listStore);
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
					header: 'Profile Pic',
					dataIndex: 'profile_pic',
					width: 80,
					renderer: function(value){
						return '<img src="'+value+'" style="width: 50px; height: auto;">';
					}
				},{
					header: 'Username',
					dataIndex: 'username'
				},{
					header: 'First Name',
					dataIndex: 'first_name'
				},{
					header: 'Last Name',
					dataIndex: 'last_name'
				},{
					header: 'Email',
					dataIndex: 'email',
					width: 200
				},{
					header: 'UID',
					dataIndex: 'uid',
					width: 200
				},{
					header: 'Access',
					dataIndex: 'access',
					width: 100
				},{
					header: 'Status',
					dataIndex: 'status',
					width: 100,
					renderer: function(value){
						if(value == '1'){
							return '<span style="color: green">Active</span>';
						}else{
							return '<span style="color: red">Blocked</span>';
						}
					}
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
			stateId: 'sm_user_listing',
			stateEvents: ['columnmove', 'columnresize', 'sortchange', 'groupchange'],
			listeners:{
				'selectionchange': function(sm, sel){
					Ext.getCmp('panel_btn_view').disable();
					Ext.getCmp('panel_btn_edit').disable();
					Ext.getCmp('panel_btn_resetpassword').disable();
					Ext.getCmp('panel_btn_delete').disable();					

					if(sm.getCount() >= 1){
						if(sm.getCount() == 1){
							Ext.getCmp('panel_btn_view').enable();
							Ext.getCmp('panel_btn_edit').enable();
							Ext.getCmp('panel_btn_resetpassword').enable();
							encId = sel[0].get('enc_id');
						}
						encDelId = new Array();
						for(i=0; i<sm.getCount(); i++){
							encDelId.push(sel[i].get('enc_id'));
						}
						Ext.getCmp('panel_btn_delete').enable();
					}
				},
				'celldblclick': function(obj){
					if(config.allowView == '1'){
						window.location = config.viewLink+'?key='+encId;
					}
				},
				statesave: function (objInit, state, eOpts) {
					if(!objInit.isStateRestoring) {
						gridSaveState('user_listing', JS_USERID, this, listStore);
					}
					if(Object.keys(state.filters).length==0) {
						listStore.reload();
					}
					Ext.getCmp(objInit.id).isStateRestoring = false;
				},
				render: function (objInit) {
					Ext.getCmp(objInit.id).isStateRestoring = true;
					gridRestoreState('user_listing', JS_USERID, this, listStore);
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