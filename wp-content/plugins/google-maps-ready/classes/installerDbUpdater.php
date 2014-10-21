<?php
class installerDbUpdaterGmp {
	static public function runUpdate() {
		self::update_044();
		self::update_046();
		self::update_05();
		self::update_105();
		self::update_109();
		self::update_117();
	}
	
	public static function update_044(){
		dbGmp::query("INSERT INTO `@__modules` (id, code, active, type_id, params, has_tab, label, description)
			VALUES (NULL, 'promo_ready', 1, 1, '', 0, 'Promo Ready', 'Promo Ready');");
	}
	public static function update_046(){
		dbGmp::query("ALTER TABLE `@__icons`
			ADD column `title` VARCHAR(100), 
			ADD column `description` text;");
	}
	public static function update_05(){
		dbGmp::query("ALTER TABLE `@__markers` ADD column `params` text;");
		
		dbGmp::query("insert into `@__options` (`code`,`value`,`label`) VALUES('save_statistic','0','Save Statistic')");

		dbGmp::query("insert into `@__options` (`code`,`value`,`label`) VALUES
			('infowindow_size','". utilsGmp::serialize(array('width'=>'100','height'=>'100')). "','Info Window Size')");
	}
	public static function update_105() {
		dbGmp::query("INSERT INTO `@__modules` (id, code, active, type_id, params, has_tab, label, description)
			VALUES (NULL, 'csv', 1, 1, '', 0, 'csv', 'csv')");
	}
	public static function update_109() {
		dbGmp::query("INSERT INTO `@__modules` (id, code, active, type_id, params, has_tab, label, description)
			VALUES (NULL, 'gmap_widget', 1, 1, '', 0, 'gmap_widget', 'gmap_widget')");
	}
	public static function update_117() {
		dbGmp::query("UPDATE @__options SET value_type = 'array' WHERE code = 'infowindow_size' LIMIT 1");
	}
}