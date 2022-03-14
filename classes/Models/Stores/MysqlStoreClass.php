<?php

namespace Models\Stores;

use Models\Sources\InterfaceSource;

class MysqlStoreClass implements InterfaceStore {

	private $source, $table, $data;

	public function __construct(InterfaceSource $source, $table, $data) {
		$this->source = $source->init();
		$this->table = $table;
		$this->data = $data;
		return $this;
	}

	public function save() {
		$ids = [];
		foreach ($this->data as $d) {
			$keys = []; $values = [];
			foreach ($d as $k => $v) {
				$keys[] = '`'.$k.'`';
				$values[] = '"'.$v.'"';
			}
			$sql = 'insert into '.$this->table.' ('.implode(', ', $keys).') values ('.implode(', ', $values).')';
			echo $sql.'<br>';
			$ids[] = $this->source->simpleQuery($sql);
		}
		return $ids;
	}

}