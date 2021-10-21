<pre>
<?php
use Krishna\DataValidator\Validator;

require_once 'vendor/autoload.php';

// $json = '{
// 	"id": "int|float@rf1@rf2",
// 	"firstname": "string",
// 	"?val": "int|float|null",
// 	"nums": ["int"],
// 	"?info": {
// 		"msg": "string"
// 	},
// 	"other": [{
// 		"a": "int",
// 		"?b": "bool"
// 	}]
// }';
// $data = [
// 	'id' => 1,
// 	'firstname' => 'test',
// 	'nums' => [1],
// 	'other' => [['a' => 1], ['a' => 2, 'b'=> 'true']],
// 	'info' => ['msg'=> '1'],
// 	'val' => 24.5
// ];

$json = '{
	"?bool":		"bool|null",
	"?email":		"email|null",
	"?float":		"float|null",
	"?hex":			"hex|null",
	"?int":			"int|null",
	"?ip":			"ip|null",
	"?mac":			"mac|null",
	"?mixed":		"mixed|null",
	"?number":		"number|null",
	"?string":		"string|null",
	"?timestamp":	"timestamp|null",
	"?unsigned":	"unsigned|null",
	"?url":			"url|null"
}';

$data = [
	'bool'		=> true,
	'email'		=> 'a@b.c',
	'float'		=> 1.2,
	'hex'		=> 'FF',
	'int'		=> 5,
	// 'ip'		=> '127.0.0.1',
	'ip'		=> '2001:db8:3333:4444:5555:6666:7777:8888',
	'mac'		=> '12-34-56-78-9A-BC',
	'mixed'		=> true,
	'number'	=> '0x1f3',
	'string'	=> "qwerty😃\u{1f60e}",
	'timestamp'	=> 'today',
	'unsigned'	=> 5,
	'url'		=> 'https://a.b',
];

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