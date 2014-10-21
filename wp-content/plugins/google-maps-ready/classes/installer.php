<?php
class installerGmp {
	static public $update_to_version_method = '';
	static public function init() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		//$start = microtime(true);					// Speed debug info
		//$queriesCountStart = $wpdb->num_queries;	// Speed debug info
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$current_version = get_option($wpPrefix. GMP_DB_PREF. 'db_version', 0);
		$installed = (int) get_option($wpPrefix. GMP_DB_PREF. 'db_installed', 0);
		/**
		 * htmltype 
		 */
		if (!dbGmp::exist($wpPrefix.GMP_DB_PREF."htmltype")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."htmltype` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `label` varchar(32) NOT NULL,
			  `description` varchar(255) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `label` (`label`)
			) DEFAULT CHARSET=utf8");
		
			dbGmp::query("INSERT INTO `".$wpPrefix.GMP_DB_PREF."htmltype` VALUES
				(1, 'text', 'Text'),
				(2, 'password', 'Password'),
				(3, 'hidden', 'Hidden'),
				(4, 'checkbox', 'Checkbox'),
				(5, 'checkboxlist', 'Checkboxes'),
				(6, 'datepicker', 'Date Picker'),
				(7, 'submit', 'Button'),
				(8, 'img', 'Image'),
				(9, 'selectbox', 'Drop Down'),
				(10, 'radiobuttons', 'Radio Buttons'),
				(11, 'countryList', 'Countries List'),
				(12, 'selectlist', 'List'),
				(13, 'countryListMultiple', 'Country List with posibility to select multiple countries'),
				(14, 'block', 'Will show only value as text'),
				(15, 'statesList', 'States List'),
				(16, 'textFieldsDynamicTable', 'Dynamic table - multiple text options set'),
				(17, 'textarea', 'Textarea'),
				(18, 'checkboxHiddenVal', 'Checkbox with Hidden field')");
		}
		/**
		 * modules 
		 */
		if (!dbGmp::exist($wpPrefix.GMP_DB_PREF."modules")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."modules` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(64) NOT NULL,
			  `active` tinyint(1) NOT NULL DEFAULT '0',
			  `type_id` smallint(3) NOT NULL DEFAULT '0',
			  `params` text,
			  `has_tab` tinyint(1) NOT NULL DEFAULT '0',
			  `label` varchar(128) DEFAULT NULL,
			  `description` text,
			  `ex_plug_dir` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `code` (`code`)
			) DEFAULT CHARSET=utf8;");

			dbGmp::query("INSERT INTO `".$wpPrefix.GMP_DB_PREF."modules` (id, code, active, type_id, params, has_tab, label, description) VALUES
				(NULL, 'adminmenu',1,1,'',0,'Admin Menu',''),
				(NULL, 'options',1,1,'',1,'Options',''),
				(NULL, 'user',1,1,'',1,'Users',''),
				(NULL, 'templates',1,1,'',1,'Templates for Plugin',''),

				(NULL, 'shortcodes', 1, 6, '', 0, 'Shortcodes', 'Shortcodes data'),
				(NULL, 'gmap', 1, 1, '',1, 'Gmap', 'Gmap'),
				(NULL, 'marker', 1, 1, '', 0, 'Markers', 'Google Maps Markers'),
				(NULL, 'marker_groups', 1, 1, '', 0, 'Marker Gropus', 'Marker Groups'),                  
				(NULL, 'promo_ready', 1, 1, '', 0, 'Promo Ready', 'Promo Ready'),                  
				(NULL, 'icons', 1, 1, '', 1, 'Marker Icons', 'Marker Icons');");
		}
		/**
		 *  modules_type 
		 */
		if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."modules_type")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."modules_type` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `label` varchar(64) NOT NULL,
			  PRIMARY KEY (`id`)
			) AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");
		
			dbGmp::query("INSERT INTO `".$wpPrefix.GMP_DB_PREF."modules_type` VALUES
			  (1,'system'),
			  (2,'payment'),
			  (3,'shipping'),
			  (4,'widget'),
			  (5,'product_extra'),
			  (6,'addons'),
			  (7,'template')");
		}
		/**
		 * options 
		 */
		if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."options")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."options` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(64) CHARACTER SET latin1 NOT NULL,
			  `value` text NULL,
			  `label` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
			  `description` text CHARACTER SET latin1,
			  `htmltype_id` smallint(2) NOT NULL DEFAULT '1',
			  `params` text NULL,
			  `cat_id` mediumint(3) DEFAULT '0',
			  `sort_order` mediumint(3) DEFAULT '0',
			  `value_type` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id` (`id`),
			  UNIQUE INDEX `code` (`code`)
			) DEFAULT CHARSET=utf8");
			dbGmp::query("`".$wpPrefix.GMP_DB_PREF."options` (  `code` ,  `value` ,  `label` ) 
					VALUES ( 'save_statistic',  '0',  'Send statistic')");
		}
		$eol = "\n";
		
		/* options categories */
		if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."options_categories")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."options_categories` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `label` varchar(128) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id` (`id`)
			) DEFAULT CHARSET=utf8");
			dbGmp::query("INSERT INTO `".$wpPrefix.GMP_DB_PREF."options_categories` VALUES
				(1, 'General'),
				(2, 'Template'),
				(3, 'Subscribe'),
				(4, 'Social');");
		
		}
		/*
		* Create table for map
		*/
        if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."maps")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."maps` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`title` varchar(125) CHARACTER SET utf8  NOT NULL,
				`description` text CHARACTER SET utf8 NULL,
				`params` text NULL,
				`html_options` text NOT NULL,
				`create_date` datetime,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `id` (`id`)
			  ) DEFAULT CHARSET=utf8");
		}
		/**
		 * Create table for markers    
		 */
		if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."markers")){
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."markers"."` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`title` varchar(125) CHARACTER SET utf8  NOT NULL,
					`description` text CHARACTER SET utf8 NULL,
					`coord_x` varchar(30) NOT NULL,
					`coord_y` varchar(30) NOT NULL,
					`icon` int(11),
					`map_id` int(11),
					`marker_group_id` int(11),
					`address` text,
					`animation` int(1),
					`create_date` datetime,
					`params` text  CHARACTER SET utf8  NOT NULL,
					PRIMARY KEY (`id`)
					)");                    
		}
		/**
		 * Create table for marker Icons
		 */
		if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."icons")){
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."icons"."` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`title` varchar(100) CHARACTER SET utf8,   
					`description` text CHARACTER SET utf8,   
					`path` varchar(250) CHARACTER SET utf8,   
					 PRIMARY KEY (`id`)
					)");        
		}

		/**
		 * Create table for marker groups
		 */
		if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."marker_groups")){
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix.GMP_DB_PREF."marker_groups"."` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`title` varchar(250) CHARACTER SET utf8,
					`description` text CHARACTER SET utf8,
				 PRIMARY KEY (`id`)
				  )");
			dbGmp::query("INSERT INTO `".$wpPrefix.GMP_DB_PREF."marker_groups` VALUES('null', 'Default Group','Default Group');");
		}     

		/*
		 * Create table for statistic
		 * 
		 */

		/**
		* Plugin usage statistics
		*/
		if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."usage_stat")) {
			dbDelta("CREATE TABLE `".$wpPrefix.GMP_DB_PREF."usage_stat` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(64) NOT NULL,
			  `visits` int(11) NOT NULL DEFAULT '0',
			  `spent_time` int(11) NOT NULL DEFAULT '0',
			  `modify_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  UNIQUE INDEX `code` (`code`),
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8");
			dbGmp::query("INSERT INTO `".$wpPrefix.GMP_DB_PREF."usage_stat` (code, visits) VALUES ('installed', 1)");
		}
        update_option($wpPrefix. GMP_DB_PREF. 'db_version', GMP_VERSION);
		add_option($wpPrefix. GMP_DB_PREF. 'db_installed', 1);
		dbGmp::query("UPDATE `".$wpPrefix.GMP_DB_PREF."options` SET value = '". GMP_VERSION. "' WHERE code = 'version' LIMIT 1");
                  
        installerDbUpdaterGmp::runUpdate();
		//$time = microtime(true) - $start;	// Speed debug info
	}
	static public function setUsed() {
		update_option(GMP_DB_PREF. 'plug_was_used', 1);
	}
	static public function isUsed() {
		return (bool)get_option(GMP_DB_PREF. 'plug_was_used');
	}
	/**
	 * Create pages for plugin usage
	 */
	static public function createPages() {
		return false;
	}
	
	/**
	 * Return page data from given array, searched by title, used in self::createPages()
	 * @return mixed page data object if success, else - false
	 */
	static private function _getPageByTitle($title, $pageArr) {
		foreach($pageArr as $p) {
			if($p->title == $title)
				return $p;
		}
		return false;
	}
	static public function delete() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$deleteOptions = reqGmp::getVar('deleteAllData');
		if(is_null($deleteOptions)) {
			frameGmp::_()->getModule('options')->getView()->displayDeactivatePage();
			exit();
		}
		if((bool)$deleteOptions){
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."modules`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."icons`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."maps`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."options`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."htmltype`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."markers`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."marker_groups`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."options_categories`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."modules_type`");
		   $wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.GMP_DB_PREF."usage_stat`");

		   delete_option('gmp_def_icons_installed');
		   delete_option(GMP_DB_PREF. 'db_version');
		   delete_option($wpPrefix.GMP_DB_PREF.'db_installed');
		   //delete_option(GMP_DB_PREF. 'plug_was_used');       
		}
	}
	static protected function _addPageToWP($post_title, $post_parent = 0) {
		return wp_insert_post(array(
			 'post_title' => langGmp::_($post_title),
			 'post_content' => langGmp::_($post_title. ' Page Content'),
			 'post_status' => 'publish',
			 'post_type' => 'page',
			 'post_parent' => $post_parent,
			 'comment_status' => 'closed'
		));
	}
	static public function update() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$currentVersion = get_option($wpPrefix. GMP_DB_PREF. 'db_version', 0);
		$installed = (int) get_option($wpPrefix. GMP_DB_PREF. 'db_installed', 0);
		if(!$currentVersion || version_compare(GMP_VERSION, $currentVersion, '>')) {
			self::init();
			update_option($wpPrefix. GMP_DB_PREF. 'db_version', GMP_VERSION);
		}
	}
}
