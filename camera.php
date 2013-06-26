#!/usr/bin/php

<?php

$filename = "/home/pi/test.jpg";
$FontBold = "/home/pi/FreeMonoBold.ttf";
$Font     = "/home/pi/verdana.ttf";
$counter=1;
$countervideo=1;
$filenameweb = "/home/pi/image.jpg";
$url = "http://rss.wunderground.com/auto/rss_full/global/stations/10575.xml?units=metric";

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
    'March'     => 'März',
    'May'       => 'Mai',
    'June'      => 'Juni',
    'July'      => 'Juli',
    'October'   => 'Oktober',
    'December'  => 'Dezember',
);

//Initialize hardware and delete old files
exec ("sudo modprobe -q w1-gpio");
exec ("sudo modprobe -q w1-therm");
exec("sudo rm -f /home/pi/images/*.jpg");
exec ("sudo lcd4linux");

//get rss feed values
$content = implode("", file($url));

preg_match_all("/\<item>(.*?)\<\/item\>/si", $content, $results); 
preg_match("/\<description><!\[CDATA\[(.*?)\<img/si", $results[1][0], $desc); 

$arr = explode(" | ",$desc[1]);

/* 

 * echo '<pre>' . print_r($arr, true) . '</pre>'; 
 *  

 * Array 

 * ( 
 *     [0] => Temperature: 86ÝF / 30ÝC 
 *     [1] => Humidity: 74% 
 *     [2] => Pressure: 29.83in / 1010hPa 

 *     [3] => Conditions: Partly Cloudy 
 *     [4] => Wind Direction: ESE 
 *     [5] => Wind Speed: 5mph / 7km/h 

 *     [6] => Updated: 10:00 PM PHT 
 * ) 
 */ 
 
preg_match("/Temperature: (.*?)&deg/si", $arr[0], $str); 
$wetter['temp'] = $str['1']; 

preg_match("/Humidity: (.*?)%/si", $arr[1], $str); 
$wetter['hum'] = $str['1']; 

while(1)
{
$time = date('H:i');
$date = date('d.m.y');

$cpu_temperature = round(exec("cat /sys/class/thermal/thermal_zone0/temp") / 1000, 1)."°C";  //get cpu temperature value
//last line of w1_slave: f7 01 4b 46 7f ff 09 10 fd t=31437
$get_temperature = round(substr(exec("cat /sys/bus/w1/devices/28-*/w1_slave"),-5) / 1000, 1);  //get one DS18B20 temperature value

// get image with low quality for digital picture frame
exec("raspistill -w 320 -h 240 -t 1000 -rot 270 -q 50 -o ".$filename."");

$im = imagecreatefromjpeg($filename);

$bg_color = ImageColorAllocate ($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 255, 255, 0);

// time
ImageTTFText($im, 75, 0, 5, 80, $text_color, $FontBold, $time);
// date
ImageTTFText($im, 35, 0, 5, 130, $text_color, $Font, $date);
// cpu temperature
//ImageTTFText($im, 25, 0, 10, 200, $text_color, $Font, "CPU: ".$cpu_temperature);
// DS18B20 temperature
ImageTTFText($im, 25, 0, 5, 180, $text_color, $Font, "IN:".$get_temperature." °C");
// temperature and humidity from RSS feed
ImageTTFText($im, 25, 0, 5, 220, $text_color, $Font, "OUT:".$wetter['temp']." °C/".$wetter['hum']." %");

imagepng($im, $filename);
rename($filename, "/home/pi/test.png");

// get and save higher quality image for timelapse and webbrowser
if ($counter%5==0)
{
sleep(2);
$imagecounter = $counter/5;
$filenameweb = "/home/pi/image.jpg";

exec("raspistill -w 1280 -h 960 -t 1000 -rot 270 -o ".$filenameweb."");
$im = imagecreatefromjpeg($filenameweb);

// save informations in picture for webbrowser
// place, date, time
$bg_color = ImageColorAllocate ($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 255, 255, 0);

$date = date('l, d.m.y');
$date = strtr($date, $trans);

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

ImageTTFText($im, 20, 0, 50, 920, $text_color, $Font, $date.", ".$time);
ImageTTFText($im, 20, 0, 450, 920, $text_color, $Font, $wetter['temp']." °C, ".$wetter['hum']." %, ".$wetter['press'].", ".$wetter['cond']);

imagejpeg ($im, "/home/pi/images/img".$imagecounter.".jpg");
imagejpeg ($im, $filenameweb);
exec("sudo cp ".$filenameweb." /var/www/media/image.jpg");

// save values in csv file
//$date = date('d.m.y');
//$time = date('H:i:s');
//$filehandle = fopen("/home/pi/data.txt","a");
//fwrite($filehandle, $date.",".$time.",".$get_temperature.",".$wetter['temp'].",".$wetter['hum']."\n");
//fclose($filehandle);

// save weather values in JSON file for evaluate with highcharts
$filehandle = fopen("/var/www/data.json","r+");
$unixtime = (time()+7200)*1000;
fseek($filehandle, -1, SEEK_END);
fwrite($filehandle, ",\n[".$unixtime.",".$wetter['temp'].",".$wetter['hum'].",".$get_temperature."]]");
fclose($filehandle);

}

imagedestroy($im);

$counter++;

//convert pictures to flv video 
if ($counter==200)
{
   $filenamevideo="/home/pi/video".$countervideo.".flv";
   exec("avconv -y -f image2 -i /home/pi/images/img%d.jpg -r 10 ".$filenamevideo."");
   exec("sudo rm -f /home/pi/images/*.jpg");
   $counter=1;
   $countervideo++;
   exec("sudo cp ".$filenamevideo." /var/www/media/outfile.flv");
}

sleep(8);

}

?>
