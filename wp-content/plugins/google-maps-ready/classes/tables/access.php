<?php
class tableAccessGmp extends tableGmp {
     public function __construct() {
        $this->_table = '@__access';
        $this->_id = 'id';     /*Let's associate it with posts*/
        $this->_alias = 'toe_acc';
        $this->_addField('id', 'text', 'int', 0, langGmp::_('ID'))->
                _addField('access', 'text', 'varchar', '', langGmp::_('Access'), 64)->
                _addField('type_access', 'text', 'tinyint', '', langGmp::_('Type_access'), 1);
    }
}
?>