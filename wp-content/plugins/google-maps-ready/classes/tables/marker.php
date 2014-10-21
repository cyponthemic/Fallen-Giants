<?php
class tableMarkerGmp extends tableGmp{
    public function __construct() {
        $this->_table = '@__markers';
        $this->_id = 'id';
        $this->_alias = 'toe_mr';
        $this->_addField('id', 'int', 'int', '11', langGmp::_('Map ID'))
                ->_addField('title', 'varchar', 'varchar', '255', langGmp::_('File name'))
                ->_addField('description', 'text', 'text', '', langGmp::_('Description Of Map'))
                ->_addField('coord_x', 'varchar', 'varchar', '50', langGmp::_('X coordinate if marker(lng)')) 
                ->_addField('coord_y', 'varchar', 'varchar', '50', langGmp::_('Y coordinate of marker(lat)'))
                ->_addField('icon', 'varchar', 'varchar', '255', langGmp::_('Path of icon file'))
                ->_addField('map_id', 'int', 'int', '11', langGmp::_('Map Id'))                
                ->_addField('address', 'text', 'text', '', langGmp::_('Marker Address'))                
                ->_addField('marker_group_id', 'int', 'int', '11', langGmp::_("Id of Marker's group"))
                ->_addField('animation','int','int','0', langGmp::_('Animation'))
                ->_addField('params','text','text','', langGmp::_('Params'))
                ->_addField('create_date','datetime','datetime','',  langGmp::_('Creation date'));
    }
}

