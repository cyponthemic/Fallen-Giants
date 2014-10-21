<?php
abstract class toeWordpressWidgetGmp extends WP_Widget {
	public function preWidget($args, $instance) {
		if(frameGmp::_()->isTplEditor())
			echo $args['before_widget'];
	}
	public function postWidget($args, $instance) {
		if(frameGmp::_()->isTplEditor())
			echo $args['after_widget'];
	}
}
