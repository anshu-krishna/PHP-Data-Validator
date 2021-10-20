<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class IntType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'int';

	public static function validate($value, bool $allow_null = false) : Returner {
		$f = filter_var($value, FILTER_VALIDATE_INT);
		if($f === false) {
			if($allow_null) {
				return NullType::validate($value);
			}
			return Returner::invalid(static::Name);
		}
		return Returner::valid($f);
	}
}