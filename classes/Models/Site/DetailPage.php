<?php

require_once(CLASSES_PATH . 'Models/Site/Page.php');
class DetailPage extends Page {

	function getContent() {
		$item = $this->getItem();
		$view = new View();
		if (empty($item)) {
			$view->setTpl(TRL_PATH . 'detail_empty.tpl');
		}
		else {
			$view->setTpl(TRL_PATH . 'detail.tpl');
			$view->assign('id_object', (int)$this->data['id']);
			$view->assign('item', $item);
			$view->assign('images', $this->getImages());
			$view->assign('url_image', IMG_DIR);
		}
		return $view;
	}

	function getItem()	{
		if (!isset($this->data['id'])) return [];

		$sql = ''.
			'select t.id_object, t.name, t.views, '.
				'( '.
					'select id_object_image '.
					'from object_images '.
					'where id_object = t.id_object '.
					'order by id_object_image '.
					'limit 1 '.
			') as id_object_image '.
			'from objects t '.
			'where t.id_object = '.(int)$this->data['id'].' '.
			'limit 1 '.
		'';
		$item = DBDriver::getDriver()->rowSelect($sql);

		return $item;
	}

	function getImages()	{
		if (!isset($this->data['id'])) return [];

		$sql = ''.
			'select id_object_image, image_small_url, image_large_url '.
			'from object_images '.
			'where id_object = '.$this->data['id'].' '.
			'order by id_object_image '.
		'';
		$item = DBDriver::getDriver()->multiSelect($sql);

		return $item;
	}

}



