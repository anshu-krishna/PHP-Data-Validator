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
	'other' => [['a' => 1], ['a' => 2, 'b'=> 3]],
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

list("valid" => $valid, "value" => $v) = Validator::create($json)->get_as_array();

if(!$valid) {
	echo 'Error: ', $v;
	exit(0);
}

// print_r($v);
// var_dump($v);

list("valid" => $valid, "value" => $data) = $v->validate($data)->get_as_array();
if(!$valid) {
	echo 'Data Error:', $data;
	exit(0);
}
print_r($data);