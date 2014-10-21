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

if ( !function_exists('cgmp_google_map_plugin_menu') ):
      function cgmp_google_map_plugin_menu() {
      		$hook = add_menu_page("Comprehensive Google Map", 'Google Map', 'activate_plugins', CGMP_HOOK, 'cgmp_parse_menu_html', CGMP_PLUGIN_IMAGES .'/google_map.png');
	  		add_action('admin_print_scripts-'.$hook, 'cgmp_google_map_tab_script');

            $hook = add_submenu_page(CGMP_HOOK, 'Documentation', 'Documentation', 'activate_plugins', CGMP_HOOK);
            add_action('admin_print_scripts-'.$hook, 'cgmp_google_map_tab_script');

            $hook = add_submenu_page(CGMP_HOOK, 'Shortcode Builder', 'Shortcode Builder', 'activate_plugins', 'cgmp-shortcodebuilder', 'cgmp_shortcodebuilder_callback' );
			add_action('admin_print_scripts-'.$hook, 'cgmp_google_map_tab_script');

            $hook = add_submenu_page(CGMP_HOOK, 'Saved Shortcodes', 'Saved Shortcodes', 'activate_plugins', 'cgmp-saved-shortcodes', 'cgmp_saved_shortcodes_callback' );
            add_action('admin_print_scripts-'.$hook, 'cgmp_google_map_tab_script');

            $hook = add_submenu_page(CGMP_HOOK, 'Settings', 'Settings', 'activate_plugins', 'cgmp-settings', 'cgmp_settings_callback' );
		   	add_action('admin_print_scripts-'.$hook, 'cgmp_google_map_tab_script');

            $hook = add_submenu_page(CGMP_HOOK, 'Notice of discontinuation', '<div style="background:#F99755;color:#000;padding:2px;line-height:1.2em;text-align:center;">Notice of plugin discontinuation</div>', 'activate_plugins', 'cgmp_info', 'cgmp_info_callback');
		   	add_action('admin_print_scripts-'.$hook, 'cgmp_google_map_tab_script');

            $hook = add_submenu_page(CGMP_HOOK, 'Transfer maps to Maps Marker Pro', '<div style="background:#F99755;color:#000;padding:2px;line-height:1.2em;text-align:center;">Transfer maps to Maps Marker Pro</div>', 'activate_plugins', 'cgmp_export', 'cgmp_export_callback');
		   	add_action('admin_print_scripts-'.$hook, 'cgmp_google_map_tab_script');
	  }
endif;

if ( !function_exists('cgmp_export_callback') ):
	function cgmp_export_callback() {
		include('cgmp-export.php');
	}
endif;

if ( !function_exists('cgmp_info_callback') ):
	function cgmp_info_callback() {
		include('info.php');
	}
endif;

if ( !function_exists('cgmp_settings_callback') ):

	function cgmp_settings_callback() {

		if (!current_user_can('activate_plugins'))  {
             	wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		if (isset($_POST['cgmp-save-settings']))  {
		    update_option(CGMP_DB_SETTINGS_BUILDER_LOCATION, $_POST['builder-under-post']);
		    update_option(CGMP_DB_SETTINGS_CUSTOM_POST_TYPES, $_POST['custom-post-types']);
		    update_option(CGMP_DB_SETTINGS_TINYMCE_BUTTON, $_POST['tinymce-button-in-editor']);
		    update_option(CGMP_DB_SETTINGS_PLUGIN_ADMIN_BAR_MENU, $_POST['plugin-admin-bar-menu']);
		    update_option(CGMP_DB_SETTINGS_MAP_SHOULD_FILL_VIEWPORT, $_POST['map-fill-viewport']);
            cgmp_show_message("Settings updated successfully!");
		}

        $template_values = array();
        $template_values = cgmp_populate_token_builder_under_post($template_values);
        $template_values = cgmp_populate_token_custom_post_types($template_values);
        $template_values = cgmp_populate_tiny_mce_button($template_values);
        $template_values = cgmp_populate_plugin_admin_bar_menu($template_values);
        $template_values = cgmp_populate_map_should_fill_viewport($template_values);
        $template_values["SUPPORT_DATA"] = cgmp_generate_support_data();
        echo cgmp_render_template_with_values($template_values, CGMP_HTML_TEMPLATE_PLUGIN_SETTINGS_PAGE);
	}

endif;


if ( !function_exists('cgmp_generate_support_data') ):
function cgmp_generate_support_data() {
    global $wpdb, $wp_version;
    $current_wp_theme = wp_get_theme();
    $published_post_count = wp_count_posts("post");
    $published_posts = $published_post_count->publish;

    $published_page_count = wp_count_posts("page");
    $published_pages = $published_page_count->publish;

    global $wpdb;
    $table = $wpdb->posts;

    // LIMIT 1000 should be more than enough, really who has a blog with 1000+ published content these days?
    $query = "SELECT $table.post_type FROM $table WHERE $table.post_type NOT IN ('post', 'page') AND $table.post_status = 'publish' LIMIT 1000";
    $published_results = $wpdb->get_results($query);
    $published_per_type = array();
    foreach ($published_results as $result) {
        if (!isset($published_per_type[$result->post_type])) {
            $published_per_type[$result->post_type] = 1;
        } else {
            $published_per_type[$result->post_type]++;
        }
    }

    $custom_types_count = "";
    foreach ($published_per_type as $type => $count) {
        $custom_types_count .= "<li>Published ".$type."s: ".$count."</li>";
     }

    $plugin_names = scandir(CGMP_PLUGIN_DIR."/..");
    $plugin_names = array_flip($plugin_names);

    return
    "<h4>Environment</h4>"
    ."<ul>"
    ."<li>PHP v".PHP_VERSION."</li>"
    ."<li>MySQL v".mysql_get_server_info($wpdb->dbh)."</li>"
    ."</ul>"
    ."<h4>WordPress</h4>"
    ."<ul>"
    ."<li>WordPress v".$wp_version."</li>"
    ."<li>Comprehensive Google Map Plugin v".CGMP_VERSION."</li>"
    ."<li>Theme: ".$current_wp_theme->Name . ", v" . $current_wp_theme->Version."</li>"
    ."<li>Published posts: ".$published_posts."</li>"
    ."<li>Published pages: ".$published_pages."</li>"
    .$custom_types_count
    ."</ul>"
    ."<h4>JavaScript</h4>"
    ."<ul>"
    ."<li>jQuery v".($GLOBALS['wp_scripts']->registered["jquery"]->ver).(isset($GLOBALS['wp_scripts']->registered["jquery"]->src) && trim($GLOBALS['wp_scripts']->registered["jquery"]->src) != "" ?  ", src: ".($GLOBALS['wp_scripts']->registered["jquery"]->src)."</li>" : "</li>")
    ."<li>jQuery Core v".($GLOBALS['wp_scripts']->registered["jquery-core"]->ver)."</li>"
    ."<li>jQuery UI Core v".($GLOBALS['wp_scripts']->registered["jquery-ui-core"]->ver)."</li>"
    .(isset($GLOBALS['wp_scripts']->registered["jquery-migrate"]) ?  "<li>jQuery Migrate v".($GLOBALS['wp_scripts']->registered["jquery-migrate"]->ver)."</li>" : "<li>jQuery Migrate is <b>not</b> installed</li>")
    ."</ul>"
    ."<h4>Plugins known to modify global WordPress query</h4>"
    ."<ul>"
    ."<li>Advanced Category Excluder plugin: ".(isset($plugin_names['advanced-category-excluder']) ? "<b>Installed</b>" : "Not installed")."</li>"
    ."<li>Category Excluder plugin: ".(isset($plugin_names['category-excluder']) ? "<b>Installed</b>" : "Not installed")."</li>"
    ."<li>Simply Exclude plugin: ".(isset($plugin_names['simply-exclude']) ? "<b>Installed</b>" : "Not installed")."</li>"
    ."<li>Ultimate Category Excluder plugin: ".(isset($plugin_names['ultimate-category-excluder']) ? "<b>Installed</b>" : "Not installed")."</li>"
    ."</ul>";
}
endif;

function cgmp_populate_token_builder_under_post($template_values) {
   $setting_builder_location = get_option(CGMP_DB_SETTINGS_BUILDER_LOCATION);                                        
   $yes_display_radio_btn = "";                                                                                      
   $no_display_radio_btn = "checked='checked'";                                                                      
   if (isset($setting_builder_location) && $setting_builder_location == "true") {                                    
      $no_display_radio_btn = "";                                                                                    
      $yes_display_radio_btn = "checked='checked'";                                                                  
   }                                                                                                                 
   $template_values["YES_DISPLAY_SHORTCODE_BUILDER_INPOST_TOKEN"] = $yes_display_radio_btn;                        
   $template_values["NO_DISPLAY_SHORTCODE_BUILDER_INPOST_TOKEN"] = $no_display_radio_btn;                            
   return $template_values;
}

function cgmp_populate_tiny_mce_button($template_values) {
    $setting_tiny_mce_button = get_option(CGMP_DB_SETTINGS_TINYMCE_BUTTON);
    $yes_enable_radio_btn = "checked='checked'";
    $no_enable_radio_btn = "";
    if (isset($setting_tiny_mce_button) && $setting_tiny_mce_button == "false") {
        $yes_enable_radio_btn = "";
        $no_enable_radio_btn = "checked='checked'";
    }
    $template_values["YES_ENABLED_TINYMCE_BUTTON_TOKEN"] = $yes_enable_radio_btn;
    $template_values["NO_ENABLED_TINYMCE_BUTTON_TOKEN"] = $no_enable_radio_btn;
    return $template_values;
}

function cgmp_populate_plugin_admin_bar_menu($template_values) {
    $setting_plugin_admin_bar_menu = get_option(CGMP_DB_SETTINGS_PLUGIN_ADMIN_BAR_MENU);
    $yes_enable_radio_btn = "checked='checked'";
    $no_enable_radio_btn = "";
    if (isset($setting_plugin_admin_bar_menu) && $setting_plugin_admin_bar_menu == "false") {
        $yes_enable_radio_btn = "";
        $no_enable_radio_btn = "checked='checked'";
    }
    $template_values["YES_ENABLED_PLUGIN_ADMIN_BAR_MENU_TOKEN"] = $yes_enable_radio_btn;
    $template_values["NO_ENABLED_PLUGIN_ADMIN_BAR_MENU_TOKEN"] = $no_enable_radio_btn;
    return $template_values;
}

function cgmp_populate_map_should_fill_viewport($template_values) {
    $setting_map_fill_viewport = get_option(CGMP_DB_SETTINGS_MAP_SHOULD_FILL_VIEWPORT);
    $yes_enable_radio_btn = "";
    $no_enable_radio_btn = "checked='checked'";
    if (isset($setting_map_fill_viewport) && $setting_map_fill_viewport == "true") {
        $yes_enable_radio_btn = "checked='checked'";
        $no_enable_radio_btn = "";
    }
    $template_values["YES_ENABLED_MAP_FILL_VIEWPORT_TOKEN"] = $yes_enable_radio_btn;
    $template_values["NO_ENABLED_MAP_FILL_VIEWPORT_TOKEN"] = $no_enable_radio_btn;
    return $template_values;
}

function cgmp_populate_token_custom_post_types($template_values) {
   $custom_post_types = get_option(CGMP_DB_SETTINGS_CUSTOM_POST_TYPES);                                           
   $template_values["CUSTOM_POST_TYPES_TOKEN"] = $custom_post_types;                             
   return $template_values;                 
} 

if ( !function_exists('cgmp_shortcodebuilder_callback') ):

	function cgmp_shortcodebuilder_callback() {

		if (!current_user_can('activate_plugins'))  {
             	wp_die( __('You do not have sufficient permissions to access this page.') );
        }

        if (isset($_POST['hidden-shortcode-code']))  {

            $bad_entities = array("&quot;", "&#039;", "'");
            $title = str_replace($bad_entities, "", $_POST['hidden-shortcode-title']);
            $title = preg_replace('/\s+/', ' ', trim($title));
            $code = str_replace($bad_entities, "", $_POST['hidden-shortcode-code']);

            $shortcodes = array();

            $persisted_shortcodes_json = get_option(CGMP_PERSISTED_SHORTCODES);
            if (isset($persisted_shortcodes_json) && trim($persisted_shortcodes_json) != "") {
                $persisted_shortcodes = json_decode($persisted_shortcodes_json, true);
                if (is_array($persisted_shortcodes)) {
                    $persisted_shortcodes[$title] = array("title" => $title, "code" => $code);
                    $shortcodes = $persisted_shortcodes;
                }
            } else {
                $shortcodes[$title] = array("title" => $title, "code" => $code);
            }

            update_option(CGMP_PERSISTED_SHORTCODES, json_encode($shortcodes));

            cgmp_show_message("Shortcode save successfully!");
            //cgmp_show_message("Look for the map icon&nbsp;<img src='".CGMP_PLUGIN_IMAGES."/google_map.png' border='0' valign='middle' />&nbsp;in WordPress page/post WYSIWYG editor or check <a href='admin.php?page=cgmp-saved-shortcodes'>Saved Shortcodes</a> page");
        }

        $settings = array();
        $json_string = file_get_contents(CGMP_PLUGIN_DATA_DIR."/".CGMP_JSON_DATA_HTML_ELEMENTS_FORM_PARAMS);
        $parsed_json = json_decode($json_string, true);

        if (is_array($parsed_json)) {
            foreach ($parsed_json as $data_chunk) {
                cgmp_set_values_for_html_rendering($settings, $data_chunk);
            }
        }

        $template_values = cgmp_build_template_values($settings);
        $template_values['SHORTCODEBUILDER_FORM_TITLE'] = cgmp_render_template_with_values($template_values, CGMP_HTML_TEMPLATE_SHORTCODE_BUILDER_FORM_TITLE);
        $template_values['SHORTCODEBUILDER_HTML_FORM'] = cgmp_render_template_with_values($template_values, CGMP_HTML_TEMPLATE_SHORTCODE_BUILDER_HTML_FORM);
        $map_configuration_template = cgmp_render_template_with_values($template_values, CGMP_HTML_TEMPLATE_MAP_CONFIGURATION_FORM);

		echo cgmp_render_template_with_values(array("CGMP_PLUGIN_IMAGES" => CGMP_PLUGIN_IMAGES, "SHORTCODEBUILDER_TOKEN" => $map_configuration_template), CGMP_HTML_TEMPLATE_MAP_SHORTCODE_BUILDER_PAGE);
	}
endif;

if ( !function_exists('cgmp_saved_shortcodes_callback') ):

    function cgmp_saved_shortcodes_callback() {
        if (!current_user_can('activate_plugins'))  {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }

        if (isset($_REQUEST['delete_shortcode']) && trim($_REQUEST['delete_shortcode']) != "")  {
            $title = $_REQUEST['delete_shortcode'];
            $persisted_shortcodes_json = get_option(CGMP_PERSISTED_SHORTCODES);
            if (isset($persisted_shortcodes_json) && trim($persisted_shortcodes_json) != "") {
                $persisted_shortcodes = json_decode($persisted_shortcodes_json, true);
                if (is_array($persisted_shortcodes)) {
                    if (isset($persisted_shortcodes[$title])) {
                        unset($persisted_shortcodes[$title]);
                        update_option(CGMP_PERSISTED_SHORTCODES, json_encode($persisted_shortcodes));
                        cgmp_show_message("Shortcode deleted successfully!");
                    } else {
                        cgmp_show_message("Could not deleted shortcode!", true);
                    }
                }
            }
        }

        $template_values = array();
        $template_values["CGMP_PLUGIN_IMAGES"] = CGMP_PLUGIN_IMAGES;
        $template_values["SAVED_SHORTCODES_TOKEN"] = "No shortcodes found in the database.";

        $persisted_shortcodes_json = get_option(CGMP_PERSISTED_SHORTCODES);
        if (isset($persisted_shortcodes_json) && trim($persisted_shortcodes_json) != "") {
            $persisted_shortcodes = json_decode($persisted_shortcodes_json, true);
            if (is_array($persisted_shortcodes) && !empty($persisted_shortcodes)) {
                $content = "";
                foreach ($persisted_shortcodes as $shortcode) {
                    $shortcode_id = substr(md5(rand()), 0, 10);
                    if (is_array($shortcode)) {
                        $raw_code = $shortcode['code'];
                        $raw_code = str_replace("TO_BE_GENERATED", $shortcode_id, $raw_code);

                        $content .= "<div style='line-height: 15px; min-height: 20px; height: 20px; width: 70%; padding: 0; margin: 0'>";
                        $content .= "Title: <span style='color: green;'><b>".$shortcode['title']."</b></span>";
                        /* broken with WP 4.0
						$content .= "&nbsp;&nbsp;&nbsp;";
                        $content .= "<a id='".$shortcode['title']."' href='javascript:void(0)' class='insert-shortcode-to-post'>[insert to post]</a>";
						*/
                        $content .= "&nbsp;&nbsp;&nbsp;";
                        $content .= "<a href='javascript:void(0)' onclick='return confirmShortcodeDelete(\"admin.php?page=cgmp-saved-shortcodes&delete_shortcode=".$shortcode['title']."\", \"".$shortcode['title']."\");'>";
                        $content .= "<img src='".CGMP_PLUGIN_IMAGES."/close.png' border='0' valign='middle' /></a>";
                        $content .= "</div>";
                        $content .= "<div class='loaded-db-shortcodes'><b>".stripslashes($raw_code) . "</b></div><br />";
                    }
                }
                $template_values["SAVED_SHORTCODES_TOKEN"] = $content;
            }
        }

        echo cgmp_render_template_with_values($template_values, CGMP_HTML_TEMPLATE_PLUGIN_SAVED_SHORTCODES_PAGE);
    }
endif;


if ( !function_exists('cgmp_parse_menu_html') ):
function cgmp_parse_menu_html() {
      if (!current_user_can('activate_plugins'))  {
                wp_die( __('You do not have sufficient permissions to access this page.') );
        }

		$json_html_doco_params = cgmp_fetch_json_data_file(CGMP_JSON_DATA_HTML_ELEMENTS_DOCO_PARAMS);

		if (is_array($json_html_doco_params)) {
            $json_html_doco_params['SHORTCODEBUILDER_FORM_TITLE'] = "";
			$map_configuration_form_template = cgmp_render_template_with_values($json_html_doco_params, CGMP_HTML_TEMPLATE_MAP_CONFIGURATION_FORM);
			$template_values = array();
        	$template_values["DOCUMENTATION_TOKEN"] = $map_configuration_form_template;

        	echo cgmp_render_template_with_values($template_values, CGMP_HTML_TEMPLATE_MAP_CONFIG_DOCUMENTATION_PAGE);
		}
}
endif;

?>
