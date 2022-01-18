<?php
namespace Krishna\DataValidator\RngFmt\Str;

use Krishna\DataValidator\Returner;

class PadMB extends \Krishna\DataValidator\AbstractRangerFormatter {
	public function __construct(private int $pad_length, private string $pad_string = ' ', private PadType $pad_type = PadType::Right) {}
	public function exec($value): Returner {
		if(!is_string($value)) {
			return Returner::invalid('Str\\PadMB expects a string value');
		}
		$encoding = 'UTF-8';
		$diff = $this->pad_length - mb_strlen($value, $encoding);
		if($diff < 1) {
			return Returner::valid($value);
		}

		$left_len = match($this->pad_type) {
			PadType::Both => floor($diff / 2),
			PadType::Left => $diff,
			PadType::Right => 0
		};

		$pad_strlen = mb_strlen($this->pad_string, $encoding);
		$left_padding = mb_substr(str_repeat($this->pad_string, ceil($left_len/$pad_strlen)), 0, $left_len, $encoding);

		$right_len = $diff - mb_strlen($left_padding, $encoding);
		$right_padding = mb_substr(str_repeat($this->pad_string, ceil($right_len/$pad_strlen)), 0, $right_len, $encoding);

		return Returner::valid("{$left_padding}{$value}{$right_padding}");
	}
}