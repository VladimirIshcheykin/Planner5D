<?php

namespace Views;

class View {
	protected $_tpl = null, $_vars = [];

	public function display() {
		if ($this->_tpl) include($this->_tpl);
	}

	public function setTpl($tpl) {
		if ($tpl && file_exists($tpl)) $this->_tpl = $tpl;
	}

	public function fetch() {
		ob_start();
		$this->display();
		$res = ob_get_contents();
		ob_end_clean();
		return $res;
	}

	public function assign($key, $value) {
		$this->_vars[$key] = $value;
		return $this;
	}

	public function get($key, $default = null) {
		if (isset($this->_vars[$key])) return $this->_vars[$key];
		return $default;
	}

}

