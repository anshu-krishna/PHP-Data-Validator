<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Data-Validator - Example - Demostrating custom ranger/formatter</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once 'helper_functions.php';

use Krishna\DataValidator\Returner;

class DaysOfWeek extends \Krishna\DataValidator\AbstractRangerFormatter {
	// Converts int to DayofWeek string
	public function exec($result): Returner {
		if(0 <= $result && $result <= 6) {
			$list = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
			return Returner::valid($list[$result]);
		}
		return Returner::invalid('DaysOfWeek');
	}
}

$examples = [
	['heading' => 'Custom ranger/formatter - Days of week'],
	[
		'repeat' => 'single',
		'struct' => 'int@' . DaysOfWeek::class,
		'data' => [-1, 0, 1, 5, 6, 7, 8]
	]
];
echo_example_list($examples);
?>
</body>
</html>