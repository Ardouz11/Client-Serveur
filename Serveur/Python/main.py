#!/usr/bin/env python
import datetime
import sys
import sqlite3
import os
import time
def get_temp(i):
	with open('/www/Projet/Serveur/Python/Acquisition/temperature.txt', 'r') as f:
		s = f.readline()
		c= sum(1 for line in open('/www/Projet/Serveur/Python/Acquisition/temperature.txt'))
		print "Name of the file: ", f.name
    		while s and i<c-1:
        		tempvalue= f.readlines()[i]
			return tempvalue
def log_temperature(temp):
	conn=sqlite3.connect('/www/Projet/Client/DB/tempbase.db')
	curs=conn.cursor()
	curs.execute("INSERT INTO temps values(datetime('now'), (?))", (temp,))
# commit the changes
	conn.commit()
	conn.close()
def display_data():
	conn=sqlite3.connect('/www/Projet/Client/DB/tempbase.db')
	curs=conn.cursor()
	for row in curs.execute("SELECT * FROM temps"):
		print str(row[0])+" "+str(row[1])
	conn.close()
def main(i):
	print('---------------------------------------------------------Acquisition--------------------------------------------')
	execfile( "/www/Projet/Serveur/Python/Acquisition/acquisition.py")
	print('----------------------------------------------------------Insertion---------------------------------------------')
	f = open("/www/Projet/Serveur/Python/Acquisition/temperature.txt", "r")
	c= sum(1 for line in open('/www/Projet/Serveur/Python/Acquisition/temperature.txt'))
	while True and i<c-1:
# get the temperature from the device file
		temperature = get_temp(i)
		if temperature != None:
			print "temperature="+str(temperature)
		else:
			temperature = get_temp(i)
			print "temperature="+str(temperature)
# Store the temperature in the database
		log_temperature(temperature)
		i=i+1
# display the contents of the database
		#display_data()	
		time.sleep(2)
	print("--------------------------------------------------------Successfully inserted--------------------------------------")
	f.close()
i=0
if __name__=="__main__":
	main(i)
