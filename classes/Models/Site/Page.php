<?php

abstract class Page {
	protected $data = [];
	protected $tpls = [];

	function __construct($settings) {
		$this->data = array_merge($settings, $_POST, $_GET);
	}

	function run() {
		$this->fillTpls();
		$view = new View();
		foreach ($this->tpls as $name => $v) $view->$name = $v;
		$view->setTpl(TRL_PATH . 'general.tpl');
		return $view;
	}

	function fillTpls() {
		$this->tpls['content'] = $this->getContent();
	}

	abstract function getContent();

}

