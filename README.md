# PHP Data-Validator
A PHP library for simplifying complexly-structured-data validation.

### Installation
```
composer require anshu-krishna/data-validator
```
## Features:
* Supported types:
	* `bool` : Boolean value
	* `email` : String containing an email address
	* `float` : Float value
	* `hex` : String containing a hex value
	* `int` : Int value
	* `ipv4` : String containing an IPv4 address
	* `ipv6` : String containing an IPv6 address
	* `mac` : String containing a MAC address
	* `mixed` : Any value
	* `null` : Null value
	* `number` : Int or Float value
	* `string` : String value
	* `timestamp` : String containing a timestamp.\
					eg: '2021-01-31', 'today', 'yesterday', '01-Jan-2021', 'January 1, 2021 05:00:10 AM GMT+05:30', etc.
	* `unsigned` : Int >= 0
	* `url` : String containing a URL

* Custom data types can also be added. For example see `./examples`

* Multiple alternative data types can be set for a data item. eg: `'int|float|null'`, `'ipv4|ipv6'`, etc.

* Supported Ranger/Formatter
	* `num_range` : Works with any numeric data. Sets range (min, max) of the value
	* `str_range` : Works with any string data. Sets range (min, max) of the string length
	* `str_lower` : Works with any string data. Transforms the string to lowercase
	* `str_upper` : Works with any string data. Transforms the string to uppercase
	* `str_title` : Works with any string data. Transforms the string to titlecase

* Custom ranger/formater can also be added. For example see `./examples`

* Data-structure can of nested style (Upto depth **512**). For example see `./examples`


## Basic Example:
```php
<?php
require_once 'vendor/autoload.php';
use Krishna\DataValidator\Validator;

/**********************************
 * Setup
 **********************************/
$structure = [
	"name" => "string",			// Name is a string
	"id" => "int|email",			// ID can be an int or email address
	"age" => "int@num_range(18,45)",	// Age is an int. Age must be in range [18,45]
	"?nums" => ["int|float"],		// Nums is optional. Nums is an array contaning int and float items

	// Links is optional. Links is an array of 'item' arrays. 'item' has Title and Link property
	"?links" => [[
		"title" => "string",		// Title is a string
		"link" => "url",		// Link is a URL
	]]
];

// Create a validator object for the given data-structure
$validator = Validator::create($structure);
if(!$validator->valid) {
	// Structure syntax is not correct; Printing the error msg;
	echo '<pre>Validator Error:', PHP_EOL, $validator->value, '</pre>';
	exit(0);
}

// Structure syntax is correct; Collect the validator object
$validator = $validator->value;

/**********************************
 * Validations
 **********************************/
$data1 = [
	"name" => "Test User1",
	"id" => "12345",
	"age" => 20,
	"nums" => [1, 2, 3, 4.5, 6.7, 8],
	"links" => [[
		"title" => "Link 1",
		"link" => "http://site1.com/user/12345"
	], [
		"title" => "Link 2",
		"link" => "http://site2.com/user/12345"
	]]
];

// Use the validator to validate data1
$data1 = $validator->validate($data1);

// Check if data1 is valid
if($data1->valid) {
	// Print validated data1
	echo '<pre>Validated Data1: ', PHP_EOL, print_r($data1->value, true), '</pre>';
} else {
	// Print error msg
	echo '<pre>Data1 Error: ', PHP_EOL, $data1->value, '</pre>';
}
/////////////////////////////////////////////////////////////////
$data2 = [
	"name" => "Test User2",
	"id" => "user2@site2.com",
	"age" => "30", // Integer value in a string
	// Optional nums not present
	"links" => [[
		"title" => "Link 1",
		"link" => "http://site1.com/user/56789"
	]]
];
// Use the validator to validate data2
$data2 = $validator->validate($data2);
// Check if data2 is valid
if($data2->valid) {
	// Print validated data2
	echo '<pre>Validated Data2: ', PHP_EOL, print_r($data2->value, true), '</pre>';
} else {
	// Print error msg
	echo '<pre>Data2 Error: ', PHP_EOL, $data2->value, '</pre>';
}
/////////////////////////////////////////////////////////////////
$data3 = ['id' => 78912, "age" => 70]; // Lots of missing data. Age is out of range.
// Use the validator to validate data3
$data3 = $validator->validate($data3);
// Check if data3 is valid
if($data3->valid) {
	// Print validated data3
	echo '<pre>Validated Data3: ', PHP_EOL, print_r($data3->value, true), '</pre>';
} else {
	// Print error msg
	echo '<pre>Data3 Error: ', PHP_EOL, $data3->value, '</pre>';
}
/////////////////////////////////////////////////////////////////
$data4 = [
	"name" => true, // Bool insteadof a string
	"age" => 80,
	"nums" => [1, false, 20, 'hello']
	// Optional links not present
];
// Use the validator to validate data4
$data4 = $validator->validate($data4);
// Check if data4 is valid
if($data4->valid) {
	// Print validated data4
	echo '<pre>Validated Data4: ', PHP_EOL, print_r($data4->value, true), '</pre>';
} else {
	// Print error msg
	echo '<pre>Data4 Error: ', PHP_EOL, $data4->value, '</pre>';
}
```
### Output
```
Validated Data1: 
Array
(
    [name] => Test User1
    [id] => 12345
    [age] => 20
    [nums] => Array
        (
            [0] => 1
            [1] => 2
            [2] => 3
            [3] => 4.5
            [4] => 6.7
            [5] => 8
        )

    [links] => Array
        (
            [0] => Array
                (
                    [title] => Link 1
                    [link] => http://site1.com/user/12345
                )

            [1] => Array
                (
                    [title] => Link 2
                    [link] => http://site2.com/user/12345
                )

        )

)

Validated Data2: 
Array
(
    [name] => Test User2
    [id] => user2@site2.com
    [age] => 30
    [links] => Array
        (
            [0] => Array
                (
                    [title] => Link 1
                    [link] => http://site1.com/user/56789
                )

        )

)

Data3 Error: 
[
    "[name]: Missing",
    "[age]: Expected range [18, 45]"
]

Data4 Error: 
[
    "[name]: Expected 'string'",
    "[id]: Missing",
    "[age]: Expected range [18, 45]",
    "[nums][1]: Expected 'int|float'",
    "[nums][3]: Expected 'int|float'"
]
```
___
### See DOC file for more info.