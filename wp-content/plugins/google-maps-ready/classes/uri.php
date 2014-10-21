<?php
class uriGmp {
	/**
	 * Tell link form method to replace symbols for special html caracters only for ONE output
	 */
	static private $_oneHtmlEnc = false;
    static public function fileToPageParam($file) {
        $file = str_replace(DS, '/', $file);
        return substr($file, strpos($file, GMP_PLUG_NAME));
    }
    static public function _($params) {
        global $wp_rewrite;
        $link = '';
		if(is_string($params) && strpos($params, 'http') === 0) {
			if(self::isHttps())
				$params = self::makeHttps($params);
			return $params;
		} elseif(is_array($params) && isset($params['page_id'])) {
            if(is_null($wp_rewrite)) {
                $wp_rewrite = new WP_Rewrite();
            }
            $link = get_page_link($params['page_id']);
            unset($params['page_id']);
        } elseif(isset($params['baseUrl'])) {
            $link = $params['baseUrl'];
            unset($params['baseUrl']);
        } else {
            $link = GMP_URL;
        }
        if(!empty($params)) {
            $query = is_array($params) ? http_build_query($params) : $params;
            $link .= (strpos($link, '?') === false ? '?' : '&'). $query;
        }
		if(self::$_oneHtmlEnc) {
			$link = str_replace('&', '&amp;', $link);
			self::$_oneHtmlEnc = false;
		}
        return $link;
    }
    static public function _e($params) {
        echo self::_($params);
    }
    static public function page($id) {
        return get_page_link($id);
    }
    static public function getGetParams($exclude = array()) {
        $res = array();
        if(isset($_GET) && !empty($_GET)) {
            foreach($_GET as $key => $val) {
                if(in_array($key, $exclude)) continue;
                $res[$key] = $val;
            }
        }
        return $res;
    }
    static public function mod($name, $view = '', $action = '', $data = NULL) {
        if(is_admin())
            $params = array('page' => $name);
        else
            $params = array('mod' => $name);
        if($view)
            $params['viewGmp'] = $view;
        if($action)
            $params['action'] = $action;
        if($data) {
            if(is_array($data)) {
                $params = array_merge($params, $data);
            } elseif(is_string($data)) {
                $params = http_build_query($params);
                $params .= '&'. $data;
            }
        }
        return self::_($params);
    }
    static public function atach($params) {
        $getData = self::getGetParams();
        if(!empty($getData)) {
            if(is_array($params))
                $params = array_merge($getData, $params);
            else
                $params = http_build_query($getData). '&'. $params;
        }
        return self::_($params);
    }
    /**
     * Get current path
     * @return string current link
     */
    static public function getCurrent() {
        if (!empty($_SERVER['HTTPS'])) {
            return 'https://'. $_SERVER['HTTP_HOST']. $_SERVER['SCRIPT_NAME'];
        } else {
            return 'http://'. $_SERVER['HTTP_HOST']. $_SERVER['SCRIPT_NAME'];
        }
    }
	/**
	 * Replace symbols to special html caracters in one output
	 */
	static public function oneHtmlEnc() {
		self::$_oneHtmlEnc = true;
	}
	static public function makeHttps($link) {
		if(strpos($link, 'https:') === false) {
			$link = str_replace('http:', 'https:', $link);
		}
		return $link;
	}
	static public function isHttps() {
		return is_ssl();
		//return (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on');
	}
}

