<?php
namespace Krishna\DataValidator\RngFmt\Str;

use Krishna\DataValidator\Returner;

class Upper extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function exec($value): Returner {
		if(!is_string($value)) {
			return Returner::invalid('StrUpper expects a string value');
		}
		return Returner::valid(strtoupper($value));
	}
}