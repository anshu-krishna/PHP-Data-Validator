<?php
namespace Krishna\DataValidator;

class TypeHandler {
	private const InterfaceClass = '\\' . TypeInterface::class;
	private const NSPathCache = '\\' . __NAMESPACE__ . '\\Types\\';
	private static array $types_cache = [
		'bool'			=> self::NSPathCache . 'BoolType',
		'email'			=> self::NSPathCache . 'EmailType',
		'float'			=> self::NSPathCache . 'FloatType',
		'hex'			=> self::NSPathCache . 'HexType',
		'int'			=> self::NSPathCache . 'IntType',
		'ipv4'			=> self::NSPathCache . 'IPv4Type',
		'ipv6'			=> self::NSPathCache . 'IPv6Type',
		'json'			=> self::NSPathCache . 'JsonType',
		'json64'		=> self::NSPathCache . 'Json64Type',
		'mac'			=> self::NSPathCache . 'MACType',
		'mixed'			=> self::NSPathCache . 'MixedType',
		'null'			=> self::NSPathCache . 'NullType',
		'number'		=> self::NSPathCache . 'NumberType',
		'string'		=> self::NSPathCache . 'StringType',
		'string64'		=> self::NSPathCache . 'String64Type',
		'string64bin'	=> self::NSPathCache . 'String64BinType',
		'timestamp'		=> self::NSPathCache . 'TimestampType',
		'timestamp_utc'	=> self::NSPathCache . 'TimestampUTCType',
		'unsigned'		=> self::NSPathCache . 'UnsignedType',
		'url'			=> self::NSPathCache . 'URLType',
		'url64'			=> self::NSPathCache . 'URL64Type',
		'uuid'			=> self::NSPathCache . 'UUIDType',
	];

	public readonly array $types;
	public readonly array $rng_fmt;
	public readonly bool $nullable;

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
	public static function set_multiple_custom_type_class(array $list, ?string $prefix_namespace = null, bool $sub_class_check = true) {
		/*
			Disabling sub_class_check is more efficient and also VERY DANGEROUS;
			For best results:
				During development -> $sub_class_check = true;
				For deployment -> $sub_class_check = false;
		*/
		$prefix_namespace = ($prefix_namespace === null) ? '' : "{$prefix_namespace}\\";
		if($sub_class_check) {
			foreach($list as $type => $class) {
				self::set_custom_type_class($type, $prefix_namespace . $class);
			}
		} else {
			foreach($list as $type => $class) {
				static::$types_cache[$type] = $prefix_namespace . $class;
			}
		}
	}
	public static function set_custom_type_class(string $type, string $class) {
		if(is_subclass_of($class, static::InterfaceClass)) {
			static::$types_cache[$type] = $class;
		} else {
			throw new ComplexException("'{$class}'; Custom type '{$type}' must be a sub-class of '" . static::InterfaceClass . "'");
		}
	}
	public function __construct(string $info) {
		$rng_fmt = preg_split('/\s*@/', $info);
		$types = array_unique(preg_split('/\s*\|\s*/', array_shift($rng_fmt)));
		$nullable = in_array('null', $types);
		$types = array_diff($types, ['null', '']);
		$rng_fmt = array_diff($rng_fmt, ['']);
		if(count($types) === 0) {
			if($nullable) {
				throw new ComplexException('Data-type missing (data-type cannot be null only, use null with other data-types (e.g. "null|string")');
			} else {
				throw new ComplexException('Data-type missing');
			}
		}

		foreach($types as &$t) {
			if(($longt = static::get_type_class($t)) === null) {
				throw new ComplexException("'{$t}'; Unknown data-type");
			} else {
				$t = $longt;
			}
		}
		foreach($rng_fmt as &$rf) {
			$rf_detail = explode(';', $rf);
			$rf_class = $rf_detail[0] ?? null;
			if(!is_subclass_of($rf_class, \Krishna\DataValidator\AbstractRangerFormatter::class)) {
				throw new ComplexException("'{$rf}'; Invalid ranger-formatter used; Class unknown");
			}
			$rf_id = $rf_detail[1] ?? 'undefined';
			$rf_obj = $rf_class::get($rf_id);
			if($rf_obj === null) {
				throw new ComplexException("'{$rf}'; Invalid ranger-formatter used; Invalid object id");
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