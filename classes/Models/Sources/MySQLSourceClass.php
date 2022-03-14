<?php

namespace Models\Sources;

use Models\Drivers\InterfaceDBDriver;

class MySQLSourceClass implements InterfaceSource {
	private $driver;

	public function __construct(InterfaceDBDriver $driver) {
		$this->driver = $driver;
		return $this;
	}

	public function init() {
		$this->driver->setConnection();
		return $this->driver;
		// подключаемся к бд
	}
}
