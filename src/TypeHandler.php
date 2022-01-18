<?php
namespace Krishna\DataValidator;

class TypeHandler {
	private const InterfaceClass = '\\' . __NAMESPACE__ . '\\TypeInterface';
	private const NSPathCache = '\\' . __NAMESPACE__ . '\\Types\\';
	private static ?array $types_cache = null;

	public readonly array $types;
	public readonly array $rng_fmt;
	public readonly bool $nullable;

	private static function init_cache() {
		static::$types_cache = [];
		foreach([
			'bool'		=> 'BoolType',
			'email'		=> 'EmailType',
			'float'		=> 'FloatType',
			'hex'		=> 'HexType',
			'int'		=> 'IntType',
			'ipv4'		=> 'IPv4Type',
			'ipv6'		=> 'IPv6Type',
			'mac'		=> 'MACType',
			'mixed'		=> 'MixedType',
			'null'		=> 'NullType',
			'number'	=> 'NumberType',
			'string'	=> 'StringType',
			'timestamp'	=> 'TimestampType',
			'unsigned'	=> 'UnsignedType',
			'url'		=> 'URLType',
		] as $k => $v) {
			static::$types_cache[$k] = static::NSPathCache . $v;
		}
	}
	private static function get_type_class(string $type) :?string {
		if(array_key_exists($type, static::$types_cache)) {
			return static::$types_cache[$type];
		}
		if(is_subclass_of($type, static::InterfaceClass)) {
			static::$types_cache[$type] = $type;
			return $type;
		}
		$longtype = static::NSPathCache . ucfirst($type) . 'Type';
		if(is_subclass_of($longtype, static::InterfaceClass)) {
			static::$types_cache[$type] = $longtype;
			return $longtype;
		}
		return null;
	}
	public function __construct(string $info) {
		if(static::$types_cache === null) {
			static::init_cache();
		}
		$rng_fmt = preg_split('/\s*@/', $info);
		$types = array_unique(preg_split('/\s*\|\s*/', array_shift($rng_fmt)));
		$nullable = in_array('null', $types);
		$types = array_diff($types, ['null', '']);
		$rng_fmt = array_diff($rng_fmt, ['']);
		if(count($types) === 0) {
			throw new MultiLinedException('Data-type missing');
		}

		foreach($types as &$t) {
			if(($longt = static::get_type_class($t)) === null) {
				throw new MultiLinedException("'{$t}'; Unknown data-type");
			} else {
				$t = $longt;
			}
		}
		foreach($rng_fmt as &$rf) {
			$rf_detail = explode(';', $rf);
			$rf_class = $rf_detail[0] ?? null;
			if(!is_subclass_of($rf_class, \Krishna\DataValidator\AbstractRangerFormatter::class)) {
				throw new MultiLinedException("'{$rf}'; Invalid ranger-formatter used; Class unknown");
			}
			$rf_id = $rf_detail[1] ?? 'undefined';
			$rf_obj = $rf_class::get($rf_id);
			if($rf_obj === null) {
				throw new MultiLinedException("'{$rf}'; Invalid ranger-formatter used; Invalid object id");
			}
			$rf = $rf_obj;
		}
		$this->rng_fmt = $rng_fmt;
		$this->types = $types;
		$this->nullable = $nullable;
	}
	public function validate($val) : Returner {
		$errors = [];
		foreach($this->types as $t) {
			$ret = $t::validate($val, $this->nullable);
			if($ret->valid) {
				foreach($this->rng_fmt as $rf) {
					if(!($ret = $rf->exec($ret->value))->valid) {
						return $ret;
					}
				}
				return $ret;
			}
			$errors[] = $ret->error;
		}
		if($this->nullable) {
			$errors[] = 'null';
		}
		return Returner::invalid("Expected '" . implode('|', $errors) . "'");
	}
}