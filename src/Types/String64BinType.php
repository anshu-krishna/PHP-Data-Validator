<?php
namespace Krishna\DataValidator\Types;
use Krishna\DataValidator\Returner;
use Krishna\Utilities\Base64;

class String64BinType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'string:{Base64URL encoded}';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(($value = StringType::validate($value, $allow_null))->valid) {
			if($allow_null && $value->value === null) {
				return $value;
			}
			if(($value = Base64::decode($value->value, false)) !== null) {
				return Returner::valid($value);
			}
		}
		return Returner::invalid(static::Name);
	}
}