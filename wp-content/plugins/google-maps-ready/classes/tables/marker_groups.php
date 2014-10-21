<?php
class tableMarker_groupsGmp extends tableGmp{
    public function __construct() {

        $this->_table = '@__marker_groups';
        $this->_id = 'id';
        $this->_alias = 'gmp_mrgr';
        $this->_addField('id', 'int', 'int', '11', langGmp::_('Map ID'))
                ->_addField('title', 'varchar', 'varchar', '255', langGmp::_('File name'))
                ->_addField('description', 'text', 'text', '', langGmp::_('Description Of Map'));
    }
}

