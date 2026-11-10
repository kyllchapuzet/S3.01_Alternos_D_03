<?php
require_once 'include.php';

$erreur = null;

echo $twig->render('page_creationDeCompte.html.twig', ['erreur' => $erreur]);
?>