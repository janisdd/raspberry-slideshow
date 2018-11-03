#!/bin/sh

# this is called on the client
#ssh pi@192.168.178.18 'bash /home/pi/Desktop/start_slides.sh'

# this is on the raspberry, located at /home/pi/Desktop/start_slides.sh
# screen is not needed only longer
#screen
vcgencmd display_power 1
sleep 3
export DISPLAY=:0
sleep 1
#the next line this might needed if you wan't prevent browser caching
#rm -r  ~/.cache/chromium/Default/Cache/*
chromium-browser -kiosk http://localhost > /dev/null 2>&1 &
