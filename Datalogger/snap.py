#!/usr/bin/python
import sys
import subprocess
import os

try:
    os.remove("/var/www/media/image.jpg")
except OSError:
    pass


error = subprocess.check_output("raspistill -w 1280 -h 960 -t 1000 -rot 270 -q 90 -o /var/www/media/image.jpg", shell=True, stderr=subprocess.STDOUT)

sys.stdout.write( "Content-Type: image/jpeg;\n\n" + file("/var/www/media/image.jpg","rb").read() )

