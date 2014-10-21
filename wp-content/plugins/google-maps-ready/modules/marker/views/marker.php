<?php
class markerViewGmp extends viewGmp {
	private $_displayColumns = array();
    public function showMarkersTab($markerList, $isAjaxRequest = false){
        $this->assign('markerList', $markerList);
        if($isAjaxRequest){
			return parent::getContent('markerTable');            
        }
		$this->assign('displayColumns', $this->getDisplayColumns());
        $this->assign('tableContent', parent::getContent('markerTable'));
		return parent::getContent('markerList');             
    }
	public function getMarkerForm($params){
		$marker_opts = frameGmp::_()->getModule('marker')->getModel()->constructMarkerOptions(); 
		$this->assign('marker_opts', $marker_opts);
		$this->assign('params', $params);
		$this->assign('animOpts', $this->getModule()->getAnimationList());
		return parent::getContent('markerForm');
	}
	public function showAllMarkers(){
        $markerList = $this->getModel()->getAllMarkers();
        return $this->showMarkersTab($markerList);
    }
	public function getDisplayColumns() {
		if(empty($this->_displayColumns)) {
			$this->_displayColumns = array(
				'marker_check'		=> array('label' => htmlGmp::checkbox('check_all_markers'), 'db' => 'id', 'width' => '1'),
				'id'				=> array('label' => __('ID'), 'db' => 'id', 'width' => '1'),
				'list_icon'			=> array('label' => __('Icon'), 'db' => 'icon', 'width' => '1'),
				'list_title'		=> array('label' => __('Title'), 'db' => 'title', 'width' => '1'),
				'description'		=> array('label' => __('Description'), 'db' => 'description', 'width' => '1'),
				'group_title'		=> array('label' => __('Group'), 'db' => 'group_title', 'width' => '10'),
				'create_date'		=> array('label' => __('Creation Date'), 'db' => 'create_date', 'width' => '10'),
				'list_address'		=> array('label' => __('Address'), 'db' => 'Address', 'width' => '500'),
				'uses_on_map'		=> array('label' => __('Uses On Map'), 'db' => 'uses_on_map', 'width' => '10'),
				'operations'		=> array('label' => __('Operations'), 'db' => 'operations', 'width' => '10'),
			);
		}
		return $this->_displayColumns;
	}
	public function getListIcon($marker) {
		$this->assign('marker', $marker);
		return parent::getContent('markerListIcon');
	}
	public function getListTitle($marker) {
		$this->assign('marker', $marker);
		return parent::getContent('markerListTitle');
	}
	public function getListAddress($marker) {
		$this->assign('marker', $marker);
		return parent::getContent('markerListAddress');
	}
	public function getListUsesOnMap($marker) {
		$this->assign('marker', $marker);
		return parent::getContent('markerListUsesOnMap');
	}
	public function getListOperations($marker) {
		$this->assign('marker', $marker);
		return parent::getContent('markerListOperations');
	}
}