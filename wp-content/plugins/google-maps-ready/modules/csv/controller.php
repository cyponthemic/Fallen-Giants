<?php
class csvControllerGmp extends controllerGmp {
	private function _connectCsvLib() {
		importClassGmp('filegeneratorGmp');
		importClassGmp('csvgeneratorGmp');
	}
	private function _getSitePath() {
		return $this->getModel()->getSitePath();
	}
	public function exportMaps() {
		$fileName = langGmp::_('Maps');
		$this->_connectCsvLib();
		$withMarkers = (int) reqGmp::getVar('withMarkers');
		$maps = frameGmp::_()->getModule('gmap')->getModel()->getAllMaps(array(), $withMarkers, $withMarkers);	// If there will be markers - there should be also be all groups data
		$mapOptKeys = frameGmp::_()->getModule('gmap')->getModel()->getParamsList();
		foreach($maps as $i => $map) {
			foreach($mapOptKeys as $key) {
				$maps[$i][$key] = isset($maps[$i]['params'][$key]) ? $maps[$i]['params'][$key] : '';
				switch($key) {
					case 'map_center':
						if(!empty($maps[$i][$key]) && is_array($maps[$i][$key]))
							$maps[$i][$key] = implode(', ', $maps[$i][$key]);
						break;
				}
			}
		}
		$htmlKeys = frameGmp::_()->getModule('gmap')->getModel()->getHtmlOptionsList();
		foreach($maps as $i => $map) {
			foreach($htmlKeys as $key) {
				$maps[$i][$key] = isset($maps[$i]['html_options'][$key]) ? $maps[$i]['html_options'][$key] : '';
			}
		}
		$mapHeaders = $this->getModule()->getMapHeadersList();
		if($withMarkers) {
			$markerHeaders = $this->getModule()->getMarkerHeadersList();
		}
		
		$c = $r = 0;
		$csvGenerator = toeCreateObjGmp('csvgeneratorGmp', array($fileName));
		foreach($mapHeaders as $k => $v) {
			$csvGenerator->addCell($r, $c, langGmp::_($v). ' ['. $k. ']');
			$c++;
		}
		$mapHeaderCount = $c;
		if($withMarkers) {
			foreach($markerHeaders as $k => $v) {
				$csvGenerator->addCell($r, $c, langGmp::_('Marker'). ' - '. langGmp::_($v). ' ['. $k. ']');
				$c++;
			}
		}
		$c = 0;
		$r = 1;
		foreach($maps as $map) {
			$c = 0;
			foreach($mapHeaders as $k => $v) {
				$mapValue = $this->_prepareValueToExport($map[$k]);
				$csvGenerator->addCell($r, $c, $mapValue);
				$c++;
			}
			$r++;
			if($withMarkers && !empty($map['markers'])) {
				foreach($map['markers'] as $marker) {
					for($c = 0; $c < $mapHeaderCount; $c++) {
						$csvGenerator->addCell($r, $c, '');
					}
					foreach($markerHeaders as $k => $v) {
						$markerValue = $this->_prepareValueToExport( $this->_getMarkerValue($marker, $k) );
						$csvGenerator->addCell($r, $c, htmlspecialchars($markerValue));
						$c++;
					}
					$r++;
				}
			}
		}
		$csvGenerator->generate();
		frameGmp::_()->getModule('promo_ready')->getModel()->saveUsageStat('csv.export.maps');
		exit();
	}
	private function _prepareValueToExport($val) {
		$sitePath = $this->_getSitePath();
		return str_replace($sitePath, '[GMP_SITE_PATH]', $val);
	}
	private function _getMarkerValue($marker, $key) {
		$value = '';
		switch($key) {
			case 'icon_path': 
				$value = $marker['icon_data']['path']; break;
			case 'icon_title':
				$value = $marker['icon_data']['title']; break;
			case 'marker_group_title':
				$value = $marker['groupObj']['title']; break;
			case 'marker_group_description':
				$value = $marker['groupObj']['description']; break;
			default:
				$value = $marker[$key]; break;
		}
		return $value;
	}
	public function exportMarkers() {
		$fileName = langGmp::_('Markers');
		$this->_connectCsvLib();
		$csvGenerator = toeCreateObjGmp('csvgeneratorGmp', array($fileName));
		$markers = frameGmp::_()->getModule('marker')->getModel()->getAllMarkers();
		$markerHeaders = $this->getModule()->getMarkerHeadersList();
		$c = $r = 0;
		
		foreach($markerHeaders as $k => $v) {
			$csvGenerator->addCell($r, $c, langGmp::_($v). ' ['. $k. ']');
			$c++;
		}
		$c = 0;
		$r = 1;
		foreach($markers as $marker) {
			$c = 0;
			foreach($markerHeaders as $k => $v) {
				$csvGenerator->addCell($r, $c, htmlspecialchars($marker[$k]));
				$c++;
			}
			$r++;
		}
		$csvGenerator->generate();
		frameGmp::_()->getModule('promo_ready')->getModel()->saveUsageStat('csv.export.markers');
		exit();
	}
	public function import() {
		@ini_set('auto_detect_line_endings', true);
		$res = new responseGmp();
		$this->_connectCsvLib();
		$csvGenerator = toeCreateObjGmp('csvgeneratorGmp', array($fileName));

        $file = reqGmp::getVar('csv_import_file', 'file');
        if(empty($file) || empty($file['size']))
            $res->pushError (langGmp::_('Missing File'));
        if(!empty($file['error']))
            $res->pushError (langGmp::_(array('File uploaded with error code', $file['error'])));
        if(!$res->error()) {
            $fileArray = array();
			$handle = fopen($file['tmp_name'], 'r');
			$csvParams['delimiter'] = $csvGenerator->getDelimiter();
			$csvParams['enclosure'] = $csvGenerator->getEnclosure();
			$csvParams['escape'] = $csvGenerator->getEscape();
			//if(version_compare( phpversion(), '5.3.0' ) == -1) //for PHP lower than 5.3.0 third parameter - escape - is not implemented
				while($row = @fgetcsv( $handle, 0, $csvParams['delimiter'], '"' )) $fileArray[] = $row;
			/*else
				while($row = @fgetcsv( $handle, 0, $csvParams['delimiter'], $csvParams['enclosure'], $csvParams['escape'] )) $fileArray[] = $row;*/
			/*var_dump($fileArray);
			exit();*/
			if(!empty($fileArray)) {
				if(count($fileArray) > 1) {
					$overwriteSameNames = (int) reqGmp::getVar('overwrite_same_names');
					$importRes = $this->getModel()->import($fileArray, $overwriteSameNames);
					if($importRes) {
						if($importRes['map']['added'])
							$res->addMessage (langGmp::_(array('Added', $importRes['map']['added'], 'maps')));
						if($importRes['map']['updated'])
							$res->addMessage (langGmp::_(array('Updated', $importRes['map']['updated'], 'maps')));
						if($importRes['marker']['added'])
							$res->addMessage (langGmp::_(array('Added', $importRes['marker']['added'], 'markers')));
						if($importRes['marker']['updated'])
							$res->addMessage (langGmp::_(array('Updated', $importRes['marker']['updated'], 'markers')));
					} else
						$res->pushError ($this->getModel()->getErrors());
				} else
					$res->pushError (langGmp::_('File should contain more then 1 row, at least 1 row should be for headers'));
			} else
				$res->pushError (langGmp::_('Empty data in file'));
		}
		frameGmp::_()->getModule('promo_ready')->getModel()->saveUsageStat('csv.import');
		$res->ajaxExec();
	}
	private function _toYesNo($val) {
		return empty($val) ? 'No' : 'Yes';
	}
	private function _fromYesNo($val) {
		return $val === 'No' ? 0 : 1;
	}
	
	/**
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			GMP_USERLEVELS => array(
				GMP_ADMIN => array('exportMaps', 'exportMaps', 'import')
			),
		);
	}
} 