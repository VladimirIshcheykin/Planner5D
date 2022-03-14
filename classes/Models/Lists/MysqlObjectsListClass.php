<?php

namespace Models\Lists;

class MysqlObjectsListClass extends AbstractDBListClass {

	protected function getListFromSource() {
		$limit = '';
		if ($page = $this->getPage()) {
			$limit = 'limit '.($page - 1)*Cfg__get('params', 'items_per_page').', '.Cfg__get('params', 'items_per_page');
		}

		$sql = ''.
			'select id_object, name, views, url, image_preview_url '.
			'from objects '.
			'order by id_object '.
			($limit?' '.$limit:'').
			'';
		$this->list = $this->source->multiSelect($sql);

		$this->setCount();
	}

	private function setCount() {
		if ($this->getPage()) {
			$sql = ''.
				'select count(*) as c '.
				'from objects '.
				'';
			$this->count = $this->source->fieldSelect($sql);
		}
		else {
			$this->count = count($this->list);
		}
	}

	private function getPage() {
		if (!isset($this->params['page'])) return false;
		$page = (int) $this->params['page'];
		if ($page == 0) $page = 1;
		return $page;
	}

}