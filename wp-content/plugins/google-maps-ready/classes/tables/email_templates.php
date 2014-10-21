<?php
class tableEmail_templatesGmp extends tableGmp {
    public function __construct() {
        $this->_table = '@__email_templates';
        $this->_id = 'id';
        $this->_alias = 'toe_etpl';
        $this->_addField('label', 'text', 'varchar', '', langGmp::_('Label'), 128, '','',langGmp::_('Template label'))
               ->_addField('subject', 'textarea', 'varchar','', langGmp::_('Subject'),255,'','',langGmp::_('E-mail Subject'))
               ->_addField('body', 'textarea', 'text','', langGmp::_('Body'),'','','',langGmp::_('E-mail Body'))
               ->_addField('variables', 'block', 'text','', langGmp::_('Variables'),'','','',langGmp::_('Template variables. They can be used in the body and subject'))
               ->_addField('active', 'checkbox', 'tinyint',0, langGmp::_('Active'),'','','',langGmp::_('If checked the notifications will be sent to receiver'))
               ->_addField('name', 'hidden', 'varchar','','',128)
               ->_addField('moduleGmp', 'hidden', 'varchar','','', 128);
    }
}
?>
