<?php

require_once(CLASSES_PATH . 'Models/Site/Page.php');
require_once(CLASSES_PATH . 'View/ViewPager.php');
class ListPage extends Page {

	function getContent() {
		$view = new View();
		$view->setTpl(TRL_PATH . 'list.tpl');
		$view->assign('items', $this->getItems());
		$view->assign('url', Cfg__get('params', 'url').(isset($this->data['path'])?$this->data['path']:''));
		$view->assign('url_image', IMG_DIR);
		$view->pager = $this->_getViewPager();

		return $view;
	}

	function getItems() {
		$limit = '';
		if (Cfg__get('params', 'pages')) {
			$limit = 'limit '.($this->getPage() - 1)*Cfg__get('params', 'items_per_page').', '.Cfg__get('params', 'items_per_page');
		}

		$sql = ''.
			'select id_object, name, views '.
			'from objects '.
			'order by id_object '.
			($limit?' '.$limit:'').
		'';
		$items = DBDriver::getDriver()->multiSelect($sql);

		return $items;
	}

	function getAmountOfPages() {
		$sql = ''.
			'select count(*) as c '.
			'from objects '.
			'';
		return ceil(DBDriver::getDriver()->fieldSelect($sql)/Cfg__get('params', 'items_per_page'));
	}

	function getPage() {
		$page = 0;
		if (isset($this->data['page'])) $page = (int) $this->data['page'];
		if ($page == 0) $page = 1;
		return $page;
	}

	private function _getViewPager() {
		$view = new ViewPager();
		$view->assign('url', Cfg__get('params', 'url').(isset($this->data['path'])?$this->data['path']:''));
		$view->assign('page', $this->getPage());
		$view->assign('pages', $this->getAmountOfPages());
		return $view;
	}

}

