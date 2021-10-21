<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class UnsignedType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'unsigned';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(($f = IntType::validate($value))->valid && $f->value >= 0) {
			return $f;
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}