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
}