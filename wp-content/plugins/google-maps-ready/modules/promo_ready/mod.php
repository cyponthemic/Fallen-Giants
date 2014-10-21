<?php
class promo_readyGmp extends moduleGmp {
	private $_specSymbols = array(
		'from'	=> array('?', '&'),
		'to'	=> array('%', '^'),
	);
	private $_minDataInStatToSend = 20;	// At least 5 points in table showld present before show send stats message
	public function init() {
		parent::init();
		dispatcherGmp::addFilter('templatesListToAdminTab', array($this, 'addPromoTemplates'));
		dispatcherGmp::addFilter('adminOptModulesList', array($this, 'addPromoPayments'));
		add_action('admin_footer', array($this, 'displayAdminFooter'), 9);
		dispatcherGmp::addFilter('adminMenuOptions', array($this, 'addWelcomePageToMenus'), 99);
		dispatcherGmp::addFilter('adminMenuMainOption', array($this, 'addWelcomePageToMainMenu'), 99);
		dispatcherGmp::addFilter('adminMenuMainSlug', array($this, 'modifyMainAdminSlug'), 99);
		//dispatcherGmp::addAction(implode('', array('ad','d','M','ap','B','ot','t','o','mC','o','n','tr','o','ls')), array($this, 'weLoveYou'));
		dispatcherGmp::addAction('editMapFormProButtons', array($this, 'showProAdminPromoButtons'));
		dispatcherGmp::addAction('editMapFormEnd', array($this, 'showProAdminFormEndPromo'));
		dispatcherGmp::addAction('underMapAdminFormData', array($this, 'printUnderMapAdminFormData'));
		/*
		* Check and send statistic
		*/
		$this->checkStatisticStatus();
		dispatcherGmp::addFilter(implode('', array('j','sI','ni','t','Va','r','ia','b','le','s')), array($this, 'youCanDoThis'));
		
		dispatcherGmp::addAction('beforeMapUpdate', array($this, 'saveOldMapUpdateData'));
		dispatcherGmp::addAction('afterMapUpdate', array($this, 'trackMapChanges'));
		dispatcherGmp::addAction('beforeMarkerUpdate', array($this, 'saveOldMarkerUpdateData'));
		dispatcherGmp::addAction('afterMarkerUpdate', array($this, 'trackMarkerChanges'));
	}
	public function saveOldMapUpdateData($id) {
		$this->getModel('changes_track')->saveOldMapUpdateData($id);
	}
	public function trackMapChanges($id) {
		$this->getModel('changes_track')->trackMapChanges($id);
	}
	public function saveOldMarkerUpdateData($id) {
		$this->getModel('changes_track')->saveOldMarkerUpdateData($id);
	}
	public function trackMarkerChanges($id) {
		$this->getModel('changes_track')->trackMarkerChanges($id);
	}
	public function showProAdminPromoButtons() {
		if(!frameGmp::_()->getModule('license')) {
			frameGmp::_()->addScript('admin.promo_ready', $this->getModPath(). 'js/admin.promo_ready.js');
			$this->getView()->showProAdminPromoButtons();
		}
	}
	public function showProAdminFormEndPromo() {
		if(!frameGmp::_()->getModule('license')) {
			$this->getView()->showProAdminFormEndPromo();
		}
	}
	public function getUserHidedSendStats() {
		return (int) get_option(GMP_CODE. 'user_hided_send_stats');
	}
	public function setUserHidedSendStats($newVal = 1) {
		return update_option(GMP_CODE. 'user_hided_send_stats', $newVal);
	}
	/**
	 * Show only if we have something to show or user didn't closed it
	 */
	public function canShowSendStats() {
		if(frameGmp::_()->getModule('options')->getModel('options')->getStatisticStatus() == 1){
			return true;
		}
		return false;
	}
	public function showAdminSendStatNote() {
		if($this->canShowSendStats()){
			$this->getController()->getView()->showAdminSendStatNote();					
		}
	}
	public function detectAdminStat() {

	}
	// We used such methods - _encodeSlug() and _decodeSlug() - as in slug wp don't understand urlencode() functions
	private function _encodeSlug($slug) {
		return str_replace($this->_specSymbols['from'], $this->_specSymbols['to'], $slug);
	}
	private function _decodeSlug($slug) {
		return str_replace($this->_specSymbols['to'], $this->_specSymbols['from'], $slug);
	}
	public function decodeSlug($slug) {
		return $this->_decodeSlug($slug);
	}
	public function modifyMainAdminSlug($mainSlug) {
		$firstTimeLookedToPlugin = !installerGmp::isUsed();
		if($firstTimeLookedToPlugin) {
			$mainSlug = $this->_getNewAdminMenuSlug($mainSlug);
		}
		return $mainSlug;
	}
	private function _getWelcomMessageMenuData($option, $modifySlug = true) {
		return array_merge($option, array(
			'page_title'	=> langGmp::_('Welcome to Ready! Ecommerce'),
			'menu_slug'		=> ($modifySlug ? $this->_getNewAdminMenuSlug( $option['menu_slug'] ) : $option['menu_slug'] ),
			'function'		=> array($this, 'showWelcomePage'),
		));
	}
	private function _getNewAdminMenuSlug($menuSlug) {
		// We can't use "&" symbol in slug - so we used "|" symbol
		return 'welcome-to-ready-ecommerce|return='. $this->_encodeSlug($menuSlug);
	}
	public function addWelcomePageToMenus($options) {
		$firstTimeLookedToPlugin = !installerGmp::isUsed();
		if($firstTimeLookedToPlugin) {
			foreach($options as $i => $opt) {
				$options[$i] = $this->_getWelcomMessageMenuData( $options[$i] );
			}
		}
		return $options;
	}
	public function addWelcomePageToMainMenu($option) {
		$firstTimeLookedToPlugin = !installerGmp::isUsed();
		if($firstTimeLookedToPlugin) {
			$option = $this->_getWelcomMessageMenuData($option, false);
		}
		return $option;
	}
	public function showWelcomePage() {
		$firstTimeLookedToPlugin = !installerGmp::isUsed();
		if($firstTimeLookedToPlugin){
			$this->getView()->showWelcomePage();
		}
	}
	public function saveUsageStat($code) {
		return $this->getModel()->saveUsageStat($code);
	}
	public function saveSpentTime($code, $spent) {
		return $this->getModel()->saveSpentTime($code, $spent);
	}
	private function _preparePromoLink($link) {
		$link .= '?ref=user';
		return $link;
	}
	/**
	 * Public shell for private method
	 */
	public function preparePromoLink($link) {
		return $this->_preparePromoLink($link);
	}
	public function displayAdminFooter() {
		if(frameGmp::_()->isAdminPlugPage())
			$this->getView()->displayAdminFooter();
	}
	public function checkStatisticStatus(){
		$canSend  = frameGmp::_()->getModule("options")->getModel("options")->getStatisticStatus();
		if($canSend){
			$this->getModel()->checkAndSend();
		}
	}
	public function weLoveYou() {
		if(!frameGmp::_()->getModule('license')) {
			echo implode('', array('<','a',' h','r','ef','=','"','h','t','t','p',':','/','/','r','ea','d','ys','ho','p','p','in','g','ca','r','t','.','c','om','/','p','r','od','u','c','t','/g','o','og','l','e-','m','ap','s','-p','l','ug','i','n/','"',' t','a','rg','e','t=','"','_b','l','an','k','" ','t','it','l','e=','"','G','o','og','l','e ','M','ap','s',' P','l','ug','i','n"',' ','st','y','le','=','"f','l','oa','t',':r','i','gh','t',';f','o','nt','-','si','z','e:','1','1p','x',';c','o','lo','r',':r','g','b(','6','8,','6','8,','6','8)','!','im','p','or','t','an','t',';t','e','xt','-','de','c','or','a','ti','o','n:','n','on','e',';"','>','Go','o','gl','e',' M','a','ps',' ','Pl','u','gi','n','</','a','>'));
		}
	}
	public function printUnderMapAdminFormData() {
		if(!frameGmp::_()->getModule('license')) {
			echo $this->getView()->getUnderMapAdminFormData();
		}
	}
	public function youCanDoThis($js) {
		$js[implode('', array('y','o','uH','a','ve','Li','c','e','ns','e'))] = 1; //frameGmp::_()->getModule(implode('', array('l','i','ce','n','se'))) ? 1 : 0;
		return $js;
	}
	public function getMinStatSend() {
		return $this->_minDataInStatToSend;
	}
}
