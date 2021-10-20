<pre>
<?php

use Krishna\DataValidator\ErrorReader;
use Krishna\DataValidator\Validator;

require_once 'vendor/autoload.php';

$json = '{
	"id": "int|float@rf1@rf2",
	"firstname": "string|string64",
	"?val": "int|float|mytype|null",
	"nums": ["int"],
	"?info": {
		"msg": "string"
	},
	"other": [{
		"a": "int",
		"?b": "bool"
	}]
}';
$data = [
	'id' => 1,
	'firstname' => 'test',
	'nums' => [1],
	'other' => [['a' => 1]],
	'info' => ['msg'=> 1],
	'val' => 24.5
];

// $json = '["int|float|null@max10", {
// 	"a": "int",
// 	"b": "bool"
// }]';

// $json = '{
// 	"a": "int",
// 	"b": "string"
// }';
// $data = (object) [
// 	"a" => 1,
// 	"b" => 'hello'
// ];

// $json = '["int", "string", "int"]';
// $data = [0, 'hello', 1];

$v = Validator::create($json);

if(!$v->valid) {
	echo 'Error: ', $v->value;
	exit(0);
}

$v = $v->value;

// print_r($v);
// var_dump($v);

$data = $v->validate($data);
if(!$data->valid) {
	echo 'Data Error:', new ErrorReader($data->value);
	exit(0);
}
$data = $data->value;

print_r($data);