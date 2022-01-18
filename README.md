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
	* `unsigned` : Int >= 0
	* `url` : String containing a URL

* Custom data types can also be added. For example see `./examples`

* Multiple alternative data types can be set for a data item. eg: `'int|float|null'`, `'ipv4|ipv6'`, etc.

* Supports Ranger/Formatter for validated data
___

### See `./examples` for more info.
### Documentation/Examples is under construction