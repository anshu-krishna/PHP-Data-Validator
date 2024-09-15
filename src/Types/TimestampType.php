<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class TimestampType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'timestamp';

	public static function validate($value, bool $allow_null = false) : Returner {
		// is int and is valid unix epoch
		if(is_int($value)) {
			return Returner::valid($value);
		}

		if(is_string($value)) {
			// is string and is valid unix epoch
			if(preg_match('/^-?\d+$/', $value)) {
				return Returner::valid((int)$value);
			}
			if(($f = strtotime($value)) !== false) {
				return Returner::valid($f);
			}
		}

		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}