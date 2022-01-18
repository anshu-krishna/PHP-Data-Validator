<?php
namespace Krishna\DataValidator;

final class Returner {
	public readonly mixed $value;
	public readonly mixed $error;
	private function __construct(public readonly bool $valid, mixed $value) {
		if($this->valid) {
			$this->value = $value;
			$this->error = null;
		} else {
			$this->value = null;
			$this->error = $value;
		}
	}
	public static function valid($value = true) : Returner {
		return new self(true, $value);
	}
	public static function invalid($reason = null) : Returner {
		return new self(false, $reason);
	}
	public function get_as_array() : array {
		return [
			"valid" => $this->valid,
			"value" => $this->value,
			"error" => $this->error
		];
	}
}