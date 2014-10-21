<?php
class promo_readyControllerGmp extends controllerGmp {
	public function welcomePageSaveInfo() {
		$res = new responseGmp();
		// Start usage in any case
		installerGmp::setUsed();
		if($this->getModel()->welcomePageSaveInfo(reqGmp::get('get'))) {
			$res->addMessage(langGmp::_('Information was saved. Thank you!'));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$originalPage = reqGmp::getVar('original_page');
		$return = admin_url( strpos($originalPage, '?') ? $return : 'admin.php?page='. $originalPage);
		// Start usage in any case
		redirectGmp($return);
	}
	public function saveUsageStat() {
		$res = new responseGmp();
		$code = reqGmp::getVar('code');
		if($code)
			$this->getModel()->saveUsageStat($code);
		return $res->ajaxExec();
	}
	public function sendUsageStat() {
		$res = new responseGmp();
		$this->getModel()->sendUsageStat();
		$res->addMessage(langGmp::_('Information was saved. Thank you for your support!'));
		return $res->ajaxExec();
	}
	public function hideUsageStat() {
		$res = new responseGmp();
		$this->getModule()->setUserHidedSendStats();
		return $res->ajaxExec();
	}
	/**
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			GMP_USERLEVELS => array(
				GMP_ADMIN => array('welcomePageSaveInfo', 'saveUsageStat', 'sendUsageStat', 'hideUsageStat')
			),
		);
	}
}