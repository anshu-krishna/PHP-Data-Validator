<?php
namespace Krishna\DataValidator\RngFmt\Str;

use Krishna\DataValidator\Returner;

class Lower extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function exec($value): Returner {
		if(!is_string($value)) {
			return Returner::invalid('Str\\Lower expects a string value');
		}
		return Returner::valid(strtolower($value));
	}
}