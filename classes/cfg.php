<?php
/*Created by Vladimir (10.02.2022)*/

function Cfg__get($arrName, $key = null, $default = null) {
	static $_cfg = array();
	static $_cfg = array();
	if (!isset($_cfg[$arrName])){
		$_cfg[$arrName] = array();
		if (file_exists(CFG_PATH . 'config_' . $arrName . '.php'))
			$_cfg[$arrName] = include(CFG_PATH . 'config_' . $arrName . '.php');
	}
	if ($key === null) return isset($_cfg[$arrName]) ? $_cfg[$arrName] : $default;
	return isset($_cfg[$arrName][$key]) ? $_cfg[$arrName][$key] : $default;
}