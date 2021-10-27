<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Data-Validator - Example - Demostrating custom data type</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once 'helper_functions.php';

use Krishna\DataValidator\Returner;
use Krishna\DataValidator\Types\IntType;

class DaysOfWeek implements \Krishna\DataValidator\TypeInterface {
	// Converts a valid day value to integer between [0,6];
	public static function validate($value, bool $allow_null = false): Returner {
		$num = IntType::validate($value);
		if($num->valid && 0 <= $num->value && $num->value <=6) {
			return Returner::valid($num->value);
		}
		if(is_string($value)) {
			$short = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
			$long = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
			$value = strtolower($value);
			foreach([$short, $long] as $list) {
				if(($pos = array_search($value, $list)) !== false) {
					return Returner::valid($pos);
				}
			}
		}
		return Returner::invalid('DaysOfWeek');
	}
}

$examples = [
	['heading' => 'Custom datatpe - Days of week'],
	[
		'repeat' => 'single',
		'struct' => DaysOfWeek::class,
		'data' => ['sunday', 'mon', 3, 10, false, 'TUE']
	]
];
echo_example_list($examples);
?>
</body>
</html>