<?php

require_once(dirname(__FILE__) . '/system/define.php');
require_once(dirname(__FILE__) . '/system/require.php');

try {
	$path = urldecode($_SERVER['REQUEST_URI']);

    // First comment
	if (strpos($path, '/') === 0) $path = substr($path, 1);
	$ind = strpos($path, "?");
	if ($ind !== false) $path = substr($path, 0, $ind);
	unset($ind);

	if ((isset($_GET['id']))&&((int)$_GET['id'] > 0)) {
		$action = 'DetailPage';
	}
	else {
		$action = 'ListPage';
	}
	require_once(CLASSES_PATH . 'Models/Site/'.$action.'.php');
	(new $action(['path' => (string) $path]))->run()->display();
} catch (Exception $e){
	echo $e->getMessage();
}

