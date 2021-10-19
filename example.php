<pre>
<?php
require_once 'vendor/autoload.php';
use Krishna\DataValidator\Validator;

// $v = Validator::create('{
// 	"id": "int|float@rf1@rf2",
// 	"firstame": "string|string64",
// 	"?val": "int|float|mytype|null",
// 	"nums": ["int"],
// 	"?info": {
// 		"?msg": "string"
// 	},
// 	"other": [{
// 		"a": "int",
// 		"b": "bool"
// 	}]
// }');
$v = Validator::create('["int|float|null@max10", 1, {
	"a": ["int", 2],
	"b": "bool"
}]');
if($v->valid) {
	$v = $v->value;
	print_r($v);
	// var_dump($v);
} else {
	echo $v->value;
}