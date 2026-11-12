<?php

require_once "include.php";

//Récupération de l'adresse mail de l'entreprise 
$mailEntreprise = $_GET['mailEntreprise'];

//Connexion à la base de données
$pdo = new PDO('mysql:host=lakartxela.iutbayonne.univ-pau.fr;dbname=mhild001_bd;charset=utf8', 'mhild001_bd', 'mhild001_bd');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Récupéation des informations de l'entreprise à partir de la base de données
$sql = $pdo->prepare("SELECT * FROM entreprise WHERE mailEntreprise = ?");
$sql->execute([$mailEntrprise]);
$entreprise = $sql->fetch(PDO::FETCH_ASSOC);

//Transmission des données au template
echo $twig->render('pageInformationEntreprise.html.twig', [
    'entreprise' => $entreprise
]);

?>