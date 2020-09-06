import RPi.GPIO as GPIO
import time
import sys
GPIO.setwarnings(False)
#initialisation de la numerotation et des E/S
GPIO.setmode(GPIO.BCM) #numerotation des port
GPIO.setup(21,GPIO.OUT, initial = GPIO.LOW)
#on fait clignoter la LED
if int(sys.argv[1])==1:
	GPIO.output(21,GPIO.HIGH)    
else:
	 GPIO.output(21,GPIO.LOW)
