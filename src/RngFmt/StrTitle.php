<?php
namespace Krishna\DataValidator\RngFmt;

use Krishna\DataValidator\Returner;
use Krishna\DataValidator\Types\IntType;

class StrTitle extends \Krishna\DataValidator\AbstractRangerFormatter {
	private ?float $_min, $_max;
	public function exec($value): Returner {
		if(!is_string($value)) {
			return Returner::invalid('StrTitle expects a string value');
		}
		return Returner::valid(ucwords($value));
	}
}