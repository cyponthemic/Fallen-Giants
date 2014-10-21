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
@ini_set('memory_limit', '96M');
if(isset($_POST['cgmp-reset-export'])):
    global $wpdb;
     $shortcodes =  get_transient('cgmp_layers_markers_export');
 if($shortcodes !== false){    
     foreach ($shortcodes as $shortcode) {
         if(isset($shortcode['layer'])){
            if(isset($shortcode['layer']['new_id']) AND $shortcode['layer']['new_id']!='')
                $wpdb->query('DELETE FROM '.$wpdb->prefix.'leafletmapsmarker_layers WHERE id='.$shortcode['layer']['new_id']);
         }
         if(isset($shortcode['markers'])){
                 foreach ($shortcode['markers'] as $marker) {
                    if(isset($marker['new_id']) AND $marker['new_id']!='')
                        $wpdb->query('DELETE FROM '.$wpdb->prefix.'leafletmapsmarker_markers WHERE id='.$marker['new_id']);

                       
                    }
        }
     }
}
     delete_transient('cgmp_layers_markers_export');

endif;

if (!function_exists('add_action')) {
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
}

define('MMP_API_URL', get_option('siteurl') . '/wp-content' . '/plugins/leaflet-maps-marker-pro/leaflet-api.php');
require 'lib/spherical-geometry.class.php';
require ('export-functions.php');

$cgmp_export_notice = isset($_GET['export_notice']) ? $_GET['export_notice'] : '';
$cgmp_options = get_option('cgmp_options');
if ($cgmp_export_notice != NULL) {
    $cgmp_options['export_notice'] = 'hide';
    update_option('cgmp_options', $cgmp_options);
    echo '<div class="updated" style="padding:10px;margin:10px 0;">"Comprehensive Google Map Plugin" options have been updated!</div>';
}

echo '<h3>Transfer maps to Maps Marker Pro</h3>';

// check if the user installed MMP
$admin_url = get_admin_url();
if (get_option('leafletmapsmarkerpro_license_key') == NULL) {
    $lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
    if (!file_exists($lmm_pro_readme)) {
        echo '<div class="error" style="padding:10px;">The plugin "Maps Marker Pro" needs to be installed first before you are able to transfer your maps!<br/>';
        if (current_user_can('install_plugins')) {
            echo __('<a href="admin.php?page=cgmp_info&action=install_maps_marker_pro">Please click here to directly download and install the plugin "Maps Marker Pro".</a>');
        } else {
            echo sprintf(__('Please contact your administrator (%1s) to install the plugin "Maps Marker Pro" from this page.'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=Please install and activate the plugin Maps Marker Pro">' . get_bloginfo('admin_email') . '</a>');
        }
        echo '</div>';
    } else {
        if (!is_plugin_active('leaflet-maps-marker-pro/leaflet-maps-marker.php')) {
            echo '<div class="error" style="padding:10px;"><strong>You already downloaded "Maps Marker Pro" but did not activate the plugin yet!</strong>';
            if (current_user_can('install_plugins')) {
                echo sprintf(__('<br/>Please navigate to <a href="%1$s">Plugins / Installed Plugins</a> and activate the plugin "Maps Marker Pro" before you can start the transfer.'), $admin_url . 'plugins.php');
            } else {
                echo sprintf(__('Please contact your administrator (%1s) to activate the plugin "Maps Marker Pro".'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=Please activate the plugin Maps Marker Pro">' . get_bloginfo('admin_email') . '</a>');
            }
            echo '</div>';
        } else {
            if (get_option('leafletmapsmarkerpro_license_key') == NULL) {
                echo '<div class="error" style="padding:10px;">Please <a href="' . $admin_url . 'admin.php?page=leafletmapsmarker_license">activate a valid "Maps Marker Pro" license</a> to be able to start the transfer!</div>';
            } else {
                
                //info: nothing needed to show/do if as code below is executed
                
            }
        }
    }
} else {
    $stored_shortcodes = get_transient('cgmp_layers_markers_export');
    if ($stored_shortcodes === false) {
        
        // extract shortcodes and display them.
        $shortcodes_data = cgmp_get_posts_shortcodes();
        if (!$shortcodes_data['results']) {
            $template_values['TRANSFERE_BUTTON'] = '';
            $template_values['RESET_BUTTON'] = '';
        } else {
            
            // Button 'Start Transfere'
            $template_values['TRANSFERE_BUTTON'] = "<input type='submit' onclick='' style='float:left;margin-top:8px;' class='button-primary alignleft' tabindex='4' value='step 1/2: create \"Maps Marker Pro\" maps' id='cgmp-save-settings' name='cgmp-export-maps' />";
             $template_values['RESET_BUTTON'] = '';
   
        }
    } else {
        $shortcodes_data = cgmp_get_posts_shortcodes($stored_shortcodes);
        
        // 'Replace Button'
        $template_values['TRANSFERE_BUTTON'] = "<input type='submit' onclick='return confirm(\"Please click OK if you want to replace all existing CGMP shortcodes (please note that these can only be restored from a backup!)\")' style='float:left;margin-top:8px;' class='button-primary alignleft' tabindex='4' value='step 2/2: replace existing CGMP shortcodes' id='cgmp-save-settings' name='cgmp-replace-shortcodes' />";
        $template_values['RESET_BUTTON'] = "<input type='submit' style='float:right;margin-top:8px;' class='button-secondary alignleft' tabindex='4' value='or reset transfer and start again' id='cgmp-save-settings' name='cgmp-reset-export' />";
    }
    
    $template_values['EXPORTED_MSG'] = '';
    
    // Check if the user requests the Export Function and process it.
    if (isset($_POST['cgmp-export-maps']) && !empty($shortcodes_data['shortcodes'])) {
        
        // Enable the MMP API
        $mapsmarkerapi_options = get_option('leafletmapsmarker_options');
        $mapsmarkerapi_options['api_status'] = 'enabled';
        $mapsmarkerapi_options['api_key'] = 'cgmptransfer';
        $mapsmarkerapi_options['api_key_private'] = 'cgmptransferprivatekey';
        update_option('leafletmapsmarker_options', $mapsmarkerapi_options);
        
        // Calculate the signature
        
        $api_key = $mapsmarkerapi_options['api_key'];
        $private_key = $mapsmarkerapi_options['api_key_private'];
        $expires = strtotime("+60 mins");
        
        $string_to_sign = sprintf("%s:%s", $api_key, $expires);
        $string_to_sign = $api_key . ":" . $expires;
        
        $sig = calculate_signature($string_to_sign, $private_key);
        
        $export = cgmp_export_to_api($shortcodes_data['shortcodes'], $api_key, $sig, $expires);
        
        //passing the generated markers
        $shortcodes_data = cgmp_get_posts_shortcodes($export);
        
        if ($export !== FALSE) {
            
            //save the generated data to prevent duplication
            set_transient('cgmp_layers_markers_export', $export, 60 * 60 * 24 * 7);
            $template_values['EXPORTED_MSG'] = '
                         <div id="message" class="updated"><p><strong>
Your maps have been successfully created in "Maps Marker Pro"!</strong><br/>
As next step it is recommended to review those maps and replace the old shortcode in your content with the new one.
<br/><br/>
You can also automatically replace all existing shortcodes by clicking the button "step 2/2: replace existing CGMP shortcodes".
<br/>

<strong>Anyway it is strongly advised to check your maps before and make a database backup or at least an export of your current content from <a style="text-decoration:underline;" href="' . admin_url() . 'export.php">(tools->export)</a>,  as the CGMP shortcodes can only be restored from a a backup!</strong>
</p></div>';
            $template_values['TRANSFERE_BUTTON'] = "<input type='submit' onclick='return confirm(\"Please click OK if you want to replace all existing CGMP shortcodes (please note that these can only be restored from a backup!)\")' style='float:left;margin-top:8px;' class='button-primary alignleft' tabindex='4' value='step 2/2: replace existing CGMP shortcodes' id='cgmp-save-settings' name='cgmp-replace-shortcodes' />";
                 $template_values['RESET_BUTTON'] = "<input type='submit' style='float:right;margin-top:8px;' class='button-secondary alignleft' tabindex='4' value='or reset transfer and start again' id='cgmp-save-settings' name='cgmp-reset-export' />"; 
  
        }
    }
    
    $template_values['POSTS_WITH_SHORTCODES'] = '<p>You are currently using the following shortcodes:</p>
<table cellspacing="0" cellpadding="0" >
                    <thead>
                        <th>CGMP shortcode</th>
                        <th style="width:20%">Link to Maps Marker Pro map</th>
                         <th style="width:20%">Maps Marker Pro Shortcode</th> 
                         <th style="width:15%">Link to content where shortcode is used</th>  
                    </thead>
                    <tbody>
                        ' . $shortcodes_data['results'] . '
                    </tbody>
                </table>
';
    
    // AUTO REPLACE ACTION

    if (isset($_POST['cgmp-replace-shortcodes'])):
         $template_values['EXPORTED_MSG'] = '<div class="updated" id="message"> Transfer is finished - all shortcodes from the "comprehensive google map plugin" have been replaced with "Maps Marker Pro" shortcodes. You can now disable and optionally delete the "comprehensive google map plugin".</div>';
        $autoreplace = cgmp_autoreplace_shortcodes($stored_shortcodes);
        $template_values['POSTS_WITH_SHORTCODES'] = $autoreplace;
        $template_values['TRANSFERE_BUTTON'] = "";
        
        delete_transient('cgmp_layers_markers_export');
    endif;
    
    // Assign shortcodes table
    
    echo cgmp_render_template_with_values($template_values, 'page_admin_menu_export.tpl');
}

?>
