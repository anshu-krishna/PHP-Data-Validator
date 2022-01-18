<?php
namespace Krishna\DataValidator\RngFmt\Num;

use Krishna\DataValidator\MultiLinedException;
use Krishna\DataValidator\Returner;

class Range extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function __construct(private ?float $min = null, private ?float $max = null) {
		if(
			($this->min === null && $this->max === null) ||
			($this->min !== null && $this->max !== null && $this->min > $this->max)
		) {
			throw new MultiLinedException('Invalid range values');
		}
	}
	public function exec($value): Returner {
		if(!is_numeric($value)) {
			return Returner::invalid('Num\\Range expects a numeric value');
		}
		if(
			($this->min !== null && $value < $this->min) ||
			($this->max !== null && $value > $this->max)
		) {
			return Returner::invalid('Expected range [' . ($this->min ?? 'null') . ', ' . ($this->max ?? 'null') . ']');
		}
		return Returner::valid($value);
	}
}