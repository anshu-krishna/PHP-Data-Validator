<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class NullType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'null';

	public static function validate($value, bool $allow_null = false) : Returner {
		if($value === null || (is_string($value) && ($value === '' || strcasecmp($value, 'null') === 0))) {
			return Returner::valid(null);
		}
		return Returner::invalid(static::Name);
	}
}