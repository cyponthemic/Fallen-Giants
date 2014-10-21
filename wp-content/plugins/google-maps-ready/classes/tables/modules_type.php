<?php
class tableModules_typeGmp extends tableGmp {
    public function __construct() {
        $this->_table = '@__modules_type';
        $this->_id = 'id';     /*Let's associate it with posts*/
        $this->_alias = 'toe_m_t';
        $this->_addField($this->_id, 'text', 'int', '', langGmp::_('ID'))->
                _addField('label', 'text', 'varchar', '', langGmp::_('Label'), 128);
    }
}
?>
