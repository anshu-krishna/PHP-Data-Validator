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
		$i = -1;
		foreach($list as $key=>$item) {
			++$i;
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
				$info['val'] = $item->value;
				$ret[$key] = $info;
			} else {
				$error[$key] = $item->value;
			}
		}
		if(count($error) > 0) {
			return Returner::invalid($error);
		} else {
			return Returner::valid(new self($ret));
		}
	}
}