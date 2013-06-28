<?php
//Max Resolution 2592 x 1944
$width = 1920;
$height = 1080;
$output = shell_exec("sudo ./wc_pid.sh '-w ".$width."' '-h ".$height."'");
header('Content-Type: image/jpeg');
readfile("wc_image.jpg");
?>
