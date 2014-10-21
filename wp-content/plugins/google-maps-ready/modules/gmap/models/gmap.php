<?php
class gmapModelGmp extends modelGmp {
	public function getAllMaps($d = array(), $withMarkers = false, $markersWithGroups = false){
		if(isset($d['limitFrom']) && isset($d['limitTo']))
			frameGmp::_()->getTable('maps')->limitFrom($d['limitFrom'])->limitTo($d['limitTo']);
		if(isset($d['orderBy']) && !empty($d['orderBy'])) {
			frameGmp::_()->getTable('maps')->orderBy( $d['orderBy'] );
		}
		$maps = frameGmp::_()->getTable('maps')->get('*', $d);
		$markerModule = frameGmp::_()->getModule('marker');
		foreach($maps as &$map) {
			$map['html_options'] = utilsGmp::unserialize($map['html_options']);
			$map['params'] = utilsGmp::unserialize($map['params']);
			if($withMarkers) {
				$map['markers'] = $markerModule->getModel()->getMapMarkers($map['id'], $markersWithGroups);
			}
		}
		return $maps;
	}
	public function getParamsList() {
		$mapOptKeys = dispatcherGmp::applyFilters('mapParamsKeys', 
				array('enable_zoom', 'enable_mouse_zoom', 'zoom', 'type', 'language', 'map_display_mode', 'map_center', 'infowindow_height', 'infowindow_width', 'width_units', 'infowindow_on_mouseover'));
		return $mapOptKeys;
	}
	public function getHtmlOptionsList() {
		return array('width', 'height', 'align', 'margin', 'border_color', 'border_width');
	}
	public function prepareParams($params){
		$htmlKeys = $this->getHtmlOptionsList();
		$htmlOpts = array();
		foreach($htmlKeys as $k){
			$htmlOpts[$k] = isset($params[$k]) ? $params[$k] : null;
		}
		$mapOptKeys = $this->getParamsList();
		$mapOpts = array();
		foreach($mapOptKeys as $k){
			$mapOpts[$k] = isset($params[$k]) ? $params[$k] : null;
		}
		$insert = array(
			'title'			=> trim($params['title']),
			'description'	=> $params['description'],
			'html_options'	=> utilsGmp::serialize($htmlOpts),
			'params'		=> utilsGmp::serialize($mapOpts),
			'create_date'	=> date('Y-m-d H:i:s')
		);
		return $insert;
	}
	private function _validateSaveMap($map) {
		if(empty($map['title'])) {
			$this->pushError(langGmp::_('Please enter Map Name'), 'map_opts[title]');
		}
		return !$this->haveErrors();
	}
	public function updateMap($params){
		$data = $this->prepareParams($params);
		if($this->_validateSaveMap($data)) {
			dispatcherGmp::doAction('beforeMapUpdate', $params['id'], $data);
			$res = frameGmp::_()->getTable('maps')->update($data, array('id' => (int)$params['id']));
			if($res) {
				dispatcherGmp::doAction('afterMapUpdate', $params['id'], $data);
			}
			return $res;
		}
		return false;
	}
	public function saveNewMap($params){
		if(!empty($params)) {
			$insertData = $this->prepareParams($params);
			if($this->_validateSaveMap($insertData)) {
				$newMapId = frameGmp::_()->getTable('maps')->insert($insertData);
				if($newMapId){
					dispatcherGmp::doAction('afterMapInsert', $newMapId, $params);
					return $newMapId;
				} else {
					$this->pushError(frameGmp::_()->getTable('maps')->getErrors());
				}
			}
		} else
			$this->pushError(langGmp::_('Empty Params'));
		return false;
	}
	public function remove($mapId){
		frameGmp::_()->getModule('marker')->getModel()->removeMarkersFromMap($mapId);
		return frameGmp::_()->getTable("maps")->delete($mapId);
	}
	public function getMapByTitle($title) {
		$map = frameGmp::_()->getTable('maps')->get('*', array('title' => $title), '', 'row');
		if(!empty($map)) {
			$map['html_options'] = utilsGmp::unserialize($map['html_options']);				
			$map['params']= utilsGmp::unserialize($map['params']);				
			return $map;
		}
		return false;
	}
	public function getMapById($id = false, $withMarkers = true, $withGroups = false){
		if(!$id){
			return false;
		}
		$map = frameGmp::_()->getTable('maps')->get('*', array('id' => (int)$id));
		if(!empty($map)){
			if($withMarkers){
			   $map[0]['markers'] = frameGmp::_()->getModule('marker')->getModel()->getMapMarkers($map[0]['id'], $withGroups);				
			}
			$map[0]['html_options'] = utilsGmp::unserialize($map[0]['html_options']);				
			$map[0]['params']= utilsGmp::unserialize($map[0]['params']);				
			return $map[0];
		}
		return false;
	}
	public function constructMapOptions(){
		$params = array();
		$params['zoom']=array();
		for($i = 0; $i < 22; $i++){
			$params['zoom'][$i] = $i;
		}
		$params['type']= array(
			'ROADMAP'=>'Map',
			'TERRAIN'=>'Relief',
			'HYBRID'=>'Hybrid',
			'SATELLITE'=>'Satellite',
		);
		$params['language'] = array(
			'ar'=>'ARABIC',
			'bg'=>'BULGARIAN',
			'cs'=>'CZECH',
			'da'=>'DANISH',
			'de'=>'GERMAN',
			'el'=>'GREEK',
			'en'=>'ENGLISH',
			'en-AU'=>'ENGLISH (AUSTRALIAN)',
			'en-GB'=>'ENGLISH (GREAT BRITAIN)',
			'es'=>'SPANISH',
			'fa'=>'FARSI',
			'fil'=>'FILIPINO',
			'fr'=>'FRENCH',
			'hi'=>'HINDI',
			'hu'=>'HUNGARIAN',
			'id'=>'INDONESIAN',
			'it'=>'ITALIAN',
			'ja'=>'JAPANESE',
			'kn'=>'KANNADA',
			'ko'=>'KOREAN',
			'lv'=>'LATVIAN',
			'nl'=>'DUTCH',
			'no'=>'NORWEGIAN',
			'pt'=>'PORTUGUESE',
			'pt-BR'=>'PORTUGUESE (BRAZIL)',
			'pt-PT'=>'PORTUGUESE (PORTUGAL)',
			'rm'=>'ROMANSCH',
			'ru'=>'RUSSIAN',
			'sv'=>'SWEDISH',
			'zh-CN'=>'CHINESE (SIMPLIFIED)',
			'zh-TW'=>'CHINESE (TRADITIONAL)',		  
		);
		$params['align'] = array('top' => 'top', 'right' => 'right', 'bottom' => 'bottom', 'left' => 'left');
		$params['display_mode'] = array('map' => 'Display Map', 'popup' => 'Display Map Icon');
		return $params;
	}
	public function getCount($d = array()) {
		return frameGmp::_()->getTable('maps')->get('COUNT(*)', $d, '', 'one');
	}
}
