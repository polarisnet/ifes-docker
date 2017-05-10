//
// Create general function for emas sync or download
//
emasInsertData = function (thisScope, listStore, mask, remotepath, opt, scn, file, table, uniqueField, syncType/*, emasfields, emastypes*/, totalrows, startrow, increamentrows, headerInfo, totalrows_updated) {
	//alert((totalrows+increamentrows)+" "+startrow);
	if(totalrows >= startrow) {
		
		if(startrow>0) {
			mask.hide();
			mask = new Ext.LoadMask(document.getElementById('ext-grid'), {
				msg: 'Please wait, currently downloaded \''+startrow+'\' row of record'+(startrow>1?"s":"")+'...'
			});
			mask.show();
		}
		
		jQuery.ajax({
			//url: ( remotepath != "" ? remotepath : JS_SERVER + JS_ROOT + "/receiving_stock/index.php" ),
			url: remotepath,
			//url: "http://127.0.0.1:8080/redina/ACCWMS/remote_connectivity/data_request.php",
			dataType: 'jsonp',
			data: {
				//uid: JS_USERID,
				//sid: JS_SESSION_ID,
				//seov: "off",
				//mode: mode,
				opt: opt,
				scn: scn,
				file: file,
				table: table,
				unique_field: uniqueField,
				//emasfields: emasfields,
				//emastypes: emastypes,
				totalrows: totalrows,
				startrow: startrow,
				increamentrows: increamentrows//,
				//security_phase: 'sec2'
			},
			success: function(F){
				// Include header information
				//var F=Ext.util.JSON.decode(D.responseText);
				var headerInfo = "";
				if(F.headerInfo != "") {
					headerInfo = F.headerInfo;
				}
				
				// Ajax to save data into local database
				Ext.Ajax.request({
					//url: JS_SERVER + JS_ROOT + "/emas_sync/index.php",
					url: remotepath,
					params: {
						//uid: JS_USERID,
						//sid: JS_SESSION_ID,
						//seov: "off",
						//mode: "emassync_action",
						opt: opt,
						scn: "savedata",
						file: file,
						table: table,
						unique_field: uniqueField,
						sync_type: syncType,
						headerInfo: headerInfo,
						rowdata: JSON.stringify(F.rowdata)
					},
					method: "POST",
					success: function(X,Y){
						//var Z=Ext.util.JSON.decode(X.responseText);
						var Z = Ext.decode(X.responseText);
						totalrows_updated += Z.totalrows_updated;
						// Include total rows updated information
						//emasInsertData(thisScope, mask, remotepath, mode, opt, scn, file, table, emasfields, emastypes, totalrows, startrow+increamentrows, increamentrows, headerInfo, totalrows_updated);
						emasInsertData(thisScope, listStore, mask, HTTP_AJAX, opt, scn, file, table, uniqueField, syncType/*, config.emasfields, config.emastypes*/, totalrows, startrow+increamentrows, increamentrows, headerInfo, totalrows_updated);
					},
					failure: function(D,E){
						mask.hide();
						Ext.Msg.alert("Error", "Failed to download data from EMAS inventory!");
					},scope: this
				});
				
			},
			complete : function(){
				mask.hide();
			},
			failure: function(D,E){
				mask.hide();
				Ext.Msg.alert("Error", "Failed to download data from EMAS inventory!");
			}
		});
		
	} else {
		mask.hide();
		Ext.Msg.alert("Download Data", "<strong>Table Details</strong><br />" + headerInfo + "<br />" + totalrows_updated + " row"+(totalrows_updated>1?"s":"")+" data"+(totalrows_updated>1?" have":" has")+" been successfully synchronised.",function(){
			//this.fireEvent("refresh", this);
			listStore.reload();
		}, thisScope);
	}
}

autocountInsertData = function (thisScope, listStore, mask, remotepath, opt, scn, file, table, autocountUniqueField, uniqueField, syncType, totalrows, startrow, increamentrows, headerInfo, totalrows_updated) {
	
	if(totalrows >= startrow) {
		
		if(startrow>0) {
			mask.hide();
			mask = new Ext.LoadMask(document.getElementById('ext-grid'), {
				msg: 'Please wait, currently downloaded \''+startrow+'\' row of record'+(startrow>1?"s":"")+'...'
			});
			mask.show();
		}
		
		jQuery.ajax({
			url: remotepath,
			dataType: 'jsonp',
			data: {

				opt: opt,
				scn: scn,
				file: file,
				table: table,
				unique_field_source: autocountUniqueField,
				unique_field: uniqueField,
				totalrows: totalrows,
				startrow: startrow,
				increamentrows: increamentrows
			},
			success: function(F){
				var headerInfo = "";
				if(F.headerInfo != "") {
					headerInfo = F.headerInfo;
				}
				
				// Ajax to save data into local database
				Ext.Ajax.request({
					url: remotepath,
					params: {
						opt: opt,
						scn: "savedata",
						file: file,
						table: table,
						unique_field_source: autocountUniqueField,
						unique_field: uniqueField,
						sync_type: syncType,
						headerInfo: headerInfo,
						rowdata: JSON.stringify(F.rowdata)
					},
					method: "POST",
					success: function(X,Y){
						var Z = Ext.decode(X.responseText);
						totalrows_updated += Z.totalrows_updated;
						autocountInsertData(thisScope, listStore, mask, HTTP_AJAX, opt, scn, file, table, autocountUniqueField, uniqueField, syncType, totalrows, startrow+increamentrows, increamentrows, headerInfo, totalrows_updated);
					},
					failure: function(D,E){
						mask.hide();
						Ext.Msg.alert("Error", "Failed to download data from AutoCount inventory!");
					},scope: this
				});
				
			},
			complete : function(){
				mask.hide();
			},
			failure: function(D,E){
				mask.hide();
				Ext.Msg.alert("Error", "Failed to download data from AutoCount inventory!");
			}
		});
		
	} else {
		mask.hide();
		Ext.Msg.alert("Download Data", "<strong>Table Details</strong><br>" + totalrows_updated + " row"+(totalrows_updated>1?"s":"")+" data"+(totalrows_updated>1?" have":" has")+" been<br> successfully synchronised.",function(){
			//this.fireEvent("refresh", this);
			listStore.reload();
		}, thisScope);
	}
}

qneInsertData = function (thisScope, listStore, mask, remotepath, opt, scn, file, table, qneUniqueField, uniqueField, syncType, totalrows, startrow, increamentrows, headerInfo, totalrows_updated) {
	
	if(totalrows >= startrow) {
		
		if(startrow>0) {
			mask.hide();
			mask = new Ext.LoadMask(document.getElementById('ext-grid'), {
				msg: 'Please wait, currently downloaded \''+startrow+'\' row of record'+(startrow>1?"s":"")+'...'
			});
			mask.show();
		}
		
		jQuery.ajax({
			url: remotepath,
			dataType: 'jsonp',
			data: {

				opt: opt,
				scn: scn,
				file: file,
				table: table,
				unique_field_source: qneUniqueField,
				unique_field: uniqueField,
				totalrows: totalrows,
				startrow: startrow,
				increamentrows: increamentrows
			},
			success: function(F){
				var headerInfo = "";
				if(F.headerInfo != "") {
					headerInfo = F.headerInfo;
				}
				
				// Ajax to save data into local database
				Ext.Ajax.request({
					url: remotepath,
					params: {
						opt: opt,
						scn: "savedata",
						file: file,
						table: table,
						unique_field_source: qneUniqueField,
						unique_field: uniqueField,
						sync_type: syncType,
						headerInfo: headerInfo,
						rowdata: JSON.stringify(F.rowdata)
					},
					method: "POST",
					success: function(X,Y){
						var Z = Ext.decode(X.responseText);
						totalrows_updated += Z.totalrows_updated;
						qneInsertData(thisScope, listStore, mask, HTTP_AJAX, opt, scn, file, table, qneUniqueField, uniqueField, syncType, totalrows, startrow+increamentrows, increamentrows, headerInfo, totalrows_updated);
					},
					failure: function(D,E){
						mask.hide();
						Ext.Msg.alert("Error", "Failed to download data from QNE inventory!");
					},scope: this
				});
				
			},
			complete : function(){
				mask.hide();
			},
			failure: function(D,E){
				mask.hide();
				Ext.Msg.alert("Error", "Failed to download data from QNE inventory!");
			}
		});
		
	} else {
		mask.hide();
		Ext.Msg.alert("Download Data", "<strong>Table Details</strong><br>" + totalrows_updated + " row"+(totalrows_updated>1?"s":"")+" data"+(totalrows_updated>1?" have":" has")+" been<br> successfully synchronised.",function(){
			//this.fireEvent("refresh", this);
			listStore.reload();
		}, thisScope);
	}
}