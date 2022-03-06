<?php

class DBDriver {
	/**@var mysqli*/
	static private $_mysqli = null;

	/**
	 * @return ModelsDriverBDSimpleDriver
	 */
	static function getDriver() {
		static $drivers = array();
		$className = static::class;
		if (!isset($drivers[$className])) $drivers[$className] = new static();
		return $drivers[$className];
	}

	function getMysqli(){
		if (!self::$_mysqli) {
			self::$_mysqli = new mysqli(Cfg__get('db', 'host'), Cfg__get('db', 'username'), Cfg__get('db', 'password'), Cfg__get('db', 'database'));
			if (self::$_mysqli->connect_errno) {
				throw new Exception('Could not select database');
			}
			self::$_mysqli->set_charset('utf8');
		}
		return self::$_mysqli;
	}

	/**
	 * @param $sql
	 * @return bool|mysqli_result
	 * @throws Exception
	 */
	function query($sql) {
		$result = $this->getMysqli()->query($sql);

		$queryResult = ($result !== false);
		if (!$queryResult) {
			throw new Exception('Database error: '.$this->getMysqli()->errno);
		}

		return $result;
	}

	/**
	 * @param $sql
	 * @return bool|mysqli_result
	 */
	final protected function _multi_query($sql) {
		$result = $this->getMysqli()->multi_query($sql);
		return $result;
	}

	function real_escape_string($s) {
		return $this->getMysqli()->real_escape_string($s);
	}

	function multiSelect($sql) {
		$result = $this->query($sql);
		if ($result) {
			$return = $result->fetch_all(MYSQLI_ASSOC);
			$result->free();
			return $return;
		}
		throw new Exception('Request error ' . self::$_mysqli->error . ' in ' . $sql);
		return array();
	}

	function multiSelectKey($sql, $key) {
		$result = $this->query($sql);
		if ($result) {
			$return = array();
			while ($row = $result->fetch_assoc()) $return[$row[$key]] = $row;
			$result->free();
			return $return;
		}
		throw new Exception('Request error ' . self::$_mysqli->error . ' in ' . $sql);
		return array();
	}

	function simpleQuery($sql) {
		$result = $this->query($sql);
		if ($result) {
			if (stripos($sql, 'insert ') !== false)
				return self::$_mysqli->insert_id;
			else
				return self::$_mysqli->affected_rows;
		}
		throw new Exception('Request error ' . self::$_mysqli->error . ' in ' . $sql);
		return false;
	}

	function rowSelect($sql) {
		$result = $this->query($sql);
		if ($result) {
			$row = $result->fetch_assoc();
			$result->free();
			return $row;
		}
		throw new Exception('Request error ' . self::$_mysqli->error . ' in ' . $sql);
		return array();
	}

	function queryCol($sql) {
		$result = $this->query($sql);
		if ($result) {
			$return = array();
			while ($row = $result->fetch_row()) $return[] = array_shift($row);
			$result->free();
			return $return;
		}
		throw new Exception('Request error ' . self::$_mysqli->error . ' in ' . $sql);
		return array();
	}

	function fieldSelect($sql) {
		$result = $this->rowSelect($sql);
		return $result ? array_shift($result) : false;
	}

	function multiQuery($sqls){
		/*возвращает многомерный массив. Каждый элемент массива - результат по каждому запросу(одна строка)*/
		$return = array();
		if ($this->_multi_query($sqls)){
			$i = 0;
			do {
				$return[$i] = array();
				if ($res = self::$_mysqli->store_result()) {
					$return[$i] = $res->fetch_all(MYSQLI_ASSOC);
					$res->free();
				}
				$i++;
			} while ($this->_nextResult($sqls));
			return $return;
		}
		throw new Exception('Request error ' . self::$_mysqli->error . ' in multiQuery');
		return array();
	}

	private function _nextResult($sqls){
		if (!self::$_mysqli->more_results()) return false;
		if (self::$_mysqli->next_result()) return true;
		throw new Exception('Request error ' . self::$_mysqli->error . ' in multiQuery');
		return false;
	}

}

