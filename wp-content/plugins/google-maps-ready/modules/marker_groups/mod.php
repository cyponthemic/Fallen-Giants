<?php
class  marker_groupsGmp extends moduleGmp {
    public function init(){
		if(frameGmp::_()->isAdminPlugPage()){
		   frameGmp::_()->addScript("admin.group",$this->getModPath()."js/admin.group.js");			
		}
    }
    public function getDefaultIcon(){
        return frameGmp::_()->getModule('icons')->getModPath().'icon_files'.DS.'marker1.png';
    }
    public function install() {
        parent::install();
        frameGmp::_()->getTable('marker_groups')->insert(array(
                'title' => 'Default',
                'description' => 'Default'
        ));
    }    
}