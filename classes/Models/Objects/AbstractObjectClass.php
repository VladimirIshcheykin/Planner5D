<?php

namespace Models\Objects;

use Models\Sources\InterfaceSource;

abstract class AbstractObjectClass implements InterfaceObject {

	protected $source, $params = [];

	public function __construct(InterfaceSource $source) {
		$this->source = $source->init();
		$this->getObjectFromSource();
		// получили подключение к базе - curl / mysql
	}

	abstract protected function getObjectFromSource();

	public function getData() {
		return $this->params;
	}

}
