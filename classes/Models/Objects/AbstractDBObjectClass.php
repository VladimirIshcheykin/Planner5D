<?php

namespace Models\Objects;

use Models\Sources\InterfaceSource;

abstract class AbstractDBObjectClass extends AbstractObjectClass {

	protected $id;

	public function __construct(InterfaceSource $source, $id = 0) {
		$this->setId($id);
		parent::__construct($source);
		// connection mysql
	}

	public function setId($id) {
		$this->id = $id;
		// set id
	}

	public function getId() {
		return $this->id;
		// get id
	}

}
