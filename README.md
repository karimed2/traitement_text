###Projet d'Analyse de Texte - Nuage de Mots

Ce projet permet d'analyser un texte ou un fichier texte et de générer un nuage de mots en utilisant la technique de stemmer pour normaliser les mots. L'application offre une interface web où les utilisateurs peuvent soit entrer du texte directement, soit télécharger un fichier .txt. Le texte est ensuite traité pour extraire les mots, les normaliser en utilisant un algorithme de stemming, puis un nuage de mots est généré pour visualiser la fréquence de chaque mot.

###Fonctionnalités
- Saisie de texte : Permet à l'utilisateur de copier et coller un texte pour l'analyser.
- Téléchargement de fichier : Permet de télécharger un fichier .txt pour l'analyser.
- Nuage de mots : Affiche un nuage de mots basé sur les mots les plus fréquents du texte analysé.
- Filtrage des mots vides : Ignore les mots courants comme "le", "la", "de", etc. pendant l'analyse.
- Normalisation avec Stemmer : Les mots sont réduits à leur racine pour éviter les variations (ex. "mangeant" -> "manger").
###Technologies Utilisées
- PHP : Langage serveur pour le traitement des fichiers et du texte.
- D3.js : Bibliothèque JavaScript pour générer le nuage de mots.
- d3-cloud : Extension de D3.js utilisée pour créer un nuage de mots interactif.
- Wamania\Snowball : Bibliothèque PHP pour le stemming des mots en français.
