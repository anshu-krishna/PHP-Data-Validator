<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class NumberType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'number';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(($f = filter_var($value, FILTER_VALIDATE_FLOAT)) !== false) {
			return Returner::valid($f);
		}
		if(($f = filter_var($value, FILTER_VALIDATE_INT)) !== false) {
			return Returner::valid($f);
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}