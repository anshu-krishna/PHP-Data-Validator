<?php
require_once '../vendor/autoload.php';
use Krishna\DataValidator\Validator;

function var_echo($var) {
	// return print_r($var, true);
	return var_export($var, true);
}

function echo_example(array $struct, array $data) {
	echo '<div>';
	echo '<pre>Structure: ' , var_echo($struct) . '</pre>';
	echo '<pre>Data to be Checked: ' , var_echo($data) . '</pre>';

	// Create a validator object for the given data-structure
	$validator = Validator::create($struct);
	if(!$validator->valid) {
		// Structure syntax is not correct; Printing the error msg;
		echo '<pre>Validator Error: ', $validator->value, '</pre>';
	} else {
		// Structure syntax is correct; Collect the validator object
		$validator = $validator->value;

		// Use the validator to validate data
		$data = $validator->validate($data);

		// Check if data is valid
		if($data->valid) {
			// Print validated data
			echo '<pre>Validated Data: ', var_echo($data->value), '</pre>';
		} else {
			// Print error msg
			echo '<pre>Data Error: ', $data->value, '</pre>';
		}
	}
	echo '</div>';
};

function echo_example_list(array $examples) {
	foreach($examples as $ex) {
		if(isset($ex['heading'])) {
			echo '<h2>', $ex['heading'], '</h2>';
			echo '<div><span>Structure</span><span>Data</span><span>Result</span></div>';
			continue;
		}
		if(($repeat = $ex['repeat'] ?? null) !== null) {
			if($repeat === 'multi') {
				foreach($ex['data'] as $data) {
					echo_example($ex['struct'], $data);
				}
			} else {
				$struct = ['item' => $ex['struct']];
				foreach($ex['data'] as $data) {
					echo_example($struct, ['item' => $data]);
				}
			}
			continue;
		}
		echo_example($ex['struct'], $ex['data']);
	}
}