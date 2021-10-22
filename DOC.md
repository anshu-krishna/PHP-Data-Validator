# Data-Validator Documention

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
