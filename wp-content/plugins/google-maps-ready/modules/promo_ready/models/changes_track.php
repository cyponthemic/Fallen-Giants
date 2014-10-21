<?php
class changes_trackModelGmp extends modelGmp {
	private $_oldMapsData = array();
	private $_oldMarkersData = false;
	public function saveOldMapUpdateData($id) {
		if($id && !isset($this->_oldMapsData[ $id ])) {
			$this->_oldMapsData[ $id ] = frameGmp::_()->getModule('gmap')->getModel()->getMapById($id, false);
		}
	}
	public function saveOldMarkerUpdateData($id) {
		if($id && !isset($this->_oldMarkersData[ $id ])) {
			$this->_oldMarkersData[ $id ] = frameGmp::_()->getModule('marker')->getModel()->getById($id);
		}
	}
	public function trackMapChanges($id) {
		if($id && isset($this->_oldMapsData[ $id ])) {
			$newMapData = frameGmp::_()->getModule('gmap')->getModel()->getMapById($id, false);
			if($newMapData) {
				$changedKeys = $this->getChangedKeys($this->_oldMapsData[ $id ], $newMapData);
				if(!empty($changedKeys)) {
					
					foreach($changedKeys as $k => $v) {
						
						$this->_writeAuditChange(array(
							'key' => $k,
							'val' => $v,
							'for' => 'map.edit',
						));
					}
				}
			}
		}
	}
	public function trackMarkerChanges($id) {
		if($id && isset($this->_oldMarkersData[ $id ])) {
			$newMarkerData = frameGmp::_()->getModule('marker')->getModel()->getById($id);
			if($newMarkerData) {
				$changedKeys = $this->getChangedKeys($this->_oldMarkersData[ $id ], $newMarkerData);
				if(!empty($changedKeys)) {
					foreach($changedKeys as $k => $v) {
						$this->_writeAuditChange(array(
							'key' => $k,
							'val' => $v,
							'for' => 'marker.edit',
						));
					}
				}
			}
		}
	}
	private function _writeAuditChange($d) {
		if(is_array($d['val']) && !isset($d['val'][0])) {	// Is associative array, not numeric
			foreach($d['val'] as $k => $v) {
				$writeAuditData = $d;
				$writeAuditData['key'] = $k;
				$writeAuditData['val'] = $v;
				$this->_writeAuditChange($writeAuditData);
			}
		} else {
			$k = $d['key'];
			$ignoreChangeKeys = array('create_date', 'title', 'description');
			if(in_array($k, $ignoreChangeKeys)) return;
			$fullSaveKey = $d['for']. '.'. $k;
			$this->getModule()->saveUsageStat($fullSaveKey);
		}
	}
	public function getChangedKeys($oldData, $newData) {
		$res = array();
		$ignoreKeys = array('icon_data');
		foreach($oldData as $k => $v) {
			if($oldData[ $k ] == $newData[ $k ] || in_array($k, $ignoreKeys)) continue;
			if(is_array($v)) {
				$res[ $k ] = $this->getChangedKeys($oldData[ $k ], $newData[ $k ]);
			} else {
				$res[ $k ] = $k;
			}
		}
		return $res;
	}
}