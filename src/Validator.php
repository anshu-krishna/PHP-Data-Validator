<?php
namespace Krishna\DataValidator;

class Validator {
	public readonly ArrayHandler|ObjectHandler $struct;
	public function __construct(array|string $data_struct, public readonly bool $trimOutOfBound = false) {
		$error = [];
		if(is_array($data_struct)) {
			$data_struct = json_encode($data_struct, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
		}
		$data_struct = json_decode($data_struct, flags: JSON_INVALID_UTF8_SUBSTITUTE);
		if($data_struct === null) {
			throw new MultiLinedException('Invalid structure');
		}
		try {
			$this->struct = match(true) {
				is_object($data_struct) => new ObjectHandler($data_struct, $this->trimOutOfBound),
				is_array($data_struct) => new ArrayHandler($data_struct, $this->trimOutOfBound),
				default => throw new MultiLinedException('Invalid structure')
			};
		} catch(MultiLinedException $ex) {
			$error = $ex->getInfo();
		}
		if(count($error) > 0) {
			throw new MultiLinedException($error);
		}
	}
	public function validate(array|object $val) : Returner {
		$res = $this->struct->validate($val);
		if($res->valid) {
			return $res;
		}
		return Returner::invalid(new ErrorReader($res->error));
	}
}