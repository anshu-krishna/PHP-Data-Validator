<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class TimestampUTCType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'timestamp_utc';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(is_string($value) && ($f = strtotime($value)) !== false) {
			// unix epic to ISO UTC format
			$f = gmdate('Y-m-d\TH:i:s.u\Z', $f);
			return Returner::valid($f);
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}