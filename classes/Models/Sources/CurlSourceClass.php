<?php

namespace Models\Sources;

class CurlSourceClass implements InterfaceSource {

	private $page;
	static private $_sources = [];

	public function __construct($page) {
		$this->page = $page;
		return $this;
	}

	public function init() {
		if (empty($this->page)) return '';

		if (isset(self::$_sources[$this->page])) return self::$_sources[$this->page];


		echo 'Page - '.$this->page;
		echo '<br>';

		$ch = curl_init($this->page);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$html = curl_exec($ch);
		curl_close($ch);
		// качаем страницу

		self::$_sources[$this->page] = $html;

		//var_dump($html);

		return $html;
	}

}
