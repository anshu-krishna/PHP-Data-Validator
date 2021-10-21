<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class TimestampType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'timestamp';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(is_string($value) && ($f = strtotime($value)) !== false) {
			return Returner::valid($f);
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}