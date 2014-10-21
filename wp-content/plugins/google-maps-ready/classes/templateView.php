<?php
class templateViewGmp extends viewGmp {
	protected $_styles = array();
	protected $_scripts = array();
	/**
	 * Provide or not html code of subscribe for to template. Can be re-defined for child classes
	 */
	protected $_useSubscribeForm = true;
	/**
	 * Provide or not html code of social icons for to template. Can be re-defined for child classes
	 */
	protected $_useSocIcons = true;
	public function getComingSoonPageHtml() {
		$this->_beforeShow();
		
		$this->assign('msgTitle', frameGmp::_()->getModule('options')->get('msg_title'));
		$this->assign('msgTitleColor', frameGmp::_()->getModule('options')->get('msg_title_color'));
		$this->assign('msgTitleFont', frameGmp::_()->getModule('options')->get('msg_title_font'));
		$msgTitleStyle = array();
		if(!empty($this->msgTitleColor))
			$msgTitleStyle['color'] = $this->msgTitleColor;
		if(!empty($this->msgTitleFont)) {
			$msgTitleStyle['font-family'] = $this->msgTitleFont;
			$this->_styles[] = 'http://fonts.googleapis.com/css?family='. $this->msgTitleFont. '&subset=latin,cyrillic-ext';
		}
		$this->assign('msgTitleStyle', utilsGmp::arrToCss( $msgTitleStyle ));
		
		$this->assign('msgText', frameGmp::_()->getModule('options')->get('msg_text'));
		$this->assign('msgTextColor', frameGmp::_()->getModule('options')->get('msg_text_color'));
		$this->assign('msgTextFont', frameGmp::_()->getModule('options')->get('msg_text_font'));
		$msgTextStyle = array();
		if(!empty($this->msgTextColor))
			$msgTextStyle['color'] = $this->msgTextColor;
		if(!empty($this->msgTextFont)) {
			$msgTextStyle['font-family'] = $this->msgTextFont;
			if($this->msgTitleFont != $this->msgTextFont)
				$this->_styles[] = 'http://fonts.googleapis.com/css?family='. $this->msgTextFont. '&subset=latin,cyrillic-ext';
		}
		$this->assign('msgTextStyle', utilsGmp::arrToCss( $msgTextStyle ));
		
		if($this->_useSubscribeForm && frameGmp::_()->getModule('options')->get('sub_enable')) {
			$this->_scripts[] = frameGmp::_()->getModule('subscribe')->getModPath(). 'js/frontend.subscribe.js';
			$this->assign('subscribeForm', frameGmp::_()->getModule('subscribe')->getController()->getView()->getUserForm());
		}
		
		$this->assign('countDownTimerHtml', dispatcherGmp::applyFilters('countDownTimerHtml', ''));
		$this->assign('progressBarHtml', dispatcherGmp::applyFilters('progressBarHtml', ''));
		$this->assign('contactFormHtml', dispatcherGmp::applyFilters('contactFormHtml', ''));
		$this->assign('googleMapsHtml', dispatcherGmp::applyFilters('googleMapsHtml', ''));

		if($this->_useSocIcons) {
			$this->assign('socIcons', frameGmp::_()->getModule('social_icons')->getController()->getView()->getFrontendContent());
		}
		
		if(file_exists($this->getModule()->getModDir(). 'css/style.css'))
			$this->_styles[] = $this->getModule()->getModPath(). 'css/style.css';
		
		$this->assign('logoPath', $this->getModule()->getLogoImgPath());
		$this->assign('bgCssAttrs', dispatcherGmp::applyFilters('tplBgCssAttrs', $this->getModule()->getBgCssAttrs()));
		$this->assign('styles', dispatcherGmp::applyFilters('tplStyles', $this->_styles));
		$this->assign('scripts', dispatcherGmp::applyFilters('tplScripts', $this->_scripts));
		$this->assign('initJsVars', dispatcherGmp::applyFilters('tplInitJsVars', $this->initJsVars()));
		$this->assign('messages', frameGmp::_()->getRes()->getMessages());
		$this->assign('errors', frameGmp::_()->getRes()->getErrors());
		return parent::getContent($this->getCode(). 'GMPHtml');
	}
	public function addScript($path) {
		if(!in_array($path, $this->_scripts))
			$this->_scripts[] = $path;
	}
	public function addStyle($path) {
		if(!in_array($path, $this->_styles))
			$this->_styles[] = $path;
	}
	public function initJsVars() {
		$ajaxurl = admin_url('admin-ajax.php');
		if(frameGmp::_()->getModule('options')->get('ssl_on_ajax')) {
			$ajaxurl = uriGmp::makeHttps($ajaxurl);
		}
		$jsData = array(
			'siteUrl'					=> GMP_SITE_URL,
			'imgPath'					=> GMP_IMG_PATH,
			'loader'					=> GMP_LOADER_IMG, 
			'close'						=> GMP_IMG_PATH. 'cross.gif', 
			'ajaxurl'					=> $ajaxurl,
			'animationSpeed'			=> frameGmp::_()->getModule('options')->get('js_animation_speed'),
			'GMP_CODE'					=> GMP_CODE,
		);
		return '<script type="text/javascript">
		// <!--
			var GMP_DATA = '. utilsGmp::jsonEncode($jsData). ';
		// -->
		</script>';
	}
	protected function _beforeShow() {
		
	}
}