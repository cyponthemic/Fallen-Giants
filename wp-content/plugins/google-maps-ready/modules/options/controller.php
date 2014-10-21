<?php
class optionsControllerGmp extends controllerGmp {
	public function activatePlugin() {
		$res = new responseGmp();
		if ($this->getModel('modules')->activatePlugin(reqGmp::get('post'))) {
			$res->addMessage(langGmp::_('Plugin was activated'));
		} else {
			$res->pushError($this->getModel('modules')->getErrors());
		}
		return $res->ajaxExec();
	}
	public function activateUpdate() {
		$res = new responseGmp();
		if ($this->getModel('modules')->activateUpdate(reqGmp::get('post'))) {
			$res->addMessage(langGmp::_('Very good! Now plugin will be updated.'));
		} else {
			$res->pushError($this->getModel('modules')->getErrors());
		}
		return $res->ajaxExec();
	}
	/*public function updatePluginSettings() {
		$data = reqGmp::get('post');
		$resp = new responseGmp();
		if (empty($data)) {
			$resp->pushError(langGmp::_('Cannot Save Info'));
			return $resp->ajaxExec();
		}
		$saveStatistic = $this->getModel('options')->updateStatisticStatus($data);
		$saveinfoWindowSize = $this->getModel('options')->updateInfoWindowSize($data['infoWindowSize']);
	}*/
	public function saveGroup() {
		$res = new responseGmp();
		if($this->getModel()->saveGroup(reqGmp::get('post'))) {
			$res->addMessage(langGmp::_('Done'));
		} else
			$res->pushError ($this->getModel('options')->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			GMP_USERLEVELS => array(
				GMP_ADMIN => array('activatePlugin', 'activateUpdate', 'updatePluginSettings', 'saveGroup')
			),
		);
	}
}