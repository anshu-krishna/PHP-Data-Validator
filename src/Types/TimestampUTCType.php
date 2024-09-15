<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class TimestampUTCType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'timestamp_utc';

	public static function validate($value, bool $allow_null = false) : Returner {
		$value = TimestampType::validate($value, $allow_null);
		if(!$value->valid) {
			return Returner::invalid(static::Name);
		}
		// unix epic to ISO UTC format
		$f = gmdate('Y-m-d\TH:i:s.u\Z', $value->value);
		return Returner::valid($f);
	}
}