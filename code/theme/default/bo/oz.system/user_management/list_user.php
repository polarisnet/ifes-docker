<div id="ext-grid"></div>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/GridFilters.css" />
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/RangeMenu.css" />
<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/oz.system/user_management/list_user.js"></script>
<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/list-shortcut.js"></script>
<script type="text/javascript">
	Ext.Loader.setPath('Ext.ux', '<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux');
	Ext.require([
		'Ext.ux.ProgressBarPager',
		'Ext.ux.grid.FiltersFeature'
	]);
	Ext.namespace('User');
	User.app = function(){
		return{
			init: function(){
				var mask = new Ext.LoadMask(document.getElementById('ext-grid'),{ msg: 'Loading...'});
				var listPanel = new ListPanel({
					'start': '<?php echo $start; ?>',
					'itemsPerPage': '<?php echo $itemsPerPage; ?>',
					'listFields': [<?php echo implode(",", $fields); ?>],
					'allowNew': '<?php echo checkAccess('oz.system.user_management.users.new'); ?>',
					'allowEdit': '<?php echo checkAccess('oz.system.user_management.users.edit'); ?>',
					'allowResetPassword': '<?php echo checkAccess('oz.system.user_management.users.resetpassword'); ?>',
					'allowDelete': '<?php echo checkAccess('oz.system.user_management.users.delete'); ?>',
					'allowView': '<?php echo checkAccess('oz.system.user_management.users.view'); ?>',
					'newLink': '<?php echo getModuleURL('oz.system.user_management.users.new'); ?>',
					'viewLink': '<?php echo getModuleURL('oz.system.user_management.users.view'); ?>',
					'editLink': '<?php echo getModuleURL('oz.system.user_management.users.edit'); ?>',
					'resetpasswordLink': '<?php echo getModuleURL('oz.system.user_management.users.resetpassword'); ?>'
				});
				Ext.create('Ext.container.Container',{
					id: 'ext-container',
					renderTo: 'ext-grid',
					items:[listPanel]
				});
			}
		}
	}();
	Ext.onReady(User.app.init, User.app);
</script>