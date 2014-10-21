<?php
class optionsModelGmp extends modelGmp {
	protected $_allOptions = array();
	public function get($d = array()) {
		$this->_loadOptions();
		$code = false;
		if(is_string($d))
			$code = $d;
		elseif(is_array($d) && isset($d['code']))
			$code = $d['code'];
		if($code) {
			$opt = $this->_getByCode($code);
			if(isset($d['what']) && isset($opt[$d['what']]))
				return $opt[$d['what']];
			else
				return $opt['value'];
		} else {
			return $this->_allOptions;
		}
	}
	public function isEmpty($d = array()) {
		$value = $this->get($d);
		return empty($value);
	}
	public function getByCategories($category = '') {
		$this->_loadOptions();
		$categories = array();
		$returnForCat = !empty($category);	// If this is not empty - will be returned anly for one category
		foreach($this->_allOptions as $opt) {
			if(empty($category)
				|| (is_numeric($category) && $category == $opt['cat_id'])
				|| ($category == $opt['cat_label'])
			) {
				if(empty($categories[ (int)$opt['cat_id'] ]))
					$categories[ (int)$opt['cat_id'] ] = array('cat_id' => $opt['cat_id'], 'cat_label' => $opt['cat_label'], 'opts' => array());
				$categories[ (int)$opt['cat_id'] ]['opts'][] = $opt;
				if($returnForCat)	// Save category ID for returning
					$returnForCat = (int)$opt['cat_id'];
			}
		}
		if($returnForCat)
			return $categories[ $returnForCat ];
		ksort($categories);
		return $categories;
	}
	public function getByCode($d = array()) {
		$res = array();
		$codeData = $this->get($d);
		if(empty($d)) {
			// Sort by code
			foreach($codeData as $opt) {
				$res[ $opt['code'] ] = $opt;
			}
		} else
			$res = $codeData;
		return $res;
	}
	/**
	 * Load all options data into protected array
	 */
	protected function _loadOptions() {
		if(empty($this->_allOptions)) {
			$options = frameGmp::_()->getTable('options');
			$htmltype = frameGmp::_()->getTable('htmltype');
			$optionsCategories = frameGmp::_()->getTable('options_categories');
			$this->_allOptions = $options->innerJoin($htmltype, 'htmltype_id')
					->leftJoin($optionsCategories, 'cat_id')
					->orderBy(array('cat_id', 'sort_order'))
					->getAll($options->alias(). '.*, '. $htmltype->alias(). '.label AS htmltype, '. $optionsCategories->alias(). '.label AS cat_label');
			foreach($this->_allOptions as $i => $opt) {
				if(!empty($this->_allOptions[$i]['params'])) {
					$this->_allOptions[$i]['params'] = utilsGmp::unserialize($this->_allOptions[$i]['params']);
				}
				if($this->_allOptions[$i]['value_type'] == 'array') {
					$this->_allOptions[$i]['value'] = utilsGmp::unserialize($this->_allOptions[$i]['value']);
					if(!is_array($this->_allOptions[$i]['value']))
						$this->_allOptions[$i]['value'] = array();
				}
				if(empty($this->_allOptions[$i]['cat_id'])) {	// Move all options that have no category - to Other
					$this->_allOptions[$i]['cat_id'] = 6;
					$this->_allOptions[$i]['cat_label'] = 'Other';
				}
			}
		}
	}
	/**
	 * Returns option data by it's code
	 * @param string $code option's code
	 * @return array option's data
	 */
	protected function _getByCode($code) {
		$this->_loadOptions();
		if(!empty($this->_allOptions)) {
			foreach($this->_allOptions as $opt) {
				if($opt['code'] == $code)
					return $opt;
			}
		}
		return false;
	}
	
	/**
	 * Set option value by code, do no changes in database
	 * @param string $code option's code
	 * @param string $value option's new value
	 */
	protected function _setByCode($code, $value) {
		$this->_loadOptions();
		if(!empty($this->_allOptions)) {
			foreach($this->_allOptions as $id => $opt) {
				if($opt['code'] == $code) {
					$this->_allOptions[ $id ]['value'] = $value;
					break;
				}
			}
		}
	} 
	public function save($d = array()) {
		$id = 0;
		if(isset($d['opt_values']) && is_array($d['opt_values']) && !empty($d['opt_values'])) {
			if(isset($d['code']) && !empty($d['code'])) {
				$d['what'] = 'id';
				$id = $this->get($d);
				$id = intval($id);
			}
			if($id) {
				$updateData = array('value' => $d['opt_values'][ $d['code'] ]);
				if($this->get(array('code' => $d['code'], 'what' => 'value_type')) == 'array') {
					$updateData['value'] = utilsGmp::serialize( $updateData['value'] );
				}
				if(frameGmp::_()->getTable('options')->update($updateData, array('id' => $id))) {
					// Let's update data in current options params to avoid reload it from database
					if(isset($d['code']))
						$this->_setByCode($d['code'], $d['opt_values'][ $d['code'] ]);
					return true;
				} else
					$this->pushError(langGmp::_('Option '. $d['code']. ' update Failed'));
			} else {
				$this->pushError(langGmp::_('Invalid option ID or Code'));
			}
		} else
			$this->pushError(langGmp::_('Empty data to save option'));
		return false;
	}
	public function saveGroup($d = array()) {
		if(isset($d['opt_values']) && is_array($d['opt_values']) && !empty($d['opt_values'])) {
			foreach($d['opt_values'] as $code => $value) {
				$d['code'] = $code;
				$this->save($d);
			}
			return !$this->haveErrors();
		} else
			$this->pushError(langGmp::_('Empty data to setup'));
	}
	public function welcomePageSaveInfo($params){
		$findOpts=frameGmp::_()->getModule('options')->getFindOptions();
			$insert=array(
							"code"=>"find_us",
							"value"=>$params["where_find_us"],
							"label"=>$findOpts[$params["where_find_us"]]['label'],
							"params" =>  utilsGmp::jsonEncode(array("save_statistic"=>(int)$params['statistic']))
					);
		 
			switch($params["where_find_us"]){
			   case "5":
				   $insert['description']=$params['other_way_desc'];
			   break;
			   case "4":
				$insert['description']=$params['find_on_web_url'];
			   break;	
			}
			frameGmp::_()->getTable("options")->insert($insert); 
			return true;
	}
	public function getStatisticStatus(){
		$stat = frameGmp::_()->getTable('options')->get('value', " `code`='save_statistic' ") ;
		if(empty($stat)){
			return 0;
		}
		return $stat[0]['value'];
	}
	public function updateStatisticStatus($params){
		return frameGmp::_()->getTable('options')->update(array('value' => $params['send_statistic']), "`code`='save_statistic'");
	}
	
	public function updateInfoWindowSize($sizeParams){
		return frameGmp::_()->getTable('options')->update(array('value' => utilsGmp::serialize($sizeParams)), "`code`='infowindow_size'");			
	}
}