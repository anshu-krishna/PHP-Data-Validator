<?php
namespace Krishna\DataValidator\RngFmt;

use Krishna\DataValidator\Returner;
use Krishna\DataValidator\Types\IntType;

class StrRange extends \Krishna\DataValidator\AbstractRangerFormatter {
	private ?float $_min, $_max;
	public function setup(...$args): Returner {
		$min = IntType::validate($args[0] ?? '', true);
		$max = IntType::validate($args[1] ?? '', true);
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
		if(!is_string($value)) {
			return Returner::invalid('StrRange expects a string value');
		}
		$len = mb_strlen($value, 'utf-8');
		if(
			($this->_min !== null && $len < $this->_min) ||
			($this->_max !== null && $len > $this->_max)
		) {
			return Returner::invalid('Expected strlen [' . ($this->_min ?? 'null') . ', ' . ($this->_max ?? 'null') . ']');
		}
		return Returner::valid($value);
	}
}