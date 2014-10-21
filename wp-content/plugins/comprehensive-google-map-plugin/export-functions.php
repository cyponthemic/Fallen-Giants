<?php

///////
if (!function_exists('cgmp_get_posts_shortcodes')):
    
    function cgmp_get_posts_shortcodes($markers_data = array()) {
        
        
        $saved_shortcodes = cgmp_get_saved_shortcodes();
        $args = array('post_type' => get_post_types(array('public' => true)), 's' => '[google-map-v3', 'post_status' => 'any');
        
        $posts = get_posts($args);
        
        //if(empty($post)) return __("You are not using any shortcodes.");
        
        $pattern = get_shortcode_regex();
        
        $shortcodes = array();
        $results = '';
        $warnned_results = '';
        $i = 0;
        
        $shortcodes_count = 0;
        $marker_count = 0;
        foreach ($posts as $post):
            
            preg_match_all('/' . $pattern . '/s', $post->post_content, $matches, PREG_SET_ORDER);
            if (is_array($matches)) {
                $marker_counter = 0;
                foreach ($matches as $match) {
                    $mapMarkerLink = '';
                    $new_shortcodes = '';
                    
                    // retrieving the markers and layers links
                    
                    if ($match[2] == 'google-map-v3') {
                        $shortcodes_count++;
                        if (!empty($markers_data[$shortcodes_count]['layer'])) {
                            $mapMarkerLink = '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_layer&id=' . $markers_data[$shortcodes_count]['layer']['new_id'] . '" target="_blank">review layer ID ' . $markers_data[$shortcodes_count]['layer']['new_id'] . '</a><br/><br/><br/>';
                            
                            $new_shortcodes = '[mapsmarker layer="' . $markers_data[$shortcodes_count]['layer']['new_id'] . '"]';
                        }
                      
                          if(isset($markers_data[$shortcodes_count]['layer']['mixed_addresses'])){
                                $mapMarkerLink.= '
                                           <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                            Warning: please verify map center and zoom level as mixed address formats were used that could not be processed automatically!
                                           </strong></p></div>
                                       ';
                            }
                        
                        if (!empty($markers_data[$shortcodes_count]['markers'][0]) and empty($markers_data[$shortcodes_count]['layer'])) {
                            $mapMarkerLink.= '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_marker&id=' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '" target="_blank">review marker ID ' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '</a><br/>';
                            
                            $new_shortcodes.= '[mapsmarker marker="' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '"]';
                        }


                        if(isset($markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'])){
                            if ($markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1) {
                                $mapMarkerLink = '
                                        <div id="message" style="float:right;padding:0 10px;background:red;"><p><strong style="padding:3px;color:white;">Error: geocoding failed - please review the map and manually correct the address of the CGMP shortcode!</strong></p></div>
                                    ';
                                $new_shortcodes = '';
                            }
                        }

                        if(isset($markers_data[$shortcodes_count]['markers'][0]['has_styles'])){
                            if ($markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1) {
                                $mapMarkerLink.= '
                                       <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                        Warning: Map styles can only be set globally within Maps Marker Pro at <a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_settings#lmm-google-section6">Settings / Google Maps / Google Maps styling</a> and will not be transfered on a single map level!
                                       </strong></p></div>
                                   ';
                            }
                        }

                        if(isset($markers_data[$shortcodes_count]['markers'][0]['has_kml'])){
                            if ($markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1) {
                                $mapMarkerLink.= '
                                       <div id="message" style="margin-top:5px;float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                        Warning: your CGMP shortcode links to a KML or GeoRSS file which is currently not yet supported within Maps Marker Pro and cannot be transfered!
                                       </strong></p></div>
                                   ';
                            } 
                        }

                        if ($mapMarkerLink == '') {
                            $mapMarkerLink = 'not yet available';
                            $new_shortcodes = 'not yet available';
                        }
                        
                        $marker_counter++;
                        
                        if ((isset($markers_data[$shortcodes_count]['markers'][0]['has_kml']) AND $markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1) 
                            || (isset($markers_data[$shortcodes_count]['markers'][0]['has_styles']) AND $markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1 )
                            || (isset($markers_data[$shortcodes_count]['markers'][0]['geolocation_failed']) AND   $markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1)) {
                            $warnned_results.= '<tr>';
                            
                            $warnned_results.= '<td class="shortcode">' . $match[0] . '</td>';
                            $warnned_results.= '<td>' . $mapMarkerLink . '<br/></td>';
                            $warnned_results.= '<td>' . $new_shortcodes . '</td>';
                            $warnned_results.= '<td><a href="' . get_edit_post_link($post->ID) . '" target="_blank">' . __('Edit') . '</a></td>';
                            
                            $warnned_results.= '</tr>';
                        } else {
                            
                            $results.= '<tr>';
                            
                            $results.= '<td class="shortcode">' . $match[0] . '</td>';
                            $results.= '<td>' . $mapMarkerLink . '<br/></td>';
                            $results.= '<td>' . $new_shortcodes . '</td>';
                            $results.= '<td><a href="' . get_edit_post_link($post->ID) . '" target="_blank">' . __('Edit') . '</a></td>';
                            
                            $results.= '</tr>';
                        }
                        $shortcodes[$i]['attributes'] = shortcode_parse_atts($match[0]);
                        $shortcodes[$i]['post_id'] = $post->ID;
                        if($saved_shortcodes != NULL){
                                $saved_shortcode_exist = array_search(cgmp_remove_shortcodeid($match[0]), array_map('cgmp_remove_shortcodeid', $saved_shortcodes));
                            if ($saved_shortcode_exist !== FALSE) {
                                unset($saved_shortcodes[$saved_shortcode_exist]);
                            }
                        }
                        
                        $i++;
                    }
                }
            }
        endforeach;
        
        // Extracting shortcodes from Widgets
        $widgets = get_option('widget_comprehensivegooglemap');
        
        if (!empty($widgets)):
            foreach ($widgets as $w_key => $widget):
                $shortcode = '[google-map-v3 ';
                if (is_array($widget)) {
                    $shortcodes_count++;
                    $mapMarkerLink = '';
                    $new_shortcodes = '';
                    
                    // Building the widget shortcode to display it.
                    foreach ($widget as $key => $value) {
                        $shortcode.= ' ' . $key . '="' . $value . '"';
                    }
                    $shortcode.= ']';
                    
                    //Mapping the difference between widgets atts and shortcodes atts.
                    $widget['enablemarkerclustering'] = $widget['enablemarkerclusteringhidden'];
                    $widget['addmarkerlist'] = $widget['addmarkerlisthidden'];
                    
                    // retrieving the markers and layers links
                    if (!empty($markers_data[$shortcodes_count]['layer'])) {
                        $mapMarkerLink = '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_layer&id=' . $markers_data[$shortcodes_count]['layer']['new_id'] . '" target="_blank">review layer ID ' . $markers_data[$shortcodes_count]['layer']['new_id'] . '</a><br/><br/><br/>';
                        
                        $new_shortcodes = '[mapsmarker layer="' . $markers_data[$shortcodes_count]['layer']['new_id'] . '"]';
                    }
                    if(isset($markers_data[$shortcodes_count]['layer']['mixed_addresses'])){
                      
                        $mapMarkerLink.= '
                                   <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: please verify map center and zoom level as mixed address formats were used that could not be processed automatically!
                                   </strong></p></div>
                               ';
                    }
                    
                    if (!empty($markers_data[$shortcodes_count]['markers'][0]) and empty($markers_data[$shortcodes_count]['layer'])) {
                        $mapMarkerLink.= '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_marker&id=' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '" target="_blank">review marker ID ' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '</a><br/>';
                        
                        $new_shortcodes.= '[mapsmarker marker="' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '"]';
                    }
                    
                    if ($markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1) {
                        $mapMarkerLink = '
                                    <div id="message" style="float:right;padding:0 10px;background:red;"><p><strong style="padding:3px;color:white;">Error: geocoding failed - please review the map and manually correct the address of the CGMP shortcode!</strong></p></div>
                                ';
                        $new_shortcodes = '';
                    }
                    
                    if ($markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1) {
                        $mapMarkerLink.= '
                                   <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: Map styles can only be set globally within Maps Marker Pro at <a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_settings#lmm-google-section6">Settings / Google Maps / Google Maps styling</a> and will not be transfered on a single map level!
                                   </strong></p></div>
                               ';
                    }
                    if ($markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1) {
                        $mapMarkerLink.= '
                                   <div id="message" style="margin-top:5px;float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: your CGMP shortcode links to a KML or GeoRSS file which is currently not yet supported within Maps Marker Pro and cannot be transfered!
                                   </strong></p></div>
                               ';
                    }
                    if ($mapMarkerLink == '') {
                        $mapMarkerLink = 'not yet available';
                        $new_shortcodes = 'not yet available';
                    }
                    
                    if ($markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1 || $markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1 || $markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1) {
                        
                        $warnned_results.= '<tr>';
                        
                        $warnned_results.= '<td class="shortcode">' . $shortcode . '</td>';
                        $warnned_results.= '<td>' . $mapMarkerLink . '</td>';
                        $warnned_results.= '<td>' . $new_shortcodes . '</td>';
                        $warnned_results.= '<td><a href="' . admin_url('widgets.php') . '" target="_blank">' . __('Edit') . '</a></td>';
                        $warnned_results.= '</tr>';
                    } else {
                        
                        $results.= '<tr>';
                        
                        $results.= '<td class="shortcode">' . $shortcode . '</td>';
                        $results.= '<td>' . $mapMarkerLink . '</td>';
                        $results.= '<td>' . $new_shortcodes . '</td>';
                        $results.= '<td><a href="' . admin_url('widgets.php') . '" target="_blank">' . __('Edit') . '</a></td>';
                        $results.= '</tr>';
                    }
                    $shortcodes[$i]['attributes'] = $widget;
                    $shortcodes[$i]['post_id'] = $post->ID;
                    $shortcodes[$i]['post_type'] = 'cgmp_widget';
                    $shortcodes[$i]['widget_id'] = $w_key;
                    $shortcodes[$i]['widget_type'] = 'comprehensivegooglemap';
                    $shortcodes[$i]['widget_title'] = $widget['title'];
                    if( $saved_shortcodes != NULL){
                         $saved_shortcode_exist = array_search($shortcode, $saved_shortcodes);
                            if ($saved_shortcode_exist !== FALSE) {
                                unset($saved_shortcodes[$saved_shortcode_exist]);
                            }
                    }
                   
                    
                    $i++;
                }
            endforeach;
        endif;
        
        // Extracting shortcodes from Text Widgets
        $widgets = get_option('widget_text');
        
        if (!empty($widgets)):
            foreach ($widgets as $key => $widget):
                
                preg_match('/' . $pattern . '/s', $widget['text'], $matches);
                
                if (isset($matches[2]) AND ($matches[2] == 'google-map-v3')):
                    
                    $shortcodes_count++;
                    $mapMarkerLink = '';
                    $new_shortcodes = '';
                    
                    // retrieving the markers and layers links
                    
                    if (!empty($markers_data[$shortcodes_count]['layer'])) {
                        $mapMarkerLink = '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_layer&id=' . $markers_data[$shortcodes_count]['layer']['new_id'] . '" target="_blank">review layer ID ' . $markers_data[$shortcodes_count]['layer']['new_id'] . '</a><br/><br/><br/>';
                        $new_shortcodes = '[mapsmarker layer="' . $markers_data[$shortcodes_count]['layer']['new_id'] . '"]';
                    }
                      if(isset($markers_data[$shortcodes_count]['layer']['mixed_addresses'])){
                        $mapMarkerLink.= '
                                   <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: please verify map center and zoom level as mixed address formats were used that could not be processed automatically!
                                   </strong></p></div>
                               ';
                    }
                    if (!empty($markers_data[$shortcodes_count]['markers'][0]) and empty($markers_data[$shortcodes_count]['layer'])) {
                        $mapMarkerLink.= '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_marker&id=' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '" target="_blank">review marker ID ' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '</a><br/>';
                        
                        $new_shortcodes.= '[mapsmarker marker="' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '"]';
                    }
                    
                    if ($markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1) {
                        $mapMarkerLink = '
                                    <div id="message" style="float:right;padding:0 10px;background:red;"><p><strong style="padding:3px;color:white;">Error: geocoding failed - please review the map and manually correct the values before automatically replacing the shortcodes!</strong></p></div>
                                ';
                        $new_shortcodes = '';
                    }
                    if ($markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1) {
                        $mapMarkerLink.= '
                                   <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: Map styles can only be set globally within Maps Marker<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_settings#lmm-google-section6">Settings / Google Maps / Google Maps styling</a> and will not be transfered on a single map level!
                                   </strong></p></div>
                               ';
                    }
                    if ($markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1) {
                        $mapMarkerLink.= '
                                   <div id="message" style="margin-top:5px;float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: your CGMP shortcode links to a KML or GeoRSS file which is currently not yet supported within Maps Marker Pro and cannot be transfered!
                                   </strong></p></div>
                               ';
                    }
                    if ($mapMarkerLink == '') {
                        $mapMarkerLink = 'not yet available';
                        $new_shortcodes = 'not yet available';
                    }
                    
                    if ($markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1 || $markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1 || $markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1) {
                        $warnned_results.= '<tr>';
                        
                        $warnned_results.= '<td class="shortcode">' . $matches[0] . '</td>';
                        $warnned_results.= '<td>' . $mapMarkerLink . '</td>';
                        $warnned_results.= '<td>' . $new_shortcodes . '</td>';
                        $warnned_results.= '<td><a href="' . admin_url('widgets.php') . '" target="_blank">' . __('Edit') . '</a></td>';
                        $warnned_results.= '</tr>';
                    } else {
                        $results.= '<tr>';
                        
                        $results.= '<td class="shortcode">' . $matches[0] . '</td>';
                        $results.= '<td>' . $mapMarkerLink . '</td>';
                        $results.= '<td>' . $new_shortcodes . '</td>';
                        $results.= '<td><a href="' . admin_url('widgets.php') . '" target="_blank">' . __('Edit') . '</a></td>';
                        $results.= '</tr>';
                    }
                    
                    $shortcodes[$i]['attributes'] = shortcode_parse_atts($matches[0]);
                    $shortcodes[$i]['post_id'] = $post->ID;
                    $shortcodes[$i]['post_type'] = 'text_widget';
                    $shortcodes[$i]['widget_type'] = 'text';
                    $shortcodes[$i]['widget_id'] = $key;
                    $shortcodes[$i]['widget_title'] = $widget['title'];
                    if($saved_shortcodes != NULL){
                        $saved_shortcode_exist = array_search($matches[0], $saved_shortcodes);
                        if ($saved_shortcode_exist !== FALSE) {
                            unset($saved_shortcodes[$saved_shortcode_exist]);
                        }
                    }
                    
                    
                    $i++;
                endif;
            endforeach;
        endif;
        
        // PROCESS REMAINING SAVED SHORTCODES
        if (!empty($saved_shortcodes)):
            foreach ($saved_shortcodes as $shortcode):
                
                $shortcodes_count++;
                $mapMarkerLink = '';
                $new_shortcodes = '';
                
                // retrieving the markers and layers links
                
                if (!empty($markers_data[$shortcodes_count]['layer'])) {
                    $mapMarkerLink = '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_layer&id=' . $markers_data[$shortcodes_count]['layer']['new_id'] . '" target="_blank">review layer ID ' . $markers_data[$shortcodes_count]['layer']['new_id'] . '</a><br/><br/><br/>';
                    $new_shortcodes = '[mapsmarker layer="' . $markers_data[$shortcodes_count]['layer']['new_id'] . '"]';
                }

                  if(isset($markers_data[$shortcodes_count]['layer']['mixed_addresses'])){
                        $mapMarkerLink.= '
                                   <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: please verify map center and zoom level as mixed address formats were used that could not be processed automatically!
                                   </strong></p></div>
                               ';
                    }
                
                if (!empty($markers_data[$shortcodes_count]['markers'][0]) and empty($markers_data[$shortcodes_count]['layer'])) {
                    $mapMarkerLink.= '<a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_marker&id=' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '" target="_blank">review marker ID ' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '</a><br/>';
                    
                    $new_shortcodes.= '[mapsmarker marker="' . $markers_data[$shortcodes_count]['markers'][0]['new_id'] . '"]';
                }
                
                if ($markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1) {
                    $mapMarkerLink = '
                                    <div id="message" style="float:right;padding:0 10px;background:red;"><p><strong style="padding:3px;color:white;">Error: geocoding failed - please review the map and manually correct the values before automatically replacing the shortcodes!</strong></p></div>
                                ';
                    $new_shortcodes = '';
                }
                
                if ($markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1) {
                    $mapMarkerLink.= '
                                   <div id="message" style="float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: Map styles can only be set globally within Maps Marker Pro at <a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_settings#lmm-google-section6">Settings / Google Maps / Google Maps styling</a> and will not be transfered on a single map level!
                                   </strong></p></div>
                               ';
                }
                if ($markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1) {
                    $mapMarkerLink.= '
                                   <div id="message" style="margin-top:5px;float:right;padding:0 10px;background:orange;"><p><strong style="padding:3px;color:white;">
                                    Warning: your CGMP shortcode links to a KML or GeoRSS file which is currently not yet supported within Maps Marker Pro and cannot be transfered!
                                   </strong></p></div>
                               ';
                }
                if ($mapMarkerLink == '') {
                    $mapMarkerLink = 'not yet available';
                    $new_shortcodes = 'not yet available';
                }
                
                if ($markers_data[$shortcodes_count]['markers'][0]['has_kml'] == 1 || $markers_data[$shortcodes_count]['markers'][0]['has_styles'] == 1 || $markers_data[$shortcodes_count]['markers'][0]['geolocation_failed'] == 1) {
                    $warnned_results.= '<tr>';
                    
                    $warnned_results.= '<td class="shortcode">' . $shortcode . '</td>';
                    $warnned_results.= '<td>' . $mapMarkerLink . '</td>';
                    $warnned_results.= '<td>' . $new_shortcodes . '</td>';
                    $warnned_results.= '<td>not used within posts, pages, custom post types or widgets</td>';
                    $warnned_results.= '</tr>';
                } else {
                    $results.= '<tr>';
                    
                    $results.= '<td class="shortcode">' . $shortcode . '</td>';
                    $results.= '<td>' . $mapMarkerLink . '</td>';
                    $results.= '<td>' . $new_shortcodes . '</td>';
                    $results.= '<td>not used within posts, pages, custom post types or widgets</td>';
                    $results.= '</tr>';
                }
                
                $shortcodes[$i]['attributes'] = shortcode_parse_atts($shortcode);
                $shortcodes[$i]['post_id'] = '';
                $shortcodes[$i]['post_type'] = 'saved_shortcode';
                
                $i++;
            endforeach;
        endif;
        
        return array('results' => $warnned_results . $results, 'shortcodes' => $shortcodes);
    }
endif;

if (!function_exists('cgmp_export_to_api')):
    
    function cgmp_export_to_api($shortcodes = array(), $api_key, $sig, $expires) {
       
        if (!empty($shortcodes)) {
            global $current_user;
            get_currentuserinfo();
            $results = array();
            $layers = array();
            $markers = array();
            $layer_count = 0;
            $marker_count = 0;
            $results_count = 0;
            $mashup_maps = array();
            
            foreach ($shortcodes as $shortcode):
                
                $results_count++;
                $mark = array();
                $markerlist = explode('|', $shortcode['attributes']['addmarkerlist']);
                $addmarkerlist = (is_array($markerlist) && !empty($markerlist)) ? $markerlist : array($shortcode['attributes']['addmarkerlist']);

                // Calculate the boundbox
                if (is_array($addmarkerlist) && !empty($addmarkerlist)) {
                    $lats = array();
                    $lons = array();
                    foreach ($addmarkerlist as $marker):
                        
                        $marker = explode('{}', $marker);
                        $address = cgmp_analyse_address($marker[0]);
                        if (isset($address['lat'])) {
                            $lats[] = $address['lat'];
                        }
                        if (isset($address['lon'])) {
                            $lons[] = $address['lon'];
                        }
                    endforeach;

                    
                   
                    if (class_exists('LatLngBounds')) {
                        if ((!empty($lats) && !empty($lons))) {
                            $LatLngSw = new LatLng(min($lats), min($lons));
                            $LatLngNe = new LatLng(max($lats), max($lons));
                            
                            $layercenter = new LatLngBounds($LatLngSw, $LatLngNe);
                            $layerviewlat = $layercenter->getCenter()->getLat();
                            $layerviewlon = $layercenter->getCenter()->getLng();

                            $zoomlevel = getBoundsZoomLevel(array('width' => $shortcode['attributes']['width'], 'height' => $shortcode['attributes']['height']), $LatLngSw, $LatLngNe);
                           
                        }
                    }
                }
                 
                // if addmarkerlist contains more than 1 value in, an according entry in layers.csv has to be created.
                if ((is_array($addmarkerlist) && count($addmarkerlist) > 1) OR ($shortcode['attributes']['addmarkermashup'] == 'true')) {
                    
                    $geocode = '';
                    if (!is_null($address['address'])) {
                        
                        // use the caching before geocoding
                        //
                        if (in_array($shortcode['post_type'], array('cgmp_widget', 'text_widget'))) {
                            $post_type = 'widget_map';
                            $shortcode_id = $shortcode['widget_type'] . '-' . $shortcode['widget_id'];
                        } else {
                            $post_type = get_post_type($shortcode['post_id']);
                            $shortcode_id = $shortcode['post_id'];
                        }
                        if (!in_array($post_type, array('page', 'post', 'widget_map'))) {
                            $post_type = 'custom_map';
                        }
                        if ($shortcode['attributes']['addmarkermashup'] == 'true') {
                            $post_type = 'mashup_map';
                        }
                        
                        if ($post_type != false) {
                         
                            $cached_address = get_option('cgmp_cache_' . $post_type . '_' . $shortcode_id . (($shortcode['attributes']['shortcodeid'] == '') ? '' : '_' . $shortcode['attributes']['shortcodeid']));
                            
                            if ($cached_address !== FALSE) {
                                $cached_address = explode('{}', $cached_address);
                                
                                $latlon = explode(',', $cached_address[3]);
                                
                                if (is_numeric(trim($latlon[0]))) {
                                    
                                    //use the cached
                                    $address['lat'] = trim($latlon[0]);
                                    $address['lon'] = trim($latlon[1]);
                                    $cache_used = true;
                                } else {
                                    
                                    // use geocoding
                                    $geocode = $address['address'];
                                }
                            }
                        }
                    }
                    
                    $layers[$layer_count] = array('id' => '', 'name' => ($shortcode['attributes']['shortcodeid'] == 'TO_BE_GENERATED') ? '' : $shortcode['attributes']['shortcodeid'], 'address' => '', 'geocode' => cgmp_accent_folding($geocode), 'layerviewlat' => $layerviewlat,
                    
                    //all lat+lon values from a CGMP shortcode
                    'layerviewlon' => $layerviewlon,
                    
                    //all lat+lon values from a CGMP shortcode
                    'layerzoom' => ($zoomlevel) ? $zoomlevel : 12,
                    
                    // Needs tests if all markers added to a layer are visible!
                    
                    'mapwidth' => $shortcode['attributes']['width'], 'mapwidthunit' => (strpos($shortcode['attributes']['width'], '%') === FALSE) ? 'px' : '%',
                    
                    //Use px if width value contains only digits and use % if width value contains %
                    
                    'mapheight' => $shortcode['attributes']['height'], 'basemap' => cgmp_get_the_basemap($shortcode['attributes']['maptype']), 'panel' => 1, 'clustering' => ($shortcode['attributes']['enablemarkerclustering'] == 'true') ? 1 : 0, 'listmarkers' => 0, 'multi_layer_map' => 0, 'multi_layer_map_list' => 0, 'controlbox' => 1, 'createdby' => $current_user->user_login, 'createdon' => current_time('mysql', 0), 'updatedby' => $current_user->user_login, 'updatedon' => current_time('mysql', 0), 'overlays_custom' => 0, 'overlays_custom2' => 0, 'overlays_custom3' => 0, 'overlays_custom4' => 0, 'wms' => 0, 'wms2' => 0, 'wms3' => 0, 'wms4' => 0, 'wms5' => 0, 'wms6' => 0, 'wms7' => 0, 'wms8' => 0, 'wms9' => 0, 'wms10' => 0,
                    
                    //Only map value if url contains gpx otherwise leave empty
                    
                    'gpx_url' => (strpos(strtolower($shortcode['attributes']['kml']), 'gpx') === FALSE) ? '' : $shortcode['attributes']['kml'], 'gpx_panel' => 0);
                    
                    // ADD THE MASHUP LAYER
                    
                    if ($shortcode['attributes']['addmarkermashup'] == 'true') {
                        $layers[$layer_count]['mashup'] = true;
                        $mashup_maps[$results_count] = $shortcode['attributes'];  
                      
                    } else {
                        
                        // send the layer to the API
                        $response = wp_remote_get(MMP_API_URL . '?key=' . $api_key . '&signature=' . $sig . '&expires=' . $expires . '&action=add&type=layer&' . http_build_query($layers[$layer_count]));
                        
                        if (is_array($response)) {
                            
                            $layer_response = jsonp_decode($response['body']);
                        }else{
                            if ($shortcode['attributes']['addmarkermashup'] != 'true') {
                                $layers[$layer_count]['geolocation_failed'] = true;
                            }
                        }
                    }
                      
                    
                    if((isset($lats)) AND isset($shortcode['attributes']['addmarkerlist']) AND (    count($lats) < count($addmarkerlist))){
                         
                        $layers[$layer_count]['mixed_addresses'] = true;
                    }
                    $layers[$layer_count]['new_id'] = $layer_response->data->id;
                    $layers[$layer_count]['post_id'] = $shortcode['post_id'];
                    $layers[$layer_count]['shortcode_type'] = ($shortcode['post_type']) ? $shortcode['post_type'] : 'post';
                    $layers[$layer_count]['widget_type'] = ($shortcode['widget_type']) ? $shortcode['widget_type'] : '';
                    $layers[$layer_count]['widget_id'] = $shortcode['widget_id'];
                    $layers[$layer_count]['has_styles'] = ($shortcode['attributes']['styles'] != '') ? true : false;
                    $layers[$layer_count]['has_kml'] = (strpos(strtolower($shortcode['attributes']['kml']), 'gpx') === FALSE AND $shortcode['attributes']['kml'] != '') ? true : false;
                    
                    if ($layer_response->success == false) {
                        if($shortcode['attributes']['addmarkermashup']!='true'){
                            $layers[$layer_count]['geolocation_failed'] = true;
                        }
                        
                    }
                    
                    $results[$results_count]['layer'] = $layers[$layer_count];
                    
                    $layer_count++;
                }
                
                if (is_array($addmarkerlist) && !empty($addmarkerlist) && $shortcode['attributes']['addmarkermashup']!='true') {
                    $newlats = array();
                    $markers_counter = 0;
                    foreach ($addmarkerlist as $marker):
                        
                        $marker = explode('{}', $marker);
                        $address = cgmp_analyse_address($marker[0]);
                        $geocode = '';
                        
                        if (!is_null($address['address'])) {
                            
                            // use the caching before geocoding
                            $p_type = (isset($shortcode['post_type']))?$shortcode['post_type']:'';
                            if (in_array($p_type, array('cgmp_widget', 'text_widget'))) {
                                $post_type = 'widget_map';
                                $shortcode_id = $shortcode['widget_type'] . '-' . $shortcode['widget_id'];
                            } else {
                                $post_type = get_post_type($shortcode['post_id']);
                                $shortcode_id = $shortcode['post_id'];
                            }
                            if (!in_array($post_type, array('page', 'post', 'widget_map'))) {
                                $post_type = 'custom_map';
                            }
                            if ($shortcode['attributes']['addmarkermashup'] == 'true') {
                                $post_type = 'mashup_map';
                            }
                            
                            if ($post_type != '') {
                                $cached_address = get_option('cgmp_cache_' . $post_type . '_' . $shortcode_id . (($shortcode['attributes']['shortcodeid'] == '') ? '' : '_' . $shortcode['attributes']['shortcodeid']));
                                
                                if ($cached_address !== FALSE) {
                                    $temp_cached_address = explode('|', $cached_address);
                                    $cached_address = explode('{}', $temp_cached_address[$markers_counter]);
                                    $markers_counter++;
                                    
                                    $latlon = explode(',', $cached_address[3]);
                                    
                                    if (is_numeric(trim($latlon[0]))) {
                                        
                                        //use the cached
                                        $address['lat'] = trim($latlon[0]);
                                        $address['lon'] = trim($latlon[1]);
                                        $newlats[] = 1;
                                     
                                        $cache_used = true;
                                        $geocode = '';
                                    } else {
                                        
                                        // use geocoding
                                        $geocode = $address['address'];
                                    }
                                } else {
                                    $geocode = $address['address'];
                                }
                            }
                        }

                        
                        $markers[] = array('id' => '', 'markername' => (is_null($address['address'])) ? '' : $address['address'], 'popuptext' => (isset($marker[2]))?$marker[2]:'', 'openpopup' => 0, 'address' => (is_null($address['address'])) ? '' : $address['address'], 'geocode' => cgmp_accent_folding($geocode), 'lat' => (isset($address['lat']))?$address['lat']:'', 'lon' => (isset($address['lon']))?$address['lon']:'', 'layer' => (count($addmarkerlist) == 1) ? 0 : $layer_response->data->id,
                        
                        /// GETING BACK TO IT
                        'zoom' => 12, 'icon' => $marker[1], 'mapwidth' => $shortcode['attributes']['width'], 'mapwidthunit' => (strpos($shortcode['attributes']['width'], '%') === FALSE) ? 'px' : '%', 'mapheight' => $shortcode['attributes']['height'], 'basemap' => cgmp_get_the_basemap($shortcode['attributes']['maptype']), 'panel' => 1, 'controlbox' => 1, 'createdby' => $current_user->user_login, 'createdon' => current_time('mysql', 0), 'updatedby' => $current_user->user_login, 'updatedon' => current_time('mysql', 0), 'kml_timestamp' => '', 'overlays_custom' => 0, 'overlays_custom2' => 0, 'overlays_custom3' => 0, 'overlays_custom4' => 0, 'wms' => 0, 'wms2' => 0, 'wms3' => 0, 'wms4' => 0, 'wms5' => 0, 'wms6' => 0, 'wms7' => 0, 'wms8' => 0, 'wms9' => 0, 'wms10' => 0, 'gpx_url' => (strpos(strtolower((isset($shortcode['attributes']['kml']))?$shortcode['attributes']['kml']:''), 'gpx') === FALSE) ? '' : $shortcode['attributes']['kml'], 'gpx_panel' => 0);
                        
                        // ADD THE MASHUP LAYER
                        
                      //  if ($shortcode['attributes']['addmarkermashup'] == 'true') {
                           // $markers[$marker_count]['mashup'] = true;
                           // array_push($mashup_maps, $shortcode['attributes']);
                      //  } else {
                            
                            // send the marker to the API
                            
                            $response = wp_remote_get(MMP_API_URL . '?key=' . $api_key . '&signature=' . $sig . '&expires=' . $expires . '&action=add&type=marker&' . http_build_query($markers[$marker_count]));
                          
                            if(is_array($response)){
                                 $marker_response = jsonp_decode($response['body']);
                            }else{
                                 $markers[$marker_count]['geolocation_failed'] = true;
                            }
                           
                            
                            if ($marker_response->success == false) {
                                $markers[$marker_count]['geolocation_failed'] = true;
                            }
                        //}
                        
                        $markers[$marker_count]['new_id'] = (isset($marker_response->data->id))?$marker_response->data->id:'';
                        $markers[$marker_count]['post_id'] = $shortcode['post_id'];
                        $markers[$marker_count]['shortcode_type'] = (isset($shortcode['post_type'])) ? $shortcode['post_type'] : 'post';
                        $markers[$marker_count]['widget_type'] = (isset($shortcode['widget_type'])) ? $shortcode['widget_type'] : '';
                        $markers[$marker_count]['widget_id'] = (isset($shortcode['widget_id']))?$shortcode['widget_id']:'';
                        $markers[$marker_count]['has_styles'] = (isset($shortcode['attributes']['styles']) AND $shortcode['attributes']['styles'] != '') ? true : false;
                        $markers[$marker_count]['has_kml'] = (strpos(strtolower( (isset($shortcode['attributes']['kml']))?$shortcode['attributes']['kml']:'' ), 'gpx') === FALSE AND (isset($shortcode['attributes']['kml']) AND $shortcode['attributes']['kml'] != '')) ? true : false;
                        
                        $results[$results_count]['markers'][] = $markers[$marker_count];
                        
                        $marker_count++;
                    endforeach;
                        
                        if((count($newlats)+count($lats)) == count($addmarkerlist) or count($newlats)==0){
                         unset($results[$results_count]['layer']['mixed_addresses']);
                        }
                }
                
                // increment layer_id by 1 just if there is more than marker in the shortcode
                if (count($addmarkerlist) > 1 OR $shortcode['attributes']['addmarkermashup'] == 'true') $layer_id++;
            endforeach;
        }
        
        // Process the mashup layers
        if (!empty($mashup_maps)):
             
            foreach ($mashup_maps as $key => $mashup) {
               
                $boundbox = cgmp_calculate_boundbox_from_db($mashup['width'], $mashup['height']);
                
                $mashup_layer['name'] = 'Marker Geo Mashup';
                $mashup_layer['layerzoom'] = ($boundbox['layerzoom']) ? $boundbox['layerzoom'] : '2';
                $mashup_layer['layerviewlat'] = ($boundbox['layerviewlat']) ? $boundbox['layerviewlat'] : '26';
                $mashup_layer['layerviewlon'] = ($boundbox['layerviewlon']) ? $boundbox['layerviewlon'] : '-2';
              
                $mashup_layer['mapwidth'] = $mashup['width'];
                $mashup_layer['mapheight'] = $mashup['height'];
                $mashup_layer['mapwidthunit'] = (strpos($mashup['width'], '%') === FALSE) ? 'px' : '%';
                $mashup_layer['clustering'] = ($mashup['enablemarkerclustering'] == 'true') ? 1 : 0;
                $mashup_layer['multi_layer_map'] = '1';
                $mashup_layer['multi_layer_map_list'] = 'all';
				$mashup_layer['overlays_custom'] = '0';
				$mashup_layer['overlays_custom2'] = '0';
				$mashup_layer['overlays_custom3'] = '0';
				$mashup_layer['overlays_custom4'] = '0';
				$mashup_layer['wms'] = '0';
				$mashup_layer['wms2'] = '0';
				$mashup_layer['wms3'] = '0';
				$mashup_layer['wms4'] = '0';
				$mashup_layer['wms5'] = '0';
				$mashup_layer['wms6'] = '0';
				$mashup_layer['wms7'] = '0';
				$mashup_layer['wms8'] = '0';
				$mashup_layer['wms9'] = '0';
				$mashup_layer['wms10'] = '0';
                $response = wp_remote_get(MMP_API_URL . '?key=' . $api_key . '&signature=' . $sig . '&expires=' . $expires . '&action=add&type=layer&' . http_build_query($mashup_layer));
                
                if (is_array($response )) {
                            
                           $mashup_response = jsonp_decode($response['body']);
                            //$layers[$key]['new_id'] = $mashup_response->data->id;
                            $results[$key]['layer']['new_id'] = $mashup_response->data->id;
                    
                            
               }
              

            }
        endif;
        
        // Disable the API after the transfere
        $mapsmarkerapi_options = get_option('leafletmapsmarker_options');
        $mapsmarkerapi_options['api_status'] = 'disabled';
        $mapsmarkerapi_options['api_key'] = '';
        $mapsmarkerapi_options['api_key_private'] = '';
        update_option('leafletmapsmarker_options', $mapsmarkerapi_options);
        
        foreach ($layers as $layer) {
             
            //calculate the boundbox from the database
            if ($layer['layerviewlat'] == '' and $layer['layerviewlon'] == '' and !isset($layer['mashup'])) {
               
                $boundbox = cgmp_calculate_boundbox_from_db($layer['mapwidth'], $layer['mapheight'], $layer['new_id']);
                
                global $wpdb;
                $wpdb->update($wpdb->prefix . 'leafletmapsmarker_layers', $boundbox, array('id' => $layer['new_id']));
            }
        }
       
        return $results;
    }
endif;

if (!function_exists('cgmp_get_the_basemap')):
    function cgmp_get_the_basemap($maptype = '') {
        if (trim($maptype) == '') return '';
        
        return ($maptype == 'OSM') ? 'osm_mapnik' : 'googleLayer_' . strtolower($maptype);
    }
endif;

if (!function_exists('cgmp_enclouse_csv')):
    function cgmp_enclouse_csv($input) {
        $input = str_replace(array("\r", "\n"), '', $input);
        $input = str_replace('"', "'", $input);
        
        //escaping in csv files is done by doing the same quote twice, odd
        return '"' . $input . '"';
    }
endif;

if (!function_exists('cgmp_analyse_address')):
    
    /**
     * [cgmp_analyse_address addmarkerlist can have multiple values assigned! Leave empty if 2 digits separated by comma or semi- colon are used ]
     * @param  [string] $address
     * @return [array] 0 => address 1 => lat 2 => lon
     */
    function cgmp_analyse_address($address) {
       
        $result['address'] = $address;
        $check_comma = explode(',', $address);
        $check_comma = array_map('trim', $check_comma);
        if (is_numeric($check_comma[0])) {
            $result['address'] = NULL;
            $result['lat'] = $check_comma[0];
            $result['lon'] = $check_comma[1];
        }
        $check_semicolon = explode(';', $address);
        if (is_numeric($check_semicolon[0])) {
            $result['address'] = NULL;
            $result['lat'] = $check_semicolon[0];
            $result['lon'] = $check_semicolon[1];
        }
        //strpos(trim($check_comma[0]), ' ') !== FALSE
        if ((  strpos(trim($check_comma[0]),' ') !== FALSE AND (is_numeric(str_replace(' ', '',trim( $check_comma[0]))))  ) OR strpos(trim($check_comma[0]), '°') !== FALSE) {
            
            //check DMS
            $directions = array('N', 'W', 'E', 'S', 'n', 'w', 'e', 's');
            if (in_array($check_comma[0][0], $directions)) {
                $direction = $check_comma[0][0];
            }
            if($direction == ''){
                if(in_array($check_comma[0][strlen($check_comma[0])-1], $directions)){
                    $direction = $check_comma[0][strlen($check_comma[0])-1];
                }
            }
           
            $lat = str_replace($directions, '', $check_comma[0]);
            $lat = str_replace('°', ' ', $lat);
            $lat = explode(' ', trim($lat));
            
            $result['lat'] = DMS2Decimal($lat[0], $lat[1], $lat[2], $direction);
            
            //$lat[0]+((($lat[1]*60)+($lat[2]))/3600);
            $direction = '';
            if (in_array($check_comma[1][0], $directions)) {
                $direction = $check_comma[1][0];
            }
            if($direction == ''){
                if (in_array($check_comma[1][strlen($check_comma[1])-1], $directions)) {
                      $direction = $check_comma[1][strlen($check_comma[1])-1];
                 }
            }


            $lon = str_replace($directions, '', $check_comma[1]);
            $lon = str_replace('°', ' ', $lon);
            $lon = explode(' ', trim($lon));
            


            $result['lon'] = DMS2Decimal($lon[0], $lon[1], $lon[2], $direction);
            
            $result['address'] = NULL;
        }
     
        return $result;
    }
endif;

if (!function_exists('calculate_signature')):
    
    /**
     * [calculate the signature for MMP]
     * @param  [type] $string      [description]
     * @param  [type] $private_key [description]
     * @return [string]              [description]
     */
    function calculate_signature($string, $private_key) {
        $hash = hash_hmac("sha1", $string, $private_key, true);
        
        $sig = rawurlencode(base64_encode($hash));
        return $sig;
    }
endif;

function jsonp_decode($jsonp, $assoc = false) {
    
    // PHP 5.3 adds depth as third parameter to json_decode
    if ($jsonp[0] !== '[' && $jsonp[0] !== '{') {
        
        // we have JSONP
        $jsonp = substr($jsonp, strpos($jsonp, '('));
    }
    return json_decode(trim($jsonp, '();'), $assoc);
}

function DMS2Decimal($degrees = 0, $minutes = 0, $seconds = 0, $direction = 'n') {
    
    //converts DMS coordinates to decimal
    //returns false on bad inputs, decimal on success
    
    //direction must be n, s, e or w, case-insensitive
    $d = strtolower($direction);
    $ok = array('n', 's', 'e', 'w');
    
    //degrees must be integer between 0 and 180
    if (!is_numeric($degrees) || $degrees < 0 || $degrees > 180) {
        $decimal = false;
    }
    
    //minutes must be integer or float between 0 and 59
    elseif (!is_numeric($minutes) || $minutes < 0 || $minutes > 59) {
        $decimal = false;
    }
    
    //seconds must be integer or float between 0 and 59
    elseif (!is_numeric($seconds) || $seconds < 0 || $seconds > 59) {
        $decimal = false;
    } elseif (!in_array($d, $ok)) {
        $decimal = false;
    } else {
        
        //inputs clean, calculate
        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
        
        //reverse for south or west coordinates; north is assumed
        if ($d == 's' || $d == 'w') {
            $decimal*= - 1;
        }
    }
    
    return $decimal;
}

if (!function_exists('cgmp_autoreplace_shortcodes')):
    
    function cgmp_autoreplace_shortcodes($shortcodes) {
        $pattern = get_shortcode_regex();
       
        $result = '';
        
        foreach ($shortcodes as $shortcode) {
            
            if (empty($shortcode['layer'])):
                
                // Process Markers
                foreach ($shortcode['markers'] as $marker):
                    
                    if (isset($marker['geolocation_failed'])) continue 2;
                    
                    if ($marker['shortcode_type'] == 'post') {
                        $post = get_post($marker['post_id']);
                        if ($post) {
                            
                            preg_match_all('/' . $pattern . '/s', $post->post_content, $matches, PREG_SET_ORDER);
                            
                            foreach ($matches as $match) {
                                if ($match[2] == 'google-map-v3') {
                                    
                                    wp_update_post(array('ID' => $post->ID, 'post_content' => str_replace($match[0], '[mapsmarker marker="' . $marker['new_id'] . '"]', $post->post_content)));
                                    $result.= '<tr>';
                                    $result.= '<td>' . '[mapsmarker marker="' . $marker['new_id'] . '"]' . '</td>';
                                    
                                    $result.= '<td><a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_marker&id=' . $marker['new_id'] . '" target="_blank">view map  </a></td>';
                                    $result.= '<td><a href="' . get_edit_post_link($post->ID) . '" target="_blank">Edit  </a></td>';
                                    $result.= '</tr>';
                                    break;
                                     // replace one occurance
                                    
                                }
                            }
                        }
                    } else {
                        
                        // ADD WIDGET
                        if ($marker['widget_type'] == 'comprehensivegooglemap') {
                            
                            $active_widgets = get_option('sidebars_widgets');
                            $text_widgets = get_option('widget_text');
                            
                            foreach ($active_widgets as $key => $value) {
                                if ($key != 'wp_inactive_widgets' AND $key != 'array_version') {
                                    if (is_array($value)) {
                                        $keys[$key] = array_search('comprehensivegooglemap-' . $marker['widget_id'], $value);
                                    } else {
                                        if ('comprehensivegooglemap-' . $marker['widget_id'] == $value) {
                                            $keys[$key] = $value;
                                        }
                                    }
                                }
                            }
                            
                            $text_widgets[] = array(
                            'title' => (trim($marker['widget_title'])) ? trim($marker['widget_title']) : 'Map', 'text' => '[mapsmarker marker="' . $marker['new_id'] . '"]', 'filter' => '');
                            $result.= '<tr>';
                            $result.= '<td>' . '[mapsmarker marker="' . $marker['new_id'] . '"]' . '</td>';
                            
                            $result.= '<td><a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_marker&id=' . $marker['new_id'] . '" target="_blank">view map  </a></td>';
                            $result.= '<td><a href="' . admin_url() . 'widgets.php" target="_blank">Edit  </a></td>';
                            $result.= '</tr>';
                            
                            foreach ($keys as $key => $value) {
                                
                                // unset($active_widgets[$key][$value]);
                                $active_widgets[$key][$value] = 'text-' . max(array_keys($text_widgets));
                            }
                            
                            update_option('sidebars_widgets', $active_widgets);
                            update_option('widget_text', $text_widgets);
                            
                            // REPLACE TEXT WIDGETS
                            
                        } elseif ($marker['widget_type'] == 'text') {
                            
                            $text_widgets = get_option('widget_text');
                            
                            $text_widgets[$marker['widget_id']] = array(
                            'title' => (trim($marker['widget_title'])) ? trim($marker['widget_title']) : 'Map', 'text' => '[mapsmarker marker="' . $marker['new_id'] . '"]', 'filter' => '');
                            $result.= '<tr>';
                            $result.= '<td>' . '[mapsmarker marker="' . $marker['new_id'] . '"]' . '</td>';
                            
                            $result.= '<td><a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_marker&id=' . $marker['new_id'] . '" target="_blank">view map  </a></td>';
                            $result.= '<td><a href="' . admin_url() . 'widgets.php" target="_blank">Edit  </a></td>';
                            $result.= '</tr>';
                            
                            update_option('widget_text', $text_widgets);
                        }
                    }
                endforeach;
            else:
                
                if (isset($shortcode['layer']['geolocation_failed'])) continue;
                
                // Process Layer
                if ($shortcode['layer']['shortcode_type'] == 'post') {
                    
                    $post = get_post($shortcode['layer']['post_id']);
                    if ($post) {
                        preg_match_all('/' . $pattern . '/s', $post->post_content, $matches, PREG_SET_ORDER);
                        foreach ($matches as $match) {
                            if ($match[2] == 'google-map-v3') {
                                wp_update_post(array('ID' => $post->ID, 'post_content' => str_replace($match[0], '[mapsmarker layer="' . $shortcode['layer']['new_id'] . '"]', $post->post_content)));
                                
                                $result.= '<tr>';
                                $result.= '<td>' . '[mapsmarker layer="' . $shortcode['layer']['new_id'] . '"]' . '</td>';
                                
                                $result.= '<td><a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_layer&id=' . $shortcode['layer']['new_id'] . '" target="_blank">view map  </a></td>';
                                $result.= '<td><a href="' . get_edit_post_link($post->ID) . '" target="_blank" target="_blank">Edit  </a></td>';
                                $result.= '</tr>';
                            }
                        }
                    }
                    
                    // Process Layers on widgets
                    
                } elseif ($shortcode['layer']['shortcode_type'] == 'cgmp_widget') {
                    $active_widgets = get_option('sidebars_widgets');
                    $text_widgets = get_option('widget_text');
                    
                    foreach ($active_widgets as $key => $value) {
                        if ($key != 'wp_inactive_widgets' AND $key != 'array_version') {
                            if (is_array($value)) {
                                $keys[$key] = array_search('comprehensivegooglemap-' . $shortcode['layer']['widget_id'], $value);
                            } else {
                                if ('comprehensivegooglemap-' . $shortcode['layer']['widget_id'] == $value) {
                                    $keys[$key] = $value;
                                }
                            }
                        }
                    }
                    
                    $text_widgets[] = array(
                    'title' => (trim($shortcode['layer']['widget_title'])) ? $shortcode['layer']['widget_title'] : 'Map', 'text' => '[mapsmarker layer="' . $shortcode['layer']['new_id'] . '"]', 'filter' => '');
                    $result.= '<tr>';
                    $result.= '<td>' . '[mapsmarker layer="' . $shortcode['layer']['new_id'] . '"]' . '</td>';
                    
                    $result.= '<td><a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_layer&id=' . $shortcode['layer']['new_id'] . '" target="_blank">view map  </a></td>';
                    $result.= '<td><a href="' . admin_url() . 'widgets.php" target="_blank">Edit  </a></td>';
                    $result.= '</tr>';
                    foreach ($keys as $key => $value) {
                        
                        // unset($active_widgets[$key][$value]);
                        $active_widgets[$key][$value] = 'text-' . max(array_keys($text_widgets));
                    }
                    
                    update_option('sidebars_widgets', $active_widgets);
                    update_option('widget_text', $text_widgets);
                } elseif ($shortcode['layer']['shortcode_type'] == 'text_widget') {
                    
                    $text_widgets = get_option('widget_text');
                    
                    $text_widgets[$shortcode['layer']['widget_id']] = array(
                    'title' => (trim($shortcode['layer']['widget_title'])) ? $shortcode['layer']['widget_title'] : 'Map', 'text' => '[mapsmarker layer="' . $shortcode['layer']['new_id'] . '"]', 'filter' => '');
                    $result.= '<tr>';
                    $result.= '<td>' . '[mapsmarker layer="' . $shortcode['layer']['new_id'] . '"]' . '</td>';
                    
                    $result.= '<td><a href="' . admin_url() . 'admin.php?page=leafletmapsmarker_layer&id=' . $shortcode['layer']['new_id'] . '" target="_blank">view map  </a></td>';
                    $result.= '<td><a href="' . admin_url() . 'widgets.php" target="_blank">Edit  </a></td>';
                    $result.= '</tr>';
                    
                    update_option('widget_text', $text_widgets);
                }
            endif;
        }
        if ($result != '') {
            update_option('widget_comprehensivegooglemap', array());
            return '
                    
                    <table cellspacing="0" cellpadding="0" >
                    <thead>
                        <th>Maps Marker Pro shortcode </th>
                        <th style="width:20%">Link to Maps Marker Pro map</th>
                       
                         <th style="width:15%">Link to content where shortcode is used</th>  
                    </thead>
                    <tbody>
                        ' . $result . '
                    </tbody>
                </table>

                ';
        }
    }
endif;

if (!function_exists('cgmp_calculate_boundbox_from_db')) {
    
    function cgmp_calculate_boundbox_from_db($width, $height, $layer_id = 0) {
        global $wpdb;
        if ($layer_id !== 0) {
            
            $markers = $wpdb->get_row('SELECT  min(lat) as minlat, max(lat) as maxlat, min(lon) as minlon, max(lon) as maxlon FROM ' . $wpdb->prefix . 'leafletmapsmarker_markers WHERE layer = ' . $layer_id);
        } else {
            $markers = $wpdb->get_row('SELECT  min(lat) as minlat, max(lat) as maxlat, min(lon) as minlon, max(lon) as maxlon FROM ' . $wpdb->prefix . 'leafletmapsmarker_markers');
        }
       
       
        if ($markers) {
            
            if (class_exists('LatLngBounds')) {
                $LatLngSw = new LatLng($markers->minlat, $markers->minlon);
                $LatLngNe = new LatLng($markers->maxlat, $markers->maxlon);
                
                $layercenter = new LatLngBounds($LatLngSw, $LatLngNe);
                $results['layerviewlat'] = $layercenter->getCenter()->getLat();
                $results['layerviewlon'] = $layercenter->getCenter()->getLng();
                
                $results['layerzoom'] = getBoundsZoomLevel(array('width' => $width, 'height' => $height), $LatLngSw, $LatLngNe);
            }
           
            return $results;
        } else {
            return false;
        }
    }
}

if (!function_exists('getBoundsZoomLevel')):
    function getBoundsZoomLevel($mapDim, $sw, $ne) {
        $world_dim = array('height' => 256, 'width' => 256);
        $zoom_max = 21;
        
        $latFraction = (cgmp_latRad($ne->getLat()) - cgmp_latRad($sw->getLat())) / M_PI;
       
        $lngDiff = $ne->getLng() - $sw->getLng();
        $lngFraction = (($lngDiff < 0) ? ($lngDiff + 360) : $lngDiff) / 360;
        
        $latZoom = @cgmp_zoom($mapDim['height'], $world_dim['height'], $latFraction);
        $lngZoom = @cgmp_zoom($mapDim['width'], $world_dim['width'], $lngFraction);
        
        return min($latZoom, $lngZoom, $zoom_max) - 1;
    }
endif;

if (!function_exists('cgmp_latRad')):
    function cgmp_latRad($lat) {
        $sin = sin($lat * M_PI / 180);
        $radX2 = log((1 + $sin) / (1 - $sin)) / 2;
        return max(min($radX2, M_PI), -M_PI) / 2;
    }
endif;

if (!function_exists('cgmp_zoom')):
    function cgmp_zoom($mapPx, $worldPx, $fraction) {
        if($fraction == 0){
            $fraction = 0.00000000001;
        }
        return floor(log($mapPx / $worldPx / $fraction) / 0.693);
         // 0.693 is ln2
        
    }
endif;

if (!function_exists('cgmp_get_saved_shortcodes')):
    function cgmp_get_saved_shortcodes() {
        $saved_shortcodes = get_option('cgmp_persisted_shortcodes');
         if( $saved_shortcodes === FALSE) return NULL;
        $saved_shortcodes = json_decode(stripslashes($saved_shortcodes), TRUE);
        $extracted_codes = array();
        if( is_array($saved_shortcodes)){


            foreach ($saved_shortcodes as $key => $shortcode) {
                array_push($extracted_codes, $shortcode['code']);
            }
         }
        return $extracted_codes;
    }
endif;

if (!function_exists('cgmp_remove_shortcodeid')):
    
    function cgmp_remove_shortcodeid($shortcode) {
        
        preg_match('/shortcodeid="([^"]*)"+/', $shortcode, $matches);
        if ($matches[0]) {
            return str_replace($matches[0], '', $shortcode);
        } else {
            return $shortcode;
        }
    }
endif;

if (!function_exists('cgmp_accent_folding')):
    function cgmp_accent_folding($geocode) {
        if (!$geocode) return '';
        
        $accent_map = array('ẚ' => 'a', 'Á' => 'a', 'á' => 'a', 'À' => 'a', 'à' => 'a', 'Ă' => 'a', 'ă' => 'a', 'Ắ' => 'a', 'ắ' => 'a', 'Ằ' => 'a', 'ằ' => 'a', 'Ẵ' => 'a', 'ẵ' => 'a', 'Ẳ' => 'a', 'ẳ' => 'a', 'Â' => 'a', 'â' => 'a', 'Ấ' => 'a', 'ấ' => 'a', 'Ầ' => 'a', 'ầ' => 'a', 'Ẫ' => 'a', 'ẫ' => 'a', 'Ẩ' => 'a', 'ẩ' => 'a', 'Ǎ' => 'a', 'ǎ' => 'a', 'Å' => 'a', 'å' => 'a', 'Ǻ' => 'a', 'ǻ' => 'a', 'Ä' => 'a', 'ä' => 'a', 'Ǟ' => 'a', 'ǟ' => 'a', 'Ã' => 'a', 'ã' => 'a', 'Ȧ' => 'a', 'ȧ' => 'a', 'Ǡ' => 'a', 'ǡ' => 'a', 'Ą' => 'a', 'ą' => 'a', 'Ā' => 'a', 'ā' => 'a', 'Ả' => 'a', 'ả' => 'a', 'Ȁ' => 'a', 'ȁ' => 'a', 'Ȃ' => 'a', 'ȃ' => 'a', 'Ạ' => 'a', 'ạ' => 'a', 'Ặ' => 'a', 'ặ' => 'a', 'Ậ' => 'a', 'ậ' => 'a', 'Ḁ' => 'a', 'ḁ' => 'a', 'Ⱥ' => 'a', 'ⱥ' => 'a', 'Ǽ' => 'a', 'ǽ' => 'a', 'Ǣ' => 'a', 'ǣ' => 'a', 'Ḃ' => 'b', 'ḃ' => 'b', 'Ḅ' => 'b', 'ḅ' => 'b', 'Ḇ' => 'b', 'ḇ' => 'b', 'Ƀ' => 'b', 'ƀ' => 'b', 'ᵬ' => 'b', 'Ɓ' => 'b', 'ɓ' => 'b', 'Ƃ' => 'b', 'ƃ' => 'b', 'Ć' => 'c', 'ć' => 'c', 'Ĉ' => 'c', 'ĉ' => 'c', 'Č' => 'c', 'č' => 'c', 'Ċ' => 'c', 'ċ' => 'c', 'Ç' => 'c', 'ç' => 'c', 'Ḉ' => 'c', 'ḉ' => 'c', 'Ȼ' => 'c', 'ȼ' => 'c', 'Ƈ' => 'c', 'ƈ' => 'c', 'ɕ' => 'c', 'Ď' => 'd', 'ď' => 'd', 'Ḋ' => 'd', 'ḋ' => 'd', 'Ḑ' => 'd', 'ḑ' => 'd', 'Ḍ' => 'd', 'ḍ' => 'd', 'Ḓ' => 'd', 'ḓ' => 'd', 'Ḏ' => 'd', 'ḏ' => 'd', 'Đ' => 'd', 'đ' => 'd', 'ᵭ' => 'd', 'Ɖ' => 'd', 'ɖ' => 'd', 'Ɗ' => 'd', 'ɗ' => 'd', 'Ƌ' => 'd', 'ƌ' => 'd', 'ȡ' => 'd', 'ð' => 'd', 'É' => 'e', 'Ə' => 'e', 'Ǝ' => 'e', 'ǝ' => 'e', 'é' => 'e', 'È' => 'e', 'è' => 'e', 'Ĕ' => 'e', 'ĕ' => 'e', 'Ê' => 'e', 'ê' => 'e', 'Ế' => 'e', 'ế' => 'e', 'Ề' => 'e', 'ề' => 'e', 'Ễ' => 'e', 'ễ' => 'e', 'Ể' => 'e', 'ể' => 'e', 'Ě' => 'e', 'ě' => 'e', 'Ë' => 'e', 'ë' => 'e', 'Ẽ' => 'e', 'ẽ' => 'e', 'Ė' => 'e', 'ė' => 'e', 'Ȩ' => 'e', 'ȩ' => 'e', 'Ḝ' => 'e', 'ḝ' => 'e', 'Ę' => 'e', 'ę' => 'e', 'Ē' => 'e', 'ē' => 'e', 'Ḗ' => 'e', 'ḗ' => 'e', 'Ḕ' => 'e', 'ḕ' => 'e', 'Ẻ' => 'e', 'ẻ' => 'e', 'Ȅ' => 'e', 'ȅ' => 'e', 'Ȇ' => 'e', 'ȇ' => 'e', 'Ẹ' => 'e', 'ẹ' => 'e', 'Ệ' => 'e', 'ệ' => 'e', 'Ḙ' => 'e', 'ḙ' => 'e', 'Ḛ' => 'e', 'ḛ' => 'e', 'Ɇ' => 'e', 'ɇ' => 'e', 'ɚ' => 'e', 'ɝ' => 'e', 'Ḟ' => 'f', 'ḟ' => 'f', 'ᵮ' => 'f', 'Ƒ' => 'f', 'ƒ' => 'f', 'Ǵ' => 'g', 'ǵ' => 'g', 'Ğ' => 'g', 'ğ' => 'g', 'Ĝ' => 'g', 'ĝ' => 'g', 'Ǧ' => 'g', 'ǧ' => 'g', 'Ġ' => 'g', 'ġ' => 'g', 'Ģ' => 'g', 'ģ' => 'g', 'Ḡ' => 'g', 'ḡ' => 'g', 'Ǥ' => 'g', 'ǥ' => 'g', 'Ɠ' => 'g', 'ɠ' => 'g', 'Ĥ' => 'h', 'ĥ' => 'h', 'Ȟ' => 'h', 'ȟ' => 'h', 'Ḧ' => 'h', 'ḧ' => 'h', 'Ḣ' => 'h', 'ḣ' => 'h', 'Ḩ' => 'h', 'ḩ' => 'h', 'Ḥ' => 'h', 'ḥ' => 'h', 'Ḫ' => 'h', 'ḫ' => 'h', 'H' => 'h', '̱' => 'h', 'ẖ' => 'h', 'Ħ' => 'h', 'ħ' => 'h', 'Ⱨ' => 'h', 'ⱨ' => 'h', 'Í' => 'i', 'í' => 'i', 'Ì' => 'i', 'ì' => 'i', 'Ĭ' => 'i', 'ĭ' => 'i', 'Î' => 'i', 'î' => 'i', 'Ǐ' => 'i', 'ǐ' => 'i', 'Ï' => 'i', 'ï' => 'i', 'Ḯ' => 'i', 'ḯ' => 'i', 'Ĩ' => 'i', 'ĩ' => 'i', 'İ' => 'i', 'i' => 'i', 'Į' => 'i', 'į' => 'i', 'Ī' => 'i', 'ī' => 'i', 'Ỉ' => 'i', 'ỉ' => 'i', 'Ȉ' => 'i', 'ȉ' => 'i', 'Ȋ' => 'i', 'ȋ' => 'i', 'Ị' => 'i', 'ị' => 'i', 'Ḭ' => 'i', 'ḭ' => 'i', 'I' => 'i', 'ı' => 'i', 'Ɨ' => 'i', 'ɨ' => 'i', 'Ĵ' => 'j', 'ĵ' => 'j', 'J' => 'j', '̌' => 'j', 'ǰ' => 'j', 'ȷ' => 'j', 'Ɉ' => 'j', 'ɉ' => 'j', 'ʝ' => 'j', 'ɟ' => 'j', 'ʄ' => 'j', 'Ḱ' => 'k', 'ḱ' => 'k', 'Ǩ' => 'k', 'ǩ' => 'k', 'Ķ' => 'k', 'ķ' => 'k', 'Ḳ' => 'k', 'ḳ' => 'k', 'Ḵ' => 'k', 'ḵ' => 'k', 'Ƙ' => 'k', 'ƙ' => 'k', 'Ⱪ' => 'k', 'ⱪ' => 'k', 'Ĺ' => 'a', 'ĺ' => 'l', 'Ľ' => 'l', 'ľ' => 'l', 'Ļ' => 'l', 'ļ' => 'l', 'Ḷ' => 'l', 'ḷ' => 'l', 'Ḹ' => 'l', 'ḹ' => 'l', 'Ḽ' => 'l', 'ḽ' => 'l', 'Ḻ' => 'l', 'ḻ' => 'l', 'Ł' => 'l', 'ł' => 'l', 'Ł' => 'l', '̣' => 'l', 'ł' => 'l', '̣' => 'l', 'Ŀ' => 'l', 'ŀ' => 'l', 'Ƚ' => 'l', 'ƚ' => 'l', 'Ⱡ' => 'l', 'ⱡ' => 'l', 'Ɫ' => 'l', 'ɫ' => 'l', 'ɬ' => 'l', 'ɭ' => 'l', 'ȴ' => 'l', 'Ḿ' => 'm', 'ḿ' => 'm', 'Ṁ' => 'm', 'ṁ' => 'm', 'Ṃ' => 'm', 'ṃ' => 'm', 'ɱ' => 'm', 'Ń' => 'n', 'ń' => 'n', 'Ǹ' => 'n', 'ǹ' => 'n', 'Ň' => 'n', 'ň' => 'n', 'Ñ' => 'n', 'ñ' => 'n', 'Ṅ' => 'n', 'ṅ' => 'n', 'Ņ' => 'n', 'ņ' => 'n', 'Ṇ' => 'n', 'ṇ' => 'n', 'Ṋ' => 'n', 'ṋ' => 'n', 'Ṉ' => 'n', 'ṉ' => 'n', 'Ɲ' => 'n', 'ɲ' => 'n', 'Ƞ' => 'n', 'ƞ' => 'n', 'ɳ' => 'n', 'ȵ' => 'n', 'N' => 'n', '̈' => 'n', 'n' => 'n', '̈' => 'n', 'Ó' => 'o', 'ó' => 'o', 'Ò' => 'o', 'ò' => 'o', 'Ŏ' => 'o', 'ŏ' => 'o', 'Ô' => 'o', 'ô' => 'o', 'Ố' => 'o', 'ố' => 'o', 'Ồ' => 'o', 'ồ' => 'o', 'Ỗ' => 'o', 'ỗ' => 'o', 'Ổ' => 'o', 'ổ' => 'o', 'Ǒ' => 'o', 'ǒ' => 'o', 'Ö' => 'o', 'ö' => 'o', 'Ȫ' => 'o', 'ȫ' => 'o', 'Ő' => 'o', 'ő' => 'o', 'Õ' => 'o', 'õ' => 'o', 'Ṍ' => 'o', 'ṍ' => 'o', 'Ṏ' => 'o', 'ṏ' => 'o', 'Ȭ' => 'o', 'ȭ' => 'o', 'Ȯ' => 'o', 'ȯ' => 'o', 'Ȱ' => 'o', 'ȱ' => 'o', 'Ø' => 'o', 'ø' => 'o', 'Ǿ' => 'o', 'ǿ' => 'o', 'Ǫ' => 'o', 'ǫ' => 'o', 'Ǭ' => 'o', 'ǭ' => 'o', 'Ō' => 'o', 'ō' => 'o', 'Ṓ' => 'o', 'ṓ' => 'o', 'Ṑ' => 'o', 'ṑ' => 'o', 'Ỏ' => 'o', 'ỏ' => 'o', 'Ȍ' => 'o', 'ȍ' => 'o', 'Ȏ' => 'o', 'ȏ' => 'o', 'Ơ' => 'o', 'ơ' => 'o', 'Ớ' => 'o', 'ớ' => 'o', 'Ờ' => 'o', 'ờ' => 'o', 'Ỡ' => 'o', 'ỡ' => 'o', 'Ở' => 'o', 'ở' => 'o', 'Ợ' => 'o', 'ợ' => 'o', 'Ọ' => 'o', 'ọ' => 'o', 'Ộ' => 'o', 'ộ' => 'o', 'Ɵ' => 'o', 'ɵ' => 'o', 'Ṕ' => 'p', 'ṕ' => 'p', 'Ṗ' => 'p', 'ṗ' => 'p', 'Ᵽ' => 'p', 'Ƥ' => 'p', 'ƥ' => 'p', 'P' => 'p', '̃' => 'p', 'p' => 'p', '̃' => 'p', 'ʠ' => 'q', 'Ɋ' => 'q', 'ɋ' => 'q', 'Ŕ' => 'r', 'ŕ' => 'r', 'Ř' => 'r', 'ř' => 'r', 'Ṙ' => 'r', 'ṙ' => 'r', 'Ŗ' => 'r', 'ŗ' => 'r', 'Ȑ' => 'r', 'ȑ' => 'r', 'Ȓ' => 'r', 'ȓ' => 'r', 'Ṛ' => 'r', 'ṛ' => 'r', 'Ṝ' => 'r', 'ṝ' => 'r', 'Ṟ' => 'r', 'ṟ' => 'r', 'Ɍ' => 'r', 'ɍ' => 'r', 'ᵲ' => 'r', 'ɼ' => 'r', 'Ɽ' => 'r', 'ɽ' => 'r', 'ɾ' => 'r', 'ᵳ' => 'r', 'ß' => 's', 'Ś' => 's', 'ś' => 's', 'Ṥ' => 's', 'ṥ' => 's', 'Ŝ' => 's', 'ŝ' => 's', 'Š' => 's', 'š' => 's', 'Ṧ' => 's', 'ṧ' => 's', 'Ṡ' => 's', 'ṡ' => 's', 'ẛ' => 's', 'Ş' => 's', 'ş' => 's', 'Ṣ' => 's', 'ṣ' => 's', 'Ṩ' => 's', 'ṩ' => 's', 'Ș' => 's', 'ș' => 's', 'ʂ' => 's', 'S' => 's', '̩' => 's', 's' => 's', '̩' => 's', 'Þ' => 't', 'þ' => 't', 'Ť' => 't', 'ť' => 't', 'T' => 't', '̈' => 't', 'ẗ' => 't', 'Ṫ' => 't', 'ṫ' => 't', 'Ţ' => 't', 'ţ' => 't', 'Ṭ' => 't', 'ṭ' => 't', 'Ț' => 't', 'ț' => 't', 'Ṱ' => 't', 'ṱ' => 't', 'Ṯ' => 't', 'ṯ' => 't', 'Ŧ' => 't', 'ŧ' => 't', 'Ⱦ' => 't', 'ⱦ' => 't', 'ᵵ' => 't', 'ƫ' => 't', 'Ƭ' => 't', 'ƭ' => 't', 'Ʈ' => 't', 'ʈ' => 't', 'ȶ' => 't', 'Ú' => 'u', 'ú' => 'u', 'Ù' => 'u', 'ù' => 'u', 'Ŭ' => 'u', 'ŭ' => 'u', 'Û' => 'u', 'û' => 'u', 'Ǔ' => 'u', 'ǔ' => 'u', 'Ů' => 'u', 'ů' => 'u', 'Ü' => 'u', 'ü' => 'u', 'Ǘ' => 'u', 'ǘ' => 'u', 'Ǜ' => 'u', 'ǜ' => 'u', 'Ǚ' => 'u', 'ǚ' => 'u', 'Ǖ' => 'u', 'ǖ' => 'u', 'Ű' => 'u', 'ű' => 'u', 'Ũ' => 'u', 'ũ' => 'u', 'Ṹ' => 'u', 'ṹ' => 'u', 'Ų' => 'u', 'ų' => 'u', 'Ū' => 'u', 'ū' => 'u', 'Ṻ' => 'u', 'ṻ' => 'u', 'Ủ' => 'u', 'ủ' => 'u', 'Ȕ' => 'u', 'ȕ' => 'u', 'Ȗ' => 'u', 'ȗ' => 'u', 'Ư' => 'u', 'ư' => 'u', 'Ứ' => 'u', 'ứ' => 'u', 'Ừ' => 'u', 'ừ' => 'u', 'Ữ' => 'u', 'ữ' => 'u', 'Ử' => 'u', 'ử' => 'u', 'Ự' => 'u', 'ự' => 'u', 'Ụ' => 'u', 'ụ' => 'u', 'Ṳ' => 'u', 'ṳ' => 'u', 'Ṷ' => 'u', 'ṷ' => 'u', 'Ṵ' => 'u', 'ṵ' => 'u', 'Ʉ' => 'u', 'ʉ' => 'u', 'Ṽ' => 'v', 'ṽ' => 'v', 'Ṿ' => 'v', 'ṿ' => 'v', 'Ʋ' => 'v', 'ʋ' => 'v', 'Ẃ' => 'w', 'ẃ' => 'w', 'Ẁ' => 'w', 'ẁ' => 'w', 'Ŵ' => 'w', 'ŵ' => 'w', 'W' => 'w', '̊' => 'w', 'ẘ' => 'w', 'Ẅ' => 'w', 'ẅ' => 'w', 'Ẇ' => 'w', 'ẇ' => 'w', 'Ẉ' => 'w', 'ẉ' => 'w', 'Ẍ' => 'x', 'ẍ' => 'x', 'Ẋ' => 'x', 'ẋ' => 'x', 'Ý' => 'y', 'ý' => 'y', 'Ỳ' => 'y', 'ỳ' => 'y', 'Ŷ' => 'y', 'ŷ' => 'y', 'Y' => 'y', '̊' => 'y', 'ẙ' => 'y', 'Ÿ' => 'y', 'ÿ' => 'y', 'Ỹ' => 'y', 'ỹ' => 'y', 'Ẏ' => 'y', 'ẏ' => 'y', 'Ȳ' => 'y', 'ȳ' => 'y', 'Ỷ' => 'y', 'ỷ' => 'y', 'Ỵ' => 'y', 'ỵ' => 'y', 'ʏ' => 'y', 'Ɏ' => 'y', 'ɏ' => 'y', 'Ƴ' => 'y', 'ƴ' => 'y', 'Ź' => 'z', 'ź' => 'z', 'Ẑ' => 'z', 'ẑ' => 'z', 'Ž' => 'z', 'ž' => 'z', 'Ż' => 'z', 'ż' => 'z', 'Ẓ' => 'z', 'ẓ' => 'z', 'Ẕ' => 'z', 'ẕ' => 'z', 'Ƶ' => 'z', 'ƶ' => 'z', 'Ȥ' => 'z', 'ȥ' => 'z', 'ʐ' => 'z', 'ʑ' => 'z', 'Ⱬ' => 'z', 'ⱬ' => 'z', 'Ǯ' => 'z', 'ǯ' => 'z', 'ƺ' => 'z',
        
        // Roman fullwidth ascii equivalents =>  0xff00 to 0xff5e
        '２' => '2', '６' => '6', 'Ｂ' => 'B', 'Ｆ' => 'F', 'Ｊ' => 'J', 'Ｎ' => 'N', 'Ｒ' => 'R', 'Ｖ' => 'V', 'Ｚ' => 'Z', 'ｂ' => 'b', 'ｆ' => 'f', 'ｊ' => 'j', 'ｎ' => 'n', 'ｒ' => 'r', 'ｖ' => 'v', 'ｚ' => 'z', '１' => '1', '５' => '5', '９' => '9', 'Ａ' => 'A', 'Ｅ' => 'E', 'Ｉ' => 'I', 'Ｍ' => 'M', 'Ｑ' => 'Q', 'Ｕ' => 'U', 'Ｙ' => 'Y', 'ａ' => 'a', 'ｅ' => 'e', 'ｉ' => 'i', 'ｍ' => 'm', 'ｑ' => 'q', 'ｕ' => 'u', 'ｙ' => 'y', '０' => '0', '４' => '4', '８' => '8', 'Ｄ' => 'D', 'Ｈ' => 'H', 'Ｌ' => 'L', 'Ｐ' => 'P', 'Ｔ' => 'T', 'Ｘ' => 'X', 'ｄ' => 'd', 'ｈ' => 'h', 'ｌ' => 'l', 'ｐ' => 'p', 'ｔ' => 't', 'ｘ' => 'x', '３' => '3', '７' => '7', 'Ｃ' => 'C', 'Ｇ' => 'G', 'Ｋ' => 'K', 'Ｏ' => 'O', 'Ｓ' => 'S', 'Ｗ' => 'W', 'ｃ' => 'c', 'ｇ' => 'g', 'ｋ' => 'k', 'ｏ' => 'o', 'ｓ' => 's', 'ｗ' => 'w');
        
        return str_replace(array_keys($accent_map), array_values($accent_map), $geocode);
    }
endif;