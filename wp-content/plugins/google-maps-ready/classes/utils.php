<?php
class utilsGmp {
    static public function jsonEncode($arr) {
        return (is_array($arr) || is_object($arr)) ? json_encode_utf_normal($arr) : json_encode_utf_normal(array());
    }
    static public function jsonDecode($str) {
        if(is_array($str))
            return $str;
        if(is_object($str))
            return (array)$str;
        return empty($str) ? array() : json_decode($str, true);
    }
    static public function unserialize($data) {
        return unserialize($data);
    }
    static public function serialize($data) {
        return serialize($data);
    }
    static public function createDir($path, $params = array('chmod' => NULL, 'httpProtect' => false)) {
        if(@mkdir($path)) {
            if(!is_null($params['chmod'])) {
                @chmod($path, $params['chmod']);
            }
            if(!empty($params['httpProtect'])) {
                self::httpProtectDir($path);
            }
            return true;
        }
        return false;
    }
    static public function httpProtectDir($path) {
        $content = 'DENY FROM ALL';
        if(strrpos($path, DS) != strlen($path))
            $path .= DS;
        if(file_put_contents($path. '.htaccess', $content)) {
            return true;
        }
        return false;
    }
    /**
     * Copy all files from one directory ($source) to another ($destination)
     * @param string $source path to source directory
     * @params string $destination path to destination directory
     */
    static public function copyDirectories($source, $destination) {
        if(is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
                if ( $readdirectory == '.' || $readdirectory == '..' ) {
                    continue;
                }
                $PathDir = $source . '/' . $readdirectory; 
                if (is_dir($PathDir)) {
                    utilsGmp::copyDirectories( $PathDir, $destination . '/' . $readdirectory );
                    continue;
                }
                copy( $PathDir, $destination . '/' . $readdirectory );
            }
            $directory->close();
        } else {
            copy( $source, $destination );
        }
    }
    static public function getIP() {
        return (empty($_SERVER['HTTP_CLIENT_IP']) ? (empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR']) : $_SERVER['HTTP_CLIENT_IP']);
    }
    
    /**
     * Parse xml file into simpleXML object
     * @param string $path path to xml file
     * @return mixed object SimpleXMLElement if success, else - false
     */
    static public function getXml($path) {
        if(is_file($path)) {
            return simplexml_load_file($path);
        }
        return false;
    }
    /**
     * Check if the element exists in array
     * @param array $param 
     */
    static public function xmlAttrToStr($param, $element) {
        if (isset($param[$element])) {
            // convert object element to string
            return (string)$param[$element];
        } else {
            return '';
        }
    }
    static public function xmlNodeAttrsToArr($node) {
        $arr = array();
        foreach($node->attributes() as $a => $b) {
            $arr[$a] = utilsGmp::xmlAttrToStr($node, $a);
        }
        return $arr;
    }
    static public function deleteFile($str) {
        return @unlink($str);
    }
    static public function deleteDir($str){
        if(is_file($str)){
            return self::deleteFile($str);
        }
        elseif(is_dir($str)){
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path){
                utilsGmp::deleteDir($path);
            }
            return @rmdir($str);
        }
    }
    /**
     * Retrives list of directories ()
     */
    static public function getDirList($path) {
        $res = array();
        if(is_dir($path)){
            $files = scandir($path);
            foreach($files as $f) {
                if($f == '.' || $f == '..' || $f == '.svn') continue;
                if(!is_dir($path. $f))                      continue;
                $res[$f] = array('path' => $path. $f. DS);
            }
        }
        return $res;
    }
    /**
     * Retrives list of files
     */
    static public function getFilesList($path) {
        $files = array();
        if(is_dir($path)){
            $dirHandle = opendir($path);
            while(($file = readdir($dirHandle)) !== false) {
                if($file != '.' && $file != '..' && $f != '.svn' && is_file($path. DS. $file)) {
                    $files[] = $file;
                }
            }
        }
        return $files;
    }
    /**
     * Check if $var is object or something another in future
     */
    static public function is($var, $what = '') {
        if (!is_object($var)) {
            return false;
        }
        if(get_class($var) == $what) {
            return true;
        }
        return false;
    }
    /**
     * Get array with all monthes of year, uses in paypal pro and sagepay payment modules for now, than - who knows)
     * @return array monthes
     */
    static public function getMonthesArray() {
        static $monthsArray = array();
        //Some cache
        if(!empty($monthsArray))
            return $monthsArray;
        for ($i=1; $i<13; $i++) {
            $monthsArray[sprintf('%02d', $i)] = strftime('%B', mktime(0,0,0,$i,1,2000));
        }
        return $monthsArray;
    }
    /**
     * Get an array with years range from current year
     * @param int $from - how many years from today ago
     * @param int $to - how many years in future
     * @param $formatKey - format for keys in array, @see strftime
     * @param $formatVal - format for values in array, @see strftime
     * @return array - years 
     */
    static public function getYearsArray($from, $to, $formatKey = '%Y', $formatVal = '%Y') {
        $today = getdate();
        $yearsArray = array();
        for ($i=$today['year']-$from; $i <= $today['year']+$to; $i++) {
            $yearsArray[strftime($formatKey,mktime(0,0,0,1,1,$i))] = strftime($formatVal,mktime(0,0,0,1,1,$i));
        }
        return $yearsArray;
    }
    /**
     * Make replacement in $text, where it will be find all keys with prefix ":" and replace it with corresponding value
     * @see email_templatesModel::renderContent()
     * @see checkoutView::getSuccessPage()
     */
    static public function makeVariablesReplacement($text, $variables) {
        if(!empty($text) && !empty($variables) && is_array($variables)) {
            foreach($variables as $k => $v) {
                $text = str_replace(':'. $k, $v, $text);
            }
            return $text;
        }
        return false;
    }
    static public function getCurrentWPThemeDir() {
        static $themePath;
        if(empty($themePath)) {
            $themePath = get_theme_root(). DS. utilsGmp::getCurrentWPThemeCode(). DS; 
        }
        return $themePath;
    }
    /**
     * Retrive full directory of plugin
     * @param string $name - plugin name
     * @return string full path in file system to plugin directory
     */
    static public function getPluginDir($name = '') {
        return WP_PLUGIN_DIR. DS. $name. DS;
    }
    static public function getPluginPath($name = '') {
        return WP_PLUGIN_URL. '/'. $name. '/';
    }
    static public function getExtModDir($plugName) {
        if(strpos($plugName, 'wp-content/themes') !== false) {  //for modules in theme dir
            return ABSPATH. $plugName. DS;
        } else
            return self::getPluginDir($plugName);
    }
    static public function getExtModPath($plugName) {
        if(strpos($plugName, 'wp-content/themes') !== false) {  //for modules in theme dir
            return GMP_SITE_URL. str_replace(DS, '/', $plugName). '/';
        } else
            return self::getPluginPath($plugName);
    }
    static public function getCurrentWPThemePath() {
        return get_template_directory_uri();
    }
    static public function getCurrentWPThemeCode() {
        static $activeThemeName;
        if(empty($activeThemeName)) {
            $url = get_bloginfo('template_url');
            $temp = explode('wp-content/themes/', $url);
            $activeThemeName = $temp[1];    // The second value will be the theme name
        }
        return $activeThemeName;
    }
    static public function isThisCommercialEdition() {
        /*$commercialModules = array('rating');
        foreach($commercialModules as $m) {
            if(!frameGmp::_()->getModule($m)) 
                return false;
            if(!is_dir(frameGmp::_()->getModule($m)->getModDir())) 
                return false;
        }
        return true;*/
        foreach(frameGmp::_()->getModules() as $m) {
            if(is_object($m) && $m->isExternal()) // Should be at least one external module
                return true;
        }
        return false;
    }
    static public function checkNum($val, $default = 0) {
        if(!empty($val) && is_numeric($val))
            return $val;
        return $default;
    }
    static public function checkString($val, $default = '') {
        if(!empty($val) && is_string($val))
            return $val;
        return $default;
    }
    /**
     * Retrives extension of file
     * @param string $path - path to a file
     * @return string - file extension
     */
    static public function getFileExt($path) {
        return strtolower( pathinfo($path, PATHINFO_EXTENSION) );
    }
    static public function getRandStr($length = 10, $allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') {
        $result = '';
        $allowedCharsLen = strlen($allowedChars);

        while(strlen($result) < $length) {
          $result .= substr($allowedChars, rand(0, $allowedCharsLen), 1);
        }

        return $result;
    }
    /**
     * Get current host location
     * @return string host string
     */
    static public function getHost() {
        return $_SERVER['HTTP_HOST'];
    }
    /**
     * Check if device is mobile
     * @return bool true if user are watching this site from mobile device
     */
    static public function isMobile() {
        return mobileDetect::_()->isMobile();
    }
    /**
     * Check if device is tablet
     * @return bool true if user are watching this site from tablet device
     */
    static public function isTablet() {
        return mobileDetect::_()->isTablet();
    }
    static public function getWidgetCategory($callback) {
        $categoryToId = array(
            'products' => array('toealsopurchasedwidget', 'toebestsellerswidget', 'toefpwidget', 'toemostviewedwidget', 'toerecentproductswidget', 'toespwidget'),
            'shopping' => array('toebcwidget', 'toecurrencywidget', 'toeshoppingcartwidget'),
            'additional' => array('toebrcwidget', 'toecommentswidget', 'toesearchwidget', 'toesliderwidget', 'toetwitterwidget'),
        );
        foreach($categoryToId as $cat => $ids) {
            if(in_array($callback, $ids))
                return $cat;
        }
        return false;
    }
    static public function getUploadsDir() {
        $uploadDir = wp_upload_dir();
        return $uploadDir['basedir'];
    }
    static public function getUploadsPath() {
        $uploadDir = wp_upload_dir();
        return $uploadDir['baseurl'];
    }
    static public function arrToCss($data) {
        $res = '';
        if(!empty($data)) {
            foreach($data as $k => $v) {
                $res .= $k. ':'. $v. ';';
            }
        }
        return $res;
    }
    /**
     * Activate all GMP Plugins
     * 
     * @return NULL Check if it's site or multisite and activate.
     */
    public function activatePlugin() 
    {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            $orig_id = $wpdb->blogid;
            $blog_id = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_id as $id) {
                if (switch_to_blog($id)) {
                    installerGmp::init();
                } 
            }
            switch_to_blog($orig_id);
            return;
        } else {
            installerGmp::init();
        }
    }

    /**
     * Deactivate All GMP Plugins
     * 
     * @return NULL Check if it's site or multisite and decativate it.
     */
    public function deactivatePlugin() 
    {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            $orig_id = $wpdb->blogid;
            $blog_id = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_id as $id) {
                if (switch_to_blog($id)) {
                    installerGmp::delete();
                } 
            }
            switch_to_blog($orig_id);
            return;
        } else {
            installerGmp::delete();
        }
    }
	
	static public function isWritable($filename) {
		return is_writable($filename);
	}
	
	static public function isReadable($filename) {
		return is_readable($filename);
	}
	
	static public function fileExists($filename) {
		return file_exists($filename);
	}
	public static function listToJson($array){
		return str_replace("'","\'", str_replace('\\','\\\\',utilsGmp::jsonEncode($array)));
	}
	static public function getMaxUploadFileSize($returnFormat = '') {
		static $upload_size;
        if (!$upload_size) {
            $max_upload = (int) self::returnBytes((@ini_get('upload_max_filesize')));
            $max_post = (int) self::returnBytes((@ini_get('post_max_size')));
            $memory_limit = (int) self::returnBytes((@ini_get('memory_limit')));
            $upload_size = min($max_upload, $max_post, $memory_limit);
        }
		if(!empty($returnFormat)) {
			return self::returnBytesFormatted($upload_size, $returnFormat);
		}
        return $upload_size;
	}
	static public function returnBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
	static public function returnBytesFormatted($val, $format) {
		$format = strtolower($format);
		switch($format) {
			case 'g':
                $val /= 1024;
            case 'm':
                $val /= 1024;
            case 'k':
                $val /= 1024;
		}
		return $val;
	}
}
