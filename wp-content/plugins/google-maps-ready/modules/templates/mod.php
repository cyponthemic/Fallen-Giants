<?php
class templatesGmp extends moduleGmp {
    /**
     * Returns the available tabs
     * 
     * @return array of tab 
     */
    protected $_styles = array();
    public function getTabs(){
        $tabs = array();
        $tab = new tabGmp(langGmp::_('Templates'), $this->getCode());
        $tab->setView('templatesTab');
		$tab->setSortOrder(1);
        $tabs[] = $tab;
        return $tabs;
    }
    public function init() {
		if(frameGmp::_()->isAdminPlugPage()){
			$this->_styles = array(
				'styleGmp'				=> array('path' => GMP_CSS_PATH. 'style.css'), 
				'adminStylesGmp'		=> array('path' => GMP_CSS_PATH. 'adminStyles.css'), 
				'farbtastic'			=> array(),
				'wp-jquery-ui-dialog'	=> array(),
				// Our corrections for ui dialog
				'jquery-dialog'			=> array('path' => GMP_CSS_PATH. 'jquery-dialog.css'),
				//'jquery-tabs'			=> array('path' => GMP_CSS_PATH. 'jquery-tabs.css', 'for' => 'admin'),
			);
		}
        $defaultPlugTheme = frameGmp::_()->getModule('options')->get('default_theme');
		$ajaxurl = admin_url('admin-ajax.php');
		if(frameGmp::_()->getModule('options')->get('ssl_on_ajax')) {
			$ajaxurl = uriGmp::makeHttps($ajaxurl);
		}
        $jsData = array(
            'siteUrl'					=> GMP_SITE_URL,
            'imgPath'					=> GMP_IMG_PATH,
			'cssPath'					=> GMP_CSS_PATH,
            'loader'					=> GMP_LOADER_IMG, 
            'close'						=> GMP_IMG_PATH. 'cross.gif', 
            'ajaxurl'					=> $ajaxurl,
            'animationSpeed'			=> frameGmp::_()->getModule('options')->get('js_animation_speed'),
			'siteLang'					=> langGmp::getData(),
			'options'					=> frameGmp::_()->getModule('options')->getAllowedPublicOptions(),
			'GMP_CODE'					=> GMP_CODE,
			'ball_loader'				=> GMP_IMG_PATH. 'ajax-loader-ball.gif',
			'ok_icon'					=> GMP_IMG_PATH. 'ok-icon.png',
			'isHttps'					=> uriGmp::isHttps(),
        );
		if(frameGmp::_()->isAdminPlugPage()){
			frameGmp::_()->addScript('commonGmp', GMP_JS_PATH. 'common.js');
			frameGmp::_()->addScript('coreGmp', GMP_JS_PATH. 'core.js');
	
			$jsData = dispatcherGmp::applyFilters('jsInitVariables', $jsData);
		
	        frameGmp::_()->addJSVar('coreGmp', 'GMP_DATA', $jsData);

			frameGmp::_()->addScript('datatable', GMP_JS_PATH. 'jquery.dataTables.min.js');	
			frameGmp::_()->addScript('farbtastic',get_bloginfo('wpurl'). '/wp-admin/js/farbtastic.js', array('jquery'));
			
			frameGmp::_()->addScript('jquery-ui-tabs', '', array('jquery'), false, true);
			frameGmp::_()->addScript('jquery-ui-autocomplete', '', array('jquery'), false, true);
			
			frameGmp::_()->getModule('marker')->connectAssets();
			frameGmp::_()->addScript('jquery-ui-dialog', '', array('jquery'));
			frameGmp::_()->addScript('adminOptionsGmp', GMP_JS_PATH. 'admin.options.js');
		}

        if (is_admin()) {
			frameGmp::_()->addScript('ajaxupload', GMP_JS_PATH. 'ajaxupload.js');
			frameGmp::_()->addScript('postbox', get_bloginfo('wpurl'). '/wp-admin/js/postbox.js');
			add_action('wp_enqueue_scripts', array($this, 'addThickbox'));

			$jsData['allCheckRegPlugs']	= modInstallerGmp::getCheckRegPlugs();
		} else {

        }
        foreach($this->_styles as $s => $sInfo) {
            if(isset($sInfo['for'])) {
                if(($sInfo['for'] == 'frontend' && is_admin()) || ($sInfo['for'] == 'admin' && !is_admin()))
                    continue;
            }
            $canBeSubstituted = true;
            if(isset($sInfo['substituteFor'])) {
                switch($sInfo['substituteFor']) {
                    case 'frontend':
                        $canBeSubstituted = !is_admin();
                        break;
                    case 'admin':
                        $canBeSubstituted = is_admin();
                        break;
                }
            }
            if($canBeSubstituted && file_exists(GMP_TEMPLATES_DIR. $defaultPlugTheme. DS. $s. '.css')) {
                frameGmp::_()->addStyle($s, GMP_TEMPLATES_PATH. $defaultPlugTheme. '/'. $s. '.css');
            } elseif($canBeSubstituted && file_exists(utilsGmp::getCurrentWPThemeDir(). 'gmp'. DS. $s. '.css')) {
                frameGmp::_()->addStyle($s, utilsGmp::getCurrentWPThemePath(). '/toe/'. $s. '.css');
            } elseif(!empty($sInfo['path'])) {
                frameGmp::_()->addStyle($s, $sInfo['path']);
            } else {
				frameGmp::_()->addStyle($s);
			}
        }
        parent::init();
    }
	public function addThickbox() {
		add_thickbox();
	}
}
