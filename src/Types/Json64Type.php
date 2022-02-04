<?php
namespace Krishna\API\DataType;
use Krishna\DataValidator\Returner;
use Krishna\DataValidator\Types\JsonType;
use Krishna\DataValidator\Types\String64Type;

class Json64Type implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'string:{Base64URL encoded JSON}';

	public static function validate($value, bool $allow_null = false): Returner {
		if(($value = String64Type::validate($value, $allow_null))->valid) {
			if($allow_null && $value->value === null) {
				return $value;
			}
			if(($value = JsonType::validate($value->value, $allow_null))->valid) {
				return $value;
			}
		}
		return Returner::invalid(static::Name);
	}
}