<?php
class  gmapGmp extends moduleGmp {
	public function init() {
		if(frameGmp::_()->isAdminPlugPage()){
			frameGmp::_()->addScript('gmp', GMP_JS_PATH. 'gmp.js', array(), false, false);
			frameGmp::_()->addScript('mutal_opts', GMP_JS_PATH. 'mutal.js', array(), false, false);	
			frameGmp::_()->addStyle('map_std', $this->getModPath(). 'css/map.css');  
		}
		dispatcherGmp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
		dispatcherGmp::addAction('tplHeaderBegin',array($this, 'showFavico'));
		dispatcherGmp::addAction('tplBodyEnd',array($this, 'GoogleAnalitics'));

        add_action('wp_footer', array($this, 'addMapDataToJs'));
	}
	public function addOptionsTab($tabs) {
		if(frameGmp::_()->isAdminPlugPage()){
			frameGmp::_()->addScript('mapOptions', $this->getModPath(). 'js/admin.maps.options.js');
			frameGmp::_()->addScript('bootstrap', GMP_JS_PATH .'bootstrap.min.js');
			frameGmp::_()->addStyle('bootstrapCss', GMP_CSS_PATH .'bootstrap.min.css');			
		}
		return $tabs;
	}
    public function drawMapFromShortcode($params = null) {
		frameGmp::_()->addScript('commonGmp', GMP_JS_PATH. 'common.js', array('jquery'));
		frameGmp::_()->addScript('coreGmp', GMP_JS_PATH. 'core.js');
		frameGmp::_()->addScript('mutal_opts', GMP_JS_PATH. 'mutal.js');
        if(!isset($params['id'])) {
            return $this->getController()->getDefaultMap();
        }
        return $this->getController()->getView()->drawMap($params);
    }
    public function addMapDataToJs(){
        $this->getView()->addMapDataToJs();
    }
	public function getMapsTab() {
		return $this->getView()->getMapsTab();
	}
	public function generateShortcode($map) {
		$shortcodeParams = array();
		$shortcodeParams['id'] = $map['id'];
		// For PRO version
		$shortcodeParamsArr = array();
		foreach($shortcodeParams as $k => $v) {
			$shortcodeParamsArr[] = $k. "='". $v. "'";
		}
		return '[ready_google_map '. implode(' ', $shortcodeParamsArr). ']';
	}
	public function getControlsPositions() {
		return array(
			'TOP_CENTER' => langGmp::_('Top Center'),
			'TOP_LEFT' => langGmp::_('Top Left'),
			'TOP_RIGHT' => langGmp::_('Top Right'),
			'LEFT_TOP' => langGmp::_('Left Top'),
			'RIGHT_TOP' => langGmp::_('Right Top'),
			'LEFT_CENTER' => langGmp::_('Left Center'),
			'RIGHT_CENTER' => langGmp::_('Right Center'),
			'LEFT_BOTTOM' => langGmp::_('Left Bottom'),
			'RIGHT_BOTTOM' => langGmp::_('Right Bottom'),
			'BOTTOM_CENTER' => langGmp::_('Bottom Center'),
			'BOTTOM_LEFT' => langGmp::_('Bottom Left'),
			'BOTTOM_RIGHT' => langGmp::_('Bottom Right'),
		);
	}
}