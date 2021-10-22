<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Data-Validator Example 1</title>
	<style>
		body {
			padding: 0;
			margin: 0;
		}
		body > div {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			gap: 0.75em;
			
		}
		body > div > pre {
			box-sizing: border-box;
			box-shadow: 0 0 5px black;
			padding: 0.75em 1em;
		}
		body > div > span {
			padding: 1rem;
			font-size: 1.5em;
			font-weight: bold;
			text-align: center;
			border-bottom: 1px solid;
		}
	</style>
</head>
<body>
	<div>
		<span>Structure</span>
		<span>Data</span>
		<span>Result</span>
	</div>
<?php
require_once '../vendor/autoload.php';
use Krishna\DataValidator\Validator;

$examples = [
	[
		'struct' => [
			"id" => "int|float",
			"name" => "string",
			"?val" => "int|float|null",
			"nums" => ["int"],
			"?info" => [
				"msg" => "string"
			],
			"other" => [[
				"a" => "int",
				"?b" => "bool"
			]]
		],
		'data' => [
			'id' => 1,
			'name' => 'test',
			'nums' => [1, 2, 3],
			'other' => [['a' => 1], ['a' => 2, 'b'=> 'true']],
			'info' => ['msg'=> '1']
		]
	] , [
		'struct' => [
			"?bool" => "bool",
			"?email" => "email@str_lower",
			"?float" => "float@num_range(5,7)",
			"?hex" => "hex",
			"?int" => "int@num_range(10,20)",
			"?ipv4" => "ipv4",
			"?ipv6" => "ipv6",
			"?mac" => "mac",
			"?mixed" => "mixed",
			"?number" => "number",
			"?string" => "string@str_range(5,10)@str_upper",
			"?timestamp" => "timestamp",
			"?unsigned" => "unsigned",
			"?url" => "url"
		],
		'data' => [
			'bool'		=> true,
			'email'		=> 'test@GMAIL.com',
			'float'		=> 5.2,
			'hex'		=> 'FF',
			'int'		=> 15,
			'ipv4'		=> '127.0.0.1',
			'ipv6'		=> '2001:db8:3333:4444:5555:6666:7777:8888',
			'mac'		=> '12-34-56-78-9A-BC',
			'mixed'		=> true,
			'number'	=> '0x1f3',
			'string'	=> "qwerty😃\u{1f60e}",
			'timestamp'	=> 'today',
			'unsigned'	=> 5,
			'url'		=> 'https://www.google.com',
		]
	]
];

foreach($examples as $ex) {
	echo '<div>';
	echo '<pre>Structure:' , PHP_EOL , print_r($ex['struct'], true) . '</pre>';
	echo '<pre>Data to be Checked:' , PHP_EOL , print_r($ex['data'], true) . '</pre>';
	// Create a validator object for te given data-structure
	$validator = Validator::create($ex['struct']);
	if(!$validator->valid) {
		// Structure syntax is not correct; Printing the error msg;
		echo '<pre>Validator Error:', PHP_EOL, $validator->value, '</pre>';
	} else {
		// Structure syntax is correct; Collect the validator object
		$validator = $validator->value;

		// Use the validator to validate data
		$data = $validator->validate($ex['data']);

		// Check if data is valid
		if($data->valid) {
			// Print validated data
			echo '<pre>Validated Data: ', PHP_EOL, print_r($data->value, true), '</pre>';
		} else {
			// Print error msg
			echo '<pre>Data Error: ', PHP_EOL, $data->value, '</pre>';
		}
	}
	echo '</div>';
}
?>
</body>
</html>