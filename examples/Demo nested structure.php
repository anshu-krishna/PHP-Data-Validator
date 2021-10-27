<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Data-Validator - Example - Demostrating nested structures</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once 'helper_functions.php';

$examples = [
	['heading' => 'Nested structure'],
	[
		'repeat' => 'multi',
		'struct' => [
			"id" => "int", // ID is an integer
			"val" => "int|float|null", // Val can be int, float or null
			"list" => ["int|float"], // List is an array containing integers and floats
			"?list2" => ['int', 'bool', 'string'], // List2 is optional. List2 is an array with three items. First is int, second is bool, third is string
			"info" => [
				"msg" => "string", // Msg is a string
				"?c" => "bool" // C is optional. C is a bool
			],
			"?other" => [[ // Other is optional Other is an array of 'items'. 'item' is an array containing 'a' and 'b'
				"a" => "int", // A is an int
				"?b" => "bool" // B is optional. B is bool
			]]
		],
		'data' => [[
			'id' => 1,
			'val' => 5.5,
			'list' => [1, 22.5, 6, 7, 8.9],
			'info' => ['msg'=> 'hello world'],
			'other' => [['a' => 1], ['a' => 2, 'b'=> 'true'], ['a' => 45]]
		], [
			'id' => 2
		], [
			'id' => 3,
			'list' => [1],
			'val' => null,
			'info' => [
				'msg' => 'uqiweyr',
				"c" => false
			]
		], [
			'id' => 4,
			'info' => [
				"c" => true,
				'd' =>'hi'
			],
			'xyz' => 12
		]]
	]
];
echo_example_list($examples);
?>
</body>
</html>