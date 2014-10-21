<?php
/*
Copyright (C) 2011-08/2014  Alexander Zagniotov

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
$cgmp_admin_notice = isset($_GET['admin_notice']) ? $_GET['admin_notice'] : '';
$cgmp_plugin_notice = isset($_GET['plugin_notice']) ? $_GET['plugin_notice'] : '';
$cgmp_metabox_notice = isset($_GET['metabox_notice']) ? $_GET['metabox_notice'] : '';
$cgmp_options = get_option('cgmp_options');

if ($cgmp_admin_notice != NULL) {
	$cgmp_options['admin_notice'] = 'hide';
	update_option('cgmp_options',$cgmp_options);
	echo '<div class="updated" style="padding:10px;margin:10px 0;">"Comprehensive Google Map Plugin" options have been updated!</div>';
	echo '<script type="text/javascript">jQuery(document).ready(function($) { $("#cgmp_admin_notice").hide(); });</script>';
}
if ($cgmp_plugin_notice != NULL) {
	$cgmp_options['plugin_notice'] = 'hide';
	update_option('cgmp_options',$cgmp_options);
	echo '<div class="updated" style="padding:10px;margin:10px 0;">"Comprehensive Google Map Plugin" options have been updated!</div>';
}
if ($cgmp_metabox_notice != NULL) {
	$cgmp_options['metabox_notice'] = 'hide';
	update_option('cgmp_options',$cgmp_options);
	echo '<div class="updated" style="padding:10px;margin:10px 0;">"Comprehensive Google Map Plugin" options have been updated!</div>';
}
$action = isset($_GET['action']) ? $_GET['action'] : '';
$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';

if (file_exists($lmm_pro_readme)) {
	$install_active = 'disabled="disabled"';
	$install_title = '"Maps Marker Pro" has already been installed';
} else {
	$install_active = '';
	$install_title = 'install "Maps Marker Pro" plugin';
}	

if ( $action == NULL ) {
	$current_user = wp_get_current_user();
	echo '<div style="padding-right:20px;">
	<h3>Notice of plugin discontinuation</h3>';
	
	if (file_exists($lmm_pro_readme)) {
		$admin_url = get_admin_url();
		if (!is_plugin_active('leaflet-maps-marker-pro/leaflet-maps-marker.php') ) {
			echo '<div class="error" style="padding:10px;"><strong>You already downloaded "Maps Marker Pro" but did not activate the plugin yet!</strong>';
			if ( current_user_can( 'install_plugins' ) ) {
				echo sprintf(__('<br/>Please navigate to <a style="text-decoration:underline;" href="%1$s">Plugins / Installed Plugins</a> and activate the plugin "Maps Marker Pro".'), $admin_url . 'plugins.php');
			} else {
				echo sprintf(__('Please contact your administrator (%1s) to activate the plugin "Maps Marker Pro".'), '<a style="text-decoration:underline;" href="mailto:' . get_bloginfo('admin_email') . '?subject=Please activate the plugin Maps Marker Pro">' . get_bloginfo('admin_email') . '</a>' );
			}
			echo '</div><br/>';
		} else {
			if (get_option('leafletmapsmarkerpro_license_key') == NULL) {
				echo '<div class="error" style="padding:10px;">Please <a style="text-decoration:underline;" href="' . $admin_url . 'admin.php?page=leafletmapsmarker_license">activate a valid "Maps Marker Pro" license</a> to be able to start the transfer!</div><br/>';
			} else {
				echo '<div class="updated" style="padding:10px;">"Maps Marker Pro" has been successully installed.<br/>You can now <a style="text-decoration:underline;" href="' . $admin_url . 'admin.php?page=cgmp_export">start transfering your maps</a>.</div><br/>';
			}
		}
	}
	
	echo 'Dear ' . $current_user->display_name . ',
	<br/><br/>
	over the last 3 years the "Comprehensive Google Map Plugin" has been downloaded more than <a href="http://wordpress.org/plugins/comprehensive-google-map-plugin/" target="_blank" title="view download stats on wordpress.org">545,000 times</a> - making my plugin one of the most downloaded mapping plugins in the WordPress plugin repository :-)
	<br/>
	Unfortunately, due to limited resources and changes in my professional life, I am not able anymore to offer the kind of professional support and development needed for a plugin with such a large userbase :-(
	<br/>After thinking about this for a long time, I have finally decided that this will be my last official release, fixing an issue with broken post edit screens since WordPress 4.0.
	<br/><br/>
	Any future possible issues (e.g. incompatibilities with other plugins or newer WordPress versions) will not be fixed by me and I will not offer any new official releases available from the WordPress plugin repository. Of course, interested developers can fork my plugin (as it is licensed under the <a href="https://www.gnu.org/licenses/" target="_blank" title="show license on gnu.org">GPL</a>) and make it available as a new plugin. Please also note that I will not monitor and reply to support tickets in the WordPress support forum anymore. Please also refrain from sending me emails regarding support issues.
	<br/><br/>
	<div style="float:left;padding-right:5px;"><a href="https://www.mapsmarker.com" title="visit official website www.mapsmarker.com" target="_blank"><img src="' . CGMP_PLUGIN_IMAGES .'/logo-mapsmarker-pro.png"></a></div>Anyway, in the long run I recommend switching to another mapping plugin which is actively maintained, developed and supported. I personally recommend giving <a href="https://www.mapsmarker.com" target="_blank" title="view official website www.mapsmarker.com">Maps Marker Pro</a> a try. This plugin received lots of <a href="https://www.mapsmarker.com/reviews" target="_blank" title="view review on mapsmarker.com">great reviews</a> and allows you to pin, organize &amp; show your favorite places and tracks through OpenStreetMap, Google Maps, <a href="https://demo.mapsmarker.com/features/animated-timeline-in-kml/" target="_blank" title="show video about KMKL animation">KML</a>, Bing Maps, APIs or even <a href="https://demo.mapsmarker.com/features/sample-page/" target="_blank" title="show screenshots about this great feature">Augmented-Reality browsers</a>.
	<br/><br/>
	In addition Maps Marker Pro also offers 
	<ul style="list-style:disc;margin-left:15px;">
	<li><a href="https://www.mapsmarker.com/v1.9p" target="_blank">geolocation support</a> (show and follow your location when viewing maps),</li>
	<li><a href="https://www.mapsmarker.com/pro-feature-import" target="_blank">support for CSV/XLS/XLSX/ODS imports and exports</a> (for bulk additions and bulk updates),</li>
	<li><a href="https://www.mapsmarker.com/pro-feature-webapp" target="_blank">mobile web app support</a> (for <a href="https://demo.mapsmarker.com/wp-content/plugins/leaflet-maps-marker-pro/leaflet-fullscreen.php?layer=1" target="_blank">fullscreen maps with optimized mobile viewport</a>),</li>
	<li><a href="https://www.mapsmarker.com/pro-feature-qrcode" target="_blank">support for QR codes with custom backgrounds</a>,</li>
	<li><a href="https://www.mapsmarker.com/pro-feature-adsense" target="_blank">Google Adsense for maps integration</a>,</li>
	<li><a href="https://www.mapsmarker.com/pro-feature-whitelabel" target="_blank">an option to whitelabel the plugin,</a></li>
	<li><a href="https://www.mapsmarker.com/pro-feature-minimaps" target="_blank">collapsible minimaps</a>,</li>
	<li><a href="https://www.mapsmarker.com/pro-feature-advanced-widget" target="_blank">advanced recent marker widgets</a>,</li>
	<li><a href="https://www.mapsmarker.com/mapsmarker-api" target="_blank" title="view API docs on mapsmarker.com">a fully-featured API</a>,</li>
	<li><a href="https://www.mapsmarker.com/docs/misc/translations/" target="_blank">translations for 34 languages</a>,</li>
	</ul>
	and <a href="https://www.mapsmarker.com/features" target="_blank" title="view features on mapsmarker.com">lots of other features</a>.
	<br/><br/>
	If you are interested, you can either test drive the plugin at <a href="https://demo.mapsmarker.com" target="_blank" title="test drive Maps Marker Pro on demo site">https://demo.mapsmarker.com</a>, <a href="https://www.mapsmarker.com/download-pro" target="_blank">download the current plugin-package</a> or start a free 30-day trial for ' . get_bloginfo('url') . ' by clicking the following button:
	<br/><br/>
	<a style="clear:both;" class="button button-primary" href="admin.php?page=cgmp_info&action=install_maps_marker_pro" ' . $install_active . '>' . $install_title . '</a>
	<br/><br/>
	The current "Comprehensive Google Map Plugin"-release also includes a new <a href="admin.php?page=cgmp_export">transfer feature</a> contributed by Robert from Mapsmarker.com, which allows you to automatically convert your current "Comprehensive Google Map Plugin" maps to "Maps Marker Pro" maps. If you have any questions about that feature or Maps Marker Pro in general, I am sure Robert <a href="https://www.mapsmarker.com/contact" target="_blank" title="open contact form on mapsmarker.com">will be glad to to answer them</a>.
	<br/><br/>
	Thanks a lot for your understanding & good luck!
	<br/><br/>
	Alex</div>';
} else {
	if ($action == 'install_maps_marker_pro') {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		add_filter( 'https_ssl_verify', '__return_false' ); //info: otherwise SSL error on localhost installs.
		add_filter( 'https_local_ssl_verify', '__return_false' ); //info: not sure if needed, added to be sure
		$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
		if (file_exists($lmm_pro_readme)) {
			if (!is_plugin_active('leaflet-maps-marker-pro/leaflet-maps-marker.php') ) {
				echo '<h3 style="font-size:23px;">Maps Marker Pro installation</h3>';
				echo '<div class="error" style="padding:10px;"><strong>You already downloaded "Maps Marker Pro" but did not activate the plugin yet!</strong>';
				if ( current_user_can( 'install_plugins' ) ) {
					echo sprintf(__('<br/>Please navigate to <a href="%1$s">Plugins / Installed Plugins</a> and activate the plugin "Maps Marker Pro".'), $admin_url . 'plugins.php');
				} else {
					echo sprintf(__('Please contact your administrator (%1s) to activate the plugin "Maps Marker Pro".'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=Please activate the plugin Maps Marker Pro">' . get_bloginfo('admin_email') . '</a>' );
				}
				echo '</div>';
			} else {
				echo '<h3>Maps Marker Pro installation</h3>';
				echo '<div class="updated" style="padding:10px;">"Maps Marker Pro" installation is finished, you can start <a style="text-decoration:underline;" href="admin.php?page=cgmp_export">transfering your maps</a>!</div>';
			}
		} else {
			$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin() );
			$upgrader->install( 'https://www.mapsmarker.com/download-pro' );
			//info: check if download was successful
			$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
			if (file_exists($lmm_pro_readme)) {
				echo '<p>' . 'Please activate the plugin Maps Marker Pro by clicking the link "Activate Plugin" above' . '</p>';
			} else {
				$dl_l = 'https://www.mapsmarker.com/download-pro';
				$dl_lt = 'www.mapsmarker.com/download-pro';
				echo '<p>' . sprintf('The pro plugin package could not be downloaded automatically. Please download the plugin from <a href="%1s">%2s</a> and upload it to the directory /wp-content/plugins on your server manually', $dl_l, $dl_lt) . '</p>';
			}
		}
	}
}
?>
