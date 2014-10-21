<?php
class marker_groupsViewGmp extends viewGmp {
    public function showGroupsTab($groupsList,$isAjaxRequest=false){
        $this->assign('groupsList',$groupsList);
        if($isAjaxRequest){
           return parent::getContent('groupsTable');            
        }
        $this->assign("tableContent", parent::getContent('groupsTable'));
        return parent::getContent('groupsList');                    
    }
}