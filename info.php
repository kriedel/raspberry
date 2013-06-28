#!/usr/bin/php
<?php
$date = new DateTime('2000-01-01', new DateTimeZone('Pacific/Nauru'));
echo time() . "\n";

$date->setTimezone(new DateTimeZone('Pacific/Chatham'));
echo time() . "\n"
?>
