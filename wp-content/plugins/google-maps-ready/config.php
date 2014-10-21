<?php
    global $wpdb;
    if (WPLANG == '') {
        define('GMP_WPLANG', 'en_GB');
    } else {
        define('GMP_WPLANG', WPLANG);
    }
    if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

    define('GMP_PLUG_NAME', basename(dirname(__FILE__)));
    define('GMP_DIR', WP_PLUGIN_DIR. DS. GMP_PLUG_NAME. DS);
    define('GMP_TPL_DIR', GMP_DIR. 'tpl'. DS);
    define('GMP_CLASSES_DIR', GMP_DIR. 'classes'. DS);
    define('GMP_TABLES_DIR', GMP_CLASSES_DIR. 'tables'. DS);
	define('GMP_HELPERS_DIR', GMP_CLASSES_DIR. 'helpers'. DS);
    define('GMP_LANG_DIR', GMP_DIR. 'lang'. DS);
    define('GMP_IMG_DIR', GMP_DIR. 'img'. DS);
    define('GMP_TEMPLATES_DIR', GMP_DIR. 'templates'. DS);
    define('GMP_MODULES_DIR', GMP_DIR. 'modules'. DS);
    define('GMP_FILES_DIR', GMP_DIR. 'files'. DS);
    define('GMP_ADMIN_DIR', ABSPATH. 'wp-admin'. DS);
	$siteUrl = get_bloginfo('wpurl');
	$pluginUrl = WP_PLUGIN_URL;
	if(is_ssl()) {
		if(strpos($siteUrl, 'https') === false) {
			$siteUrl = str_replace('http:', 'https:', $siteUrl);
		}
		if(strpos($pluginUrl, 'https') === false) {
			$pluginUrl = str_replace('http:', 'https:', $pluginUrl);
		}
	}
	define('GMP_SITE_URL', $siteUrl. '/');
    define('GMP_JS_PATH', $pluginUrl.'/'.basename(dirname(__FILE__)).'/js/');
    define('GMP_CSS_PATH', $pluginUrl.'/'.basename(dirname(__FILE__)).'/css/');
    define('GMP_IMG_PATH', $pluginUrl.'/'.basename(dirname(__FILE__)).'/img/');
    define('GMP_MODULES_PATH', $pluginUrl.'/'.basename(dirname(__FILE__)).'/modules/');
    define('GMP_TEMPLATES_PATH', $pluginUrl.'/'.basename(dirname(__FILE__)).'/templates/');
    define('GMP_JS_DIR', GMP_DIR. 'js/');

    define('GMP_URL', GMP_SITE_URL);

    define('GMP_LOADER_IMG', GMP_IMG_PATH. 'loading-cube.gif');
	define('GMP_TIME_FORMAT', 'H:i:s');
    define('GMP_DATE_DL', '/');
    define('GMP_DATE_FORMAT', 'm/d/Y');
    define('GMP_DATE_FORMAT_HIS', 'm/d/Y ('. GMP_TIME_FORMAT. ')');
    define('GMP_DATE_FORMAT_JS', 'mm/dd/yy');
    define('GMP_DATE_FORMAT_CONVERT', '%m/%d/%Y');
    define('GMP_WPDB_PREF', $wpdb->prefix);
    define('GMP_DB_PREF', 'gmp_');
    define('GMP_MAIN_FILE', 'gmp.php');

    define('GMP_DEFAULT', 'default');
    define('GMP_CURRENT', 'current');
    
    
    define('GMP_PLUGIN_INSTALLED', true);
    define('GMP_VERSION', '1.2.5.2');
    define('GMP_USER', 'user');
    
    define('GMP_CLASS_PREFIX', 'gmpc');        
    define('GMP_FREE_VERSION', false);
    
    define('GMP_API_UPDATE_URL', 'http://somereadyapiupdatedomain.com');
    
    define('GMP_SUCCESS', 'Success');
    define('GMP_FAILED', 'Failed');
    define("GMP_LNG_CODE","gmp");
	define('GMP_ERRORS', 'gmpErrors');
	
	define('GMP_THEME_MODULES', 'theme_modules');
	
	
	define('GMP_ADMIN',	'admin');
	define('GMP_LOGGED','logged');
	define('GMP_GUEST',	'guest');
	
	define('GMP_ALL',		'all');
	
	define('GMP_METHODS',		'methods');
	define('GMP_USERLEVELS',	'userlevels');
	/**
	 * Framework instance code, unused for now
	 */
	define('GMP_CODE', 'gmp');
	/**
	 * Plugin name
	 */
	define('GMP_WP_PLUGIN_NAME', 'Ready! Google Maps Plugin');
        