<?php
namespace ExampleApp;
require_once '../vendor/autoload.php';

use Krishna\DataValidator\MultiLinedException;
use Krishna\DataValidator\Returner;
use Krishna\DataValidator\TypeHandler;
use Krishna\DataValidator\TypeInterface;
use Krishna\DataValidator\Validator;

$rng_50 = strval(new \Krishna\DataValidator\RngFmt\Num\Range(max: 50));
$toString = strval(new \Krishna\DataValidator\RngFmt\Str\ToString);
$toUpper = strval(new \Krishna\DataValidator\RngFmt\Str\Upper);

class MyType1 implements TypeInterface {
	public static function validate($value, bool $allow_null = false) : Returner {
		return Returner::valid('my_type1_' . strval($value));
	}
}
class MyType2 implements TypeInterface {
	public static function validate($value, bool $allow_null = false) : Returner {
		return Returner::valid('my_type2_' . strval($value));
	}
}
class MyType3 implements TypeInterface {
	public static function validate($value, bool $allow_null = false) : Returner {
		return Returner::valid('my_type3_' . strval($value));
	}
}

TypeHandler::set_custom_type_class('my_type1', MyType1::class);
TypeHandler::set_multiple_custom_type_class([
	'my_type2' => 'MyType2',
	'my_type3' => 'MyType3'
], __NAMESPACE__);

try {
	$dv = new Validator([
		'?a' => "int@{$rng_50}",
		'?b' => ["int@{$rng_50}"],
		'?c' => ["string@{$toUpper}", 'mixed', 'mixed'],
		'?d' => 'my_type1',
		'?e' => 'my_type2',
		'?f' => 'my_type3'
	]);
	['valid' => $valid, 'value' => $data, 'error' => $error] = $dv->validate([
		'a' => '0',
		'b' => [5, 16],
		'c' => ['18asd', 5, false],
		'd' => 564,
		'e' => 987,
		'f' => 1389
	])->get_as_array();
	if($valid) {
		var_dump(['Data' => $data]);
	} else {
		echo "<pre>Error:\n", $error ,"</pre>";
	}
} catch (MultiLinedException $th) {
	var_dump($th->getInfo());
}