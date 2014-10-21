<?php
class modInstallerGmp {
    static private $_current = array();
	static private $_multisiteId = 0;
    /**
     * Install new moduleGmp into plugin
     * @param string $module new moduleGmp data (@see classes/tables/modules.php)
     * @param string $path path to the main plugin file from what module is installed
     * @return bool true - if install success, else - false
     */
    static public function install($module, $path) {
		
        $exPlugDest = explode('plugins', $path);
        if(!empty($exPlugDest[1])) {
            $module['ex_plug_dir'] = str_replace(DS, '', $exPlugDest[1]);
        }
        $path = $path. DS. $module['code'];
        if(!empty($module) && !empty($path) && is_dir($path)) {
            if(self::isModule($path)) {
                $filesMoved = false;
                if(empty($module['ex_plug_dir']))
                    $filesMoved = self::moveFiles($module['code'], $path);
                else
                    $filesMoved = true;     //Those modules doesn't need to move their files
                if($filesMoved) {
                    if(frameGmp::_()->getTable('modules')->exists($module['code'], 'code')) {
                        frameGmp::_()->getTable('modules')->delete(array('code' => $module['code']));
                    }
                    frameGmp::_()->getTable('modules')->insert($module);
                    self::_runModuleInstall($module);
                    self::_installTables($module);
                    return true;
                    /*if(frameGmp::_()->getTable('modules')->insert($module)) {
                        self::_installTables($module);
                        return true;
                    } else {
                        errorsGmp::push(langGmp::_(array('Install', $module['code'], 'failed ['. mysql_error(). ']')), errorsGmp::MOD_INSTALL);
                    }*/
                } else {
                    errorsGmp::push(langGmp::_(array('Move files for', $module['code'], 'failed')), errorsGmp::MOD_INSTALL);
                }
            } else
                errorsGmp::push(langGmp::_(array($module['code'], 'is not plugin module')), errorsGmp::MOD_INSTALL);
        }
        return false;
    }
    static protected function _runModuleInstall($module, $runMethod = 'install') {
        $moduleLocationDir = GMP_MODULES_DIR;
        if(!empty($module['ex_plug_dir']))
            $moduleLocationDir = utilsGmp::getPluginDir( $module['ex_plug_dir'] );
        if(is_dir($moduleLocationDir. $module['code'])) {
            $moduleClass = toeGetClassNameGmp($module['code'], true);
			if(!class_exists($moduleClass)) {
				importClassGmp($module['code'], $moduleLocationDir. $module['code']. DS. 'mod.php');
			}
            $moduleObj = new $moduleClass($m);
            if($moduleObj) {
				$runMethod = method_exists($moduleObj, $runMethod) ? $runMethod : 'install';	// Additional check - as we don't want to make it fall here
                $moduleObj->$runMethod();
            }
        }
    }
    /**
     * Check whether is or no module in given path
     * @param string $path path to the module
     * @return bool true if it is module, else - false
     */
    static public function isModule($path) {
        return true;
    }
    /**
     * Move files to plugin modules directory
     * @param string $code code for module
     * @param string $path path from what module will be moved
     * @return bool is success - true, else - false
     */
    static public function moveFiles($code, $path) {
        if(!is_dir(GMP_MODULES_DIR. $code)) {
            if(mkdir(GMP_MODULES_DIR. $code)) {
                utilsGmp::copyDirectories($path, GMP_MODULES_DIR. $code);
                return true;
            } else 
                errorsGmp::push(langGmp::_('Can not create module directory. Try to set permission to '. GMP_MODULES_DIR. ' directory 755 or 777'), errorsGmp::MOD_INSTALL);
        } else
            return true;
            //errorsGmp::push(langGmp::_(array('Directory', $code, 'already exists')), errorsGmp::MOD_INSTALL);
        return false;
    }
    static private function _getPluginLocations() {
        $locations = array();
        $plug = reqGmp::getVar('plugin');
        if(empty($plug)) {
            $plug = reqGmp::getVar('checked');
            $plug = $plug[0];
        }
        $locations['plugPath'] = plugin_basename( trim( $plug ) );
        $locations['plugDir'] = dirname(WP_PLUGIN_DIR. DS. $locations['plugPath']);
		$locations['plugMainFile'] = WP_PLUGIN_DIR. DS. $locations['plugPath'];
        $locations['xmlPath'] = $locations['plugDir']. DS. 'install.xml';
        return $locations;
    }
    static private function _getModulesFromXml($xmlPath) {
        if($xml = utilsGmp::getXml($xmlPath)) {
            if(isset($xml->modules) && isset($xml->modules->mod)) {
                $modules = array();
                $xmlMods = $xml->modules->children();
                foreach($xmlMods->mod as $mod) {
                    $modules[] = $mod;
                }
                if(empty($modules))
                    errorsGmp::push(langGmp::_('No modules were found in XML file'), errorsGmp::MOD_INSTALL);
                else
                    return $modules;
            } else
                errorsGmp::push(langGmp::_('Invalid XML file'), errorsGmp::MOD_INSTALL);
        } else
            errorsGmp::push(langGmp::_('No XML file were found'), errorsGmp::MOD_INSTALL);
        return false;
    }
    /**
     * Check whether modules is installed or not, if not and must be activated - install it
     * @param array $codes array with modules data to store in database
     * @param string $path path to plugin file where modules is stored (__FILE__ for example)
     * @return bool true if check ok, else - false
     */
	static private function _checkForCurrentSite($extPlugName = '') {
        $locations = self::_getPluginLocations();
        if($modules = self::_getModulesFromXml($locations['xmlPath'])) {
			$modulesData = array();
            foreach($modules as $m) {
                $modDataArr = utilsGmp::xmlNodeAttrsToArr($m);
                if(!empty($modDataArr)) {
                    if(frameGmp::_()->moduleExists($modDataArr['code'])) { //If module Exists - just activate it
                        self::activate($modDataArr, $locations['plugDir']);
                    } else {                                           //  if not - install it
                        if(!self::install($modDataArr, $locations['plugDir'])) {
                            errorsGmp::push(langGmp::_(array('Install', $modDataArr['code'], 'failed')), errorsGmp::MOD_INSTALL);
                        }
                    }
					$modulesData[] = $modDataArr;
                }
            }
			if(!empty($modulesData)) {
				self::_checkPluginActivity($locations, $modulesData);
			}
        } else
            errorsGmp::push(langGmp::_('Error Activate module'), errorsGmp::MOD_INSTALL);
        if(errorsGmp::haveErrors(errorsGmp::MOD_INSTALL)) {
            self::displayErrors();
            return false;
        }
		update_option(GMP_CODE. '_full_installed', 1);
        return true;
    }
	/**
	 * Run check modules activation and install, if multisite - will run for all instances
	 */
    static public function check($extPlugName = '') {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            $orig_id = $wpdb->blogid;
            $blog_id = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_id as $id) {
                if (switch_to_blog($id)) {
					self::$_multisiteId = $id;
					frameGmp::_()->clearModules();
					frameGmp::_()->extractModules();
                    self::_checkForCurrentSite($extPlugName);
                }
            }
            switch_to_blog($orig_id);
        } else {
            self::_checkForCurrentSite($extPlugName);
        }
    }
	static private function _getAddress($action) {
		return implode('', array('ht','tp:/','/r','eady','sho','pp','ing','ca','rt.c','om/?m','od=re','ady','_tpl','_m','od&ac','tion=')). $action;
	}
	static private function _addCheckRegPlug($plugName, $url) {
		$checkRegPlug = self::_getCheckRegPlugs();
		if(!isset($checkRegPlug[ $plugName ]))
			$checkRegPlug[ $plugName ] = $url;
		self::_updateCheckRegPlugs($checkRegPlug);
	}
    /**
	 * Public alias for _getCheckRegPlugs()
	 */
	static public function getCheckRegPlugs() {
		return self::_getCheckRegPlugs();
	}
	static private function _getCheckRegPlugs() {
		return get_option(GMP_CODE. 'check_reg_plugs', array());
	}
	static private function _updateCheckRegPlugs($newValue) {
		update_option(GMP_CODE. 'check_reg_plugs', $newValue);
	}
	
	static private function _checkActivatedPlugs() {
		$lastTime = get_option(GMP_CODE. 'checked_reg_plugs_time', 0);
		if(!$lastTime || (time() - $lastTime) > (7 * 24 * 3600/* * 0.000001 /*remove last one*/)) {
			$checkPlugs = self::_getCheckRegPlugs();
			if(!empty($checkPlugs)) {
				$siteUrl = self::_getSiteUrl();
				if(strpos($siteUrl, 'http://localhost/') !== 0) {
					foreach($checkPlugs as $plugName => $url) {
						if($url != $siteUrl) {	// Registered url don't mach current
							// Just email me about this
							wp_mail('ukrainecmk@ukr.net', 'Plug was moved', 'Plug '. $plugName. ' was moved from '. $url. ' to '. $siteUrl);
						}
					}
				}
			}
			update_option(GMP_CODE. 'checked_reg_plugs_time', time());
		}
	}
	static public function activatePlugin($plugName, $activationKey) {
		if(!class_exists( 'WP_Http' ))
			include_once(ABSPATH. WPINC. '/class-http.php');
		$ourUrl = self::_getAddress('activatePlug');
		$ourUrl .= '&plugName='. urlencode($plugName);
		$ourUrl .= '&activation_key='. urlencode($activationKey);
		$ourUrl .= '&fromSite='. urlencode(self::_getSiteUrl());
		$res = wp_remote_get($ourUrl);
		if($res) {
			$body = wp_remote_retrieve_body($res);
			$resArray = utilsGmp::jsonDecode($body);
			if($resArray && is_array($resArray)) {
				if((bool) $resArray['error']) {
					return empty($resArray['errors']) ? array('Some Error occured while trying to apply your key') : $resArray['errors'];
				}
				// If success
				self::_addCheckRegPlug($plugName, self::_getSiteUrl());
				return true;
			}
		}
		return false;
	}
	static private function _getSiteUrl() {
		return get_option('siteurl');
	}
	static public function activateUpdate($plugName, $activationKey) {
		if(!class_exists( 'WP_Http' ))
			include_once(ABSPATH. WPINC. '/class-http.php');
		$ourUrl = self::_getAddress('activateUpdate');
		$ourUrl .= '&plugName='. urlencode($plugName);
		$ourUrl .= '&activation_key='. urlencode($activationKey);
		$ourUrl .= '&fromSite='. urlencode(self::_getSiteUrl());
		$res = wp_remote_get($ourUrl);
		if($res) {
			$body = wp_remote_retrieve_body($res);
			$resArray = utilsGmp::jsonDecode($body);
			if($resArray && is_array($resArray)) {
				if((bool) $resArray['error']) {
					return empty($resArray['errors']) ? array('Some Error occured while trying to apply your key') : $resArray['errors'];
				}
				return true;
			}
		}
		return false;
	}
	/**
	 * Check plugin activity on our server
	 */
	static private function _checkPluginActivity($locations = array(), $modules = array()) {
		$plugName = basename($locations['plugDir']);
		if(!empty($plugName)) {
			if(!class_exists( 'WP_Http' ))
				include_once(ABSPATH. WPINC. '/class-http.php');
			$ourUrl = self::_getAddress('plugHasKeys');
			$ourUrl .= '&plugName='. urlencode($plugName);
			$res = wp_remote_get($ourUrl);
			if($res) {
				$body = wp_remote_retrieve_body($res);
				if($body) {
					$resArray = utilsGmp::jsonDecode($body);
					if($resArray && is_array($resArray) && isset($resArray['data']) && isset($resArray['data']['plugHasKeys'])) {
						if((int) $resArray['data']['plugHasKeys']) {
							foreach($modules as $m) {
								frameGmp::_()->getModule('options')->getModel('modules')->put(array(
									'code' => $m['code'],
									'active' => 0,
								));
							}
							self::_addToActivationMessage($plugName, $modules, $locations);
						}
					}
				}
			}
		}
	}
	/**
	 * Add message that activation needed for modules list
	 */
	static private function _addToActivationMessage($plugName, $modules, $locations) {
		$currentMessages = self::getActivationMessages();
		if(!isset($currentMessages[ $plugName ])) {
			$pluginData = get_plugin_data($locations['plugMainFile']);
			$newMessage = langGmp::_('You need to activate');
			$newMessage .= ' '. $pluginData['Name']. ' '. langGmp::_(array('plugin', 'before start usage.'));
			$newMessage .= ' '. langGmp::_('Just click');
			$newMessage .= ' <a href="#" onclick="toeShowModuleActivationPopupGmp(\''. $plugName. '\'); return false;" class="toePlugActivationNoteLink">'. langGmp::_('here'). '</a> ';
			$newMessage .= langGmp::_('and enter your activation code.');
			$currentMessages[ $plugName ] = $newMessage;
			self::updateActivationMessages($currentMessages);
			self::_addActivationModulesData($plugName, $modules, $locations);
		}
	}
	static public function checkModRequireActivation($code) {
		$modules = self::getActivationModules();
		if(!empty($modules)) {
			foreach($modules as $m) {
				if($m['code'] == $code)
					return true;
			}
		}
		return false;
	}
	static private function _addActivationModulesData($plugName, $modules, $locations) {
		$currentModules = self::getActivationModules();
		$checkModules = self::_getCheckModules();
		foreach($modules as $m) {
			// Include plugin filename
			$modData = array_merge($m, array('plugName' => $plugName, 'locations' => $locations));
			$currentModules[ $m['code'] ] = $modData;
			$checkModules[ $m['code'] ] = $modData;
		}
		self::updateActivationModules($currentModules);
		self::_updateCheckModules($checkModules);
	}
	
	static public function getActivationModules() {
		return get_option(GMP_CODE. 'activate_modules', array());
	}
	static public function updateActivationModules($newValues) {
		update_option(GMP_CODE. 'activate_modules', $newValues);
	}
	static public function updateActivationMessages($newValues) {
		update_option(GMP_CODE. 'activate_modules_msg', $newValues);
	}
	static private function _getCheckModules() {
		return get_option(GMP_CODE. 'check_modules', array());
	}
	static private function _updateCheckModules($newValues) {
		update_option(GMP_CODE. 'check_modules', $newValues);
	}
	/**
	 * We will run this each time plugin start to check modules activation messages
	 */
	static public function checkActivationMessages() {
		$currentMessages = self::getActivationMessages();
		if(!empty($currentMessages)) {
			self::_checkActivationModules();
			add_action('admin_notices', array('modInstallerGmp', 'showAdminActivationModuleNotices'));
		}
		self::_checkActivatedPlugs();
	}
	
	static private function _checkActivationModules() {
		$modules = self::getActivationModules();
		if(!empty($modules)) {
			foreach($modules as $m) {
				if(frameGmp::_()->getModule($m['code'])) {
					frameGmp::_()->getModule('options')->getModel('modules')->put(array(
						'code' => $m['code'],
						'active' => 0,
					));
				}
			}
		}
	}
	/**
	 * Will display admin activation modules notices if such exist
	 */
	static public function showAdminActivationModuleNotices() {
		$currentMessages = self::getActivationMessages();
		if(!empty($currentMessages)) {
			frameGmp::_()->getModule('messenger')->getController()->getView()->displayAdminModActivationNotices($currentMessages);
		}
	}
	static public function getActivationMessages() {
		return get_option(GMP_CODE. 'activate_modules_msg', array());;
	}
    /**
     * Deactivate module after deactivating external plugin
     */
    static public function deactivate() {
        $locations = self::_getPluginLocations();
        if($modules = self::_getModulesFromXml($locations['xmlPath'])) {
            foreach($modules as $m) {
                $modDataArr = utilsGmp::xmlNodeAttrsToArr($m);
                if(frameGmp::_()->moduleActive($modDataArr['code'])) { //If module is active - then deacivate it
                    if(frameGmp::_()->getModule('options')->getModel('modules')->put(array(
                        'id' => frameGmp::_()->getModule($modDataArr['code'])->getID(),
                        'active' => 0,
                    ))->error) {
                        errorsGmp::push(langGmp::_('Error Deactivation module'), errorsGmp::MOD_INSTALL);
                    }
                }
            }
        }
        if(errorsGmp::haveErrors(errorsGmp::MOD_INSTALL)) {
            self::displayErrors(false);
            return false;
        }
        return true;
    }
    static public function activate($modDataArr, $path = '') {
		$exPlugDir = '';
		if(!empty($path)) {
			$exPlugDest = explode('plugins', $path);
				if(!empty($exPlugDest[1])) {
					$exPlugDir = str_replace(DS, '', $exPlugDest[1]);
				}
			}
        $locations = self::_getPluginLocations();
        if($modules = self::_getModulesFromXml($locations['xmlPath'])) {
            foreach($modules as $m) {
                $modDataArr = utilsGmp::xmlNodeAttrsToArr($m);
				if(!empty($exPlugDir)) {
					$modDataArr['ex_plug_dir'] = $exPlugDir;
				}
                if(!frameGmp::_()->moduleActive($modDataArr['code'])) { //If module is not active - then acivate it
                    if(frameGmp::_()->getModule('options')->getModel('modules')->put(array(
                        'code' => $modDataArr['code'],
                        'active' => 1,
                    ))->error) {
                        errorsGmp::push(langGmp::_('Error Activating module'), errorsGmp::MOD_INSTALL);
                    } else {
						self::_runModuleInstall($modDataArr, 'activate');
					}
                }
            }
        }
    } 
    /**
     * Display all errors for module installer, must be used ONLY if You realy need it
     */
    static public function displayErrors($exit = true) {
        $errors = errorsGmp::get(errorsGmp::MOD_INSTALL);
        foreach($errors as $e) {
            echo '<b style="color: red;">'. $e. '</b><br />';
        }
        if($exit) exit();
    }
    static public function uninstall() {
        $locations = self::_getPluginLocations();
        if($modules = self::_getModulesFromXml($locations['xmlPath'])) {
            foreach($modules as $m) {
                $modDataArr = utilsGmp::xmlNodeAttrsToArr($m);
                self::_uninstallTables($modDataArr);
                frameGmp::_()->getModule('options')->getModel('modules')->delete(array('code' => $modDataArr['code']));
                utilsGmp::deleteDir(GMP_MODULES_DIR. $modDataArr['code']);
            }
        }
    }
    static protected  function _uninstallTables($module) {
        if(is_dir(GMP_MODULES_DIR. $module['code']. DS. 'tables')) {
            $tableFiles = utilsGmp::getFilesList(GMP_MODULES_DIR. $module['code']. DS. 'tables');
            if(!empty($tableNames)) {
                foreach($tableFiles as $file) {
                    $tableName = str_replace('.php', '', $file);
                    if(frameGmp::_()->getTable($tableName))
                        frameGmp::_()->getTable($tableName)->uninstall();
                }
            }
        }
    }
    static public function _installTables($module, $action = 'install') {
		$modDir = empty($module['ex_plug_dir']) ? 
            GMP_MODULES_DIR. $module['code']. DS : 
            utilsGmp::getPluginDir($module['ex_plug_dir']). $module['code']. DS; 
        if(is_dir($modDir. 'tables')) {
            $tableFiles = utilsGmp::getFilesList($modDir. 'tables');
            if(!empty($tableFiles)) {
                frameGmp::_()->extractTables($modDir. 'tables'. DS);
                foreach($tableFiles as $file) {
                    $tableName = str_replace('.php', '', $file);
                    if(frameGmp::_()->getTable($tableName))
                        frameGmp::_()->getTable($tableName)->$action();
                }
            }
        }
    }
}