<?php
namespace Krishna\DataValidator;

class ObjectHandler {
	public readonly array $list;
	private static function parse_key_string(string $k) {
		$op = $k[0] ?? '';		
		/* Return structure [key, action_when_missing]
		action_when_missing:
			0: Error
			1: Optional
			2: Set default value to null
		*/
		if($op === '?') {
			$def = $k[1] ?? '';
			if($def === '?') {
				return [substr($k, 2), 2];
			} else {
				return [substr($k, 1), 1];
			}
		}
		return [$k, 0];
	}
	public function __construct(object $list, public readonly OutOfBoundAction $on_out_of_bound = OutOfBoundAction::Error) {
		$error = [];
		$ret_list = [];
		foreach($list as $key=>&$item) {
			$info = [];
			list($key, $info['act']) = self::parse_key_string($key);
			try {
				$info['handler'] = match(true) {
					is_string($item) => new TypeHandler($item),
					is_array($item) => new ArrayHandler($item, $this->on_out_of_bound),
					is_object($item) => new static($item, $this->on_out_of_bound),
					default => throw new ComplexException('{' . strval($item) . '}; Invalid value', $key)
				};
				$ret_list[$key] = $info;
			} catch (ComplexException $th) {
				foreach($th->getInfo() as $m) {
					$error[] = "[{$key}]: {$m}";
				}
			}
		}
		if(count($error) > 0) {
			throw new ComplexException($error);
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
			} else {
				switch($info['act']) {
					case 0:
						$error[$key] = "Missing";
						break;
					case 1:
						continue 2;
					case 2:
						$ret[$key] = null;
						break;
				}
			}
		}
		switch($this->on_out_of_bound) {
			case OutOfBoundAction::Trim: break;
			case OutOfBoundAction::Keep:
				foreach(array_diff($all_keys, $found) as $k) {
					$ret[$k] = $val->{$k};
				}
				break;
			case OutOfBoundAction::Error:
				foreach(array_diff($all_keys, $found) as $k) {
					$error[$k] = "Out of bound";
				}
				break;
		}
		if(count($error) === 0) {
			return Returner::valid($ret);
		}
		return Returner::invalid($error);
	}
}