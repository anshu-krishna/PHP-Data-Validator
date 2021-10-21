<?php
namespace Krishna\DataValidator\Types;

use Krishna\DataValidator\Returner;

class IPType implements \Krishna\DataValidator\TypeInterface {
	use \Krishna\DataValidator\StaticOnlyTrait;
	const Name = 'IP';

	public static function validate($value, bool $allow_null = false) : Returner {
		if(($f = filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE|FILTER_FLAG_IPV4|FILTER_FLAG_IPV6)) !== false) {
			return Returner::valid($f);
		}
		if($allow_null && ($f = NullType::validate($value))->valid) {
			return $f;
		}
		return Returner::invalid(static::Name);
	}
}