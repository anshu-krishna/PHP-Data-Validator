<?php
namespace Krishna\DataValidator\RngFmt;

use Krishna\DataValidator\Returner;

class AllowedValues extends \Krishna\DataValidator\AbstractRangerFormatter {
	private array $allowed;
	public function __construct(...$allowed) {
		$this->allowed = $allowed;
	}
	public function exec($value): Returner {
		if(in_array($value, $this->allowed, true)) {
			return Returner::valid($value);
		}
		return Returner::invalid('Unsupported value');
	}
}