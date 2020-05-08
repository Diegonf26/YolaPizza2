#!/bin/python  
#comando para ver temp /opt/vc/bin/vcgencmd measure_temp
# Simple script for shutting down the raspberry Pi at the press of a button.  

#**Adding it to startup
#sudo gedit /etc/rc.local

#** Add the following line before the #fi at the end of the file.
#sudo python /home/pi/Scripts/shutdown_pi.py &

import RPi.GPIO as GPIO  
import time  
import os  
 
GPIO.setmode(GPIO.BCM)  
GPIO.setup(3, GPIO.IN, pull_up_down = GPIO.PUD_UP)  
 
# Our function on what to do when the button is pressed  
def Shutdown(channel):  
    os.system("sudo shutdown -h now")  
 
# Add our function to execute when the button pressed event happens  
GPIO.add_event_detect(3, GPIO.FALLING, callback = Shutdown, bouncetime = 2000)  
 
# Now wait!  
while 1:  
    time.sleep(1)  