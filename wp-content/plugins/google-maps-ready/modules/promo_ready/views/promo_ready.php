<?php
class promo_readyViewGmp extends viewGmp {
	private $_gmapProdLink = 'http://readyshoppingcart.com/product/google-maps-plugin/';
    public function displayAdminFooter() {
        parent::display('adminFooter');
    }
	public function showWelcomePage() {
		$this->assign('askOptions', array(
			1 => array('label' => 'Google'),
			2 => array('label' => 'Wordpress.org'),
			3 => array('label' => 'Refer a friend'),
			4 => array('label' => 'Find on the web'),
			5 => array('label' => 'Other way...'),
		));
		parent::display('welcomePage');
	}
	public function showAdminSendStatNote() {
		parent::display('adminSendStatNote');
	}
	public function showProAdminPromoButtons() {
		$modPath = $this->getModule()->getModPath();
		$listAvailableDirectionsViews = array(
			//5 => array('label' => 'Slider mini'),
			6 => array('label' => 'Slider mini'),
			4 => array('label' => 'First Table'),
			1 => array('label' => 'Second Table'),
		);
		foreach($listAvailableDirectionsViews as $id => $v) {
			$listAvailableDirectionsViews[ $id ]['prev_img'] = $modPath. 'img/prev_marker_list/'. $id. '.jpg';
		}
		$listAvailableCustomControls = array(
			'simple' => array('label' => langGmp::_('Simple')),
			'pro_search' => array('label' => langGmp::_('PRO Search')),
			'ultra' => array('label' => langGmp::_('Ultra')),
		);
		foreach($listAvailableCustomControls as $c => $data) {
			$listAvailableCustomControls[ $c ]['code'] = $c;
			$listAvailableCustomControls[ $c ]['prev_img'] = $modPath. 'img/prev_cust_map_controls/'. $c. '.jpg';
		}
		$fetchStyles = array(
			'Bentley' => array(),
			'Icy Blue' => array(),
			'Snazzy Maps' => array(),
			'Subtle' => array(),
			'Apple Maps' => array(),
			'Neutral Blue' => array(),
			'Shift Worker' => array(),
			'Subtle Grayscale' => array(),
			'Pale Dawn' => array(),
			'Blue water' => array(),
			'Midnight Commander' => array(),
			'Retro' => array(),
		);
		$stylesList = array();
		foreach($fetchStyles as $label => $sData) {
			$code = str_replace(' ', '-', $label);
			$stylesList[ $code ] = $sData;
			$stylesList[ $code ]['code'] = $code;
			$stylesList[ $code ]['label'] = $label;
			$stylesList[ $code ]['prev_img'] = $modPath. 'img/prev_styles/'. $code. '.jpg';
		}
		$this->assign('listAvailableDirectionsViews', $listAvailableDirectionsViews);
		$this->assign('listAvailableCustomControls', $listAvailableCustomControls);
		$this->assign('stylesList', $stylesList);
		$this->assign('proLink', $this->_gmapProdLink);
		$this->assign('modPath', $modPath);
		parent::display('proAdminPromoButtons');
	}
	public function showProAdminFormEndPromo() {
		$this->assign('proLink', $this->_gmapProdLink);
		$this->assign('modPath', $this->getModule()->getModPath());
		parent::display('proAdminFormEndPromo');
	}
	public function getUnderMapAdminFormData() {
		return parent::getContent('underMapAdminFormData');
	}
}
