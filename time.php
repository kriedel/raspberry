#!/usr/bin/php

<?php

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

$filename = "/home/pi/img.png";
$FontBold = "/home/pi/FreeMonoBold.ttf";
$Font     = "/home/pi/verdana.ttf";

while( 1 )
{
$time = date('H:i');
$date = date('D d.m.y');
$date = strtr($date, $trans);
$cpu_temperature = round(exec("cat /sys/class/thermal/thermal_zone0/temp ") / 1000, 1)."°C";

$im = imagecreate(320, 240);
$bg_color = ImageColorAllocate ($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 255, 255, 0);

// time
ImageTTFText($im, 75, 0, 5, 80, $text_color, $FontBold, $time);
// date
ImageTTFText($im, 35, 0, 10, 140, $text_color, $Font, $date);
// temp
ImageTTFText($im, 35, 0, 10, 200, $text_color, $Font, $cpu_temperature);


imagepng($im, $filename);
imagedestroy($im);

rename($filename, "/home/pi/test.png");
sleep(5);

}

?>
