Raspberry Webcam, Weather, Timelapse and optional digital picture frame
=======================================================================

A digital picture frame (DPF with hacked firmware, how to do this -> look to https://gist.github.com/kriedel/5780328) is connected to one USB connector and display the picture from the camera module with local time,
date and weather information getting from an RSS weather feed. Every 30 seconds a serially numbered picture is saved with the same information in it.
Every 600 pictures a timelapse video is generated from the photos with avconv (5 hours in one minute video). You can also watch the last picture and if the last video
in a webbrowser from everywhere. Additionally the data is saved in a JSON file for use with highchart-library in the website. The website based on the Bootstrap framework, a lot of templates with different colors are available.

Note: Sometimes I have problems with the DPF, it seems to block the USB connectors for other devices like keyboard and wlan sticks. Also the required lcd4linux
is not so stable as I hoped. So a php script version without DPF is in the repository. The script with DPF support is now camera_dpf.php.

* Blogentry on element14: http://www.element14.com/community/groups/raspberry-pi-accessories/blog/2013/06/12/pi-camera-board-photography-competition-timelapse-webcam-and-dpf
