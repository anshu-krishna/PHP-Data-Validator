<?php
namespace Krishna\DataValidator\RngFmt\Str;

use Krishna\DataValidator\Returner;

class ToString extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function exec($value): Returner {
		if(!is_string($value)) {
			$value = strval($value);
		}
		return Returner::valid($value);
	}
}