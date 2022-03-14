<?php

namespace Models\Stores;

use Models\Sources\InterfaceSource;

class ImageStoreClass implements InterfaceStore {

	private $source, $file;

	public function __construct(InterfaceSource $source, $file) {
		$this->source = $source->init();
		$this->file = $file;
		return $this;
	}

	public function save() {
		if (empty($this->file)||empty($this->source)) return;

		if (file_exists($this->file)) unlink($this->file);
		file_put_contents($this->file, $this->source);
	}

}