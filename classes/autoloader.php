<?php

/**Функция подключает классы, если при их вызове они не были найдены*/
spl_autoload_register(function ($class) {
	$file = CLASSES_PATH . str_replace('\\', '/', $class) . '.php';
	if (file_exists($file)) {
		require_once $file;
		return true;
	}
	return false;
});
