<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class IntType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'int';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(($f = filter_var($value, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_OCTAL | FILTER_FLAG_ALLOW_HEX)) !== false) {
			return Returner::valid($f);
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}