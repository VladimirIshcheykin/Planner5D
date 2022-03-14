<?php

namespace Pages;

use Views;

class Page {

	public function getView($data) {
		$view = new Views\View();
		foreach ($data as $param => $value) {
			if (strpos($param, 'view_') !== false) {
				$sub_view = substr($param, 5);
				$view->$sub_view = $this->getView($value);
			}
			elseif ($param == 'tpl') {
				$view->setTpl($value);
			}
			else {
				$view->assign($param, $value);
			}
		}
		return $view;
	}

}

