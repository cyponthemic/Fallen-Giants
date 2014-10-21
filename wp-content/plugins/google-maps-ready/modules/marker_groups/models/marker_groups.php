<?php
class marker_groupsModelGmp extends modelGmp {
    public function getMarkerGroups($params = array()){
        return frameGmp::_()->getTable('marker_groups')->get('*', $params);
    }
	public function getListForMarkers($markers) {
		if($markers) {
			$goupIds = array();
			foreach($markers as $m) {
				if((int) $m['marker_group_id'])
					$goupIds[ $m['marker_group_id'] ] = 1;
			}
			if(!empty($goupIds)) {
				$goupIds = array_keys($goupIds);
				return $this->getMarkerGroups(array('additionalCondition' => 'id IN ('. implode(',', $goupIds). ')'));
			}
		}
		return false;
	}
	public function getGroupByTitle($title) {
		return frameGmp::_()->getTable('marker_groups')->get('*', array('title' => $title), '', 'row');
	}
    public function getGroupById($id){
        $group = frameGmp::_()->getTable('marker_groups')->get('*', array('id' => $id), '', 'row');
        if(!empty($group)){
            return $group;
        }
        return $group;
    }
    public function showAllGroups(){
        $groups = $this->getMarkerGroups();
        return $this->getModule()->getView()->showGroupsTab($groups);
    }
    public function saveGroup($params){
        if($params['mode'] == 'update'){
            unset($params['mode']);
            $id = $params['id'];
            unset($params['id']);
            frameGmp::_()->getModule('promo_ready')->getModel()->saveUsageStat('group.edit');
			if(frameGmp::_()->getTable('marker_groups')->update($params, array('id' => $id))) {
				return $id;
			} else
				$this->pushError (frameGmp::_()->getTable('marker_groups')->getErrors());
        } else {
            unset($params['mode']);      
            frameGmp::_()->getModule('promo_ready')->getModel()->saveUsageStat('group.save');
            return frameGmp::_()->getTable('marker_groups')->insert($params);
        }
		return false;
    }
    public function removeGroup($groupId){
      return frameGmp::_()->getTable('marker_groups')->delete(array('id' => $groupId));
    }
}