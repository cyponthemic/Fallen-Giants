<?php
class tableUsage_statGmp extends tableGmp{
    public function __construct() {

        $this->_table = '@__usage_stat';
        $this->_id = 'id';
        $this->_alias = 'gmp_icons';
        $this->_addField('id', 'int', 'int', '11', langGmp::_('Usage id'))
               ->_addField('code', 'varchar', 'varchar', '200', langGmp::_('Code'))
               ->_addField('visits', 'int', 'int', '11', langGmp::_('Visits Count'));
    }
}

