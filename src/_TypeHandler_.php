<?php
namespace Krishna\DataValidator;
class _TypeHandler_ {
	private const InterfaceClass = '\\' . __NAMESPACE__ . '\\TypeInterface';
	private const NSPathCache = '\\' . __NAMESPACE__ . '\\Types\\';
	private static $_type_dict = [];
	private static bool $_init_cache = true;
	private function __construct(
		private array $_types,
		private array $_rng_fmt,
		private bool $_nullable
	) {}
	private static function _get_type_class(string $type) :?string {
		if(array_key_exists($type, static::$_type_dict)) {
			return static::$_type_dict[$type];
		}
		if(is_subclass_of($type, static::InterfaceClass)) {
			static::$_type_dict[$type] = $type;
			return $type;
		}
		$longtype = '\\' . __NAMESPACE__ . '\\Types\\' . ucfirst($type) . 'Type';
		if(is_subclass_of($longtype, static::InterfaceClass)) {
			static::$_type_dict[$type] = $longtype;
			return $longtype;
		}
		return null;
	}
	public static function create($info) : Returner {
		if(static::$_init_cache) {
			foreach([
				'bool'		=> 'BoolType',
				'email'		=> 'EmailType',
				'float'		=> 'FloatType',
				'hex'		=> 'HexType',
				'int'		=> 'IntType',
				'ip'		=> 'IPType',
				'mac'		=> 'MACType',
				'mixed'		=> 'MixedType',
				'null'		=> 'NullType',
				'number'	=> 'NumberType',
				'string'	=> 'StringType',
				'timestamp'	=> 'TimestampType',
				'unsigned'	=> 'UnsignedType',
				'url'		=> 'URLType',
			] as $k => $v) {
				static::$_type_dict[$k] = static::NSPathCache . $v;
			}
			static::$_init_cache = false;
		}
		$info = strval($info);
		$rng_fmt = preg_split('/@/', $info);
		$types = array_unique(explode('|', array_shift($rng_fmt)));
		$nullable = in_array('null', $types);
		$types = array_diff($types, ['null', '']);
		$rng_fmt = array_diff($rng_fmt, ['']);

		if(count($types) === 0) {
			return Returner::invalid('Data-type missing');
		}
		foreach($types as &$t) {
			if(($longt = static::_get_type_class($t)) === null) {
				return Returner::invalid("'{$t}'; Unknown data-type");
			} else {
				$t = $longt;
			}
		}
		return Returner::valid(new self($types, $rng_fmt, $nullable));
	}
	public function validate($val) : Returner {
		$errors = [];
		foreach($this->_types as $t) {
			$ret = $t::validate($val, $this->_nullable);
			if($ret->valid) {
				return $ret;
			}
			$errors[] = $ret->value;
		}
		if($this->_nullable) {
			$errors[] = 'null';
		}
		return Returner::invalid("Expected '" . implode('|', $errors) . "'");
	}
	public function __debugInfo() {
		return [
			'types' => json_encode($this->_types),
			'rng_fmt' => json_encode($this->_rng_fmt),
			'nullable' => json_encode($this->_nullable)
		];
	}
}