<?php namespace ExampleApp; ?>
<style>
body {
	padding: 0.5rem;
	font-family: Arial, sans-serif;
	display: grid;
	gap: 1rem;
}
.full-width {
	grid-column:1 / -1;
}
h3 {
	margin: 0;
	margin-bottom: 0.5rem;
}
pre {
	margin:0;
	background-color: #f9f9f9;
	padding:0.5rem 0.8rem;
	border:1px solid #ccc;
	border-radius: 0.3rem;
	white-space:pre-wrap;
	word-wrap:break-word;
}
details {
	border:1px solid #ccc;
	border-radius: 0.3rem;
	&[open] > summary {
		border-bottom:1px solid #ccc;
	}
	& > div {
		padding: 0.5rem;
		display: grid;
		gap: 0.5rem;
		grid-template-columns: 1fr 1fr;
	}
}
summary {
	padding:0.5rem 0.8rem;
	cursor:pointer;
	background: #f9f9f9;
	font-weight: bold;
	font-size: 1.2rem;
}
</style><?php

require_once '../vendor/autoload.php';

use Krishna\DataValidator\ComplexException;
use Krishna\DataValidator\Validator;

function example(string $title, string $type, mixed ...$values) {
	echo '<details><summary class="full-width">Example: ', htmlentities($title), '</summary><div>';
	$structure = [
		'value' => $type
	];
	echo '<div class="full-width"><h3>Structure:</h3>';
	var_dump($structure);
	echo "</div>";
	try {
		$dv = new Validator($structure);
		foreach($values as $value) {
			$test = [
				'value' => $value
			];
			echo '<div><h3>Value:</h3>';
			var_dump($test);
			echo "</div>";

			echo '<div><h3>Result:</h3>';
			var_dump($dv->validate($test));
			echo "</div>";
		}
	} catch (ComplexException $th) {
		echo '<div class="full-width"><h3>Error:</h3>';
		var_dump($th->getInfo());
		// var_dump($th->getMessage());
		echo "</div>";
	} catch (\Throwable $th) {
		echo '<div class="full-width"><h3>Error:</h3>';
		var_dump($th->getMessage());
		echo "</div>";
	}
	echo '</div></details>';
}

example(
	'bool type', 'bool',
	true,
	false,
	'',
	0,
	1,
	'1',
	'0',
	'yes',
	'no',
	null,
	'null',
);

example(
	'email type', 'email',
	'abc@example.com',
	123,
	'abc@example',
	''
);

example(
	'float type', 'float',
	123.45,
	'123.45',
	123,
	''
);

example(
	'hex type', 'hex',
	'1234567890abcdefABCDEF',
	'10',
	10
);

example(
	'int type', 'int',
	123,
	'123',
	123.45,
	''
);


example(
	'ipv4 type', 'ipv4',
	'192.168.1.1'
);

example(
	'ipv6 type', 'ipv6',
	'2001:0db8:85a3:0000:0000:8a2e:0370:7334',
);

example(
	'json type', 'json',
	'{"key":"value"}',
	'[1, 2, 3]',
	''
);

example(
	'json64 type', 'json64',
	'eyJrZXkiOiJ2YWx1ZSJ9',
	'WzEsIDIsIDNd',
);

example(
	'mac type', 'mac',
	'00:00:5e:00:53:01',
	'00-00-5e-00-53-01',
	'0000.5e00.5301',
);

example(
	'mixed type', 'mixed',
	'Hello, World!',
	123,
	123.45,
	true,
	false,
	null,
	'',
	[]
);

example(
	'null type', 'null',
	null,
	'null',
	'',
	0,
	1,
);

example(
	'number type', 'number',
	123,
	'123',
	123.45,
	'123.45',
	''
);

example(
	'string type', 'string',
	'Hello, World!',
	123
);

example(
	'string64 type', 'string64',
	'SGVsbG8sIFdvcmxkIQ',
);

example(
	'timestamp type', 'timestamp',
	
	// Formats
	'2024-06-15T14:07:11Z', // UTC
	'2024-06-15T14:07:11+0000', // ISO-8601
	'2024-06-15T14:07:11+00:00', // ISO-8601
	'Sat 15 Jun 2024 14:07:11 +0000', // RFC 2822
	'Saturday, 15-Jun-24 14:07:11 UTC', // RFC 850
	'Sat, 15 Jun 24 14:07:11 +0000', // RFC 1036
	'Sat, 15 Jun 2024 14:07:11 +0000', // RFC 1123
	'Sat, 15 Jun 24 14:07:11 +0000', // RFC 822
	'2024-06-15T14:07:11+00:00', // RFC 3339
	'2024-06-15T14:07:11+00:00', // ATOM
	'Saturday, 15-Jun-2024 14:07:11 UTC', // COOKIE
	'Sat, 15 Jun 2024 14:07:11 +0000', // RSS
	'2024-06-15T14:07:11+00:00', // W3C
	
	// Failed formats
	// '1718460431', // Unix Epoch
	// 1718460431, // Unix Epoch
	// '2024-15-06 14:07:11', // YYYY-DD-MM HH:MM:SS
	// '2024-15-06 02:07:11 PM', // YYYY-DD-MM HH:MM:SS am/pm
	// '15-06-2024 14:07:11', // DD-MM-YYYY HH:MM:SS
	// '06-15-2024 14:07:11' // MM-DD-YYYY HH:MM:SS


	// PHP values
	'now',
	'today',
	'yesterday',
	'tomorrow',
);

example(
	'timestamp_utc type', 'timestamp_utc',
	
	// Formats
	'2024-06-15T14:07:11Z', // UTC
	'2024-06-15T14:07:11+0000', // ISO-8601
	'2024-06-15T14:07:11+00:00', // ISO-8601
	'Sat 15 Jun 2024 14:07:11 +0000', // RFC 2822
	'Saturday, 15-Jun-24 14:07:11 UTC', // RFC 850
	'Sat, 15 Jun 24 14:07:11 +0000', // RFC 1036
	'Sat, 15 Jun 2024 14:07:11 +0000', // RFC 1123
	'Sat, 15 Jun 24 14:07:11 +0000', // RFC 822
	'2024-06-15T14:07:11+00:00', // RFC 3339
	'2024-06-15T14:07:11+00:00', // ATOM
	'Saturday, 15-Jun-2024 14:07:11 UTC', // COOKIE
	'Sat, 15 Jun 2024 14:07:11 +0000', // RSS
	'2024-06-15T14:07:11+00:00', // W3C
	
	// Failed formats
	// '1718460431', // Unix Epoch
	// 1718460431, // Unix Epoch
	// '2024-15-06 14:07:11', // YYYY-DD-MM HH:MM:SS
	// '2024-15-06 02:07:11 PM', // YYYY-DD-MM HH:MM:SS am/pm
	// '15-06-2024 14:07:11', // DD-MM-YYYY HH:MM:SS
	// '06-15-2024 14:07:11' // MM-DD-YYYY HH:MM:SS


	// PHP values
	'now',
	'today',
	'yesterday',
	'tomorrow',


	// Timezones
	'2024-06-15T14:42:52+00:00:10', // UTC
	'2024-06-15T23:42:52+09:00:10', // Asia/Tokyo
	'2024-06-15T20:12:52+05:30:10', // Asia/Kolkata
	'2024-06-15T22:42:52+08:00:10', // Asia/Shanghai
	'2024-06-15T18:42:52+04:00:10', // Asia/Dubai
	'2024-06-15T23:42:52+09:00:10', // Asia/Seoul
	'2024-06-15T07:42:52-07:00:10', // US/Pacific
	'2024-06-15T10:42:52-04:00:10', // US/Eastern
	'2024-06-15T09:42:52-05:00:10', // US/Central
	'2024-06-15T08:42:52-06:00:10', // US/Mountain
	'2024-06-15T15:42:52+01:00:10', // Europe/London
	'2024-06-15T16:42:52+02:00:10', // Europe/Paris
	'2024-06-15T16:42:52+02:00:10', // Europe/Berlin
	'2024-06-15T17:42:52+03:00:10', // Europe/Moscow
	'2024-06-16T00:42:52+10:00:10', // Australia/Sydney
);

example(
	'unsigned type', 'unsigned',
	123,
	'123',
	123.45,
	'123.45',
	'',
	-123,
	'-123',
);

example(
	'url type', 'url',
	'https://example.com',
	'http://example.com',
	'example.com',
	'',
	'example',
);

example(
	'url64 type', 'url64',
	'aHR0cHM6Ly9leGFtcGxlLmNvbQ',
	'aHR0cDovL2V4YW1wbGUuY29t',
);

example(
	'UUID type', 'uuid',
	'123e4567-e89b-12d3-a456-426614174000',
	'xyz'
);

example(
	'Multiple alternative types', 'null|int|string',
	'Hello, World!',
	null,
	'null',
	'',
	123
);