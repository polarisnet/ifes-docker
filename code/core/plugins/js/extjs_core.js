Ext.Loader.setConfig({
    disableCaching: false
});

Ext.tip.QuickTipManager.init();
// Override Store Load
Ext.override(Ext.data.JsonStore, {
	listeners:{
		'load': function(obj, records, successful, opts){
			if(successful){
				currentTotal = obj.getCount();
				currentPage = obj.currentPage;
				if(currentTotal == 0 && currentPage > 1){
					obj.previousPage();
				}
			}
		}
	}
});

// Override Field Number - set value to 0 if field is empty and clear value on focus if value is 0
Ext.override(Ext.form.field.Number, {
	listeners:{	
		'blur': function(d) { 
			var newValue = d.getValue();
			if(newValue == '' || newValue == null){
				d.setValue('0');
			} else {
				d.setValue(newValue);
			}
		},
		'focus': function(c) {
			var newValue = c.getValue();
			if(newValue == '0'){
				c.setValue('');
			}
		}
	}
});

// Custom combobox
Ext.override(Ext.form.field.ComboBox, {
	minChars: 1,	
	width: 208,
	matchFieldWidth: false,
    onLoad: function() {
        var me = this,
        value = me.value;

        if (me.ignoreSelection > 0) {
            --me.ignoreSelection;
        }

        if (me.rawQuery) {
            me.rawQuery = false;
            me.syncSelection();
            if (me.picker && !me.picker.getSelectionModel().hasSelection()) {
                me.doAutoSelect();
            }
        }else{
            if (me.value || me.value === 0) {
                if (me.pageSize === 0) { // added for paging; do not execute on page change
                    me.setValue(me.value);
                }
            } else {
                if (me.store.getCount()) {
                    me.doAutoSelect();
                }else{
                    me.setValue(me.value);
                }
            }
        }
    }
});

//Custom Grid with multiple select to allow deselet
Ext.override( Ext.selection.Model, {
    selectWithEvent: function(record, e, keepExisting) {
        var me = this;

        switch (me.selectionMode) {
            case 'MULTI':
                if (e.ctrlKey && me.isSelected(record)) {
                    me.doDeselect(record, false);
                } else if (e.shiftKey && me.lastFocused) {
                    me.selectRange(me.lastFocused, record, e.ctrlKey);
                } else if (e.ctrlKey) {
                    me.doSelect(record, true, false);
                // Mod Start
                } else if (me.isSelected(record) && !e.shiftKey && !e.ctrlKey && me.selected.getCount() == 1 && me.allowDeselect) {
                    me.doDeselect(record, false);                    
                // Mod End                        
                } else if (me.isSelected(record) && !e.shiftKey && !e.ctrlKey && me.selected.getCount() > 1) {
                    me.doSelect(record, keepExisting, false);
                } else {
                    me.doSelect(record, false);
                }
                break;
            case 'SIMPLE':
                if (me.isSelected(record)) {
                    me.doDeselect(record);
                } else {
                    me.doSelect(record, true);
                }
                break;
            case 'SINGLE':
                // if allowDeselect is on and this record isSelected, deselect it
                if (me.allowDeselect && me.isSelected(record)) {
                    me.doDeselect(record);
                // select the record and do NOT maintain existing selections
                } else {
                    me.doSelect(record, false);
                }
                break;
        }
    },
});

// Save/ Restore grid state
function gridSaveState(gridID, userID, gridView, dataStore) {
	var smGridID = "sm_"+gridID;
	var gdGridID = "grid_"+gridID;

	//var columnModel = gridView.columns;
	var columnModel = gridView.getView().getHeaderCt().getGridColumns();
	var sortState = dataStore.getSorters();
	//var colIdx = "-1";

	var c = [];
	var attr = {};
	
	for (var i = 0; i < columnModel.length; i++) {
		//if(columnModel[i].dataIndex=="") { continue; }
		//if(columnModel[i].isCheckerHd) { continue; }
		attr = {
			dataIndex: columnModel[i].dataIndex,
			id: columnModel[i].id,
			hidden: columnModel[i].hidden,
			width: columnModel[i].width,
			locked: columnModel[i].locked
		}
		if (sortState.length>0) {
			if (columnModel[i].dataIndex == sortState[0].property) { 
				//columnModel[i]['direction'] = sortState[0].direction;
				attr.direction = sortState[0].direction;
			}
		}
		
		for(j = 0; j < gridView.filters.getFilterData().length; j++) {
			//console.log(gridView.filters.getFilterData()[j]);			
			if(columnModel[i].dataIndex==gridView.filters.getFilterData()[j].field) {
				attr.filter_type = gridView.filters.getFilterData()[j].data.type;
				if(gridView.filters.getFilterData()[j].data.type=="date") {
					if(gridView.filters.getFilterData()[j].data.comparison=="lt") {
						attr.filter_lt_value = gridView.filters.getFilterData()[j].data.value;
						attr.filter_lt_comparison = gridView.filters.getFilterData()[j].data.comparison;
					} if(gridView.filters.getFilterData()[j].data.comparison=="gt") {
						attr.filter_gt_value = gridView.filters.getFilterData()[j].data.value;
						attr.filter_gt_comparison = gridView.filters.getFilterData()[j].data.comparison;
					} if(gridView.filters.getFilterData()[j].data.comparison=="eq") {
						attr.filter_eq_value = gridView.filters.getFilterData()[j].data.value;
						attr.filter_eq_comparison = gridView.filters.getFilterData()[j].data.comparison;
					}
				} if(gridView.filters.getFilterData()[j].data.type=="numeric") {
					if(gridView.filters.getFilterData()[j].data.comparison=="lt") {
						attr.filter_lt_value = gridView.filters.getFilterData()[j].data.value.toString().replace(/'/gi, "&#039");
						attr.filter_lt_comparison = gridView.filters.getFilterData()[j].data.comparison;
					} if(gridView.filters.getFilterData()[j].data.comparison=="gt") {
						attr.filter_gt_value = gridView.filters.getFilterData()[j].data.value.toString().replace(/'/gi, "&#039");
						attr.filter_gt_comparison = gridView.filters.getFilterData()[j].data.comparison;
					} if(gridView.filters.getFilterData()[j].data.comparison=="eq") {
						attr.filter_eq_value = gridView.filters.getFilterData()[j].data.value.toString().replace(/'/gi, "&#039");
						attr.filter_eq_comparison = gridView.filters.getFilterData()[j].data.comparison;
					}
				} if(gridView.filters.getFilterData()[j].data.type=="string" || gridView.filters.getFilterData()[j].data.type=="list") {
					attr.filter_value = gridView.filters.getFilterData()[j].data.value.toString().replace(/'/gi, "&#039");
				} if(gridView.filters.getFilterData()[j].data.type=="boolean") {
					if(gridView.filters.getFilterData()[j].data.value) {
						attr.filter_value = "1";
					} else {
						attr.filter_value = "0";
					}
				}
			}
		}
		c.push(attr);
	}
	var objState = Ext.encode(c);
        
        Ext.Ajax.request({
		url: JS_GRIDSTATES + '/savestates',
		params: {
			//mode: "grid_state_settings",
			opt: "save",
			smid: smGridID,
			gridid: gdGridID,
			userid: userID,
			settings: encodeURIComponent(objState)
		},
		method: "POST",
		success: function(D,E){
			var F = Ext.decode(D.responseText);
			if(F.success){
				//Ext.Msg.alert("Error", "grid state saved");
			}
			else {
				//Ext.Msg.alert("Error", "Fail to save grid state");
			}
//                        if(Ext.getCmp('btnResetGridView') !== undefined) {
//                            if(objState == '') {
//                                Ext.getCmp('btnResetGridView').disable();
//                            }else {
//                                Ext.getCmp('btnResetGridView').enable();
//                            }
//                        }
                        if(Ext.getCmp('btnResetFilter') !== undefined) {
                            if(gridView.filters.getFilterData().length == 0) {
                                Ext.getCmp('btnResetFilter').setIcon(HTTP_MEDIA+"/site-image/filter.png");
                                Ext.getCmp('btnResetFilter').setText('Filter: Off');
                                Ext.getCmp('btnResetFilter').disable();
                            }else {
                                Ext.getCmp('btnResetFilter').setIcon(HTTP_MEDIA+"/site-image/clear_filter.png");
                                Ext.getCmp('btnResetFilter').setText('Clear Filter');
                                Ext.getCmp('btnResetFilter').enable();
                                if(Ext.getCmp('searchgrid_field') !== undefined) {
                                    Ext.getCmp('searchgrid_field').setValue('');
                                    Ext.getCmp('searchgrid_field').fireEvent('doneSearch',Ext.getCmp('searchgrid_field'));
                                    Ext.getCmp('panel_btn_searchgrid').setIcon(HTTP_MEDIA+"/site-image/searchicon.png");
                                }
                            }
                        }
                        
		},
		failure: function(D,E){
			//Ext.Msg.alert("Error", "Fail to save grid state");
		},scope: this
	})
}

function searchGrid(grid, searhField, currSearch, listStore, opt, arrColumns) {
    if(currSearch != searhField.getValue()) {
        Ext.getCmp(grid).filters.clearFilters();
        listStore.getProxy().extraParams= {
            opt: opt,
            search_value: searhField.getValue(),
            search_columns: Ext.encode(arrColumns)
        }
        listStore.reload();
        if(searhField.getValue() == '') {
            Ext.getCmp('panel_btn_searchgrid').setIcon(HTTP_MEDIA+"/site-image/searchicon.png");
        }else {
            Ext.getCmp('panel_btn_searchgrid').setIcon(HTTP_MEDIA+"/site-image/searchapplied.png");
        }
        searhField.fireEvent('doneSearch',searhField);
    }
}

function clearSearchGrid(state,listStore, opt) {
    if(Object.keys(state.filters).length != 0) {
        listStore.getProxy().extraParams= {
            opt: opt,
            search_value: ''
        }
    }
}

function findColumnIndex(columns, dataIndex) {
    var index;
    for (index = 0; index < columns.length; index++) {
		if(columns[index].dataIndex == "") { continue; }
        if (columns[index].dataIndex == dataIndex) { break; }
	}
    return index == columns.length ? -1 : index;
}
function gridRestoreState(gridID, userID, gridView, dataStore) {
	var smGridID = "sm_"+gridID;
	var gdGridID = "grid_"+gridID;
	
	Ext.getCmp(gdGridID).isStateRestoring = true;
	
	Ext.Ajax.request({
		url: JS_GRIDSTATES + '/savestates',
		params: {
			//mode: "grid_state_settings",
			opt: "restore",
			smid: smGridID,
			gridid: gdGridID,
			userid: userID
		},
		method: "POST",
		success: function(D,E){
			var columnModel = gridView.getView().getHeaderCt().getGridColumns(); // Get current column states
			Ext.getCmp(gdGridID).suspendEvents(false);
			var F = Ext.decode(D.responseText);
			if(F.success && F.settings && F.settings!=""){
				var newConfig = eval(decodeURIComponent(F.settings));
				var oldCol, columnSort;
				var sortingIndex = "", sortingDirection = "";
				
				var newConfigLength = newConfig.length;
				if(columnModel.length != newConfigLength) { return false; }
				// First to move the columns in correct order.
				for (var i = 0; i < newConfigLength; i++) {
					if(columnModel[i].dataIndex == "" || newConfig[i].dataIndex == "") { continue; }
					oldCol = -1; oldCol = findColumnIndex(gridView.getView().getHeaderCt().getGridColumns(), newConfig[i].dataIndex);
					if (oldCol != -1) {
						if (oldCol != i) { gridView.headerCt.move(oldCol, i); }
					}
				}
				// Second to hide, set width, sorting and filter of the columns.
				gridView.filters.view.headerCt.getMenu();
                                var gridChange = false;
				for (var i = 0; i < newConfigLength; i++) {
					if(newConfig[i].hidden) {
						gridView.getView().getHeaderCt().getGridColumns()[i].hide();
					}
					if (parseInt(gridView.getView().getHeaderCt().getGridColumns()[i].width) != parseInt(newConfig[i].width)) {
						gridView.getView().getHeaderCt().getGridColumns()[i].setWidth(parseInt(newConfig[i].width));
					}
					if (newConfig[i].direction) {
                                                gridChange = true;
						sortingIndex = newConfig[i].dataIndex;
						sortingDirection = newConfig[i].direction;
						dataStore.sort(sortingIndex, sortingDirection);
					}
					if(newConfig[i].filter_type) {
                                                gridChange = true;
						gridView.filters.getFilter(newConfig[i].dataIndex).setActive(true);
						if(newConfig[i].filter_type=="date") {
							if(newConfig[i].filter_lt_comparison && newConfig[i].filter_lt_comparison=="lt") {
								gridView.filters.getFilter(newConfig[i].dataIndex).setValue({before: new Date(newConfig[i].filter_lt_value)});
							} if(newConfig[i].filter_gt_comparison && newConfig[i].filter_gt_comparison=="gt") {
								gridView.filters.getFilter(newConfig[i].dataIndex).setValue({after: new Date(newConfig[i].filter_gt_value)});
							} if(newConfig[i].filter_eq_comparison && newConfig[i].filter_eq_comparison=="eq") {
								gridView.filters.getFilter(newConfig[i].dataIndex).setValue({on: new Date(newConfig[i].filter_eq_value)});
							}
						} if(newConfig[i].filter_type=="numeric") {
							if(newConfig[i].filter_lt_comparison && newConfig[i].filter_lt_comparison=="lt") {
								gridView.filters.getFilter(newConfig[i].dataIndex).menu.fields.lt.setValue(newConfig[i].filter_lt_value.toString().replace(/&#039/gi,"'"));
							} if(newConfig[i].filter_gt_comparison && newConfig[i].filter_gt_comparison=="gt") {
								gridView.filters.getFilter(newConfig[i].dataIndex).menu.fields.gt.setValue(newConfig[i].filter_gt_value.toString().replace(/&#039/gi,"'"));
							} if(newConfig[i].filter_eq_comparison && newConfig[i].filter_eq_comparison=="eq") {
								gridView.filters.getFilter(newConfig[i].dataIndex).menu.fields.eq.setValue(newConfig[i].filter_eq_value.toString().replace(/&#039/gi,"'"));
							}
						} if(newConfig[i].filter_type=="string" || newConfig[i].filter_type=="list") {
							gridView.filters.getFilter(newConfig[i].dataIndex).setValue(newConfig[i].filter_value.toString().replace(/&#039/gi,"'"));
						} if(newConfig[i].filter_type=="boolean") {
							if(newConfig[i].filter_value == '1') {
								gridView.filters.getFilter(newConfig[i].dataIndex).setValue(true);
							}else {
								gridView.filters.getFilter(newConfig[i].dataIndex).setValue(false);
							}
						}
					}
				}
			}
			else {
				//Ext.Msg.alert("Error", "Fail to restore grid state");
			}
			Ext.getCmp(gdGridID).resumeEvents();
                        if(!gridChange){
                            Ext.getCmp(gdGridID).fireEvent('restoregridstate',Ext.getCmp(gdGridID), true);
                        }else {
                            Ext.getCmp(gdGridID).fireEvent('restoregridstate',Ext.getCmp(gdGridID), false);
                        }
//                        if(Ext.getCmp('btnResetGridView') !== undefined) {
//                            if(F.settings === undefined) {
//                                Ext.getCmp('btnResetGridView').disable();
//                            }else {
//                                Ext.getCmp('btnResetGridView').enable();
//                            }
//                        }
                        if(Ext.getCmp('btnResetFilter') !== undefined) {
                            if(gridView.filters.getFilterData().length == 0) {
                                Ext.getCmp('btnResetFilter').setIcon(HTTP_MEDIA+"/site-image/filter.png");
                                Ext.getCmp('btnResetFilter').setText('Filter: Off');
                                Ext.getCmp('btnResetFilter').disable();
                            }else {
                                Ext.getCmp('btnResetFilter').setIcon(HTTP_MEDIA+"/site-image/clear_filter.png");
                                Ext.getCmp('btnResetFilter').setText('Clear Filter');
                                Ext.getCmp('btnResetFilter').enable();
                            }
                        }
		},
		failure: function(D,E){
			//Ext.Msg.alert("Error", "Fail to save grid state");
		},scope: this
	})
}
function gridDeleteState(gridID, userID, gridView, dataStore) {
	smGridID = "sm_"+gridID;
	gdGridID = "grid_"+gridID;
        
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=1';
        window.history.pushState({path:newurl},'',newurl);
		
	Ext.Ajax.request({
		url: JS_GRIDSTATES + '/savestates',
		params: {
			//mode: "grid_state_settings",
			opt: "delete",
			smid: smGridID,
			gridid: gdGridID,
			userid: userID
		},
		method: "POST",
		success: function(D,E){
			var F = Ext.decode(D.responseText);
			if(F.success){
				window.location.href = window.location;
			}
			else {
				//Ext.Msg.alert("Error", "Fail to save grid state");
			}
		},
		failure: function(D,E){
			//Ext.Msg.alert("Error", "Fail to save grid state");
		},scope: this
	})
}