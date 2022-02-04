<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class NumberType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'number';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(($f = FloatType::validate($value))->valid) {
			return $f;
		}
		if(($f = IntType::validate($value))->valid) {
			return $f;
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}