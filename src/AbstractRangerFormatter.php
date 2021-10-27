<?php
namespace Krishna\DataValidator;

abstract class AbstractRangerFormatter {
	public function setup(string ...$args) : Returner {
		return Returner::valid();
	}
	abstract public function exec($result) : Returner;
}