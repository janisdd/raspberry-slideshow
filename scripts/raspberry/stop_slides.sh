#!/bin/sh

# this is called on the client
#ssh pi@192.168.178.18 'bash /home/pi/Desktop/stop_slides.sh'

# this is on the raspberry at /home/pi/Desktop/stop_slides.sh
# screen is not needed only longer
#pkill screen
pkill chromium-browse
sleep 2
vcgencmd display_power 0
sleep 3