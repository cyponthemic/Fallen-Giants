<?php
class markerModelGmp extends modelGmp {
    public static $tableObj;
    function __construct() {
        if(empty(self::$tableObj)){
            self::$tableObj = frameGmp::_()->getTable('marker');
        }
    }
	public function save($marker = array(), &$update = false) {
		$id = isset($marker['id']) ? (int) $marker['id'] : 0;
		$marker['title'] = isset($marker['title']) ? trim($marker['title']) : '';
		$marker['coord_x'] = isset($marker['coord_x']) ? (float)$marker['coord_x'] : 0;
		$marker['coord_y'] = isset($marker['coord_y']) ? (float)$marker['coord_y'] : 0;
		$update = (bool) $id;
		if(!empty($marker['title'])) {
			if(!isset($marker['marker_group_id'])) {
				$marker['marker_group_id'] = 1;
			}
			if(!isset($marker['icon']) || !frameGmp::_()->getModule('icons')->getModel()->iconExists($marker['icon'])) {
				$marker['icon'] = 1;
			}
			if(!$update)
				$marker['create_date'] = date('Y-m-d H:i:s');
			$marker['params'] = utilsGmp::serialize($marker['params']);
			if($update) {
				dispatcherGmp::doAction('beforeMarkerUpdate', $id, $marker);
				$dbRes = frameGmp::_()->getTable('marker')->update($marker, array('id' => $id));
				dispatcherGmp::doAction('afterMarkerUpdate', $id, $marker);
			} else {
				dispatcherGmp::doAction('beforeMarkerInsert', $marker);
				$dbRes = frameGmp::_()->getTable('marker')->insert($marker);
				dispatcherGmp::doAction('afterMarkerInsert', $dbRes, $marker);
			}
			if($dbRes) {
				if(!$update)
					$id = $dbRes;
				return $id;
			} else {
				$this->pushError(frameGmp::_()->getTable('marker')->getErrors());
			}
		} else {
			$this->pushError(langGmp::_('Please enter marker name'), 'marker_opts[title]');
		}
		return false;
	}
	public function getById($id) {
		return $this->_afterGet(frameGmp::_()->getTable('marker')->get('*', array('id' => $id), '', 'row'));
	}
	public function getMarkerByTitle($title) {
		return $this->_afterGet(frameGmp::_()->getTable('marker')->get('*', array('title' => $title), '', 'row'));
	}
	public function _afterGet($marker, $widthMapData = false) {
		if(!empty($marker)) {
			$marker['icon_data'] = frameGmp::_()->getModule('icons')->getModel()->getIconFromId($marker['icon']);
			$marker['params'] = utilsGmp::unserialize($marker['params']);
			$marker['position'] = array(
				'coord_x' => $marker['coord_x'],
				'coord_y' => $marker['coord_y'],
			);
			if(isset($marker['params']['marker_title_link']) 
				&& !empty($marker['params']['marker_title_link']) 
				&& strpos($marker['params']['marker_title_link'], 'http') !== 0
			) {
				$marker['params']['marker_title_link'] = 'http://'. $marker['params']['marker_title_link'];
			}
			if(!isset($marker['params']['title_is_link']))
				$marker['params']['title_is_link'] = false;
			// Go to absolute path as "../wp-content/" will not work on frontend
			$marker['description'] = str_replace('../wp-content/', GMP_SITE_URL. 'wp-content/', $marker['description']);
			if(uriGmp::isHttps()) {
				$marker['description'] = uriGmp::makeHttps($marker['description']);
			}
			if($widthMapData && !empty($marker['map_id']))
				$marker['map'] = frameGmp::_()->getModule('gmap')->getModel()->getMapById($marker['map_id'], false);
		}
		return $marker;
	}

	/*public function saveMarkers($markerArr, $mapId) {
        foreach($markerArr as $marker) {
			 $marker['map_id'] = $mapId;
             $this->saveMarker($marker);
        }
        return !$this->haveErrors();
    }
	public function saveMarker($marker) {
		if(!isset($marker['marker_group_id'])) {
			$marker['marker_group_id'] = 1;
		}
		if(!isset($marker['icon'])) {
			$marker['icon'] = 1;
		} elseif(!frameGmp::_()->getModule('icons')->getModel()->iconExists($marker['icon'])) {
			// Why here is echo??? I don't know.........
			//echo $marker['icon'].".."; 
			$marker['icon'] = 1;
		}
		unset($marker['id']);
		$marker['create_date'] = date('Y-m-d H:i:s');
		$marker['params'] = utilsGmp::serialize(array('titleLink' => $marker['titleLink']));
		unset($marker['titleLink']);
		if(!frameGmp::_()->getTable('marker')->insert($marker)) {
			$this->pushError(frameGmp::_()->getTable('marker')->getErrors());
		}
	}*/
    /*public function updateMapMarkers($params, $mapId = null) {
        foreach($params as $id => $data) {
			//self::$tableObj->delete($id);
			$newId = $id;
            $exists = self::$tableObj->exists($id);
            unset($data['id']);
            if($mapId) {
				$data['map_id'] = $mapId;
            }
            $data['marker_group_id'] = $data['groupId'];
			$data['params'] = utilsGmp::serialize(array('titleLink' => $data['titleLink']));
			unset($data['titleLink']);
            if($exists) {
                self::$tableObj->update($data, array('id' => $id));
            } else {
				$params[$id]['tmp_id'] = $id;
                $newId = self::$tableObj->insert($data);
            }
			$params[$id]['id'] = $newId;
			$params[$id]['params'] = utilsGmp::unserialize($data['params']);
        }
        return $params;
    }*/
    /*public function updateMarker($marker){
        $insert = array(
			'marker_group_id'   =>  $marker['goup_id'],
			'title'             =>  $marker['title'],
			'address'           =>  $marker['address'],
			'description'       =>  $marker['desc'],
			'coord_x'           =>  $marker['position']['coord_x'],
			'coord_y'           =>  $marker['position']['coord_y'],
			'animation'         =>  $marker['animation'],
			'icon'              =>  $marker['icon']['id'],
			'params'			=>  utilsGmp::serialize(array('titleLink' => $marker['titleLink']))
		);
		return self::$tableObj->update($insert," `id`='".$marker['id']."'");
    }*/
    public function getMapMarkers($mapId, $withGroup = false) {
        $markers = frameGmp::_()->getTable('marker')->get('*',array('map_id'=>$mapId));
        $iconsModel =  frameGmp::_()->getModule('icons')->getModel();
		$groupModel = frameGmp::_()->getModule('marker_groups')->getModel();
        foreach($markers as $i => $m) {
			$markers[$i] = $this->_afterGet($markers[$i]);
			if($withGroup) {
				$markers[$i]['groupObj'] = $groupModel->getGroupById($markers[$i]['marker_group_id']);
			}
        }
        return $markers;
    }
    public function constructMarkerOptions(){
        $params = array();
        $params['groups'] =  frameGmp::_()->getModule('marker_groups')->getModel()->getMarkerGroups();
        $params['icons']  =  frameGmp::_()->getModule('icons')->getModel()->getIcons();
        return  $params;
    }
    public function removeMarker($markerId){
		dispatcherGmp::doAction('beforeMarkerRemove', $markerId);
		return frameGmp::_()->getTable('marker')->delete(array('id' => $markerId));
    }
	public function removeList($ids) {
		$ids = array_map('intval', $ids);
		return frameGmp::_()->getTable('marker')->delete(array('additionalCondition' => 'id IN ('. implode(',', $ids). ')'));
	}
    public function findAddress($params){
        if(!isset($params['addressStr']) || strlen($params['addressStr']) < 3){
            $this->pushError(langGmp::_('Address is empty or not match'));
            return false;
        }
        $addr = $params['addressStr'];
        $getdata = http_build_query(
            array(
                'address' => $addr,
                'language' => 'en',
				'sensor'=>'false',
			)
		);
        $google_response = utilsGmp::jsonDecode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?'. $getdata));
        $res = array();
        foreach($google_response['results'] as $response) {
            $res[] = array(
				'position'  =>  $response['geometry']['location'],
				'address'   =>  $response['formatted_address'],
            );
        }
        return $res;
    }
    public function removeMarkersFromMap($mapId){
        return frameGmp::_()->getTable('marker')->delete("`map_id`='".$mapId."'");
    }
    public function getAllMarkers($d = array(), $widthMapData = false) {
		if(isset($d['limitFrom']) && isset($d['limitTo']))
			frameGmp::_()->getTable('marker')->limitFrom($d['limitFrom'])->limitTo($d['limitTo']);
		if(isset($d['orderBy']) && !empty($d['orderBy'])) {
			frameGmp::_()->getTable('marker')->orderBy( $d['orderBy'] );
		}
        $markerList = frameGmp::_()->getTable('marker')->get('*', $d);
        $iconsModel = frameGmp::_()->getModule('icons')->getModel();
        $markerGroupModel = frameGmp::_()->getModule('marker_groups')->getModule()->getModel();
        foreach($markerList as $i => &$m) {
			$markerList[$i] = $this->_afterGet($markerList[$i], $widthMapData);
            $m['marker_group'] = $markerGroupModel->getGroupById($m['marker_group_id']);
        } 
        return $markerList;
    }
	public function setMarkersToMap($addMarkerIds, $mapId) {
		if(!is_array($addMarkerIds))
			$addMarkerIds = array($addMarkerIds);
		$addMarkerIds = array_map('intval', $addMarkerIds);
		return frameGmp::_()->getTable('marker')->update(array('map_id' => (int)$mapId), array('additionalCondition' => 'id IN ('. implode(',', $addMarkerIds). ')'));
	}
	public function getCount($d = array()) {
		return frameGmp::_()->getTable('marker')->get('COUNT(*)', $d, '', 'one');
	}
}