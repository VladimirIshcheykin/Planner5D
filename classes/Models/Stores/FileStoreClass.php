<?php

namespace Models\Stores;

class FileStoreClass implements InterfaceStore {

	private $file, $data;

	public function __construct($file, $data) {
		$this->file = $file;
		$this->data = $data;
	}

	public function save() {
		if (empty($this->file)) return;

		$h = fopen($this->file, "x");
		foreach ($this->data as $v) {
			fwrite($h, implode("\t", $v)."\n");
		}
		fclose($h);

	}

}
