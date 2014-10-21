<?php
class tableFilesGmp extends tableGmp {
    public function __construct() {
        $this->_table = '@__files';
        $this->_id = 'id';
        $this->_alias = 'toe_f';
        $this->_addField('pid', 'hidden', 'int', '', langGmp::_('Product ID'))
                ->_addField('name', 'text', 'varchar', '255', langGmp::_('File name'))
                ->_addField('path', 'hidden', 'text', '', langGmp::_('Real Path To File'))
                ->_addField('mime_type', 'text', 'varchar', '32', langGmp::_('Mime Type'))
                ->_addField('size', 'text', 'int', 0, langGmp::_('File Size'))
                ->_addField('active', 'checkbox', 'tinyint', 0, langGmp::_('Active Download'))
                ->_addField('date','text','datetime','',langGmp::_('Upload Date'))
                ->_addField('download_limit','text','int','',langGmp::_('Download Limit'))
                ->_addField('period_limit','text','int','',langGmp::_('Period Limit'))
                ->_addField('description', 'textarea', 'text', 0, langGmp::_('Descritpion'))
                ->_addField('type_id','text','int','',langGmp::_('Type ID'));
    }
}
