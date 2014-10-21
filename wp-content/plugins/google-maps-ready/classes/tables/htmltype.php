<?php
class tableHtmltypeGmp extends tableGmp {
    public function __construct() {
        $this->_table = '@__htmltype';
        $this->_id = 'id';     
        $this->_alias = 'toe_htmlt';
        $this->_addField('id', 'hidden', 'int', 0, langGmp::_('ID'))
            ->_addField('label', 'text', 'varchar', 0, langGmp::_('Method'), 32)
            ->_addField('description', 'text', 'varchar', 0, langGmp::_('Description'), 255);
    }
}
?>
