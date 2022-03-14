<?php

namespace Models\Lists;

use Models\Sources\InterfaceSource;

abstract class AbstractDBListClass extends AbstractListClass {

	protected $params = [], $count;

	public function __construct(InterfaceSource $source, $params = []) {
		$this->setParams($params);
		parent::__construct($source);
		// connection db
	}

	public function getCount() {
		return $this->count;
	}

	private function setParams($params) {
		$this->params = $params;
	}

}
