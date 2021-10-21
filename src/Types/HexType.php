<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class HexType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'hex';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(is_string($value) && preg_match("/^[0-9a-f]+$/i", $value)) {
			return Returner::valid(hexdec($value));
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}