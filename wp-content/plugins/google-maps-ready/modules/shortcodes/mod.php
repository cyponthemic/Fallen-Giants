<?php
class shortcodesGmp extends moduleGmp {
	public function init() {
		$gmapModule = frameGmp::_()->getModule('gmap');
		add_shortcode('ready_google_map', array($gmapModule, 'drawMapFromShortcode'));
	}
}