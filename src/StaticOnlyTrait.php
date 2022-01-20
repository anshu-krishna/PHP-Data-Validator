<?php
namespace Krishna\DataValidator;

trait StaticOnlyTrait {
	final protected function __construct() {}
	final public static function __getStaticProperties__() {
		$class = new \ReflectionClass(static::class);
		return $class->getStaticProperties();
	}
}