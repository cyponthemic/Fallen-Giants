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

add_action('admin_menu', 'cgmp_google_map_meta_boxes');

if ( !function_exists('cgmp_google_map_meta_boxes') ):
function cgmp_google_map_meta_boxes() {
		$id = "google_map_shortcode_builder";
		$cgmp_options = get_option('cgmp_options');
		if ($cgmp_options['metabox_notice'] == 'show') {
			$title = 'AZ :: Google Map Shortcode Builder<div style="padding:2px 2px 2px 4px;background:#F99755;color:#000;"><div style="float:right;margin-top:9px;"><a href="admin.php?page=cgmp_info&metabox_notice=hide" style="font-size:1em;">hide message</a></div><strong>Attention: the development and maintenance of the "Comprehensive Google Map Plugin" has been discontinued!</strong><br/>A switch to the mapping plugin "Maps Marker Pro" is recommended - <a href="admin.php?page=cgmp_info" style="font-size:1em;">please click here for more information</a></div>';
		} else {
			$title = 'AZ :: Google Map Shortcode Builder';
		}
		$context = "normal";

      $setting_builder_location = get_option(CGMP_DB_SETTINGS_BUILDER_LOCATION);
      if (isset($setting_builder_location) && $setting_builder_location == "true") {                                          
         add_meta_box($id, $title, 'cgmp_render_shortcode_builder_form', 'post', $context, 'high');                        
         add_meta_box($id, $title, 'cgmp_render_shortcode_builder_form', 'page', $context, 'high');                                                            
      }

      $custom_post_types = get_option(CGMP_DB_SETTINGS_CUSTOM_POST_TYPES);
      if (isset($custom_post_types) && trim($custom_post_types) != "") {
         $custom_post_types_arr = explode(",", $custom_post_types);
         foreach ($custom_post_types_arr as $type) {
            $type = trim(strtolower($type));
            if ($type == 'page' || $type == 'post') {
               continue;
            }
            add_meta_box($id, $title, 'cgmp_render_shortcode_builder_form', $type, $context, 'high');
         }
      }
}
endif;


if ( !function_exists('cgmp_render_shortcode_builder_form') ):
function cgmp_render_shortcode_builder_form() {

    $settings = array();
    $json_string = file_get_contents(CGMP_PLUGIN_DATA_DIR."/".CGMP_JSON_DATA_HTML_ELEMENTS_FORM_PARAMS);
    $parsed_json = json_decode($json_string, true);

    if (is_array($parsed_json)) {
        foreach ($parsed_json as $data_chunk) {
            cgmp_set_values_for_html_rendering($settings, $data_chunk);
        }
    }

    $template_values = cgmp_build_template_values($settings);
    $template_values['SHORTCODEBUILDER_FORM_TITLE'] = "";
    $template_values['SHORTCODEBUILDER_HTML_FORM'] = "";
    $map_configuration_template = cgmp_render_template_with_values($template_values, CGMP_HTML_TEMPLATE_MAP_CONFIGURATION_FORM);

    $tokens_with_values = array("MAP_CONFIGURATION_FORM_TOKEN" => $map_configuration_template);
    echo cgmp_render_template_with_values($tokens_with_values, CGMP_HTML_TEMPLATE_MAP_SHORTCODE_BUILDER_METABOX);
}
endif;

?>
