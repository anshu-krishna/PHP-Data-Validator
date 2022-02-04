<?php
namespace Krishna\DataValidator\Types;
use Krishna\DataValidator\Returner;
use Krishna\DataValidator\Types\URLType;

class URL64Type implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'url:{Base64URL encoded}';

	public static function validate($value, bool $allow_null = false): Returner {
		if(($value = String64Type::validate($value, $allow_null))->valid) {
			if($allow_null && $value->value === null) {
				return $value;
			}
			if(($value = URLType::validate($value->value))->valid) {
				return $value;
			}
		}
		return Returner::invalid(static::Name);
	}
}