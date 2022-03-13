<?php

require_once(dirname(__FILE__) . '/system/define.php');
require_once(dirname(__FILE__) . '/system/require.php');

$max_pages = 3;

use Models\Drivers as Drivers;
use Models\Sources as Sources;
use Models\Lists as Lists;
use Models\Objects as Objects;
use Models\Stores as Stores;


$driver = new Drivers\MysqlDriver;
$mysql_source = new Sources\MysqlSourceClass($driver);

(new Import\ImportClear($mysql_source))->run();


$page = 1;
do {
	echo 'Page '.P_URL.(($page > 1)?'?page='.$page:'').' proceeding' . "\n";
	$plannerUrl = P_URL.(($page > 1)?'?page='.$page:'');


	$objects_list = (new Lists\CurlObjectsListClass(new Sources\CurlSourceClass($plannerUrl)))->getData();

	var_dump($objects_list);
	echo '<br>';
	echo '<br>';
	echo '<br>';

	foreach ($objects_list as $object) {
		echo $object['url'];
		echo '<br>';
		$object_params = (new Objects\CurlObjectClass(new Sources\CurlSourceClass($object['url'])))->getData();
		$object_params['url'] = $object['url'];
		$object_params['image_preview_url'] = $object['image_preview_url'];
		//$object_params = (new Objects\MysqlObjectClass($mysql_source, $object['id_object']))->getData();
		echo 'object_params<br>';
		var_dump([$object_params]);
		echo '<br>';

		$id = (new Stores\MysqlStoreClass($mysql_source, 'objects', [$object_params]))->save();
		echo 'id object_params<br>';
		var_dump($id);
		echo '<br>';

		$id_object = $id[0];
		echo 'id_object<br>';
		echo $id_object;
		echo '<br>';

		$images_list = (new Lists\CurlImagesListClass(new Sources\CurlSourceClass($object['url'])))->getData();
		array_walk($images_list, function(&$value, $key, $id_object){$value = $value+['id_object' => $id_object];}, $id_object);
		//$images_list = (new Lists\MysqlImagesListClass($mysql_source, ['id' => $object['id_object']]))->getData();
		echo 'images_list<br>';
		var_dump($images_list);
		echo '<br>';

		$ids = (new Stores\MysqlStoreClass($mysql_source, 'object_images', $images_list))->save();
		echo 'ids images_list<br>';
		var_dump($ids);
		echo '<br>';
		echo '<br>';

	}

	$page++;
} while ($page <= $max_pages);

