<?php

class View {
	protected $_tpl = null, $_widgets = [], $_vars = [];

	function display() {
		if ($this->_tpl) include($this->_tpl);
	}

	function setTpl($tpl) {
		if ($tpl && file_exists($tpl)) $this->_tpl = $tpl;
	}

	function fetch() {
		ob_start();
		$this->display();
		$res = ob_get_contents();
		ob_end_clean();
		return $res;
	}

	/**@return ViewObjects | View*/
	function __get($name) {
		return isset($this->_widgets[$name]) ? $this->_widgets[$name] : new View();
	}

	function __set($name, $value) {
		if ($value instanceof View) $this->_widgets[$name] = $value;
	}

	function addWidget($widget, $isFirst = false) {
		if ($widget instanceof View) {
			if ($isFirst) array_unshift($this->_widgets, $widget);
			else $this->_widgets[] = $widget;
		}
	}

	/**@return View*/
	function assign($key, $value) {
		$this->_vars[$key] = $value;
		return $this;
	}

	function get($key, $default = null) {
		if (isset($this->_vars[$key])) return $this->_vars[$key];
		return $default;
	}

}

