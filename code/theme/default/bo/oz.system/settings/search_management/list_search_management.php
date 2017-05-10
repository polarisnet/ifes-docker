<div id="ext-grid"></div>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/GridFilters.css" />
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux/grid/css/RangeMenu.css" />
<script type="text/javascript" src="<?php echo HTTP_ACTIVE_THEME; ?>/oz.system/settings/search_management/list_search_management.js"></script>
<script type="text/javascript" src="<?php echo HTTP_PLUGIN; ?>/js/list-shortcut.js"></script>
<script type="text/javascript">
	Ext.Loader.setPath('Ext.ux', '<?php echo HTTP_CDN_PLUGIN; ?>/extjs4.2.1/examples/ux');
	Ext.require([
		'Ext.ux.ProgressBarPager',
		'Ext.ux.grid.FiltersFeature'
	]);
	Ext.namespace('SearchManagement');
	SearchManagement.app = function(){
		return{
			init: function(){
				var mask = new Ext.LoadMask(document.getElementById('ext-grid'),{ msg: 'Loading...'});
				var listPanel = new ListPanel({
					'start': '<?php echo $start; ?>',
					'itemsPerPage': '<?php echo $itemsPerPage; ?>',
					'listFields': [<?php echo implode(",", $fields); ?>],
					'allowNew': '<?php echo checkAccess('oz.system.settings.search_management.new'); ?>',
					'allowEdit': '<?php echo checkAccess('oz.system.settings.search_management.edit'); ?>',
					'allowDelete': '<?php echo checkAccess('oz.system.settings.search_management.delete'); ?>',
					'allowView': '<?php echo checkAccess('oz.system.settings.search_management.view'); ?>',
					'newLink': '<?php echo getModuleURL('oz.system.settings.search_management.new'); ?>',
					'viewLink': '<?php echo getModuleURL('oz.system.settings.search_management.view'); ?>',
					'editLink': '<?php echo getModuleURL('oz.system.settings.search_management.edit'); ?>'
				});
				Ext.create('Ext.container.Container',{
					id: 'ext-container',
					renderTo: 'ext-grid',
					items:[listPanel]
				});
			}
		}
	}();
	Ext.onReady(SearchManagement.app.init, SearchManagement.app);
</script>