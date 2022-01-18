<?php
namespace Krishna\DataValidator;

class ObjectHandler {
	public readonly array $list;
	private static function parse_key_string(string $k) {
		$op = ($k[0] ?? '') === '?';
		if($op) {
			return [substr($k, 1), true];
		}
		return [$k, false];
	}
	public function __construct(object $list, public readonly bool $trimOutOfBound = false) {
		$error = [];
		$ret_list = [];
		foreach($list as $key=>&$item) {
			$info = [];
			list($key, $info['op']) = self::parse_key_string($key);
			try {
				$info['handler'] = match(true) {
					is_string($item) => new TypeHandler($item),
					is_array($item) => new ArrayHandler($item, $this->trimOutOfBound),
					is_object($item) => new static($item, $this->trimOutOfBound),
					default => throw new MultiLinedException('{' . strval($item) . '}; Invalid value', $key)
				};
				$ret_list[$key] = $info;
			} catch (MultiLinedException $th) {
				foreach($th->getInfo() as $m) {
					$error[] = "[{$key}]: {$m}";
				}
			}
			if(count($error) > 0) {
				throw new MultiLinedException($error);
			}
		}
		$this->list = $ret_list;
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
		foreach($this->list as $key => $info) {
			if(property_exists($val, $key)) {
				$found[] = $key;
				$res = ($info['handler'])->validate($val->{$key});
				if($res->valid) {
					$ret[$key] = $res->value;
				} else {
					$error[$key] = $res->error;
				}
			} elseif($info['op']) {
				continue;
			} else {
				$error[$key] = "Missing";
			}
		}
		if(!$this->trimOutOfBound) {
			foreach(array_diff($all_keys, $found) as $k) {
				$error[$k] = "Out of bound";
			}
		}
		if(count($error) === 0) {
			return Returner::valid($ret);
		}
		return Returner::invalid($error);
	}
}