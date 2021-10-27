<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Data-Validator - Example - Demostrating Ranger/Formatter</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<h2>Ranger/Formatter</h2>
<?php
require_once 'helper_functions.php';

$examples = [
	['heading' => '<code>num_range(min,max)</code>'],
	[
		'repeat' => 'single',
		'struct' => 'int@num_range(5,10)',
		'data' => [4, 5, 7, 10, 11]
	],
	[
		'repeat' => 'single',
		'struct' => 'float@num_range(1.5,1.7)',
		'data' => [1.49, 1.5, 1.6, 1.7, 1.71]
	],
	['heading' => '<code>num_range(min)</code>'],
	[
		'repeat' => 'single',
		'struct' => 'int@num_range(5)',
		'data' => [4, 5, 7]
	],
	['heading' => '<code>num_range(null,max)</code>'],
	[
		'repeat' => 'single',
		'struct' => 'int@num_range(null,5)',
		'data' => [4, 5, 7]
	],
	['heading' => '<code>str_range(min,max)</code>'],
	[
		'repeat' => 'single',
		'struct' => 'string@str_range(2,5)',
		'data' => ['a', 'ab', 'abc', 'abcde', 'abcdef']
	],
	['heading' => '<code>str_range(min)</code>'],
	[
		'repeat' => 'single',
		'struct' => 'string@str_range(3)',
		'data' => ['a', 'ab', 'abc', 'abcd', 'abcde']
	],
	['heading' => '<code>str_range(null,max)</code>'],
	[
		'repeat' => 'single',
		'struct' => 'string@str_range(null,3)',
		'data' => ['ab', 'abc', 'abcd']
	],
	['heading' => 'Using multiple ranger/formatter <code>str_range and str_upper</code>'],
	[
		'repeat' => 'single',
		'struct' => 'string@str_range(2,5)@str_upper',
		'data' => ['a', 'ab', "abc\u{1f60e}", 'abcde', 'abcdef']
	]
];
echo_example_list($examples);
?>
</body>
</html>