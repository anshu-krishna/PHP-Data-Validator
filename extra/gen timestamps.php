<?php

$zones = [
	'UTC',
	'Asia/Tokyo', 'Asia/Kolkata', 'Asia/Shanghai', 'Asia/Dubai', 'Asia/Seoul',
	'US/Pacific', 'US/Eastern', 'US/Central', 'US/Mountain',
	'Europe/London', 'Europe/Paris', 'Europe/Berlin', 'Europe/Moscow',
	'Australia/Sydney',
];

# yyyy-mm-ddThh:mm:ss+zzzz
$fmt = 'Y-m-d\TH:i:sP';

foreach($zones as $zone) {
	$dt = new DateTime('now', new DateTimeZone($zone));
	echo "'", $dt->format($fmt), "', // ", $zone, PHP_EOL;
}