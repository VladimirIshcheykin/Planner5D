<?php

/**Функция подключает классы, если при их вызове они не были найдены*/
spl_autoload_register(function ($class) {
	$file = CLASSES_PATH . $class.'.php';
	if (file_exists($file)) {
		require $file;
		return true;
	}
	$file = CLASSES_PATH . 'Models/' . $class.'.php';
	if (file_exists($file)) {
		require $file;
		return true;
	}
	return false;
});
