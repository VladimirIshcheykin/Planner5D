<?php

namespace Models\Drivers;

interface InterfaceDBDriver {
	public function setConnection();
	public function query($sql);
}
