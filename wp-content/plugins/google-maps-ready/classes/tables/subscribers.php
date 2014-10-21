<?php
class tableSubscribersGmp extends tableGmp {
    public function __construct() {
        $this->_table = '@__subscribers';
        $this->_id = 'id';
        $this->_alias = 'toe_subscr';
        $this->_addField('user_id', 'text', 'int', '', langGmp::_('User Id'), 11, '', '', langGmp::_('User Id'))
            ->_addField('email', 'text', 'varchar', '', langGmp::_('User E-mail'), 255, '', '', langGmp::_('Subscriber E-mail'))
            ->_addField('name', 'text', 'varchar', 0, langGmp::_('User Name'),255,'','', langGmp::_('User Name If User Is Registered'))
            ->_addField('created', 'text', 'datetime', '', langGmp::_('Subscription Date'), '', '','', langGmp::_('Date Of Subscription'))
            ->_addField('active', 'checkbox', 'tinyint', '', langGmp::_('Active Subscription'), 4, '','', langGmp::_('If Is Not Checked user will not get any newsletters'))
            ->_addField('token', 'hidden', 'varchar', '', langGmp::_('Token'), 255,'','','')
			->_addField('ip', 'hidden', 'varchar', '', langGmp::_('IP address'), 64,'','','');
    }
}
?>