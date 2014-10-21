<?php
class tableMapsGmp extends tableGmp{
    public function __construct() {

        $this->_table = '@__maps';
        $this->_id = 'id';
        $this->_alias = 'toe_m';
        $this->_addField('id', 'int', 'int', '11', langGmp::_('Map ID'))
                ->_addField('title', 'varchar', 'varchar', '255', langGmp::_('File name'))
                ->_addField('description', 'text', 'text', '', langGmp::_('Description Of Map'))
                ->_addField('html_options', 'text', 'text', '', langGmp::_('Html Parametrs'))
                ->_addField('create_date', 'datetime', 'datetime', '', langGmp::_('Create Date'))
                ->_addField('params', 'text', 'text', '', langGmp::_('Additional Params'));

    }
}

