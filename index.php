<?php

require_once(dirname(__FILE__) . '/system/define.php');
require_once(dirname(__FILE__) . '/system/require.php');


use Models\Drivers as Drivers;
use Models\Sources as Sources;
use Models\Lists as Lists;
use Models\Objects as Objects;


$path = urldecode($_SERVER['REQUEST_URI']);
if (strpos($path, '/') === 0) $path = substr($path, 1);
$ind = strpos($path, "?");
if ($ind !== false) $path = substr($path, 0, $ind);
unset($ind);


$driver = new Drivers\MysqlDriver;
$mysql_source = new Sources\MysqlSourceClass($driver);



if ((isset($_GET['id']))&&((int)$_GET['id'] > 0)) {

	$id_object = (int)$_GET['id'];

	$object_params = (new Objects\MysqlObjectClass($mysql_source, $id_object))->getData();

	if (!empty($object_params)) {

		$images_list = (new Lists\MysqlImagesListClass($mysql_source, ['id' => $id_object]))->getData();

		$params = [
			'tpl' => TRL_PATH . 'general.tpl',
			'view_content' => [
				'tpl' => TRL_PATH . 'detail.tpl',
				'id_object' => $id_object,
				'item' => $object_params,
				'images' => $images_list,
			],
		];

	}
	else {

		$params = [
			'tpl' => TRL_PATH . 'general.tpl',
			'view_content' => [
				'tpl' => TRL_PATH . 'detail_empty.tpl',
			],
		];

	}

	$view = (new Pages\Page())->getView($params);
	$view->display();


}
else {

	$page = isset($_GET['page'])?(int)$_GET['page']:1;

	$objects = (new Lists\MysqlObjectsListClass($mysql_source, ['page' => $page]));
	$objects_list = $objects->getData();
	$objects_count = $objects->getCount();

	$params = [
		'tpl' => TRL_PATH . 'general.tpl',
		'view_content' => [
			'tpl' => TRL_PATH . 'list.tpl',
			'items' => $objects_list,
			'url' => Cfg__get('params', 'url').$path,
			'view_pager' => [
				'tpl' => TRL_PATH . 'pager.tpl',
				'url' => Cfg__get('params', 'url').$path,
				'page' => $page,
				'pages' => ceil($objects_count/Cfg__get('params', 'items_per_page')),
			],
		],
	];
	$view = (new Pages\Page())->getView($params);
	$view->display();

}
