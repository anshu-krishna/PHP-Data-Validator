<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class UUIDType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\Utilities\StaticOnlyTrait;
	const Name = 'UUID';
	
	public static function validate($value, bool $allow_null = false) : Returner {
		$value = StringType::validate($value, $allow_null);
		if(!$value->valid) {
			return Returner::invalid(static::Name);
		}
		if(!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value->value)) {
			return Returner::invalid(static::Name);
		}
		return $value;
	}
}