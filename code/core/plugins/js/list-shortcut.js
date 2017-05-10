$(document).keydown(function(e){
	if((e.ctrlKey || e.altKey) && e.keyCode == 78){
		if(!Ext.getCmp('panel_btn_new').disabled){
			var trigger = Ext.getCmp('panel_btn_new');
			trigger.handler.call(trigger.scope);
			e.preventDefault(e);
			return;
		}
	}
	if(e.ctrlKey && e.keyCode == 69){
		if(e.shiftKey && !Ext.getCmp('panel_btn_view').disabled){
			var trigger = Ext.getCmp('panel_btn_view');
			trigger.handler.call(trigger.scope);
			e.preventDefault(e);
			return;
		}else if(!Ext.getCmp('panel_btn_view').disabled){
			var trigger = Ext.getCmp('panel_btn_edit');
			trigger.handler.call(trigger.scope);
			e.preventDefault(e);
			return;
		}
	}
	if(e.keyCode == 46){
		if(!Ext.getCmp('panel_btn_delete').disabled){
			var trigger = Ext.getCmp('panel_btn_delete');
			trigger.handler.call(trigger.scope);
			e.preventDefault(e);
			return;
		}
	}
});