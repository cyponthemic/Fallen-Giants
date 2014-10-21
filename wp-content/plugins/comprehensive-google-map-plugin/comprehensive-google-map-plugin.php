<?php
/*
Plugin Name: Comprehensive Google Map Plugin
Plugin URI: http://wordpress.org/support/plugin/comprehensive-google-map-plugin
Description: A simple and intuitive, yet elegant and fully documented Google map plugin that installs as a widget and a short code. The plugin is packed with useful features. Widget and shortcode enabled. Offers extensive configuration options for markers, over 250 custom marker icons, marker Geo mashup, controls, size, KML files, location by latitude/longitude, location by address, info window, directions, traffic/bike lanes and more. 
Version: 9.1.2
Author: Alex Zagniotov
Author URI: http://wordpress.org/support/plugin/comprehensive-google-map-plugin
License: GPLv2

Copyright (C) 2011-09/2014  Alexander Zagniotov

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

if ( is_admin() ) {
	$cgmp_transient = get_transient( 'cgmp_update_routine' );
	if ( $cgmp_transient === FALSE ) {
		set_transient( 'cgmp_update_routine', 'execute only once a week', 60*60*24*7 );
		//info: options to hide notices
		$cgmp_defaults = array(
			'admin_notice' => 'show',
			'plugin_notice' => 'show',
			'metabox_notice' => 'show',
			'export_notice' => 'show'
		);
		add_option('cgmp_options', $cgmp_defaults );

		//info: copy map icons to wp-content/uploads
		require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php');
		WP_Filesystem();
		$cmpg_upload_dir = wp_upload_dir();
		define ("CMPG_PLUGIN_ICONS_DIR", $cmpg_upload_dir['basedir'] . DIRECTORY_SEPARATOR . "leaflet-maps-marker-icons");
		define ("CMPG_PLUGIN_DIR", plugin_dir_path(__FILE__));
		$target = CMPG_PLUGIN_ICONS_DIR;
		
		if ( !file_exists(CMPG_PLUGIN_ICONS_DIR . DIRECTORY_SEPARATOR . '1-default.png') )
		{
			wp_mkdir_p( $target );
			$source = CMPG_PLUGIN_DIR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'markers' . DIRECTORY_SEPARATOR . 'zip';
			copy_dir($source, $target, $skip_list = array() );
			$zipfile = CMPG_PLUGIN_ICONS_DIR . DIRECTORY_SEPARATOR . 'cgmp-markers.zip';
			unzip_file( $zipfile, $target );
			//info: fallback for hosts where copying zipfile to LEAFLET_PLUGIN_ICON_DIR doesnt work
			if ( !file_exists(CMPG_PLUGIN_ICONS_DIR . DIRECTORY_SEPARATOR . '1-default.png') ) {
				if (class_exists('ZipArchive')) {
					$zip = new ZipArchive;
					$res = $zip->open( CMPG_PLUGIN_DIR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'markers' . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . 'cgmp-markers.zip');
					if ($res === TRUE) {
						$zip->extractTo(CMPG_PLUGIN_ICONS_DIR);
						$zip->close();
					}
				}
			}
		}

	}
}

if ( !function_exists('cgmp_admin_notice') ):
	$cgmp_options = get_option('cgmp_options');
	function cgmp_admin_notice() {
		$cgmp_options = get_option('cgmp_options');
		$page = (isset($_GET['page']) ? $_GET['page'] : '');
		$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
		$whitelistpages = array('cgmp_info','cgmp_export');
		if(!in_array($page, $whitelistpages)){
			if (file_exists($lmm_pro_readme)) {
				if (!is_plugin_active('leaflet-maps-marker-pro/leaflet-maps-marker.php') ) {
					if ($cgmp_options['admin_notice'] == 'show') { 
						$admin_url = get_admin_url();
						echo '<div class="updated" style="padding:10px;"><div style="float:right;padding-top:18px;"><a href="admin.php?page=cgmp_info&admin_notice=hide">hide message</a></div><strong>Comprehensive Google Map plugin info:</strong><br/>You already downloaded "Maps Marker Pro" but did not activate the plugin yet!';
						if ( current_user_can( 'install_plugins' ) ) {
							echo '<br/>Please navigate to <a href="' . $admin_url . 'plugins.php">Plugins / Installed Plugins</a> and activate the plugin "Maps Marker Pro".';
						} else {
							echo '<br/>' . sprintf(__('Please contact your administrator (%1s) to activate the plugin "Maps Marker Pro".'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=Please activate the plugin Maps Marker Pro">' . get_bloginfo('admin_email') . '</a>' );
						}
						echo '</div>';
					}
				} else { //info: MMPro active
					if ($cgmp_options['export_notice'] == 'show') { 
						if (get_option('leafletmapsmarkerpro_license_key') != NULL) {
							$page = (isset($_GET['page']) ? $_GET['page'] : '');
							if ($page != 'cgmp_export') {
								echo '<div class="updated" style="padding:10px;"><div style="float:right;padding-top:10px;"><a href="admin.php?page=cgmp_export&export_notice=hide">hide message</a></div><strong>Comprehensive Google Map plugin info:</strong><br/>';
								echo '"Maps Marker Pro" installation is finished, you can start <a href="admin.php?page=cgmp_export">transfering your maps</a>!</div>';
							}
						} else {
							//info: dont display 2 admin notices (MMPro admin notice for finishing installation already gets displayed)
						}
					}
				}
			} else { //info: (!file_exists($lmm_pro_readme))
				if ($cgmp_options['admin_notice'] == 'show') {
					echo '<p><div class="error" id="cgmp_admin_notice" style="padding:10px;margin-top:35px;"><div style="float:right;margin-top:7px;"><a href="admin.php?page=cgmp_info&admin_notice=hide">hide message</a></div><strong>Attention: the development and maintenance of the "Comprehensive Google Map Plugin" has been discontinued!</strong><br/>A switch to the mapping plugin "Maps Marker Pro" is recommended - <a href="admin.php?page=cgmp_info">please click here for more information</a></div></p>';
				}
			}
		}
	}
	add_action('admin_notices', 'cgmp_admin_notice');
endif;

if ( !function_exists('cgmp_define_constants') ):
	function cgmp_define_constants() {
		define('CGMP_PLUGIN_BOOTSTRAP', __FILE__ );
		define('CGMP_PLUGIN_DIR', dirname(CGMP_PLUGIN_BOOTSTRAP));
		define('CGMP_PLUGIN_URI', plugin_dir_url(CGMP_PLUGIN_BOOTSTRAP));

		$json_constants_string = file_get_contents(CGMP_PLUGIN_DIR."/data/plugin.constants.json");
		$json_constants = json_decode($json_constants_string, true);
		$json_constants = $json_constants[0];

		if (is_array($json_constants)) {
			foreach ($json_constants as $constant_key => $constant_value) {
				$constant_value = str_replace("CGMP_PLUGIN_DIR", CGMP_PLUGIN_DIR, $constant_value);
				$constant_value = str_replace("CGMP_PLUGIN_URI", CGMP_PLUGIN_URI, $constant_value);
				define($constant_key, $constant_value);
			}
		}
	}
endif;

if ( !function_exists('cgmp_require_dependancies') ):
	function cgmp_require_dependancies() {
		require_once (CGMP_PLUGIN_DIR . '/functions.php');
		require_once (CGMP_PLUGIN_DIR . '/widget.php');
		require_once (CGMP_PLUGIN_DIR . '/shortcode.php');
		require_once (CGMP_PLUGIN_DIR . '/metabox.php');
		require_once (CGMP_PLUGIN_DIR . '/admin-menu.php');
        require_once (CGMP_PLUGIN_DIR . '/admin-bar-menu.php');
		require_once (CGMP_PLUGIN_DIR . '/head.php');
	}
endif;

if ( !function_exists('cgmp_register_hooks') ):
	function cgmp_register_hooks() {
		register_activation_hook( CGMP_PLUGIN_BOOTSTRAP, 'cgmp_on_activate_hook');
	}
endif;

if ( !function_exists('cgmp_add_actions') ):
	function cgmp_add_actions() {
		//http://scribu.net/wordpress/optimal-script-loading.html
		add_action('init', 'cgmp_google_map_register_scripts');
		add_action('init', 'cgmp_load_plugin_textdomain');
		add_action('admin_notices', 'cgmp_show_message');
		add_action('admin_init', 'cgmp_google_map_admin_add_style');
		add_action('admin_init', 'cgmp_google_map_admin_add_script');
		add_action('admin_footer', 'cgmp_google_map_init_global_admin_html_object');
		add_action('admin_menu', 'cgmp_google_map_plugin_menu');

        if ( is_admin() ) {
            $setting_plugin_menu_bar_menu = get_option(CGMP_DB_SETTINGS_PLUGIN_ADMIN_BAR_MENU);
            if (!isset($setting_plugin_menu_bar_menu) || (isset($setting_plugin_menu_bar_menu) && $setting_plugin_menu_bar_menu != "false")) {
                add_action('admin_bar_menu', 'cgmp_admin_bar_menu', 99999);
            }
        }

		add_action('widgets_init', create_function('', 'return register_widget("ComprehensiveGoogleMap_Widget");'));
		add_action('wp_head', 'cgmp_google_map_deregister_scripts', 200);
		add_action('wp_head', 'cgmp_generate_global_options');

        if ( is_admin() ) {
			global $wp_version;
            $setting_tiny_mce_button = get_option(CGMP_DB_SETTINGS_TINYMCE_BUTTON);
            if (!isset($setting_tiny_mce_button) || (isset($setting_tiny_mce_button) && $setting_tiny_mce_button != "false")) {
                if (cgmp_should_load_admin_scripts()) {
                    if (version_compare($wp_version,"3.9","<")){
						add_action('init', 'cgmp_register_mce');
					}
                    add_action('wp_ajax_cgmp_mce_ajax_action', 'cgmp_mce_ajax_action_callback');
                }
            }
        }

        add_action('wp_ajax_nopriv_cgmp_ajax_cache_map_action', 'cgmp_ajax_cache_map_action_callback');
        add_action('wp_ajax_cgmp_ajax_cache_map_action', 'cgmp_ajax_cache_map_action_callback');
        add_action('wp_ajax_cgmp_insert_shortcode_to_post_action', 'cgmp_insert_shortcode_to_post_action_callback');

        add_action('save_post', 'cgmp_save_post_hook' );
        add_action('save_page', 'cgmp_save_page_hook' );

        add_action('publish_post', 'cgmp_publish_post_hook' );
        add_action('publish_page', 'cgmp_publish_page_hook' );

        add_action('deleted_post', 'cgmp_deleted_post_hook' );
        add_action('deleted_page', 'cgmp_deleted_page_hook' );

        add_action('publish_to_draft', 'cgmp_publish_to_draft_hook' );
	}
endif;

if ( !function_exists('cgmp_add_shortcode_support') ):
	function cgmp_add_shortcode_support() {
		add_shortcode('google-map-v3', 'cgmp_shortcode_googlemap_handler');
	}
endif;

if ( !function_exists('cgmp_add_filters') ):
	function cgmp_add_filters() {
		add_filter( 'widget_text', 'do_shortcode');
		add_filter( 'plugin_row_meta', 'cgmp_plugin_row_meta', 10, 2 );
        add_filter( 'plugin_action_links', 'cgmp_plugin_action_links', 10, 2 );
	}
endif;

global $cgmp_global_map_language;
$cgmp_global_map_language = "en";

/* BOOTSTRAPPING STARTS */
cgmp_define_constants();
cgmp_require_dependancies();
cgmp_add_actions();
cgmp_register_hooks();
cgmp_add_shortcode_support();
cgmp_add_filters();
/* BOOTSTRAPPING ENDS */

?>
