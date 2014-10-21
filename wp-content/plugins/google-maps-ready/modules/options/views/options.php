<?php
class optionsViewGmp extends viewGmp {
	public function getAdminPage() {
		if(!installerGmp::isUsed()){
			frameGmp::_()->getModule('promo_ready')->showWelcomePage();
			return;
		}
		$tabsData = array(
			'gmpAllMaps'		=> array(
				'title'   => 'All Maps', 
				'content' => frameGmp::_()->getModule('gmap')->getMapsTab()),
			'gmpMarkerList'		=> array(
				'title' => 'Markers',
				'content' => frameGmp::_()->getModule('marker')->getView()->showAllMarkers()),
			'gmpMarkerGroups'	=> array(
				'title'   => 'Marker Groups',
				'content' => $this->getMarkersGroupsTab()),
			'gmpPluginSettings'	=>array(
				'title' => 'Plugin Settings',
				'content' => $this->getPluginSettingsTab())						
		);
		$tabsData = dispatcherGmp::applyFilters('adminOptionsTabs', $tabsData);
		
		$defaultOpenTab = reqGmp::getVar('tab', 'get');

		frameGmp::_()->addScript('admin.settings', $this->getModule()->getModPath(). 'js/admin.settings.js');
		
		$this->assign('indoWindowSize', $this->getModel()->get('infowindow_size'));
		$this->assign('tabsData', $tabsData);
		$this->assign('defaultOpenTab', $defaultOpenTab);
		parent::display('optionsAdminPage');
	}
	
	public function getPluginSettingsTab() {
		$optModel = $this->getModel();
		$this->assign('indoWindowSize', $this->getModel()->get('infowindow_size'));
		$this->assign('additionalOptions', dispatcherGmp::applyFilters('additionalOptions', array()));
		$this->assign('additionalGlobalSettings', dispatcherGmp::applyFilters('additionalGlobalSettings', array()));
		$this->assign('optModel', $optModel);
		return parent::getContent('settingsTab');
	}
	public function getMarkersGroupsTab(){
		return  frameGmp::_()->getModule('marker_groups')->getModel()->showAllGroups();
	}
	public function displayDeactivatePage(){
		$this->assign('GET', reqGmp::get('get'));
		$this->assign('POST',reqGmp::get('post'));
		$this->assign('REQUEST_METHOD', strtoupper(reqGmp::getVar('REQUEST_METHOD', 'server')));
		$this->assign('REQUEST_URI', basename(reqGmp::getVar('REQUEST_URI', 'server')));
		parent::display('deactivatePage');
	}
}
