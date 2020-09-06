#!/usr/bin/env python
import sys
import random
import time
i=0
f = open("/www/Projet/Serveur/Python/Acquisition/temperature.txt", "w")
while i<30:
	temp = random.randint(10,38); 
	print(temp, "added to file temperaure.txt")
	f.write(str(temp) + "\n" )
	time.sleep(2)
	i=i+1
print("30 valeurs ont ete ajoute a votre fichier de simulation du capteur ")
f.close()

