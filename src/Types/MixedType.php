<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class MixedType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'mixed';

	public static function validate($value, bool $allow_null = false) : Returner {
		return Returner::valid($value);
	}
}