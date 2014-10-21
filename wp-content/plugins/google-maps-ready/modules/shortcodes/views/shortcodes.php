<?php
class shortcodesViewGmp extends viewGmp {
    public function adminTextEditorPopup() {
        $shortcodes = frameGmp::_()->getModule('shortcodesGmp')->getCodes();
        $shortcodesSelectOptions = array('' => langGmp::_('Select'));
        foreach($shortcodes as $code => $cinfo) {
            if(in_array($code, array('product', 'category'))) continue;
            $shortcodesSelectOptions[ $code ] = $code;
        }
        $this->assign('shortcodesGmp', $shortcodes);
        $this->assign('shortcodesSelectOptions', $shortcodesSelectOptions);
        return parent::getContent('adminTextEditorPopup');
    }
}
