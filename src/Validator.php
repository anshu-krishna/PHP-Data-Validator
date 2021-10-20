<?php
namespace Krishna\DataValidator;

class Validator {
	private function __construct(private _ArrayHandler_|_ObjectHandler_ $_struct) {}
	public static function create(string $json_data_struct) : Returner {
		$json = json_decode($json_data_struct);
		if($json === null) {
			return Returner::invalid('Invalid JSON');
		}
		// print_r($json);
		if(is_object($json)) {
			$json = _ObjectHandler_::create($json);
		} elseif(is_array($json)) {
			$json = _ArrayHandler_::create($json);
		} else {
			return Returner::invalid('Invalid JSON');
		}
		if($json->valid) {
			return Returner::valid(new self($json->value));
		} else {
			return Returner::invalid(new ErrorReader($json->value));
		}
	}
	public function validate(array|object $val) : Returner {
		return $this->_struct->validate($val);
	}
}