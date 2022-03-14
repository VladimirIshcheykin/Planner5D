<?php

namespace Models\Lists;

use Models\Sources\InterfaceSource;

abstract class AbstractListClass implements InterfaceList {

	protected $source, $list = [];

	public function __construct(InterfaceSource $source) {
		$this->source = $source->init();
		$this->getListFromSource();
		// получили подключение к базе - curl / mysql
	}

	abstract protected function getListFromSource();

	public function getData() {
		return $this->list;
	}

}
