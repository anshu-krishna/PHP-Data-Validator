<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class BoolType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'bool';

	public static function validate($value, bool $allow_null = false) : Returner {
		$f = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if($f === null) {
			if($allow_null) {
				return NullType::validate($value);
			}
			return Returner::invalid(static::Name);
		}
		return Returner::valid($f);
	}
}