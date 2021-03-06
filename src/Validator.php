<?php
namespace Krishna\DataValidator;

class Validator {
	public readonly ArrayHandler|ObjectHandler $struct;
	public function __construct(array|string $data_struct, public readonly OutOfBoundAction $on_out_of_bound = OutOfBoundAction::Error) {
		if(is_array($data_struct)) {
			$data_struct = json_encode($data_struct, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
		}
		$data_struct = json_decode($data_struct, flags: JSON_INVALID_UTF8_SUBSTITUTE);
		if($data_struct === null) {
			throw new ComplexException('Invalid structure');
		}
		$this->struct = match(true) {
			is_object($data_struct) => new ObjectHandler($data_struct, $this->on_out_of_bound),
			is_array($data_struct) => new ArrayHandler($data_struct, $this->on_out_of_bound),
			default => throw new ComplexException('Invalid structure')
		};
	}
	public function validate(array|object $val) : Returner {
		$res = $this->struct->validate($val);
		if($res->valid) {
			return $res;
		}
		return Returner::invalid(new ErrorReader($res->error));
	}
}