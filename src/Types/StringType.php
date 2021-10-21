<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class StringType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'string';

	public static function validate($value, bool $allow_null = false) : Returner {
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		if(is_string($value)) {
			return Returner::valid($value);
		}
		return Returner::invalid(static::Name);
	}
}