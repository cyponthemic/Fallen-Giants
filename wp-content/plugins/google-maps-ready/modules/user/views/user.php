<?php
class userViewGmp extends viewGmp {
	private $_passwordResetSuccess = false;	//This will set to true in password reset success case in userController
	public function setPasswordResetSuccess($val) {
		$this->_passwordResetSuccess = $val;
	}
    public function displayAllMeta($uid = 0) {
        if(!$uid)
            $uid = frameGmp::_()->getModule('user')->getModel()->getCurrentID();
        $metaFields = frameGmp::_()->getModule('user')->getModel()->getUserMeta($uid, 'registration');

        $showPassword = false;
        $currentUserData = frameGmp::_()->getModule('user')->getModel()->get();
        if($currentUserData->data->isAdmin && is_admin()) {
            $showPassword = true;
        }
		$haveOrders = frameGmp::_()->getModule('user')->isAdmin() && frameGmp::_()->getModule('order')->getModel()->userHaveOrders( $uid );
        $this->assign('showPassword', $showPassword);
        $this->assign('uid', $uid);
        $this->assign('metaFields', $metaFields);
		$this->assign('haveOrders', $haveOrders);
        parent::display('metaFields');
    }
    public function getAccountSummary() {
        return $this->getContent('accountSummary');
    }
    public function getProfileEdit() {
        $userData = frameGmp::_()->getModule('user')->getModel()->get();
        $this->assign('userData', $userData);
        return $this->getContent('profile');
    }
    public function getOrdersList($uid = 0) {
        $user = frameGmp::_()->getModule('user')->getCurrent();
        $searchCriteria = array();
        if(!$user->isAdmin) {
            if(!$uid || !is_numeric($uid))  //!is_numeric($uid) is becouse WP add some first parametr when adding the_content hook
                $uid = $user->ID;
            $searchCriteria['user_id'] = $uid;
        }
        frameGmp::_()->getModule('order')->getView()->getAllOrders( $searchCriteria );
    }
	public function getPasswordRecoverConfirm() {
		$errors = array();
		if(!$this->_passwordResetSuccess) {
			$errors[] = langGmp::_('Password Reset Error');
		}
		$this->_passwordResetSuccess = false;
		$this->assign('errorsGmp', $errors);
		return parent::getContent('passwordRecoverConfirm');
	}
}

