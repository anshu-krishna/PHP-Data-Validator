<?php
namespace Krishna\DataValidator;

class _ObjectHandler_ {
	public function __construct(private array $_list) {}
	private static function _parse_key_string(string $k) {
		$op = ($k[0] ?? '') === '?';
		if($op) {
			return [substr($k, 1), true];
		}
		return [$k, false];
	}
	public static function create(object $list) : Returner {
		$ret = [];
		$error = [];
		foreach($list as $key=>$item) {
			$info = [];
			list($key, $info['op']) = self::_parse_key_string($key);
			if(is_string($item)) {
				$item = _TypeHandler_::create($item);
			} elseif(is_array($item)) {
				$item = _ArrayHandler_::create($item);
			} elseif(is_object($item)) {
				$item = self::create($item);
			} else {
				$error[$key] = '{' . strval($item) . '}; Invalid value';
				continue;
			}
			if($item->valid) {
				$info['handler'] = $item->value;
				$ret[$key] = $info;
			} else {
				$error[$key] = $item->value;
			}
		}
		if(count($error) === 0) {
			return Returner::valid(new self($ret));
		} else {
			return Returner::invalid($error);
		}
	}
	public function validate($val) : Returner {
		if(is_array($val)) {
			$val = (object) $val;
		} elseif(!is_object($val)) {
			return Returner::invalid('Expecting array|object');
		}
		$error = [];
		$ret = [];
		$all_keys = array_keys(get_object_vars($val));
		$found = [];
		foreach($this->_list as $key => $info) {
			if(property_exists($val, $key)) {
				$found[] = $key;
				$res = ($info['handler'])->validate($val->{$key});
				if($res->valid) {
					$ret[$key] = $res->value;
				} else {
					$error[$key] = $res->value;
				}
			} elseif($info['op']) {
				continue;
			} else {
				$error[$key] = "Missing";
			}
		}
		foreach(array_diff($all_keys, $found) as $k) {
			$error[$k] = "Out of bound";
		}
		if(count($error) === 0) {
			return Returner::valid($ret);
		}
		return Returner::invalid($error);
	}
}