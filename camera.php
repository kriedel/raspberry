#!/usr/bin/php

<?php

//initialize variables
$filename = "/home/pi/test.jpg";
$FontBold = "/home/pi/FreeMonoBold.ttf";
$Font     = "/home/pi/verdana.ttf";
$counter=1;
$countervideo=1;
$oldtime=time();
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
exec("sudo mv -f /home/pi/*.flv /home/pi/video/");

while(1)
{
   // get and save higher quality image for timelapse and webbrowser, interval 30 seconds
    if (time()>=$oldtime+30)
    {   
        // save current time stamp
	$oldtime = time(); 
        $filenameweb = "/home/pi/image.jpg";
	$get_temperature = round(substr(exec("cat /sys/bus/w1/devices/28-*/w1_slave"),-5) / 1000, 1);  //get one DS18B20 temperature value
        
        exec("raspistill -w 1280 -h 960 -t 500 -rot 270 -q 90 --awb sun -o ".$filenameweb."");
        $im = imagecreatefromjpeg($filenameweb);
        
        // save informations in picture for webbrowser
        // place, date, time
        $bg_color = ImageColorAllocate ($im, 0, 0, 0);
        $text_color = imagecolorallocate($im, 255, 255, 0);
        
        //get rss feed values
        $content = implode("", file($url));
        
        preg_match_all("/\<item>(.*?)\<\/item\>/si", $content, $results); 
        preg_match("/\<description><!\[CDATA\[(.*?)\<img/si", $results[1][0], $desc); 
        
        $arr = explode(" | ",$desc[1]);
	/* Array content

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
        
        if ($wetter['temp']!=''and $wetter['hum']!='')
            ImageTTFText($im, 20, 0, 450, 920, $text_color, $Font, $wetter['temp']." °C, ".$wetter['hum']." %, ".$wetter['press'].", ".$wetter['cond']);
        
        
        imagejpeg ($im, "/home/pi/images/img".$counter.".jpg");
        imagejpeg ($im, $filenameweb);
        exec("sudo cp ".$filenameweb." /var/www/media/image.jpg");
        
        // optional save values in csv file
        //$date = date('d.m.y');
        //$time = date('H:i:s');
        //$filehandle = fopen("/home/pi/data.txt","a");
        //fwrite($filehandle, $date.",".$time.",".$get_temperature.",".$wetter['temp'].",".$wetter['hum']."\n");
        //fclose($filehandle);
        
        // save weather values in JSON file for evaluate with highcharts only if data correct every 10 minutes
        if ($wetter['temp']!=''and $wetter['hum']!='' and $counter%20==0)
        {
            $filehandle = fopen("/var/www/data.json","r+");
            $unixtime = (time()+7200)*1000;
            fseek($filehandle, -1, SEEK_END);
            fwrite($filehandle, ",\n[".$unixtime.",".$wetter['temp'].",".$wetter['hum'].",".$get_temperature."]]");
            fclose($filehandle);
        }
        
        $counter++;
       
        imagedestroy($im);
    }
    
    
    //convert pictures to flv video
    if ($counter==600)
    {
       $filenamevideo="/home/pi/video".$countervideo.".flv";
       exec("avconv -r 10 -y -f image2 -i /home/pi/images/img%d.jpg ".$filenamevideo."");
       exec("sudo rm -f /home/pi/images/*.jpg");
       $counter=1;
       $countervideo++;
       exec("sudo cp ".$filenamevideo." /var/www/media/outfile.flv");
    }
sleep (1);

}

?>
