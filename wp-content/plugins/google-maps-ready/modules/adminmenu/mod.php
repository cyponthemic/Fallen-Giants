<?php
class adminmenuGmp extends moduleGmp {
	protected $_mainSlug = 'ready-google-maps';
	
    public function init() {
        parent::init();
        //$this->getController()->getView('adminmenu')->init();
		$plugName = plugin_basename(GMP_DIR. GMP_MAIN_FILE);
		add_filter('plugin_action_links_'. $plugName, array($this, 'addSettingsLinkForPlug') );
		add_action('admin_menu', array($this, 'initMenu'), 9);
    }
	public function addSettingsLinkForPlug($links) {
		array_unshift($links, '<a href="'. uriGmp::_(array('baseUrl' => admin_url('admin.php'), 'page' => $this->getMainSlug())). '">'. langGmp::_('Settings'). '</a>');
		return $links;
	}
	public function initMenu() {
		$accessCap = 'manage_options';
		$accessCap = dispatcherGmp::applyFilters('adminMenuAccessCap', $accessCap);
		$options = array(
            'add_new_map' => array('title' => langGmp::_('Add New Map'),	'capability' => $accessCap, 'menu_slug' => $this->_mainSlug. '&tab=gmpAddNewMap',	'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            'all_maps' => array('title' => langGmp::_('All Maps'), 'capability' => $accessCap, 'menu_slug' => $this->_mainSlug. '&tab=gmpAllMaps', 'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            'markers' => array('title' => langGmp::_('Markers'),		'capability' => $accessCap, 'menu_slug' => $this->_mainSlug. '&tab=gmpMarkerList',	'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            'marker_groups' => array('title' => langGmp::_('Marker Groups'),		'capability' => $accessCap, 'menu_slug' => $this->_mainSlug. '&tab=gmpMarkerGroups',	'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            'plugin_settings' => array('title' => langGmp::_('Plugin Settings'),		'capability' => $accessCap, 'menu_slug' => $this->_mainSlug. '&tab=gmpPluginSettings',	'function' => array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
        );
		$options = dispatcherGmp::applyFilters('adminMenuOptions', $options);
		$mainSlug = dispatcherGmp::applyFilters('adminMenuMainSlug', $this->_mainSlug);	
		add_menu_page(langGmp::_('Ready! Google Maps'), langGmp::_('Ready! Google Maps'), $accessCap, $this->_mainSlug, array(frameGmp::_()->getModule('options')->getView(), 'getAdminPage'), 'dashicons-admin-site');
		foreach($options as $opt) {
			add_submenu_page($mainSlug, langGmp::_($opt['title']), langGmp::_($opt['title']), $opt['capability'], $opt['menu_slug'], $opt['function']);
		}
	}
}

