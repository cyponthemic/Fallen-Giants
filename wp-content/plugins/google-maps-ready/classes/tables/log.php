<?php
class tableLogGmp extends tableGmp {
    public function __construct() {
        $this->_table = '@__log';
        $this->_id = 'id';     /*Let's associate it with posts*/
        $this->_alias = 'toe_log';
        $this->_addField('id', 'text', 'int', 0, langGmp::_('ID'), 11)
                ->_addField('type', 'text', 'varchar', '', langGmp::_('Type'), 64)
                ->_addField('data', 'text', 'text', '', langGmp::_('Data'))
                ->_addField('date_created', 'text', 'int', '', langGmp::_('Date created'))
				->_addField('uid', 'text', 'int', 0, langGmp::_('User ID'))
				->_addField('oid', 'text', 'int', 0, langGmp::_('Order ID'));
    }
}