<?php
class tableOptionsGmp extends tableGmp {
     public function __construct() {
        $this->_table = '@__options';
        $this->_id = 'id';     /*Let's associate it with posts*/
        $this->_alias = 'toe_opt';
        $this->_addField('id', 'text', 'int', 0, langGmp::_('ID'))->
                _addField('code', 'text', 'varchar', '', langGmp::_('Code'), 64)->
                _addField('value', 'text', 'varchar', '', langGmp::_('Value'), 134217728)->
                _addField('label', 'text', 'varchar', '', langGmp::_('Label'), 255)->
                _addField('params', 'text', 'text', '', langGmp::_('Params') )->
                _addField('description', 'text', 'text', '', langGmp::_('Description'))->
                _addField('htmltype_id', 'selectbox', 'text', '', langGmp::_('Type'))->
				_addField('cat_id', 'hidden', 'int', '', langGmp::_('Category ID'))->
				_addField('sort_order', 'hidden', 'int', '', langGmp::_('Sort Order'))->
				_addField('value_type', 'hidden', 'varchar', '', langGmp::_('Value Type'));;
    }
}
?>
