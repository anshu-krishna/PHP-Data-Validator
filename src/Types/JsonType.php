<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;
use Krishna\Utilities\JSON;

class JsonType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'string:{JSON}';

	public static function validate($value, bool $allow_null = false): Returner {
		if(($value = StringType::validate($value, $allow_null))->valid) {
			if($allow_null && $value->value === null) {
				return $value;
			}
			if(($value = JSON::decode($value->value)) !== null) {
				return Returner::valid($value);
			}
		}
		return Returner::invalid(static::Name);
	}
}