<?php
namespace Krishna\DataValidator;

class _ArrayHandler_ {
	private function __construct(private bool $_single, private array $_list) {}

	public static function create(array $list) : Returner {
		$ret = [];
		$error = [];
		$i = -1;
		foreach($list as $item) {
			++$i;
			if(is_string($item)) {
				$item = _TypeHandler_::create($item);
			} elseif(is_array($item)) {
				$item = self::create($item);
			} elseif(is_object($item)) {
				$item = _ObjectHandler_::create($item);
			} else {
				$error[$i] = '{' . strval($item) . '}; Invalid value';
				continue;
			}
			if($item->valid) {
				$ret[] = $item->value;
			} else {
				$error[$i] = $item->value;
			}
		}
		if(count($error) > 0) {
			return Returner::invalid($error);
		} else {
			return Returner::valid(new self(count($list) === 1, $ret));
		}
	}
	public function validate($vals) : Returner {
		if(!is_array($vals)) {
			return Returner::invalid('Expecting array');
		}
		$ret = [];
		$error = [];
		$found = [];
		foreach($vals as $key => $val) {
			$found[] = $key;
			$handler = $this->_list[$this->_single ? 0 : $key] ?? false;
			if($handler === false) {
				$error[$key] = 'Out of bounds';
				continue;
			}
			$res = $handler->validate($vals[$key]);
			if($res->valid) {
				$ret[$key] = $res->value;
			} else {
				$error[$key] = $res->value;
			}
		}
		if(!$this->_single) {
			foreach(array_diff(range(0, count($this->_list) - 1), $found) as $k) {
				$error[$k] = "Missing";
			}
		}
		if(count($error) === 0) {
			return Returner::valid($ret);
		}
		ksort($error);
		return Returner::invalid($error);
	}
}