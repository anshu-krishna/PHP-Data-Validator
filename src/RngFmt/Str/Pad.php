<?php
namespace Krishna\DataValidator\RngFmt\Str;

use Krishna\DataValidator\Returner;

class Pad extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function __construct(private int $pad_length, private string $pad_string = ' ', private PadType $pad_type = PadType::Right) {}
	public function exec($value): Returner {
		if(!is_string($value)) {
			return Returner::invalid('Str\\Pad expects a string value');
		}
		return Returner::valid(str_pad($value, $this->pad_length, $this->pad_string, $this->pad_type->value));
	}
}