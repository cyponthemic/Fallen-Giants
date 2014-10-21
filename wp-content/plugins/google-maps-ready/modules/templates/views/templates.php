<?php
/**
 * Class for templates module tab at options page
 */
class templatesViewGmp extends viewGmp {
    /**
     * Get the content for templates module tab
     * 
     * @return type 
     */
    public function getTabContent(){
       $templates = frameGmp::_()->getModule('templatesGmp')->getModel()->get();
       if(empty($templates)) {
           $tpl = 'noTemplates';
       } else {
           $this->assign('templatesGmp', $templates);
           $this->assign('default_theme', frameGmp::_()->getModule('optionsGmp')->getModel()->get('default_theme'));
           $tpl = 'templatesTab';
       }
       return parent::getContent($tpl);
   }
}

