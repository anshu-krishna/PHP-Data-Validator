<?php
namespace Krishna\DataValidator;

class Validator {
	private function __construct(private _ArrayHandler_|_ObjectHandler_ $_struct) {}
	public static function create(array|string $data_struct) : Returner {
		if(is_array($data_struct)) {
			$data_struct = json_encode($data_struct);
		}
		$data_struct = json_decode($data_struct);
		if($data_struct === null) {
			return Returner::invalid('Invalid JSON');
		}
		if(is_object($data_struct)) {
			$data_struct = _ObjectHandler_::create($data_struct);
		} elseif(is_array($data_struct)) {
			$data_struct = _ArrayHandler_::create($data_struct);
		} else {
			return Returner::invalid('Invalid JSON');
		}
		if($data_struct->valid) {
			return Returner::valid(new self($data_struct->value));
		} else {
			return Returner::invalid(new ErrorReader($data_struct->value));
		}
	}
	public function validate(array|object $val) : Returner {
		$res = $this->_struct->validate($val);
		if($res->valid) {
			return $res;
		}
		return Returner::invalid(new ErrorReader($res->value));
	}
}