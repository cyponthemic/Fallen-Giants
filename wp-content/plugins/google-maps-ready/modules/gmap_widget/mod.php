<?php
class gmap_widgetGmp extends moduleGmp {
	public function init() {
        parent::init();
        add_action('widgets_init', array($this, 'registerWidget'));
    }
    public function registerWidget() {
        return register_widget('gmpMapsWidget');
    }    
}
/**
 * Maps widget class
 */
class gmpMapsWidget extends WP_Widget {
    public function __construct() {
        $widgetOps = array( 
            'classname' => 'gmpMapsWidget', 
            'description' => langGmp::_('Displays Most Viewed Products')
        );
        $control_ops = array(
            'id_base' => 'gmpMapsWidget'
        );
		parent::__construct( 'gmpMapsWidget', langGmp::_('Google Maps Ready!'), $widgetOps );
    }
    public function widget($args, $instance) {
        frameGmp::_()->getModule('gmap_widget')->getView()->displayWidget($instance);
    }
    public function update($new_instance, $old_instance) {
		frameGmp::_()->getModule('promo_ready')->getModel()->saveUsageStat('map.widget.update');
        return $new_instance;
    }
    public function form($instance) {
        frameGmp::_()->getModule('gmap_widget')->getView()->displayForm($instance, $this);
    }
}