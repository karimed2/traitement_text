<?php
// Affichage des erreurs pour déboguer
require 'vendor/autoload.php';

use Wamania\Snowball\StemmerFactory;

$stemmer = StemmerFactory::create('french');

// Initialiser les variables
$videlist = [];
$wordsList = [];
$wordCounts = [];
$stemmedResults = [];
$error = null;
$finalWordList = [];

// Gérer le texte saisi
if (isset($_POST['text']) && !empty($_POST['text'])) {
    $fileContent = $_POST['text'];  // Utiliser le texte saisi par l'utilisateur

} elseif (isset($_FILES['file'])) {
    // Gérer les erreurs de téléchargement de fichier
    $fileError = $_FILES['file']['error'];

    if ($fileError === UPLOAD_ERR_NO_FILE) {
        $error = "Aucun fichier n'a été sélectionné.";
    } elseif ($fileError !== UPLOAD_ERR_OK) {
        $error = "Erreur de téléchargement du fichier. Code d'erreur : " . $fileError;
    } else {
        // Si le fichier est téléchargé correctement, récupérer son contenu
        $fileContent = file_get_contents($_FILES['file']['tmp_name']);
    }
} else {
    $error = "Aucun texte ou fichier n'a été fourni.";
}

if (!empty($fileContent)) {
    // Lire le fichier des mots vides
    $filePath1 = "C:/Users/Eddebi/Desktop/projet-web/mots-vide.txt"; // Fichier des mots vides
    if (file_exists($filePath1)) {
        $contenu = file_get_contents($filePath1);
        $videlist = preg_split('/\s*,\s*/', $contenu);
        $videlist = array_map('strtolower', $videlist);
    } else {
        $error = "Le fichier des mots vides n'existe pas à l'emplacement spécifié : $filePath1";
    }

    // Découper le texte en mots
    $wordsList = preg_split('/[\s,;.!?"\’""\'":()]+/', $fileContent, -1, PREG_SPLIT_NO_EMPTY);
    $wordsList = array_map('strtolower', $wordsList);

    // Filtrer les mots de longueur > 2 et supprimer les mots vides
    $wordsList = array_filter($wordsList, function($word) use ($videlist) {
        return strlen($word) > 2 && !in_array($word, $videlist);
    });

    // Réindexer le tableau
    $wordsList = array_values($wordsList);

    // Construire une correspondance entre mots originaux et stemmés
    $originalToStemmed = [];
    foreach ($wordsList as $word) {
        $stemmed = $stemmer->stem($word);
        if (!isset($originalToStemmed[$stemmed])) {
            $originalToStemmed[$stemmed] = [];
        }
        $originalToStemmed[$stemmed][] = $word;
    }

    // Construire une liste des mots stemmés
    $stemmedWordsList = array_map(function($word) use ($stemmer) {
        return $stemmer->stem($word);
    }, $wordsList);

    // Calculer les fréquences des mots stemmés
    $stemmed_count = array_count_values($stemmedWordsList);
    arsort($stemmed_count);

    // Filtrer les mots ayant une fréquence > 2
    $filteredStemmedCount = array_filter($stemmed_count, function($count) {
        return $count > 1;
    });

    // Reconstruire les mots normaux pour les mots stemmés filtrés
    foreach ($filteredStemmedCount as $stemmedWord => $count) {
        if (isset($originalToStemmed[$stemmedWord])) {
            // Sélectionner le mot original avec la taille maximale
            $originalWord = array_reduce($originalToStemmed[$stemmedWord], function($carry, $item) {
                return (strlen($item) > strlen($carry)) ? $item : $carry;
            }, "");
            // Ajouter le mot normal et sa fréquence au résultat final
            $finalWordList[$originalWord] = $count;
        }
    }
}
 

$finalWordListJson = json_encode($finalWordList);
include "projethtml.html";
?>

