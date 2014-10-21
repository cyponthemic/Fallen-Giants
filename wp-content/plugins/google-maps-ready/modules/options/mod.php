<?php
class optionsGmp extends moduleGmp {
	public static $saveStatistic = null;
	public static $statLimit = 20;
		
	/**
	 * Method to trigger the database update
	 */
	// Really - don't know what prev. developer tryed to say in this code, 
	// there are no "find_us" code in options page, and there are no checkStatistic() function
	/*public function init(){
		parent::init();
		if(!self::$saveStatistic){
			$data = frameGmp::_()->getTable('options')->get('*', " `code`='find_us' "); 
			$params = utilsGmp::jsonDecode($data[0]['params']);
			self::$saveStatistic = $params['save_statistic'];
		}
		$this->checkStatistic();
	}*/
	/**
	 * Returns the available tabs
	 * 
	 * @return array of tab 
	 */
	public function getTabs(){
		$tabs = array();
		$tab = new tabGmp(langGmp::_('General'), $this->getCode());
		$tab->setView('optionTab');
		$tab->setSortOrder(-99);
		$tabs[] = $tab;
		return $tabs;
	}
	/**
	 * This method provides fast access to options model method get
	 * @see optionsModel::get($d)
	 */
	public function get($d = array()) {
		return $this->getController()->getModel()->get($d);
	}
	/**
	 * This method provides fast access to options model method get
	 * @see optionsModel::get($d)
	 */
	public function isEmpty($d = array()) {
		return $this->getController()->getModel()->isEmpty($d);
	}
	
	public function getUploadDir() {
		return $this->_uploadDir;
	}

	public function getAllowedPublicOptions() {
		$res = array();
		$alowedForPublic = array('mode', 'template');
		$allOptions = $this->getModel()->getByCode();
		foreach($alowedForPublic as $code) {
			if(isset($allOptions[ $code ]))
				$res[ $code ] = $allOptions[ $code ];
		}
		return $res;
	}
	public function getFindOptions(){
			return array(
			1 => array('label' => 'Google'),
			2 => array('label' => 'Wordpress.org'),
			3 => array('label' => 'Refer a friend'),
			4 => array('label' => 'Find on the web'),
			5 => array('label' => 'Other way...'),
		);
	}
}

