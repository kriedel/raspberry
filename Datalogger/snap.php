<?php

//initialize variables
$filename = "/home/pi/test.jpg";
$FontBold = "/home/pi/FreeMonoBold.ttf";
$Font     = "/home/pi/verdana.ttf";
$filenameweb = "/home/pi/image.jpg";
$url = "http://rss.wunderground.com/auto/rss_full/global/stations/10575.xml?units=metric";

//translation array for weather feed
$trans = array(
    'Monday'    => 'Montag',
    'Tuesday'   => 'Dienstag',
    'Wednesday' => 'Mittwoch',
    'Thursday'  => 'Donnerstag',
    'Friday'    => 'Freitag',
    'Saturday'  => 'Samstag',
    'Sunday'    => 'Sonntag',
    'Mon'       => 'Mo',
    'Tue'       => 'Di',
    'Wed'       => 'Mi',
    'Thu'       => 'Do',
    'Fri'       => 'Fr',
    'Sat'       => 'Sa',
    'Sun'       => 'So',
    'January'   => 'Januar',
    'February'  => 'Februar',
    'March'     => 'M+ñrz',
    'May'       => 'Mai',
    'June'      => 'Juni',
    'July'      => 'Juli',
    'October'   => 'Oktober',
    'December'  => 'Dezember',
);

//get picture from raspicam
$output=exec("raspistill -w 1280 -h 960 -t 500 -rot 270 -q 90 -o ".$filenameweb."");
//$output=exec("raspistill -t 500 -rot 270 -q 90 --awb sun -o ".$filenameweb.""); // maximal resolution of camera

$im = imagecreatefromjpeg($filenameweb);
        
// save informations in picture for webbrowser
// place, date, time
$bg_color = ImageColorAllocate ($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 255, 140, 0);

//get rss feed values
$content = implode("", file($url));

preg_match_all("/\<item>(.*?)\<\/item\>/si", $content, $results); 
preg_match("/\<description><!\[CDATA\[(.*?)\<img/si", $results[1][0], $desc); 

$arr = explode(" | ",$desc[1]);
preg_match("/Temperature: (.*?)&deg/si", $arr[0], $str);

$wetter['temp'] = $str['1']; 

preg_match("/Humidity: (.*?)%/si", $arr[1], $str); 
$wetter['hum'] = $str['1']; 

preg_match("/Wind Direction: (.*)/si", $arr[4], $str); 
$wetter['windr'] = $str['1']; 

preg_match("/Wind Speed: (.*)/si", $arr[5], $str); 
$wetter['windg'] = $str['1'];

preg_match("/Pressure: (.*)/si", $arr[2], $str); 
$wetter['press'] = $str['1'];

preg_match("/Conditions: (.*)/si", $arr[3], $str); 
$wetter['cond'] = $str['1'];  

$date = date('l, d.m.y');
$time = date('H:i');
$date = strtr($date, $trans);

ImageTTFText($im, 20, 0, 50, 920, $text_color, $Font, $date.", ".$time);
//ImageTTFText($im, 40, 0, 50, 1900, $text_color, $Font, $date.", ".$time);

if ($wetter['temp']!=''and $wetter['hum']!='')
   ImageTTFText($im, 20, 0, 475, 920, $text_color, $Font, $wetter['temp']." °C, ".$wetter['hum']." %, ".$wetter['press'].", ".$wetter['cond']);


//imagejpeg ($im, "/home/pi/images/img".$counter.".jpg");
imagejpeg ($im, $filenameweb);
copy ($filenameweb, "/var/www/media/image.jpg");

$time = date('y_m_d_H_i_s');
copy ($filenameweb, "/home/pi/images/img_".$time.".jpg");

imagedestroy($im);
?>
