<?php

namespace Models\Objects;

class MysqlObjectClass extends AbstractDBObjectClass {

	public function getObjectFromSource() {
		if (!isset($this->id)) return;

		$sql = ''.
			'select t.id_object, t.name, t.views, t.image_large_url, t.image_small_url, t.image_preview_url, t.url '.
			'from objects t '.
			'where t.id_object = '.$this->id.' '.
			'limit 1 '.
			'';
		$this->params = $this->source->rowSelect($sql);
	}

}
