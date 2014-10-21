<?php
if(defined('WP_UNINSTALL_PLUGIN')) {
	global $wpdb;
	if (is_multisite()) {
		$blogs = $wpdb->get_results("SELECT `blog_id` FROM {$wpdb->blogs}", ARRAY_A);
		$options_table = $wpdb->options;
			$wpdb->query( "DELETE FROM ".$options_table." WHERE option_name LIKE '".CGMP_ALL_MAP_CACHED_CONSTANTS_PREFIX."%';" );
	
			delete_option('cgmp_options');
			delete_option('widget_comprehensivegooglemap');
			delete_option('cgmp_persisted_shortcodes');
			delete_option('cgmp_initial_warning');
			delete_transient('cgmp_update_routine');
			delete_transient('cgmp_layers_markers_export');

			/*remove map icons directory for main site if Maps Marker Pro hasnt been activated */
			if (get_option( 'leafletmapsmarker_version_pro' ) == NULL) {
				$cgmp_upload_dir = wp_upload_dir();
				$icons_directory = $cgmp_upload_dir['basedir'] . DIRECTORY_SEPARATOR . "leaflet-maps-marker-icons" . DIRECTORY_SEPARATOR;
				if (is_dir($icons_directory)) {
					foreach(glob($icons_directory.'*.*') as $v){
						unlink($v);
					}
					rmdir($icons_directory);
				}
			}
						
			//legacy
			delete_option(CGMP_DB_PUBLISHED_POST_MARKERS);
			delete_option(CGMP_DB_POST_COUNT);
			delete_option(CGMP_DB_PUBLISHED_POST_IDS);
			delete_option(CGMP_DB_PUBLISHED_PAGE_IDS);
			delete_option(CGMP_DB_SETTINGS_SHOULD_BASE_OBJECT_RENDER);
			delete_option(CGMP_DB_SETTINGS_WAS_BASE_OBJECT_RENDERED);
			delete_option(CGMP_DB_PURGE_GEOMASHUP_CACHE);
			delete_option(CGMP_DB_GEOMASHUP_CONTENT);
		if ($blogs) {
			foreach($blogs as $blog) {
				switch_to_blog($blog['blog_id']);
					$options_table = $wpdb->options;
					$wpdb->query( "DELETE FROM ".$options_table." WHERE option_name LIKE '".CGMP_ALL_MAP_CACHED_CONSTANTS_PREFIX."%';" );
			
					delete_option('cgmp_options');
					delete_option('widget_comprehensivegooglemap');
					delete_option('cgmp_persisted_shortcodes');
					delete_option('cgmp_initial_warning');
					delete_transient('cgmp_update_routine');
					delete_transient('cgmp_layers_markers_export');

					/*remove map icons directory for subsites if Maps Marker Pro hasnt been activated */
					if (get_option( 'leafletmapsmarker_version_pro' ) == NULL) {
						$cgmp_upload_dir = wp_upload_dir();
						$icons_directory = $cgmp_upload_dir['basedir'] . DIRECTORY_SEPARATOR . "leaflet-maps-marker-icons" . DIRECTORY_SEPARATOR;
						if (is_dir($icons_directory)) {
							foreach(glob($icons_directory.'*.*') as $v){
								unlink($v);
							}
							rmdir($icons_directory);
						}
					}
					
					//legacy
					delete_option(CGMP_DB_PUBLISHED_POST_MARKERS);
					delete_option(CGMP_DB_POST_COUNT);
					delete_option(CGMP_DB_PUBLISHED_POST_IDS);
					delete_option(CGMP_DB_PUBLISHED_PAGE_IDS);
					delete_option(CGMP_DB_SETTINGS_SHOULD_BASE_OBJECT_RENDER);
					delete_option(CGMP_DB_SETTINGS_WAS_BASE_OBJECT_RENDERED);
					delete_option(CGMP_DB_PURGE_GEOMASHUP_CACHE);
					delete_option(CGMP_DB_GEOMASHUP_CONTENT);
					restore_current_blog();
				}
		}
	} else {
		$options_table = $wpdb->options;
		$wpdb->query( "DELETE FROM ".$options_table." WHERE option_name LIKE '".CGMP_ALL_MAP_CACHED_CONSTANTS_PREFIX."%';" );

		delete_option('cgmp_options');
		delete_option('widget_comprehensivegooglemap');
		delete_option('cgmp_persisted_shortcodes');
		delete_option('cgmp_initial_warning');
		delete_transient('cgmp_update_routine');
		delete_transient('cgmp_layers_markers_export');

		/*remove map icons directory if Maps Marker Pro hasnt been activated */
		if (get_option( 'leafletmapsmarker_version_pro' ) == NULL) {
			$cgmp_upload_dir = wp_upload_dir();
			$icons_directory = $cgmp_upload_dir['basedir'] . DIRECTORY_SEPARATOR . "leaflet-maps-marker-icons" . DIRECTORY_SEPARATOR;
			if (is_dir($icons_directory)) {
				foreach(glob($icons_directory.'*.*') as $v){
					unlink($v);
				}
				rmdir($icons_directory);
			}
		}
		
		//legacy
		delete_option(CGMP_DB_PUBLISHED_POST_MARKERS);
		delete_option(CGMP_DB_POST_COUNT);
		delete_option(CGMP_DB_PUBLISHED_POST_IDS);
		delete_option(CGMP_DB_PUBLISHED_PAGE_IDS);
		delete_option(CGMP_DB_SETTINGS_SHOULD_BASE_OBJECT_RENDER);
		delete_option(CGMP_DB_SETTINGS_WAS_BASE_OBJECT_RENDERED);
		delete_option(CGMP_DB_PURGE_GEOMASHUP_CACHE);
		delete_option(CGMP_DB_GEOMASHUP_CONTENT);
	}
}
?>
