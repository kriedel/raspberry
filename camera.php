#!/usr/bin/php

<?php

//initialize variables
$filename = "/home/pi/test.jpg";
$FontBold = "/home/pi/FreeMonoBold.ttf";
$Font     = "/home/pi/verdana.ttf";
//$counter=1;
//$countervideo=1;
$oldtime=0;
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

//Initialize hardware and delete old files
//exec ("sudo modprobe -q w1-gpio");
//exec ("sudo modprobe -q w1-therm");
//exec("rm -f /home/pi/images/*.jpg");
//$handle = popen ("sudo ./rfm12","r");
//exec("mv -f /home/pi/*.flv /home/pi/video/");
exec ("sudo modprobe spi-bcm2708");
exec ("sudo insmod /home/pi/rfm12b-linux-master/rfm12b.ko");
$handle = popen ("sudo /home/pi/rfm12b-linux-master/examples/bin/rfm12b_read", "r");

while(1)
{
   // get and save image for webbrowser, interval 10 minutes
    if (time()>=$oldtime+600)
    {   
        // save current time stamp
	    $oldtime = time(); 
        //$get_temperature = round(substr(exec("cat /sys/bus/w1/devices/28-*/w1_slave"),-5) / 1000, 1);  //get one DS18B20 temperature value
        $get_sensorvalue2 = round(substr(exec("cat /home/pi/sensorvalue2"),0, 3),0);  //get value from rfm12 sensor 2 
        $get_sensorvalue3 = round(substr(exec("cat /home/pi/sensorvalue3"),0, 3),0);  //get value from rfm12 sensor 3
        
        //echo($get_sensorvalue2);
        
        $output=shell_exec("raspistill -w 1280 -h 960 -t 1000 -rot 270 -q 90 -o ".$filenameweb."");
	    //exec("raspistill -t 500 -rot 270 -q 90 --awb sun -o ".$filenameweb.""); // higher resolution of camera
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
	/* Array content

	 * echo '<pre>' . print_r($arr, true) . '</pre>'; 
	 *  

	 * Array 

	 * ( 
	 *     [0] => Temperature: 86+ØF / 30+ØC 
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
	//ImageTTFText($im, 40, 0, 50, 1900, $text_color, $Font, $date.", ".$time);		
        
        if ($wetter['temp']!=''and $wetter['hum']!='')
            ImageTTFText($im, 20, 0, 475, 920, $text_color, $Font, $get_sensorvalue2." °C, ".$wetter['hum']." %, ".$wetter['press'].", ".$wetter['cond']);
        
        
        //imagejpeg ($im, "/home/pi/images/img".$counter.".jpg");
        imagejpeg ($im, $filenameweb);
	
        copy ($filenameweb, "/var/www/media/image.jpg");
        // optional save values in csv file
        //$date = date('d.m.y');
        //$time = date('H:i:s');
        //$filehandle = fopen("/home/pi/data.txt","a");
        //fwrite($filehandle, $date.",".$time.",".$get_temperature.",".$wetter['temp'].",".$wetter['hum']."\n");
        //fclose($filehandle);
        
        // save weather values in JSON file for evaluate with highcharts only if data correct every 10 minutes
        if ($wetter['temp']!=''and $wetter['hum']!='')
        {
            $filehandle = fopen("/var/www/data.json","r+");
            $unixtime = (time()+7200)*1000;
            fseek($filehandle, -1, SEEK_END);
            fwrite($filehandle, ",\n[".$unixtime.",".$get_sensorvalue2.",".$wetter['hum'].",".$get_sensorvalue3."]]");
            fclose($filehandle);
        }
        
        //$counter++;
       
        imagedestroy($im);
    }
    
    
    //convert pictures to flv video
   /* if ($counter==600)
    {
       //$filenamevideo="/home/pi/video".$countervideo.".flv";
       //exec("avconv -r 10 -y -f image2 -i /home/pi/images/img%d.jpg ".$filenamevideo."");
       //exec("rm -f /home/pi/images/*.jpg");
       $counter=1;
       //$countervideo++;
       //copy($filenamevideo, "/var/www/media/outfile.flv");
    }*/
sleep (10);

}

?>
