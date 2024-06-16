<?php
namespace Krishna\DataValidator;

class Validator {
	public readonly ArrayHandler|ObjectHandler|TypeHandler $struct;
	public function __construct(array|string|TypeHandler $data_struct, public readonly OutOfBoundAction $on_out_of_bound = OutOfBoundAction::Error) {
		if(is_array($data_struct)) {
			$data_struct = json_encode($data_struct, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
			$data_struct = json_decode($data_struct, flags: JSON_INVALID_UTF8_SUBSTITUTE);
			
			if($data_struct === null) {
				// JSON decode failed
				throw new ComplexException('Invalid structure');
			}

			if(is_object($data_struct)) {
				$this->struct = new ObjectHandler($data_struct, $this->on_out_of_bound);
			} else {
				$this->struct = new ArrayHandler($data_struct, $this->on_out_of_bound);
			}
		} elseif(is_string($data_struct)) {
			$this->struct = new TypeHandler($data_struct);
		} else {
			// $data_struct is an instance of TypeHandler
			$this->struct = $data_struct;
		}
	}
	public function validate(mixed $val) : Returner {
		$res = $this->struct->validate($val);
		if(is_array($res->error)) {
			return Returner::invalid(new ErrorReader($res->error));
		}
		return $res;
	}
}