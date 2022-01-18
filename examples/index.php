<?php
namespace ExampleApp;
require_once '../vendor/autoload.php';

use Krishna\DataValidator\MultiLinedException;
use Krishna\DataValidator\Validator;

$rng_50 = strval(new \Krishna\DataValidator\RngFmt\Num\Range(max: 50));
$toString = strval(new \Krishna\DataValidator\RngFmt\Str\ToString);
$toUpper = strval(new \Krishna\DataValidator\RngFmt\Str\Upper);

try {
	$dv = new Validator([
		'a' => "int@{$rng_50}",
		'b' => ["int@{$rng_50}"],
		'?c' => ["string@{$toUpper}", 'mixed', 'mixed']
	], true);
	['valid' => $valid, 'value' => $data, 'error' => $error] = $dv->validate([
		'a' => '0',
		'b' => [5, 16],
		'c' => ['18asd', 5, false],
		'd' => 564
	])->get_as_array();
	if($valid) {
		var_dump(['Data' => $data]);
	} else {
		echo "<pre>Error:\n", $error ,"</pre>";
	}
} catch (MultiLinedException $th) {
	var_dump($th->getInfo());
}