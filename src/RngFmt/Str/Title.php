<?php
namespace Krishna\DataValidator\RngFmt\Str;

use Krishna\DataValidator\Returner;

class Title extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function exec($value): Returner {
		if(!is_string($value)) {
			return Returner::invalid('Str\\Title expects a string value');
		}
		return Returner::valid(ucwords($value));
	}
}