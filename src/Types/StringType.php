<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class StringType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'string';
	private static function can_be_string($var) {
		return $var === null || is_scalar($var) || (is_object($var) && method_exists($var, '__toString'));
	}

	public static function validate($value, bool $allow_null = false) : Returner {
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		if(is_string($value)) {
			return Returner::valid($value);
		}
		return static::can_be_string($value) ? Returner::valid(strval($value)) : Returner::invalid(static::Name);
	}
}