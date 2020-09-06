# L'application se commpose de deux grandes parties 
# la premiere est le Client ./Client
Dans cette partie on a les fichiers nécessaires pour le front End c'est à dire tous les fichiers
qui ont en relation avec l'interface web ./Client/Php
ainsi que la base de données ./Client/DB
# La deuxieme partie Serveur ./Serveur se compose de deux grandes parties
La premiere c'est la partie python où on a tous les fichiers de lacquisition ./Serveur/Acquisition/acquisition.py ce fichier permet 
la génération des valeurs aléatoires dans un fichier nommé ./temperature.txt
et on a aussi le fichier ./Serveur/main.py c'est celui qui gere le processus d'insertion des données dans la base de données
l'application s'execute une fois la carte raspberry est branché parce qu'on a ajouté le main.py dans le crontab
pour qu'il s'execute periodiquement l'interface Web aussi se met à jour une fois une valeur s'ajooute dans la base de données
