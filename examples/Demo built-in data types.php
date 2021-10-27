<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Data-Validator - Example - Demostrating built-in data types</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once 'helper_functions.php';

$examples = [
	['heading' => 'All buit-in data types'],
	[
		'struct' => [
			"?bool" => "bool",
			"?email" => "email",
			"?float" => "float",
			"?hex" => "hex",
			"?int" => "int",
			"?ipv4" => "ipv4",
			"?ipv6" => "ipv6",
			"?mac" => "mac",
			"?mixed" => "mixed",
			"?number" => "number",
			"?string" => "string",
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
	] ,
	['heading' =>"<code>bool</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'bool',
		'data' => [true, false, 1, 0, 'true', 'false', '', 10, -1, 'hello', null]
	],
	['heading' =>"<code>email</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'email',
		'data' => ['test@site.com', 'hello', 1, false, null]
	],
	['heading' =>"<code>float</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'float',
		'data' => [1.2, 5, -0.1, '10', '-15.25', true, false, null]
	],
	['heading' =>"<code>hex</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'hex',
		'data' => ['ff', 'AB', 'eDf', 10, false, 'hello', null]
	],
	['heading' =>"<code>int</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'int',
		'data' => [1, '2', '4.5', 5.6, '89275', true, false, '', null]
	],
	['heading' =>"<code>ipv4</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'ipv4',
		'data' => ['192.168.1.1', '127.0.0.1:8000', true, '2001:db8:3333:4444:5555:6666:7777:8888']
	],
	['heading' =>"<code>ipv6</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'ipv6',
		'data' => ['2001:db8:3333:4444:5555:6666:7777:8888', '192.168.1.1']
	],
	['heading' =>"<code>mac</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'mac',
		'data' => ['12-34-56-78-9A-BC', 'uyadoiuf']
	],
	['heading' =>"<code>mixed</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'mixed',
		'data' => [1, '2', 3.4, '5.6', true, false, null, [1, 2, 3]]
	],
	['heading' =>"<code>number</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'number',
		'data' => [1, 2.3. '4', '5.6', true, false, null, '']
	],
	['heading' =>"<code>string</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'string',
		'data' => ["hello world 😃\u{1f60e}", 1, true, null]
	],
	['heading' =>"<code>timestamp</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'timestamp',
		'data' => ['today', 'yesterday', '5 days ago', '01-Jan-2021', '2021-10-27T12:07:57Z', 1609459200]
	],
	['heading' =>"<code>unsigned</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'unsigned',
		'data' => [10, 0, -1, '5', '-4', 7.8]
	],
	['heading' =>"<code>url</code> type"],
	[
		'repeat' => 'single',
		'struct' => 'url',
		'data' => ['https://test.com', 'http://www.test.com', 'www.test.com']
	]
];
echo_example_list($examples);
?>
</body>
</html>