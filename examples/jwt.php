<?php
namespace ExampleApp;
require_once '../vendor/autoload.php';

use Krishna\DataValidator\ComplexException;
use Krishna\DataValidator\RecursiveValidator;
use Krishna\DataValidator\Returner;
use Krishna\DataValidator\RngFmt\AllowedValues;
use Krishna\DataValidator\TypeHandler;
use Krishna\DataValidator\TypeInterface;
use Krishna\DataValidator\Types\StringType;
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
class MyStringType implements TypeInterface {
	public static function validate($value, bool $allow_null = false) : Returner {
		$value = StringType::validate($value);
		if(!$value->valid) {
			return $value;
		}
		$value = explode('.', $value->value);
		return Returner::valid([
			'head' => $value[0],
			'body' => $value[1] ?? ''
		]);
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
} catch (ComplexException $th) {
	var_dump($th->getInfo());
}

echo '<hr />';

// Testing recursive validator
try {
	$dv = (new RecursiveValidator(MyStringType::class, step_title: 'Token to array'))
	->then([
		'head' => 'json64',
		'body' => 'json64'
	], step_title: 'Base64 JSON decode')
	->then([
		'head' => [
		'alg' => 'string@' . new AllowedValues('HS256', 'HS384', 'HS512', 'RS256', 'RS384', 'RS512'),
		'typ' => 'string@' . new AllowedValues('JWT')
		],'body' => [
			'?iss' => 'string',
			'?sub' => 'string|null',
			'?aud' => 'string',
			'?exp' => 'unsigned',
			'?nbf' => 'unsigned',
			'?iat' => 'unsigned',
			'?kid' => 'unsigned'
		]
	], step_title: "Test structure of 'head' & 'body'");
	['valid' => $valid, 'value' => $data, 'error' => $error] = $dv->validate('eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ0b2tlbi50ZXN0LmNvbSIsImF1ZCI6ImFwcC50b2tlbi5jb20iLCJpYXQiOjE2NDM4OTE3NTgsIm5iZiI6MTY0Mzg5MTc1OCwiZXhwIjoxNjQzODkyMzU4LCJuYW1lIjoiQW5zaHUgS3Jpc2huYSIsImNpdHkiOiJCYW5nYWxvcmUifQ')->get_as_array();
	if($valid) {
		var_dump(['Data' => $data]);
	} else {
		echo "<pre>Error on step '{$error['step']}' =&gt; ", $error['msg']->getFormattedErrors('; ') ,"</pre>";
	}
} catch (ComplexException $th) {
	var_dump($th->getInfo());
}