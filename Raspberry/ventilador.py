#!/usr/bin/python
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setup(27, GPIO.OUT)

while True:
  try:
    tFile = open('/sys/class/thermal/thermal_zone0/temp')
    temp = float(tFile.read())
    tempC = temp/1000
    print tempC
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
    
GPIO.cleanup()