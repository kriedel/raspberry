#!/usr/bin/php

<?php

//Wetterdaten
$url = "http://rss.wunderground.com/auto/rss_full/global/stations/10575.xml?units=metric"; 
$content = implode("", file($url)); 

preg_match_all("/\<item>(.*?)\<\/item\>/si", $content, $results); 
preg_match("/\<description><!\[CDATA\[(.*?)\<img/si", $results[1][0], $desc); 

$arr = explode(" | ",$desc[1]); 

 
 echo '<pre>' . print_r($arr, true) . '</pre>'; 
  
/* Array 
 ( 
      [0] => Temperature: 86İF / 30İC 
      [1] => Humidity: 74% 
      [2] => Pressure: 29.83in / 1010hPa 
      [3] => Conditions: Partly Cloudy 
      [4] => Wind Direction: ESE 
      [5] => Wind Speed: 5mph / 7km/h 
      [6] => Updated: 10:00 PM PHT 
  ) 
*/  
  
preg_match("/Temperature: (.*?)&deg/si", $arr[0], $str); 
$wetter['temp'] = $str['1']."°C"; 

preg_match("/Humidity: (.*)/si", $arr[1], $str); 
$wetter['hum'] = $str['1']; 

preg_match("/Wind Direction: (.*)/si", $arr[4], $str); 
$wetter['windr'] = $str['1']; 

preg_match("/Wind Speed: (.*)/si", $arr[5], $str); 
$wetter['windg'] = $str['1'];

preg_match("/Pressure: (.*)/si", $arr[2], $str); 
$wetter['press'] = $str['1']; 
 

echo '<pre>' . print_r($wetter, true) . '</pre>'; 

echo $wetter['temp']; 

?>