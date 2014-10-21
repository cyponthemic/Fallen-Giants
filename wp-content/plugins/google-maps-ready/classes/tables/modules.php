<?php
class tableModulesGmp extends tableGmp {
    public function __construct() {
        $this->_table = '@__modules';
        $this->_id = 'id';     /*Let's associate it with posts*/
        $this->_alias = 'toe_m';
        $this->_addField('label', 'text', 'varchar', 0, langGmp::_('Label'), 128)
                ->_addField('type_id', 'selectbox', 'smallint', 0, langGmp::_('Type'))
                ->_addField('active', 'checkbox', 'tinyint', 0, langGmp::_('Active'))
                ->_addField('params', 'textarea', 'text', 0, langGmp::_('Params'))
                ->_addField('has_tab', 'checkbox', 'tinyint', 0, langGmp::_('Has Tab'))
                ->_addField('description', 'textarea', 'text', 0, langGmp::_('Description'), 128)
                ->_addField('code', 'hidden', 'varchar', '', langGmp::_('Code'), 64)
                ->_addField('ex_plug_dir', 'hidden', 'varchar', '', langGmp::_('External plugin directory'), 255);
    }
}
?>
