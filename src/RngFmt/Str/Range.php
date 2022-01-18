<?php
namespace Krishna\DataValidator\RngFmt\Str;

use Krishna\DataValidator\MultiLinedException;
use Krishna\DataValidator\Returner;

class Range extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function __construct(private ?int $min = null, private ?int $max = null) {
		if(
			($this->min === null && $this->max === null) ||
			($this->min !== null && $this->max !== null && $this->min > $this->max)
		) {
			throw new MultiLinedException('Invalid range values');
		}
	}
	public function exec($value): Returner {
		if(!is_string($value)) {
			return Returner::invalid('Str\\Range expects a string value');
		}
		$len = mb_strlen($value, 'utf-8');
		if(
			($this->min !== null && $len < $this->min) ||
			($this->max !== null && $len > $this->max)
		) {
			return Returner::invalid('Expected strlen [' . ($this->min ?? 'null') . ', ' . ($this->max ?? 'null') . ']');
		}
		return Returner::valid($value);
	}
}