<?php
class gmapControllerGmp extends controllerGmp {
	public function getAllMaps($withMarkers = false){
	   $maps = $this->getModel()->getAllMaps($withMarkers);
	   return $maps;
	}
	public function save() {
		$saveRes = false;
		$data = reqGmp::get('post');
		$res = new responseGmp();
		$mapId = 0;
		if(!isset($data['map_opts'])) {
			$res->pushError(langGmp::_('Map data not found'));
			return $res->ajaxExec();
		}
		if(isset($data['map_opts']['id']) && !empty($data['map_opts']['id'])) {
			$saveRes = $this->getModel()->updateMap($data['map_opts']);
			$mapId = $data['map_opts']['id'];
		} else {
			$saveRes = $this->getModel()->saveNewMap($data['map_opts']);
			$mapId = $saveRes;
		}
		if($saveRes) {
			$addMarkerIds = reqGmp::getVar('add_marker_ids');
			if($addMarkerIds && !empty($addMarkerIds)) {
				frameGmp::_()->getModule('marker')->getModel()->setMarkersToMap($addMarkerIds, $mapId);
			}
			$res->addMessage(langGmp::_('Done'));
			$res->addData('map_id', $mapId);
			//$res->addData('updateMarkers', $updateMarkers);
		} else {
			$res->pushError( $this->getModel()->getErrors() );
		}
		frameGmp::_()->getModule('promo_ready')->getModel()->saveUsageStat('map.edit');
		return $res->ajaxExec();
	}
	public function removeMap(){
		$data=  reqGmp::get('post');
		$res = new responseGmp();
		if(!isset($data['map_id']) || empty($data['map_id'])){
			$res->pushError(langGmp::_("Nothing to remove"));
			return $res->ajaxExec();
		}

		if($this->getModel()->remove($data['map_id'])){
			$res->addMessage(langGmp::_("Done"));
		}else{
			$res->pushError($this->getModel()->getErrors());
		}
		frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("map.delete");            
		return $res->ajaxExec();
	}
	public function getListForTable() {
		$res = new responseGmp();
		$res->ignoreShellData();
		
		$count = $this->getModel()->getCount();
		$listReqData = array(
			'limitFrom' => reqGmp::getVar('iDisplayStart'),
			'limitTo' => reqGmp::getVar('iDisplayLength'),
		);
		$displayColumns = $this->getView()->getDisplayColumns();
		$displayColumnsKeys = array_keys($displayColumns);
		$iSortCol = reqGmp::getVar('iSortCol_0');
		if(!is_null($iSortCol) && is_numeric($iSortCol)) {
			$listReqData['orderBy'] = $displayColumns[ $displayColumnsKeys[ $iSortCol ] ]['db'];
			$iSortDir = reqGmp::getVar('sSortDir_0');
			if(!is_null($iSortDir)) {
				$listReqData['orderBy'] .= ' '. strtoupper($iSortDir);
			}
		}
		$search = reqGmp::getVar('sSearch');
		if(!is_null($search) && !empty($search)) {
			$dbSearch = dbGmp::escape($search);
			$listReqData['additionalCondition'] = 'title LIKE "%'. $dbSearch. '%" OR description LIKE "%'. $dbSearch. '%"';
		}
		$list = $this->getModel()->getAllMaps( $listReqData, true );

		$res->addData('aaData', $this->_convertDataForDatatable($list));
		$res->addData('iTotalRecords', $count);
		$res->addData('iTotalDisplayRecords', $count);
		$res->addData('sEcho', reqGmp::getVar('sEcho'));
		$res->addMessage(__('Done'));
		return $res->ajaxExec();
	}
	private function _convertDataForDatatable($list, $single = false) {
		$returnList = array();
		if($single) {
			$list = array($list);
		}
		foreach($list as $i => $map) {
			$returnList[ $i ] = $map;
			$returnList[ $i ]['list_html_options'] = $this->getView()->getListHtmlOptions( $map );
			$returnList[ $i ]['list_markers'] = $this->getView()->getListMarkers( $map );
			$returnList[ $i ]['operations'] = $this->getView()->getListOperations( $map );
		}
		if($single) {
			return $returnList[0];
		}
		return $returnList;
	}
	/**
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			GMP_USERLEVELS => array(
				GMP_ADMIN => array('getAllMaps', 'save', 'getListForTable')
			),
		);
	}
} 