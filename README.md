Raspberry Camera and DPF
========================

A digital picture frame (DPF with hacked firmware) is connected to one USB connector and display the picture from the camera module with local time,
date and weather information getting from an RSS weather feed. Every 30 seconds a serially numbered picture is saved with the same information in it.
Every 200 pictures a timelapse video is generated from the photos with avconv. You can also watch the last picture and if you wish the last video
in a webbrowser from everywhere. Additionally the data is saved in a JSON file for use with highchart-library in the browser file.

* Blogentry: http://www.element14.com/community/groups/raspberry-pi-accessories/blog/2013/06/12/pi-camera-board-photography-competition-timelapse-webcam-and-dpf
