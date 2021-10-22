<?php
namespace Krishna\DataValidator\RngFmt;

use Krishna\DataValidator\Returner;
use Krishna\DataValidator\Types\FloatType;

class NumRange extends \Krishna\DataValidator\AbstractRangerFormatter {
	private ?float $_min, $_max;
	public function setup(...$args): Returner {
		$min = FloatType::validate($args[0] ?? '', true);
		$max = FloatType::validate($args[1] ?? '', true);
		if(
			!$min->valid ||
			!$max->valid ||
			($min->value === null && $max->value === null) ||
			($min->value !== null && $max->value !== null && $min->value > $max->value)
		) {
			return Returner::invalid('Invalid range values');
		}
		$this->_min = $min->value;
		$this->_max = $max->value;
		return Returner::valid();
	}
	public function exec($value): Returner {
		if(!is_numeric($value)) {
			return Returner::invalid('NumRange expects a numeric value');
		}
		if(
			($this->_min !== null && $value < $this->_min) ||
			($this->_max !== null && $value > $this->_max)
		) {
			return Returner::invalid('Expected range [' . ($this->_min ?? 'null') . ', ' . ($this->_max ?? 'null') . ']');
		}
		return Returner::valid($value);
	}
}