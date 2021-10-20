<?php
namespace Krishna\DataValidator;
class _TypeHandler_ {
	private function __construct(
		private array $_types,
		private array $_rng_fmt,
		private bool $_nullable
	) {}
	public static function create(string $info) : Returner {
		$rng_fmt = preg_split('/@/', $info);
		$types = explode('|', array_shift($rng_fmt));
		$nullable = in_array('null', $types);
		$types = array_diff($types, ['null', '']);

		if(count($types) === 0) {
			return Returner::invalid('Data-type missing');
		}
		return Returner::valid(new self($types, $rng_fmt, $nullable));
	}
	public function validate($val) : Returner {
		return Returner::valid($val);
	}
	public function __debugInfo() {
		return [
			'types' => json_encode($this->_types),
			'rng_fmt' => json_encode($this->_rng_fmt),
			'nullable' => json_encode($this->_nullable)
		];
	}
}