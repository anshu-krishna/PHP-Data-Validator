<?php
namespace Krishna\DataValidator;

interface TypeInterface {
	public static function validate($value, bool $allow_null = false) : Returner;
}