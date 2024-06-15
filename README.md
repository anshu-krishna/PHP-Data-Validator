# PHP Data-Validator
A PHP library for simplifying complexly-structured-data validation.

***Note: Version >=2.0 is incompatible with older versions.***

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
	* `timestamp_utc` : String containing a timestamp (it will be converted to UTC timezone).\
					eg: '2021-01-31', 'today', 'yesterday', '01-Jan-2021', 'January 1, 2021 05:00:10 AM GMT+05:30', '2024-06-15T20:12:52+05:30:10', etc.
	* `unsigned` : Int >= 0
	* `url` : String containing a URL
	* `uuid` : String containing a UUIDv4

* Custom data types can also be added. For example see `./examples`

* Multiple alternative data types can be set for a data item. eg: `'int|float|null'`, `'ipv4|ipv6'`, etc.

* Supports Ranger/Formatter for validated data

## Example:
```php
<?php
require_once 'vendor/autoload.php';

use Krishna\DataValidator\ComplexException;
use Krishna\DataValidator\OutOfBoundAction;
use Krishna\DataValidator\Validator;
try {
   $dv = new Validator([
      'a' => 'int',
      // a is required and must be an integer
      
      '?b' => 'float',
      // b is optional and must be a float
      
      '??c' => 'unsigned',
      // c is optional and must be an unsigned integer;
      // if not provided, it will be set to null
      
      'd' => 'null|int|uuid',
      // d is required and must be an integer or a UUIDv4 or null
   ], OutOfBoundAction::Trim);
}
catch (ComplexException $e) {
   echo 'Unable to create validator';
   var_dump($th->getInfo());
   die();
}

$result = $dv->validate([
   'a' => 10,
   'b' => '20.5',
   'd' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
]);
var_dump($result);
/*
Output:
object(Krishna\DataValidator\Returner)[14]
public readonly mixed 'value' => 
array (size=4)
   'a' => int 10
   'b' => float 20.5
   'c' => null
   'd' => string 'f47ac10b-58cc-4372-a567-0e02b2c3d479' (length=36)
public readonly mixed 'error' => null
public readonly bool 'valid' => boolean true
*/

$result = $dv->validate([
   'a' => '10',
   'b' => 20,
   'c' => 5,
   'd' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
]);
var_dump($result);
/*
Output:
object(Krishna\DataValidator\Returner)[7]
public readonly mixed 'value' => 
array (size=4)
   'a' => int 10
   'b' => float 20
   'c' => int 5
   'd' => string 'f47ac10b-58cc-4372-a567-0e02b2c3d479' (length=36)
public readonly mixed 'error' => null
public readonly bool 'valid' => boolean true
*/

$result = $dv->validate([
   'a' => '0x10',
   'd' => 20,
   'e' => 30,
]);
var_dump($result);
/*
Output:
object(Krishna\DataValidator\Returner)[17]
public readonly mixed 'value' => 
array (size=3)
   'a' => int 16
   'c' => null
   'd' => int 20
public readonly mixed 'error' => null
public readonly bool 'valid' => boolean true
*/

$result = $dv->validate([
   'b' => 'abc',
   'c' => 'def',
   'd' => 'null',
]);
var_dump($result);
/*
Output:
object(Krishna\DataValidator\Returner)[7]
public readonly mixed 'value' => null
public readonly mixed 'error' => 
object(Krishna\DataValidator\ErrorReader)[14]
   public 0 => string '[a]: Missing' (length=12)
   public 1 => string '[b]: Expected 'float'' (length=21)
   public 2 => string '[c]: Expected 'unsigned'' (length=24)
public readonly bool 'valid' => boolean false
*/
```
___

### See `./examples` for more info.
### Documentation/Examples is under construction