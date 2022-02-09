<?php
namespace Krishna\DataValidator;

class RecursiveValidator {
	private $steps = [];
	private $titles = [];
	public function __construct(
		TypeHandler|string|array $struct,
		OutOfBoundAction $on_out_of_bound = OutOfBoundAction::Keep,
		?string $step_title = null
	) {
		$this->then($struct, $on_out_of_bound, $step_title);
	}
	public function then(
		TypeHandler|string|array $struct,
		OutOfBoundAction $on_out_of_bound = OutOfBoundAction::Keep,
		?string $step_title = null
	) : static {
		if(is_array($struct)) {
			$this->steps[] = new Validator($struct, $on_out_of_bound);
		} elseif(is_string($struct)) {
			$this->steps[] = new TypeHandler($struct);
		} elseif(is_a($struct, TypeHandler::class)) {
			$this->steps[] = $struct;
		}
		$this->titles[] = ($step_title === null) ? strval(count($this->steps)) : $step_title;
		return $this;
	}
	public function validate(mixed $val) : Returner {
		foreach($this->steps as $i => $t) {
			if(is_a($t, Validator::class)) {
				if(!is_array($val) && !is_object($val)) {
					return Returner::invalid([
						'step' => $this->titles[$i],
						'msg' => new ErrorReader(["Expected 'array|object'"])
					]);
				}
				$val = $t->validate($val);
				if($val->valid) {
					$val = $val->value;
				} else {
					return Returner::invalid([
						'step' => $this->titles[$i],
						'msg' => $val->error
					]);
				}
			} else {
				$val = $t->validate($val);
				if($val->valid) {
					$val = $val->value;
				} else {
					return Returner::invalid([
						'step' => $this->titles[$i],
						'msg' => new ErrorReader([$val->error])
					]);
				}
			}
		}
		return Returner::valid($val);
	}
}