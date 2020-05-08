
#comando para ver temp /opt/vc/bin/vcgencmd measure_temp
# Simple script for shutting down the raspberry Pi at the press of a button.  

#**Adding it to startup
#sudo gedit /etc/rc.local

#** Add the following line before the #fi at the end of the file.
#sudo python /home/pi/Scripts/script.py &
#(sleep 10;sudo python /home/pi/folder/yourscript.py) &


#!/bin/python 

import RPi.GPIO as GPIO  
import time  
import os  

GPIO.setmode(GPIO.BCM)
GPIO.setup(3, GPIO.IN, pull_up_down = GPIO.PUD_UP)  
GPIO.setup(27, GPIO.OUT)

# Our function on what to do when the button is pressed  
def Shutdown(channel):  
    os.system("sudo shutdown -h now")  
 
# Add our function to execute when the button pressed event happens  
GPIO.add_event_detect(3, GPIO.FALLING, callback = Shutdown, bouncetime = 2000)  
 
# Now wait!  
while 1:
  try:
    tFile = open('/sys/class/thermal/thermal_zone0/temp')
    temp = float(tFile.read())
    tempC = temp/1000
    #print tempC
    if tempC > 40:
      GPIO.output(27, 1)
      #print "HOT"
    else:
      GPIO.output(27, 0)
      #print "COLD"

  except:
    tFile.close()
    GPIO.cleanup()
    exit
  
  time.sleep(1) 

GPIO.cleanup()  	

