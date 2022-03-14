<?php

namespace Models\Lists;

class MysqlImagesListClass extends AbstractDBListClass {

	protected function getListFromSource() {
		if (!isset($this->params['id'])) return;

		$sql = ''.
			'select id_object_image, image_small_url, image_large_url '.
			'from object_images '.
			'where id_object = '.$this->params['id'].' '.
			'order by id_object_image '.
			'';
		$this->list = $this->source->multiSelect($sql);
	}

}